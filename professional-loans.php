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
    <title>Professional Loans - Login To Loans</title>
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
            background: linear-gradient(135deg, #7b1fa2, #4a148c);
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
            color: #7b1fa2;
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
        .offer-item:active { transform: scale(0.98); background: #f5f7ff; }
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
            color: #7b1fa2;
            font-weight: 600;
            background: #f3e5f5;
            padding: 2px 10px;
            border-radius: 12px;
            display: inline-block;
            margin-top: 4px;
        }
        .offer-item .offer-arrow {
            color: var(--text-tertiary);
            font-size: 14px;
        }

        /* ===== PROFESSIONAL CATEGORIES ===== */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            padding: 0 14px;
            margin: 10px 0 16px;
        }
        .category-card {
            background: var(--white);
            border-radius: 12px;
            padding: 14px 8px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .category-card:active { transform: scale(0.95); background: #f5f7ff; }
        .category-card .cat-icon {
            font-size: 24px;
            color: #7b1fa2;
            margin-bottom: 4px;
        }
        .category-card .cat-name {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-primary);
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
        .emi-box .emi-title i { color: #7b1fa2; margin-right: 6px; }
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
        .emi-box .form-group label i { margin-right: 4px; color: #7b1fa2; }
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
            border-color: #7b1fa2;
            box-shadow: 0 0 0 3px rgba(123,31,162,0.1);
        }
        .emi-box .emi-result {
            margin-top: 12px;
            padding: 12px;
            background: #f3e5f5;
            border-radius: 10px;
            text-align: center;
        }
        .emi-box .emi-result .emi-amount {
            font-size: 24px;
            font-weight: 800;
            color: #7b1fa2;
        }
        .emi-box .emi-result .emi-label {
            font-size: 11px;
            color: var(--text-secondary);
        }
        .emi-box .calculate-btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, #7b1fa2, #4a148c);
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
            color: #7b1fa2;
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
        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .apply-now-btn {
            background: linear-gradient(135deg, #7b1fa2, #4a148c);
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
            .categories-grid { grid-template-columns: repeat(2, 1fr); }
            .emi-box .emi-result .emi-amount { font-size: 20px; }
        }

        @media (max-width: 480px) {
            .page-header { padding: 16px 14px 24px; }
            .page-header h1 { font-size: 22px; }
            .feature-card .feature-title { font-size: 11px; }
            .offer-item .offer-info .offer-name { font-size: 13px; }
            .categories-grid { grid-template-columns: repeat(3, 1fr); }
            .category-card .cat-name { font-size: 9px; }
            .emi-box .emi-result .emi-amount { font-size: 22px; }
            .why-grid { gap: 8px; }
            .why-card { padding: 12px 8px; }
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
        <h1>👨‍⚕️ Professional Loans</h1>
        <p>Specialized loans for doctors, CAs, architects & more</p>
        <div class="header-icon"><i class="fas fa-briefcase"></i></div>
    </div>

    <!-- FEATURES -->
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-percent"></i></div>
            <div class="feature-title">Special Rates</div>
            <div class="feature-desc">Competitive interest rates</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-clock"></i></div>
            <div class="feature-title">Quick Approval</div>
            <div class="feature-desc">Fast processing for professionals</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="feature-title">High Amount</div>
            <div class="feature-desc">Up to ₹3 Crore</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-file-signature"></i></div>
            <div class="feature-title">Minimal Docs</div>
            <div class="feature-desc">Less documentation required</div>
        </div>
    </div>

    <!-- PROFESSIONAL CATEGORIES -->
    <div class="section-header">
        <h2>👔 Professional Categories</h2>
        <a href="#" onclick="openApplyModal('Professional Loan')">Apply Now</a>
    </div>
    <div class="categories-grid">
        <div class="category-card" onclick="openApplyModal('Doctor Loan')">
            <div class="cat-icon"><i class="fas fa-user-md"></i></div>
            <div class="cat-name">Doctors</div>
        </div>
        <div class="category-card" onclick="openApplyModal('CA Loan')">
            <div class="cat-icon"><i class="fas fa-calculator"></i></div>
            <div class="cat-name">Chartered Accountants</div>
        </div>
        <div class="category-card" onclick="openApplyModal('Architect Loan')">
            <div class="cat-icon"><i class="fas fa-drafting-compass"></i></div>
            <div class="cat-name">Architects</div>
        </div>
        <div class="category-card" onclick="openApplyModal('Engineer Loan')">
            <div class="cat-icon"><i class="fas fa-cogs"></i></div>
            <div class="cat-name">Engineers</div>
        </div>
        <div class="category-card" onclick="openApplyModal('Consultant Loan')">
            <div class="cat-icon"><i class="fas fa-user-tie"></i></div>
            <div class="cat-name">Consultants</div>
        </div>
        <div class="category-card" onclick="openApplyModal('CS Loan')">
            <div class="cat-icon"><i class="fas fa-file-signature"></i></div>
            <div class="cat-name">Company Secretaries</div>
        </div>
    </div>

    <!-- LOAN OFFERS -->
    <div class="section-header">
        <h2>💰 Loan Offers</h2>
        <a href="#" onclick="openApplyModal('Professional Loan')">Apply Now</a>
    </div>
    <div class="offers-list">
        <div class="offer-item" onclick="openApplyModal('Doctor Loan')">
            <div class="offer-icon" style="background: #fce4ec; color: #c62828;">
                <i class="fas fa-user-md"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">Doctor Loan</div>
                <div class="offer-desc">For medical professionals & clinics</div>
                <span class="offer-tag">8.5% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal('Chartered Accountant Loan')">
            <div class="offer-icon" style="background: #fff3e0; color: #e65100;">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">Chartered Accountant Loan</div>
                <div class="offer-desc">For CAs and accounting firms</div>
                <span class="offer-tag">8.75% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal('Architect Loan')">
            <div class="offer-icon" style="background: #e3f2fd; color: #1565c0;">
                <i class="fas fa-drafting-compass"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">Architect Loan</div>
                <div class="offer-desc">For architecture professionals</div>
                <span class="offer-tag">9.0% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal('Engineer Loan')">
            <div class="offer-icon" style="background: #e8f5e9; color: #2e7d32;">
                <i class="fas fa-cogs"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">Engineer Loan</div>
                <div class="offer-desc">For engineering professionals</div>
                <span class="offer-tag">8.5% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal('Consultant Loan')">
            <div class="offer-icon" style="background: #f3e5f5; color: #7b1fa2;">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">Consultant Loan</div>
                <div class="offer-desc">For management & IT consultants</div>
                <span class="offer-tag">9.25% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal('Company Secretary Loan')">
            <div class="offer-icon" style="background: #fff8e1; color: #f57f17;">
                <i class="fas fa-file-signature"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">Company Secretary Loan</div>
                <div class="offer-desc">For CS professionals</div>
                <span class="offer-tag">8.75% p.a. onwards</span>
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
                <label><i class="fas fa-calendar-alt"></i> Loan Tenure (Months)</label>
                <select id="emiTenure">
                    <option value="12">12 months</option>
                    <option value="24">24 months</option>
                    <option value="36">36 months</option>
                    <option value="48">48 months</option>
                    <option value="60" selected>60 months</option>
                    <option value="84">84 months</option>
                    <option value="120">120 months</option>
                </select>
            </div>
            <button class="calculate-btn" onclick="calculateEMI()"><i class="fas fa-calculator"></i> Calculate EMI</button>
            <div class="emi-result" id="emiResult">
                <div class="emi-label">Monthly EMI</div>
                <div class="emi-amount" id="emiAmount">₹ 20,534</div>
                <div style="font-size:10px;color:var(--text-tertiary);margin-top:4px;">
                    Total Payment: ₹<span id="totalPayment">12,32,040</span> | 
                    Total Interest: ₹<span id="totalInterest">2,32,040</span>
                </div>
            </div>
        </div>
    </div>

    <!-- WHY CHOOSE US -->
    <div class="why-section">
        <div class="section-header" style="padding: 0; margin: 0 0 10px 0;">
            <h2>✅ Why Choose Us</h2>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Special Professional Rates</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Quick Disbursal</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Minimal Documentation</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Flexible Repayment</div>
            </div>
        </div>
    </div>

    <!-- APPLY MODAL -->
    <div id="applyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-briefcase" style="color:#7b1fa2;"></i> Apply for Professional Loan</h3>
                <button class="modal-close" onclick="closeModal('applyModal')">✕</button>
            </div>
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Loan Type</label>
                <input type="text" id="loanTypeDisplay" class="form-input" readonly style="background:#f5f5f5;font-weight:600;">
            </div>
            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name</label>
                <input type="text" class="form-input" id="applicantName" value="<?php echo $fullname; ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-input" id="applicantEmail" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-phone"></i> Phone</label>
                <input type="tel" class="form-input" id="applicantPhone" value="<?php echo $phone; ?>">
            </div>
            <div class="form-group">
                <label><i class="fas fa-user-tie"></i> Professional Type</label>
                <select class="form-input" id="professionalType">
                    <option value="doctor">Doctor</option>
                    <option value="ca">Chartered Accountant</option>
                    <option value="architect">Architect</option>
                    <option value="engineer">Engineer</option>
                    <option value="consultant">Consultant</option>
                    <option value="cs">Company Secretary</option>
                    <option value="other">Other Professional</option>
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> Annual Income (₹)</label>
                <input type="number" class="form-input" id="annualIncome" placeholder="Enter annual income">
            </div>
            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> Loan Amount (₹)</label>
                <input type="number" class="form-input" id="loanAmount" placeholder="Enter loan amount">
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Tenure</label>
                <select class="form-input" id="loanTenure">
                    <option value="12">12 months</option>
                    <option value="24">24 months</option>
                    <option value="36">36 months</option>
                    <option value="48">48 months</option>
                    <option value="60">60 months</option>
                    <option value="84">84 months</option>
                    <option value="120">120 months</option>
                </select>
            </div>
            <button class="apply-now-btn" onclick="submitApplication()">
                <i class="fas fa-paper-plane"></i> Apply Now →
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
        const tenure = parseInt(document.getElementById('emiTenure').value);

        if (!amount || !rate || !tenure) {
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
        document.getElementById('loanTypeDisplay').value = loanType || 'Professional Loan';
        document.getElementById('annualIncome').value = '';
        document.getElementById('loanAmount').value = '';
        document.getElementById('applyModal').classList.add('active');
    }

    function submitApplication() {
        const type = document.getElementById('loanTypeDisplay').value;
        const name = document.getElementById('applicantName').value;
        const amount = document.getElementById('loanAmount').value;

        if (!amount) {
            showToast('Please enter loan amount');
            return;
        }

        showToast('✓ ' + type + ' application submitted for ' + name + '!');
        closeModal('applyModal');
    }

    // ============================================================
    // TOAST & MODAL HELPERS
    // ============================================================
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
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
    // EXPOSE GLOBALLY
    // ============================================================
    Object.assign(window, {
        calculateEMI, openApplyModal, submitApplication,
        showToast, closeModal
    });
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>