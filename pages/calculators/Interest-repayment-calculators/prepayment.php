<?php
// FILE: prepayment.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Prepayment Calculator – Save on Loan Interest</title>
    <meta name="description" content="Calculate how much you can save by making prepayments on your loan. Compare regular EMI vs prepayment and see interest savings and tenure reduction.">
    <meta name="keywords" content="loan prepayment calculator, prepayment savings, loan early payment, reduce loan interest, LOGINTOLOANS">
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

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #F8FAFF 0%, #F1F5F9 100%);
            color: var(--text-body);
            min-height: 100vh;
        }

        .calculator-wrapper {
            max-width: 1300px;
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

        /* Input Card */
        .input-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(249,115,22,0.1);
        }

        .form-group {
            margin-bottom: 24px;
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
        }

        .double-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .triple-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        /* Comparison Cards */
        .comparison-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .loan-card {
            background: var(--white);
            border-radius: 24px;
            padding: 28px;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        .loan-card.without-prepay {
            border-top: 4px solid var(--danger);
        }
        .loan-card.with-prepay {
            border-top: 4px solid var(--success);
            background: linear-gradient(135deg, var(--white), #F8FAFF);
        }
        .loan-card h3 {
            font-size: 20px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .savings-badge {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }
        .detail-row .label {
            color: var(--text-muted);
        }
        .detail-row .value {
            font-weight: 700;
            color: var(--navy);
        }
        .highlight-value {
            font-size: 24px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--orange);
        }

        /* Savings Section */
        .savings-section {
            background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }
        .savings-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 15px;
        }
        .savings-amount {
            font-size: 48px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--success);
            margin: 15px 0;
        }
        .savings-breakdown {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .breakdown-item {
            text-align: center;
        }
        .breakdown-item .label {
            font-size: 12px;
            color: var(--text-muted);
        }
        .breakdown-item .value {
            font-size: 20px;
            font-weight: 700;
            color: var(--navy);
        }

        /* Strategy Options */
        .strategy-section {
            background: #F8FAFF;
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .strategy-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .strategy-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .strategy-card {
            background: var(--white);
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid var(--border);
        }
        .strategy-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }
        .strategy-card.active {
            border-color: var(--orange);
            background: #FFF9F5;
        }
        .strategy-card i {
            font-size: 32px;
            color: var(--orange);
            margin-bottom: 10px;
        }
        .strategy-card h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 5px;
        }
        .strategy-card p {
            font-size: 12px;
            color: var(--text-muted);
        }

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
        .formula-box {
            background: #F8FAFF;
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 24px;
            border-left: 4px solid var(--orange);
        }
        .formula-box p {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 12px;
        }

        @media (max-width: 900px) {
            .comparison-grid { grid-template-columns: 1fr; }
            .double-group, .triple-group { grid-template-columns: 1fr; gap: 16px; }
            .strategy-options { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .input-card { padding: 24px; }
            .calculator-header h1 { font-size: 32px; }
            .savings-amount { font-size: 32px; }
        }
    </style>
</head>
<body>



<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-rupee-sign"></i>
            <span>Prepayment Calculator</span>
        </div>
        <h1>Save Thousands with Loan Prepayment</h1>
        <p>See how extra payments can reduce your interest burden and loan tenure</p>
    </div>

    <!-- Input Section -->
    <div class="input-card">
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> LOAN AMOUNT (₹)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">₹</span>
                    <input type="number" id="loanAmount" value="1000000" step="50000">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-percent"></i> INTEREST RATE (% P.A.)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">%</span>
                    <input type="number" id="interestRate" value="10.5" step="0.25">
                </div>
            </div>
        </div>
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> LOAN TENURE (Years)</label>
                <input type="number" id="loanTenure" value="5" step="1">
            </div>
            <div class="form-group">
                <label><i class="fas fa-chart-line"></i> PREPAYMENT AMOUNT (₹)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">₹</span>
                    <input type="number" id="prepaymentAmount" value="100000" step="10000">
                </div>
            </div>
        </div>
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-clock"></i> PREPAYMENT TIMING (Year)</label>
                <input type="number" id="prepaymentYear" value="1" step="1" min="1">
            </div>
            <div class="form-group">
                <label><i class="fas fa-chart-line"></i> PREPAYMENT FREQUENCY</label>
                <select id="prepaymentType">
                    <option value="one-time">One Time Prepayment</option>
                    <option value="monthly">Monthly Extra Payment</option>
                    <option value="yearly">Yearly Extra Payment</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Strategy Options -->
    <div class="strategy-section">
        <h3><i class="fas fa-chart-line"></i> Prepayment Strategy</h3>
        <div class="strategy-options">
            <div class="strategy-card" data-strategy="reduce-tenure">
                <i class="fas fa-hourglass-half"></i>
                <h4>Reduce Tenure</h4>
                <p>Keep EMI same, reduce loan duration</p>
            </div>
            <div class="strategy-card active" data-strategy="reduce-emi">
                <i class="fas fa-chart-pie"></i>
                <h4>Reduce EMI</h4>
                <p>Keep tenure same, reduce monthly payment</p>
            </div>
            <div class="strategy-card" data-strategy="compare">
                <i class="fas fa-balance-scale"></i>
                <h4>Compare Both</h4>
                <p>See both options side by side</p>
            </div>
        </div>
    </div>

    <!-- Comparison Cards -->
    <div class="comparison-grid" id="comparisonGrid">
        <!-- Without Prepayment Card -->
        <div class="loan-card without-prepay" id="withoutPrepayCard">
            <h3><i class="fas fa-chart-line"></i> Without Prepayment</h3>
            <div class="detail-row"><span class="label">Monthly EMI</span><span class="value" id="normalEmi">₹0</span></div>
            <div class="detail-row"><span class="label">Total Interest</span><span class="value" id="normalInterest">₹0</span></div>
            <div class="detail-row"><span class="label">Total Payment</span><span class="value" id="normalTotal">₹0</span></div>
            <div class="detail-row"><span class="label">Loan Tenure</span><span class="value" id="normalTenure">0 Years</span></div>
        </div>

        <!-- With Prepayment Card -->
        <div class="loan-card with-prepay" id="withPrepayCard">
            <h3><i class="fas fa-gift"></i> With Prepayment</h3>
            <div class="savings-badge" id="savingsBadge">You Save ₹0</div>
            <div class="detail-row"><span class="label">New Monthly EMI</span><span class="value" id="newEmi">₹0</span></div>
            <div class="detail-row"><span class="label">New Total Interest</span><span class="value" id="newInterest">₹0</span></div>
            <div class="detail-row"><span class="label">New Total Payment</span><span class="value" id="newTotal">₹0</span></div>
            <div class="detail-row"><span class="label">New Loan Tenure</span><span class="value" id="newTenure">0 Years</span></div>
            <div class="detail-row"><span class="label">Tenure Reduction</span><span class="value" id="tenureReduction">0 Months</span></div>
        </div>
    </div>

    <!-- Savings Section -->
    <div class="savings-section" id="savingsSection">
        <h3><i class="fas fa-coins"></i> Total Interest Savings</h3>
        <div class="savings-amount" id="totalSavings">₹0</div>
        <div class="savings-breakdown">
            <div class="breakdown-item">
                <div class="label">Monthly EMI Reduction</div>
                <div class="value" id="emiReduction">₹0</div>
            </div>
            <div class="breakdown-item">
                <div class="label">Tenure Reduction</div>
                <div class="value" id="tenureReductionValue">0 Months</div>
            </div>
            <div class="breakdown-item">
                <div class="label">Prepayment Amount</div>
                <div class="value" id="prepaymentDisplay">₹0</div>
            </div>
        </div>
    </div>

    <!-- Formula Section -->
    <div class="formula-section">
        <h3><i class="fas fa-square-root-alt"></i> How Prepayment Works</h3>
        <div class="formula-box">
            <p>When you make a prepayment, the extra amount goes directly toward reducing your principal balance. This reduces:</p>
            <div class="formula-desc">
                • <strong>Total Interest:</strong> Less principal means less interest accrual<br>
                • <strong>Loan Tenure:</strong> You can finish your loan earlier<br>
                • <strong>Monthly EMI:</strong> You can choose to reduce your monthly payment
            </div>
        </div>
        <div class="formula-box">
            <p>Key Insights:</p>
            <div class="formula-desc">
                • Prepayments made in the early years save the most interest<br>
                • Even small extra payments can save thousands over time<br>
                • Check if your loan has prepayment penalties before making extra payments
            </div>
        </div>
        <div class="formula-note" style="background: #FEF3E8; padding: 16px; border-radius: 12px;">
            <i class="fas fa-lightbulb"></i> <strong>Pro Tip:</strong> A prepayment of just ₹10,000 per year on a ₹10L loan at 10.5% for 5 years can save you over ₹50,000 in interest and reduce tenure by 6-8 months.
        </div>
    </div>
</div>

<?php include('../../../includes/footer.php'); ?>

<script>
    // DOM Elements
    const loanAmountInput = document.getElementById('loanAmount');
    const interestRateInput = document.getElementById('interestRate');
    const loanTenureInput = document.getElementById('loanTenure');
    const prepaymentAmountInput = document.getElementById('prepaymentAmount');
    const prepaymentYearInput = document.getElementById('prepaymentYear');
    const prepaymentTypeSelect = document.getElementById('prepaymentType');
    
    let currentStrategy = 'reduce-emi';

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

    function calculateEMI(principal, annualRate, months) {
        if (principal <= 0 || months <= 0) return 0;
        const monthlyRate = annualRate / 12 / 100;
        if (monthlyRate === 0) return principal / months;
        const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
        return emi;
    }

    function calculateRemainingBalance(principal, annualRate, monthsPassed, emi) {
        let balance = principal;
        const monthlyRate = annualRate / 12 / 100;
        
        for (let i = 1; i <= monthsPassed; i++) {
            let interest = balance * monthlyRate;
            let principalPaid = emi - interest;
            if (principalPaid > balance) principalPaid = balance;
            balance = balance - principalPaid;
            if (balance <= 0) break;
        }
        return Math.max(0, balance);
    }

    function calculateLoanWithPrepayment() {
        let principal = parseFloat(loanAmountInput.value) || 0;
        let annualRate = parseFloat(interestRateInput.value) || 0;
        let tenureYears = parseFloat(loanTenureInput.value) || 1;
        let prepaymentAmount = parseFloat(prepaymentAmountInput.value) || 0;
        let prepaymentYear = parseFloat(prepaymentYearInput.value) || 1;
        
        let months = tenureYears * 12;
        let normalEmi = calculateEMI(principal, annualRate, months);
        let normalTotalPayment = normalEmi * months;
        let normalTotalInterest = normalTotalPayment - principal;
        
        // Calculate remaining balance at prepayment time
        let monthsBeforePrepay = prepaymentYear * 12;
        if (monthsBeforePrepay > months) monthsBeforePrepay = months;
        
        let remainingBalance = calculateRemainingBalance(principal, annualRate, monthsBeforePrepay, normalEmi);
        let newPrincipal = Math.max(0, remainingBalance - prepaymentAmount);
        
        let remainingMonths = months - monthsBeforePrepay;
        if (remainingMonths <= 0) remainingMonths = 1;
        
        let newEmi, newTotalPayment, newTotalInterest, newTenureMonths;
        
        if (currentStrategy === 'reduce-emi') {
            // Keep same tenure, reduce EMI
            newEmi = calculateEMI(newPrincipal, annualRate, remainingMonths);
            newTenureMonths = remainingMonths;
            newTotalPayment = (normalEmi * monthsBeforePrepay) + (newEmi * remainingMonths) + prepaymentAmount;
        } else if (currentStrategy === 'reduce-tenure') {
            // Keep same EMI, reduce tenure
            newEmi = normalEmi;
            // Calculate how many months with same EMI
            let tempMonths = 1;
            let tempEmi = 0;
            while (tempEmi < newEmi && tempMonths < 600) {
                tempEmi = calculateEMI(newPrincipal, annualRate, tempMonths);
                if (tempEmi <= newEmi) tempMonths++;
                else break;
            }
            newTenureMonths = Math.min(tempMonths, remainingMonths);
            newEmi = calculateEMI(newPrincipal, annualRate, newTenureMonths);
            newTotalPayment = (normalEmi * monthsBeforePrepay) + (newEmi * newTenureMonths) + prepaymentAmount;
        } else {
            // Compare both - show reduce tenure as default for comparison
            newEmi = normalEmi;
            let tempMonths = 1;
            let tempEmi = 0;
            while (tempEmi < newEmi && tempMonths < 600) {
                tempEmi = calculateEMI(newPrincipal, annualRate, tempMonths);
                if (tempEmi <= newEmi) tempMonths++;
                else break;
            }
            newTenureMonths = Math.min(tempMonths, remainingMonths);
            newEmi = calculateEMI(newPrincipal, annualRate, newTenureMonths);
            newTotalPayment = (normalEmi * monthsBeforePrepay) + (newEmi * newTenureMonths) + prepaymentAmount;
        }
        
        newTotalInterest = newTotalPayment - principal;
        let totalSavings = normalTotalInterest - newTotalInterest;
        let emiReduction = normalEmi - newEmi;
        let tenureReductionMonths = months - (monthsBeforePrepay + newTenureMonths);
        
        // Update Without Prepayment Card
        document.getElementById('normalEmi').innerHTML = formatIndianCurrency(normalEmi);
        document.getElementById('normalInterest').innerHTML = formatIndianCurrency(normalTotalInterest);
        document.getElementById('normalTotal').innerHTML = formatIndianCurrency(normalTotalPayment);
        document.getElementById('normalTenure').innerHTML = tenureYears + ' Years (' + months + ' months)';
        
        // Update With Prepayment Card
        document.getElementById('newEmi').innerHTML = formatIndianCurrency(newEmi);
        document.getElementById('newInterest').innerHTML = formatIndianCurrency(newTotalInterest);
        document.getElementById('newTotal').innerHTML = formatIndianCurrency(newTotalPayment);
        
        let newTenureYears = (monthsBeforePrepay + newTenureMonths) / 12;
        document.getElementById('newTenure').innerHTML = newTenureYears.toFixed(1) + ' Years (' + (monthsBeforePrepay + newTenureMonths) + ' months)';
        document.getElementById('tenureReduction').innerHTML = tenureReductionMonths + ' Months';
        document.getElementById('savingsBadge').innerHTML = 'You Save ' + formatIndianCurrency(totalSavings);
        
        // Update Savings Section
        document.getElementById('totalSavings').innerHTML = formatIndianCurrency(totalSavings);
        document.getElementById('emiReduction').innerHTML = formatIndianCurrency(emiReduction);
        document.getElementById('tenureReductionValue').innerHTML = tenureReductionMonths + ' Months';
        document.getElementById('prepaymentDisplay').innerHTML = formatIndianCurrency(prepaymentAmount);
        
        // Color coding for savings
        const savingsSection = document.getElementById('savingsSection');
        if (totalSavings > 0) {
            savingsSection.style.background = 'linear-gradient(135deg, #ECFDF5, #D1FAE5)';
        } else {
            savingsSection.style.background = '#FEF3E8';
        }
    }
    
    // Strategy selection
    document.querySelectorAll('.strategy-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.strategy-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            currentStrategy = card.getAttribute('data-strategy');
            calculateLoanWithPrepayment();
        });
    });
    
    // Event Listeners
    loanAmountInput.addEventListener('input', calculateLoanWithPrepayment);
    interestRateInput.addEventListener('input', calculateLoanWithPrepayment);
    loanTenureInput.addEventListener('input', calculateLoanWithPrepayment);
    prepaymentAmountInput.addEventListener('input', calculateLoanWithPrepayment);
    prepaymentYearInput.addEventListener('input', calculateLoanWithPrepayment);
    prepaymentTypeSelect.addEventListener('change', calculateLoanWithPrepayment);
    
    // Initial calculation
    calculateLoanWithPrepayment();
</script>
</body>
</html>