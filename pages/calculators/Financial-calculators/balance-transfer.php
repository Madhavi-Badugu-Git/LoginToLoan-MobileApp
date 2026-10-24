<?php
// FILE: balance-transfer.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Balance Transfer Calculator – Save on Loan Interest</title>
    <meta name="description" content="Calculate how much you can save by transferring your loan balance to a lower interest rate. Compare EMI, total interest, and savings with our balance transfer calculator.">
    <meta name="keywords" content="balance transfer calculator, loan balance transfer, home loan balance transfer, personal loan balance transfer, save on interest, LOGINTOLOANS">
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
            --info: #3B82F6;
            --danger: #EF4444;
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
            max-width: 1200px;
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

        .triple-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 28px;
        }

        /* Current vs New Loan Cards */
        .loan-comparison {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin: 32px 0;
        }
        .loan-card {
            background: #F8FAFF;
            border-radius: 20px;
            padding: 24px;
            border: 1px solid var(--border);
        }
        .loan-card h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .loan-card.current h4 i { color: var(--danger); }
        .loan-card.new h4 i { color: var(--success); }
        .loan-card .loan-details {
            margin-top: 10px;
        }
        .loan-detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }
        .loan-detail-item .label {
            color: var(--text-muted);
        }
        .loan-detail-item .value {
            font-weight: 600;
            color: var(--navy);
        }
        .loan-card.current .value { color: var(--danger); }
        .loan-card.new .value { color: var(--success); }

        /* Savings Section */
        .savings-section {
            background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
            border-radius: 20px;
            padding: 24px;
            margin: 24px 0;
            text-align: center;
            border: 1px solid rgba(16,185,129,0.3);
        }
        .savings-section h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--success);
            margin-bottom: 8px;
        }
        .savings-amount {
            font-size: 36px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--success);
        }
        .savings-breakdown {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        .savings-item {
            text-align: center;
        }
        .savings-item .label {
            font-size: 11px;
            color: var(--text-muted);
        }
        .savings-item .value {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
        }

        /* Results Grid */
        .result-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin: 24px 0;
        }
        .result-card {
            background: linear-gradient(135deg, #F8FAFF, #F1F5F9);
            border-radius: 20px;
            padding: 16px 12px;
            text-align: center;
        }
        .result-card i {
            font-size: 24px;
            color: var(--orange);
            margin-bottom: 8px;
            display: block;
        }
        .result-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 4px;
        }
        .result-value {
            font-size: 18px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }

        /* Transfer Fee Section */
        .transfer-fee {
            background: #FEF3E8;
            border-radius: 16px;
            padding: 20px;
            margin: 20px 0;
        }
        .transfer-fee h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
        }
        .transfer-fee-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        /* Bank Offers Section */
        .offers-section {
            margin-top: 20px;
            padding: 20px;
            background: #F8FAFF;
            border-radius: 20px;
        }
        .offers-section h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .offers-grid {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .offer-card {
            background: var(--white);
            padding: 12px 18px;
            border-radius: 12px;
            text-align: center;
            flex: 1;
            min-width: 140px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .offer-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .offer-card .bank {
            font-weight: 700;
            color: var(--navy);
            font-size: 14px;
        }
        .offer-card .rate {
            color: var(--orange);
            font-weight: 800;
            font-size: 18px;
        }
        .offer-card .savings {
            font-size: 11px;
            color: var(--success);
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
        .formula-note {
            background: #FEF3E8;
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 13px;
            color: var(--orange-dark);
            margin-top: 16px;
        }

        @media (max-width: 900px) {
            .loan-comparison { grid-template-columns: 1fr; }
            .result-grid { grid-template-columns: repeat(2, 1fr); }
            .transfer-fee-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .calculator-card { padding: 24px; }
            .double-group, .triple-group { grid-template-columns: 1fr; gap: 16px; }
            .result-grid { grid-template-columns: 1fr; }
            .calculator-header h1 { font-size: 32px; }
            .savings-breakdown { gap: 20px; }
        }
    </style>
</head>
<body>


<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-exchange-alt"></i>
            <span>Balance Transfer Calculator</span>
        </div>
        <h1>Save Money by Transferring Your Loan</h1>
        <p>Compare your current loan with a new lower interest rate loan and see your savings</p>
    </div>

    <div class="calculator-card">
        <!-- Current Loan Details -->
        <h3 style="font-size: 18px; margin-bottom: 20px; color: var(--navy);">
            <i class="fas fa-chart-line" style="color: var(--orange);"></i> Current Loan Details
        </h3>
        
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> REMAINING PRINCIPAL (₹)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">₹</span>
                    <input type="number" id="remainingPrincipal" placeholder="Remaining loan amount" value="2500000" step="50000">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-percent"></i> CURRENT INTEREST RATE (% P.A.)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">%</span>
                    <input type="number" id="currentRate" placeholder="Current interest rate" value="11.5" step="0.25">
                </div>
            </div>
        </div>

        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> REMAINING TENURE (Years)</label>
                <input type="number" id="remainingTenure" placeholder="Remaining years" value="12" step="1">
            </div>
            <div class="form-group">
                <label><i class="fas fa-chart-pie"></i> CURRENT MONTHLY EMI</label>
                <div class="input-prefix">
                    <span class="prefix-icon">₹</span>
                    <input type="number" id="currentEmi" placeholder="Current EMI (auto-calculated)" readonly style="background: #F1F5F9;">
                </div>
            </div>
        </div>

        <!-- New Loan Details -->
        <h3 style="font-size: 18px; margin: 30px 0 20px; color: var(--navy);">
            <i class="fas fa-hand-holding-usd" style="color: var(--success);"></i> New Loan Offer Details
        </h3>

        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-percent"></i> NEW INTEREST RATE (% P.A.)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">%</span>
                    <input type="number" id="newRate" placeholder="New interest rate" value="8.75" step="0.25">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> NEW LOAN TENURE (Years)</label>
                <input type="number" id="newTenure" placeholder="New tenure" value="12" step="1">
            </div>
        </div>

        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-file-invoice"></i> TRANSFER / PROCESSING FEE (%)</label>
                <input type="number" id="transferFee" placeholder="Transfer fee percentage" value="1" step="0.5">
            </div>
            <div class="form-group">
                <label><i class="fas fa-gavel"></i> PREPAYMENT PENALTY (%)</label>
                <input type="number" id="prepaymentPenalty" placeholder="Prepayment penalty" value="0" step="0.5">
            </div>
        </div>

        <!-- Transfer Fee Calculation -->
        <div class="transfer-fee">
            <h4><i class="fas fa-calculator"></i> Transfer Cost Breakdown</h4>
            <div class="transfer-fee-grid">
                <div class="loan-detail-item">
                    <span class="label">Transfer Fee Amount:</span>
                    <span class="value" id="transferFeeAmount">₹0</span>
                </div>
                <div class="loan-detail-item">
                    <span class="label">Prepayment Penalty:</span>
                    <span class="value" id="penaltyAmount">₹0</span>
                </div>
                <div class="loan-detail-item">
                    <span class="label">Total Transfer Cost:</span>
                    <span class="value" id="totalTransferCost">₹0</span>
                </div>
                <div class="loan-detail-item">
                    <span class="label">Break-even Period:</span>
                    <span class="value" id="breakEvenPeriod">0 months</span>
                </div>
            </div>
        </div>

        <!-- Loan Comparison -->
        <div class="loan-comparison">
            <div class="loan-card current">
                <h4><i class="fas fa-exclamation-triangle"></i> Current Loan</h4>
                <div class="loan-details">
                    <div class="loan-detail-item"><span class="label">Monthly EMI</span><span class="value" id="currentEmiDisplay">₹0</span></div>
                    <div class="loan-detail-item"><span class="label">Total Interest Remaining</span><span class="value" id="currentInterest">₹0</span></div>
                    <div class="loan-detail-item"><span class="label">Total Payment Remaining</span><span class="value" id="currentTotal">₹0</span></div>
                </div>
            </div>
            <div class="loan-card new">
                <h4><i class="fas fa-check-circle"></i> New Loan (After Transfer)</h4>
                <div class="loan-details">
                    <div class="loan-detail-item"><span class="label">New Monthly EMI</span><span class="value" id="newEmiDisplay">₹0</span></div>
                    <div class="loan-detail-item"><span class="label">Total Interest Payable</span><span class="value" id="newInterest">₹0</span></div>
                    <div class="loan-detail-item"><span class="label">Total Payment</span><span class="value" id="newTotal">₹0</span></div>
                </div>
            </div>
        </div>

        <!-- Savings Section -->
        <div class="savings-section" id="savingsSection">
            <h4><i class="fas fa-gift"></i> YOUR TOTAL SAVINGS</h4>
            <div class="savings-amount" id="totalSavings">₹0</div>
            <div class="savings-breakdown">
                <div class="savings-item">
                    <div class="label">Interest Savings</div>
                    <div class="value" id="interestSavings">₹0</div>
                </div>
                <div class="savings-item">
                    <div class="label">Monthly EMI Reduction</div>
                    <div class="value" id="emiReduction">₹0</div>
                </div>
                <div class="savings-item">
                    <div class="label">Net Savings (After Costs)</div>
                    <div class="value" id="netSavings">₹0</div>
                </div>
            </div>
        </div>

        <!-- Quick Results -->
        <div class="result-grid">
            <div class="result-card">
                <i class="fas fa-chart-pie"></i>
                <div class="result-label">EMI Reduction/Month</div>
                <div class="result-value" id="emiReductionValue">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-hourglass-half"></i>
                <div class="result-label">Tenor Reduction</div>
                <div class="result-value" id="tenorReduction">0 Years</div>
            </div>
            <div class="result-card">
                <i class="fas fa-percent"></i>
                <div class="result-label">Rate Reduction</div>
                <div class="result-value" id="rateReduction">0%</div>
            </div>
            <div class="result-card">
                <i class="fas fa-chart-line"></i>
                <div class="result-label">Break-even Months</div>
                <div class="result-value" id="breakEvenMonths">0</div>
            </div>
        </div>

        <!-- Bank Offers Section -->
        <div class="offers-section">
            <h4><i class="fas fa-university"></i> Best Balance Transfer Offers</h4>
            <div class="offers-grid" id="offersGrid">
                <!-- Dynamically populated -->
            </div>
        </div>
    </div>

    <!-- Formula Explanation Section -->
    <div class="formula-section">
        <h3>
            <i class="fas fa-square-root-alt"></i>
            Balance Transfer Calculation Explained
        </h3>
        
        <div class="formula-box">
            <p>EMI Formula: EMI = P × r × (1+r)ⁿ / ((1+r)ⁿ - 1)</p>
            <div class="formula-desc">
                Where:<br>
                <strong>P</strong> = Remaining Principal<br>
                <strong>r</strong> = Monthly Interest Rate<br>
                <strong>n</strong> = Remaining Months
            </div>
        </div>

        <div class="formula-box">
            <p>Total Savings = (Current Total Payment - New Total Payment) - Transfer Costs</p>
            <div class="formula-desc">
                Transfer costs include processing fee and prepayment penalty (if any).<br>
                Break-even period = Transfer Cost ÷ Monthly EMI Savings
            </div>
        </div>

        <div class="formula-note">
            <i class="fas fa-lightbulb"></i>
            <strong>When to Consider Balance Transfer:</strong><br>
            • If new interest rate is at least 0.5-1% lower than current rate<br>
            • If remaining tenure is more than 2-3 years<br>
            • If transfer costs are recovered within 12-18 months<br>
            • If you have a good credit score (750+) for better offers
        </div>

        <div class="formula-note" style="background: #ECFDF5; color: #059669; margin-top: 12px;">
            <i class="fas fa-chart-line"></i>
            <strong>Example:</strong> Loan: ₹25,00,000 | Current Rate: 11.5% | New Rate: 8.75% | Tenure: 12 Years<br>
            Monthly Savings: ~₹4,000 | Total Interest Savings: ~₹5,76,000 | Net Savings after costs: ~₹5,50,000
        </div>
    </div>
</div>

<?php include('../../../includes/footer.php'); ?>

<script>
    // DOM Elements
    const remainingPrincipalInput = document.getElementById('remainingPrincipal');
    const currentRateInput = document.getElementById('currentRate');
    const remainingTenureInput = document.getElementById('remainingTenure');
    const currentEmiInput = document.getElementById('currentEmi');
    
    const newRateInput = document.getElementById('newRate');
    const newTenureInput = document.getElementById('newTenure');
    const transferFeeInput = document.getElementById('transferFee');
    const prepaymentPenaltyInput = document.getElementById('prepaymentPenalty');
    
    // Display Elements
    const currentEmiDisplay = document.getElementById('currentEmiDisplay');
    const currentInterestSpan = document.getElementById('currentInterest');
    const currentTotalSpan = document.getElementById('currentTotal');
    const newEmiDisplay = document.getElementById('newEmiDisplay');
    const newInterestSpan = document.getElementById('newInterest');
    const newTotalSpan = document.getElementById('newTotal');
    
    const totalSavingsSpan = document.getElementById('totalSavings');
    const interestSavingsSpan = document.getElementById('interestSavings');
    const emiReductionSpan = document.getElementById('emiReduction');
    const netSavingsSpan = document.getElementById('netSavings');
    
    const emiReductionValueSpan = document.getElementById('emiReductionValue');
    const tenorReductionSpan = document.getElementById('tenorReduction');
    const rateReductionSpan = document.getElementById('rateReduction');
    const breakEvenMonthsSpan = document.getElementById('breakEvenMonths');
    
    const transferFeeAmountSpan = document.getElementById('transferFeeAmount');
    const penaltyAmountSpan = document.getElementById('penaltyAmount');
    const totalTransferCostSpan = document.getElementById('totalTransferCost');
    const breakEvenPeriodSpan = document.getElementById('breakEvenPeriod');
    
    const offersGrid = document.getElementById('offersGrid');

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

    // Calculate EMI
    function calculateEMI(principal, annualRate, months) {
        if (principal <= 0 || months <= 0) return 0;
        const monthlyRate = annualRate / 12 / 100;
        if (monthlyRate === 0) return principal / months;
        const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
        return emi;
    }

    // Main calculation function
    function calculateBalanceTransfer() {
        // Get values
        let principal = parseFloat(remainingPrincipalInput.value);
        let currentRate = parseFloat(currentRateInput.value);
        let remainingYears = parseFloat(remainingTenureInput.value);
        let newRate = parseFloat(newRateInput.value);
        let newYears = parseFloat(newTenureInput.value);
        let transferFeePercent = parseFloat(transferFeeInput.value);
        let prepaymentPercent = parseFloat(prepaymentPenaltyInput.value);
        
        // Validate
        if (isNaN(principal) || principal <= 0) principal = 0;
        if (isNaN(currentRate)) currentRate = 0;
        if (isNaN(remainingYears)) remainingYears = 0;
        if (isNaN(newRate)) newRate = 0;
        if (isNaN(newYears)) newYears = remainingYears;
        if (isNaN(transferFeePercent)) transferFeePercent = 0;
        if (isNaN(prepaymentPercent)) prepaymentPercent = 0;
        
        let currentMonths = remainingYears * 12;
        let newMonths = newYears * 12;
        
        // Calculate current loan EMI and totals
        let currentEmi = calculateEMI(principal, currentRate, currentMonths);
        let currentTotalPayment = currentEmi * currentMonths;
        let currentTotalInterest = currentTotalPayment - principal;
        
        // Calculate new loan EMI and totals
        let newEmi = calculateEMI(principal, newRate, newMonths);
        let newTotalPayment = newEmi * newMonths;
        let newTotalInterest = newTotalPayment - principal;
        
        // Calculate transfer costs
        let transferFeeAmount = principal * (transferFeePercent / 100);
        let prepaymentAmount = principal * (prepaymentPercent / 100);
        let totalTransferCost = transferFeeAmount + prepaymentAmount;
        
        // Calculate savings
        let totalInterestSavings = currentTotalInterest - newTotalInterest;
        let monthlyEmiReduction = currentEmi - newEmi;
        let totalSavingsBeforeCost = currentTotalPayment - newTotalPayment;
        let netSavings = totalSavingsBeforeCost - totalTransferCost;
        
        // Calculate break-even period (months to recover transfer cost)
        let breakEvenMonths = monthlyEmiReduction > 0 ? Math.ceil(totalTransferCost / monthlyEmiReduction) : 0;
        
        // Calculate rate reduction
        let rateReduction = currentRate - newRate;
        
        // Calculate tenor reduction
        let tenorReduction = remainingYears - newYears;
        
        // Update current loan display
        currentEmiInput.value = Math.round(currentEmi);
        currentEmiDisplay.innerText = formatIndianCurrency(currentEmi);
        currentInterestSpan.innerText = formatIndianCurrency(currentTotalInterest);
        currentTotalSpan.innerText = formatIndianCurrency(currentTotalPayment);
        
        // Update new loan display
        newEmiDisplay.innerText = formatIndianCurrency(newEmi);
        newInterestSpan.innerText = formatIndianCurrency(newTotalInterest);
        newTotalSpan.innerText = formatIndianCurrency(newTotalPayment);
        
        // Update savings display
        totalSavingsSpan.innerText = formatIndianCurrency(Math.max(0, netSavings));
        interestSavingsSpan.innerText = formatIndianCurrency(Math.max(0, totalInterestSavings));
        emiReductionSpan.innerText = formatIndianCurrency(Math.max(0, monthlyEmiReduction));
        netSavingsSpan.innerText = formatIndianCurrency(Math.max(0, netSavings));
        
        // Update results
        emiReductionValueSpan.innerText = formatIndianCurrency(Math.max(0, monthlyEmiReduction));
        tenorReductionSpan.innerText = tenorReduction > 0 ? tenorReduction + ' Years' : (tenorReduction < 0 ? '-' + Math.abs(tenorReduction) + ' Years' : 'Same');
        rateReductionSpan.innerText = rateReduction > 0 ? rateReduction + '%' : (rateReduction < 0 ? '-' + Math.abs(rateReduction) + '%' : '0%');
        breakEvenMonthsSpan.innerText = breakEvenMonths;
        
        // Update transfer costs
        transferFeeAmountSpan.innerText = formatIndianCurrency(transferFeeAmount);
        penaltyAmountSpan.innerText = formatIndianCurrency(prepaymentAmount);
        totalTransferCostSpan.innerText = formatIndianCurrency(totalTransferCost);
        breakEvenPeriodSpan.innerText = breakEvenMonths + ' months';
        
        // Change savings section color based on savings
        const savingsSection = document.getElementById('savingsSection');
        if (netSavings > 0) {
            savingsSection.style.background = 'linear-gradient(135deg, #ECFDF5, #D1FAE5)';
            savingsSection.style.border = '1px solid rgba(16,185,129,0.3)';
        } else {
            savingsSection.style.background = '#FEF3E8';
            savingsSection.style.border = '1px solid rgba(249,115,22,0.3)';
        }
    }
    
    function updateBankOffers() {
        const offers = [
            { bank: 'HDFC Bank', rate: '8.50%', savings: 'Save up to ₹5L' },
            { bank: 'ICICI Bank', rate: '8.65%', savings: 'Save up to ₹4.5L' },
            { bank: 'SBI', rate: '8.40%', savings: 'Save up to ₹5.5L' },
            { bank: 'Axis Bank', rate: '8.70%', savings: 'Save up to ₹4L' },
            { bank: 'Kotak Bank', rate: '8.75%', savings: 'Save up to ₹3.8L' }
        ];
        
        offersGrid.innerHTML = offers.map(offer => `
            <div class="offer-card" onclick="document.getElementById('newRate').value = parseFloat('${offer.rate}'); calculateBalanceTransfer();">
                <div class="bank">${offer.bank}</div>
                <div class="rate">${offer.rate}</div>
                <div class="savings">${offer.savings}</div>
            </div>
        `).join('');
    }
    
    // Calculate current EMI based on principal, rate, tenure
    function updateCurrentEmi() {
        let principal = parseFloat(remainingPrincipalInput.value);
        let currentRate = parseFloat(currentRateInput.value);
        let remainingYears = parseFloat(remainingTenureInput.value);
        
        if (!isNaN(principal) && !isNaN(currentRate) && !isNaN(remainingYears) && principal > 0 && remainingYears > 0) {
            let months = remainingYears * 12;
            let emi = calculateEMI(principal, currentRate, months);
            currentEmiInput.value = Math.round(emi);
        }
        calculateBalanceTransfer();
    }
    
    // Event Listeners
    remainingPrincipalInput.addEventListener('input', updateCurrentEmi);
    currentRateInput.addEventListener('input', updateCurrentEmi);
    remainingTenureInput.addEventListener('input', updateCurrentEmi);
    newRateInput.addEventListener('input', calculateBalanceTransfer);
    newTenureInput.addEventListener('input', calculateBalanceTransfer);
    transferFeeInput.addEventListener('input', calculateBalanceTransfer);
    prepaymentPenaltyInput.addEventListener('input', calculateBalanceTransfer);
    
    // Initial calculations
    updateBankOffers();
    updateCurrentEmi();
    calculateBalanceTransfer();
</script>
</body>
</html>