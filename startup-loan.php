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
    <title>Startup Loan - Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        :root {
            --primary: #1a237e; --primary-dark: #0d1757; --primary-light: #283593;
            --secondary: #00c853; --accent: #ff6b35; --orange: #ff9800;
            --text-primary: #1a1a2e; --text-secondary: #666666; --text-tertiary: #999999;
            --background: #f8f9fc; --white: #ffffff; --border: #eef2f6;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --success: #00c853; --warning: #ff9800; --danger: #ef4444;
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

        .page, .modal-content, .menu-drawer {
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        /* ===== PAGE HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, #e65100, #ff6f00, #f57c00);
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
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            padding: 0 14px;
            margin: 10px 0 16px;
        }
        .feature-card {
            background: var(--white);
            border-radius: 14px;
            padding: 16px 12px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .feature-card .feature-icon {
            font-size: 28px;
            margin-bottom: 6px;
            color: #e65100;
        }
        .feature-card .feature-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .feature-card .feature-desc {
            font-size: 9px;
            color: var(--text-tertiary);
            margin-top: 2px;
        }

        /* ===== LOAN OFFERS ===== */
        .offers-list {
            padding: 0 14px;
        }
        .offer-item {
            background: var(--white);
            border-radius: 14px;
            padding: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .offer-item:active { transform: scale(0.98); background: #fff3e0; }
        .offer-item .offer-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .offer-item .offer-info {
            flex: 1;
        }
        .offer-item .offer-info .offer-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .offer-item .offer-info .offer-desc {
            font-size: 11px;
            color: var(--text-secondary);
            margin-top: 2px;
        }
        .offer-item .offer-info .offer-tag {
            font-size: 9px;
            color: #e65100;
            font-weight: 600;
            background: #fff3e0;
            padding: 2px 10px;
            border-radius: 12px;
            display: inline-block;
            margin-top: 4px;
        }
        .offer-item .offer-arrow {
            color: var(--text-tertiary);
            font-size: 14px;
        }

        /* ===== BUSINESS STAGES ===== */
        .stage-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            padding: 0 14px;
            margin: 10px 0 16px;
        }
        .stage-card {
            background: var(--white);
            border-radius: 12px;
            padding: 14px 8px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .stage-card:active { transform: scale(0.95); background: #fff3e0; }
        .stage-card .stage-icon {
            font-size: 28px;
            color: #e65100;
            margin-bottom: 4px;
        }
        .stage-card .stage-name {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ===== DOCUMENT CHECKLIST ===== */
        .doc-section {
            padding: 0 14px;
            margin: 16px 0 10px;
        }
        .doc-box {
            background: var(--white);
            border-radius: 14px;
            padding: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .doc-box .doc-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
        }
        .doc-box .doc-title i { color: #e65100; margin-right: 6px; }
        .doc-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
        }
        .doc-item:last-child { border-bottom: none; }
        .doc-item .doc-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
            background: #fff3e0;
            color: #e65100;
        }
        .doc-item .doc-info {
            flex: 1;
        }
        .doc-item .doc-info .doc-name {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .doc-item .doc-info .doc-desc {
            font-size: 9px;
            color: var(--text-tertiary);
        }
        .doc-item .doc-status {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 12px;
        }
        .doc-status.required { background: #fce4ec; color: #c62828; }
        .doc-status.optional { background: #e3f2fd; color: #1565c0; }
        .doc-status.verified { background: #e8f5e9; color: #2e7d32; }

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
        .emi-box .emi-title i { color: #e65100; margin-right: 6px; }
        .emi-box .form-group {
            margin-bottom: 12px;
        }
        .emi-box .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }
        .emi-box .form-group label i { margin-right: 4px; color: #e65100; }
        .emi-box .form-group input,
        .emi-box .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
            background: var(--white);
        }
        .emi-box .form-group input:focus,
        .emi-box .form-group select:focus {
            outline: none;
            border-color: #e65100;
            box-shadow: 0 0 0 3px rgba(230,81,0,0.1);
        }
        .emi-box .emi-result {
            margin-top: 12px;
            padding: 12px;
            background: #fff3e0;
            border-radius: 10px;
            text-align: center;
        }
        .emi-box .emi-result .emi-amount {
            font-size: 24px;
            font-weight: 800;
            color: #e65100;
        }
        .emi-box .emi-result .emi-label {
            font-size: 11px;
            color: var(--text-secondary);
        }
        .emi-box .calculate-btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #e65100, #f57c00);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 13px;
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
            padding: 14px 10px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .why-card .why-icon {
            font-size: 22px;
            color: #e65100;
            margin-bottom: 4px;
        }
        .why-card .why-text {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }

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
            border-radius: 24px;
            padding: 20px;
            width: 92%;
            max-width: 380px;
            max-height: 85vh;
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
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border);
        }
        .modal-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary);
        }
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
        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--text-secondary);
        }
        .form-group label i { margin-right: 6px; color: var(--accent); }
        .form-group label .required-star { color: #c62828; }
        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
            background: var(--white);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .form-input.file-input {
            padding: 8px 12px;
            cursor: pointer;
        }
        .form-input.file-input::-webkit-file-upload-button {
            background: #e65100;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 11px;
        }
        .form-input.file-input::-webkit-file-upload-button:hover {
            background: #f57c00;
        }
        .form-section-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 16px 0 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid var(--border);
        }
        .form-section-title i { color: #e65100; margin-right: 6px; }

        .apply-now-btn {
            background: linear-gradient(135deg, #e65100, #f57c00);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 40px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
            font-size: 13px;
            margin-top: 8px;
            font-family: 'Inter', sans-serif;
        }
        .apply-now-btn:active { transform: scale(0.98); }
        .apply-now-btn:disabled { opacity: 0.6; cursor: not-allowed; }

        .file-upload-status {
            font-size: 10px;
            color: var(--text-tertiary);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .file-upload-status .uploaded { color: var(--success); }
        .file-upload-status .pending { color: var(--warning); }

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
        }
        .toast.show { transform: translateX(-50%) translateY(0); }

        .footer-note {
            text-align: center;
            font-size: 9px;
            color: var(--text-tertiary);
            padding: 14px;
            border-top: 1px solid var(--border);
            margin-top: 16px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 374px) {
            .page-header h1 { font-size: 20px; }
            .page-header .header-icon { font-size: 40px; }
            .features-grid { gap: 6px; }
            .feature-card { padding: 12px 8px; }
            .feature-card .feature-icon { font-size: 22px; }
            .offer-item { padding: 12px; }
            .offer-item .offer-icon { width: 40px; height: 40px; font-size: 18px; }
            .stage-grid { grid-template-columns: repeat(2, 1fr); }
            .emi-box .emi-result .emi-amount { font-size: 20px; }
        }

        @media (max-width: 480px) {
            .page-header { padding: 16px 14px 24px; }
            .page-header h1 { font-size: 22px; }
            .feature-card .feature-title { font-size: 11px; }
            .offer-item .offer-info .offer-name { font-size: 13px; }
            .stage-grid { grid-template-columns: repeat(3, 1fr); }
            .stage-card .stage-name { font-size: 9px; }
            .emi-box .emi-result .emi-amount { font-size: 22px; }
            .why-grid { gap: 8px; }
            .why-card { padding: 12px 8px; }
            .doc-item { padding: 6px 0; }
            .doc-item .doc-info .doc-name { font-size: 11px; }
            .modal-content { padding: 16px; }
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
        <h1>🚀 Startup Loan</h1>
        <p>Fuel your entrepreneurial dreams with quick funding</p>
        <div class="header-icon"><i class="fas fa-rocket"></i></div>
    </div>

    <!-- FEATURES -->
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-percent"></i></div>
            <div class="feature-title">Low Interest</div>
            <div class="feature-desc">Starting from 9.5% p.a.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-clock"></i></div>
            <div class="feature-title">Quick Approval</div>
            <div class="feature-desc">Approval in 3-7 days</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="feature-title">High Amount</div>
            <div class="feature-desc">Up to ₹2 Crore</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="feature-title">Flexible Tenure</div>
            <div class="feature-desc">Up to 7 years</div>
        </div>
    </div>

    <!-- BUSINESS STAGES -->
    <div class="section-header">
        <h2>📊 Business Stages</h2>
        <a href="#" onclick="openApplyModal('Startup Loan')">Apply Now</a>
    </div>
    <div class="stage-grid">
        <div class="stage-card" onclick="openApplyModal('Idea Stage Startup')">
            <div class="stage-icon"><i class="fas fa-lightbulb"></i></div>
            <div class="stage-name">Idea Stage</div>
        </div>
        <div class="stage-card" onclick="openApplyModal('Early Stage Startup')">
            <div class="stage-icon"><i class="fas fa-seedling"></i></div>
            <div class="stage-name">Early Stage</div>
        </div>
        <div class="stage-card" onclick="openApplyModal('Growth Stage Startup')">
            <div class="stage-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stage-name">Growth Stage</div>
        </div>
        <div class="stage-card" onclick="openApplyModal('Scale-up Startup')">
            <div class="stage-icon"><i class="fas fa-rocket"></i></div>
            <div class="stage-name">Scale-up</div>
        </div>
        <div class="stage-card" onclick="openApplyModal('Series A Funding')">
            <div class="stage-icon"><i class="fas fa-hand-holding-usd"></i></div>
            <div class="stage-name">Series A</div>
        </div>
        <div class="stage-card" onclick="openApplyModal('Established Startup')">
            <div class="stage-icon"><i class="fas fa-trophy"></i></div>
            <div class="stage-name">Established</div>
        </div>
    </div>

    <!-- LOAN OFFERS -->
    <div class="section-header">
        <h2>💰 Startup Loan Offers</h2>
        <a href="#" onclick="openApplyModal('Startup Loan')">Apply Now</a>
    </div>
    <div class="offers-list">
        <div class="offer-item" onclick="openApplyModal('SBI Startup Loan')">
            <div class="offer-icon" style="background: #e8f5e9; color: #2e7d32;">
                <i class="fas fa-rocket"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">SBI Startup Loan</div>
                <div class="offer-desc">Government backed startup funding</div>
                <span class="offer-tag">9.5% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal('HDFC Startup Loan')">
            <div class="offer-icon" style="background: #fff3e0; color: #e65100;">
                <i class="fas fa-rocket"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">HDFC Startup Loan</div>
                <div class="offer-desc">Quick funding for new ventures</div>
                <span class="offer-tag">10.0% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal('ICICI Startup Loan')">
            <div class="offer-icon" style="background: #e3f2fd; color: #1565c0;">
                <i class="fas fa-rocket"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">ICICI Startup Loan</div>
                <div class="offer-desc">Innovative startup solutions</div>
                <span class="offer-tag">9.75% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal('Axis Startup Loan')">
            <div class="offer-icon" style="background: #fce4ec; color: #c62828;">
                <i class="fas fa-rocket"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">Axis Startup Loan</div>
                <div class="offer-desc">Flexible funding options</div>
                <span class="offer-tag">10.25% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>
    </div>

    <!-- DOCUMENT CHECKLIST -->
    <div class="doc-section">
        <div class="doc-box">
            <div class="doc-title"><i class="fas fa-file-alt"></i> Document Checklist</div>
            
            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-id-card"></i></div>
                <div class="doc-info">
                    <div class="doc-name">Business Registration Certificate</div>
                    <div class="doc-desc">GST registration / Udyam registration / Company incorporation</div>
                </div>
                <span class="doc-status required">Required</span>
            </div>

            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-file-pdf"></i></div>
                <div class="doc-info">
                    <div class="doc-name">Business Plan & Project Report</div>
                    <div class="doc-desc">Detailed business plan with financial projections</div>
                </div>
                <span class="doc-status required">Required</span>
            </div>

            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-chart-line"></i></div>
                <div class="doc-info">
                    <div class="doc-name">Financial Statements</div>
                    <div class="doc-desc">Last 3 years ITR, Balance Sheet, Profit & Loss statement</div>
                </div>
                <span class="doc-status required">Required</span>
            </div>

            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-user-circle"></i></div>
                <div class="doc-info">
                    <div class="doc-name">KYC Documents</div>
                    <div class="doc-desc">PAN Card, Aadhaar Card, Passport size photo</div>
                </div>
                <span class="doc-status required">Required</span>
            </div>

            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-bank"></i></div>
                <div class="doc-info">
                    <div class="doc-name">Bank Statements</div>
                    <div class="doc-desc">Last 6 months bank statements</div>
                </div>
                <span class="doc-status required">Required</span>
            </div>

            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-file-signature"></i></div>
                <div class="doc-info">
                    <div class="doc-name">Business Agreements</div>
                    <div class="doc-desc">Partnership deed / MOA / AOA (if applicable)</div>
                </div>
                <span class="doc-status optional">Optional</span>
            </div>

            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-building"></i></div>
                <div class="doc-info">
                    <div class="doc-name">Property / Office Address Proof</div>
                    <div class="doc-desc">Rental agreement or ownership documents</div>
                </div>
                <span class="doc-status optional">Optional</span>
            </div>

            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-tasks"></i></div>
                <div class="doc-info">
                    <div class="doc-name">Projected Financials</div>
                    <div class="doc-desc">3-5 years projected income statement and cash flow</div>
                </div>
                <span class="doc-status required">Required</span>
            </div>

            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="doc-info">
                    <div class="doc-name">Business Insurance Details</div>
                    <div class="doc-desc">Any existing business insurance policies</div>
                </div>
                <span class="doc-status optional">Optional</span>
            </div>

            <div class="doc-item">
                <div class="doc-icon"><i class="fas fa-handshake"></i></div>
                <div class="doc-info">
                    <div class="doc-name">Partners / Directors Details</div>
                    <div class="doc-desc">KYC and profile of all partners/directors</div>
                </div>
                <span class="doc-status required">Required</span>
            </div>
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
                <input type="number" id="emiInterestRate" placeholder="Enter interest rate" value="9.5" step="0.1">
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Loan Tenure (Years)</label>
                <select id="emiTenure">
                    <option value="1">1 year</option>
                    <option value="2">2 years</option>
                    <option value="3" selected>3 years</option>
                    <option value="5">5 years</option>
                    <option value="7">7 years</option>
                </select>
            </div>
            <button class="calculate-btn" onclick="calculateEMI()"><i class="fas fa-calculator"></i> Calculate EMI</button>
            <div class="emi-result" id="emiResult">
                <div class="emi-label">Monthly EMI</div>
                <div class="emi-amount" id="emiAmount">₹ 32,008</div>
                <div style="font-size:10px;color:var(--text-tertiary);margin-top:4px;">
                    Total Payment: ₹<span id="totalPayment">11,52,288</span> | 
                    Total Interest: ₹<span id="totalInterest">1,52,288</span>
                </div>
            </div>
        </div>
    </div>

    <!-- WHY CHOOSE -->
    <div class="why-section">
        <div class="section-header" style="padding: 0; margin: 0 0 10px 0;">
            <h2>✅ Why Choose Startup Loan</h2>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">No Collateral</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Quick Processing</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Government Schemes</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Mentorship Support</div>
            </div>
        </div>
    </div>

    <!-- APPLY MODAL -->
    <div id="applyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-rocket" style="color:#e65100;"></i> Apply for Startup Loan</h3>
                <button class="modal-close" onclick="closeModal('applyModal')">✕</button>
            </div>
            
            <!-- Personal Information -->
            <div class="form-section-title"><i class="fas fa-user"></i> Personal Information</div>
            
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Loan Type</label>
                <input type="text" id="loanTypeDisplay" class="form-input" readonly style="background:#f5f5f5;font-weight:600;">
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name <span class="required-star">*</span></label>
                <input type="text" class="form-input" id="applicantName" value="<?php echo $fullname; ?>" required>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email <span class="required-star">*</span></label>
                <input type="email" class="form-input" id="applicantEmail" value="<?php echo $email; ?>" required>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-phone"></i> Phone <span class="required-star">*</span></label>
                <input type="tel" class="form-input" id="applicantPhone" value="<?php echo $phone; ?>" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Date of Birth <span class="required-star">*</span></label>
                <input type="date" class="form-input" id="dob">
            </div>

            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Current Address <span class="required-star">*</span></label>
                <input type="text" class="form-input" id="address" placeholder="Enter your current address">
            </div>

            <div class="form-group">
                <label><i class="fas fa-city"></i> City <span class="required-star">*</span></label>
                <input type="text" class="form-input" id="city" placeholder="Enter your city">
            </div>

            <div class="form-group">
                <label><i class="fas fa-code"></i> PIN Code <span class="required-star">*</span></label>
                <input type="text" class="form-input" id="pincode" placeholder="Enter PIN code" maxlength="6">
            </div>

            <!-- Business Information -->
            <div class="form-section-title"><i class="fas fa-briefcase"></i> Business Information</div>

            <div class="form-group">
                <label><i class="fas fa-building"></i> Business Name <span class="required-star">*</span></label>
                <input type="text" class="form-input" id="businessName" placeholder="Enter your business name">
            </div>

            <div class="form-group">
                <label><i class="fas fa-tag"></i> Business Type <span class="required-star">*</span></label>
                <select class="form-input" id="businessType">
                    <option value="">Select Business Type</option>
                    <option value="sole_proprietorship">Sole Proprietorship</option>
                    <option value="partnership">Partnership</option>
                    <option value="llp">LLP</option>
                    <option value="pvt_ltd">Private Limited</option>
                    <option value="public_ltd">Public Limited</option>
                    <option value="startup">Startup</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Business Established Year</label>
                <input type="number" class="form-input" id="establishedYear" placeholder="e.g., 2020" min="2000" max="2025">
            </div>

            <div class="form-group">
                <label><i class="fas fa-users"></i> Number of Employees</label>
                <input type="number" class="form-input" id="employees" placeholder="Enter number of employees">
            </div>

            <div class="form-group">
                <label><i class="fas fa-globe"></i> Business Website</label>
                <input type="url" class="form-input" id="website" placeholder="e.g., https://www.yourcompany.com">
            </div>

            <div class="form-group">
                <label><i class="fas fa-file-invoice"></i> GST Registration Number</label>
                <input type="text" class="form-input" id="gstNumber" placeholder="Enter GST number (if applicable)">
            </div>

            <div class="form-group">
                <label><i class="fas fa-id-card"></i> Udyam Registration Number</label>
                <input type="text" class="form-input" id="udyamNumber" placeholder="Enter Udyam number (if applicable)">
            </div>

            <!-- Financial Information -->
            <div class="form-section-title"><i class="fas fa-coins"></i> Financial Information</div>

            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> Annual Revenue (₹) <span class="required-star">*</span></label>
                <input type="number" class="form-input" id="annualRevenue" placeholder="Enter annual revenue">
            </div>

            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> Monthly Expenses (₹)</label>
                <input type="number" class="form-input" id="monthlyExpenses" placeholder="Enter monthly expenses">
            </div>

            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> Existing Loan/Debt (₹)</label>
                <input type="number" class="form-input" id="existingDebt" placeholder="Enter existing debt amount" value="0">
            </div>

            <div class="form-group">
                <label><i class="fas fa-hand-holding-usd"></i> Loan Amount Required (₹) <span class="required-star">*</span></label>
                <input type="number" class="form-input" id="loanAmount" placeholder="Enter loan amount" min="100000">
            </div>

            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Tenure</label>
                <select class="form-input" id="loanTenure">
                    <option value="1">1 year</option>
                    <option value="2">2 years</option>
                    <option value="3" selected>3 years</option>
                    <option value="5">5 years</option>
                    <option value="7">7 years</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-bullseye"></i> Purpose of Loan <span class="required-star">*</span></label>
                <select class="form-input" id="loanPurpose">
                    <option value="">Select Purpose</option>
                    <option value="working_capital">Working Capital</option>
                    <option value="equipment_purchase">Equipment/Machinery Purchase</option>
                    <option value="expansion">Business Expansion</option>
                    <option value="marketing">Marketing & Branding</option>
                    <option value="inventory">Inventory Management</option>
                    <option value="technology">Technology Upgrade</option>
                    <option value="staffing">Staffing & Hiring</option>
                    <option value="research">Research & Development</option>
                    <option value="office_setup">Office Setup</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-file-alt"></i> Business Plan Summary</label>
                <textarea class="form-input" id="businessSummary" rows="3" placeholder="Brief description of your business plan and how you will use the loan" style="resize:vertical;"></textarea>
            </div>

            <!-- Document Upload -->
            <div class="form-section-title"><i class="fas fa-file-upload"></i> Upload Documents</div>

            <div class="form-group">
                <label><i class="fas fa-id-card"></i> PAN Card <span class="required-star">*</span></label>
                <input type="file" class="form-input file-input" id="panFile" accept=".pdf,.jpg,.jpeg,.png">
                <div class="file-upload-status"><span class="pending">⚠️</span> Upload your PAN card (PDF/JPG/PNG, max 5MB)</div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-id-card"></i> Aadhaar Card <span class="required-star">*</span></label>
                <input type="file" class="form-input file-input" id="aadhaarFile" accept=".pdf,.jpg,.jpeg,.png">
                <div class="file-upload-status"><span class="pending">⚠️</span> Upload your Aadhaar card (PDF/JPG/PNG, max 5MB)</div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-file-pdf"></i> Business Registration <span class="required-star">*</span></label>
                <input type="file" class="form-input file-input" id="registrationFile" accept=".pdf,.jpg,.jpeg,.png">
                <div class="file-upload-status"><span class="pending">⚠️</span> Upload incorporation/GST/udyam certificate (PDF/JPG/PNG, max 5MB)</div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-file-pdf"></i> Financial Statements</label>
                <input type="file" class="form-input file-input" id="financialFile" accept=".pdf,.xls,.xlsx">
                <div class="file-upload-status"><span class="pending">⚠️</span> Upload ITR/Balance Sheet (PDF/Excel, max 5MB)</div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-file-pdf"></i> Business Plan</label>
                <input type="file" class="form-input file-input" id="businessPlanFile" accept=".pdf,.doc,.docx">
                <div class="file-upload-status"><span class="pending">⚠️</span> Upload your business plan (PDF/DOC, max 5MB)</div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-bank"></i> Bank Statement</label>
                <input type="file" class="form-input file-input" id="bankStatementFile" accept=".pdf,.jpg,.jpeg,.png">
                <div class="file-upload-status"><span class="pending">⚠️</span> Upload last 6 months bank statement (PDF/JPG/PNG, max 5MB)</div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-image"></i> Passport Size Photo <span class="required-star">*</span></label>
                <input type="file" class="form-input file-input" id="photoFile" accept=".jpg,.jpeg,.png">
                <div class="file-upload-status"><span class="pending">⚠️</span> Upload passport size photo (JPG/PNG, max 2MB)</div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-check-circle"></i> Terms & Conditions</label>
                <div style="display:flex;align-items:center;gap:10px;font-size:12px;">
                    <input type="checkbox" id="termsCheck" style="width:18px;height:18px;cursor:pointer;">
                    <label for="termsCheck" style="margin:0;font-size:12px;cursor:pointer;">I agree to the <a href="#" style="color:#e65100;text-decoration:underline;">Terms & Conditions</a> and confirm that all information provided is true and accurate.</label>
                </div>
            </div>

            <button class="apply-now-btn" id="submitBtn" onclick="submitStartupApplication()">
                <i class="fas fa-paper-plane"></i> Submit Application →
            </button>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="toast"></div>

    <div class="footer-note"><i class="fas fa-lock"></i> Secure & Trusted | RBI Compliant</div>
</div>

<script>
    // ============================================================
    // EMI CALCULATOR
    // ============================================================
    function calculateEMI() {
        const amount = parseFloat(document.getElementById('emiLoanAmount').value);
        const rate = parseFloat(document.getElementById('emiInterestRate').value);
        const years = parseInt(document.getElementById('emiTenure').value);
        const tenure = years * 12;

        if (!amount || !rate || !years) {
            showToast('Please fill all fields');
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

    // Auto calculate on load
    document.addEventListener('DOMContentLoaded', function() {
        calculateEMI();
        
        document.getElementById('emiLoanAmount').addEventListener('input', calculateEMI);
        document.getElementById('emiInterestRate').addEventListener('input', calculateEMI);
        document.getElementById('emiTenure').addEventListener('change', calculateEMI);
    });

    // ============================================================
    // APPLY MODAL
    // ============================================================
    function openApplyModal(loanType) {
        document.getElementById('loanTypeDisplay').value = loanType || 'Startup Loan';
        document.getElementById('applyModal').classList.add('active');
        // Reset file upload status
        document.querySelectorAll('.file-upload-status .pending').forEach(el => {
            el.textContent = '⚠️';
        });
    }

    // File upload tracking
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

    function submitStartupApplication() {
        // Validate required fields
        const requiredFields = ['applicantName', 'applicantEmail', 'applicantPhone', 'dob', 'address', 'city', 'pincode', 
                               'businessName', 'businessType', 'annualRevenue', 'loanAmount', 'loanPurpose', 'businessSummary'];
        
        let missingFields = [];
        requiredFields.forEach(id => {
            const field = document.getElementById(id);
            if (!field.value || field.value.trim() === '') {
                missingFields.push(id);
            }
        });

        // Validate terms checkbox
        if (!document.getElementById('termsCheck').checked) {
            showToast('Please accept the Terms & Conditions');
            return;
        }

        // Validate file uploads
        const requiredFiles = ['panFile', 'aadhaarFile', 'registrationFile', 'photoFile'];
        let missingFiles = [];
        requiredFiles.forEach(id => {
            const file = document.getElementById(id);
            if (!file.files || file.files.length === 0) {
                missingFiles.push(id);
            }
        });

        if (missingFields.length > 0 || missingFiles.length > 0) {
            let msg = 'Please fill all required fields and upload required documents: ';
            let count = 0;
            if (missingFields.length > 0) {
                msg += missingFields.join(', ');
                count++;
            }
            if (missingFiles.length > 0) {
                if (count > 0) msg += ' and ';
                msg += missingFiles.join(', ');
            }
            showToast(msg);
            return;
        }

        const name = document.getElementById('applicantName').value;
        const business = document.getElementById('businessName').value;
        const amount = document.getElementById('loanAmount').value;

        showToast('✓ Startup Loan application submitted for ' + name + ' (Business: ' + business + ')!');
        closeModal('applyModal');
        
        // Reset form after submission (optional)
        document.querySelectorAll('.file-input').forEach(input => input.value = '');
        document.querySelectorAll('.file-upload-status span').forEach(el => {
            el.textContent = '⚠️';
            el.className = 'pending';
        });
        document.getElementById('termsCheck').checked = false;
    }

    // ============================================================
    // TOAST & MODAL HELPERS
    // ============================================================
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 4000);
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    document.querySelectorAll('.modal').forEach(m => {
        m.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    // ============================================================
    // PIN CODE VALIDATION
    // ============================================================
    document.getElementById('pincode').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    // ============================================================
    // EXPOSE GLOBALLY
    // ============================================================
    Object.assign(window, {
        calculateEMI, openApplyModal, submitStartupApplication,
        showToast, closeModal
    });
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>