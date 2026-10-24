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
    <title>Business Loans - Login To Loans</title>
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

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        /* ===== BACK HEADER ===== */
        .back-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .back-btn {
            background: none;
            border: none;
            font-size: 16px;
            color: var(--text-primary);
            cursor: pointer;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
        }
        .back-btn:active { opacity: 0.6; }
        .back-btn i { font-size: 18px; }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-actions .action-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f5f5f5;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--text-secondary);
            transition: 0.2s;
        }
        .header-actions .action-icon:active { background: #e0e0e0; transform: scale(0.95); }

        /* ===== PAGE HEADER ===== */
        .page-header {
            padding: 20px 16px 12px;
        }
        .page-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .page-header h1 i {
            color: var(--primary);
        }
        .page-header p {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        /* ===== FILTERS SECTION ===== */
        .filters-section {
            background: var(--white);
            border-radius: 16px;
            padding: 16px;
            margin: 0 16px 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }
        .filters-section .filter-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .filter-group {
            margin-bottom: 14px;
        }
        .filter-group:last-child { margin-bottom: 0; }
        .filter-group .filter-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 4px;
            display: flex;
            justify-content: space-between;
        }
        .filter-group .filter-label span {
            color: var(--primary);
        }
        .filter-slider {
            width: 100%;
            height: 4px;
            background: var(--border);
            border-radius: 4px;
            -webkit-appearance: none;
            appearance: none;
            outline: none;
        }
        .filter-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--primary);
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .filter-slider::-moz-range-thumb {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--primary);
            cursor: pointer;
            border: 2px solid white;
        }
        .filter-value {
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            text-align: right;
        }
        .filter-tenure-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .filter-tenure-options .tenure-btn {
            padding: 6px 14px;
            border-radius: 20px;
            border: 1.5px solid var(--border);
            background: var(--white);
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .filter-tenure-options .tenure-btn:active { transform: scale(0.95); }
        .filter-tenure-options .tenure-btn.active {
            border-color: var(--primary);
            background: var(--primary);
            color: white;
        }

        .cashback-banner {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            border-radius: 12px;
            padding: 12px 14px;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-left: 4px solid var(--orange);
        }
        .cashback-banner i {
            color: var(--orange);
            font-size: 20px;
        }
        .cashback-banner .cb-text {
            font-size: 12px;
            font-weight: 600;
            color: #e65100;
        }
        .cashback-banner .cb-text small {
            display: block;
            font-weight: 400;
            font-size: 10px;
            color: var(--text-secondary);
        }

        /* ===== LOAN GREATER THAN 5 LAKHS ===== */
        .loan-greater-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 16px;
            margin: 16px 0 10px;
        }
        .loan-greater-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .loan-greater-header a {
            font-size: 11px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        /* ===== LOAN OFFER CARD ===== */
        .loan-offer-card {
            background: var(--white);
            border-radius: 16px;
            padding: 16px;
            margin: 0 16px 14px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        .loan-offer-card:active { transform: scale(0.98); }

        .loan-offer-card .offer-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .loan-offer-card .offer-logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }
        .offer-logo.poonawalla { background: linear-gradient(135deg, #1a237e, #283593); }
        .offer-logo.tata { background: linear-gradient(135deg, #004d7a, #006699); }
        .offer-logo.bajaj { background: linear-gradient(135deg, #e65100, #ff6f00); }
        .offer-logo.hdfc { background: linear-gradient(135deg, #004d7a, #006699); }
        .offer-logo.icici { background: linear-gradient(135deg, #004080, #0066b3); }
        .offer-logo.axis { background: linear-gradient(135deg, #800000, #cc0000); }

        .loan-offer-card .offer-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .loan-offer-card .offer-sub {
            font-size: 11px;
            color: var(--text-secondary);
        }
        .loan-offer-card .offer-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 16px;
            margin: 10px 0 12px;
            padding: 10px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .loan-offer-card .offer-detail-item {
            display: flex;
            flex-direction: column;
        }
        .loan-offer-card .offer-detail-item .od-label {
            font-size: 9px;
            color: var(--text-tertiary);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .loan-offer-card .offer-detail-item .od-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-top: 1px;
        }
        .loan-offer-card .offer-detail-item .od-value .instant {
            color: var(--success);
        }
        .loan-offer-card .offer-detail-item .od-value .excellent {
            color: var(--success);
            font-weight: 700;
        }
        .loan-offer-card .offer-detail-item .od-value .good {
            color: #1565c0;
            font-weight: 700;
        }
        .loan-offer-card .offer-detail-item .od-value .emi {
            color: var(--warning);
            font-weight: 700;
        }
        .loan-offer-card .offer-detail-item .od-value .apr {
            font-size: 10px;
            font-weight: 400;
            color: var(--text-secondary);
            display: block;
        }

        .loan-offer-card .offer-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .loan-offer-card .offer-actions .action-btn {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-family: 'Inter', sans-serif;
            flex: 1;
            min-width: 70px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .loan-offer-card .offer-actions .action-btn:active { transform: scale(0.95); }
        .loan-offer-card .offer-actions .action-btn-secondary {
            background: var(--border);
            color: var(--text-secondary);
        }
        .loan-offer-card .offer-actions .action-btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
        }
        .loan-offer-card .offer-actions .action-btn-warning {
            background: linear-gradient(135deg, #ff9800, #ffb74d);
            color: white;
        }

        /* ===== GST QUESTION ===== */
        .gst-section {
            background: var(--white);
            border-radius: 16px;
            padding: 16px;
            margin: 16px 16px 0;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }
        .gst-section .gst-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }
        .gst-options {
            display: flex;
            gap: 12px;
        }
        .gst-options .gst-btn {
            padding: 8px 20px;
            border-radius: 30px;
            border: 1.5px solid var(--border);
            background: var(--white);
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .gst-options .gst-btn:active { transform: scale(0.95); }
        .gst-options .gst-btn.active {
            border-color: var(--success);
            background: #e8f5e9;
            color: #2e7d32;
        }
        .gst-options .gst-btn.active i { color: var(--success); }

        .gst-note {
            font-size: 11px;
            color: var(--text-tertiary);
            margin-top: 10px;
            text-align: center;
        }

        /* ===== TOAST ===== */
        .toast { position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%) translateY(100px); background: var(--text-primary); color: white; padding: 10px 18px; border-radius: 24px; font-size: 12px; z-index: 9999; transition: transform 0.3s; white-space: nowrap; }
        .toast.show { transform: translateX(-50%) translateY(0); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .back-header { padding: 10px 14px; }
            .back-btn { font-size: 14px; }
            .page-header h1 { font-size: 20px; }
            .loan-offer-card .offer-details { grid-template-columns: 1fr 1fr; gap: 4px 10px; }
            .loan-offer-card .offer-detail-item .od-value { font-size: 12px; }
            .loan-offer-card .offer-actions .action-btn { font-size: 11px; padding: 6px 10px; min-width: 60px; }
            .filters-section { padding: 14px; }
            .gst-options { flex-direction: column; }
            .gst-options .gst-btn { width: 100%; text-align: center; }
            .filter-tenure-options .tenure-btn { font-size: 11px; padding: 4px 12px; }
            .loan-greater-header h3 { font-size: 14px; }
        }
        @media (max-width: 374px) {
            .page-header h1 { font-size: 18px; }
            .loan-offer-card .offer-details { grid-template-columns: 1fr; }
            .loan-offer-card .offer-actions .action-btn { font-size: 10px; padding: 5px 8px; min-width: 50px; }
        }
    </style>
</head>
<body>

<div class="app-container">

    <!-- BACK HEADER -->
    <div class="back-header">
        <button class="back-btn" onclick="goBack()">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <div class="header-actions">
            <button class="action-icon" onclick="showToast('No new notifications')">
                <i class="fas fa-bell"></i>
            </button>
        </div>
    </div>

    <!-- PAGE HEADER -->
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Business Loans</h1>
        <p>Find the best business loan offers for your enterprise</p>
    </div>

    <!-- FILTERS SECTION -->
    <div class="filters-section">
        <div class="filter-title"><i class="fas fa-sliders-h" style="color: var(--primary);"></i> Filters</div>

        <!-- Loan Amount -->
        <div class="filter-group">
            <div class="filter-label">
                <span>Loan Amount</span>
                <span class="filter-value" id="loanAmountDisplay">₹ 0</span>
            </div>
            <input type="range" class="filter-slider" id="loanAmountSlider" min="0" max="5000000" step="50000" value="0">
        </div>

        <!-- Loan Tenure -->
        <div class="filter-group">
            <div class="filter-label">
                <span>Loan Tenure</span>
                <span class="filter-value" id="loanTenureDisplay">5 Years</span>
            </div>
            <div class="filter-tenure-options">
                <button class="tenure-btn" data-years="1">1 Year</button>
                <button class="tenure-btn" data-years="3">3 Years</button>
                <button class="tenure-btn active" data-years="5">5 Years</button>
                <button class="tenure-btn" data-years="7">7 Years</button>
                <button class="tenure-btn" data-years="10">10 Years</button>
            </div>
        </div>

        <!-- Cashback Banner -->
        <div class="cashback-banner">
            <i class="fas fa-gift"></i>
            <div class="cb-text">
                Get ₹1,000* Cashback on Loan Disbursal
                <small>Valid till 30th Jun'26 | *T&C Apply</small>
            </div>
        </div>
    </div>

    <!-- LOAN GREATER THAN 5 LAKHS -->
    <div class="loan-greater-header">
        <h3>Loan Greater than 5 Lakhs</h3>
        <a href="#" onclick="showToast('Showing all loans')">More</a>
    </div>

    <!-- Poonawalla Fincorp -->
    <div class="loan-offer-card">
        <div class="offer-header">
            <div class="offer-logo poonawalla">P</div>
            <div>
                <div class="offer-name">Poonawalla Fincorp</div>
                <div class="offer-sub">Business Loan</div>
            </div>
        </div>
        <div class="offer-details">
            <div class="offer-detail-item">
                <span class="od-label">Processing Time</span>
                <span class="od-value"><span class="instant">Instant</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Chances Of Approval</span>
                <span class="od-value"><span class="excellent">EXCELLENT</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Interest Rate (p.a.)</span>
                <span class="od-value">15% onwards <span class="apr">APR: 16.44%</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">EMI</span>
                <span class="od-value"><span class="emi">₹ 13,866 onwards</span></span>
            </div>
        </div>
        <div class="offer-actions">
            <button class="action-btn action-btn-secondary" onclick="showToast('Showing details for Poonawalla Fincorp')">
                <i class="fas fa-chevron-down"></i> Show Details
            </button>
            <button class="action-btn action-btn-primary" onclick="openApplicationForm('Poonawalla Fincorp')">
                <i class="fas fa-paper-plane"></i> Apply Now
            </button>
        </div>
    </div>

    <!-- Tata Capital -->
    <div class="loan-offer-card">
        <div class="offer-header">
            <div class="offer-logo tata">T</div>
            <div>
                <div class="offer-name">Tata Capital</div>
                <div class="offer-sub">Business Loan</div>
            </div>
        </div>
        <div class="offer-details">
            <div class="offer-detail-item">
                <span class="od-label">Processing Time</span>
                <span class="od-value"><span class="instant">2 - 3 Days</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Chances Of Approval</span>
                <span class="od-value"><span class="excellent">EXCELLENT</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Interest Rate (p.a.)</span>
                <span class="od-value">13% onwards <span class="apr">APR: 14.5%</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">EMI</span>
                <span class="od-value"><span class="emi">₹ 11,200 onwards</span></span>
            </div>
        </div>
        <div class="offer-actions">
            <button class="action-btn action-btn-secondary" onclick="showToast('Showing details for Tata Capital')">
                <i class="fas fa-chevron-down"></i> Show Details
            </button>
            <button class="action-btn action-btn-primary" onclick="openApplicationForm('Tata Capital')">
                <i class="fas fa-paper-plane"></i> Apply Now
            </button>
        </div>
    </div>

    <!-- Bajaj Finserv -->
    <div class="loan-offer-card">
        <div class="offer-header">
            <div class="offer-logo bajaj">B</div>
            <div>
                <div class="offer-name">Bajaj Finserv</div>
                <div class="offer-sub">Business Loan</div>
            </div>
        </div>
        <div class="offer-details">
            <div class="offer-detail-item">
                <span class="od-label">Processing Time</span>
                <span class="od-value"><span class="instant">1 - 2 Days</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Chances Of Approval</span>
                <span class="od-value"><span class="good">GOOD</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Interest Rate (p.a.)</span>
                <span class="od-value">14% onwards <span class="apr">APR: 15.75%</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">EMI</span>
                <span class="od-value"><span class="emi">₹ 12,500 onwards</span></span>
            </div>
        </div>
        <div class="offer-actions">
            <button class="action-btn action-btn-secondary" onclick="showToast('Showing details for Bajaj Finserv')">
                <i class="fas fa-chevron-down"></i> Show Details
            </button>
            <button class="action-btn action-btn-primary" onclick="openApplicationForm('Bajaj Finserv')">
                <i class="fas fa-paper-plane"></i> Apply Now
            </button>
        </div>
    </div>

    <!-- HDFC Bank -->
    <div class="loan-offer-card">
        <div class="offer-header">
            <div class="offer-logo hdfc">H</div>
            <div>
                <div class="offer-name">HDFC Bank</div>
                <div class="offer-sub">Business Loan</div>
            </div>
        </div>
        <div class="offer-details">
            <div class="offer-detail-item">
                <span class="od-label">Processing Time</span>
                <span class="od-value"><span class="instant">3 - 5 Days</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Chances Of Approval</span>
                <span class="od-value"><span class="excellent">EXCELLENT</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Interest Rate (p.a.)</span>
                <span class="od-value">11.5% onwards <span class="apr">APR: 12.75%</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">EMI</span>
                <span class="od-value"><span class="emi">₹ 10,800 onwards</span></span>
            </div>
        </div>
        <div class="offer-actions">
            <button class="action-btn action-btn-secondary" onclick="showToast('Showing details for HDFC Bank')">
                <i class="fas fa-chevron-down"></i> Show Details
            </button>
            <button class="action-btn action-btn-primary" onclick="openApplicationForm('HDFC Bank')">
                <i class="fas fa-paper-plane"></i> Apply Now
            </button>
        </div>
    </div>

    <!-- ICICI Bank -->
    <div class="loan-offer-card">
        <div class="offer-header">
            <div class="offer-logo icici">I</div>
            <div>
                <div class="offer-name">ICICI Bank</div>
                <div class="offer-sub">Business Loan</div>
            </div>
        </div>
        <div class="offer-details">
            <div class="offer-detail-item">
                <span class="od-label">Processing Time</span>
                <span class="od-value"><span class="instant">2 - 4 Days</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Chances Of Approval</span>
                <span class="od-value"><span class="excellent">EXCELLENT</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Interest Rate (p.a.)</span>
                <span class="od-value">12% onwards <span class="apr">APR: 13.25%</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">EMI</span>
                <span class="od-value"><span class="emi">₹ 11,500 onwards</span></span>
            </div>
        </div>
        <div class="offer-actions">
            <button class="action-btn action-btn-secondary" onclick="showToast('Showing details for ICICI Bank')">
                <i class="fas fa-chevron-down"></i> Show Details
            </button>
            <button class="action-btn action-btn-primary" onclick="openApplicationForm('ICICI Bank')">
                <i class="fas fa-paper-plane"></i> Apply Now
            </button>
        </div>
    </div>

    <!-- Axis Bank -->
    <div class="loan-offer-card">
        <div class="offer-header">
            <div class="offer-logo axis">A</div>
            <div>
                <div class="offer-name">Axis Bank</div>
                <div class="offer-sub">Business Loan</div>
            </div>
        </div>
        <div class="offer-details">
            <div class="offer-detail-item">
                <span class="od-label">Processing Time</span>
                <span class="od-value"><span class="instant">3 - 6 Days</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Chances Of Approval</span>
                <span class="od-value"><span class="good">GOOD</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">Interest Rate (p.a.)</span>
                <span class="od-value">13.5% onwards <span class="apr">APR: 14.9%</span></span>
            </div>
            <div class="offer-detail-item">
                <span class="od-label">EMI</span>
                <span class="od-value"><span class="emi">₹ 12,900 onwards</span></span>
            </div>
        </div>
        <div class="offer-actions">
            <button class="action-btn action-btn-secondary" onclick="showToast('Showing details for Axis Bank')">
                <i class="fas fa-chevron-down"></i> Show Details
            </button>
            <button class="action-btn action-btn-primary" onclick="openApplicationForm('Axis Bank')">
                <i class="fas fa-paper-plane"></i> Apply Now
            </button>
        </div>
    </div>

    <!-- GST SECTION -->
    <div class="gst-section">
        <div class="gst-title">Business registered under GST?</div>
        <div class="gst-options">
            <button class="gst-btn active" onclick="selectGST(this, true)"><i class="fas fa-check-circle"></i> Yes, I have a GST Number</button>
            <button class="gst-btn" onclick="selectGST(this, false)"><i class="fas fa-times-circle"></i> No</button>
        </div>
        <div class="gst-note">
            <i class="fas fa-info-circle"></i> Your selection helps us find most relevant offers for you!
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="toast"></div>
</div>

<script>
    // ============================================================
    // NAVIGATION
    // ============================================================
    function goBack() {
        window.location.href = 'dashboard.php';
    }

    // ============================================================
    // FILTERS - LOAN AMOUNT SLIDER
    // ============================================================
    const loanAmountSlider = document.getElementById('loanAmountSlider');
    const loanAmountDisplay = document.getElementById('loanAmountDisplay');

    loanAmountSlider.addEventListener('input', function() {
        const val = parseInt(this.value);
        if (val === 0) {
            loanAmountDisplay.textContent = '₹ 0';
        } else if (val >= 100000) {
            loanAmountDisplay.textContent = '₹ ' + (val / 100000).toFixed(1) + ' Lakhs';
        } else {
            loanAmountDisplay.textContent = '₹ ' + (val / 1000).toFixed(0) + 'K';
        }
    });

    // ============================================================
    // FILTERS - TENURE BUTTONS
    // ============================================================
    document.querySelectorAll('.tenure-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tenure-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('loanTenureDisplay').textContent = this.dataset.years + ' Years';
        });
    });

    // ============================================================
    // GST SELECTION
    // ============================================================
    function selectGST(btn, hasGST) {
        document.querySelectorAll('.gst-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        showToast(hasGST ? 'GST Number selected' : 'No GST selected');
    }

    // ============================================================
    // APPLICATION FORM
    // ============================================================
    function openApplicationForm(bankName) {
        showToast('Opening application for ' + bankName);
        // You can redirect to application form page here
        // window.location.href = 'apply.php?bank=' + encodeURIComponent(bankName);
    }

    // ============================================================
    // TOAST
    // ============================================================
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(t._timeout);
        t._timeout = setTimeout(() => t.classList.remove('show'), 3000);
    }

    // ============================================================
    // EXPOSE GLOBALLY
    // ============================================================
    Object.assign(window, {
        goBack, showToast, openApplicationForm, selectGST
    });
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>