<?php
// FILE: loan-details.php
include('includes/dbConfig.php');
include('includes/navbar.php');

// Get loan ID from URL
$loan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch loan details
$loan_query = "SELECT * FROM loan_offers WHERE id = $loan_id AND status = 1";
$loan_result = $conn->query($loan_query);

if ($loan_result && $loan_result->num_rows > 0) {
    $loan = $loan_result->fetch_assoc();
    
    // Decode features - stored as newline separated text
    $features = [];
    if (!empty($loan['features'])) {
        $features = explode("\n", $loan['features']);
        $features = array_filter($features);
    }
    
    // Decode eligibility
    $eligibility = [];
    if (!empty($loan['eligibility'])) {
        $eligibility = explode("\n", $loan['eligibility']);
        $eligibility = array_filter($eligibility);
    }
    
    // Decode documents
    $documents = [];
    if (!empty($loan['documents_required'])) {
        $documents = explode("\n", $loan['documents_required']);
        $documents = array_filter($documents);
    }
} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($loan['title']); ?> | Loan Details</title>

    <!-- Favicons -->
    <link href="assets/img/favicon.jpeg" rel="icon">
    <link href="assets/img/favicon.jpeg" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #124265;
            --primary-dark: #0a2b3e;
            --primary-light: #1e6a9f;
            --accent: #fd9130;
            --accent-dark: #e07a1a;
            --accent-light: #fef3c7;
            --ink: #0d0d0d;
            --ink-soft: #3a3a3a;
            --ink-muted: #888;
            --bg-body: #ffffff;
            --bg-surface: #ffffff;
            --bg-card: #ffffff;
            --bg-sidebar: #ffffff;
            --border: rgba(0, 0, 0, 0.08);
            --border-light: rgba(0, 0, 0, 0.05);
            --shadow-xs: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow-sm: 0 4px 16px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 8px 32px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 16px 48px rgba(0, 0, 0, 0.12);
            --gray-50: #faf8f5;
            --gray-100: #f5f2ee;
        }

        [data-theme="dark"] {
            --primary: #1e4d6f;
            --primary-dark: #0f2e44;
            --ink: #ffffff;
            --ink-soft: #e0e0e0;
            --ink-muted: #a0a0a0;
            --bg-body: #121212;
            --bg-surface: #1e1e1e;
            --bg-card: #1e1e1e;
            --bg-sidebar: #1e1e1e;
            --border: rgba(255, 255, 255, 0.08);
            --border-light: rgba(255, 255, 255, 0.05);
            --gray-50: #1a1a1a;
            --gray-100: #252525;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-body);
            color: var(--ink);
            overflow-x: hidden;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Progress Bar */
        .progress-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: transparent;
            z-index: 10000;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--accent), var(--accent-dark));
            width: 0%;
            transition: width 0.1s linear;
        }

        /* Hero Section */
        .hero-premium {
            position: relative;
            min-height: 60vh;
            display: flex;
            align-items: flex-end;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            overflow: hidden;
        }
        .hero-bg-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.3;
        }
        .hero-gradient-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(10, 7, 0, 0.2) 0%, rgba(10, 7, 0, 0.4) 40%, rgba(10, 7, 0, 0.92) 100%);
        }
        .hero-content {
            position: relative;
            z-index: 10;
            max-width: 1000px;
            margin: 0 auto;
            padding: 80px 40px 60px;
            text-align: center;
        }
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 100px;
            margin-bottom: 24px;
        }
        .hero-eyebrow-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }
        .hero-eyebrow span {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.9);
        }
        .hero-title {
            font-size: 48px;
            font-weight: 800;
            color: white;
            line-height: 1.2;
            margin-bottom: 24px;
        }
        .hero-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 28px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .hero-meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }
        .hero-meta-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .rate-badge {
            background: rgba(253, 145, 48, 0.9);
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 700;
        }

        /* Main Layout */
        .main-layout {
            width: 100%;
            max-width: 1400px;
            margin: 60px auto 0;
            padding: 0 24px 60px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 50px;
        }

        /* Article Card */
        .article-card {
            background: var(--bg-card);
            border-radius: 24px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--border);
            width: 100%;
        }
        .article-inner {
            padding: 48px;
            width: 100%;
        }

        /* Info Cards */
        .info-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: transform 0.3s ease;
        }
        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        .info-card h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--accent);
        }
        .info-card h2 i {
            color: var(--accent);
        }

        /* Detail Grid */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 30px;
        }
        .detail-box {
            background: var(--gray-50);
            border-radius: 16px;
            padding: 1.2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
        }
        .detail-box:hover {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            transform: translateY(-3px);
        }
        .detail-box:hover .detail-icon i,
        .detail-box:hover .detail-label,
        .detail-box:hover .detail-value {
            color: white;
        }
        .detail-icon i {
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 0.5rem;
        }
        .detail-label {
            font-size: 0.7rem;
            color: var(--ink-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .detail-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--ink);
            margin-top: 0.25rem;
        }

        /* Feature Lists */
        .feature-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem;
            background: var(--gray-50);
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .feature-list li:hover {
            background: linear-gradient(135deg, var(--accent-light), var(--gray-100));
            transform: translateX(5px);
        }
        .feature-list li i {
            color: var(--accent);
            width: 20px;
        }

        /* Rich Content Styles - For displaying HTML from editor */
        .rich-content {
            font-size: 1rem;
            line-height: 1.8;
            color: var(--ink-soft);
        }
        .rich-content h1, .rich-content h2, .rich-content h3, .rich-content h4, .rich-content h5, .rich-content h6 {
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            font-weight: 700;
            color: var(--ink);
        }
        .rich-content h2 {
            font-size: 1.8rem;
            border-left: 4px solid var(--accent);
            padding-left: 15px;
        }
        .rich-content h3 {
            font-size: 1.5rem;
        }
        .rich-content p {
            margin-bottom: 1em;
        }
        .rich-content ul, .rich-content ol {
            margin: 1em 0;
            padding-left: 2em;
        }
        .rich-content li {
            margin-bottom: 0.5em;
        }
        .rich-content strong {
            font-weight: 700;
            color: var(--accent);
        }
        .rich-content a {
            color: var(--accent);
            text-decoration: none;
        }
        .rich-content a:hover {
            text-decoration: underline;
        }
        .rich-content img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1em 0;
        }
        .rich-content blockquote {
            border-left: 4px solid var(--accent);
            padding: 1em 1.5em;
            background: var(--gray-50);
            border-radius: 0 12px 12px 0;
            font-style: italic;
            margin: 1em 0;
        }
        .rich-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1em 0;
        }
        .rich-content th, .rich-content td {
            border: 1px solid var(--border);
            padding: 8px 12px;
        }
        .rich-content th {
            background: var(--gray-50);
            font-weight: 700;
        }

        /* EMI Calculator */
        .emi-calculator {
            background: var(--gray-50);
            padding: 1.5rem;
            border-radius: 20px;
        }
        .emi-calculator input {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg-card);
            color: var(--ink);
            margin-top: 5px;
        }
        .emi-result {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            margin-top: 1rem;
        }
        .emi-result span {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--accent);
        }

        /* Apply Button */
        .apply-btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .apply-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(253, 145, 48, 0.5);
        }

        /* Sidebar Widgets */
        .sidebar-premium {
            position: sticky;
            top: 20px;
            align-self: start;
        }
        .widget-premium {
            background: var(--bg-sidebar);
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
        }
        .widget-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border);
        }
        .widget-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent-light), var(--gray-100));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 18px;
        }
        .widget-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--ink);
            margin: 0;
        }

        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 100;
            border: none;
        }
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--bg-card);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 100;
            color: var(--accent);
            font-size: 22px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .modal.show {
            display: block;
        }
        .modal-dialog {
            position: relative;
            width: auto;
            max-width: 500px;
            margin: 1.75rem auto;
        }
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            background: var(--bg-card);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid var(--border);
        }
        .modal-body {
            padding: 1rem;
        }
        .modal-footer {
            padding: 1rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .form-control, .form-select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg-card);
            color: var(--ink);
        }

        @media (max-width: 992px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
            .hero-title {
                font-size: 32px;
            }
            .article-inner {
                padding: 24px;
            }
            .detail-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
            .hero-content {
                padding: 60px 20px 40px;
            }
            .hero-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<!-- Progress Bar -->
<div class="progress-container">
    <div class="progress-bar" id="progressBar"></div>
</div>

<!-- Hero Section -->
<section class="hero-premium">
    <?php if (!empty($loan['image_path']) && file_exists($loan['image_path'])): ?>
        <img class="hero-bg-image" src="<?php echo htmlspecialchars($loan['image_path']); ?>" alt="<?php echo htmlspecialchars($loan['title']); ?>">
    <?php else: ?>
        <img class="hero-bg-image" src="assets/images/loan-placeholder.jpg" alt="Loan">
    <?php endif; ?>
    <div class="hero-gradient-overlay"></div>
    <div class="hero-content">
        <div class="hero-eyebrow">
            <div class="hero-eyebrow-dot"></div>
            <span>Loan Offer</span>
        </div>
        <h1 class="hero-title"><?php echo htmlspecialchars($loan['title']); ?></h1>
        <div class="hero-meta">
            <?php if (!empty($loan['interest_rate'])): ?>
                <div class="hero-meta-item">
                    <div class="hero-meta-icon"><i class="fas fa-percent"></i></div>
                    <span>Interest: <?php echo htmlspecialchars($loan['interest_rate']); ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($loan['max_amount'])): ?>
                <div class="hero-meta-item">
                    <div class="hero-meta-icon"><i class="fas fa-rupee-sign"></i></div>
                    <span>Up to <?php echo htmlspecialchars($loan['max_amount']); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Main Layout -->
