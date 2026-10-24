<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// If not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pull session data into clean variables
$fullname      = htmlspecialchars($_SESSION['fullname']      ?? 'User');
$email         = htmlspecialchars($_SESSION['email']         ?? 'user@email.com');
$phone         = htmlspecialchars($_SESSION['phone']         ?? '+91 98765 43210');
$role          = htmlspecialchars($_SESSION['role']          ?? 'user');
$profile_image = htmlspecialchars($_SESSION['profile_image'] ?? '');
$firstname     = htmlspecialchars(explode(' ', trim($_SESSION['fullname'] ?? 'User'))[0]);
$initials      = strtoupper(mb_substr($_SESSION['fullname'] ?? 'U', 0, 1));

// ===== DATABASE CONNECTION =====
// Try multiple possible paths for dbConfig.php
$dbPaths = [
    'includes/dbConfig.php',
    '../includes/dbConfig.php',
    '../../includes/dbConfig.php',
    dirname(__FILE__) . '/includes/dbConfig.php',
    dirname(__FILE__) . '/../includes/dbConfig.php',
    $_SERVER['DOCUMENT_ROOT'] . '/includes/dbConfig.php',
    $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/dbConfig.php'
];

$dbFound = false;
foreach ($dbPaths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $dbFound = true;
        break;
    }
}

// If dbConfig not found, create a direct connection
if (!$dbFound) {
    // Try to connect directly - update these with your actual credentials
    $host = 'localhost';
    $username = 'logintoloans_mobile'; // Change this
    $password = 'your_password'; // Change this
    $database = 'logintoloans_mobile'; // Change this
    
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

// Check if connection exists
if (!isset($conn) || !$conn) {
    die("❌ Database connection failed. Please check your configuration.");
}

// Create table if it doesn't exist
$tableCheck = $conn->query("SHOW TABLES LIKE 'tbl_loan_offers'");
if ($tableCheck->num_rows == 0) {
    $createTable = "CREATE TABLE IF NOT EXISTS `tbl_loan_offers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `offer_title` varchar(255) NOT NULL,
        `short_description` varchar(500) DEFAULT NULL,
        `interest_rate` decimal(5,2) NOT NULL,
        `min_amount` decimal(15,2) NOT NULL,
        `max_amount` decimal(15,2) NOT NULL,
        `loan_term` int(11) NOT NULL COMMENT 'in months',
        `loan_type` varchar(100) NOT NULL,
        `full_description` text,
        `lender_name` varchar(255) DEFAULT NULL,
        `cta_text` varchar(100) DEFAULT 'Apply Now',
        `is_featured` tinyint(1) DEFAULT 0,
        `is_urgent` tinyint(1) DEFAULT 0,
        `promo_code` varchar(50) DEFAULT NULL,
        `image_path` varchar(500) DEFAULT NULL,
        `status` tinyint(1) DEFAULT 1,
        `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
        `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_loan_type` (`loan_type`),
        KEY `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $conn->query($createTable);
    
    // Insert sample data with image paths
    $sampleData = "INSERT INTO `tbl_loan_offers` 
        (`offer_title`, `short_description`, `interest_rate`, `min_amount`, `max_amount`, `loan_term`, `loan_type`, `lender_name`, `is_featured`, `is_urgent`, `image_path`) VALUES
        ('Personal Loan', 'Quick approval for salaried individuals', 9.6, 50000, 2500000, 60, 'Personal', 'HDFC Bank', 1, 0, '1782301596_6a3bc39c9699e.jpg'),
        ('Home Loan', 'Dream home financing with low EMI', 8.4, 500000, 50000000, 240, 'Home', 'SBI', 1, 0, 'home-loan.jpg'),
        ('Car Loan', 'New and used car financing', 7.8, 100000, 5000000, 84, 'Car', 'ICICI Bank', 0, 1, 'car-loan.jpg'),
        ('Gold Loan', 'Instant loan against gold jewelry', 9.2, 10000, 5000000, 36, 'Gold', 'Muthoot Finance', 0, 0, 'gold-loan.jpg'),
        ('Business Loan', 'Working capital for your business', 10.5, 100000, 10000000, 60, 'Business', 'Axis Bank', 0, 0, 'business-loan.jpg'),
        ('Education Loan', 'Study abroad or in India', 8.9, 100000, 5000000, 120, 'Education', 'Bank of Baroda', 0, 0, 'education-loan.jpg')";
    $conn->query($sampleData);
}

// Fetch all active loan offers
$loan_offers = [];
$featured_offers = [];
$loans_by_type = [];

$query = "SELECT * FROM tbl_loan_offers WHERE status = 1 ORDER BY is_featured DESC, created_at DESC";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $loan_offers[] = $row;
    }
}

