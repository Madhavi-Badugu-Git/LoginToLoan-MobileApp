<?php
// FILE: emi.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Universal EMI Calculator – Calculate Loan EMI for Any Loan</title>
    <meta name="description" content="Calculate EMI for any loan - Personal Loan, Home Loan, Car Loan, Business Loan, Education Loan. Free universal EMI calculator with amortization schedule.">
    <meta name="keywords" content="EMI calculator, loan EMI calculator, universal EMI calculator, personal loan EMI, home loan EMI, car loan EMI, LOGINTOLOANS">
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

        /* Loan Type Tabs */
        .loan-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 32px;
            justify-content: center;
        }
        .loan-tab {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 50px;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-body);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .loan-tab i {
            font-size: 16px;
            color: var(--text-muted);
        }
        .loan-tab:hover {
            border-color: var(--orange);
            background: #FFF9F5;
        }
        .loan-tab.active {
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            border-color: var(--orange);
            color: white;
        }
        .loan-tab.active i {
            color: white;
        }

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

        .triple-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 28px;
        }

        /* Results Grid */
        .result-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin: 32px 0 24px;
            padding-top: 24px;
            border-top: 2px dashed var(--border);
        }
        .result-card {
            background: linear-gradient(135deg, #F8FAFF, #F1F5F9);
            border-radius: 20px;
            padding: 20px 12px;
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
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            margin-bottom: 6px;
        }
        .result-value {
            font-size: 22px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }
        .result-value small {
            font-size: 11px;
            font-weight: 500;
        }

        /* Loan Summary Section */
        .summary-section {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 20px;
        }
        .summary-section h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .summary-section h4 i { color: var(--orange); }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        .summary-item {
            background: var(--white);
            padding: 12px;
            border-radius: 12px;
            text-align: center;
        }
        .summary-item .label {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 4px;
        }
        .summary-item .value {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
        }

        /* Amortization Table */
        .amortization-section {
            margin-top: 32px;
            background: #F8FAFF;
            border-radius: 20px;
            padding: 20px;
        }
        .amortization-section h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .amortization-section h4 i { color: var(--orange); }
        .table-wrapper {
            overflow-x: auto;
            max-height: 400px;
            overflow-y: auto;
        }
        .amortization-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .amortization-table th, .amortization-table td {
            padding: 12px 8px;
            text-align: right;
            border-bottom: 1px solid var(--border);
        }
        .amortization-table th:first-child, .amortization-table td:first-child {
            text-align: left;
        }
        .amortization-table th {
            background: var(--white);
            font-weight: 700;
            color: var(--navy);
            position: sticky;
            top: 0;
        }
        .amortization-table tr:hover {
            background: rgba(249,115,22,0.05);
        }
        .view-toggle {
            text-align: center;
            margin-top: 16px;
        }
        .view-toggle button {
            background: var(--white);
            border: 1.5px solid var(--orange);
            color: var(--orange);
            padding: 8px 24px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .view-toggle button:hover {
            background: var(--orange);
            color: white;
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
            font-size: 16px;
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

        @media (max-width: 900px) {
            .result-grid { grid-template-columns: repeat(2, 1fr); }
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .calculator-card { padding: 24px; }
            .double-group, .triple-group { grid-template-columns: 1fr; gap: 16px; margin-bottom: 20px; }
            .result-grid { grid-template-columns: 1fr; }
            .summary-grid { grid-template-columns: 1fr; }
            .calculator-header h1 { font-size: 32px; }
            .loan-tab { padding: 8px 16px; font-size: 12px; }
        }
    </style>
</head>
<body>



<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-calculator"></i>
            <span>Universal EMI Calculator</span>
        </div>
        <h1>Calculate Loan EMI for Any Loan</h1>
        <p>Personal Loan | Home Loan | Car Loan | Business Loan | Education Loan | Any Loan</p>
    </div>

    <!-- Loan Type Tabs -->
    <div class="loan-tabs">
        <div class="loan-tab active" data-loan="personal">
            <i class="fas fa-user"></i> Personal Loan
        </div>
        <div class="loan-tab" data-loan="home">
            <i class="fas fa-home"></i> Home Loan
        </div>
        <div class="loan-tab" data-loan="car">
            <i class="fas fa-car"></i> Car Loan
        </div>
        <div class="loan-tab" data-loan="business">
            <i class="fas fa-briefcase"></i> Business Loan
        </div>
        <div class="loan-tab" data-loan="education">
            <i class="fas fa-graduation-cap"></i> Education Loan
        </div>
    </div>

    <div class="calculator-card">
        <!-- Loan Amount -->
        <div class="form-group">
            <label><i class="fas fa-rupee-sign"></i> LOAN AMOUNT (₹)</label>
            <div class="input-prefix">
                <span class="prefix-icon">₹</span>
                <input type="number" id="loanAmount" placeholder="Enter loan amount" value="1000000" step="50000">
            </div>
        </div>

        <!-- Interest Rate & Tenure -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-percent"></i> INTEREST RATE (% P.A.)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">%</span>
                    <input type="number" id="interestRate" placeholder="Interest rate per annum" value="10.5" step="0.25">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> LOAN TENURE</label>
                <div class="double-group" style="gap: 12px; margin-bottom: 0;">
                    <input type="number" id="tenureValue" placeholder="Value" value="5" step="1" style="width: 60%;">
                    <select id="tenureType" style="width: 40%;">
                        <option value="years" selected>Years</option>
                        <option value="months">Months</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Processing Fee & Start Date (Optional) -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-file-invoice"></i> PROCESSING FEE (%) <span style="font-weight: normal;">(Optional)</span></label>
                <div class="input-prefix">
                    <span class="prefix-icon">%</span>
                    <input type="number" id="processingFee" placeholder="Processing fee percentage" value="1" step="0.5">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar"></i> LOAN START DATE (Optional)</label>
                <input type="month" id="startDate">
            </div>
        </div>

        <!-- Results Grid -->
        <div class="result-grid">
            <div class="result-card">
                <i class="fas fa-chart-pie"></i>
                <div class="result-label">MONTHLY EMI</div>
                <div class="result-value" id="monthlyEmi">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-coins"></i>
                <div class="result-label">TOTAL INTEREST</div>
                <div class="result-value" id="totalInterest">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-wallet"></i>
                <div class="result-label">TOTAL PAYMENT</div>
                <div class="result-value" id="totalPayment">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-hand-holding-usd"></i>
                <div class="result-label">PROCESSING FEE</div>
                <div class="result-value" id="processingFeeAmount">₹0</div>
            </div>
        </div>

        <!-- Loan Summary Section -->
        <div class="summary-section">
            <h4><i class="fas fa-info-circle"></i> Loan Summary</h4>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="label">Principal Amount</div>
                    <div class="value" id="principalDisplay">₹10,00,000</div>
                </div>
                <div class="summary-item">
                    <div class="label">Interest Rate</div>
                    <div class="value" id="rateDisplay">10.5%</div>
                </div>
                <div class="summary-item">
                    <div class="label">Loan Tenure</div>
                    <div class="value" id="tenureDisplay">5 Years</div>
                </div>
                <div class="summary-item">
                    <div class="label">EMI per Lakh</div>
                    <div class="value" id="emiPerLakh">₹0</div>
                </div>
                <div class="summary-item">
                    <div class="label">Annual EMI Outflow</div>
                    <div class="value" id="annualEmi">₹0</div>
                </div>
            </div>
        </div>

        <!-- Amortization Table Toggle -->
        <div class="amortization-section" id="amortizationSection" style="display: none;">
            <h4><i class="fas fa-table"></i> Loan Amortization Schedule</h4>
            <div class="table-wrapper">
                <table class="amortization-table" id="amortizationTable">
                    <thead>
                        <tr><th>#</th><th>EMI (₹)</th><th>Principal (₹)</th><th>Interest (₹)</th><th>Balance (₹)</th></tr>
                    </thead>
                    <tbody id="amortizationBody"></tbody>
                </table>
            </div>
            <div class="view-toggle">
                <button id="toggleAmortizationBtn">View Amortization Schedule</button>
            </div>
        </div>
    </div>

    <!-- Formula Explanation Section -->
    <div class="formula-section">
        <h3>
            <i class="fas fa-square-root-alt"></i>
            EMI Calculation Formula
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
            <p>Total Interest = (EMI × n) - P</p>
            <div class="formula-desc">
                Total Payment = Principal + Total Interest + Processing Fee (if any)
            </div>
        </div>

        <div class="formula-box">
            <p>Key Insights:</p>
            <div class="formula-desc">
                • <strong>Lower Interest Rate:</strong> Reduces EMI and total interest significantly<br>
                • <strong>Longer Tenure:</strong> Lower EMI but higher total interest<br>
                • <strong>Shorter Tenure:</strong> Higher EMI but lower total interest<br>
                • <strong>Extra Payments:</strong> Making prepayments reduces principal and total interest
            </div>
        </div>

        <div class="formula-note">
            <i class="fas fa-lightbulb"></i>
            <strong>Pro Tip:</strong> Even a 1% reduction in interest rate can save you lakhs over the loan tenure. Always compare rates from multiple lenders before finalizing.
        </div>

        <div class="formula-note" style="background: #ECFDF5; color: #059669; margin-top: 12px;">
            <i class="fas fa-chart-line"></i>
            <strong>Example:</strong> Loan Amount: ₹10,00,000 | Interest: 10.5% | Tenure: 5 Years<br>
            Monthly EMI: ₹21,494 | Total Interest: ₹2,89,640 | Total Payment: ₹12,89,640
        </div>
    </div>
</div>

<?php include('../../../includes/footer.php'); ?>

<script>
    // DOM Elements
    const loanAmountInput = document.getElementById('loanAmount');
    const interestRateInput = document.getElementById('interestRate');
    const tenureValueInput = document.getElementById('tenureValue');
    const tenureTypeSelect = document.getElementById('tenureType');
    const processingFeeInput = document.getElementById('processingFee');
    const startDateInput = document.getElementById('startDate');
    
    const monthlyEmiSpan = document.getElementById('monthlyEmi');
    const totalInterestSpan = document.getElementById('totalInterest');
    const totalPaymentSpan = document.getElementById('totalPayment');
    const processingFeeAmountSpan = document.getElementById('processingFeeAmount');
    const principalDisplay = document.getElementById('principalDisplay');
    const rateDisplay = document.getElementById('rateDisplay');
    const tenureDisplay = document.getElementById('tenureDisplay');
    const emiPerLakhSpan = document.getElementById('emiPerLakh');
    const annualEmiSpan = document.getElementById('annualEmi');
    
    const amortizationSection = document.getElementById('amortizationSection');
    const amortizationBody = document.getElementById('amortizationBody');
    const toggleBtn = document.getElementById('toggleAmortizationBtn');
    const loanTabs = document.querySelectorAll('.loan-tab');
    
    let showAmortization = false;

    // Loan type presets
    const loanPresets = {
        personal: { rate: 10.5, tenure: 5, minAmount: 50000, maxAmount: 4000000 },
        home: { rate: 8.5, tenure: 20, minAmount: 500000, maxAmount: 100000000 },
        car: { rate: 9.5, tenure: 5, minAmount: 200000, maxAmount: 5000000 },
        business: { rate: 11.5, tenure: 5, minAmount: 100000, maxAmount: 50000000 },
        education: { rate: 9.5, tenure: 10, minAmount: 100000, maxAmount: 15000000 }
    };

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

    // Generate Amortization Schedule
    function generateAmortization(principal, annualRate, months, emi, startDate) {
        const monthlyRate = annualRate / 12 / 100;
        let balance = principal;
        let schedule = [];
        let currentDate = startDate ? new Date(startDate) : new Date();
        
        for (let i = 1; i <= months; i++) {
            let interest = balance * monthlyRate;
            let principalPaid = emi - interest;
            if (principalPaid > balance) principalPaid = balance;
            balance = balance - principalPaid;
            if (balance < 0) balance = 0;
            
            let dateStr = '';
            if (startDate) {
                let nextDate = new Date(currentDate);
                nextDate.setMonth(currentDate.getMonth() + i);
                dateStr = nextDate.toLocaleDateString('en-IN', { month: 'short', year: 'numeric' });
            }
            
            schedule.push({
                month: i,
                date: dateStr,
                emi: emi,
                principal: principalPaid,
                interest: interest,
                balance: balance
            });
            
            if (balance <= 0) break;
        }
        return schedule;
    }
    
    function displayAmortization(schedule) {
        amortizationBody.innerHTML = '';
        let totalPrincipal = 0;
        let totalInterest = 0;
        
        schedule.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.month} ${item.date ? `<br><small style="font-size:10px;">${item.date}</small>` : ''}</td>
                <td>${formatIndianCurrency(item.emi)}</td>
                <td>${formatIndianCurrency(item.principal)}</td>
                <td>${formatIndianCurrency(item.interest)}</td>
                <td>${formatIndianCurrency(item.balance)}</td>
            `;
            amortizationBody.appendChild(row);
            totalPrincipal += item.principal;
            totalInterest += item.interest;
        });
        
        const summaryRow = document.createElement('tr');
        summaryRow.style.background = '#FEF3E8';
        summaryRow.style.fontWeight = 'bold';
        summaryRow.innerHTML = `
            <td><strong>Total</strong></td>
            <td><strong>${formatIndianCurrency(schedule.reduce((sum, item) => sum + item.emi, 0))}</strong></td>
            <td><strong>${formatIndianCurrency(totalPrincipal)}</strong></td>
            <td><strong>${formatIndianCurrency(totalInterest)}</strong></td>
            <td></td>
        `;
        amortizationBody.appendChild(summaryRow);
    }

    // Apply loan preset
    function applyLoanPreset(loanType) {
        const preset = loanPresets[loanType];
        if (preset) {
            interestRateInput.value = preset.rate;
            tenureValueInput.value = preset.tenure;
            tenureTypeSelect.value = 'years';
            calculateEMIAmount();
        }
    }

    // Main calculation function
    function calculateEMIAmount() {
        // Get values
        let principal = parseFloat(loanAmountInput.value);
        let annualRate = parseFloat(interestRateInput.value);
        let tenureValue = parseFloat(tenureValueInput.value);
        let tenureType = tenureTypeSelect.value;
        let processingFeePercent = parseFloat(processingFeeInput.value);
        
        // Validate inputs
        if (isNaN(principal) || principal <= 0) principal = 0;
        if (isNaN(annualRate) || annualRate <= 0) annualRate = 0;
        if (isNaN(tenureValue) || tenureValue <= 0) tenureValue = 1;
        if (isNaN(processingFeePercent)) processingFeePercent = 0;
        
        // Calculate months
        let months = tenureType === 'years' ? tenureValue * 12 : tenureValue;
        months = Math.floor(months);
        
        // Calculate EMI
        let emi = calculateEMI(principal, annualRate, months);
        
        // Calculate totals
        let totalPayment = emi * months;
        let totalInterest = totalPayment - principal;
        let processingFee = principal * (processingFeePercent / 100);
        let totalWithFee = totalPayment + processingFee;
        
        // Calculate EMI per lakh
        let emiPerLakh = calculateEMI(100000, annualRate, months);
        
        // Update UI
        monthlyEmiSpan.innerText = formatIndianCurrency(emi);
        totalInterestSpan.innerText = formatIndianCurrency(totalInterest);
        totalPaymentSpan.innerText = formatIndianCurrency(totalWithFee);
        processingFeeAmountSpan.innerText = formatIndianCurrency(processingFee);
        principalDisplay.innerText = formatIndianCurrency(principal);
        rateDisplay.innerText = annualRate + '%';
        
        let tenureText = tenureType === 'years' ? 
            tenureValue + (tenureValue === 1 ? ' Year' : ' Years') + ' (' + months + ' Months)' : 
            tenureValue + (tenureValue === 1 ? ' Month' : ' Months');
        tenureDisplay.innerText = tenureText;
        emiPerLakhSpan.innerText = formatIndianCurrency(emiPerLakh);
        
        let annualEmi = emi * 12;
        annualEmiSpan.innerText = formatIndianCurrency(annualEmi);
        
        // Generate amortization if toggled
        if (showAmortization && principal > 0 && months > 0 && emi > 0) {
            const startDate = startDateInput.value;
            const schedule = generateAmortization(principal, annualRate, months, emi, startDate);
            displayAmortization(schedule);
        }
    }
    
    function toggleAmortization() {
        showAmortization = !showAmortization;
        if (showAmortization) {
            amortizationSection.style.display = 'block';
            toggleBtn.textContent = 'Hide Amortization Schedule';
            
            let principal = parseFloat(loanAmountInput.value);
            let annualRate = parseFloat(interestRateInput.value);
            let tenureValue = parseFloat(tenureValueInput.value);
            let tenureType = tenureTypeSelect.value;
            let months = tenureType === 'years' ? tenureValue * 12 : tenureValue;
            let emi = calculateEMI(principal, annualRate, months);
            const startDate = startDateInput.value;
            
            if (principal > 0 && months > 0 && emi > 0) {
                const schedule = generateAmortization(principal, annualRate, months, emi, startDate);
                displayAmortization(schedule);
            }
        } else {
            amortizationSection.style.display = 'none';
            toggleBtn.textContent = 'View Amortization Schedule';
        }
    }
    
    // Tab click handlers
    loanTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            loanTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            const loanType = tab.getAttribute('data-loan');
            applyLoanPreset(loanType);
        });
    });
    
    // Event Listeners
    loanAmountInput.addEventListener('input', calculateEMIAmount);
    interestRateInput.addEventListener('input', calculateEMIAmount);
    tenureValueInput.addEventListener('input', calculateEMIAmount);
    tenureTypeSelect.addEventListener('change', calculateEMIAmount);
    processingFeeInput.addEventListener('input', calculateEMIAmount);
    startDateInput.addEventListener('change', () => {
        if (showAmortization) calculateEMIAmount();
    });
    toggleBtn.addEventListener('click', toggleAmortization);
    
    // Set default start date to current month
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    startDateInput.value = `${year}-${month}`;
    
    // Initial calculation
    calculateEMIAmount();
</script>
</body>
</html>