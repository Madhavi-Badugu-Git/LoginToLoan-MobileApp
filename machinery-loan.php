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
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Machinery Loan - Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        :root {
            --primary: #1a237e;
            --primary-dark: #0d1757;
            --primary-light: #283593;
            --secondary: #00c853;
            --accent: #ff6b35;
            --orange: #ff9800;
            --text-primary: #1a1a2e;
            --text-secondary: #666666;
            --text-tertiary: #999999;
            --background: #f8f9fc;
            --white: #ffffff;
            --border: #eef2f6;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --success: #00c853;
            --warning: #ff9800;
            --danger: #ef4444;
        }

        html { scroll-behavior: smooth; overscroll-behavior: contain; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
            min-height: 100vh;
            position: relative;
            padding-bottom: 20px;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        /* ===== PAGE HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, #1a237e, #283593, #3949ab);
            padding: 20px 16px 30px;
            color: white;
            border-radius: 0 0 24px 24px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .page-header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }
        .page-header .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }
        .page-header .back-btn i { font-size: 14px; }
        .page-header h1 {
            font-size: 24px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }
        .page-header p {
            font-size: 13px;
            opacity: 0.8;
            margin-top: 6px;
            position: relative;
            z-index: 1;
        }
        .page-header .header-icon {
            position: absolute;
            right: 16px;
            bottom: 16px;
            font-size: 60px;
            opacity: 0.15;
            z-index: 0;
        }

        /* ===== SECTION HEADERS ===== */
        .section-header { display: flex; justify-content: space-between; align-items: baseline; padding: 0 14px; margin: 20px 0 10px; }
        .section-header h2 { font-size: 16px; font-weight: 700; color: var(--text-primary); }
        .section-header a { font-size: 11px; color: var(--primary); text-decoration: none; font-weight: 600; cursor: pointer; }

        /* ===== FEATURES ===== */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            padding: 0 14px;
            margin: 10px 0 16px;
        }
        .feature-card {
            background: var(--white);
            border-radius: 12px;
            padding: 12px 6px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .feature-card .feature-icon {
            font-size: 22px;
            margin-bottom: 4px;
            color: var(--primary);
        }
        .feature-card .feature-title {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .feature-card .feature-desc {
            font-size: 8px;
            color: var(--text-tertiary);
            margin-top: 2px;
        }

        /* ===== MACHINERY TYPES ===== */
        .machinery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            padding: 0 14px;
            margin: 10px 0 16px;
        }
        .machinery-card {
            background: var(--white);
            border-radius: 10px;
            padding: 10px 6px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .machinery-card:active { transform: scale(0.95); background: #e8eaf6; }
        .machinery-card .mach-icon {
            font-size: 22px;
            color: var(--primary);
            margin-bottom: 3px;
        }
        .machinery-card .mach-name {
            font-size: 9px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ===== LOAN OFFERS ===== */
        .offers-list {
            padding: 0 14px;
        }
        .offer-item {
            background: var(--white);
            border-radius: 14px;
            padding: 14px 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .offer-item:active { transform: scale(0.98); background: #e8eaf6; }
        .offer-item .offer-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .offer-item .offer-info {
            flex: 1;
        }
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
            font-size: 8px;
            color: var(--primary);
            font-weight: 600;
            background: #e8eaf6;
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 3px;
        }
        .offer-item .offer-arrow {
            color: var(--text-tertiary);
            font-size: 12px;
        }

        /* ===== EMI CALCULATOR ===== */
        .emi-section {
            padding: 0 14px;
            margin: 16px 0 10px;
        }
        .emi-box {
            background: var(--white);
            border-radius: 14px;
            padding: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .emi-box .emi-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
        }
        .emi-box .emi-title i { color: var(--primary); margin-right: 6px; }
        .emi-box .form-group {
            margin-bottom: 10px;
        }
        .emi-box .form-group label {
            display: block;
            font-size: 10px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 3px;
        }
        .emi-box .form-group label i { margin-right: 4px; color: var(--primary); }
        .emi-box .form-group input,
        .emi-box .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
            background: var(--white);
        }
        .emi-box .form-group input:focus,
        .emi-box .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,35,126,0.1);
        }
        .emi-box .emi-result {
            margin-top: 12px;
            padding: 12px;
            background: #e8eaf6;
            border-radius: 10px;
            text-align: center;
        }
        .emi-box .emi-result .emi-amount {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
        }
        .emi-box .emi-result .emi-label {
            font-size: 10px;
            color: var(--text-secondary);
        }
        .emi-box .calculate-btn {
            width: 100%;
            padding: 8px;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            margin-top: 4px;
        }
        .emi-box .calculate-btn:active { transform: scale(0.98); }

        /* ===== WHY CHOOSE ===== */
        .why-section {
            padding: 0 14px;
            margin: 16px 0 10px;
        }
        .why-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .why-card {
            background: var(--white);
            border-radius: 12px;
            padding: 12px 10px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .why-card .why-icon {
            font-size: 20px;
            color: var(--primary);
            margin-bottom: 4px;
        }
        .why-card .why-text {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ===== APPLY BUTTON ===== */
        .apply-fixed-btn {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            max-width: 480px;
            margin: 0 auto;
            padding: 12px 16px;
            background: var(--white);
            border-top: 1px solid var(--border);
            box-shadow: 0 -4px 12px rgba(0,0,0,0.08);
            z-index: 100;
        }
        .apply-fixed-btn .apply-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .apply-fixed-btn .apply-btn:active { transform: scale(0.97); }

        /* ===== MODAL ===== */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: 0.2s;
        }
        .modal.active {
            visibility: visible;
            opacity: 1;
        }
        .modal-content {
            background: var(--white);
            border-radius: 20px;
            padding: 20px;
            width: 94%;
            max-width: 400px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalPop 0.3s ease;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }
        @keyframes modalPop {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border);
        }
        .modal-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }
        .modal-header h3 i { color: var(--primary); margin-right: 6px; }
        .modal-close {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--border);
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: 0.2s;
        }
        .modal-close:active { background: #e0e0e0; }

        /* ===== MULTI-STEP FORM ===== */
        .step-indicators {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 0 10px;
        }
        .step-dot {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .step-dot .dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-tertiary);
            background: var(--border);
            border: 2px solid var(--border);
            transition: all 0.3s ease;
        }
        .step-dot .dot.active {
            color: white;
            background: var(--primary);
            border-color: var(--primary);
        }
        .step-dot .dot.done {
            color: white;
            background: var(--success);
            border-color: var(--success);
        }
        .step-dot .step-label {
            font-size: 9px;
            color: var(--text-tertiary);
            font-weight: 500;
            display: none;
        }
        .step-dot .step-label.active { display: block; color: var(--primary); }
        .step-dot .step-label.done { display: block; color: var(--success); }
        .step-line {
            width: 30px;
            height: 2px;
            background: var(--border);
            transition: all 0.3s ease;
        }
        .step-line.done { background: var(--success); }

        .form-step {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .form-step.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 12px;
        }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--text-secondary);
        }
        .form-group label .required-star { color: #c62828; }
        .form-group label i { margin-right: 6px; color: var(--primary); width: 16px; }
        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
            background: var(--white);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,35,126,0.1);
        }
        .form-input.file-input {
            padding: 6px 10px;
            cursor: pointer;
        }
        .form-input.file-input::-webkit-file-upload-button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 10px;
        }
        .form-input.file-input::-webkit-file-upload-button:hover {
            background: var(--primary-light);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .form-section-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 14px 0 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid var(--border);
        }
        .form-section-title i { color: var(--primary); margin-right: 6px; }

        .file-upload-status {
            font-size: 9px;
            color: var(--text-tertiary);
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .file-upload-status .uploaded { color: var(--success); }
        .file-upload-status .pending { color: var(--warning); }

        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 12px 0;
        }
        .terms-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            flex-shrink: 0;
            cursor: pointer;
            accent-color: var(--primary);
        }
        .terms-check label {
            font-size: 12px;
            color: var(--text-secondary);
            cursor: pointer;
            line-height: 1.4;
        }
        .terms-check label a {
            color: var(--primary);
            text-decoration: underline;
        }

        .step-buttons {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }
        .step-buttons .btn-prev,
        .step-buttons .btn-next,
        .step-buttons .btn-submit {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            flex: 1;
        }
        .step-buttons .btn-prev:active,
        .step-buttons .btn-next:active,
        .step-buttons .btn-submit:active { transform: scale(0.97); }
        .step-buttons .btn-prev {
            background: var(--border);
            color: var(--text-secondary);
        }
        .step-buttons .btn-next {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
        }
        .step-buttons .btn-submit {
            background: linear-gradient(135deg, #00c853, #00e676);
            color: white;
        }
        .step-buttons .btn-prev:disabled,
        .step-buttons .btn-next:disabled,
        .step-buttons .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ===== TOAST ===== */
        .toast {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--text-primary);
            color: white;
            padding: 10px 18px;
            border-radius: 24px;
            font-size: 12px;
            z-index: 1000;
            transition: transform 0.3s;
            white-space: nowrap;
            max-width: 90%;
            text-align: center;
        }
        .toast.show { transform: translateX(-50%) translateY(0); }

        .footer-note {
            text-align: center;
            font-size: 9px;
            color: var(--text-tertiary);
            padding: 14px;
            border-top: 1px solid var(--border);
            margin-top: 16px;
            padding-bottom: 80px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 374px) {
            .page-header h1 { font-size: 20px; }
            .page-header .header-icon { font-size: 40px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .feature-card { padding: 10px 4px; }
            .feature-card .feature-icon { font-size: 18px; }
            .machinery-grid { grid-template-columns: repeat(2, 1fr); }
            .form-row { grid-template-columns: 1fr; }
            .step-dot .dot { width: 28px; height: 28px; font-size: 10px; }
            .step-line { width: 20px; }
        }

        @media (max-width: 480px) {
            .page-header { padding: 16px 14px 24px; }
            .page-header h1 { font-size: 22px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .feature-card .feature-title { font-size: 9px; }
            .feature-card .feature-desc { font-size: 7px; }
            .offer-item { padding: 12px 14px; }
            .offer-item .offer-icon { width: 38px; height: 38px; font-size: 16px; }
            .offer-item .offer-info .offer-name { font-size: 12px; }
            .machinery-grid { grid-template-columns: repeat(3, 1fr); }
            .machinery-card .mach-name { font-size: 8px; }
            .modal-content { padding: 16px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
            .step-dot .dot { width: 28px; height: 28px; font-size: 10px; }
            .step-line { width: 20px; }
            .step-dot .step-label { display: none !important; }
        }
    </style>
</head>
<body>

<div class="app-container">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <a href="javascript:history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1>🔧 Machinery Loan</h1>
        <p>Finance your industrial and agricultural equipment with ease</p>
        <div class="header-icon"><i class="fas fa-industry"></i></div>
    </div>

    <!-- FEATURES -->
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-percent"></i></div>
            <div class="feature-title">Low Interest</div>
            <div class="feature-desc">From 8.5% p.a.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-clock"></i></div>
            <div class="feature-title">Quick Approval</div>
            <div class="feature-desc">3-5 days</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="feature-title">High Amount</div>
            <div class="feature-desc">Up to ₹5Cr</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="feature-title">Flexible Tenure</div>
            <div class="feature-desc">Up to 7 years</div>
        </div>
    </div>

    <!-- MACHINERY TYPES -->
    <div class="section-header">
        <h2>⚙️ Machinery Types</h2>
        <a href="#" onclick="openApplyModal()">Apply Now</a>
    </div>
    <div class="machinery-grid">
        <div class="machinery-card" onclick="openApplyModal()">
            <div class="mach-icon"><i class="fas fa-tractor"></i></div>
            <div class="mach-name">Agricultural</div>
        </div>
        <div class="machinery-card" onclick="openApplyModal()">
            <div class="mach-icon"><i class="fas fa-industry"></i></div>
            <div class="mach-name">Industrial</div>
        </div>
        <div class="machinery-card" onclick="openApplyModal()">
            <div class="mach-icon"><i class="fas fa-truck"></i></div>
            <div class="mach-name">Construction</div>
        </div>
        <div class="machinery-card" onclick="openApplyModal()">
            <div class="mach-icon"><i class="fas fa-print"></i></div>
            <div class="mach-name">Printing</div>
        </div>
        <div class="machinery-card" onclick="openApplyModal()">
            <div class="mach-icon"><i class="fas fa-utensils"></i></div>
            <div class="mach-name">Food Processing</div>
        </div>
        <div class="machinery-card" onclick="openApplyModal()">
            <div class="mach-icon"><i class="fas fa-microchip"></i></div>
            <div class="mach-name">IT Equipment</div>
        </div>
        <div class="machinery-card" onclick="openApplyModal()">
            <div class="mach-icon"><i class="fas fa-syringe"></i></div>
            <div class="mach-name">Medical</div>
        </div>
        <div class="machinery-card" onclick="openApplyModal()">
            <div class="mach-icon"><i class="fas fa-cogs"></i></div>
            <div class="mach-name">Manufacturing</div>
        </div>
        <div class="machinery-card" onclick="openApplyModal()">
            <div class="mach-icon"><i class="fas fa-bolt"></i></div>
            <div class="mach-name">Power Equipment</div>
        </div>
    </div>

    <!-- LOAN OFFERS -->
    <div class="section-header">
        <h2>💰 Loan Offers</h2>
        <a href="#" onclick="openApplyModal()">Apply Now</a>
    </div>
    <div class="offers-list">
        <div class="offer-item" onclick="openApplyModal()">
            <div class="offer-icon" style="background: #e8f5e9; color: #2e7d32;">
                <i class="fas fa-industry"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">HDFC Machinery Loan</div>
                <div class="offer-desc">Special rates for equipment financing</div>
                <span class="offer-tag">8.5% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal()">
            <div class="offer-icon" style="background: #fff3e0; color: #e65100;">
                <i class="fas fa-industry"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">SBI Machinery Loan</div>
                <div class="offer-desc">Government bank scheme for equipment</div>
                <span class="offer-tag">8.25% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal()">
            <div class="offer-icon" style="background: #e3f2fd; color: #1565c0;">
                <i class="fas fa-industry"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">ICICI Machinery Loan</div>
                <div class="offer-desc">Quick approval for equipment purchase</div>
                <span class="offer-tag">8.75% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal()">
            <div class="offer-icon" style="background: #fce4ec; color: #c62828;">
                <i class="fas fa-industry"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">Axis Machinery Loan</div>
                <div class="offer-desc">Flexible funding for all machinery</div>
                <span class="offer-tag">8.9% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>
    </div>

    <!-- EMI CALCULATOR -->
    <div class="emi-section">
        <div class="emi-box">
            <div class="emi-title"><i class="fas fa-calculator"></i> EMI Calculator</div>
            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> Loan Amount (₹)</label>
                <input type="number" id="emiLoanAmount" placeholder="Enter loan amount" value="1000000">
            </div>
            <div class="form-group">
                <label><i class="fas fa-percent"></i> Interest Rate (% p.a.)</label>
                <input type="number" id="emiInterestRate" placeholder="Enter interest rate" value="8.5" step="0.1">
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Loan Tenure</label>
                <select id="emiTenure">
                    <option value="1">1 Year</option>
                    <option value="2">2 Years</option>
                    <option value="3" selected>3 Years</option>
                    <option value="5">5 Years</option>
                    <option value="7">7 Years</option>
                </select>
            </div>
            <button class="calculate-btn" onclick="calculateEMI()"><i class="fas fa-calculator"></i> Calculate EMI</button>
            <div class="emi-result" id="emiResult">
                <div class="emi-label">Monthly EMI</div>
                <div class="emi-amount" id="emiAmount">₹ 31,576</div>
                <div style="font-size:9px;color:var(--text-tertiary);margin-top:3px;">
                    Total: ₹<span id="totalPayment">11,36,736</span> | Interest: ₹<span id="totalInterest">1,36,736</span>
                </div>
            </div>
        </div>
    </div>

    <!-- WHY CHOOSE -->
    <div class="why-section">
        <div class="section-header" style="padding: 0; margin: 0 0 10px 0;">
            <h2>✅ Why Choose Machinery Loan</h2>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Up to 90% Financing</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Quick Disbursal</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Tax Benefits</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Flexible Repayment</div>
            </div>
        </div>
    </div>

    <!-- FIXED APPLY BUTTON -->
    <div class="apply-fixed-btn">
        <button class="apply-btn" onclick="openApplyModal()">
            <i class="fas fa-industry"></i> Apply for Machinery Loan
        </button>
    </div>

    <!-- ===== STEP-BY-STEP MULTI-FORM MODAL ===== -->
    <div id="applyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-industry"></i> Apply for Machinery Loan</h3>
                <button class="modal-close" onclick="closeModal('applyModal')">✕</button>
            </div>

            <!-- Step Indicators -->
            <div class="step-indicators">
                <div class="step-dot">
                    <div class="dot active" id="dot1">1</div>
                    <span class="step-label active" id="label1">Personal</span>
                </div>
                <div class="step-line" id="line1"></div>
                <div class="step-dot">
                    <div class="dot" id="dot2">2</div>
                    <span class="step-label" id="label2">Business</span>
                </div>
                <div class="step-line" id="line2"></div>
                <div class="step-dot">
                    <div class="dot" id="dot3">3</div>
                    <span class="step-label" id="label3">Documents</span>
                </div>
            </div>

            <!-- STEP 1: Personal Information -->
            <div class="form-step active" id="step1">
                <div class="form-section-title"><i class="fas fa-user"></i> Personal Information</div>

                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Loan Type</label>
                    <input type="text" class="form-input" readonly style="background:#f5f5f5;font-weight:600;" value="Machinery Loan">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name <span class="required-star">*</span></label>
                        <input type="text" class="form-input" id="applicantName" value="<?php echo $fullname; ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Date of Birth <span class="required-star">*</span></label>
                        <input type="date" class="form-input" id="dob">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email <span class="required-star">*</span></label>
                        <input type="email" class="form-input" id="applicantEmail" value="<?php echo $email; ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Phone <span class="required-star">*</span></label>
                        <input type="tel" class="form-input" id="applicantPhone" value="<?php echo $phone; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Address <span class="required-star">*</span></label>
                    <input type="text" class="form-input" id="address" placeholder="Enter your current address">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-city"></i> City <span class="required-star">*</span></label>
                        <input type="text" class="form-input" id="city" placeholder="Enter your city">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-code"></i> PIN Code <span class="required-star">*</span></label>
                        <input type="text" class="form-input" id="pincode" placeholder="PIN code" maxlength="6">
                    </div>
                </div>

                <div class="step-buttons">
                    <button class="btn-prev" disabled>Previous</button>
                    <button class="btn-next" onclick="goToStep(2)">Next →</button>
                </div>
            </div>

            <!-- STEP 2: Business & Machinery Details -->
            <div class="form-step" id="step2">
                <div class="form-section-title"><i class="fas fa-briefcase"></i> Business & Machinery Details</div>

                <div class="form-group">
                    <label><i class="fas fa-building"></i> Business Name <span class="required-star">*</span></label>
                    <input type="text" class="form-input" id="businessName" placeholder="Enter your business name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> Business Type <span class="required-star">*</span></label>
                        <select class="form-input" id="businessType">
                            <option value="">Select Type</option>
                            <option value="sole_proprietorship">Sole Proprietorship</option>
                            <option value="partnership">Partnership</option>
                            <option value="llp">LLP</option>
                            <option value="pvt_ltd">Private Limited</option>
                            <option value="public_ltd">Public Limited</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Business Since</label>
                        <input type="number" class="form-input" id="businessSince" placeholder="Year" min="2000" max="2025">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-file-invoice"></i> GST Number</label>
                        <input type="text" class="form-input" id="gstNumber" placeholder="GST number (if applicable)">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> Udyam Number</label>
                        <input type="text" class="form-input" id="udyamNumber" placeholder="Udyam number (if applicable)">
                    </div>
                </div>

                <div class="form-section-title"><i class="fas fa-cogs"></i> Machinery Details</div>

                <div class="form-group">
                    <label><i class="fas fa-tools"></i> Machinery Type <span class="required-star">*</span></label>
                    <select class="form-input" id="machineryType">
                        <option value="">Select Machinery Type</option>
                        <option value="agricultural">Agricultural Equipment</option>
                        <option value="industrial">Industrial Machinery</option>
                        <option value="construction">Construction Equipment</option>
                        <option value="printing">Printing Machinery</option>
                        <option value="food_processing">Food Processing Equipment</option>
                        <option value="it_equipment">IT Equipment</option>
                        <option value="medical">Medical Equipment</option>
                        <option value="manufacturing">Manufacturing Machinery</option>
                        <option value="power_equipment">Power Equipment</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Machinery Brand/Model</label>
                    <input type="text" class="form-input" id="machineryModel" placeholder="Enter brand and model name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-rupee-sign"></i> Machinery Cost (₹) <span class="required-star">*</span></label>
                        <input type="number" class="form-input" id="machineryCost" placeholder="Cost of machinery">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-percent"></i> Down Payment (%)</label>
                        <select class="form-input" id="downPayment">
                            <option value="10">10%</option>
                            <option value="15">15%</option>
                            <option value="20" selected>20%</option>
                            <option value="25">25%</option>
                            <option value="30">30%</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-file-alt"></i> Machinery Description</label>
                    <textarea class="form-input" id="machineryDesc" rows="2" placeholder="Brief description of the machinery and its purpose" style="resize:vertical;"></textarea>
                </div>

                <div class="form-section-title"><i class="fas fa-coins"></i> Financial Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-rupee-sign"></i> Annual Revenue (₹) <span class="required-star">*</span></label>
                        <input type="number" class="form-input" id="annualRevenue" placeholder="Annual revenue">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-rupee-sign"></i> Monthly Expenses</label>
                        <input type="number" class="form-input" id="monthlyExpenses" placeholder="Monthly expenses">
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-rupee-sign"></i> Existing Loan/Debt</label>
                    <input type="number" class="form-input" id="existingDebt" placeholder="Existing debt amount" value="0">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-hand-holding-usd"></i> Loan Amount (₹) <span class="required-star">*</span></label>
                        <input type="number" class="form-input" id="loanAmount" placeholder="Loan amount" min="100000">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Tenure</label>
                        <select class="form-input" id="loanTenure">
                            <option value="1">1 Year</option>
                            <option value="2">2 Years</option>
                            <option value="3" selected>3 Years</option>
                            <option value="5">5 Years</option>
                            <option value="7">7 Years</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-bullseye"></i> Purpose of Loan <span class="required-star">*</span></label>
                    <select class="form-input" id="loanPurpose">
                        <option value="">Select Purpose</option>
                        <option value="new_purchase">New Equipment Purchase</option>
                        <option value="replacement">Equipment Replacement</option>
                        <option value="upgrade">Technology/Equipment Upgrade</option>
                        <option value="expansion">Business Expansion</option>
                        <option value="working_capital">Working Capital</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="step-buttons">
                    <button class="btn-prev" onclick="goToStep(1)">← Previous</button>
                    <button class="btn-next" onclick="goToStep(3)">Next →</button>
                </div>
            </div>

            <!-- STEP 3: Document Upload & Terms -->
            <div class="form-step" id="step3">
                <div class="form-section-title"><i class="fas fa-file-upload"></i> Upload Documents</div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> PAN Card <span class="required-star">*</span></label>
                        <input type="file" class="form-input file-input" id="panFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> Aadhaar Card <span class="required-star">*</span></label>
                        <input type="file" class="form-input file-input" id="aadhaarFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-file-pdf"></i> Business Registration <span class="required-star">*</span></label>
                        <input type="file" class="form-input file-input" id="registrationFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-file-invoice"></i> GST Certificate</label>
                        <input type="file" class="form-input file-input" id="gstFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-file-pdf"></i> Machinery Quotation <span class="required-star">*</span></label>
                        <input type="file" class="form-input file-input" id="quotationFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-bank"></i> Bank Statement</label>
                        <input type="file" class="form-input file-input" id="bankStatementFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-file-invoice"></i> ITR Document</label>
                        <input type="file" class="form-input file-input" id="itrFile" accept=".pdf">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF, max 5MB</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Passport Photo <span class="required-star">*</span></label>
                        <input type="file" class="form-input file-input" id="photoFile" accept=".jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> JPG/PNG, max 2MB</div>
                    </div>
                </div>

                <div class="form-section-title"><i class="fas fa-check-circle"></i> Terms & Conditions</div>

                <div class="terms-check">
                    <input type="checkbox" id="termsCheck">
                    <label for="termsCheck">I agree to the <a href="#" onclick="showToast('Terms & Conditions accepted')">Terms & Conditions</a> and confirm all information is true.</label>
                </div>

                <div class="step-buttons">
                    <button class="btn-prev" onclick="goToStep(2)">← Previous</button>
                    <button class="btn-submit" onclick="submitMachineryApplication()">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="toast"></div>

    <div class="footer-note"><i class="fas fa-lock"></i> Secure & Trusted | RBI Compliant</div>
</div>

<script>
    // ============================================================
    // STEP NAVIGATION
    // ============================================================
    let currentStep = 1;
    const totalSteps = 3;

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;

        if (step > currentStep) {
            if (!validateStep(currentStep)) {
                return;
            }
        }

        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));

        const targetStep = document.getElementById('step' + step);
        if (targetStep) targetStep.classList.add('active');

        for (let i = 1; i <= totalSteps; i++) {
            const dot = document.getElementById('dot' + i);
            const label = document.getElementById('label' + i);
            const line = document.getElementById('line' + (i - 1));

            dot.classList.remove('active', 'done');
            if (label) label.classList.remove('active', 'done');

            if (i < step) {
                dot.classList.add('done');
                if (label) label.classList.add('done');
                if (line) line.classList.add('done');
            } else if (i === step) {
                dot.classList.add('active');
                if (label) label.classList.add('active');
                if (line) line.classList.remove('done');
            } else {
                if (line) line.classList.remove('done');
            }
        }

        currentStep = step;
    }

    function validateStep(step) {
        let isValid = true;
        let fields = [];

        if (step === 1) {
            fields = ['applicantName', 'applicantEmail', 'applicantPhone', 'dob', 'address', 'city', 'pincode'];
        } else if (step === 2) {
            fields = ['businessName', 'businessType', 'machineryType', 'machineryCost', 'annualRevenue', 'loanAmount', 'loanPurpose'];
        }

        fields.forEach(id => {
            const field = document.getElementById(id);
            if (!field || !field.value || field.value.trim() === '') {
                field.style.borderColor = '#c62828';
                isValid = false;
            } else {
                field.style.borderColor = '';
            }
        });

        if (!isValid) {
            showToast('Please fill all required fields');
        }

        return isValid;
    }

    // ============================================================
    // EMI CALCULATOR
    // ============================================================
    function calculateEMI() {
        const amount = parseFloat(document.getElementById('emiLoanAmount').value);
        const rate = parseFloat(document.getElementById('emiInterestRate').value);
        const years = parseInt(document.getElementById('emiTenure').value);
        const tenure = years * 12;

        if (!amount || !rate || !years) {
            return;
        }

        const monthlyRate = rate / 12 / 100;
        const emi = amount * monthlyRate * Math.pow(1 + monthlyRate, tenure) / (Math.pow(1 + monthlyRate, tenure) - 1);
        const totalPayment = emi * tenure;
        const totalInterest = totalPayment - amount;

        document.getElementById('emiAmount').textContent = '₹ ' + emi.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('totalPayment').textContent = totalPayment.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('totalInterest').textContent = totalInterest.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateEMI();
        document.getElementById('emiLoanAmount').addEventListener('input', calculateEMI);
        document.getElementById('emiInterestRate').addEventListener('input', calculateEMI);
        document.getElementById('emiTenure').addEventListener('change', calculateEMI);
    });

    // ============================================================
    // APPLY MODAL
    // ============================================================
    function openApplyModal() {
        document.getElementById('applyModal').classList.add('active');
        goToStep(1);
        document.querySelectorAll('.file-upload-status .pending').forEach(el => {
            el.textContent = '⚠️';
            el.className = 'pending';
        });
    }

    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function() {
            const statusDiv = this.parentElement.querySelector('.file-upload-status span');
            if (this.files.length > 0) {
                statusDiv.textContent = '✅';
                statusDiv.className = 'uploaded';
            } else {
                statusDiv.textContent = '⚠️';
                statusDiv.className = 'pending';
            }
        });
    });

    function submitMachineryApplication() {
        const requiredFiles = ['panFile', 'aadhaarFile', 'registrationFile', 'quotationFile', 'photoFile'];
        let missingFiles = [];
        requiredFiles.forEach(id => {
            const file = document.getElementById(id);
            if (!file.files || file.files.length === 0) {
                missingFiles.push(id);
            }
        });

        if (!document.getElementById('termsCheck').checked) {
            showToast('Please accept the Terms & Conditions');
            return;
        }

        if (missingFiles.length > 0) {
            showToast('Please upload all required documents');
            return;
        }

        const name = document.getElementById('applicantName').value;
        const business = document.getElementById('businessName').value;
        const machType = document.getElementById('machineryType').value;
        const amount = document.getElementById('loanAmount').value;

        showToast('✓ Machinery Loan application submitted for ' + business + ' (' + machType + ')!');
        closeModal('applyModal');

        document.querySelectorAll('.file-input').forEach(input => input.value = '');
        document.querySelectorAll('.file-upload-status span').forEach(el => {
            el.textContent = '⚠️';
            el.className = 'pending';
        });
        document.getElementById('termsCheck').checked = false;
        goToStep(1);
    }

    // ============================================================
    // TOAST & MODAL HELPERS
    // ============================================================
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(t._timeout);
        t._timeout = setTimeout(() => t.classList.remove('show'), 3000);
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    document.querySelectorAll('.modal').forEach(m => {
        m.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    document.getElementById('pincode').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    // ============================================================
    // EXPOSE GLOBALLY
    // ============================================================
    Object.assign(window, {
        goToStep, calculateEMI, openApplyModal, submitMachineryApplication,
        showToast, closeModal
    });
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>