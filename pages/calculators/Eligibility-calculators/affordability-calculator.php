<?php
// FILE: affordability-calculator.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Affordability Calculator – Know Your Loan Eligibility</title>
    <meta name="description" content="Enter your income, expenses, interest rate and tenure to calculate your loan affordability instantly.">
    <meta name="keywords" content="loan affordability calculator, EMI calculator, loan eligibility, LOGINTOLOANS">
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
            --shadow-lg: 0 24px 64px rgba(6,15,34,0.14);
            --radius-md: 20px;
            --radius-lg: 32px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #F8FAFF 0%, #F1F5F9 100%);
            color: var(--text-body);
            min-height: 100vh;
        }

        .calculator-wrapper {
            max-width: 900px;
            margin: 120px auto 60px;
            padding: 0 24px;
        }

        .calculator-header {
            text-align: center;
            margin-bottom: 40px;
        }
        .calculator-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 900;
            background: linear-gradient(135deg, var(--navy) 0%, var(--orange) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 12px;
        }
        .calculator-header p {
            font-size: 16px;
            color: var(--text-muted);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            padding: 6px 18px;
            border-radius: 100px;
            margin-bottom: 20px;
            font-size: 12px;
            font-weight: 600;
            color: var(--orange-dark);
        }
        .badge i { color: var(--orange); }

        .calculator-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 40px;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(249,115,22,0.1);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 28px;
        }
        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 10px;
        }
        .form-group label i {
            color: var(--orange);
            font-size: 14px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 16px 18px;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            font-size: 16px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            color: var(--navy);
            background: #FAFBFD;
            transition: all 0.25s ease;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 4px rgba(249,115,22,0.1);
        }
        .form-group input::placeholder {
            color: var(--text-muted);
            font-weight: 400;
            font-size: 14px;
        }

        .input-prefix {
            position: relative;
        }
        .input-prefix input {
            padding-left: 40px;
        }
        .prefix-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--orange);
            font-weight: 600;
            font-size: 16px;
        }

        .double-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }

        /* Results Grid */
        .result-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin: 32px 0 24px;
            padding-top: 24px;
            border-top: 2px dashed var(--border);
        }
        .result-card {
            background: linear-gradient(135deg, #F8FAFF, #F1F5F9);
            border-radius: 20px;
            padding: 20px 16px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .result-card i {
            font-size: 28px;
            color: var(--orange);
            margin-bottom: 8px;
            display: block;
        }
        .result-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 6px;
        }
        .result-value {
            font-size: 24px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }

        .eligibility-badge {
            background: var(--navy);
            color: white;
            border-radius: 40px;
            padding: 14px 20px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            margin-top: 16px;
        }
        .eligibility-badge.high { background: linear-gradient(135deg, var(--success), #059669); }
        .eligibility-badge.medium { background: linear-gradient(135deg, var(--orange), var(--orange-dark)); }
        .eligibility-badge.low { background: linear-gradient(135deg, #6B7280, #4B5563); }
        .eligibility-badge i { margin-right: 8px; }

        /* Formula Section */
        .formula-section {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 32px;
            margin-top: 40px;
            border: 1px solid var(--border);
        }
        .formula-section h3 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .formula-section h3 i { color: var(--orange); }
        .formula-box {
            background: #F8FAFF;
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 24px;
            border-left: 4px solid var(--orange);
        }
        .formula-box p {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 12px;
        }
        .formula-box .formula-desc {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
        }
        .formula-note {
            background: #FEF3E8;
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 13px;
            color: var(--orange-dark);
            margin-top: 16px;
        }
        .formula-note i { margin-right: 8px; }

        @media (max-width: 640px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .calculator-card { padding: 24px; }
            .double-group { grid-template-columns: 1fr; gap: 16px; margin-bottom: 20px; }
            .result-grid { grid-template-columns: 1fr; }
            .calculator-header h1 { font-size: 32px; }
        }
    </style>
</head>
<body>


<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-calculator"></i>
            <span>Smart Affordability Tool</span>
        </div>
        <h1>How Much Can You Borrow?</h1>
        <p>Enter your income, expenses, and loan preferences to calculate your eligibility instantly</p>
    </div>

    <div class="calculator-card">
        <!-- Monthly Income -->
        <div class="form-group">
            <label><i class="fas fa-rupee-sign"></i> MONTHLY INCOME</label>
            <div class="input-prefix">
                <span class="prefix-icon">₹</span>
                <input type="number" id="monthlyIncome" placeholder="Enter your monthly income" value="75000" step="1000">
            </div>
        </div>

        <!-- Monthly Expenses -->
        <div class="form-group">
            <label><i class="fas fa-credit-card"></i> MONTHLY EXPENSES</label>
            <div class="input-prefix">
                <span class="prefix-icon">₹</span>
                <input type="number" id="monthlyExpenses" placeholder="Enter your monthly expenses" value="30000" step="1000">
            </div>
        </div>

        <!-- Interest Rate & Tenure -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-percent"></i> INTEREST RATE (% P.A.)</label>
                <input type="number" id="interestRate" placeholder="Interest rate per annum" value="10.5" step="0.25">
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> LOAN TENURE (YEARS)</label>
                <select id="loanTenure">
                    <option value="1">1 Year</option>
                    <option value="2">2 Years</option>
                    <option value="3">3 Years</option>
                    <option value="4">4 Years</option>
                    <option value="5" selected>5 Years</option>
                    <option value="6">6 Years</option>
                    <option value="7">7 Years</option>
                    <option value="8">8 Years</option>
                    <option value="9">9 Years</option>
                    <option value="10">10 Years</option>
                    <option value="12">12 Years</option>
                    <option value="15">15 Years</option>
                    <option value="20">20 Years</option>
                </select>
            </div>
        </div>

        <!-- Results -->
        <div class="result-grid">
            <div class="result-card">
                <i class="fas fa-hand-holding-usd"></i>
                <div class="result-label">ELIGIBLE LOAN AMOUNT</div>
                <div class="result-value" id="eligibleLoan">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-chart-pie"></i>
                <div class="result-label">MONTHLY EMI</div>
                <div class="result-value" id="emiAmount">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-coins"></i>
                <div class="result-label">TOTAL INTEREST PAYABLE</div>
                <div class="result-value" id="totalInterest">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-wallet"></i>
                <div class="result-label">TOTAL PAYMENT</div>
                <div class="result-value" id="totalPayment">₹0</div>
            </div>
        </div>

        <!-- Eligibility Message -->
        <div id="eligibilityMessage" class="eligibility-badge medium">
            <i class="fas fa-chart-line"></i> Enter values to see your eligibility
        </div>
    </div>

    <!-- Formula Explanation Section -->
    <div class="formula-section">
        <h3>
            <i class="fas fa-square-root-alt"></i>
            Understanding the Calculation
        </h3>
        
        <div class="formula-box">
            <p>EMI Formula: EMI = P × r × (1+r)ⁿ / ((1+r)ⁿ - 1)</p>
            <div class="formula-desc">
                Where:<br>
                <strong>P</strong> = Principal Loan Amount<br>
                <strong>r</strong> = Monthly Interest Rate (Annual Rate ÷ 12 ÷ 100)<br>
                <strong>n</strong> = Loan Tenure in Months
            </div>
        </div>

        <div class="formula-box">
            <p>Loan Affordability Formula: P = EMI × ((1+r)ⁿ - 1) / (r × (1+r)ⁿ)</p>
            <div class="formula-desc">
                Where:<br>
                <strong>EMI</strong> = Maximum Affordable EMI (45% of monthly income)<br>
                <strong>r</strong> = Monthly Interest Rate<br>
                <strong>n</strong> = Loan Tenure in Months
            </div>
        </div>

        <div class="formula-note">
            <i class="fas fa-lightbulb"></i>
            <strong>How we calculate your eligibility:</strong> Banks typically allow up to 40-50% of your monthly income towards EMI payments (FOIR - Fixed Obligation to Income Ratio). We use 45% as the standard ratio. Your eligible loan amount is then calculated based on this maximum EMI, the interest rate, and the chosen tenure.
        </div>

        <div class="formula-note" style="background: #ECFDF5; color: #059669;">
            <i class="fas fa-chart-line"></i>
            <strong>Example:</strong> If your monthly income is ₹75,000 and expenses are ₹30,000, your disposable income is ₹45,000. With a 45% FOIR, your maximum EMI is ₹33,750. At 10.5% interest for 5 years, your eligible loan amount would be approximately ₹15.7 Lakhs.
        </div>
    </div>
</div>

<?php include('../../../includes/footer.php'); ?>

<script>
    // DOM Elements
    const incomeInput = document.getElementById('monthlyIncome');
    const expenseInput = document.getElementById('monthlyExpenses');
    const rateInput = document.getElementById('interestRate');
    const tenureSelect = document.getElementById('loanTenure');
    
    const eligibleLoanSpan = document.getElementById('eligibleLoan');
    const emiAmountSpan = document.getElementById('emiAmount');
    const totalInterestSpan = document.getElementById('totalInterest');
    const totalPaymentSpan = document.getElementById('totalPayment');
    const eligibilityMessageDiv = document.getElementById('eligibilityMessage');

    // Format Indian Rupees
    function formatIndianCurrency(amount) {
        if (isNaN(amount) || amount <= 0) return '₹0';
        const formatter = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
        return formatter.format(amount);
    }

    // Calculate EMI for a given principal
    function calculateEMI(principal, annualRate, months) {
        if (principal <= 0 || months <= 0) return 0;
        const monthlyRate = annualRate / 12 / 100;
        if (monthlyRate === 0) return principal / months;
        const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
        return emi;
    }

    // Calculate principal from EMI
    function calculatePrincipalFromEMI(emi, annualRate, months) {
        if (emi <= 0 || months <= 0) return 0;
        const monthlyRate = annualRate / 12 / 100;
        if (monthlyRate === 0) return emi * months;
        const principal = emi * (Math.pow(1 + monthlyRate, months) - 1) / (monthlyRate * Math.pow(1 + monthlyRate, months));
        return principal;
    }

    // Main calculation function
    function calculateAffordability() {
        // Get values - allow any positive number
        let monthlyIncome = parseFloat(incomeInput.value);
        let monthlyExpenses = parseFloat(expenseInput.value);
        let annualInterest = parseFloat(rateInput.value);
        let tenureYears = parseFloat(tenureSelect.value);
        
        // Validate inputs - if empty or invalid, set to 0
        if (isNaN(monthlyIncome)) monthlyIncome = 0;
        if (isNaN(monthlyExpenses)) monthlyExpenses = 0;
        if (isNaN(annualInterest)) annualInterest = 0;
        if (isNaN(tenureYears)) tenureYears = 5;
        
        const months = tenureYears * 12;
        
        // FOIR Ratio (45% of monthly income is typical max EMI)
        const FOIR_RATIO = 0.45;
        let maxEMI = monthlyIncome * FOIR_RATIO;
        
        if (maxEMI < 0) maxEMI = 0;
        
        // Calculate eligible loan amount based on max EMI
        let eligibleLoan = calculatePrincipalFromEMI(maxEMI, annualInterest, months);
        
        // Round to nearest 1000 for cleaner display
        if (eligibleLoan > 0) {
            eligibleLoan = Math.floor(eligibleLoan / 1000) * 1000;
        }
        if (eligibleLoan < 0) eligibleLoan = 0;
        
        // Calculate EMI for the eligible loan
        let calculatedEMI = calculateEMI(eligibleLoan, annualInterest, months);
        
        const totalPayment = calculatedEMI * months;
        const totalInterest = totalPayment - eligibleLoan;
        
        // Update UI
        eligibleLoanSpan.innerText = formatIndianCurrency(eligibleLoan);
        emiAmountSpan.innerText = formatIndianCurrency(calculatedEMI);
        totalInterestSpan.innerText = formatIndianCurrency(totalInterest);
        totalPaymentSpan.innerText = formatIndianCurrency(totalPayment);
        
        // Update eligibility message based on EMI to income ratio
        let emiToIncomeRatio = 0;
        if (monthlyIncome > 0) {
            emiToIncomeRatio = (calculatedEMI / monthlyIncome) * 100;
        }
        
        eligibilityMessageDiv.classList.remove('high', 'medium', 'low');
        
        if (monthlyIncome === 0) {
            eligibilityMessageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Please enter your monthly income to calculate eligibility';
            eligibilityMessageDiv.classList.add('low');
        } else if (eligibleLoan === 0 || monthlyIncome <= monthlyExpenses) {
            eligibilityMessageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Low Affordability • Consider reducing expenses or increasing income';
            eligibilityMessageDiv.classList.add('low');
        } else if (emiToIncomeRatio <= 35) {
            eligibilityMessageDiv.innerHTML = '<i class="fas fa-check-circle"></i> Excellent! You can comfortably afford this loan. EMI is ' + emiToIncomeRatio.toFixed(1) + '% of your income.';
            eligibilityMessageDiv.classList.add('high');
        } else if (emiToIncomeRatio <= 50) {
            eligibilityMessageDiv.innerHTML = '<i class="fas fa-chart-line"></i> Good! You may qualify. EMI is ' + emiToIncomeRatio.toFixed(1) + '% of your income.';
            eligibilityMessageDiv.classList.add('medium');
        } else {
            eligibilityMessageDiv.innerHTML = '<i class="fas fa-chart-line"></i> Moderate • Consider longer tenure or lower loan amount. EMI is ' + emiToIncomeRatio.toFixed(1) + '% of income.';
            eligibilityMessageDiv.classList.add('low');
        }
    }
    
    // Event Listeners - calculate on any input change
    incomeInput.addEventListener('input', calculateAffordability);
    expenseInput.addEventListener('input', calculateAffordability);
    rateInput.addEventListener('input', calculateAffordability);
    tenureSelect.addEventListener('change', calculateAffordability);
    
    // Initial calculation
    calculateAffordability();
</script>
</body>
</html>