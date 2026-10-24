<?php
// FILE: loan-comparison.php
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
    <meta name="theme-color" content="#0a0a1a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>LOGINTOLOANS | Loan Comparison Calculator – Compare Loan Offers Side by Side</title>
    <meta name="description" content="Compare multiple loan offers side by side. Analyze interest rates, EMIs, total interest, and processing fees to choose the best loan for your needs.">
    <meta name="keywords" content="loan comparison, compare loans, best loan offers, EMI comparison, loan interest comparison, LOGINTOLOANS">
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

        /* ===== APP STATUS BAR ===== */
        .app-status-bar {
            height: 44px;
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            color: rgba(255,255,255,0.6);
            font-size: 11px;
            font-weight: 500;
        }
        .app-status-bar .time { color: white; font-weight: 600; }
        .app-status-bar .icons { display: flex; gap: 6px; }

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
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--navy);
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 13px;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            max-width: 90%;
            overflow: hidden;
            text-overflow: ellipsis;
            box-shadow: var(--shadow-lg);
            font-weight: 500;
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

        /* Common Inputs */
        .common-inputs {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 18px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(249,115,22,0.1);
            width: 100%;
        }
        .common-inputs h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .common-inputs h3 i { color: var(--orange); }
        .double-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            width: 100%;
        }
        .form-group {
            margin-bottom: 0;
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

        /* Loan Cards - Fixed Alignment */
        .loan-cards-container {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 20px;
            width: 100%;
            padding: 0;
        }

        .loan-card {
            background: var(--white);
            border-radius: 20px;
            padding: 16px 18px 18px;
            box-shadow: 0 4px 20px rgba(6,15,34,0.08);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
            width: 100%;
            display: flex;
            flex-direction: column;
        }
        .loan-card.recommended {
            border: 2px solid var(--orange);
            background: linear-gradient(135deg, #FFFFFF, #FFF9F5);
            box-shadow: 0 4px 20px rgba(249,115,22,0.15);
        }
        .recommended-badge {
            position: absolute;
            top: -10px;
            right: 14px;
            background: var(--orange);
            color: white;
            padding: 3px 14px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(249,115,22,0.3);
            z-index: 5;
            letter-spacing: 0.5px;
        }
        .loan-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 2px dashed var(--border);
            margin-bottom: 12px;
            width: 100%;
        }
        .bank-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--navy);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .bank-name i { 
            color: var(--orange); 
            font-size: 14px;
        }
        .interest-rate {
            font-size: 24px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--orange);
            white-space: nowrap;
        }
        .interest-rate small { 
            font-size: 12px; 
            font-weight: 500; 
            color: var(--text-muted); 
        }
        .loan-details {
            margin: 8px 0;
            width: 100%;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
            width: 100%;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .label { 
            color: var(--text-muted); 
            font-size: 11px;
        }
        .detail-row .value { 
            font-weight: 700; 
            color: var(--navy);
            font-size: 12px;
        }
        .emi-amount {
            background: #F8FAFF;
            padding: 10px 12px;
            border-radius: 12px;
            text-align: center;
            margin: 10px 0 8px;
            width: 100%;
        }
        .emi-amount .label { 
            font-size: 10px; 
            color: var(--text-muted); 
            display: block;
            margin-bottom: 2px;
        }
        .emi-amount .value {
            font-size: 20px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--orange);
        }
        .loan-card-footer {
            text-align: center;
            margin-top: 10px;
            width: 100%;
        }
        .select-btn {
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            font-family: 'DM Sans', sans-serif;
        }
        .select-btn:active { 
            transform: scale(0.97); 
            background: var(--orange);
        }

        /* Add Loan Button */
        .add-loan-container {
            text-align: center;
            margin: 14px 0 20px;
            width: 100%;
        }
        .add-loan-btn {
            background: var(--white);
            border: 2px dashed var(--orange);
            color: var(--orange);
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'DM Sans', sans-serif;
        }
        .add-loan-btn:active {
            background: var(--orange);
            color: white;
            transform: scale(0.96);
        }

        /* Comparison Summary */
        .comparison-summary {
            background: linear-gradient(135deg, #F8FAFF, #F1F5F9);
            border-radius: 20px;
            padding: 18px;
            margin-top: 20px;
            width: 100%;
        }
        .comparison-summary h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .comparison-summary h3 i { color: var(--orange); }
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            width: 100%;
        }
        .summary-card {
            background: var(--white);
            padding: 12px 10px;
            border-radius: 14px;
            text-align: center;
            border: 1px solid var(--border);
            width: 100%;
        }
        .summary-card i {
            font-size: 20px;
            color: var(--orange);
            margin-bottom: 4px;
            display: block;
        }
        .summary-card .label {
            font-size: 9px;
            color: var(--text-muted);
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .summary-card .value {
            font-size: 15px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }
        .summary-card .best {
            font-size: 9px;
            color: var(--success);
            margin-top: 2px;
        }

        /* Formula */
        .formula-section {
            background: var(--white);
            border-radius: 20px;
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

        /* ===== FLOATING ACTION BUTTON ===== */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            color: white;
            border: none;
            font-size: 24px;
            box-shadow: 0 4px 20px rgba(249,115,22,0.4);
            cursor: pointer;
            transition: all 0.3s;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .fab:active {
            transform: scale(0.9);
        }

        /* Responsive */
        @media (max-width: 400px) {
            .double-group { grid-template-columns: 1fr; gap: 8px; }
            .summary-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
            .calculator-wrapper { padding: 12px 12px 30px; }
            .calculator-header h1 { font-size: 24px; }
            .common-inputs { padding: 14px; }
            .loan-card { 
                padding: 14px 14px 16px; 
            }
            .interest-rate { font-size: 20px; }
            .bank-name { font-size: 14px; }
            .emi-amount .value { font-size: 18px; }
            .summary-card .value { font-size: 14px; }
            .loan-card-header { padding-bottom: 10px; }
            .detail-row { padding: 5px 0; font-size: 11px; }
            .recommended-badge { 
                font-size: 9px; 
                padding: 2px 10px;
                top: -8px;
                right: 10px;
            }
        }

        @media (min-width: 481px) {
            .app-container {
                border-left: 1px solid var(--border);
                border-right: 1px solid var(--border);
                box-shadow: 0 0 40px rgba(0,0,0,0.05);
            }
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
                <i class="fas fa-arrow-left"></i> Back
            </button>

            <div class="calculator-header">
                <div class="badge">
                    <i class="fas fa-balance-scale"></i>
                    <span>Loan Comparison Tool</span>
                </div>
                <h1>Compare Loan Offers</h1>
                <p>Analyze and compare multiple loan offers side by side</p>
            </div>

            <!-- Common Inputs -->
            <div class="common-inputs">
                <h3><i class="fas fa-rupee-sign" style="color: var(--orange);"></i> Common Loan Parameters</h3>
                <div class="double-group">
                    <div class="form-group">
                        <label><i class="fas fa-rupee-sign"></i> LOAN AMOUNT (₹)</label>
                        <div class="input-prefix">
                            <span class="prefix-icon">₹</span>
                            <input type="number" id="loanAmount" value="1000000" step="50000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> LOAN TENURE (Years)</label>
                        <input type="number" id="loanTenure" value="5" step="1">
                    </div>
                </div>
            </div>

            <!-- Loan Cards Container -->
            <div class="loan-cards-container" id="loanCardsContainer">
                <!-- Loan Card 1 - SBI -->
                <div class="loan-card recommended" id="loanCard1">
                    <div class="recommended-badge">⭐ Recommended</div>
                    <div class="loan-card-header">
                        <div class="bank-name"><i class="fas fa-university"></i> SBI</div>
                        <div class="interest-rate">8.50<small>%</small></div>
                    </div>
                    <div class="loan-details">
                        <div class="detail-row"><span class="label">Processing Fee</span><span class="value" id="fee1">1.00%</span></div>
                        <div class="detail-row"><span class="label">Monthly EMI</span><span class="value" id="emi1">₹0</span></div>
                        <div class="detail-row"><span class="label">Total Interest</span><span class="value" id="interest1">₹0</span></div>
                        <div class="detail-row"><span class="label">Total Payment</span><span class="value" id="total1">₹0</span></div>
                    </div>
                    <div class="emi-amount">
                        <span class="label">EMI per ₹1 Lakh</span>
                        <span class="value" id="emiPerLakh1">₹0</span>
                    </div>
                    <div class="loan-card-footer">
                        <button class="select-btn" onclick="showToast('Applying for SBI Loan. Our expert will contact you shortly.')">Select This Offer</button>
                    </div>
                </div>

                <!-- Loan Card 2 - HDFC -->
                <div class="loan-card" id="loanCard2">
                    <div class="loan-card-header">
                        <div class="bank-name"><i class="fas fa-university"></i> HDFC Bank</div>
                        <div class="interest-rate">8.75<small>%</small></div>
                    </div>
                    <div class="loan-details">
                        <div class="detail-row"><span class="label">Processing Fee</span><span class="value" id="fee2">1.25%</span></div>
                        <div class="detail-row"><span class="label">Monthly EMI</span><span class="value" id="emi2">₹0</span></div>
                        <div class="detail-row"><span class="label">Total Interest</span><span class="value" id="interest2">₹0</span></div>
                        <div class="detail-row"><span class="label">Total Payment</span><span class="value" id="total2">₹0</span></div>
                    </div>
                    <div class="emi-amount">
                        <span class="label">EMI per ₹1 Lakh</span>
                        <span class="value" id="emiPerLakh2">₹0</span>
                    </div>
                    <div class="loan-card-footer">
                        <button class="select-btn" onclick="showToast('Applying for HDFC Bank Loan. Our expert will contact you shortly.')">Select This Offer</button>
                    </div>
                </div>

                <!-- Loan Card 3 - ICICI -->
                <div class="loan-card" id="loanCard3">
                    <div class="loan-card-header">
                        <div class="bank-name"><i class="fas fa-university"></i> ICICI Bank</div>
                        <div class="interest-rate">9.00<small>%</small></div>
                    </div>
                    <div class="loan-details">
                        <div class="detail-row"><span class="label">Processing Fee</span><span class="value" id="fee3">1.50%</span></div>
                        <div class="detail-row"><span class="label">Monthly EMI</span><span class="value" id="emi3">₹0</span></div>
                        <div class="detail-row"><span class="label">Total Interest</span><span class="value" id="interest3">₹0</span></div>
                        <div class="detail-row"><span class="label">Total Payment</span><span class="value" id="total3">₹0</span></div>
                    </div>
                    <div class="emi-amount">
                        <span class="label">EMI per ₹1 Lakh</span>
                        <span class="value" id="emiPerLakh3">₹0</span>
                    </div>
                    <div class="loan-card-footer">
                        <button class="select-btn" onclick="showToast('Applying for ICICI Bank Loan. Our expert will contact you shortly.')">Select This Offer</button>
                    </div>
                </div>
            </div>

            <!-- Add Loan Button -->
            <div class="add-loan-container">
                <button class="add-loan-btn" onclick="addLoanCard()">
                    <i class="fas fa-plus"></i> Add Another Loan
                </button>
            </div>

            <!-- Comparison Summary -->
            <div class="comparison-summary">
                <h3><i class="fas fa-chart-line"></i> Comparison Summary</h3>
                <div class="summary-grid" id="summaryGrid">
                    <div class="summary-card">
                        <i class="fas fa-rupee-sign"></i>
                        <div class="label">Lowest EMI</div>
                        <div class="value" id="lowestEmi">₹0</div>
                        <div class="best" id="lowestEmiBank">-</div>
                    </div>
                    <div class="summary-card">
                        <i class="fas fa-percent"></i>
                        <div class="label">Lowest Rate</div>
                        <div class="value" id="lowestRate">0%</div>
                        <div class="best" id="lowestRateBank">-</div>
                    </div>
                    <div class="summary-card">
                        <i class="fas fa-coins"></i>
                        <div class="label">Lowest Interest</div>
                        <div class="value" id="lowestInterest">₹0</div>
                        <div class="best" id="lowestInterestBank">-</div>
                    </div>
                    <div class="summary-card">
                        <i class="fas fa-wallet"></i>
                        <div class="label">Lowest Fee</div>
                        <div class="value" id="lowestFee">0%</div>
                        <div class="best" id="lowestFeeBank">-</div>
                    </div>
                </div>
            </div>

            <!-- Formula Section -->
            <div class="formula-section">
                <h3><i class="fas fa-square-root-alt"></i> Loan Comparison Explained</h3>
                <div class="formula-box">
                    <p>EMI = P × r × (1+r)ⁿ / ((1+r)ⁿ - 1)</p>
                    <div class="formula-desc">Total Interest = (EMI × n) - P | Total Payment = Principal + Interest + Processing Fee</div>
                </div>
                <div class="formula-note">
                    <i class="fas fa-lightbulb"></i> <strong>Tip:</strong> The lowest interest rate doesn't always mean the best deal. Consider processing fees and tenure flexibility too.
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <button class="fab" onclick="showToast('Share this loan comparison with your friends!')">
        <i class="fas fa-share-alt"></i>
    </button>

</div>

<div id="toast" class="toast"></div>

<script>
    // ===== STATUS BAR TIME =====
    function updateStatusTime() {
        var now = new Date();
        var hours = String(now.getHours()).padStart(2, '0');
        var minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('statusTime').textContent = hours + ':' + minutes;
    }
    updateStatusTime();
    setInterval(updateStatusTime, 30000);

    // ===== TOAST =====
    function showToast(msg) {
        var t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(t._timeout);
        t._timeout = setTimeout(function() {
            t.classList.remove('show');
        }, 3000);
    }

    // ===== LOAN COMPARISON CALCULATOR =====
    var loanCount = 3;
    var loanData = [
        { bank: "SBI", rate: 8.50, fee: 1.00 },
        { bank: "HDFC Bank", rate: 8.75, fee: 1.25 },
        { bank: "ICICI Bank", rate: 9.00, fee: 1.50 }
    ];

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

    function calculateEMI(principal, annualRate, months) {
        if (principal <= 0 || months <= 0) return 0;
        var monthlyRate = annualRate / 12 / 100;
        if (monthlyRate === 0) return principal / months;
        return principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
    }

    function calculateAndUpdate() {
        var principal = parseFloat(document.getElementById('loanAmount').value) || 0;
        var tenureYears = parseFloat(document.getElementById('loanTenure').value) || 1;
        var months = tenureYears * 12;

        var allEmis = [];
        var allRates = [];
        var allInterests = [];
        var allFees = [];

        for (var i = 0; i < loanCount; i++) {
            var rate = loanData[i].rate;
            var feePercent = loanData[i].fee;
            
            var emi = calculateEMI(principal, rate, months);
            var totalPayment = emi * months;
            var totalInterest = totalPayment - principal;
            var processingFee = principal * (feePercent / 100);
            var totalWithFee = totalPayment + processingFee;
            var emiPerLakh = calculateEMI(100000, rate, months);
            
            var emiEl = document.getElementById('emi' + (i+1));
            var interestEl = document.getElementById('interest' + (i+1));
            var totalEl = document.getElementById('total' + (i+1));
            var emiPerLakhEl = document.getElementById('emiPerLakh' + (i+1));
            var feeEl = document.getElementById('fee' + (i+1));
            
            if (emiEl) emiEl.innerText = formatIndianCurrency(emi);
            if (interestEl) interestEl.innerText = formatIndianCurrency(totalInterest);
            if (totalEl) totalEl.innerText = formatIndianCurrency(totalWithFee);
            if (emiPerLakhEl) emiPerLakhEl.innerText = formatIndianCurrency(emiPerLakh);
            if (feeEl) feeEl.innerText = feePercent + '%';
            
            var rateEl = document.querySelector('#loanCard' + (i+1) + ' .interest-rate');
            if (rateEl) rateEl.innerHTML = rate + '<small>%</small>';
            
            allEmis.push({ emi: emi, bank: loanData[i].bank, index: i });
            allRates.push({ rate: rate, bank: loanData[i].bank });
            allInterests.push({ interest: totalInterest, bank: loanData[i].bank });
            allFees.push({ fee: feePercent, bank: loanData[i].bank });
        }
        
        var lowestEmi = allEmis[0];
        var lowestRate = allRates[0];
        var lowestInterest = allInterests[0];
        var lowestFee = allFees[0];
        
        for (var j = 1; j < allEmis.length; j++) {
            if (allEmis[j].emi < lowestEmi.emi) lowestEmi = allEmis[j];
            if (allRates[j].rate < lowestRate.rate) lowestRate = allRates[j];
            if (allInterests[j].interest < lowestInterest.interest) lowestInterest = allInterests[j];
            if (allFees[j].fee < lowestFee.fee) lowestFee = allFees[j];
        }
        
        document.getElementById('lowestEmi').innerHTML = formatIndianCurrency(lowestEmi.emi);
        document.getElementById('lowestEmiBank').innerHTML = lowestEmi.bank;
        document.getElementById('lowestRate').innerHTML = lowestRate.rate + '%';
        document.getElementById('lowestRateBank').innerHTML = lowestRate.bank;
        document.getElementById('lowestInterest').innerHTML = formatIndianCurrency(lowestInterest.interest);
        document.getElementById('lowestInterestBank').innerHTML = lowestInterest.bank;
        document.getElementById('lowestFee').innerHTML = lowestFee.fee + '%';
        document.getElementById('lowestFeeBank').innerHTML = lowestFee.bank;
        
        var bestLoanIndex = lowestEmi.index;
        for (var k = 0; k < loanCount; k++) {
            var card = document.getElementById('loanCard' + (k+1));
            if (card) {
                if (k === bestLoanIndex) {
                    card.classList.add('recommended');
                    if (!card.querySelector('.recommended-badge')) {
                        var badge = document.createElement('div');
                        badge.className = 'recommended-badge';
                        badge.innerHTML = '⭐ Recommended';
                        card.insertBefore(badge, card.firstChild);
                    }
                } else {
                    card.classList.remove('recommended');
                    var badge = card.querySelector('.recommended-badge');
                    if (badge) badge.remove();
                }
            }
        }
    }

    function addLoanCard() {
        if (loanCount >= 6) {
            showToast('Maximum 6 loan offers can be compared.');
            return;
        }
        
        loanCount++;
        var newBankName = 'Bank ' + loanCount;
        loanData.push({ bank: newBankName, rate: 9.50, fee: 2.00 });
        
        var container = document.getElementById('loanCardsContainer');
        var newCard = document.createElement('div');
        newCard.className = 'loan-card';
        newCard.id = 'loanCard' + loanCount;
        newCard.innerHTML = 
            '<div class="loan-card-header">' +
                '<div class="bank-name"><i class="fas fa-university"></i> ' + newBankName + '</div>' +
                '<div class="interest-rate">9.50<small>%</small></div>' +
            '</div>' +
            '<div class="loan-details">' +
                '<div class="detail-row"><span class="label">Processing Fee</span><span class="value" id="fee' + loanCount + '">2.00%</span></div>' +
                '<div class="detail-row"><span class="label">Monthly EMI</span><span class="value" id="emi' + loanCount + '">₹0</span></div>' +
                '<div class="detail-row"><span class="label">Total Interest</span><span class="value" id="interest' + loanCount + '">₹0</span></div>' +
                '<div class="detail-row"><span class="label">Total Payment</span><span class="value" id="total' + loanCount + '">₹0</span></div>' +
            '</div>' +
            '<div class="emi-amount">' +
                '<span class="label">EMI per ₹1 Lakh</span>' +
                '<span class="value" id="emiPerLakh' + loanCount + '">₹0</span>' +
            '</div>' +
            '<div class="loan-card-footer">' +
                '<button class="select-btn" onclick="showToast(\'Applying for ' + newBankName + ' Loan. Our expert will contact you shortly.\')">Select This Offer</button>' +
            '</div>';
        container.appendChild(newCard);
        
        calculateAndUpdate();
        newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    document.getElementById('loanAmount').addEventListener('input', calculateAndUpdate);
    document.getElementById('loanTenure').addEventListener('input', calculateAndUpdate);
    
    calculateAndUpdate();
</script>
</body>
<?php
include('../../../includes/footer-nav.php');
?>
</html>