<div class="main-layout">
    <!-- Left Column -->
    <div class="article-card">
        <div class="article-inner">
            
            <!-- Description - Using html_entity_decode to render HTML properly -->
            <?php if (!empty($loan['description'])): ?>
                <div class="info-card">
                    <h2><i class="fas fa-info-circle"></i> About This Offer</h2>
                    <div class="rich-content">
                        <?php echo html_entity_decode($loan['description']); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Key Details Grid -->
            <div class="detail-grid">
                <?php if (!empty($loan['max_amount'])): ?>
                    <div class="detail-box">
                        <div class="detail-icon"><i class="fas fa-rupee-sign"></i></div>
                        <div class="detail-label">Maximum Amount</div>
                        <div class="detail-value"><?php echo htmlspecialchars($loan['max_amount']); ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($loan['min_amount'])): ?>
                    <div class="detail-box">
                        <div class="detail-icon"><i class="fas fa-rupee-sign"></i></div>
                        <div class="detail-label">Minimum Amount</div>
                        <div class="detail-value"><?php echo htmlspecialchars($loan['min_amount']); ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($loan['interest_rate'])): ?>
                    <div class="detail-box">
                        <div class="detail-icon"><i class="fas fa-percent"></i></div>
                        <div class="detail-label">Interest Rate</div>
                        <div class="detail-value"><?php echo htmlspecialchars($loan['interest_rate']); ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($loan['tenure'])): ?>
                    <div class="detail-box">
                        <div class="detail-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div class="detail-label">Tenure</div>
                        <div class="detail-value"><?php echo htmlspecialchars($loan['tenure']); ?></div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($loan['processing_fee'])): ?>
                    <div class="detail-box">
                        <div class="detail-icon"><i class="fas fa-file-invoice"></i></div>
                        <div class="detail-label">Processing Fee</div>
                        <div class="detail-value"><?php echo htmlspecialchars($loan['processing_fee']); ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Features Section -->
            <?php if (!empty($features)): ?>
                <div class="info-card">
                    <h2><i class="fas fa-star"></i> Key Features & Benefits</h2>
                    <ul class="feature-list">
                        <?php foreach ($features as $feature): ?>
                            <?php if(trim($feature)): ?>
                                <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars(trim($feature)); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Eligibility Section -->
            <?php if (!empty($eligibility)): ?>
                <div class="info-card">
                    <h2><i class="fas fa-user-check"></i> Eligibility Criteria</h2>
                    <ul class="feature-list">
                        <?php foreach ($eligibility as $item): ?>
                            <?php if(trim($item)): ?>
                                <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars(trim($item)); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Documents Section -->
            <?php if (!empty($documents)): ?>
                <div class="info-card">
                    <h2><i class="fas fa-file-alt"></i> Documents Required</h2>
                    <ul class="feature-list">
                        <?php foreach ($documents as $doc): ?>
                            <?php if(trim($doc)): ?>
                                <li><i class="fas fa-file-pdf"></i> <?php echo htmlspecialchars(trim($doc)); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- EMI Calculator -->
            <div class="info-card">
                <h2><i class="fas fa-calculator"></i> EMI Calculator</h2>
                <div class="emi-calculator">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Loan Amount (₹)</label>
                            <input type="number" id="loanAmount" value="500000" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Interest Rate (% p.a.)</label>
                            <input type="number" id="interestRate" value="<?php echo preg_replace('/[^0-9.]/', '', $loan['interest_rate']); ?>" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Tenure (Years)</label>
                            <input type="number" id="loanTenure" value="5" class="form-control">
                        </div>
                    </div>
                    <div class="emi-result mt-3">
                        Your Monthly EMI: <span id="emiAmount">₹0</span>
                    </div>
                </div>
            </div>

            <!-- Apply Now CTA -->
            <div class="info-card" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); text-align: center;">
                <h2 style="color: white; border-bottom-color: var(--accent);"><i class="fas fa-rocket"></i> Ready to Apply?</h2>
                <p style="color: rgba(255,255,255,0.8); margin-bottom: 20px;">Get instant approval with our quick and easy process!</p>
                <a href="<?php echo htmlspecialchars($loan['button_link']); ?>" class="apply-btn" style="background: white; color: var(--primary);">
                    <i class="fas fa-arrow-right"></i> <?php echo htmlspecialchars($loan['button_text']); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column - Sidebar -->
    <aside class="sidebar-premium">
        <!-- Quick Apply Widget -->
        <div class="widget-premium">
            <div class="widget-header">
                <div class="widget-icon"><i class="fas fa-bolt"></i></div>
                <h3 class="widget-title">Quick Apply</h3>
            </div>
            <button class="apply-btn w-100" onclick="openModal()" style="width: 100%; text-align: center;">
                <i class="fas fa-hand-pointer"></i> Apply Now
            </button>
            <p class="mt-3 text-center text-muted small">Get callback in 24 hours</p>
        </div>

        <!-- Why Choose Us Widget -->
        <div class="widget-premium">
            <div class="widget-header">
                <div class="widget-icon"><i class="fas fa-handshake"></i></div>
                <h3 class="widget-title">Why Choose Us?</h3>
            </div>
            <ul class="feature-list" style="grid-template-columns: 1fr;">
                <li><i class="fas fa-bolt"></i> Quick Approval within 24 hours</li>
                <li><i class="fas fa-lock"></i> 100% Secure & Trusted</li>
                <li><i class="fas fa-headset"></i> 24/7 Customer Support</li>
                <li><i class="fas fa-chart-line"></i> Lowest Interest Rates</li>
                <li><i class="fas fa-file-signature"></i> Minimal Documentation</li>
            </ul>
        </div>

        <!-- Need Help Widget -->
        <div class="widget-premium">
            <div class="widget-header">
                <div class="widget-icon"><i class="fas fa-question-circle"></i></div>
                <h3 class="widget-title">Need Help?</h3>
            </div>
            <div class="text-center">
                <i class="fas fa-phone-alt" style="font-size: 32px; color: var(--accent); margin-bottom: 10px;"></i>
                <p class="mb-2">Call us for assistance</p>
                <h4><a href="tel:9246545737" style="color: var(--accent);">+91 92465 45737</a></h4>
                <hr>
                <i class="fas fa-envelope" style="font-size: 32px; color: var(--accent); margin-bottom: 10px;"></i>
                <p><a href="mailto:info@kurakulas.com">info@kurakulas.com</a></p>
            </div>
        </div>
    </aside>