// Group loans by type
foreach ($loan_offers as $offer) {
    $type = $offer['loan_type'];
    if (!isset($loans_by_type[$type])) {
        $loans_by_type[$type] = [];
    }
    $loans_by_type[$type][] = $offer;
}

// Get featured offers
$featured_offers = array_filter($loan_offers, function($offer) {
    return isset($offer['is_featured']) && $offer['is_featured'] == 1;
});

// ===== UPDATED FUNCTION: Get image from CRM =====
function getLoanImage($image_path) {
    if (empty($image_path)) {
        return '';
    }
    
    // If it's already a full URL, return it
    if (filter_var($image_path, FILTER_VALIDATE_URL)) {
        return $image_path;
    }
    
    // Base URL for CRM uploads
    $crm_base_url = 'https://crm.logintoloans.com/uploads/loan_offers/1782301596_6a3bc39c9699e.jpg';
    
    // Try CRM URL first
    $crm_url = $crm_base_url . $image_path;
    
    // Optional: Check if file exists on CRM (you may need to enable allow_url_fopen)
    // Comment this out if it causes performance issues
    /*
    $headers = @get_headers($crm_url);
    if ($headers && strpos($headers[0], '200') !== false) {
        return $crm_url;
    }
    */
    
    // Return CRM URL directly (assumes file exists)
    return $crm_url;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#001653">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>All Loan Offers | Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        :root {
            --primary: #001653;
            --primary-dark: #000d3a;
            --primary-light: #002080;
            --secondary: #FE8402;
            --secondary-light: #ff9933;
            --secondary-dark: #e67300;
            --text-primary: #001653;
            --text-secondary: #4a4a6a;
            --text-tertiary: #8888aa;
            --background: #f5f6fa;
            --white: #ffffff;
            --border: #e8eaf0;
            --shadow: 0 2px 8px rgba(0, 22, 83, 0.06);
            --shadow-md: 0 4px 16px rgba(0, 22, 83, 0.10);
            --shadow-lg: 0 8px 30px rgba(0, 22, 83, 0.15);
            --gradient-primary: linear-gradient(135deg, #001653 0%, #002080 50%, #0033aa 100%);
            --gradient-secondary: linear-gradient(135deg, #FE8402 0%, #ff9933 50%, #e67300 100%);
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
        }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--secondary); border-radius: 10px; }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
            min-height: 100vh;
            position: relative;
            padding-bottom: 75px;
        }
        .page { display: none; animation: fadeIn 0.3s ease; }
        .page.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Hero Section */
        .hero-section {
            position: relative; padding: 30px 20px 35px;
            color: white; overflow: hidden;
            border-radius: 0 0 30px 30px;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: var(--gradient-primary);
        }
        .hero-section .overlay { 
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
            background: linear-gradient(135deg, rgba(0,22,83,0.85) 0%, rgba(0,32,128,0.75) 100%); 
            z-index: 1; 
        }
        .hero-section .content { position: relative; z-index: 2; }

        .sub-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        .sub-header i {
            cursor: pointer;
            font-size: 20px;
            color: white;
            padding: 8px;
            border-radius: 10px;
            background: rgba(255,255,255,0.1);
            transition: background 0.2s;
        }
        .sub-header i:hover {
            background: rgba(255,255,255,0.2);
        }
        .sub-header span {
            font-size: 18px;
            font-weight: 700;
            color: white;
        }
        .sub-header p {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }

        .hero-stats {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        .hero-stats .stat {
            display: flex;
            flex-direction: column;
        }
        .hero-stats .stat .number {
            font-size: 22px;
            font-weight: 800;
            color: var(--secondary);
        }
        .hero-stats .stat .label {
            font-size: 11px;
            color: rgba(255,255,255,0.7);
        }

        .section-title {
            padding: 0 16px;
            margin: 24px 0 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-title h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }
        .section-title .badge {
            background: var(--secondary);
            color: white;
            padding: 2px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Featured Cards */
        .featured-scroll {
            padding: 0 16px;
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 8px;
        }
        .featured-scroll::-webkit-scrollbar { height: 3px; }
        .featured-scroll::-webkit-scrollbar-thumb { background: var(--secondary); border-radius: 10px; }

        .featured-card {
            min-width: 220px;
            background: var(--white);
            border-radius: 16px;
            padding: 0;
            scroll-snap-align: start;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            flex-shrink: 0;
            cursor: pointer;
            transition: all 0.2s;
            overflow: hidden;
            border-top: 3px solid var(--secondary);
        }
        .featured-card:active { transform: scale(0.97); }
        
        .featured-card .card-image {
            width: 100%;
            height: 120px;
            background-size: cover;
            background-position: center;
            background-color: #e8eaf0;
            position: relative;
        }
        .featured-card .card-image .overlay-gradient {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(transparent, rgba(0,0,0,0.5));
        }
        .featured-card .card-body {
            padding: 12px 14px 14px;
        }
        .featured-card .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }
        .featured-card .card-top h4 { font-size: 14px; font-weight: 700; color: var(--primary); }
        .featured-card .card-top .tag {
            font-size: 9px;
            background: var(--secondary);
            color: white;
            padding: 2px 10px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .featured-card .rate {
            font-size: 20px;
            font-weight: 800;
            color: var(--secondary);
        }
        .featured-card .rate small {
            font-size: 12px;
            font-weight: 400;
            color: var(--text-tertiary);
        }
        .featured-card .amount {
            font-size: 13px;
            color: var(--text-secondary);
            margin: 4px 0 8px;
        }
        .featured-card .lender {
            font-size: 11px;
            color: var(--text-tertiary);
        }

        /* Loans Grid */
        .loans-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 0 16px;
            margin-top: 16px;
            margin-bottom: 20px;
        }
        .loan-card {
            background: var(--white);
            border-radius: 16px;
            padding: 0;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
            position: relative;
        }
        .loan-card:active { transform: scale(0.97); }
        
        .loan-card .card-image {
            width: 100%;
            height: 90px;
            background-size: cover;
            background-position: center;
            background-color: #e8eaf0;
            position: relative;
        }
        .loan-card .card-body {
            padding: 10px 12px 12px;
        }
        .loan-card .loan-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(254, 132, 2, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 6px;
        }
        .loan-card .loan-icon i { font-size: 14px; color: var(--secondary); }
        .loan-card h4 {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 2px;
        }
        .loan-card .rate-info {
            font-size: 11px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .loan-card .rate-info .rate-value {
            font-weight: 700;
            color: var(--secondary);
            font-size: 13px;
        }
        .loan-card .urgent-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            background: #ef4444;
            color: white;
            font-size: 7px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 50px;
            text-transform: uppercase;
            z-index: 2;
        }
        .loan-card .promo-badge {
            position: absolute;
            bottom: 6px;
            right: 6px;
            background: var(--gradient-secondary);
            color: white;
            font-size: 7px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 50px;
            z-index: 2;
        }

        /* Image loading animation */
        .card-image.loading {
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }

        .no-loans {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-tertiary);
        }
        .no-loans i {
            font-size: 48px;
            color: var(--border);
            margin-bottom: 12px;
            display: block;
        }

        /* Modal */
        .modal {
            position: fixed; inset: 0;
            background: rgba(0,22,83,0.7);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: 0.2s;
        }
        .modal.active { visibility: visible; opacity: 1; }
        .modal-content {
            background: var(--white); border-radius: 28px;
            padding: 24px;
            width: 90%;
            max-width: 380px;
            max-height: 85vh;
            overflow-y: auto;
            animation: modalPop 0.3s ease;
            border-top: 4px solid var(--secondary);
        }
        @keyframes modalPop {
            from { transform: scale(0.9); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px; padding-bottom: 12px;
            border-bottom: 2px solid var(--border);
        }
        .modal-header h3 { font-size: 1.2rem; font-weight: 700; color: var(--primary); }
        .modal-header h3 i { color: var(--secondary); margin-right: 8px; }
        .modal-close { 
            width: 32px; height: 32px; border-radius: 50%; 
            background: var(--border); border: none; 
            cursor: pointer; font-size: 16px; 
            transition: 0.2s; 
        }
        .modal-close:hover { background: #e0e0e0; }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-secondary);
        }
        .form-group label i { margin-right: 6px; color: var(--secondary); }
        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(254,132,2,0.1);
        }
        .form-input[readonly] {
            background: #f5f5f5;
            font-weight: 600;
        }
        .apply-button {
            background: var(--gradient-secondary);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 40px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s;
            font-size: 14px;
            margin-top: 10px;
        }
        .apply-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(254,132,2,0.3);
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--primary);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 13px;
            z-index: 1000;
            transition: transform 0.3s;
            white-space: nowrap;
            max-width: 90%;
            border: 1px solid var(--secondary);
        }
        .toast.show { transform: translateX(-50%) translateY(0); }

        /* Bottom Nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            max-width: 480px;
            width: 100%;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(20px);
            display: flex;
            justify-content: space-around;
            padding: 8px 16px 20px;
            border-top: 1px solid var(--border);
            z-index: 100;
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            color: var(--text-tertiary);
            font-weight: 500;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 30px;
            transition: all 0.2s;
            text-decoration: none;
            background: transparent;
            border: none;
        }
        .nav-item i { font-size: 18px; }
        .nav-item.active {
            color: var(--primary);
            background: rgba(0,22,83,0.08);
        }
        .nav-item.active i { color: var(--secondary); }

        @media (max-width: 400px) {
            .nav-item { font-size: 9px; padding: 4px 6px; min-width: 50px; }
            .nav-item i { font-size: 16px; }
            .loans-grid { gap: 8px; }
            .featured-card { min-width: 180px; }
            .featured-card .card-image { height: 100px; }
            .loan-card .card-image { height: 70px; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== OFFERS PAGE ===== -->
    <div id="offersPage" class="page active">
        <div class="hero-section">
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header">
                    <i class="fas fa-arrow-left" onclick="window.location.href='admin/admin-dashboard.php'"></i>
                    <span>All Loan Offers</span>
                </div>
                <p style="color: rgba(255,255,255,0.8); font-size: 14px;">Compare & choose best deals from top lenders</p>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="number"><?php echo count($loan_offers); ?></span>
                        <span class="label">Total Offers</span>
                    </div>
                    <div class="stat">
                        <span class="number"><?php echo count($featured_offers); ?></span>
                        <span class="label">Featured</span>
                    </div>
                    <div class="stat">
                        <span class="number"><?php echo count($loans_by_type); ?></span>
                        <span class="label">Categories</span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($featured_offers)): ?>
        <div class="section-title">
            <h2><i class="fas fa-star" style="color: var(--secondary);"></i> Featured Offers</h2>
            <span class="badge">Top Picks</span>
        </div>
        <div class="featured-scroll">
            <?php foreach ($featured_offers as $offer): 
                // Get image from CRM
                $image_url = getLoanImage($offer['image_path'] ?? '');
                // If no image, use default placeholder
                if (empty($image_url)) {
                    $image_url = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 200\'%3E%3Crect width=\'400\' height=\'200\' fill=\'%23e8eaf0\'/%3E%3Ctext x=\'200\' y=\'110\' font-family=\'Arial\' font-size=\'24\' fill=\'%23999\' text-anchor=\'middle\'%3ENo Image%3C/text%3E%3C/svg%3E';
                }
                $image_style = "background-image: url('{$image_url}');";
            ?>
            <div class="featured-card" onclick="openLoanApplication('<?php echo addslashes($offer['offer_title']); ?>', '<?php echo addslashes($offer['short_description'] ?? ''); ?>', <?php echo $offer['id']; ?>)">
                <div class="card-image" style="<?php echo $image_style; ?>">
                    <div class="overlay-gradient"></div>
                </div>
                <div class="card-body">
                    <div class="card-top">
                        <h4><?php echo htmlspecialchars($offer['offer_title']); ?></h4>
                        <?php if (isset($offer['is_urgent']) && $offer['is_urgent']): ?>
                        <span class="tag">Urgent</span>
                        <?php endif; ?>
                    </div>
                    <div class="rate"><?php echo number_format($offer['interest_rate'], 1); ?>% <small>p.a.</small></div>
                    <div class="amount">₹<?php echo number_format($offer['min_amount']); ?> - ₹<?php echo number_format($offer['max_amount']); ?></div>
                    <div class="lender"><i class="fas fa-building"></i> <?php echo htmlspecialchars($offer['lender_name'] ?? 'Multiple Lenders'); ?></div>
                    <?php if (!empty($offer['promo_code'])): ?>
                    <div style="margin-top:4px;font-size:10px;color:var(--secondary);font-weight:600;">
                        <i class="fas fa-ticket-alt"></i> Promo: <?php echo htmlspecialchars($offer['promo_code']); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($loan_offers)): ?>
        <div class="section-title">
            <h2><i class="fas fa-th-list"></i> All Loans</h2>
            <span class="badge"><?php echo count($loan_offers); ?> offers</span>
        </div>
        <div class="loans-grid">
            <?php foreach ($loan_offers as $offer): 
                // Get image from CRM
                $image_url = getLoanImage($offer['image_path'] ?? '');
                // If no image, use default placeholder
                if (empty($image_url)) {
                    $image_url = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 400 200\'%3E%3Crect width=\'400\' height=\'200\' fill=\'%23e8eaf0\'/%3E%3Ctext x=\'200\' y=\'110\' font-family=\'Arial\' font-size=\'20\' fill=\'%23999\' text-anchor=\'middle\'%3ENo Image%3C/text%3E%3C/svg%3E';
                }
                $image_style = "background-image: url('{$image_url}');";
            ?>
            <div class="loan-card" onclick="openLoanApplication('<?php echo addslashes($offer['offer_title']); ?>', '<?php echo addslashes($offer['short_description'] ?? ''); ?>', <?php echo $offer['id']; ?>)">
                <?php if (isset($offer['is_urgent']) && $offer['is_urgent']): ?>
                <span class="urgent-badge"><i class="fas fa-bolt"></i> Urgent</span>
                <?php endif; ?>
                <div class="card-image" style="<?php echo $image_style; ?>"></div>
                <div class="card-body">
                    <div class="loan-icon">
                        <?php
                        $icon_map = [
                            'Personal' => 'fa-user-friends',
                            'Home' => 'fa-home',
                            'Car' => 'fa-car',
                            'Gold' => 'fa-coins',
                            'Business' => 'fa-briefcase',
                            'Education' => 'fa-graduation-cap',
                            'Startup' => 'fa-rocket',
                            'NRI' => 'fa-globe'
                        ];
                        $icon = 'fa-hand-holding-usd';
                        foreach ($icon_map as $key => $value) {
                            if (stripos($offer['loan_type'], $key) !== false) {
                                $icon = $value;
                                break;
                            }
                        }
                        ?>
                        <i class="fas <?php echo $icon; ?>"></i>
                    </div>
                    <h4><?php echo htmlspecialchars($offer['offer_title']); ?></h4>
                    <div class="rate-info">
                        <span class="rate-value"><?php echo number_format($offer['interest_rate'], 1); ?>%</span> p.a.
                    </div>
                    <div style="font-size:9px;color:var(--text-tertiary);margin-top:2px;">
                        ₹<?php echo number_format($offer['min_amount']); ?>
                    </div>
                    <?php if (!empty($offer['promo_code'])): ?>
                    <span class="promo-badge"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($offer['promo_code']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="no-loans">
            <i class="fas fa-inbox"></i>
            <h3>No Loan Offers Available</h3>
            <p style="font-size:13px;">Please check back later for new offers</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- ===== LOAN APPLICATION MODAL ===== -->
    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-hand-holding-usd"></i> Apply for Loan</h3>
                <button class="modal-close" onclick="closeModal('applicationModal')">✕</button>
            </div>
            <form id="loanForm" onsubmit="submitApplication(event)">
                <input type="hidden" id="loanOfferId" value="">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Loan Offer</label>
                    <input type="text" id="loanTypeDisplay" class="form-input" readonly style="background:#f5f5f5;font-weight:600;">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-info-circle"></i> Description</label>
                    <input type="text" id="loanDescription" class="form-input" readonly style="background:#f5f5f5;font-size:12px;color:var(--text-secondary);">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" class="form-input" id="applicantName" placeholder="Enter your full name" required value="<?php echo $fullname; ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" class="form-input" id="applicantEmail" placeholder="your@email.com" required value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="tel" class="form-input" id="applicantPhone" placeholder="10-digit number" pattern="[0-9]{10}" required value="<?php echo preg_replace('/[^0-9]/', '', $phone); ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-rupee-sign"></i> Monthly Income (₹)</label>
                    <input type="number" class="form-input" id="monthlyIncome" placeholder="Monthly income" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-hand-holding-usd"></i> Loan Amount (₹)</label>
                    <input type="number" class="form-input" id="loanAmount" placeholder="Required loan amount" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Loan Tenure (months)</label>
                    <select class="form-input" id="loanTenure" required>
                        <option value="">Select tenure</option>
                        <option value="12">12 months</option>
                        <option value="24">24 months</option>
                        <option value="36">36 months</option>
                        <option value="48">48 months</option>
                        <option value="60">60 months</option>
                        <option value="72">72 months</option>
                        <option value="84">84 months</option>
                        <option value="96">96 months</option>
                        <option value="108">108 months</option>
                        <option value="120">120 months</option>
                    </select>
                </div>
                <button type="submit" class="apply-button"><i class="fas fa-paper-plane"></i> Submit Application →</button>
            </form>
        </div>
    </div>

    <!-- ===== BOTTOM NAVIGATION ===== -->
    <div class="bottom-nav">
        <a href="index.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="applyleads.php" class="nav-item">
            <i class="fas fa-users"></i>
            <span>Apply Leads</span>
        </a>
        <a href="credit-score.php" class="nav-item">
            <i class="fas fa-chart-line"></i>
            <span>Credit Score</span>
        </a>
        <a href="calculator.php" class="nav-item">
            <i class="fas fa-calculator"></i>
            <span>Calculator</span>
        </a>
        <a href="offers.php" class="nav-item active">
            <i class="fas fa-tag"></i>
            <span>Offers</span>
        </a>
    </div>

    <!-- ===== TOAST ===== -->
    <div id="toast" class="toast"></div>

</div>

<script>
    function showToast(msg) {
        var t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(function() { t.classList.remove('show'); }, 3000);
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    document.querySelectorAll('.modal').forEach(function(m) {
        m.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    function openLoanApplication(loanTitle, description, offerId) {
        document.getElementById('loanTypeDisplay').value = loanTitle;
        document.getElementById('loanDescription').value = description || '';
        document.getElementById('loanOfferId').value = offerId;
        document.getElementById('monthlyIncome').value = '';
        document.getElementById('loanAmount').value = '';
        document.getElementById('loanTenure').value = '';
        document.getElementById('applicationModal').classList.add('active');
    }

    function submitApplication(e) {
        e.preventDefault();
        
        var loanTitle = document.getElementById('loanTypeDisplay').value;
        var name = document.getElementById('applicantName').value;
        var email = document.getElementById('applicantEmail').value;
        var phone = document.getElementById('applicantPhone').value;
        var income = document.getElementById('monthlyIncome').value;
        var amount = document.getElementById('loanAmount').value;
        var tenure = document.getElementById('loanTenure').value;
        var offerId = document.getElementById('loanOfferId').value;
        
        if (!amount || !tenure || !income) {
            showToast('❌ Please fill all required fields');
            return;
        }
        
        console.log('Application submitted:', {
            offer_id: offerId,
            loan_type: loanTitle,
            name: name,
            email: email,
            phone: phone,
            income: income,
            amount: amount,
            tenure: tenure
        });
        
        showToast('✅ ' + loanTitle + ' application submitted for ' + name + '! We\'ll contact you within 24 hours.');
        closeModal('applicationModal');
        document.getElementById('loanForm').reset();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var modal = document.getElementById('applicationModal');
            if (modal.classList.contains('active')) {
                modal.classList.remove('active');
            }
        }
    });

    // Image loading handler
    document.addEventListener('DOMContentLoaded', function() {
        var images = document.querySelectorAll('.card-image');
        images.forEach(function(img) {
            img.classList.add('loading');
            img.addEventListener('load', function() {
                this.classList.remove('loading');
            });
        });
    });
</script>
</body>
</html>