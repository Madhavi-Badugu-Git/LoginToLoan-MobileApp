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
    <meta name="theme-color" content="#1a237e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Mortgage Loan - Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        :root {
            --primary: #1a237e;
            --primary-light: #283593;
            --primary-dark: #0d1757;
            --orange: #ff9800;
            --success: #00c853;
            --danger: #ef4444;
            --text-primary: #1a1a2e;
            --text-secondary: #666666;
            --text-tertiary: #999999;
            --background: #f0f4ff;
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
            background: linear-gradient(135deg, #0d1757, #1a237e, #283593);
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
            color: var(--primary);
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
        .section-header h2 i { color: var(--primary); margin-right: 6px; }

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
        .offer-item:active { transform: scale(0.98); background: #f0f3ff; }
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
            color: var(--primary);
            background: #e8eaf6;
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
        /* PROPERTY TYPES */
        /* ============================================================ */
        .property-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            padding: 0 14px;
        }
        .property-card {
            background: var(--white);
            border-radius: 12px;
            padding: 14px 8px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .property-card:active { transform: scale(0.95); background: #f0f3ff; }
        .property-card .prop-icon {
            font-size: 24px;
            color: var(--primary);
            margin-bottom: 4px;
        }
        .property-card .prop-name {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ============================================================ */
        /* APPLICATION FORM - NO DOCUMENT UPLOAD */
        /* ============================================================ */
        .apply-section {
            padding: 0 14px;
            margin: 16px 0 10px;
        }
        .apply-box {
            background: var(--white);
            border-radius: var(--radius);
            padding: 20px 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .apply-box .form-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
        }
        .apply-box .form-title i { color: var(--orange); margin-right: 8px; }
        .apply-box .form-subtitle {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 14px;
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
            color: var(--primary);
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
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,35,126,0.08);
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

        .form-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 16px 0;
        }

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
            color: var(--primary);
            margin-bottom: 4px;
        }
        .why-card .why-text {
            font-size: 9px;
            font-weight: 600;
            color: var(--text-primary);
        }

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
            background: linear-gradient(135deg, var(--success), #00e676);
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
            .property-grid { grid-template-columns: repeat(2, 1fr); }
            .why-grid { grid-template-columns: repeat(2, 1fr); }
            .form-row { grid-template-columns: 1fr; }
            .apply-box { padding: 16px 12px; }
        }

        @media (max-width: 480px) {
            .page-header { padding: 14px 14px 24px; }
            .page-header h1 { font-size: 20px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .feature-card { padding: 10px 6px; }
            .feature-card .feature-icon { font-size: 18px; }
            .feature-card .feature-title { font-size: 9px; }
            .property-grid { grid-template-columns: repeat(3, 1fr); gap: 6px; }
            .property-card { padding: 10px 6px; }
            .property-card .prop-icon { font-size: 20px; }
            .property-card .prop-name { font-size: 9px; }
            .why-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .why-card { padding: 10px 6px; }
            .why-card .why-icon { font-size: 18px; }
            .why-card .why-text { font-size: 8px; }
            .form-row { grid-template-columns: 1fr; }
            .apply-box { padding: 16px 14px; }
            .apply-box .form-title { font-size: 16px; }
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
            .page-header { background: linear-gradient(135deg, #0a0a3a, #1a237e, #283593); }
            .feature-card { background: var(--white); }
            .offer-item { background: var(--white); }
            .property-card { background: var(--white); }
            .apply-box { background: var(--white); }
            .why-card { background: var(--white); }
            .modal-content { background: var(--white); }
            .form-input { background: #2a2a2a; color: #e8e8e8; border-color: #3a3a3a; }
            .form-select { background: #2a2a2a; color: #e8e8e8; border-color: #3a3a3a; }
            .form-note { background: #1a1a2a; }
            .success-details-box { background: #1a1a2a; }
            .toast { background: #333333; }
            .offer-item .offer-info .offer-tag { background: #1a1a3e; }
            .offer-item:active { background: #1a1a3e; }
            .property-card:active { background: #1a1a3e; }
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
        <h1>🏠 Mortgage Loan</h1>
        <p>Find the best mortgage loan offers from top banks</p>
        <div class="header-icon"><i class="fas fa-home"></i></div>
    </div>

    <!-- ===== FEATURES ===== -->
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-percent"></i></div>
            <div class="feature-title">Low Interest</div>
            <div class="feature-desc">Starting 8.25% p.a.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-clock"></i></div>
            <div class="feature-title">Quick Approval</div>
            <div class="feature-desc">3-5 Days</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="feature-title">High Amount</div>
            <div class="feature-desc">Up to ₹10Cr</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="feature-title">Flexible Tenure</div>
            <div class="feature-desc">Up to 30 Years</div>
        </div>
    </div>

    <!-- ===== LOAN OFFERS ===== -->
    <div class="section-header">
        <h2><i class="fas fa-hand-holding-usd"></i> Mortgage Offers</h2>
    </div>
    <div class="offers-list">
        <div class="offer-item" onclick="document.getElementById('loanTypeDisplay').value='HDFC Mortgage Loan';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="offer-icon" style="background: #004d7a;">H</div>
            <div class="offer-info">
                <div class="offer-name">HDFC Mortgage Loan</div>
                <div class="offer-desc">Flexible mortgage solutions</div>
                <span class="offer-tag">8.5% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="document.getElementById('loanTypeDisplay').value='SBI Mortgage Loan';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="offer-icon" style="background: #1a5276;">S</div>
            <div class="offer-info">
                <div class="offer-name">SBI Mortgage Loan</div>
                <div class="offer-desc">Government bank mortgage</div>
                <span class="offer-tag">8.25% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="document.getElementById('loanTypeDisplay').value='ICICI Mortgage Loan';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="offer-icon" style="background: #004080;">I</div>
            <div class="offer-info">
                <div class="offer-name">ICICI Mortgage Loan</div>
                <div class="offer-desc">Quick processing & approval</div>
                <span class="offer-tag">8.75% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="document.getElementById('loanTypeDisplay').value='Axis Mortgage Loan';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="offer-icon" style="background: #800000;">A</div>
            <div class="offer-info">
                <div class="offer-name">Axis Mortgage Loan</div>
                <div class="offer-desc">Flexible tenure options</div>
                <span class="offer-tag">8.9% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="document.getElementById('loanTypeDisplay').value='Kotak Mortgage Loan';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="offer-icon" style="background: #2c3e50;">K</div>
            <div class="offer-info">
                <div class="offer-name">Kotak Mortgage Loan</div>
                <div class="offer-desc">Competitive rates & benefits</div>
                <span class="offer-tag">8.6% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>
    </div>

    <!-- ===== PROPERTY TYPES ===== -->
    <div class="section-header">
        <h2><i class="fas fa-building"></i> Property Types</h2>
    </div>
    <div class="property-grid">
        <div class="property-card" onclick="document.getElementById('propertyType').value='residential';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="prop-icon"><i class="fas fa-home"></i></div>
            <div class="prop-name">Residential</div>
        </div>
        <div class="property-card" onclick="document.getElementById('propertyType').value='commercial';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="prop-icon"><i class="fas fa-store"></i></div>
            <div class="prop-name">Commercial</div>
        </div>
        <div class="property-card" onclick="document.getElementById('propertyType').value='land';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="prop-icon"><i class="fas fa-map"></i></div>
            <div class="prop-name">Land</div>
        </div>
        <div class="property-card" onclick="document.getElementById('propertyType').value='industrial';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="prop-icon"><i class="fas fa-industry"></i></div>
            <div class="prop-name">Industrial</div>
        </div>
        <div class="property-card" onclick="document.getElementById('propertyType').value='under_construction';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="prop-icon"><i class="fas fa-hard-hat"></i></div>
            <div class="prop-name">Under Construction</div>
        </div>
        <div class="property-card" onclick="document.getElementById('propertyType').value='rental';document.getElementById('applySection').scrollIntoView({behavior:'smooth'})">
            <div class="prop-icon"><i class="fas fa-key"></i></div>
            <div class="prop-name">Rental Property</div>
        </div>
    </div>

    <!-- ===== MORTGAGE LOAN APPLICATION FORM ===== -->
    <div class="apply-section" id="applySection">
        <div class="apply-box">
            <div class="form-title"><i class="fas fa-file-signature"></i> Apply for Mortgage Loan</div>
            <div class="form-subtitle">Fill in the details below to get started</div>
            
            <form id="mortgageForm" onsubmit="submitApplication(event)">
                
                <!-- Hidden fields for user data from session -->
                <input type="hidden" id="fullNameHidden" value="<?php echo $fullname; ?>">
                <input type="hidden" id="emailHidden" value="<?php echo $email; ?>">
                <input type="hidden" id="mobileHidden" value="<?php echo str_replace(['+91', ' ', '-'], '', $phone); ?>">

                <!-- Loan Type -->
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Loan Type</label>
                    <input type="text" id="loanTypeDisplay" class="form-input" value="Mortgage Loan" readonly style="background:#f5f5f5;font-weight:600;">
                </div>

                <hr class="form-divider">

                <!-- Property Details -->
                <div class="form-group">
                    <label><i class="fas fa-building"></i> Property Type <span class="required">*</span></label>
                    <select class="form-select" id="propertyType" required>
                        <option value="">Select Property Type</option>
                        <option value="residential">🏠 Residential</option>
                        <option value="commercial">🏢 Commercial</option>
                        <option value="land">📍 Land</option>
                        <option value="industrial">🏭 Industrial</option>
                        <option value="under_construction">🔨 Under Construction</option>
                        <option value="rental">🔑 Rental Property</option>
                    </select>
                    <div class="form-error" id="propertyTypeError">Please select property type</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-rupee-sign"></i> Property Value (₹) <span class="required">*</span></label>
                        <input type="number" class="form-input" id="propertyValue" placeholder="Enter property value" min="0" required>
                        <div class="form-error" id="propertyValueError">Please enter property value</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-hand-holding-usd"></i> Loan Amount (₹) <span class="required">*</span></label>
                        <input type="number" class="form-input" id="loanAmount" placeholder="Enter loan amount" min="0" required>
                        <div class="form-error" id="loanAmountError">Please enter loan amount</div>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-money-bill-wave"></i> Down Payment (₹)</label>
                    <input type="number" class="form-input" id="downPayment" placeholder="Enter down payment" min="0" value="0">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Property Address <span class="required">*</span></label>
                    <input type="text" class="form-input" id="propertyAddress" placeholder="Enter property address" required>
                    <div class="form-error" id="propertyAddressError">Please enter property address</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-city"></i> City <span class="required">*</span></label>
                        <input type="text" class="form-input" id="propertyCity" placeholder="Enter city" required>
                        <div class="form-error" id="propertyCityError">Please enter city</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-map-pin"></i> PIN Code <span class="required">*</span></label>
                        <input type="text" class="form-input" id="propertyPincode" placeholder="Enter PIN code" maxlength="6" required>
                        <div class="form-error" id="propertyPincodeError">Please enter valid 6-digit PIN</div>
                    </div>
                </div>

                <hr class="form-divider">

                <!-- Loan Details -->
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Loan Tenure <span class="required">*</span></label>
                        <select class="form-select" id="loanTenure" required>
                            <option value="">Select Tenure</option>
                            <option value="5">5 years</option>
                            <option value="10">10 years</option>
                            <option value="15" selected>15 years</option>
                            <option value="20">20 years</option>
                            <option value="25">25 years</option>
                            <option value="30">30 years</option>
                        </select>
                        <div class="form-error" id="loanTenureError">Please select loan tenure</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-bullseye"></i> Loan Purpose <span class="required">*</span></label>
                        <select class="form-select" id="loanPurpose" required>
                            <option value="">Select Purpose</option>
                            <option value="purchase">🏠 Purchase New Property</option>
                            <option value="refinance">🔄 Refinance Existing Loan</option>
                            <option value="renovation">🔧 Home Renovation</option>
                            <option value="construction">🏗️ New Construction</option>
                            <option value="investment">📈 Investment Property</option>
                        </select>
                        <div class="form-error" id="loanPurposeError">Please select loan purpose</div>
                    </div>
                </div>

                <hr class="form-divider">

                <!-- Personal Details -->
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Date of Birth <span class="required">*</span></label>
                        <input type="date" class="form-input" id="dob" required>
                        <div class="form-error" id="dobError">Please select your date of birth</div>
                    </div>
                    <div class="form-group">
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

                <hr class="form-divider">

                <!-- Employment & Income -->
                <div class="form-group">
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

                <div class="form-group">
                    <label><i class="fas fa-building"></i> Company / Organization Name <span class="required">*</span></label>
                    <input type="text" class="form-input" id="companyName" placeholder="Enter company name" required>
                    <div class="form-error" id="companyNameError">Please enter company name</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-money-bill"></i> Monthly Income (₹) <span class="required">*</span></label>
                        <input type="number" class="form-input" id="monthlyIncome" placeholder="Enter monthly income" min="0" required>
                        <div class="form-error" id="monthlyIncomeError">Please enter monthly income</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Existing Loans (₹)</label>
                        <input type="number" class="form-input" id="existingLoans" placeholder="Enter existing loans" min="0" value="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-briefcase"></i> Work Experience (Years) <span class="required">*</span></label>
                        <input type="number" class="form-input" id="workExperience" placeholder="Years of experience" min="0" required>
                        <div class="form-error" id="workExperienceError">Please enter work experience</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Current EMI (₹)</label>
                        <input type="number" class="form-input" id="currentEmi" placeholder="Enter current EMI" min="0" value="0">
                    </div>
                </div>

                <hr class="form-divider">

                <!-- Identification -->
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> Aadhar Number <span class="required">*</span></label>
                        <input type="text" class="form-input" id="aadharNumber" placeholder="Enter 12-digit Aadhar" maxlength="12" required>
                        <div class="form-error" id="aadharNumberError">Please enter valid 12-digit Aadhar</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> PAN Number <span class="required">*</span></label>
                        <input type="text" class="form-input" id="panNumber" placeholder="Enter PAN (ABCDE1234F)" maxlength="10" required>
                        <div class="form-error" id="panNumberError">Please enter valid PAN number</div>
                    </div>
                </div>

                <hr class="form-divider">

                <!-- Address Details -->
                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Current Address <span class="required">*</span></label>
                    <input type="text" class="form-input" id="currentAddress" placeholder="Enter your current address" required>
                    <div class="form-error" id="currentAddressError">Please enter your address</div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-city"></i> City <span class="required">*</span></label>
                        <input type="text" class="form-input" id="currentCity" placeholder="Enter city" required>
                        <div class="form-error" id="currentCityError">Please enter your city</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-map-pin"></i> PIN Code <span class="required">*</span></label>
                        <input type="text" class="form-input" id="currentPincode" placeholder="Enter PIN code" maxlength="6" required>
                        <div class="form-error" id="currentPincodeError">Please enter valid 6-digit PIN</div>
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

    <!-- ===== WHY CHOOSE US ===== -->
    <div class="section-header">
        <h2><i class="fas fa-check-circle"></i> Why Choose Us</h2>
    </div>
    <div class="why-grid">
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-clock"></i></div>
            <div class="why-text">Quick Disbursal</div>
        </div>
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-percent"></i></div>
            <div class="why-text">Low Interest</div>
        </div>
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="why-text">Long Tenure</div>
        </div>
        <div class="why-card">
            <div class="why-icon"><i class="fas fa-hand-holding-usd"></i></div>
            <div class="why-text">Flexible Repayment</div>
        </div>
    </div>

    <!-- ===== SUCCESS MODAL ===== -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div style="text-align:center;">
                <div class="success-check"><i class="fas fa-check-circle"></i></div>
                <div class="success-title">Application Successful!</div>
                <div class="success-sub">Your mortgage loan application has been submitted successfully.</div>
                <div class="success-details-box">
                    <div class="success-detail-row"><span class="s-label">Bank</span><span class="s-value" id="successBank">Mortgage Loan</span></div>
                    <div class="success-detail-row"><span class="s-label">Loan Amount</span><span class="s-value" id="successAmount">₹ 50,00,000</span></div>
                    <div class="success-detail-row"><span class="s-label">Application ID</span><span class="s-value" id="successAppId">#12345</span></div>
                    <div class="success-detail-row"><span class="s-label">Status</span><span class="s-value" style="color: var(--orange);">Pending</span></div>
                </div>
                <button class="apply-btn-modal" onclick="closeSuccessModal()">
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
// APPLICATION FORM SUBMISSION
// ============================================================
function submitApplication(e) {
    e.preventDefault();
    
    // Get form values
    const loanType = document.getElementById('loanTypeDisplay').value;
    const propertyType = document.getElementById('propertyType').value;
    const propertyValue = document.getElementById('propertyValue').value;
    const loanAmount = document.getElementById('loanAmount').value;
    const downPayment = document.getElementById('downPayment').value || 0;
    const propertyAddress = document.getElementById('propertyAddress').value.trim();
    const propertyCity = document.getElementById('propertyCity').value.trim();
    const propertyPincode = document.getElementById('propertyPincode').value.trim();
    const loanTenure = document.getElementById('loanTenure').value;
    const loanPurpose = document.getElementById('loanPurpose').value;
    const dob = document.getElementById('dob').value;
    const gender = document.getElementById('gender').value;
    const employmentType = document.getElementById('employmentType').value;
    const companyName = document.getElementById('companyName').value.trim();
    const monthlyIncome = document.getElementById('monthlyIncome').value;
    const existingLoans = document.getElementById('existingLoans').value || 0;
    const workExperience = document.getElementById('workExperience').value;
    const currentEmi = document.getElementById('currentEmi').value || 0;
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

    // Property Type
    if (!propertyType) { showError('propertyType', 'propertyTypeError'); isValid = false; } else { clearError('propertyType', 'propertyTypeError'); }
    
    // Property Value
    if (!propertyValue || parseFloat(propertyValue) <= 0) { showError('propertyValue', 'propertyValueError'); isValid = false; } else { clearError('propertyValue', 'propertyValueError'); }
    
    // Loan Amount
    if (!loanAmount || parseFloat(loanAmount) <= 0) { showError('loanAmount', 'loanAmountError'); isValid = false; } else { clearError('loanAmount', 'loanAmountError'); }
    
   

    // Property Address
    if (propertyAddress.length < 5) { showError('propertyAddress', 'propertyAddressError'); isValid = false; } else { clearError('propertyAddress', 'propertyAddressError'); }
    
    // Property City
    if (propertyCity.length < 2) { showError('propertyCity', 'propertyCityError'); isValid = false; } else { clearError('propertyCity', 'propertyCityError'); }
    
    // Property Pincode
    const pincodeRegex = /^[0-9]{6}$/;
    if (!pincodeRegex.test(propertyPincode)) { showError('propertyPincode', 'propertyPincodeError'); isValid = false; } else { clearError('propertyPincode', 'propertyPincodeError'); }
    
    // Loan Tenure
    if (!loanTenure) { showError('loanTenure', 'loanTenureError'); isValid = false; } else { clearError('loanTenure', 'loanTenureError'); }
    
    // Loan Purpose
    if (!loanPurpose) { showError('loanPurpose', 'loanPurposeError'); isValid = false; } else { clearError('loanPurpose', 'loanPurposeError'); }
    
    // DOB
    if (!dob) { showError('dob', 'dobError'); isValid = false; } else { clearError('dob', 'dobError'); }
    
    // Gender
    if (!gender) { showError('gender', 'genderError'); isValid = false; } else { clearError('gender', 'genderError'); }
    
    // Employment Type
    if (!employmentType) { showError('employmentType', 'employmentTypeError'); isValid = false; } else { clearError('employmentType', 'employmentTypeError'); }
    
    // Company Name
    if (companyName.length < 2) { showError('companyName', 'companyNameError'); isValid = false; } else { clearError('companyName', 'companyNameError'); }
    
    // Monthly Income
    if (!monthlyIncome || parseFloat(monthlyIncome) <= 0) { showError('monthlyIncome', 'monthlyIncomeError'); isValid = false; } else { clearError('monthlyIncome', 'monthlyIncomeError'); }
    
    // Work Experience
    if (!workExperience || parseFloat(workExperience) < 0) { showError('workExperience', 'workExperienceError'); isValid = false; } else { clearError('workExperience', 'workExperienceError'); }
    
    // Aadhar
    const aadharRegex = /^[0-9]{12}$/;
    if (!aadharRegex.test(aadharNumber)) { showError('aadharNumber', 'aadharNumberError'); isValid = false; } else { clearError('aadharNumber', 'aadharNumberError'); }
    
    // PAN
    const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
    if (!panRegex.test(panNumber)) { showError('panNumber', 'panNumberError'); isValid = false; } else { clearError('panNumber', 'panNumberError'); }
    
    // Current Address
    if (currentAddress.length < 5) { showError('currentAddress', 'currentAddressError'); isValid = false; } else { clearError('currentAddress', 'currentAddressError'); }
    
    // Current City
    if (currentCity.length < 2) { showError('currentCity', 'currentCityError'); isValid = false; } else { clearError('currentCity', 'currentCityError'); }
    
    // Current Pincode
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
    formData.append('property_type', propertyType);
    formData.append('property_value', propertyValue);
    formData.append('loan_amount', loanAmount);
    formData.append('down_payment', downPayment);
    formData.append('property_address', propertyAddress);
    formData.append('property_city', propertyCity);
    formData.append('property_pincode', propertyPincode);
    formData.append('loan_tenure', loanTenure);
    formData.append('loan_purpose', loanPurpose);
    formData.append('dob', dob);
    formData.append('gender', gender);
    formData.append('employment_type', employmentType);
    formData.append('company_name', companyName);
    formData.append('monthly_income', monthlyIncome);
    formData.append('existing_loans', existingLoans);
    formData.append('work_experience', workExperience);
    formData.append('current_emi', currentEmi);
    formData.append('aadhar_number', aadharNumber);
    formData.append('pan_number', panNumber);
    formData.append('current_address', currentAddress);
    formData.append('current_city', currentCity);
    formData.append('current_pincode', currentPincode);
    // Send user data from hidden fields
    formData.append('full_name', fullName);
    formData.append('email', email);
    formData.append('mobile_number', mobileNumber);

    // Send data to server
    fetch('submit_mortgage_loan.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response:', data);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
        
        if (data.success) {
            showSuccessModal(loanType, loanAmount, data.application_id);
            showToast('Application submitted successfully!', 'success');
        } else {
            showToast(data.message || 'Failed to submit application', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
        showToast('Network error: ' + error.message, 'error');
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
function showSuccessModal(bank, amount, appId) {
    document.getElementById('successBank').textContent = bank || 'Mortgage Loan';
    document.getElementById('successAmount').textContent = '₹ ' + (parseFloat(amount) || 5000000).toLocaleString('en-IN');
    document.getElementById('successAppId').textContent = '#' + (appId || Math.floor(Math.random() * 90000) + 10000);
    document.getElementById('successModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.remove('active');
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
    ['propertyPincode', 'currentPincode'].forEach(id => {
        document.getElementById(id).addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
            if (this.value.length === 6) {
                const errorId = id === 'propertyPincode' ? 'propertyPincodeError' : 'currentPincodeError';
                clearError(id, errorId);
            }
        });
    });

    // Close modal on backdrop click
    document.getElementById('successModal').addEventListener('click', function(e) {
        if (e.target === this) closeSuccessModal();
    });
});

// ============================================================
// EXPOSE GLOBALLY
// ============================================================
Object.assign(window, {
    submitApplication, 
    showToast, showSuccessModal, closeSuccessModal
});
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>