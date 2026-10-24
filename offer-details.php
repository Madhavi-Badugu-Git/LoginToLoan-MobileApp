<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Pull session data into clean variables
$fullname      = htmlspecialchars($_SESSION['fullname']      ?? 'User');
$email         = htmlspecialchars($_SESSION['email']         ?? 'user@email.com');
$phone         = htmlspecialchars($_SESSION['phone']         ?? '+91 98765 43210');
$role          = htmlspecialchars($_SESSION['role']          ?? 'user');
$profile_image = htmlspecialchars($_SESSION['profile_image'] ?? '');
$firstname     = htmlspecialchars(explode(' ', trim($_SESSION['fullname'] ?? 'User'))[0]);
$initials      = strtoupper(mb_substr($_SESSION['fullname'] ?? 'U', 0, 1));

// ===== DATABASE CONNECTION =====
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

if (!$dbFound) {
    $host = 'localhost';
    $username = 'logintoloans_mobile';
    $password = 'your_password';
    $database = 'logintoloans_mobile';
    
    $conn = new mysqli($host, $username, $password, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

if (!isset($conn) || !$conn) {
    die("❌ Database connection failed. Please check your configuration.");
}

// Get offer ID from URL
$offer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;


// Fetch offer details
$query = "SELECT * FROM tbl_loan_offers WHERE id = $offer_id AND status = 1";
$result = $conn->query($query);

if (!$result || $result->num_rows == 0) {
    header("Location: offers.php");
    exit;
}

$offer = $result->fetch_assoc();

// ===== FUNCTIONS =====
function getLoanImage($image_path) {
    if (empty($image_path)) {
        return '';
    }
    
    if (filter_var($image_path, FILTER_VALIDATE_URL)) {
        return $image_path;
    }
    
    $crm_base_url = 'https://crm.logintoloans.com/uploads/loan_offers/';
    return $crm_base_url . $image_path;
}

function getLoanIcon($loan_type) {
    $icon_map = [
        'Personal' => 'fa-user-friends',
        'Home' => 'fa-home',
        'Car' => 'fa-car',
        'Gold' => 'fa-coins',
        'Business' => 'fa-briefcase',
        'Education' => 'fa-graduation-cap',
        'Startup' => 'fa-rocket',
        'NRI' => 'fa-globe',
        'Testing' => 'fa-flask',
        'Bike' => 'fa-motorcycle',
        'Two Wheeler' => 'fa-motorcycle',
        'Four Wheeler' => 'fa-car'
    ];
    
    foreach ($icon_map as $key => $value) {
        if (stripos($loan_type, $key) !== false) {
            return $value;
        }
    }
    return 'fa-hand-holding-usd';
}

function formatCurrency($amount) {
    if ($amount >= 10000000) {
        return '₹' . number_format($amount / 10000000, 1) . ' Cr';
    } elseif ($amount >= 100000) {
        return '₹' . number_format($amount / 100000, 1) . ' L';
    } else {
        return '₹' . number_format($amount);
    }
}

$image_url = getLoanImage($offer['image_path'] ?? '');
$icon = getLoanIcon($offer['loan_type']);

// Include header
include('includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#001653">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title><?php echo htmlspecialchars($offer['offer_title']); ?> | Login To Loans</title>
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
            --footer-height: 70px;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
            padding-bottom: var(--footer-height);
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
            padding-bottom: 20px;
        }

        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--white);
            border: none;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            margin: 16px 16px 0;
        }
        .back-btn:hover { background: var(--border); }
        .back-btn:active { transform: scale(0.97); }
        .back-btn i { color: var(--secondary); }

        /* Details Container */
        .details-container {
            padding: 16px;
        }

        /* Image Section */
        .details-image {
            width: 100%;
            height: 220px;
            background-color: #e8eaf0;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        .details-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .details-image .fallback-icon {
            font-size: 80px;
            color: var(--secondary);
        }

        /* Badges */
        .badges-container {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }
        .badge {
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-featured {
            background: var(--secondary);
            color: white;
        }
        .badge-urgent {
            background: #ef4444;
            color: white;
        }
        .badge-promo {
            background: var(--gradient-secondary);
            color: white;
        }
        .badge-status {
            background: #22c55e;
            color: white;
        }

        /* Title */
        .details-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 4px;
        }
        .details-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }

        /* Rate */
        .rate-big {
            font-size: 36px;
            font-weight: 800;
            color: var(--secondary);
        }
        .rate-big small {
            font-size: 16px;
            font-weight: 400;
            color: var(--text-tertiary);
        }
        .amount-range {
            font-size: 16px;
            color: var(--text-secondary);
            margin: 4px 0 12px;
        }
        .lender-info {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }
        .lender-info i { color: var(--secondary); }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 16px 0;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 16px 0;
        }
        .info-item {
            background: var(--background);
            padding: 14px 16px;
            border-radius: 14px;
        }
        .info-item .label {
            font-size: 10px;
            color: var(--text-tertiary);
            text-transform: uppercase;
            font-weight: 600;
        }
        .info-item .value {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin-top: 2px;
        }
        .info-item.full-width {
            grid-column: span 2;
        }
        .info-item.highlight {
            background: linear-gradient(135deg, rgba(254,132,2,0.1) 0%, rgba(0,22,83,0.05) 100%);
        }
        .info-item.highlight .value {
            color: var(--secondary);
        }

        /* Description */
        .description {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin: 12px 0;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }
        .btn-apply {
            flex: 1;
            background: var(--gradient-secondary);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: 0.2s;
        }
        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(254,132,2,0.4);
        }
        .btn-apply:active { transform: scale(0.97); }
        .btn-share {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--white);
            cursor: pointer;
            font-size: 18px;
            color: var(--text-secondary);
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-share:hover {
            border-color: var(--secondary);
            color: var(--secondary);
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

        @media (max-width: 400px) {
            .details-image { height: 180px; }
            .details-title { font-size: 20px; }
            .rate-big { font-size: 28px; }
            .info-grid { gap: 8px; }
            .info-item { padding: 10px 12px; }
            .info-item .value { font-size: 14px; }
        }
    </style>
</head>
<body>
<div class="app-container">

    

    <!-- Details Content -->
    <div class="details-container">
        <!-- Image -->
        <div class="details-image">
            <?php if (!empty($image_url)): ?>
                <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($offer['offer_title']); ?>" 
                     onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'fas <?php echo $icon; ?> fallback-icon\'></i>'">
            <?php else: ?>
                <i class="fas <?php echo $icon; ?> fallback-icon"></i>
            <?php endif; ?>
        </div>

        <!-- Badges -->
        <div class="badges-container">
            <?php if (isset($offer['is_featured']) && $offer['is_featured']): ?>
            <span class="badge badge-featured"><i class="fas fa-star"></i> Featured</span>
            <?php endif; ?>
            <?php if (isset($offer['is_urgent']) && $offer['is_urgent']): ?>
            <span class="badge badge-urgent"><i class="fas fa-bolt"></i> Urgent</span>
            <?php endif; ?>
            <?php if (!empty($offer['promo_code'])): ?>
            <span class="badge badge-promo"><i class="fas fa-ticket-alt"></i> <?php echo htmlspecialchars($offer['promo_code']); ?></span>
            <?php endif; ?>
            <span class="badge badge-status"><i class="fas fa-check-circle"></i> Active</span>
        </div>

        <!-- Title -->
        <h1 class="details-title"><?php echo htmlspecialchars($offer['offer_title']); ?></h1>
        <div class="details-subtitle"><?php echo htmlspecialchars($offer['loan_type'] ?? 'Loan'); ?> Loan</div>

        <!-- Rate & Amount -->
        <div class="rate-big"><?php echo number_format($offer['interest_rate'], 1); ?>% <small>p.a.</small></div>
        <div class="amount-range"><?php echo formatCurrency($offer['min_amount']); ?> - <?php echo formatCurrency($offer['max_amount']); ?></div>
        
        <div class="lender-info">
            <i class="fas fa-building"></i> <?php echo htmlspecialchars($offer['lender_name'] ?? 'Multiple Lenders'); ?>
            <span style="margin-left:auto;font-size:12px;color:var(--text-tertiary);">
                <i class="fas fa-clock"></i> <?php echo $offer['loan_term']; ?> months
            </span>
        </div>

        <hr class="divider">

        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-item">
                <div class="label">Interest Rate</div>
                <div class="value"><?php echo number_format($offer['interest_rate'], 1); ?>% p.a.</div>
            </div>
            <div class="info-item">
                <div class="label">Loan Term</div>
                <div class="value"><?php echo $offer['loan_term']; ?> months</div>
            </div>
            <div class="info-item">
                <div class="label">Min Amount</div>
                <div class="value"><?php echo formatCurrency($offer['min_amount']); ?></div>
            </div>
            <div class="info-item">
                <div class="label">Max Amount</div>
                <div class="value"><?php echo formatCurrency($offer['max_amount']); ?></div>
            </div>
            <div class="info-item full-width">
                <div class="label">Loan Type</div>
                <div class="value"><?php echo htmlspecialchars($offer['loan_type']); ?></div>
            </div>
            <?php if (!empty($offer['promo_code'])): ?>
            <div class="info-item full-width highlight">
                <div class="label">Promo Code</div>
                <div class="value"><i class="fas fa-ticket-alt"></i> <?php echo htmlspecialchars($offer['promo_code']); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($offer['full_description']) || !empty($offer['short_description'])): ?>
        <hr class="divider">
        <div class="description">
            <?php echo nl2br(htmlspecialchars($offer['full_description'] ?? $offer['short_description'] ?? '')); ?>
        </div>
        <?php endif; ?>

        <hr class="divider">

        <!-- Actions -->
        <div class="actions">
            <button class="btn-apply" onclick="openLoanApplication('<?php echo addslashes($offer['offer_title']); ?>', '<?php echo addslashes($offer['short_description'] ?? ''); ?>', <?php echo $offer['id']; ?>)">
                <i class="fas fa-paper-plane"></i> Apply Now
            </button>
            <button class="btn-share" onclick="shareOffer('<?php echo addslashes($offer['offer_title']); ?>')">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>
    </div>

    <!-- Application Modal -->
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

    function shareOffer(title) {
        if (navigator.share) {
            navigator.share({
                title: title,
                text: 'Check out this loan offer: ' + title,
                url: window.location.href
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href).then(() => {
                showToast('✅ Link copied to clipboard!');
            }).catch(() => {
                showToast('📋 Share this offer with others!');
            });
        }
    }
</script>
</body>
</html>
<?php include('includes/footer-nav.php'); ?>