</div>

<!-- Back to Top -->
<button class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Theme Toggle -->
<button class="theme-toggle" id="themeToggle">
    <i class="fas fa-moon" id="themeIcon"></i>
</button>

<!-- Eligibility Modal -->
<div id="eligibilityModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Check Your Eligibility</h5>
                <button type="button" class="btn-close" onclick="closeModal()">&times;</button>
            </div>
            <form method="post" action="send_mail.php">
                <div class="modal-body">
                    <input type="text" class="form-control mb-3" name="CustomerName" placeholder="Customer Name" required>
                    <input type="tel" class="form-control mb-3" name="MobileNumber" placeholder="Mobile Number" required>
                    <input type="email" class="form-control mb-3" name="Email" placeholder="Email" required>
                    <select class="form-select mb-3" name="Location" required>
                        <option value="">Select Location</option>
                        <option>Ameerpet</option><option>Begumpet</option><option>Madhapur</option>
                        <option>KPHB</option><option>Warangal</option><option>Karimnagar</option>
                        <option>Nizambad</option><option>Khammam</option><option>Nalgonda</option>
                        <option>Adilabad</option><option>Kothagudem</option><option>Guntur</option>
                        <option>Vizag</option><option>Bhimavaram</option><option>Kakinada</option>
                        <option>Vijayawada</option><option>Rajamundry</option><option>Vijayanagaram</option>
                        <option>Tirupati</option><option>Kadapa</option><option>Kurnool</option>
                        <option>Anantapur</option><option>Ongole</option><option>Nellore</option>
                    </select>
                    <select class="form-select mb-3" name="TypeofLoan" required>
                        <option value="">Select Type of Loan</option>
                        <option>PERSONAL LOAN</option><option>BUSINESS LOANS</option>
                        <option>HOME LOANS</option><option>CAR LOANS</option>
                        <option>PROFESSIONAL LOANS</option><option>MORTGAGE LOANS</option>
                        <option>GOLD LOAN</option><option>EDUCATION LOAN</option>
                    </select>
                    <select class="form-select mb-3" name="TypeofCustomer" id="customerType" required onchange="handleCustomerTypeChange()">
                        <option value="">Select Type of Customer</option>
                        <option value="salaried">Salaried</option>
                        <option value="Business">Business</option>
                        <option value="doctor">Doctor</option>
                        <option value="ca">CA</option>
                        <option value="other">Other</option>
                    </select>
                    <div id="additionalFields"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                    <button type="submit" name="send" class="btn" style="background: var(--accent); color: white;">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Progress Bar
