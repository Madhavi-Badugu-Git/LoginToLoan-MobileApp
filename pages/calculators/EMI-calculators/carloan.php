<?php
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

// Include the header - FIXED PATH (go up 3 levels to root)
include('../../../includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Car Loan EMI Calculator - LOGINTOLOANS</title>
    <meta name="description" content="Calculate your car loan EMI instantly. Plan your new car or used car loan repayment with our easy-to-use EMI calculator.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --primary: #1a237e;
            --primary-dark: #0d1757;
            --primary-light: #283593;
            --secondary: #00c853;
            --accent: #ff6b35;
            --orange: #ff9800;
            --navy: #060F22;
            --navy-mid: #0A1E40;
            --navy-light: #122F66;
            --orange-dark: #D95E06;
            --orange-light: #FFB57A;
            --gold: #E8C55A;
            --text-primary: #1a1a2e;
            --text-secondary: #666666;
            --text-tertiary: #999999;
            --text-muted: #8C9DB8;
            --text-body: #3D5070;
            --background: #f8f9fc;
            --white: #ffffff;
            --border: #eef2f6;
            --border-light: #E3EAF4;
            --success: #10B981;
            --warning: #F59E0B;
            --info: #3B82F6;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-md: 20px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
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
            box-shadow: var(--shadow);
            margin-bottom: 16px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
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
            background: var(--text-primary);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 13px;
            z-index: 1000;
            transition: transform 0.3s;
            white-space: nowrap;
        }
        .toast.show { transform: translateX(-50%) translateY(0); }

        /* ===== CALCULATOR STYLES (Mobile Optimized) ===== */
        .calculator-wrapper {
            padding: 20px 16px 40px;
        }

        .calculator-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .calculator-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--navy) 0%, var(--orange) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 8px;
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

        .calculator-card {
            background: var(--white);
            border-radius: 24px;
            padding: 20px;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(249,115,22,0.1);
        }

        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
        }
        .form-group label i { color: var(--orange); font-size: 12px; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border-light);
            border-radius: 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: var(--navy);
            background: #FAFBFD;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
        }

        .input-prefix {
            position: relative;
        }
        .input-prefix input { padding-left: 36px; }
        .prefix-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--orange);
            font-weight: 600;
            font-size: 14px;
        }

        .double-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 18px;
        }

        .result-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 24px 0 20px;
            padding-top: 16px;
            border-top: 2px dashed var(--border);
        }
        .result-card {
            background: linear-gradient(135deg, #F8FAFF, #F1F5F9);
            border-radius: 16px;
            padding: 12px 8px;
            text-align: center;
        }
        .result-card i { font-size: 20px; color: var(--orange); margin-bottom: 6px; display: block; }
        .result-label { font-size: 9px; font-weight: 600; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px; }
        .result-value { font-size: 14px; font-weight: 800; color: var(--navy); }

        .info-section {
            margin-top: 20px;
            padding: 16px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 16px;
        }
        .info-section h4, .offers-section h4, .amortization-section h4 {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .info-item {
            background: var(--white);
            padding: 10px;
            border-radius: 12px;
            text-align: center;
        }
        .info-item .label { font-size: 10px; color: var(--text-muted); margin-bottom: 4px; }
        .info-item .value { font-size: 13px; font-weight: 700; color: var(--navy); }

        .offers-section {
            margin-top: 20px;
            padding: 16px;
            background: #F8FAFF;
            border-radius: 16px;
            border-left: 3px solid var(--orange);
        }
        .offers-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .offer-card {
            background: var(--white);
            padding: 10px 12px;
            border-radius: 12px;
            flex: 1;
            min-width: 100px;
            text-align: center;
        }
        .offer-card .bank { font-weight: 700; font-size: 12px; color: var(--navy); }
        .offer-card .rate { color: var(--orange); font-weight: 800; font-size: 14px; }

        .amortization-section {
            margin-top: 20px;
            padding: 16px;
            background: #F8FAFF;
            border-radius: 16px;
        }
        .view-toggle {
            text-align: center;
            margin-top: 12px;
        }
        .view-toggle button {
            background: var(--white);
            border: 1.5px solid var(--orange);
            color: var(--orange);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
        }

        .table-wrapper { overflow-x: auto; max-height: 300px; overflow-y: auto; }
        .amortization-table { width: 100%; font-size: 11px; border-collapse: collapse; }
        .amortization-table th, .amortization-table td { padding: 8px 6px; text-align: right; border-bottom: 1px solid var(--border); }
        .amortization-table th:first-child, .amortization-table td:first-child { text-align: left; }

        .formula-section {
            background: var(--white);
            border-radius: 20px;
            padding: 20px;
            margin-top: 24px;
            border: 1px solid var(--border);
        }
        .formula-section h3 {
            font-size: 18px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .formula-box {
            background: #F8FAFF;
            padding: 16px;
            border-radius: 14px;
            margin-bottom: 16px;
            border-left: 3px solid var(--orange);
        }
        .formula-box p {
            font-family: monospace;
            font-size: 13px;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 8px;
        }
        .formula-desc { font-size: 12px; color: var(--text-muted); line-height: 1.5; }
        .formula-note {
            background: #FEF3E8;
            padding: 12px;
            border-radius: 12px;
            font-size: 12px;
            color: var(--orange-dark);
            margin-top: 12px;
        }
        .highlight { background: #ECFDF5; color: #059669; }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== CALCULATOR PAGE ===== -->
    <div id="calculatorPage" class="page active">
        <div class="calculator-wrapper">
           

            <div class="calculator-header">
                <div class="badge"><i class="fas fa-car"></i><span>Car Loan EMI Calculator</span></div>
                <h1>Calculate Your Car Loan EMI</h1>
                <p>Plan your new car or used car loan repayment with accurate EMI calculations</p>
            </div>

            <div class="calculator-card">
                <div class="double-group">
                    <div class="form-group">
                        <label><i class="fas fa-car"></i> CAR PRICE (On-Road)</label>
                        <div class="input-prefix">
                            <span class="prefix-icon">₹</span>
                            <input type="number" id="carPrice" placeholder="Enter on-road car price" value="800000" step="50000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-hand-holding-usd"></i> DOWN PAYMENT</label>
                        <div class="input-prefix">
                            <span class="prefix-icon">₹</span>
                            <input type="number" id="downPayment" placeholder="Down payment amount" value="100000" step="10000">
                        </div>
                    </div>
                </div>

                <div class="info-section" style="margin-top: 0; margin-bottom: 20px; padding: 12px;">
                    <div class="info-grid" style="grid-template-columns: repeat(3, 1fr);">
                        <div class="info-item"><div class="label">Loan Amount</div><div class="value" id="loanAmountDisplay">₹7,00,000</div></div>
                        <div class="info-item"><div class="label">LTV Ratio</div><div class="value" id="ltvDisplay">87.5%</div></div>
                        <div class="info-item"><div class="label">Max Eligible LTV</div><div class="value" id="maxLtvDisplay">90%</div></div>
                    </div>
                </div>

                <div class="double-group">
                    <div class="form-group">
                        <label><i class="fas fa-percent"></i> INTEREST RATE (% P.A.)</label>
                        <input type="number" id="interestRate" placeholder="Interest rate" value="9.5" step="0.25">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> LOAN TENURE</label>
                        <div style="display: flex; gap: 8px;">
                            <input type="number" id="tenureValue" placeholder="Value" value="5" step="1" style="width: 60%;">
                            <select id="tenureType" style="width: 40%;">
                                <option value="years" selected>Years</option>
                                <option value="months">Months</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="double-group">
                    <div class="form-group">
                        <label><i class="fas fa-tag"></i> CAR TYPE</label>
                        <select id="carType">
                            <option value="new" selected>New Car</option>
                            <option value="used">Used Car / Pre-owned</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-gas-pump"></i> FUEL TYPE</label>
                        <select id="fuelType">
                            <option value="petrol" selected>Petrol</option>
                            <option value="diesel">Diesel</option>
                            <option value="electric">Electric (EV)</option>
                            <option value="cng">CNG</option>
                        </select>
                    </div>
                </div>

                <div class="result-grid">
                    <div class="result-card"><i class="fas fa-chart-pie"></i><div class="result-label">MONTHLY EMI</div><div class="result-value" id="monthlyEmi">₹0</div></div>
                    <div class="result-card"><i class="fas fa-coins"></i><div class="result-label">TOTAL INTEREST</div><div class="result-value" id="totalInterest">₹0</div></div>
                    <div class="result-card"><i class="fas fa-wallet"></i><div class="result-label">TOTAL PAYMENT</div><div class="result-value" id="totalPayment">₹0</div></div>
                    <div class="result-card"><i class="fas fa-rupee-sign"></i><div class="result-label">EMI per Lakh</div><div class="result-value" id="emiPerLakh">₹0</div></div>
                </div>

                <div class="info-section">
                    <h4><i class="fas fa-info-circle"></i> Car Loan Summary</h4>
                    <div class="info-grid">
                        <div class="info-item"><div class="label">Principal Amount</div><div class="value" id="principalDisplay">₹0</div></div>
                        <div class="info-item"><div class="label">Interest Rate</div><div class="value" id="rateDisplay">9.5%</div></div>
                        <div class="info-item"><div class="label">Loan Tenure</div><div class="value" id="tenureDisplay">5 Years</div></div>
                        <div class="info-item"><div class="label">Annual EMI Outflow</div><div class="value" id="annualEmi">₹0</div></div>
                    </div>
                </div>

                <div class="offers-section">
                    <h4><i class="fas fa-university"></i> Best Car Loan Offers</h4>
                    <div class="offers-grid" id="offersGrid"></div>
                </div>

                <div class="amortization-section" id="amortizationSection" style="display: none;">
                    <h4><i class="fas fa-table"></i> Amortization Schedule</h4>
                    <div class="table-wrapper">
                        <table class="amortization-table" id="amortizationTable">
                            <thead><tr><th>Month</th><th>EMI (₹)</th><th>Principal (₹)</th><th>Interest (₹)</th><th>Balance (₹)</th></tr></thead>
                            <tbody id="amortizationBody"></tbody>
                        </table>
                    </div>
                    <div class="view-toggle"><button id="toggleAmortizationBtn">Hide Schedule</button></div>
                </div>
                <div class="view-toggle" style="margin-top: 16px;"><button id="showAmortizationBtn">View Amortization Schedule</button></div>
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

    // ===== CALCULATOR FUNCTIONS =====
    function formatIndianCurrency(amount) {
        if (isNaN(amount) || amount <= 0) return '₹0';
        return new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(amount);
    }

    function calculateEMI(principal, annualRate, months) {
        if (principal <= 0 || months <= 0) return 0;
        var monthlyRate = annualRate / 12 / 100;
        if (monthlyRate === 0) return principal / months;
        return principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
    }

    function generateAmortization(principal, annualRate, months, emi) {
        var monthlyRate = annualRate / 12 / 100;
        var balance = principal, schedule = [];
        for (var i = 1; i <= months; i++) {
            var interest = balance * monthlyRate;
            var principalPaid = emi - interest;
            if (principalPaid > balance) principalPaid = balance;
            balance -= principalPaid;
            if (balance < 0) balance = 0;
            schedule.push({ month: i, emi: emi, principal: principalPaid, interest: interest, balance: balance });
            if (balance <= 0) break;
        }
        return schedule;
    }

    var carPriceInput = document.getElementById('carPrice');
    var downPaymentInput = document.getElementById('downPayment');
    var interestRateInput = document.getElementById('interestRate');
    var tenureValueInput = document.getElementById('tenureValue');
    var tenureTypeSelect = document.getElementById('tenureType');
    var carTypeSelect = document.getElementById('carType');
    var showAmortization = false;

    function calculateAll() {
        var carPrice = parseFloat(carPriceInput.value) || 0;
        var downPayment = parseFloat(downPaymentInput.value) || 0;
        var annualRate = parseFloat(interestRateInput.value) || 0;
        var tenureValue = parseFloat(tenureValueInput.value) || 1;
        var tenureType = tenureTypeSelect.value;
        var carType = carTypeSelect.value;
        
        var principal = Math.max(0, carPrice - downPayment);
        var maxLtv = carType === 'new' ? 90 : 80;
        var maxLoan = carPrice * (maxLtv / 100);
        var ltv = carPrice > 0 ? (principal / carPrice) * 100 : 0;
        
        if (principal > maxLoan) {
            principal = maxLoan;
            ltv = maxLtv;
        }
        
        var months = tenureType === 'years' ? tenureValue * 12 : tenureValue;
        var emi = calculateEMI(principal, annualRate, months);
        var totalPayment = emi * months;
        var totalInterest = totalPayment - principal;
        var emiPerLakh = calculateEMI(100000, annualRate, months);
        
        document.getElementById('loanAmountDisplay').innerText = formatIndianCurrency(principal);
        document.getElementById('ltvDisplay').innerText = ltv.toFixed(1) + '%';
        document.getElementById('maxLtvDisplay').innerText = maxLtv + '%';
        document.getElementById('monthlyEmi').innerText = formatIndianCurrency(emi);
        document.getElementById('totalInterest').innerText = formatIndianCurrency(totalInterest);
        document.getElementById('totalPayment').innerText = formatIndianCurrency(totalPayment);
        document.getElementById('emiPerLakh').innerText = formatIndianCurrency(emiPerLakh);
        document.getElementById('principalDisplay').innerText = formatIndianCurrency(principal);
        document.getElementById('rateDisplay').innerText = annualRate + '%';
        var tenureText = tenureType === 'years' ? tenureValue + ' Years' : tenureValue + ' Months';
        document.getElementById('tenureDisplay').innerText = tenureText;
        document.getElementById('annualEmi').innerText = formatIndianCurrency(emi * 12);
        
        var offers = carType === 'new' ? 
            [{ bank: 'SBI', rate: '8.70%' }, { bank: 'HDFC Bank', rate: '8.90%' }, { bank: 'ICICI Bank', rate: '9.00%' }, { bank: 'Axis Bank', rate: '9.10%' }] :
            [{ bank: 'SBI', rate: '10.50%' }, { bank: 'HDFC Bank', rate: '10.75%' }, { bank: 'ICICI Bank', rate: '11.00%' }];
        var offersHTML = '';
        for (var i = 0; i < offers.length; i++) {
            offersHTML += '<div class="offer-card"><div class="bank">' + offers[i].bank + '</div><div class="rate">' + offers[i].rate + '</div></div>';
        }
        document.getElementById('offersGrid').innerHTML = offersHTML;
        
        if (showAmortization && principal > 0 && months > 0 && emi > 0) {
            var schedule = generateAmortization(principal, annualRate, months, emi);
            var tbody = document.getElementById('amortizationBody');
            tbody.innerHTML = '';
            for (var j = 0; j < schedule.length; j++) {
                var item = schedule[j];
                tbody.innerHTML += '<tr><td>' + item.month + '</td><td>' + formatIndianCurrency(item.emi) + '</td><td>' + formatIndianCurrency(item.principal) + '</td><td>' + formatIndianCurrency(item.interest) + '</td><td>' + formatIndianCurrency(item.balance) + '</td></tr>';
            }
        }
    }

    function toggleAmortization() {
        showAmortization = !showAmortization;
        var section = document.getElementById('amortizationSection');
        var showBtn = document.getElementById('showAmortizationBtn');
        if (showAmortization) {
            section.style.display = 'block';
            showBtn.innerText = 'Hide Amortization Schedule';
        } else {
            section.style.display = 'none';
            showBtn.innerText = 'View Amortization Schedule';
        }
        calculateAll();
    }

    carPriceInput.addEventListener('input', calculateAll);
    downPaymentInput.addEventListener('input', calculateAll);
    interestRateInput.addEventListener('input', calculateAll);
    tenureValueInput.addEventListener('input', calculateAll);
    tenureTypeSelect.addEventListener('change', calculateAll);
    carTypeSelect.addEventListener('change', calculateAll);
    document.getElementById('fuelType').addEventListener('change', calculateAll);
    document.getElementById('showAmortizationBtn').addEventListener('click', toggleAmortization);
    var toggleBtn = document.getElementById('toggleAmortizationBtn');
    if (toggleBtn) toggleBtn.addEventListener('click', toggleAmortization);
    
    calculateAll();
</script>
</body>
<?php
include('../../../includes/footer-nav.php');
?>
</html>