<?php

include('includes/dbConfig.php');
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

// Include the header
include('includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Financial Calculators · LOGINTOLOANS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --primary: #0f172a;
            --primary-light: #1e293b;
            --secondary: #3b82f6;
            --accent: #f59e0b;
            --accent-light: #fbbf24;
            --gradient-start: #0f172a;
            --gradient-end: #1e3a5f;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #94a3b8;
            --background: #f1f5f9;
            --white: #ffffff;
            --border: #e2e8f0;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.12);
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-2xl: 32px;
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

        /* ===== HERO SECTION WITH BACKGROUND IMAGE ===== */
        .hero-section {
            position: relative;
            padding: 30px 20px 40px;
            color: white;
            overflow: hidden;
            border-radius: 0 0 40px 40px;
            min-height: 240px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-image: url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&h=600&fit=crop');
        }
        
        .hero-section .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15,23,42,0.85) 0%, rgba(30,58,95,0.9) 100%);
            z-index: 1;
        }
        
        .hero-section .content {
            position: relative;
            z-index: 2;
        }

        /* Decorative circles */
        .hero-decoration {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
            z-index: 0;
        }
        .hero-decoration-1 {
            width: 200px;
            height: 200px;
            background: var(--secondary);
            top: -80px;
            right: -60px;
        }
        .hero-decoration-2 {
            width: 120px;
            height: 120px;
            background: var(--accent);
            bottom: -40px;
            left: -30px;
        }

        .sub-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
            position: relative;
            z-index: 2;
        }
        .sub-header i {
            cursor: pointer;
            font-size: 18px;
            color: white;
            padding: 8px 10px;
            border-radius: 12px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }
        .sub-header i:active {
            transform: scale(0.9);
            background: rgba(255,255,255,0.25);
        }
        .sub-header span {
            font-size: 20px;
            font-weight: 700;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .hero-section .content > p {
            color: rgba(255,255,255,0.9);
            font-size: 15px;
            font-weight: 400;
            position: relative;
            z-index: 2;
            text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }

        .hero-stats {
            display: flex;
            gap: 24px;
            margin-top: 16px;
            position: relative;
            z-index: 2;
        }
        .hero-stat {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .hero-stat i {
            color: var(--accent);
        }

        /* ===== CALCULATOR SECTIONS ===== */
        .calculators-list-section {
            padding: 12px 16px;
        }

        .section-header-title {
            padding: 16px 0 12px;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-header-title span {
            color: var(--secondary);
        }

        /* ===== EXPANDABLE CALCULATOR ===== */
        .calc-expandable {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 12px;
        }

        .calc-header {
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.3s;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
        }
        .calc-header:active {
            transform: scale(0.98);
        }

        .calc-header-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 20px;
        }
        .calc-header-icon.emi { background: linear-gradient(135deg, rgba(59,130,246,0.15), rgba(59,130,246,0.05)); color: #3b82f6; }
        .calc-header-icon.eligibility { background: linear-gradient(135deg, rgba(20,184,166,0.15), rgba(20,184,166,0.05)); color: #14b8a6; }
        .calc-header-icon.interest { background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(245,158,11,0.05)); color: #f59e0b; }
        .calc-header-icon.financial { background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(139,92,246,0.05)); color: #8b5cf6; }

        .calc-header-info {
            flex: 1;
        }
        .calc-header-info .calc-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .calc-header-info .calc-subtitle {
            font-size: 11px;
            color: var(--text-tertiary);
        }

        .calc-header-arrow {
            font-size: 14px;
            color: var(--text-tertiary);
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }
        .calc-header-arrow.open {
            transform: rotate(180deg);
        }

        .calc-header .calc-count {
            font-size: 10px;
            font-weight: 600;
            color: var(--secondary);
            background: rgba(59,130,246,0.1);
            padding: 2px 12px;
            border-radius: 20px;
            flex-shrink: 0;
        }
        .calc-header .calc-count.green { color: #14b8a6; background: rgba(20,184,166,0.1); }
        .calc-header .calc-count.orange { color: #f59e0b; background: rgba(245,158,11,0.1); }
        .calc-header .calc-count.purple { color: #8b5cf6; background: rgba(139,92,246,0.1); }

        /* ===== SUB LIST ===== */
        .calc-sub-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--background);
        }
        .calc-sub-list.open {
            max-height: 800px;
        }

        .calc-sub-item {
            padding: 11px 18px 11px 66px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border);
        }
        .calc-sub-item:last-child {
            border-bottom: none;
        }
        .calc-sub-item:active {
            background: rgba(59,130,246,0.05);
            transform: scale(0.98);
        }

        .calc-sub-item .sub-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
        }
        .calc-sub-item .sub-icon.blue { background: rgba(59,130,246,0.12); color: #3b82f6; }
        .calc-sub-item .sub-icon.green { background: rgba(34,197,94,0.12); color: #22c55e; }
        .calc-sub-item .sub-icon.orange { background: rgba(245,158,11,0.12); color: #f59e0b; }
        .calc-sub-item .sub-icon.purple { background: rgba(139,92,246,0.12); color: #8b5cf6; }
        .calc-sub-item .sub-icon.pink { background: rgba(236,72,153,0.12); color: #ec4899; }
        .calc-sub-item .sub-icon.teal { background: rgba(20,184,166,0.12); color: #14b8a6; }
        .calc-sub-item .sub-icon.red { background: rgba(239,68,68,0.12); color: #ef4444; }
        .calc-sub-item .sub-icon.indigo { background: rgba(99,102,241,0.12); color: #6366f1; }

        .calc-sub-item .sub-info {
            flex: 1;
        }
        .calc-sub-item .sub-info .sub-title {
            font-size: 13px;
            font-weight: 500;
        }
        .calc-sub-item .sub-info .sub-desc {
            font-size: 11px;
            color: var(--text-tertiary);
        }

        .calc-sub-item .sub-arrow {
            font-size: 12px;
            color: var(--text-tertiary);
            flex-shrink: 0;
        }

        /* ===== FEATURED CARD ===== */
        .featured-calculator {
            background: linear-gradient(135deg, var(--primary), var(--gradient-end));
            border-radius: 24px;
            padding: 20px 24px;
            margin: 16px 16px 0;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            position: relative;
            overflow: hidden;
        }
        .featured-calculator::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .featured-calculator:active {
            transform: scale(0.98);
        }
        .featured-calculator .featured-info h4 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .featured-calculator .featured-info p {
            font-size: 12px;
            opacity: 0.8;
        }
        .featured-calculator .featured-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            position: relative;
            z-index: 1;
        }


        @media (max-width: 400px) {
            .calc-header { padding: 12px 14px; }
            .calc-header-icon { width: 38px; height: 38px; font-size: 16px; }
            .calc-header-info .calc-title { font-size: 13px; }
            .calc-sub-item { padding: 10px 14px 10px 56px; }
            .calc-sub-item .sub-info .sub-title { font-size: 12px; }
            .hero-section { min-height: 200px; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== CALCULATORS PAGE ===== -->
    <div id="calculatorsPage" class="page active">
        <div class="hero-section">
            <div class="hero-decoration hero-decoration-1"></div>
            <div class="hero-decoration hero-decoration-2"></div>
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header">
                    <i class="fas fa-arrow-left" onclick="window.location.href='user/dashboard.php'"></i>
                    <span>Calculators</span>
                </div>
                <p>Smart financial tools at your fingertips</p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <i class="fas fa-calculator"></i>
                        <span>15+ Tools</span>
                    </div>
                    <div class="hero-stat">
                        <i class="fas fa-clock"></i>
                        <span>Instant Results</span>
                    </div>
                </div>
            </div>
        </div>

      

        <!-- ===== EMI CALCULATORS ===== -->
        <div class="calculators-list-section">
            <div class="section-header-title">
                <i class="fas fa-chart-line" style="color: var(--secondary);"></i>
                All <span>Calculators</span>
            </div>

            <div class="calc-expandable" id="emiExpandable">
                <div class="calc-header" id="emiHeader">
                    <div class="calc-header-icon emi"><i class="fas fa-calculator"></i></div>
                    <div class="calc-header-info">
                        <div class="calc-title">EMI Calculators</div>
                        <div class="calc-subtitle">Click to expand all EMI calculators</div>
                    </div>
                    <span class="calc-count">5</span>
                    <i class="fas fa-chevron-down calc-header-arrow" id="emiArrow"></i>
                </div>
                <ul class="calc-sub-list" id="emiSubList">
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/EMI-calculators/homeloan.php'">
                        <div class="sub-icon blue"><i class="fas fa-home"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Home Loan EMI Calculators</div>
                            <div class="sub-desc">Calculate monthly home loan payments</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/EMI-calculators/personal-loan.php'">
                        <div class="sub-icon green"><i class="fas fa-user-tie"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Personal Loan EMI Calculators</div>
                            <div class="sub-desc">Plan your monthly personal loan budget</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/EMI-calculators/carloan.php'">
                        <div class="sub-icon orange"><i class="fas fa-car"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Car Loan EMI Calculators</div>
                            <div class="sub-desc">Calculate car loan monthly payments</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/EMI-calculators/business-loan.php'">
                        <div class="sub-icon purple"><i class="fas fa-briefcase"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Business Loan EMI Calculators</div>
                            <div class="sub-desc">Plan your business loan repayments</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/EMI-calculators/education-loan.php'">
                        <div class="sub-icon pink"><i class="fas fa-graduation-cap"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Education Loan EMI Calculators</div>
                            <div class="sub-desc">Plan your education loan repayment</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                </ul>
            </div>
        </div>

        <!-- ===== ELIGIBILITY CALCULATORS ===== -->
        <div class="calculators-list-section">
           
            <div class="calc-expandable" id="eligibilityExpandable">
                <div class="calc-header" id="eligibilityHeader">
                    <div class="calc-header-icon eligibility"><i class="fas fa-heartbeat"></i></div>
                    <div class="calc-header-info">
                        <div class="calc-title">Eligibility Calculators</div>
                        <div class="calc-subtitle">Check if you qualify for loans</div>
                    </div>
                    <span class="calc-count green">3</span>
                    <i class="fas fa-chevron-down calc-header-arrow" id="eligibilityArrow"></i>
                </div>
                <ul class="calc-sub-list" id="eligibilitySubList">
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Eligibility-calculators/loan-eligibility.php'">
                        <div class="sub-icon teal"><i class="fas fa-user-check"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Loan Eligibility Calculators</div>
                            <div class="sub-desc">Check your loan eligibility instantly</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Eligibility-calculators/personal-loan-eligibility.php'">
                        <div class="sub-icon blue"><i class="fas fa-hand-holding-usd"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Personal Loan Eligibility Calculators</div>
                            <div class="sub-desc">Check if you qualify for a personal loan</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Eligibility-calculators/affordability-calculator.php'">
                        <div class="sub-icon green"><i class="fas fa-wallet"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Affordability Calculator Calculators</div>
                            <div class="sub-desc">Find out what you can afford</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                </ul>
            </div>
        </div>

        <!-- ===== INTEREST & REPAYMENT CALCULATORS ===== -->
        <div class="calculators-list-section">
           

            <div class="calc-expandable" id="interestExpandable">
                <div class="calc-header" id="interestHeader">
                    <div class="calc-header-icon interest"><i class="fas fa-shield-alt"></i></div>
                    <div class="calc-header-info">
                        <div class="calc-title">Interest & Repayment Calculators</div>
                        <div class="calc-subtitle">Calculate interest and plan repayments</div>
                    </div>
                    <span class="calc-count orange">4</span>
                    <i class="fas fa-chevron-down calc-header-arrow" id="interestArrow"></i>
                </div>
                <ul class="calc-sub-list" id="interestSubList">
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Interest-repayment-calculators/intrestrate.php'">
                        <div class="sub-icon red"><i class="fas fa-percentage"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Interest Calculator</div>
                            <div class="sub-desc">Calculate interest rates on loans</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Interest-repayment-calculators/loan-tenure.php'">
                        <div class="sub-icon orange"><i class="fas fa-calendar-alt"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Loan Tenure Calculator</div>
                            <div class="sub-desc">Find the optimal loan tenure</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Interest-repayment-calculators/prepayment.php'">
                        <div class="sub-icon purple"><i class="fas fa-money-check-alt"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Prepayment Calculator</div>
                            <div class="sub-desc">Save money on interest with prepayment</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Interest-repayment-calculators/amortization.php'">
                        <div class="sub-icon pink"><i class="fas fa-chart-bar"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Amortization Calculator</div>
                            <div class="sub-desc">Track your loan repayment schedule</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                </ul>
            </div>
        </div>

        <!-- ===== COMPARISON & FINANCIAL CALCULATORS ===== -->
        <div class="calculators-list-section">
           

            <div class="calc-expandable" id="financialExpandable">
                <div class="calc-header" id="financialHeader">
                    <div class="calc-header-icon financial"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="calc-header-info">
                        <div class="calc-title">Comparison & Financial</div>
                        <div class="calc-subtitle">Compare loans and analyze finances</div>
                    </div>
                    <span class="calc-count purple">4</span>
                    <i class="fas fa-chevron-down calc-header-arrow" id="financialArrow"></i>
                </div>
                <ul class="calc-sub-list" id="financialSubList">
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Financial-calculators/balance-transfer.php'">
                        <div class="sub-icon indigo"><i class="fas fa-exchange-alt"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Balance Transfer Calculators</div>
                            <div class="sub-desc">Transfer your loan balance easily</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Financial-calculators/debt-to-income.php'">
                        <div class="sub-icon teal"><i class="fas fa-chart-pie"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Debt-to-Income Calculator</div>
                            <div class="sub-desc">Analyze your debt-to-income ratio</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Financial-calculators/credit-cibil.php'">
                        <div class="sub-icon blue"><i class="fas fa-credit-card"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">CIBIL Score Calculator</div>
                            <div class="sub-desc">Check and understand your credit score</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                    <li class="calc-sub-item" onclick="window.location.href='https://mobile.logintoloans.com/pages/calculators/Financial-calculators/loan-comparison.php'">
                        <div class="sub-icon green"><i class="fas fa-balance-scale"></i></div>
                        <div class="sub-info">
                            <div class="sub-title">Loan Comparison Calculators</div>
                            <div class="sub-desc">Compare different loan options</div>
                        </div>
                        <i class="fas fa-chevron-right sub-arrow"></i>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    

</div>

<script>
   

    // ===== EXPANDABLE CALCULATORS =====
    function setupExpandable(headerId, subListId, arrowId, defaultOpen) {
        const header = document.getElementById(headerId);
        const subList = document.getElementById(subListId);
        const arrow = document.getElementById(arrowId);
        let isOpen = defaultOpen || false;

        if (defaultOpen) {
            subList.classList.add('open');
            arrow.classList.add('open');
        }

        header.addEventListener('click', function() {
            isOpen = !isOpen;
            subList.classList.toggle('open', isOpen);
            arrow.classList.toggle('open', isOpen);
        });
    }

    // Setup all expandable sections
    setupExpandable('emiHeader', 'emiSubList', 'emiArrow', false);
    setupExpandable('eligibilityHeader', 'eligibilitySubList', 'eligibilityArrow', false);
    setupExpandable('interestHeader', 'interestSubList', 'interestArrow', false);
    setupExpandable('financialHeader', 'financialSubList', 'financialArrow', false);

    
  
</script>
</body>
<?php include('includes/footer-nav.php'); ?>
</html>