<?php
include('../../../includes/dbConfig.php');

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
include('../../../includes/header-nav.php');

// Set current page for navigation
$current_page = "expenses-calculator.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#0f172a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Calculate your monthly income, expenses, and savings. Track your budget and financial health.">
    <meta name="keywords" content="expenses calculator, budget tracker, income calculator, savings calculator">
    
    <title>Expenses Calculator · LOGINTOLOANS</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
            --success: #22c55e;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
            padding-bottom: 80px;
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

        /* ===== HERO SECTION ===== */
        .hero-section {
            position: relative;
            padding: 30px 20px 40px;
            color: white;
            overflow: hidden;
            border-radius: 0 0 40px 40px;
            min-height: 200px;
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

        /* ===== CALCULATOR WRAPPER ===== */
        .calculator-wrapper {
            margin: -20px 16px 20px;
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 20px 16px;
            position: relative;
            z-index: 3;
            border: 1px solid var(--border);
        }

        .calculator-title {
            text-align: center;
            color: var(--primary);
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .calculator-title i {
            color: var(--secondary);
            margin-right: 8px;
        }
        .calculator-subtitle {
            text-align: center;
            color: var(--text-secondary);
            font-size: 12px;
            margin-bottom: 20px;
        }

        /* ===== SECTION DIVIDERS ===== */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0 14px;
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--border);
        }
        .section-divider i {
            color: var(--secondary);
            font-size: 14px;
        }

        /* ===== FORM GROUP ===== */
        .form-group {
            margin-bottom: 12px;
        }
        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
            font-weight: 600;
            font-size: 13px;
            color: var(--text-primary);
        }
        .form-group label i {
            width: 18px;
            font-size: 13px;
        }
        .form-group label .label-icon.income {
            color: var(--success);
        }
        .form-group label .label-icon.expense {
            color: var(--danger);
        }
        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 15px;
            transition: all 0.3s;
            background: var(--background);
            color: var(--text-primary);
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--secondary);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
        }
        .form-group input.income-input:focus {
            border-color: var(--success);
            box-shadow: 0 0 0 4px rgba(34,197,94,0.1);
        }
        .form-group input.expense-input:focus {
            border-color: var(--danger);
            box-shadow: 0 0 0 4px rgba(239,68,68,0.1);
        }
        .form-group input[type="number"] {
            -moz-appearance: textfield;
        }
        .form-group input[type="number"]::-webkit-outer-spin-button,
        .form-group input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* ===== BUTTONS ===== */
        .btn-calculate {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--gradient-end));
            color: white;
            border: none;
            padding: 14px;
            border-radius: var(--radius-sm);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-calculate:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        .btn-calculate:active {
            transform: scale(0.98);
        }

        .btn-reset {
            width: 100%;
            background: var(--background);
            color: var(--text-secondary);
            border: 2px solid var(--border);
            padding: 12px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-reset:hover {
            background: var(--border);
            color: var(--text-primary);
        }

        /* ===== RESULT SECTION ===== */
        .result-panel {
            margin-top: 20px;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: var(--radius);
            padding: 16px 18px;
            border: 1px solid rgba(59,130,246,0.2);
            display: none;
            animation: slideUp 0.4s ease;
        }
        .result-panel.show {
            display: block;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed rgba(59,130,246,0.15);
        }
        .result-row:last-child {
            border-bottom: none;
        }

        .result-label {
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .result-label i {
            width: 18px;
            font-size: 14px;
        }

        .result-value {
            font-weight: 700;
            font-size: 16px;
            color: var(--text-primary);
        }
        .result-value.positive {
            color: var(--success);
        }
        .result-value.negative {
            color: var(--danger);
        }
        .result-value.blue {
            color: var(--secondary);
        }

        .savings-badge {
            background: var(--white);
            padding: 2px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 14px;
            color: var(--primary);
            border: 1px solid var(--border);
        }
        .savings-badge.good {
            background: rgba(34,197,94,0.1);
            color: var(--success);
            border-color: rgba(34,197,94,0.2);
        }
        .savings-badge.warning {
            background: rgba(245,158,11,0.1);
            color: var(--warning);
            border-color: rgba(245,158,11,0.2);
        }
        .savings-badge.danger {
            background: rgba(239,68,68,0.1);
            color: var(--danger);
            border-color: rgba(239,68,68,0.2);
        }

        .afford-hint {
            margin-top: 12px;
            padding: 10px 16px;
            background: var(--white);
            border-radius: var(--radius-sm);
            text-align: center;
            font-weight: 500;
            font-size: 13px;
            color: var(--text-secondary);
            border-left: 4px solid var(--secondary);
        }
        .afford-hint strong {
            color: var(--primary);
        }
        .afford-hint .highlight {
            color: var(--secondary);
            font-weight: 700;
        }
        .afford-hint.warning {
            border-left-color: var(--warning);
            background: rgba(245,158,11,0.05);
        }
        .afford-hint.danger {
            border-left-color: var(--danger);
            background: rgba(239,68,68,0.05);
        }

        .footnote {
            margin-top: 12px;
            font-size: 11px;
            color: var(--text-tertiary);
            text-align: center;
            border-top: 1px solid var(--border);
            padding-top: 12px;
        }

        /* ===== TOAST ===== */
        .toast {
            position: fixed;
            bottom: 120px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--primary);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 13px;
            z-index: 1000;
            transition: transform 0.3s ease, opacity 0.3s ease;
            white-space: nowrap;
            max-width: 90%;
            overflow: hidden;
            text-overflow: ellipsis;
            box-shadow: var(--shadow-lg);
            opacity: 0;
            pointer-events: none;
        }
        .toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 400px) {
            .calculator-wrapper {
                padding: 14px 12px;
            }
            .calculator-title {
                font-size: 17px;
            }
            .result-row {
                padding: 6px 0;
                font-size: 13px;
            }
            .result-value {
                font-size: 14px;
            }
            .form-group input {
                padding: 8px 12px;
                font-size: 14px;
            }
            .hero-section {
                min-height: 160px;
            }
            .section-divider {
                font-size: 11px;
                margin: 16px 0 10px;
            }
        }

        @media (min-width: 481px) {
            .app-container {
                border-left: 1px solid var(--border);
                border-right: 1px solid var(--border);
                box-shadow: var(--shadow-lg);
            }
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            .hero-section {
                background: var(--primary) !important;
                min-height: 100px !important;
            }
            .btn-calculate, .btn-reset {
                display: none;
            }
            .result-panel {
                display: block !important;
                border: 1px solid #ccc;
            }
            .calculator-wrapper {
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== HERO SECTION ===== -->
    <div class="hero-section" role="banner">
        <div class="hero-decoration hero-decoration-1" aria-hidden="true"></div>
        <div class="hero-decoration hero-decoration-2" aria-hidden="true"></div>
        <div class="overlay" aria-hidden="true"></div>
        <div class="content">
            <div class="sub-header">
                <i class="fas fa-arrow-left" 
                   onclick="window.location.href='dashboard.php'" 
                   role="button" 
                   aria-label="Go back to dashboard"
                   tabindex="0"></i>
                <span>Expenses Calculator</span>
            </div>
            <p>Track your income, expenses & savings</p>
        </div>
    </div>

    <!-- ===== CALCULATOR ===== -->
    <div class="calculator-wrapper">
        <div class="calculator-title">
            <i class="fas fa-wallet"></i> Budget Tracker
        </div>
        <div class="calculator-subtitle">
            Enter your monthly income and expenses
        </div>

        <form id="financeForm" onsubmit="return false;">
            <!-- ===== INCOME SECTION ===== -->
            <div class="section-divider">
                <i class="fas fa-arrow-up" style="color: var(--success);"></i>
                Income
            </div>

            <div class="form-group">
                <label><i class="fas fa-briefcase label-icon income"></i> Monthly Income (₹)</label>
                <input type="number" class="income-input" id="income" value="50000" min="0" step="1000" placeholder="Enter your monthly salary">
            </div>

            <!-- ===== EXPENSES SECTION ===== -->
            <div class="section-divider">
                <i class="fas fa-arrow-down" style="color: var(--danger);"></i>
                Expenses
            </div>

            <div class="form-group">
                <label><i class="fas fa-home label-icon expense"></i> House Rent (₹)</label>
                <input type="number" class="expense-input" id="rent" value="15000" min="0" step="500" placeholder="Enter rent amount">
            </div>
            <div class="form-group">
                <label><i class="fas fa-utensils label-icon expense"></i> Food Expenses (₹)</label>
                <input type="number" class="expense-input" id="food" value="8000" min="0" step="500" placeholder="Enter food expenses">
            </div>
            <div class="form-group">
                <label><i class="fas fa-bolt label-icon expense"></i> Electricity Bill (₹)</label>
                <input type="number" class="expense-input" id="electricity" value="2000" min="0" step="100" placeholder="Enter electricity bill">
            </div>
            <div class="form-group">
                <label><i class="fas fa-wifi label-icon expense"></i> Mobile/Internet (₹)</label>
                <input type="number" class="expense-input" id="internet" value="1000" min="0" step="100" placeholder="Enter mobile/internet bill">
            </div>
            <div class="form-group">
                <label><i class="fas fa-bus label-icon expense"></i> Transportation (₹)</label>
                <input type="number" class="expense-input" id="transport" value="3000" min="0" step="500" placeholder="Enter transport expenses">
            </div>
            <div class="form-group">
                <label><i class="fas fa-hand-holding-usd label-icon expense"></i> EMI / Loan (₹)</label>
                <input type="number" class="expense-input" id="emi" value="10000" min="0" step="500" placeholder="Enter EMI payments">
            </div>
            <div class="form-group">
                <label><i class="fas fa-heartbeat label-icon expense"></i> Medical (₹)</label>
                <input type="number" class="expense-input" id="medical" value="0" min="0" step="500" placeholder="Enter medical expenses">
            </div>
            <div class="form-group">
                <label><i class="fas fa-graduation-cap label-icon expense"></i> Education (₹)</label>
                <input type="number" class="expense-input" id="education" value="0" min="0" step="500" placeholder="Enter education expenses">
            </div>
            <div class="form-group">
                <label><i class="fas fa-film label-icon expense"></i> Entertainment (₹)</label>
                <input type="number" class="expense-input" id="entertainment" value="0" min="0" step="500" placeholder="Enter entertainment expenses">
            </div>
            <div class="form-group">
                <label><i class="fas fa-ellipsis-h label-icon expense"></i> Other Expenses (₹)</label>
                <input type="number" class="expense-input" id="other" value="4000" min="0" step="500" placeholder="Enter other expenses">
            </div>

            <button type="button" class="btn-calculate" onclick="calculateExpenses()">
                <i class="fas fa-calculator"></i> Calculate
            </button>
            
            <button type="button" class="btn-reset" onclick="resetForm()">
                <i class="fas fa-undo"></i> Reset
            </button>
        </form>

        <!-- ===== RESULT PANEL ===== -->
        <div class="result-panel" id="resultPanel">
            <div class="result-row">
                <span class="result-label"><i class="fas fa-receipt" style="color: var(--danger);"></i> Total Expenses</span>
                <span class="result-value blue" id="totalExpenses">₹ 0</span>
            </div>
            <div class="result-row">
                <span class="result-label"><i class="fas fa-piggy-bank" style="color: var(--success);"></i> Remaining Balance</span>
                <span class="result-value positive" id="remainingBalance">₹ 0</span>
            </div>
            <div class="result-row" style="border-bottom: none;">
                <span class="result-label"><i class="fas fa-chart-pie" style="color: var(--secondary);"></i> Savings Rate</span>
                <span class="savings-badge good" id="savingsPercent">0%</span>
            </div>

            <!-- EMI affordability hint -->
            <div class="afford-hint" id="affordHint">
                💡 <strong>EMI Capacity</strong> · You can comfortably pay <span class="highlight" id="maxEmi">₹ 0</span> / month
            </div>
        </div>

        <div class="footnote">
            <i class="fas fa-shield-alt" style="color: var(--secondary);"></i> 
            Lower EMI = safer loan · Keep savings above 20% of income
        </div>
    </div>

    <!-- ===== TOAST ===== -->
    <div id="toast" class="toast" role="alert" aria-live="polite"></div>

</div>

<script>
    // ===== TOAST NOTIFICATION =====
    let toastTimeout = null;
    
    function showToast(message) {
        const toast = document.getElementById('toast');
        if (!toast) return;
        
        toast.textContent = message;
        toast.classList.add('show');
        
        if (toastTimeout) {
            clearTimeout(toastTimeout);
        }
        
        toastTimeout = setTimeout(function() {
            toast.classList.remove('show');
        }, 3000);
    }

    // ===== CALCULATE EXPENSES =====
    function calculateExpenses() {
        // Get Income
        let income = parseFloat(document.getElementById("income").value) || 0;

        // Get Expenses
        let rent = parseFloat(document.getElementById("rent").value) || 0;
        let food = parseFloat(document.getElementById("food").value) || 0;
        let electricity = parseFloat(document.getElementById("electricity").value) || 0;
        let internet = parseFloat(document.getElementById("internet").value) || 0;
        let transport = parseFloat(document.getElementById("transport").value) || 0;
        let emi = parseFloat(document.getElementById("emi").value) || 0;
        let medical = parseFloat(document.getElementById("medical").value) || 0;
        let education = parseFloat(document.getElementById("education").value) || 0;
        let entertainment = parseFloat(document.getElementById("entertainment").value) || 0;
        let other = parseFloat(document.getElementById("other").value) || 0;

        // Calculate Totals
        let totalExpenses = rent + food + electricity + internet + transport + emi + medical + education + entertainment + other;
        let remaining = income - totalExpenses;
        let savingsPercent = income > 0 ? (remaining / income) * 100 : 0;

        // Format currency
        function formatCurrency(amount) {
            return '₹ ' + Math.round(amount).toLocaleString('en-IN');
        }

        // Update UI - Expenses
        document.getElementById("totalExpenses").textContent = formatCurrency(totalExpenses);

        // Update UI - Remaining
        const remainingSpan = document.getElementById("remainingBalance");
        remainingSpan.textContent = formatCurrency(remaining);
        if (remaining >= 0) {
            remainingSpan.className = 'result-value positive';
        } else {
            remainingSpan.className = 'result-value negative';
        }

        // Update UI - Savings Percentage
        const percentSpan = document.getElementById("savingsPercent");
        let percentDisplay = savingsPercent.toFixed(1) + '%';
        if (income === 0 && totalExpenses === 0) percentDisplay = '0%';
        percentSpan.textContent = percentDisplay;
        
        // Update badge color
        percentSpan.className = 'savings-badge';
        if (savingsPercent >= 20) {
            percentSpan.classList.add('good');
        } else if (savingsPercent >= 10) {
            percentSpan.classList.add('warning');
        } else {
            percentSpan.classList.add('danger');
        }

        // Update UI - EMI Capacity
        let maxAffordableEmi = Math.max(0, remaining);
        document.getElementById("maxEmi").textContent = formatCurrency(maxAffordableEmi);

        // Update affordability hint
        const hint = document.getElementById("affordHint");
        if (remaining < 0) {
            hint.className = 'afford-hint danger';
            hint.innerHTML = '⚠️ <strong>Warning:</strong> Your expenses exceed income by <span class="highlight" style="color: var(--danger);">' + formatCurrency(Math.abs(remaining)) + '</span>';
        } else if (savingsPercent < 10) {
            hint.className = 'afford-hint warning';
            hint.innerHTML = '💡 <strong>Low Savings:</strong> Try to reduce expenses. You can comfortably pay <span class="highlight">' + formatCurrency(maxAffordableEmi) + '</span> / month as EMI';
        } else {
            hint.className = 'afford-hint';
            hint.innerHTML = '💡 <strong>EMI Capacity:</strong> You can comfortably pay <span class="highlight">' + formatCurrency(maxAffordableEmi) + '</span> / month';
        }

        // Show result panel
        document.getElementById("resultPanel").classList.add("show");

        // Show toast
        showToast('✅ Calculation complete!');
    }

    // ===== RESET FORM =====
    function resetForm() {
        document.getElementById("income").value = "50000";
        document.getElementById("rent").value = "15000";
        document.getElementById("food").value = "8000";
        document.getElementById("electricity").value = "2000";
        document.getElementById("internet").value = "1000";
        document.getElementById("transport").value = "3000";
        document.getElementById("emi").value = "10000";
        document.getElementById("medical").value = "0";
        document.getElementById("education").value = "0";
        document.getElementById("entertainment").value = "0";
        document.getElementById("other").value = "4000";

        document.getElementById("resultPanel").classList.remove("show");
        showToast('🔄 Form reset to default values');
    }

    // ===== KEYBOARD SUPPORT =====
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
            e.preventDefault();
            calculateExpenses();
        }
        if (e.key === 'Escape') {
            resetForm();
        }
    });

    // ===== AUTO-CALCULATE =====
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(this._timeout);
                this._timeout = setTimeout(() => {
                    calculateExpenses();
                }, 500);
            });
            input.addEventListener('focus', function() {
                this.select();
            });
        });

        // Auto-calculate on load
        setTimeout(calculateExpenses, 300);
    });

    // ===== BACK BUTTON KEYBOARD SUPPORT =====
    document.addEventListener('DOMContentLoaded', function() {
        const backButton = document.querySelector('.sub-header i.fa-arrow-left');
        if (backButton) {
            backButton.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });
        }
    });

    // ===== SERVICE WORKER (PWA) =====
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('Service Worker registered');
            })
            .catch(function(err) {
                console.log('Service Worker registration failed:', err);
            });
    }

    console.log('💰 LOGINTOLOANS Expenses Calculator loaded');
    console.log('👋 Welcome, <?php echo htmlspecialchars($firstname); ?>!');
</script>

</body>
<?php include('../../../includes/footer-nav.php'); ?>
</html>