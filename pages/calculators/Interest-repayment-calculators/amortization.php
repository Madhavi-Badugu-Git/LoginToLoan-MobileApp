<?php
// FILE: amortization.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Loan Amortization Schedule – Track Your Loan Repayment</title>
    <meta name="description" content="Generate detailed loan amortization schedule. See how your loan payments break down into principal and interest over the entire loan term.">
    <meta name="keywords" content="amortization schedule, loan repayment schedule, principal vs interest, loan amortization calculator, LOGINTOLOANS">
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
            max-width: 1400px;
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
        }

        .double-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        /* Summary Cards */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: var(--white);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
        }
        .summary-card i {
            font-size: 28px;
            color: var(--orange);
            margin-bottom: 10px;
        }
        .summary-card .label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 5px;
        }
        .summary-card .value {
            font-size: 22px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }

        /* Chart Section */
        .chart-section {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-lg);
        }
        .chart-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .chart-container {
            display: flex;
            align-items: center;
            gap: 50px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .pie-chart {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: conic-gradient(var(--navy) 0deg, var(--orange) 0deg);
            position: relative;
        }
        .chart-legend {
            flex: 1;
            min-width: 200px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }
        .legend-color {
            width: 24px;
            height: 24px;
            border-radius: 8px;
        }
        .legend-color.principal { background: var(--navy); }
        .legend-color.interest { background: var(--orange); }
        .legend-text {
            font-size: 14px;
        }
        .legend-text strong {
            color: var(--navy);
        }

        /* Amortization Table */
        .table-section {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 30px;
            box-shadow: var(--shadow-lg);
        }
        .table-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .view-options {
            display: flex;
            gap: 10px;
        }
        .view-btn {
            background: #F1F5F9;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .view-btn.active {
            background: var(--orange);
            color: white;
        }
        .download-btn {
            background: var(--navy);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .download-btn:hover {
            background: var(--orange);
        }
        .table-wrapper {
            overflow-x: auto;
            max-height: 500px;
            overflow-y: auto;
            border-radius: 16px;
            border: 1px solid var(--border);
        }
        .amortization-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .amortization-table th, .amortization-table td {
            padding: 12px 10px;
            text-align: right;
            border-bottom: 1px solid var(--border);
        }
        .amortization-table th:first-child, .amortization-table td:first-child {
            text-align: left;
        }
        .amortization-table th {
            background: #F8FAFF;
            font-weight: 700;
            color: var(--navy);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .amortization-table tr:hover {
            background: rgba(249,115,22,0.05);
        }
        .total-row {
            background: #FEF3E8;
            font-weight: 700;
        }
        .total-row td {
            border-top: 2px solid var(--orange);
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
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
            .double-group { grid-template-columns: 1fr; gap: 16px; }
        }
        @media (max-width: 768px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .input-card, .chart-section, .table-section { padding: 20px; }
            .summary-grid { grid-template-columns: 1fr; }
            .calculator-header h1 { font-size: 32px; }
            .chart-container { flex-direction: column; gap: 20px; }
        }
    </style>
</head>
<body>


<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-table"></i>
            <span>Loan Amortization Schedule</span>
        </div>
        <h1>Track Your Loan Repayment</h1>
        <p>See detailed breakdown of principal and interest payments over the entire loan term</p>
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
                <label><i class="fas fa-calendar-alt"></i> LOAN TENURE</label>
                <div class="double-group" style="gap: 12px;">
                    <input type="number" id="tenureValue" value="5" step="1" style="width: 60%;">
                    <select id="tenureType" style="width: 40%;">
                        <option value="years" selected>Years</option>
                        <option value="months">Months</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar"></i> START DATE (Optional)</label>
                <input type="month" id="startDate">
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <i class="fas fa-chart-pie"></i>
            <div class="label">Monthly EMI</div>
            <div class="value" id="emiAmount">₹0</div>
        </div>
        <div class="summary-card">
            <i class="fas fa-coins"></i>
            <div class="label">Total Interest</div>
            <div class="value" id="totalInterest">₹0</div>
        </div>
        <div class="summary-card">
            <i class="fas fa-wallet"></i>
            <div class="label">Total Payment</div>
            <div class="value" id="totalPayment">₹0</div>
        </div>
        <div class="summary-card">
            <i class="fas fa-hand-holding-usd"></i>
            <div class="label">Principal Amount</div>
            <div class="value" id="principalAmount">₹0</div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="chart-section">
        <h3><i class="fas fa-chart-pie"></i> Principal vs Interest Breakdown</h3>
        <div class="chart-container">
            <div class="pie-chart" id="pieChart"></div>
            <div class="chart-legend">
                <div class="legend-item">
                    <div class="legend-color principal"></div>
                    <div class="legend-text"><strong id="principalPercent">0%</strong> - Principal Amount</div>
                </div>
                <div class="legend-item">
                    <div class="legend-color interest"></div>
                    <div class="legend-text"><strong id="interestPercent">0%</strong> - Total Interest</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Amortization Table Section -->
    <div class="table-section">
        <h3><i class="fas fa-table"></i> Amortization Schedule</h3>
        <div class="table-controls">
            <div class="view-options">
                <button class="view-btn active" onclick="setView('monthly')">Monthly View</button>
                <button class="view-btn" onclick="setView('yearly')">Yearly View</button>
            </div>
            <button class="download-btn" onclick="downloadCSV()">
                <i class="fas fa-download"></i> Download as CSV
            </button>
        </div>
        <div class="table-wrapper">
            <table class="amortization-table" id="amortizationTable">
                <thead>
                    <tr id="tableHeader">
                        <th>#</th>
                        <th>EMI (₹)</th>
                        <th>Principal (₹)</th>
                        <th>Interest (₹)</th>
                        <th>Balance (₹)</th>
                    </tr>
                </thead>
                <tbody id="amortizationBody"></tbody>
            </table>
        </div>
    </div>

    <!-- Formula Section -->
    <div class="formula-section">
        <h3><i class="fas fa-square-root-alt"></i> How Amortization Works</h3>
        <div class="formula-box">
            <p>EMI Formula: EMI = P × r × (1+r)ⁿ / ((1+r)ⁿ - 1)</p>
            <div class="formula-desc">Each EMI payment consists of interest on the outstanding balance and principal repayment. Interest is higher in early months and decreases over time.</div>
        </div>
        <div class="formula-box">
            <p>Interest for Month = Outstanding Balance × Monthly Interest Rate</p>
            <div class="formula-desc">Principal Repayment = EMI - Interest | New Balance = Old Balance - Principal Repayment</div>
        </div>
        <div class="formula-note" style="background: #FEF3E8; padding: 16px; border-radius: 12px;">
            <i class="fas fa-lightbulb"></i> <strong>Key Insight:</strong> In the early years of your loan, a larger portion of your EMI goes toward interest. Making prepayments early can save significant interest over the loan term.
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
    const startDateInput = document.getElementById('startDate');
    
    const emiAmountSpan = document.getElementById('emiAmount');
    const totalInterestSpan = document.getElementById('totalInterest');
    const totalPaymentSpan = document.getElementById('totalPayment');
    const principalAmountSpan = document.getElementById('principalAmount');
    const amortizationBody = document.getElementById('amortizationBody');
    const pieChart = document.getElementById('pieChart');
    const principalPercentSpan = document.getElementById('principalPercent');
    const interestPercentSpan = document.getElementById('interestPercent');
    
    let currentView = 'monthly';
    let currentSchedule = [];

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

    function formatNumber(amount) {
        if (isNaN(amount) || amount <= 0) return '0';
        return Math.round(amount).toLocaleString('en-IN');
    }

    // Calculate EMI
    function calculateEMI(principal, annualRate, months) {
        if (principal <= 0 || months <= 0) return 0;
        const monthlyRate = annualRate / 12 / 100;
        if (monthlyRate === 0) return principal / months;
        const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
        return emi;
    }

    // Generate Monthly Schedule
    function generateMonthlySchedule(principal, annualRate, months, emi, startDate) {
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
                period: i,
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

    // Generate Yearly Schedule
    function generateYearlySchedule(monthlySchedule) {
        let yearlySchedule = [];
        let yearData = {};
        
        monthlySchedule.forEach(item => {
            const year = Math.ceil(item.period / 12);
            if (!yearData[year]) {
                yearData[year] = { emi: 0, principal: 0, interest: 0, balance: 0 };
            }
            yearData[year].emi += item.emi;
            yearData[year].principal += item.principal;
            yearData[year].interest += item.interest;
            yearData[year].balance = item.balance;
        });
        
        for (let year in yearData) {
            yearlySchedule.push({
                period: year,
                emi: yearData[year].emi,
                principal: yearData[year].principal,
                interest: yearData[year].interest,
                balance: yearData[year].balance
            });
        }
        return yearlySchedule;
    }

    // Display Schedule
    function displaySchedule() {
        const schedule = currentView === 'monthly' ? currentSchedule : generateYearlySchedule(currentSchedule);
        const headerRow = document.getElementById('tableHeader');
        
        if (currentView === 'monthly' && startDateInput.value) {
            headerRow.innerHTML = `<th>Month</th><th>Date</th><th>EMI (₹)</th><th>Principal (₹)</th><th>Interest (₹)</th><th>Balance (₹)</th>`;
        } else if (currentView === 'monthly') {
            headerRow.innerHTML = `<th>Month</th><th>EMI (₹)</th><th>Principal (₹)</th><th>Interest (₹)</th><th>Balance (₹)</th>`;
        } else {
            headerRow.innerHTML = `<th>Year</th><th>Total EMI (₹)</th><th>Principal (₹)</th><th>Interest (₹)</th><th>Balance (₹)</th>`;
        }
        
        amortizationBody.innerHTML = '';
        let totalPrincipal = 0;
        let totalInterest = 0;
        
        schedule.forEach(item => {
            const row = document.createElement('tr');
            if (currentView === 'monthly' && startDateInput.value) {
                row.innerHTML = `
                    <td>${item.period}</td>
                    <td>${item.date || '-'}</td>
                    <td>${formatNumber(item.emi)}</td>
                    <td>${formatNumber(item.principal)}</td>
                    <td>${formatNumber(item.interest)}</td>
                    <td>${formatNumber(item.balance)}</td>
                `;
            } else if (currentView === 'monthly') {
                row.innerHTML = `
                    <td>${item.period}</td>
                    <td>${formatNumber(item.emi)}</td>
                    <td>${formatNumber(item.principal)}</td>
                    <td>${formatNumber(item.interest)}</td>
                    <td>${formatNumber(item.balance)}</td>
                `;
            } else {
                row.innerHTML = `
                    <td>Year ${item.period}</td>
                    <td>${formatNumber(item.emi)}</td>
                    <td>${formatNumber(item.principal)}</td>
                    <td>${formatNumber(item.interest)}</td>
                    <td>${formatNumber(item.balance)}</td>
                `;
            }
            amortizationBody.appendChild(row);
            totalPrincipal += item.principal;
            totalInterest += item.interest;
        });
        
        // Add total row
        const totalRow = document.createElement('tr');
        totalRow.className = 'total-row';
        if (currentView === 'monthly') {
            totalRow.innerHTML = `
                <td><strong>Total</strong></td>
                <td>${startDateInput.value ? '-' : ''}</td>
                <td><strong>${formatNumber(schedule.reduce((sum, i) => sum + i.emi, 0))}</strong></td>
                <td><strong>${formatNumber(totalPrincipal)}</strong></td>
                <td><strong>${formatNumber(totalInterest)}</strong></td>
                <td>-</td>
            `;
        } else {
            totalRow.innerHTML = `
                <td><strong>Total</strong></td>
                <td><strong>${formatNumber(schedule.reduce((sum, i) => sum + i.emi, 0))}</strong></td>
                <td><strong>${formatNumber(totalPrincipal)}</strong></td>
                <td><strong>${formatNumber(totalInterest)}</strong></td>
                <td>-</td>
            `;
        }
        amortizationBody.appendChild(totalRow);
    }

    // Update Pie Chart
    function updatePieChart(principal, totalInterest) {
        const total = principal + totalInterest;
        const principalDeg = (principal / total) * 360;
        const interestDeg = (totalInterest / total) * 360;
        
        pieChart.style.background = `conic-gradient(var(--navy) 0deg ${principalDeg}deg, var(--orange) ${principalDeg}deg ${principalDeg + interestDeg}deg)`;
        principalPercentSpan.innerText = ((principal / total) * 100).toFixed(1) + '%';
        interestPercentSpan.innerText = ((totalInterest / total) * 100).toFixed(1) + '%';
    }

    // Main Calculation
    function calculateAmortization() {
        let principal = parseFloat(loanAmountInput.value) || 0;
        let annualRate = parseFloat(interestRateInput.value) || 0;
        let tenureValue = parseFloat(tenureValueInput.value) || 1;
        let tenureType = tenureTypeSelect.value;
        let startDate = startDateInput.value;
        
        let months = tenureType === 'years' ? tenureValue * 12 : tenureValue;
        months = Math.floor(months);
        
        let emi = calculateEMI(principal, annualRate, months);
        let totalPayment = emi * months;
        let totalInterest = totalPayment - principal;
        
        // Update summary
        emiAmountSpan.innerText = formatIndianCurrency(emi);
        totalInterestSpan.innerText = formatIndianCurrency(totalInterest);
        totalPaymentSpan.innerText = formatIndianCurrency(totalPayment);
        principalAmountSpan.innerText = formatIndianCurrency(principal);
        
        // Generate schedule
        currentSchedule = generateMonthlySchedule(principal, annualRate, months, emi, startDate);
        
        // Update pie chart
        updatePieChart(principal, totalInterest);
        
        // Display schedule
        displaySchedule();
    }

    // Set View
    function setView(view) {
        currentView = view;
        document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        displaySchedule();
    }

    // Download CSV
    function downloadCSV() {
        const schedule = currentView === 'monthly' ? currentSchedule : generateYearlySchedule(currentSchedule);
        let csvRows = [];
        
        if (currentView === 'monthly') {
            if (startDateInput.value) {
                csvRows.push(['Month', 'Date', 'EMI (₹)', 'Principal (₹)', 'Interest (₹)', 'Balance (₹)']);
                schedule.forEach(item => {
                    csvRows.push([item.period, item.date, item.emi.toFixed(2), item.principal.toFixed(2), item.interest.toFixed(2), item.balance.toFixed(2)]);
                });
            } else {
                csvRows.push(['Month', 'EMI (₹)', 'Principal (₹)', 'Interest (₹)', 'Balance (₹)']);
                schedule.forEach(item => {
                    csvRows.push([item.period, item.emi.toFixed(2), item.principal.toFixed(2), item.interest.toFixed(2), item.balance.toFixed(2)]);
                });
            }
        } else {
            csvRows.push(['Year', 'Total EMI (₹)', 'Principal (₹)', 'Interest (₹)', 'Balance (₹)']);
            schedule.forEach(item => {
                csvRows.push([item.period, item.emi.toFixed(2), item.principal.toFixed(2), item.interest.toFixed(2), item.balance.toFixed(2)]);
            });
        }
        
        const csvContent = csvRows.map(row => row.join(',')).join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `amortization_schedule_${currentView}.csv`;
        a.click();
        URL.revokeObjectURL(url);
    }

    // Event Listeners
    loanAmountInput.addEventListener('input', calculateAmortization);
    interestRateInput.addEventListener('input', calculateAmortization);
    tenureValueInput.addEventListener('input', calculateAmortization);
    tenureTypeSelect.addEventListener('change', calculateAmortization);
    startDateInput.addEventListener('change', calculateAmortization);
    
    // Set default start date
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    startDateInput.value = `${year}-${month}`;
    
    // Initial calculation
    calculateAmortization();
</script>
</body>
</html>