const progressBar = document.getElementById('progressBar');
window.addEventListener('scroll', () => {
    const winScroll = document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = (winScroll / height) * 100;
    if (progressBar) progressBar.style.width = scrolled + '%';
});

// Back to Top
const backToTop = document.getElementById('backToTop');
window.addEventListener('scroll', () => {
    if (window.scrollY > 500) {
        backToTop.classList.add('visible');
    } else {
        backToTop.classList.remove('visible');
    }
});
backToTop.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Theme Toggle
const htmlElement = document.documentElement;
const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const savedTheme = localStorage.getItem('kurakulas-theme') || 'light';
applyTheme(savedTheme);

themeToggle.addEventListener('click', () => {
    const currentTheme = htmlElement.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
    localStorage.setItem('kurakulas-theme', newTheme);
});

function applyTheme(theme) {
    htmlElement.setAttribute('data-theme', theme);
    if (theme === 'dark') {
        themeIcon.className = 'fas fa-sun';
    } else {
        themeIcon.className = 'fas fa-moon';
    }
}

// EMI Calculator
function calculateEMI() {
    const amount = parseFloat(document.getElementById('loanAmount')?.value) || 0;
    let rate = parseFloat(document.getElementById('interestRate')?.value) || 0;
    const years = parseFloat(document.getElementById('loanTenure')?.value) || 1;
    const months = years * 12;
    const monthlyRate = rate / 12 / 100;
    let emi;
    if (monthlyRate === 0) {
        emi = amount / months;
    } else {
        emi = amount * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
    }
    const emiElement = document.getElementById('emiAmount');
    if (emiElement) emiElement.innerHTML = '₹' + emi.toFixed(0);
}

