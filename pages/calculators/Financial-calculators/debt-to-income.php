<?php
// FILE: debt-to-income.php
session_start();

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

include('../../../includes/dbConfig.php');
include('../../../includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>LOGINTOLOANS | Debt to Income Ratio Calculator – Analyze Your Financial Health</title>
    <meta name="description" content="Calculate your Debt-to-Income (DTI) ratio instantly. Understand your financial health and loan eligibility based on your income and existing debt obligations.">
    <meta name="keywords" content="debt to income ratio, DTI calculator, financial health check, loan eligibility, debt analysis, LOGINTOLOANS">
    <meta name="author" content="LOGINTOLOANS">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #060F22;
            --navy-mid: #0A1E40;
            --navy-light: #122F66;
            --orange: #F97316;
            --orange-dark: #D95E06;
            --orange-light: #FFB57A;
            --gold: #E8C55A;
            --white: #FFFFFF;
            --text-muted: #8C9DB8;
            --text-body: #3D5070;
            --border: #E3EAF4;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #3B82F6;
            --shadow-lg: 0 24px 64px rgba(6,15,34,0.14);
            --radius-md: 20px;
            --radius-lg: 32px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #F8FAFF 0%, #F1F5F9 100%);
            color: var(--text-body);
            min-height: 100vh;
            padding-bottom: 20px;
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--orange); border-radius: 10px; }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: transparent;
            min-height: 100vh;
            position: relative;
            padding-bottom: 20px;
        }

        .page { display: none; animation: fadeIn 0.3s ease; }
        .page.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== BACK BUTTON ===== */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--white);
            border: none;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            color: var(--navy);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            margin-bottom: 16px;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .back-btn:active {
            transform: scale(0.96);
        }
        .back-btn i {
            color: var(--orange);
        }

        /* ===== TOAST ===== */
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--navy);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 13px;
            z-index: 1000;
            transition: transform 0.3s;
            white-space: nowrap;
        }
        .toast.show { transform: translateX(-50%) translateY(0); }

        /* ===== CALCULATOR WRAPPER ===== */
        .calculator-wrapper {
            max-width: 480px;
            margin: 0 auto;
            padding: 16px 16px 40px;
        }

        .calculator-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .calculator-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 900;
            background: linear-gradient(135deg, var(--navy) 0%, var(--orange) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 6px;
        }
        .calculator-header p {
            font-size: 13px;
            color: var(--text-muted);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            padding: 5px 14px;
            border-radius: 100px;
            margin-bottom: 12px;
            font-size: 11px;
            font-weight: 600;
            color: var(--orange-dark);
        }
        .badge i { color: var(--orange); }

        .calculator-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 18px;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(249,115,22,0.1);
            width: 100%;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 14px;
            width: 100%;
        }
        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }
        .form-group label i { color: var(--orange); }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-size: 15px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            color: var(--navy);
            background: #FAFBFD;
            transition: all 0.25s ease;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 4px rgba(249,115,22,0.1);
        }

        .input-prefix { position: relative; width: 100%; }
        .input-prefix input { padding-left: 36px; width: 100%; }
        .prefix-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--orange);
            font-weight: 600;
            font-size: 14px;
        }

        .double-group { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; width: 100%; }
        .double-group .form-group { margin-bottom: 0; }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
            margin: 14px 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
        }
        .income-section, .debt-section {
            background: #F8FAFF;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 14px;
            width: 100%;
        }
        .income-section .section-title { margin-top: 0; }
        .debt-section .section-title { margin-top: 0; }

        /* DTI Gauge */
        .dti-gauge {
            text-align: center;
            padding: 18px;
            background: linear-gradient(135deg, #F8FAFF, #F1F5F9);
            border-radius: 18px;
            margin: 14px 0;
            width: 100%;
        }
        .gauge-container {
            position: relative;
            width: 180px;
            height: 90px;
            margin: 0 auto;
            overflow: hidden;
        }
        .gauge-bg {
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: conic-gradient(
                from 180deg,
                var(--success) 0deg 90deg,
                var(--warning) 90deg 162deg,
                var(--danger) 162deg 360deg
            );
            bottom: 0;
            left: 0;
        }
        .gauge-mask {
            position: absolute;
            width: 150px;
            height: 150px;
            background: var(--white);
            border-radius: 50%;
            bottom: 15px;
            left: 15px;
        }
        .gauge-needle {
            position: absolute;
            width: 3px;
            height: 75px;
            background: var(--navy);
            bottom: 15px;
            left: 88px;
            transform-origin: bottom center;
            transform: rotate(-90deg);
            transition: transform 0.5s ease;
            border-radius: 4px;
        }
        .gauge-needle::after {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            background: var(--navy);
            border-radius: 50%;
            bottom: -6px;
            left: -5px;
        }
        .dti-value {
            position: relative;
            margin-top: 18px;
            font-size: 30px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }
        .dti-status {
            font-size: 14px;
            font-weight: 600;
            margin-top: 4px;
        }
        .dti-status.excellent { color: var(--success); }
        .dti-status.good { color: #10B981; }
        .dti-status.fair { color: var(--warning); }
        .dti-status.poor { color: var(--danger); }

        /* Results Grid */
        .result-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin: 14px 0;
            width: 100%;
        }
        .result-card {
            background: linear-gradient(135deg, #F8FAFF, #F1F5F9);
            border-radius: 14px;
            padding: 12px 8px;
            text-align: center;
            width: 100%;
        }
        .result-card i {
            font-size: 18px;
            color: var(--orange);
            margin-bottom: 2px;
            display: block;
        }
        .result-label {
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 2px;
        }
        .result-value {
            font-size: 15px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }

        /* Analysis Section */
        .analysis-section {
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 14px;
            padding: 16px;
            margin-top: 14px;
            width: 100%;
        }
        .analysis-section h4 {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .analysis-section h4 i { color: var(--orange); }
        .analysis-text {
            font-size: 12px;
            line-height: 1.6;
            color: var(--text-body);
        }
        .suggestions-list {
            list-style: none;
            margin-top: 10px;
        }
        .suggestions-list li {
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(249,115,22,0.15);
            font-size: 12px;
        }
        .suggestions-list li:last-child { border-bottom: none; }
        .suggestions-list li i {
            width: 18px;
            color: var(--orange);
            font-size: 13px;
        }

        /* Formula */
        .formula-section {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 18px;
            margin-top: 20px;
            border: 1px solid var(--border);
            width: 100%;
        }
        .formula-section h3 {
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .formula-section h3 i { color: var(--orange); }
        .formula-box {
            background: #F8FAFF;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 14px;
            border-left: 4px solid var(--orange);
        }
        .formula-box p {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 6px;
        }
        .formula-desc { font-size: 12px; color: var(--text-muted); line-height: 1.6; }
        .formula-note {
            background: #FEF3E8;
            padding: 14px;
            border-radius: 12px;
            font-size: 12px;
            color: var(--orange-dark);
            margin-top: 10px;
        }

        @media (max-width: 400px) {
            .double-group { grid-template-columns: 1fr; gap: 8px; }
            .result-grid { grid-template-columns: 1fr; }
            .calculator-wrapper { padding: 12px 12px 30px; }
            .calculator-header h1 { font-size: 24px; }
            .calculator-card { padding: 14px; }
            .gauge-container { transform: scale(0.85); }
            .dti-value { font-size: 26px; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== CALCULATOR PAGE ===== -->
    <div id="calculatorPage" class="page active">
        <div class="calculator-wrapper">
            <!-- Back Button -->
            <button class="back-btn" onclick="window.location.href='../../../index.php'">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </button>

            <div class="calculator-header">
                <div class="badge">
                    <i class="fas fa-chart-pie"></i>
                    <span>Debt-to-Income Ratio Calculator</span>
                </div>
                <h1>Analyze Your Financial Health</h1>
                <p>Calculate your DTI ratio to understand loan eligibility and financial stability</p>
            </div>

            <div class="calculator-card">
                <!-- Income Section -->
                <div class="income-section">
                    <div class="section-title">
                        <i class="fas fa-rupee-sign" style="color: var(--success);"></i> Monthly Income
                    </div>
                    <div class="double-group">
                        <div class="form-group">
                            <label><i class="fas fa-briefcase"></i> SALARY / BUSINESS</label>
                            <div class="input-prefix">
                                <span class="prefix-icon">₹</span>
                                <input type="number" id="salaryIncome" value="50000" step="5000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-chart-line"></i> RENTAL / OTHER</label>
                            <div class="input-prefix">
                                <span class="prefix-icon">₹</span>
                                <input type="number" id="otherIncome" value="0" step="5000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Debt Section -->
                <div class="debt-section">
                    <div class="section-title">
                        <i class="fas fa-credit-card" style="color: var(--danger);"></i> Monthly Debt Obligations
                    </div>
                    <div class="double-group">
                        <div class="form-group">
                            <label><i class="fas fa-home"></i> HOME LOAN EMI</label>
                            <div class="input-prefix">
                                <span class="prefix-icon">₹</span>
                                <input type="number" id="homeLoanEmi" value="15000" step="1000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-car"></i> CAR LOAN EMI</label>
                            <div class="input-prefix">
                                <span class="prefix-icon">₹</span>
                                <input type="number" id="carLoanEmi" value="5000" step="1000">
                            </div>
                        </div>
                    </div>
                    <div class="double-group">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> PERSONAL LOAN EMI</label>
                            <div class="input-prefix">
                                <span class="prefix-icon">₹</span>
                                <input type="number" id="personalLoanEmi" value="3000" step="1000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-credit-card"></i> CREDIT CARD BILLS</label>
                            <div class="input-prefix">
                                <span class="prefix-icon">₹</span>
                                <input type="number" id="creditCardBills" value="5000" step="1000">
                            </div>
                        </div>
                    </div>
                    <div class="double-group">
                        <div class="form-group">
                            <label><i class="fas fa-graduation-cap"></i> EDUCATION LOAN</label>
                            <div class="input-prefix">
                                <span class="prefix-icon">₹</span>
                                <input type="number" id="educationLoanEmi" value="0" step="1000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-hand-holding-usd"></i> OTHER LOANS</label>
                            <div class="input-prefix">
                                <span class="prefix-icon">₹</span>
                                <input type="number" id="otherLoanEmi" value="0" step="1000">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DTI Gauge -->
                <div class="dti-gauge">
                    <div class="gauge-container">
                        <div class="gauge-bg"></div>
                        <div class="gauge-mask"></div>
                        <div class="gauge-needle" id="gaugeNeedle"></div>
                    </div>
                    <div class="dti-value">
                        <span id="dtiPercentage">0</span>%
                    </div>
                    <div class="dti-status" id="dtiStatus">Loading...</div>
                </div>

                <!-- Results Grid -->
                <div class="result-grid">
                    <div class="result-card">
                        <i class="fas fa-rupee-sign"></i>
                        <div class="result-label">Total Income</div>
                        <div class="result-value" id="totalIncome">₹0</div>
                    </div>
                    <div class="result-card">
                        <i class="fas fa-credit-card"></i>
                        <div class="result-label">Total Debt</div>
                        <div class="result-value" id="totalDebt">₹0</div>
                    </div>
                    <div class="result-card">
                        <i class="fas fa-chart-pie"></i>
                        <div class="result-label">DTI Ratio</div>
                        <div class="result-value" id="dtiRatio">0%</div>
                    </div>
                    <div class="result-card">
                        <i class="fas fa-hand-holding-usd"></i>
                        <div class="result-label">Disposable Income</div>
                        <div class="result-value" id="disposableIncome">₹0</div>
                    </div>
                </div>

                <!-- Analysis Section -->
                <div class="analysis-section">
                    <h4><i class="fas fa-chart-line"></i> Financial Health Analysis</h4>
                    <div class="analysis-text" id="analysisText">
                        Enter your income and debt details to see analysis.
                    </div>
                    <ul class="suggestions-list" id="suggestionsList">
                        <li><i class="fas fa-lightbulb"></i> Enter your details to get personalized suggestions</li>
                    </ul>
                </div>
            </div>

            <!-- Formula Section -->
            <div class="formula-section">
                <h3><i class="fas fa-square-root-alt"></i> DTI Formula Explained</h3>
                <div class="formula-box">
                    <p>DTI Ratio = (Total Monthly Debt / Total Monthly Income) × 100</p>
                    <div class="formula-desc">
                        <strong>Total Debt</strong> = Home + Car + Personal + Credit Card + Education + Other EMIs<br>
                        <strong>Total Income</strong> = Salary + Business + Rental + Other Income
                    </div>
                </div>
                <div class="formula-box">
                    <p>DTI Interpretation:</p>
                    <div class="formula-desc">
                        • <strong>&lt; 30%</strong> - Excellent: Best rates &amp; easy approval<br>
                        • <strong>30-40%</strong> - Good: Acceptable for most loans<br>
                        • <strong>40-50%</strong> - Fair: May get approval with higher rates<br>
                        • <strong>&gt; 50%</strong> - Poor: Difficult to get approved
                    </div>
                </div>
                <div class="formula-note">
                    <i class="fas fa-lightbulb"></i> <strong>Pro Tip:</strong> Most lenders prefer DTI below 40%. Pay off smaller debts first and avoid new loans to improve your ratio.
                </div>
            </div>
        </div>
    </div>

</div>

<div id="toast" class="toast"></div>

<script>
    // ===== TOAST =====
    function showToast(msg) {
        var t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(function() {
            t.classList.remove('show');
        }, 3000);
    }

    // ===== DTI CALCULATOR =====
    var salaryIncome = document.getElementById('salaryIncome');
    var otherIncome = document.getElementById('otherIncome');
    var homeLoanEmi = document.getElementById('homeLoanEmi');
    var carLoanEmi = document.getElementById('carLoanEmi');
    var personalLoanEmi = document.getElementById('personalLoanEmi');
    var creditCardBills = document.getElementById('creditCardBills');
    var educationLoanEmi = document.getElementById('educationLoanEmi');
    var otherLoanEmi = document.getElementById('otherLoanEmi');
    
    var totalIncomeSpan = document.getElementById('totalIncome');
    var totalDebtSpan = document.getElementById('totalDebt');
    var dtiRatioSpan = document.getElementById('dtiRatio');
    var disposableIncomeSpan = document.getElementById('disposableIncome');
    var dtiPercentageSpan = document.getElementById('dtiPercentage');
    var dtiStatusSpan = document.getElementById('dtiStatus');
    var gaugeNeedle = document.getElementById('gaugeNeedle');
    var analysisText = document.getElementById('analysisText');
    var suggestionsList = document.getElementById('suggestionsList');

    function formatIndianCurrency(amount) {
        if (isNaN(amount) || amount <= 0) return '₹0';
        var formatter = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        return formatter.format(amount);
    }

    function calculateDTI() {
        var salary = parseFloat(salaryIncome.value) || 0;
        var other = parseFloat(otherIncome.value) || 0;
        var totalIncome = salary + other;
        
        var homeEmi = parseFloat(homeLoanEmi.value) || 0;
        var carEmi = parseFloat(carLoanEmi.value) || 0;
        var personalEmi = parseFloat(personalLoanEmi.value) || 0;
        var creditCard = parseFloat(creditCardBills.value) || 0;
        var educationEmi = parseFloat(educationLoanEmi.value) || 0;
        var otherEmi = parseFloat(otherLoanEmi.value) || 0;
        
        var totalDebt = homeEmi + carEmi + personalEmi + creditCard + educationEmi + otherEmi;
        var dti = totalIncome > 0 ? (totalDebt / totalIncome) * 100 : 0;
        dti = Math.min(100, Math.max(0, dti));
        var disposableIncome = totalIncome - totalDebt;
        
        totalIncomeSpan.innerText = formatIndianCurrency(totalIncome);
        totalDebtSpan.innerText = formatIndianCurrency(totalDebt);
        dtiRatioSpan.innerText = dti.toFixed(1) + '%';
        dtiPercentageSpan.innerText = dti.toFixed(1);
        disposableIncomeSpan.innerText = formatIndianCurrency(disposableIncome);
        
        var needleAngle = -90 + (dti / 100) * 180;
        gaugeNeedle.style.transform = 'rotate(' + needleAngle + 'deg)';
        
        var status = '', statusClass = '', analysis = '', suggestions = [];
        
        if (dti <= 30) {
            status = 'Excellent - Very Healthy';
            statusClass = 'excellent';
            analysis = 'Excellent! Your DTI ratio is very low. You are in a strong financial position and lenders will offer you the best interest rates.';
            suggestions = [
                '🎯 You have excellent loan eligibility.',
                '🏦 You can negotiate for lower interest rates.',
                '📈 Maintain this ratio by avoiding unnecessary debt.',
                '💰 Consider increasing your investments.'
            ];
        } else if (dti <= 40) {
            status = 'Good - Acceptable';
            statusClass = 'good';
            analysis = 'Good! Your DTI ratio is within the acceptable range. Most lenders will approve your loan applications at standard rates.';
            suggestions = [
                '✅ Most lenders will approve your loan applications.',
                '📉 Try to keep DTI below 40% for better terms.',
                '💳 Pay credit card bills in full each month.',
                '📊 Consider paying down smaller debts.'
            ];
        } else if (dti <= 50) {
            status = 'Fair - Borderline';
            statusClass = 'fair';
            analysis = 'Fair. Your DTI ratio is at the borderline. You may still get loan approval, but possibly at higher interest rates.';
            suggestions = [
                '⚠️ Loan approval may come with higher interest rates.',
                '📉 Focus on paying down high-interest debt first.',
                '💰 Consider increasing your income through side income.',
                '🔄 Avoid taking any new loans until DTI improves.'
            ];
        } else {
            status = 'Poor - High Risk';
            statusClass = 'poor';
            analysis = 'Poor. Your DTI ratio is high, indicating significant financial stress. Loan approval will be difficult.';
            suggestions = [
                '🚨 Work on reducing existing debt before applying for new loans.',
                '📉 Aim to pay more than minimum due on credit cards.',
                '💰 Consider a balance transfer to lower interest rates.',
                '🔄 Look for debt consolidation options.',
                '📊 Create a strict budget to reduce expenses.'
            ];
        }
        
        dtiStatusSpan.innerText = status;
        dtiStatusSpan.className = 'dti-status ' + statusClass;
        
        var creditUtilization = totalIncome > 0 ? (creditCard / totalIncome) * 100 : 0;
        if (creditUtilization > 20 && dti > 40) {
            suggestions.push('💳 Your credit card bills are high. Try to reduce credit card spending.');
        }
        
        analysisText.innerHTML = analysis;
        var suggestionsHTML = '';
        for (var i = 0; i < suggestions.length; i++) {
            suggestionsHTML += '<li><i class="fas fa-check-circle"></i> ' + suggestions[i] + '</li>';
        }
        suggestionsList.innerHTML = suggestionsHTML;
    }
    
    salaryIncome.addEventListener('input', calculateDTI);
    otherIncome.addEventListener('input', calculateDTI);
    homeLoanEmi.addEventListener('input', calculateDTI);
    carLoanEmi.addEventListener('input', calculateDTI);
    personalLoanEmi.addEventListener('input', calculateDTI);
    creditCardBills.addEventListener('input', calculateDTI);
    educationLoanEmi.addEventListener('input', calculateDTI);
    otherLoanEmi.addEventListener('input', calculateDTI);
    
    calculateDTI();
</script>
</body>
<?php
include('../../../includes/footer-nav.php');
?>
</html>