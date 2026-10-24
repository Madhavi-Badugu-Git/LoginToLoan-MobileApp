<?php
session_start();
include('../includes/dbConfig.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$fullname      = htmlspecialchars($_SESSION['fullname']      ?? 'User');
$email         = htmlspecialchars($_SESSION['email']         ?? 'user@email.com');
$phone         = htmlspecialchars($_SESSION['phone']         ?? '+91 98765 43210');
$role          = htmlspecialchars($_SESSION['role']          ?? 'user');
$profile_image = htmlspecialchars($_SESSION['profile_image'] ?? '');
$firstname     = htmlspecialchars(explode(' ', trim($_SESSION['fullname'] ?? 'User'))[0]);

include('../includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#0277bd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Car Loan - Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        :root {
            --primary: #1a237e;
            --primary-light: #283593;
            --primary-dark: #0d1757;
            --car-blue: #0277bd;
            --orange: #ff9800;
            --success: #16a34a;
            --danger: #ef4444;
            --text-primary: #1a1a2e;
            --text-secondary: #666666;
            --text-tertiary: #999999;
            --background: #f0f7ff;
            --white: #ffffff;
            --border: #e8edf5;
            --shadow: 0 2px 12px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
            --radius: 16px;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
            min-height: 100vh;
            padding-bottom: 20px;
        }

        /* ===== HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, #01579b, #0277bd, #0288d1);
            padding: 16px 16px 28px;
            color: white;
            border-radius: 0 0 28px 28px;
            position: relative;
            overflow: hidden;
        }
        .page-header .back-btn {
            background: none;
            border: none;
            color: white;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 0;
            margin-bottom: 8px;
            font-family: 'Inter', sans-serif;
        }
        .page-header .back-btn:active { opacity: 0.7; }
        .page-header h1 {
            font-size: 22px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }
        .page-header p {
            font-size: 13px;
            opacity: 0.8;
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }
        .page-header .header-icon {
            position: absolute;
            right: 10px;
            bottom: 10px;
            font-size: 56px;
            opacity: 0.1;
        }

        /* ============================================================ */
        /* FEATURES */
        /* ============================================================ */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            padding: 0 14px;
            margin-top: -10px;
            position: relative;
            z-index: 2;
        }
        .feature-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 12px 8px;
            text-align: center;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }
        .feature-card .feature-icon {
            font-size: 22px;
            color: var(--car-blue);
            margin-bottom: 4px;
        }
        .feature-card .feature-title {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .feature-card .feature-desc {
            font-size: 8px;
            color: var(--text-tertiary);
            margin-top: 2px;
        }

        /* ============================================================ */
        /* LOAN OFFERS */
        /* ============================================================ */
        .section-header {
            padding: 16px 14px 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .section-header h2 i { color: var(--car-blue); margin-right: 6px; }

        .offers-list {
            padding: 0 14px;
        }
        .offer-item {
            background: var(--white);
            border-radius: var(--radius);
            padding: 14px 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .offer-item:active { transform: scale(0.98); background: #f0f7ff; }
        .offer-item .offer-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            color: white;
        }
        .offer-item .offer-info { flex: 1; }
        .offer-item .offer-info .offer-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .offer-item .offer-info .offer-desc {
            font-size: 10px;
            color: var(--text-secondary);
            margin-top: 2px;
        }
        .offer-item .offer-info .offer-tag {
            font-size: 9px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 12px;
            display: inline-block;
            margin-top: 4px;
        }
        .offer-item .offer-arrow {
            color: var(--text-tertiary);
            font-size: 14px;
        }

        /* ============================================================ */
        /* WHY CHOOSE */
        /* ============================================================ */
        .why-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            padding: 0 14px;
        }
        .why-card {
            background: var(--white);
            border-radius: 12px;
            padding: 12px 8px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .why-card .why-icon {
            font-size: 20px;
            color: var(--car-blue);
            margin-bottom: 4px;
        }
        .why-card .why-text {
            font-size: 9px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ============================================================ */
        /* APPLICATION FORM - NO DOCUMENT UPLOAD */
        /* ============================================================ */
        .application-modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 3000;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }
        .application-modal.active { display: flex; }
        .application-content {
            background: var(--white);
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 480px;
            max-height: 92vh;
            overflow-y: auto;
            padding: 0;
            animation: slideUp 0.35s cubic-bezier(0.32, 0.72, 0, 1);
        }
        @keyframes slideUp {
            from { transform: translateY(60px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .application-header {
            padding: 18px 20px 14px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: var(--white);
            z-index: 5;
            border-radius: 24px 24px 0 0;
        }
        .application-header h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .application-header h3 i { color: var(--car-blue); }
        .application-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f0f2f5;
            border: none;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
        }
        .application-close:active { background: #e0e2e5; }

        .application-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }
        .form-group label i {
            margin-right: 6px;
            color: var(--car-blue);
            width: 16px;
        }
        .form-group label .required {
            color: var(--danger);
            font-weight: 700;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
            background: var(--white);
            color: var(--text-primary);
            appearance: none;
            -webkit-appearance: none;
        }
        .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='7' viewBox='0 0 12 7'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23999' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--car-blue);
            box-shadow: 0 0 0 3px rgba(2,119,189,0.08);
        }
        .form-input.error, .form-select.error {
            border-color: var(--danger);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .form-error {
            font-size: 10px;
            color: var(--danger);
            margin-top: 4px;
            display: none;
        }
        .form-error.show { display: block; }

        .section-card {
            background: #f8faff;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 18px;
            border: 1px solid var(--border);
        }
        .section-card .section-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-card .section-title i { font-size: 14px; }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 6px;
            box-shadow: 0 4px 12px rgba(255,152,0,0.25);
        }
        .submit-btn:active { transform: scale(0.97); }
        .submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }

        .form-note {
            font-size: 10px;
            color: var(--text-tertiary);
            text-align: center;
            margin-top: 10px;
            padding: 8px;
            background: #f8f9fc;
            border-radius: 8px;
        }
        .form-note i { color: var(--success); margin-right: 4px; }

        /* ============================================================ */
        /* TOAST */
        /* ============================================================ */
        .toast {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #1a1a2e;
            color: white;
            padding: 10px 20px;
            border-radius: 24px;
            font-size: 13px;
            z-index: 9999;
            transition: transform 0.3s;
            max-width: 90%;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }
        .toast.show { transform: translateX(-50%) translateY(0); }
        .toast.success { background: var(--success); }
        .toast.error { background: var(--danger); }

        /* ============================================================ */
        /* SUCCESS MODAL */
        /* ============================================================ */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9998;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: var(--white);
            border-radius: 24px;
            padding: 24px 20px 20px;
            width: 100%;
            max-width: 380px;
            animation: modalPop 0.3s ease;
        }
        @keyframes modalPop {
            from { transform: scale(0.92); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .success-check {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #e8f5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
        .success-check i { font-size: 28px; color: var(--success); }
        .success-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--success);
            text-align: center;
        }
        .success-sub {
            font-size: 13px;
            color: var(--text-secondary);
            text-align: center;
            margin-top: 4px;
            margin-bottom: 14px;
        }
        .success-details-box {
            background: #f8faff;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 14px;
            border: 1px solid var(--border);
        }
        .success-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 13px;
        }
        .success-detail-row:not(:last-child) {
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .success-detail-row .s-label { color: var(--text-secondary); }
        .success-detail-row .s-value { font-weight: 600; color: var(--text-primary); }

        .apply-btn-modal {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--success), #22c55e);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .apply-btn-modal:active { transform: scale(0.97); }

        .footer-note {
            text-align: center;
            font-size: 10px;
            color: var(--text-tertiary);
            padding: 14px;
            border-top: 1px solid var(--border);
            margin-top: 16px;
        }

        /* ============================================================ */
        /* RESPONSIVE */
        /* ============================================================ */
        @media (max-width: 374px) {
            .page-header h1 { font-size: 19px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .why-grid { grid-template-columns: repeat(2, 1fr); }
            .form-row { grid-template-columns: 1fr; }
            .application-content { border-radius: 20px 20px 0 0; }
            .section-card { padding: 12px; }
            .application-body { padding: 16px; }
        }

        @media (max-width: 480px) {
            .page-header { padding: 14px 14px 24px; }
            .page-header h1 { font-size: 20px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .feature-card { padding: 10px 6px; }
            .feature-card .feature-icon { font-size: 18px; }
            .feature-card .feature-title { font-size: 9px; }
            .why-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .why-card { padding: 10px 6px; }
            .why-card .why-icon { font-size: 18px; }
            .why-card .why-text { font-size: 8px; }
            .form-row { grid-template-columns: 1fr; }
            .application-content { border-radius: 20px 20px 0 0; }
            .application-header { padding: 14px 16px 12px; }
            .application-body { padding: 16px; }
            .section-card { padding: 12px; }
            .offers-list { padding: 0 10px; }
            .offer-item { padding: 12px 14px; }
            .offer-item .offer-icon { width: 38px; height: 38px; font-size: 15px; }
            .offer-item .offer-info .offer-name { font-size: 12px; }
            .section-header h2 { font-size: 16px; }
            .modal-content { padding: 20px 16px 16px; }
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --background: #121212;
                --white: #1e1e1e;
                --border: #2a2a2a;
                --text-primary: #e8e8e8;
                --text-secondary: #a0a0a0;
                --text-tertiary: #666666;
                --shadow: 0 2px 12px rgba(0,0,0,0.3);
            }
            .page-header { background: linear-gradient(135deg, #0a2a4a, #01579b, #0277bd); }
            .feature-card { background: var(--white); }
            .offer-item { background: var(--white); }
            .section-card { background: #1a1a2a; }
            .application-content { background: var(--white); }
            .application-header { background: var(--white); }
            .modal-content { background: var(--white); }
            .why-card { background: var(--white); }
            .form-input { background: #2a2a2a; color: #e8e8e8; border-color: #3a3a3a; }
            .form-select { background: #2a2a2a; color: #e8e8e8; border-color: #3a3a3a; }
            .form-note { background: #1a1a2a; }
            .success-details-box { background: #1a1a2a; }
            .toast { background: #333333; }
            .offer-item:active { background: #1a2a3a; }
        }
    </style>
</head>
<body>

<div class="app-container">

    <!-- ===== HEADER ===== -->
    <div class="page-header">
        <button class="back-btn" onclick="history.back()">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <h1>🚗 Car Loan</h1>
        <p>100% Finance available with lowest interest rates</p>
        <div class="header-icon"><i class="fas fa-car"></i></div>
    </div>

    <!-- ===== FEATURES ===== -->
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-percent"></i></div>
            <div class="feature-title">Low Interest</div>
            <div class="feature-desc">From 7.5% p.a.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-clock"></i></div>
            <div class="feature-title">Quick Approval</div>
            <div class="feature-desc">24 Hours</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="feature-title">100% Finance</div>
            <div class="feature-desc">On-road price</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="feature-title">Flexible Tenure</div>
            <div class="feature-desc">Up to 7 Years</div>
        </div>
    </div>

    <!-- ===== LOAN OFFERS ===== -->
    <div class="section-header">
        <h2><i class="fas fa-hand-holding-usd"></i> Loan Offers</h2>
    </div>
    <div class="offers-list">
        <div class="offer-item" onclick="openApplicationForm('New Car Loan')">
            <div class="offer-icon" style="background: #2e7d32;">🚗</div>
            <div class="offer-info">
                <div class="offer-name">New Car Loan</div>
                <div class="offer-desc">Up to 100% on-road finance</div>
                <span class="offer-tag" style="background:#e8f5e9; color:#2e7d32;">7.5% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplicationForm('Used Car Loan')">
            <div class="offer-icon" style="background: #e65100;">🚙</div>
            <div class="offer-info">
                <div class="offer-name">Used Car Loan</div>
                <div class="offer-desc">For pre-owned vehicles</div>
                <span class="offer-tag" style="background:#fff3e0; color:#e65100;">8.5% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplicationForm('Electric Vehicle Loan')">
            <div class="offer-icon" style="background: #1565c0;">⚡</div>
            <div class="offer-info">
                <div class="offer-name">Electric Vehicle Loan</div>
                <div class="offer-desc">Special rates for EVs</div>
                <span class="offer-tag" style="background:#e3f2fd; color:#1565c0;">6.9% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplicationForm('Commercial Vehicle Loan')">
            <div class="offer-icon" style="background: #c62828;">🚛</div>
            <div class="offer-info">
                <div class="offer-name">Commercial Vehicle Loan</div>
                <div class="offer-desc">For business vehicles</div>
                <span class="offer-tag" style="background:#fce4ec; color:#c62828;">8.0% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>
    </div>

    <!-- ===== WHY CHOOSE US ===== -->
    <div class="section-header">
        <h2><i class="fas fa-check-circle"></i> Why Choose Us</h2>
    </div>
    <div class="why-grid">
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-clock"></i></div>
            <div class="why-text">Quick Approval 24 hrs</div>
        </div>
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-laptop"></i></div>
            <div class="why-text">100% Online Process</div>
        </div>
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-check-circle"></i></div>
            <div class="why-text">No Hidden Charges</div>
        </div>
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-hand-holding-usd"></i></div>
            <div class="why-text">Flexible Repayment</div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- APPLICATION FORM - NO DOCUMENT UPLOAD -->
    <!-- ============================================================ -->
    <div id="applicationModal" class="application-modal">
        <div class="application-content">
            <div class="application-header">
                <h3><i class="fas fa-car"></i> Apply for <span id="selectedLoanType">Car Loan</span></h3>
                <button class="application-close" onclick="closeApplicationForm()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="application-body">
                <form id="applicationForm" onsubmit="submitApplication(event)">
                    
                    <!-- Hidden fields for user data from session -->
                    <input type="hidden" id="fullNameHidden" value="<?php echo $fullname; ?>">
                    <input type="hidden" id="emailHidden" value="<?php echo $email; ?>">
                    <input type="hidden" id="mobileHidden" value="<?php echo str_replace(['+91', ' ', '-'], '', $phone); ?>">

                    <!-- Loan Details Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-hand-holding-usd"></i> Loan Details</div>
                        
                        <div class="form-group" style="margin-bottom:10px;">
                            <label><i class="fas fa-tag"></i> Loan Type</label>
                            <input type="text" class="form-input" id="loanTypeDisplay" readonly style="background:#f5f5f5;font-weight:600;">
                        </div>

                        <div class="form-row" style="margin-bottom:10px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-rupee-sign"></i> Loan Amount <span class="required">*</span></label>
                                <input type="number" class="form-input" id="loanAmount" placeholder="Enter loan amount" required>
                                <div class="form-error" id="loanAmountError">Please enter loan amount</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-calendar-alt"></i> Loan Tenure <span class="required">*</span></label>
                                <select class="form-select" id="loanTenure" required>
                                    <option value="">Select Tenure</option>
                                    <option value="12">12 months</option>
                                    <option value="24">24 months</option>
                                    <option value="36">36 months</option>
                                    <option value="48">48 months</option>
                                    <option value="60">60 months</option>
                                    <option value="72">72 months</option>
                                    <option value="84">84 months</option>
                                </select>
                                <div class="form-error" id="loanTenureError">Please select loan tenure</div>
                            </div>
                        </div>

                        <div class="form-row" style="margin-bottom:0;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-bullseye"></i> Loan Purpose <span class="required">*</span></label>
                                <select class="form-select" id="loanPurpose" required>
                                    <option value="">Select Purpose</option>
                                    <option value="new_car">🚗 New Car Purchase</option>
                                    <option value="used_car">🚘 Used Car Purchase</option>
                                    <option value="electric">⚡ Electric Vehicle</option>
                                    <option value="commercial">🚛 Commercial Vehicle</option>
                                    <option value="refinance">🔄 Refinance</option>
                                </select>
                                <div class="form-error" id="loanPurposeError">Please select loan purpose</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-car"></i> Vehicle Type <span class="required">*</span></label>
                                <select class="form-select" id="vehicleType" required>
                                    <option value="">Select Vehicle Type</option>
                                    <option value="hatchback">Hatchback</option>
                                    <option value="sedan">Sedan</option>
                                    <option value="suv">SUV</option>
                                    <option value="luxury">Luxury</option>
                                    <option value="electric">Electric</option>
                                    <option value="commercial">Commercial</option>
                                </select>
                                <div class="form-error" id="vehicleTypeError">Please select vehicle type</div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Details Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-user-circle"></i> Personal Details</div>
                        
                        <div class="form-row" style="margin-bottom:0;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-calendar-alt"></i> Date of Birth <span class="required">*</span></label>
                                <input type="date" class="form-input" id="dob" required>
                                <div class="form-error" id="dobError">Please select your date of birth</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-venus-mars"></i> Gender <span class="required">*</span></label>
                                <select class="form-select" id="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="form-error" id="genderError">Please select your gender</div>
                            </div>
                        </div>
                    </div>

                    <!-- Vehicle Details Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-car"></i> Vehicle Details</div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label><i class="fas fa-car"></i> Car Make / Model <span class="required">*</span></label>
                            <input type="text" class="form-input" id="carModel" placeholder="e.g. Hyundai i20, Maruti Suzuki Swift" required>
                            <div class="form-error" id="carModelError">Please enter car make/model</div>
                        </div>

                        <div class="form-row" style="margin-bottom:10px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-calendar-alt"></i> Manufacturing Year <span class="required">*</span></label>
                                <select class="form-select" id="manufacturingYear" required>
                                    <option value="">Select Year</option>
                                    <?php for($y = date('Y'); $y >= 2000; $y--): ?>
                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                                <div class="form-error" id="manufacturingYearError">Please select manufacturing year</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-rupee-sign"></i> Car Price (₹) <span class="required">*</span></label>
                                <input type="number" class="form-input" id="carPrice" placeholder="Enter car price" required>
                                <div class="form-error" id="carPriceError">Please enter car price</div>
                            </div>
                        </div>

                        <div class="form-row" style="margin-bottom:0;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-money-bill-wave"></i> Down Payment (₹)</label>
                                <input type="number" class="form-input" id="downPayment" placeholder="Enter down payment" value="0">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-city"></i> City <span class="required">*</span></label>
                                <input type="text" class="form-input" id="city" placeholder="Enter city" required>
                                <div class="form-error" id="cityError">Please enter your city</div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment & Income Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-briefcase"></i> Employment & Income</div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label><i class="fas fa-briefcase"></i> Employment Type <span class="required">*</span></label>
                            <select class="form-select" id="employmentType" required>
                                <option value="">Select Employment Type</option>
                                <option value="salaried">👔 Salaried</option>
                                <option value="self_employed">💼 Self-Employed</option>
                                <option value="business">🏢 Business Owner</option>
                                <option value="professional">👨‍⚕️ Professional</option>
                                <option value="retired">👴 Retired</option>
                            </select>
                            <div class="form-error" id="employmentTypeError">Please select employment type</div>
                        </div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label><i class="fas fa-building"></i> Company / Organization Name <span class="required">*</span></label>
                            <input type="text" class="form-input" id="companyName" placeholder="Enter company name" required>
                            <div class="form-error" id="companyNameError">Please enter company name</div>
                        </div>

                        <div class="form-row" style="margin-bottom:10px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-money-bill"></i> Monthly Income (₹) <span class="required">*</span></label>
                                <input type="number" class="form-input" id="monthlyIncome" placeholder="Enter monthly income" required>
                                <div class="form-error" id="monthlyIncomeError">Please enter monthly income</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-briefcase"></i> Work Experience (Years) <span class="required">*</span></label>
                                <input type="number" class="form-input" id="workExperience" placeholder="Years of experience" required>
                                <div class="form-error" id="workExperienceError">Please enter work experience</div>
                            </div>
                        </div>

                        <div class="form-row" style="margin-bottom:0;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-credit-card"></i> Existing Loans (₹)</label>
                                <input type="number" class="form-input" id="existingLoans" placeholder="Enter existing loans" value="0">
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-credit-card"></i> Current EMI (₹)</label>
                                <input type="number" class="form-input" id="currentEmi" placeholder="Enter current EMI" value="0">
                            </div>
                        </div>
                    </div>

                    <!-- Identification Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-id-card"></i> Identification</div>

                        <div class="form-row" style="margin-bottom:0;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-id-card"></i> Aadhar Number <span class="required">*</span></label>
                                <input type="text" class="form-input" id="aadharNumber" placeholder="Enter 12-digit Aadhar" maxlength="12" required>
                                <div class="form-error" id="aadharNumberError">Please enter valid 12-digit Aadhar</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-id-card"></i> PAN Number <span class="required">*</span></label>
                                <input type="text" class="form-input" id="panNumber" placeholder="Enter PAN (ABCDE1234F)" maxlength="10" required>
                                <div class="form-error" id="panNumberError">Please enter valid PAN number</div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-map-marker-alt"></i> Address</div>

                        <div class="form-group" style="margin-bottom:10px;">
                            <label><i class="fas fa-map-marker-alt"></i> Current Address <span class="required">*</span></label>
                            <input type="text" class="form-input" id="currentAddress" placeholder="Enter your current address" required>
                            <div class="form-error" id="currentAddressError">Please enter your address</div>
                        </div>

                        <div class="form-row" style="margin-bottom:0;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-city"></i> City <span class="required">*</span></label>
                                <input type="text" class="form-input" id="currentCity" placeholder="Enter city" required>
                                <div class="form-error" id="currentCityError">Please enter your city</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-map-pin"></i> PIN Code <span class="required">*</span></label>
                                <input type="text" class="form-input" id="currentPincode" placeholder="Enter PIN code" maxlength="6" required>
                                <div class="form-error" id="currentPincodeError">Please enter valid 6-digit PIN</div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>

                    <div class="form-note">
                        <i class="fas fa-lock"></i> Your information is secure and will only be used for processing your application.
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SUCCESS MODAL -->
    <!-- ============================================================ -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div style="text-align:center;">
                <div class="success-check"><i class="fas fa-check-circle"></i></div>
                <div class="success-title">Application Submitted!</div>
                <div class="success-sub">Your car loan application has been submitted successfully.</div>
                <div class="success-details-box">
                    <div class="success-detail-row">
                        <span class="s-label">Loan Type</span>
                        <span class="s-value" id="successLoanType">Car Loan</span>
                    </div>
                    <div class="success-detail-row">
                        <span class="s-label">Loan Amount</span>
                        <span class="s-value" id="successAmount">₹ 5,00,000</span>
                    </div>
                    <div class="success-detail-row">
                        <span class="s-label">Application ID</span>
                        <span class="s-value" id="successAppId">#12345</span>
                    </div>
                    <div class="success-detail-row">
                        <span class="s-label">Status</span>
                        <span class="s-value" style="color: var(--orange);">Pending</span>
                    </div>
                </div>
                <button class="apply-btn-modal" onclick="closeModal('successModal')">
                    <i class="fas fa-check"></i> Done
                </button>
            </div>
        </div>
    </div>

    <!-- ===== TOAST ===== -->
    <div id="toast" class="toast"></div>

    <div class="footer-note"><i class="fas fa-lock"></i> Secure & Trusted | RBI Compliant</div>
</div>

<script>
    // ============================================================
    // APPLICATION FORM
    // ============================================================
    let selectedLoanType = '';

    function openApplicationForm(loanType) {
        selectedLoanType = loanType || 'Car Loan';
        document.getElementById('selectedLoanType').textContent = selectedLoanType;
        document.getElementById('loanTypeDisplay').value = selectedLoanType;
        
        document.querySelectorAll('.form-error').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.form-input, .form-select').forEach(el => el.classList.remove('error'));
        
        document.getElementById('applicationModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeApplicationForm() {
        document.getElementById('applicationModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    function submitApplication(e) {
        e.preventDefault();
        
        // Get form values
        const loanType = document.getElementById('loanTypeDisplay').value;
        const loanAmount = document.getElementById('loanAmount').value.trim();
        const loanTenure = document.getElementById('loanTenure').value;
        const loanPurpose = document.getElementById('loanPurpose').value;
        const vehicleType = document.getElementById('vehicleType').value;
        const dob = document.getElementById('dob').value;
        const gender = document.getElementById('gender').value;
        const carModel = document.getElementById('carModel').value.trim();
        const manufacturingYear = document.getElementById('manufacturingYear').value;
        const carPrice = document.getElementById('carPrice').value.trim();
        const downPayment = document.getElementById('downPayment').value.trim() || 0;
        const city = document.getElementById('city').value.trim();
        const employmentType = document.getElementById('employmentType').value;
        const companyName = document.getElementById('companyName').value.trim();
        const monthlyIncome = document.getElementById('monthlyIncome').value.trim();
        const workExperience = document.getElementById('workExperience').value.trim();
        const existingLoans = document.getElementById('existingLoans').value.trim() || 0;
        const currentEmi = document.getElementById('currentEmi').value.trim() || 0;
        const aadharNumber = document.getElementById('aadharNumber').value.trim();
        const panNumber = document.getElementById('panNumber').value.trim().toUpperCase();
        const currentAddress = document.getElementById('currentAddress').value.trim();
        const currentCity = document.getElementById('currentCity').value.trim();
        const currentPincode = document.getElementById('currentPincode').value.trim();

        // Get user data from hidden fields
        const fullName = document.getElementById('fullNameHidden').value;
        const email = document.getElementById('emailHidden').value;
        const mobileNumber = document.getElementById('mobileHidden').value;

        // Validation
        let isValid = true;

        // Loan Details
        if (!loanAmount || parseFloat(loanAmount) <= 0) { showError('loanAmount', 'loanAmountError'); isValid = false; } else { clearError('loanAmount', 'loanAmountError'); }
        if (!loanTenure) { showError('loanTenure', 'loanTenureError'); isValid = false; } else { clearError('loanTenure', 'loanTenureError'); }
        if (!loanPurpose) { showError('loanPurpose', 'loanPurposeError'); isValid = false; } else { clearError('loanPurpose', 'loanPurposeError'); }
        if (!vehicleType) { showError('vehicleType', 'vehicleTypeError'); isValid = false; } else { clearError('vehicleType', 'vehicleTypeError'); }

        // Personal Details
        if (!dob) { showError('dob', 'dobError'); isValid = false; } else { clearError('dob', 'dobError'); }
        if (!gender) { showError('gender', 'genderError'); isValid = false; } else { clearError('gender', 'genderError'); }

        // Vehicle Details
        if (carModel.length < 2) { showError('carModel', 'carModelError'); isValid = false; } else { clearError('carModel', 'carModelError'); }
        if (!manufacturingYear) { showError('manufacturingYear', 'manufacturingYearError'); isValid = false; } else { clearError('manufacturingYear', 'manufacturingYearError'); }
        if (!carPrice || parseFloat(carPrice) <= 0) { showError('carPrice', 'carPriceError'); isValid = false; } else { clearError('carPrice', 'carPriceError'); }
        if (parseFloat(loanAmount) > parseFloat(carPrice)) {
            showToast('Loan amount cannot exceed car price', 'error');
            return;
        }
        if (city.length < 2) { showError('city', 'cityError'); isValid = false; } else { clearError('city', 'cityError'); }

        // Employment
        if (!employmentType) { showError('employmentType', 'employmentTypeError'); isValid = false; } else { clearError('employmentType', 'employmentTypeError'); }
        if (companyName.length < 2) { showError('companyName', 'companyNameError'); isValid = false; } else { clearError('companyName', 'companyNameError'); }
        if (!monthlyIncome || parseFloat(monthlyIncome) <= 0) { showError('monthlyIncome', 'monthlyIncomeError'); isValid = false; } else { clearError('monthlyIncome', 'monthlyIncomeError'); }
        if (!workExperience || parseFloat(workExperience) < 0) { showError('workExperience', 'workExperienceError'); isValid = false; } else { clearError('workExperience', 'workExperienceError'); }

        // Identification
        const aadharRegex = /^[0-9]{12}$/;
        if (!aadharRegex.test(aadharNumber)) { showError('aadharNumber', 'aadharNumberError'); isValid = false; } else { clearError('aadharNumber', 'aadharNumberError'); }
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        if (!panRegex.test(panNumber)) { showError('panNumber', 'panNumberError'); isValid = false; } else { clearError('panNumber', 'panNumberError'); }

        // Address
        if (currentAddress.length < 5) { showError('currentAddress', 'currentAddressError'); isValid = false; } else { clearError('currentAddress', 'currentAddressError'); }
        if (currentCity.length < 2) { showError('currentCity', 'currentCityError'); isValid = false; } else { clearError('currentCity', 'currentCityError'); }
        const pincodeRegex = /^[0-9]{6}$/;
        if (!pincodeRegex.test(currentPincode)) { showError('currentPincode', 'currentPincodeError'); isValid = false; } else { clearError('currentPincode', 'currentPincodeError'); }

        // Validate user data from session
        if (!fullName) { showToast('User data not found. Please login again.', 'error'); return; }
        if (!email) { showToast('User data not found. Please login again.', 'error'); return; }
        if (!mobileNumber) { showToast('User data not found. Please login again.', 'error'); return; }

        if (!isValid) {
            showToast('Please fill all required fields', 'error');
            return;
        }

        // Disable submit button
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        // Prepare data for submission
        const formData = new FormData();
        formData.append('loan_type', loanType);
        formData.append('loan_amount', loanAmount);
        formData.append('loan_tenure', loanTenure);
        formData.append('loan_purpose', loanPurpose);
        formData.append('vehicle_type', vehicleType);
        formData.append('dob', dob);
        formData.append('gender', gender);
        formData.append('car_model', carModel);
        formData.append('manufacturing_year', manufacturingYear);
        formData.append('car_price', carPrice);
        formData.append('down_payment', downPayment);
        formData.append('city', city);
        formData.append('employment_type', employmentType);
        formData.append('company_name', companyName);
        formData.append('monthly_income', monthlyIncome);
        formData.append('work_experience', workExperience);
        formData.append('existing_loans', existingLoans);
        formData.append('current_emi', currentEmi);
        formData.append('aadhar_number', aadharNumber);
        formData.append('pan_number', panNumber);
        formData.append('current_address', currentAddress);
        formData.append('current_city', currentCity);
        formData.append('current_pincode', currentPincode);
        // User data from session
        formData.append('full_name', fullName);
        formData.append('email', email);
        formData.append('mobile_number', mobileNumber);

        // Send data to server
        fetch('submit_car_loan.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.text();
        })
        .then(text => {
            console.log('Raw response:', text);
            try {
                const data = JSON.parse(text);
                return data;
            } catch (e) {
                console.error('JSON parse error:', e);
                if (text.includes('<br />') || text.includes('<!DOCTYPE') || text.includes('<b>')) {
                    throw new Error('Server error. Please check your PHP file.');
                }
                throw new Error('Invalid response: ' + text.substring(0, 100));
            }
        })
        .then(data => {
            console.log('Response data:', data);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
            
            if (data.success) {
                showSuccessModal(loanType, loanAmount, data.application_id);
                closeApplicationForm();
                showToast('Application submitted successfully!', 'success');
            } else {
                showToast(data.message || 'Failed to submit application', 'error');
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
            showToast('Error: ' + error.message, 'error');
        });
    }

    function showError(inputId, errorId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        if (input) input.classList.add('error');
        if (error) error.classList.add('show');
    }

    function clearError(inputId, errorId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        if (input) input.classList.remove('error');
        if (error) error.classList.remove('show');
    }

    // ============================================================
    // SUCCESS MODAL
    // ============================================================
    function showSuccessModal(loanType, amount, appId) {
        document.getElementById('successLoanType').textContent = loanType || 'Car Loan';
        document.getElementById('successAmount').textContent = '₹ ' + (parseFloat(amount) || 500000).toLocaleString('en-IN');
        document.getElementById('successAppId').textContent = '#' + (appId || Math.floor(Math.random() * 90000) + 10000);
        document.getElementById('successModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = '';
    }

    // ============================================================
    // TOAST HELPER
    // ============================================================
    function showToast(msg, type = '') {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast';
        if (type) t.classList.add(type);
        t.classList.add('show');
        clearTimeout(t._timeout);
        t._timeout = setTimeout(() => t.classList.remove('show'), 3500);
    }

    // ============================================================
    // REAL-TIME VALIDATION
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        // Aadhar - only numbers, max 12
        document.getElementById('aadharNumber').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
            if (this.value.length === 12) clearError('aadharNumber', 'aadharNumberError');
        });

        // PAN - auto uppercase
        document.getElementById('panNumber').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
            if (this.value.length === 10 && panRegex.test(this.value)) {
                clearError('panNumber', 'panNumberError');
            }
        });

        // Pincode - only numbers, max 6
        document.getElementById('currentPincode').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
            if (this.value.length === 6) clearError('currentPincode', 'currentPincodeError');
        });

        // Close modal on backdrop click
        document.getElementById('applicationModal').addEventListener('click', function(e) {
            if (e.target === this) closeApplicationForm();
        });

        // Close success modal on backdrop click
        document.getElementById('successModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal('successModal');
        });

        // Auto-calculate down payment
        document.getElementById('carPrice').addEventListener('input', function() {
            const price = parseFloat(this.value) || 0;
            const loanAmt = parseFloat(document.getElementById('loanAmount').value) || 0;
            if (price > 0 && loanAmt > 0) {
                const downPay = price - loanAmt;
                if (downPay >= 0) {
                    document.getElementById('downPayment').value = downPay;
                }
            }
        });

        document.getElementById('loanAmount').addEventListener('input', function() {
            const loanAmt = parseFloat(this.value) || 0;
            const price = parseFloat(document.getElementById('carPrice').value) || 0;
            if (price > 0 && loanAmt > 0) {
                const downPay = price - loanAmt;
                if (downPay >= 0) {
                    document.getElementById('downPayment').value = downPay;
                }
            }
        });
    });

    // ============================================================
    // EXPOSE GLOBALLY
    // ============================================================
    Object.assign(window, {
        openApplicationForm, closeApplicationForm, submitApplication,
        showSuccessModal, showToast, closeModal
    });
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>