document.getElementById('loanAmount')?.addEventListener('input', calculateEMI);
document.getElementById('interestRate')?.addEventListener('input', calculateEMI);
document.getElementById('loanTenure')?.addEventListener('input', calculateEMI);
calculateEMI();

// Modal Functions
function openModal() {
    document.getElementById('eligibilityModal').style.display = 'block';
    document.body.classList.add('modal-open');
}
function closeModal() {
    document.getElementById('eligibilityModal').style.display = 'none';
    document.body.classList.remove('modal-open');
}
function handleCustomerTypeChange() {
    const customerType = document.getElementById('customerType').value;
    const additionalFields = document.getElementById('additionalFields');
    additionalFields.innerHTML = '';
    if (customerType === 'salaried') {
        additionalFields.innerHTML = `<input type="text" class="form-control mb-3" name="CompanyName" placeholder="Company Name" required>
                                       <input type="text" class="form-control mb-3" name="GrossSalary" placeholder="Gross Salary" required>`;
    } else if (customerType === 'Business') {
        additionalFields.innerHTML = `<input type="text" class="form-control mb-3" name="CompanyName" placeholder="Company Name" required>
                                       <input type="text" class="form-control mb-3" name="GrossTurnOver" placeholder="Gross Turnover" required>`;
    } else if (customerType === 'doctor' || customerType === 'ca') {
        additionalFields.innerHTML = `<select class="form-select mb-3" name="SelectYear" required>
                                        <option value="">Select Year</option>
                                        ${[...Array(50)].map((_, i) => `<option>${new Date().getFullYear() - i}</option>`).join('')}
                                       </select>
                                       <input type="text" class="form-control mb-3" name="Turnover" placeholder="Turnover" required>`;
    } else if (customerType === 'other') {
        additionalFields.innerHTML = `<input type="text" class="form-control mb-3" name="OtherDetails" placeholder="Specify Your Profession" required>`;
    }
}

// Close modal on ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('eligibilityModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/js/main.js"></script>

<?php include('includes/footer.php'); ?>
</body>
</html>