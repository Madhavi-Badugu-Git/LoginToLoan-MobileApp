<?php
// FILE: interestrate.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Interest Rate Comparison – Best Loan Interest Rates in India</title>
    <meta name="description" content="Compare interest rates for Personal Loan, Home Loan, Car Loan, Business Loan, and Education Loan from top banks in India. Find the best loan interest rates.">
    <meta name="keywords" content="interest rates, loan interest rates, personal loan interest rate, home loan interest rate, car loan interest rate, best loan rates, LOGINTOLOANS">
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

        /* Loan Type Tabs */
        .loan-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 30px;
            justify-content: center;
        }
        .loan-tab {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 50px;
            padding: 12px 28px;
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

        /* Rate Calculator Card */
        .rate-calculator {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(249,115,22,0.1);
        }
        .rate-calculator h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .emi-compare {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .compare-card {
            background: #F8FAFF;
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .compare-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        .compare-card .rate-value {
            font-size: 32px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--orange);
        }
        .compare-card .emi-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--navy);
            margin-top: 10px;
        }
        .savings-badge {
            background: #ECFDF5;
            color: var(--success);
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
        }

        /* Interest Rate Tables */
        .rates-table-container {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 30px;
            box-shadow: var(--shadow-lg);
        }
        .rates-table-container h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        .rates-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .rates-table th, .rates-table td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .rates-table th {
            background: #F8FAFF;
            font-weight: 700;
            color: var(--navy);
            position: sticky;
            top: 0;
        }
        .rates-table tr:hover {
            background: rgba(249,115,22,0.05);
        }
        .rates-table .best-rate {
            color: var(--success);
            font-weight: 700;
        }
        .bank-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .bank-logo i {
            font-size: 24px;
            color: var(--orange);
        }

        /* Factors Section */
        .factors-section {
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: var(--radius-lg);
            padding: 30px;
            margin-top: 30px;
        }
        .factors-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .factors-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .factor-card {
            background: var(--white);
            padding: 20px;
            border-radius: 16px;
            text-align: center;
        }
        .factor-card i {
            font-size: 32px;
            color: var(--orange);
            margin-bottom: 10px;
        }
        .factor-card h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 8px;
        }
        .factor-card p {
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
            .emi-compare { grid-template-columns: 1fr; }
            .factors-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .loan-tab { padding: 8px 16px; font-size: 12px; }
            .rate-calculator, .rates-table-container { padding: 20px; }
            .factors-grid { grid-template-columns: 1fr; }
            .calculator-header h1 { font-size: 32px; }
        }
    </style>
</head>
<body>



<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-percent"></i>
            <span>Interest Rate Comparison</span>
        </div>
        <h1>Compare Loan Interest Rates</h1>
        <p>Find the best interest rates for Personal, Home, Car, Business & Education Loans from top banks</p>
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

    <!-- Rate Calculator -->
    <div class="rate-calculator">
        <h3><i class="fas fa-calculator"></i> See How Interest Rate Affects Your EMI</h3>
        <div class="double-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> LOAN AMOUNT (₹)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">₹</span>
                    <input type="number" id="calcLoanAmount" value="1000000" step="50000">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> LOAN TENURE (Years)</label>
                <input type="number" id="calcTenure" value="5" step="1">
            </div>
        </div>
        <div class="emi-compare" id="emiCompare">
            <!-- Dynamic comparison cards -->
        </div>
    </div>

    <!-- Interest Rate Table -->
    <div class="rates-table-container">
        <h3><i class="fas fa-chart-line"></i> <span id="tableTitle">Personal Loan</span> Interest Rates</h3>
        <div class="table-wrapper">
            <table class="rates-table" id="ratesTable">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Interest Rate (p.a.)</th>
                        <th>Processing Fee</th>
                        <th>Max Tenure</th>
                        <th>Eligibility</th>
                    </tr>
                </thead>
                <tbody id="ratesTableBody">
                    <!-- Dynamic content -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Factors Affecting Interest Rates -->
    <div class="factors-section">
        <h3><i class="fas fa-chart-pie"></i> Factors That Affect Your Interest Rate</h3>
        <div class="factors-grid">
            <div class="factor-card">
                <i class="fas fa-chart-line"></i>
                <h4>Credit Score</h4>
                <p>750+ gets lowest rates, below 650 may get higher rates</p>
            </div>
            <div class="factor-card">
                <i class="fas fa-briefcase"></i>
                <h4>Employment Type</h4>
                <p>Salaried vs Self-employed - Salaried often gets better rates</p>
            </div>
            <div class="factor-card">
                <i class="fas fa-building"></i>
                <h4>Company Profile</h4>
                <p>Working with top MNCs/PSUs can get preferential rates</p>
            </div>
            <div class="factor-card">
                <i class="fas fa-history"></i>
                <h4>Relationship with Bank</h4>
                <p>Existing customers may get lower interest rates</p>
            </div>
        </div>
    </div>

    <!-- Formula Section -->
    <div class="formula-section">
        <h3><i class="fas fa-square-root-alt"></i> How Interest Rates Work</h3>
        <div class="formula-box">
            <p>EMI Formula: EMI = P × r × (1+r)ⁿ / ((1+r)ⁿ - 1)</p>
            <div class="formula-desc">A lower interest rate directly reduces your EMI and total interest payable over the loan tenure.</div>
        </div>
        <div class="formula-box">
            <p>Interest Types:</p>
            <div class="formula-desc">
                • <strong>Fixed Rate:</strong> Remains constant throughout loan tenure<br>
                • <strong>Floating Rate:</strong> Changes based on market conditions (linked to RBI repo rate)<br>
                • <strong>Reducing Balance:</strong> Interest calculated on outstanding principal (most common)
            </div>
        </div>
        <div class="formula-note" style="background: #FEF3E8; padding: 16px; border-radius: 12px;">
            <i class="fas fa-lightbulb"></i> <strong>Tip:</strong> Even a 0.5% reduction in interest rate can save you thousands over the loan tenure. Always negotiate with banks if you have a good credit score.
        </div>
    </div>
</div>

<?php include('../../../includes/footer.php'); ?>

<script>
    // Interest rate data for different loan types
    const rateData = {
        personal: {
            title: "Personal Loan",
            banks: [
                { name: "SBI", rate: "10.40", fee: "1.50%", tenure: "6 Years", eligibility: "Salaried/Self-employed" },
                { name: "HDFC Bank", rate: "10.50", fee: "2.50%", tenure: "7 Years", eligibility: "Salaried/Self-employed" },
                { name: "ICICI Bank", rate: "10.75", fee: "2.25%", tenure: "7 Years", eligibility: "Salaried/Self-employed" },
                { name: "Axis Bank", rate: "10.99", fee: "2.00%", tenure: "7 Years", eligibility: "Salaried/Self-employed" },
                { name: "Kotak Bank", rate: "10.75", fee: "2.50%", tenure: "7 Years", eligibility: "Salaried/Self-employed" },
                { name: "Bajaj Finserv", rate: "11.00", fee: "2.50%", tenure: "5 Years", eligibility: "Salaried/Self-employed" },
                { name: "Tata Capital", rate: "10.99", fee: "2.00%", tenure: "6 Years", eligibility: "Salaried" },
                { name: "Yes Bank", rate: "11.25", fee: "2.50%", tenure: "5 Years", eligibility: "Salaried" }
            ]
        },
        home: {
            title: "Home Loan",
            banks: [
                { name: "SBI", rate: "8.40", fee: "0.50%", tenure: "30 Years", eligibility: "Salaried/Self-employed" },
                { name: "HDFC Bank", rate: "8.55", fee: "0.50%", tenure: "30 Years", eligibility: "Salaried/Self-employed" },
                { name: "ICICI Bank", rate: "8.60", fee: "0.50%", tenure: "30 Years", eligibility: "Salaried/Self-employed" },
                { name: "Axis Bank", rate: "8.70", fee: "0.50%", tenure: "30 Years", eligibility: "Salaried/Self-employed" },
                { name: "LIC HFL", rate: "8.50", fee: "0.50%", tenure: "30 Years", eligibility: "Salaried/Self-employed" },
                { name: "PNB Housing", rate: "8.65", fee: "0.50%", tenure: "30 Years", eligibility: "Salaried/Self-employed" },
                { name: "Bank of Baroda", rate: "8.45", fee: "0.50%", tenure: "30 Years", eligibility: "Salaried/Self-employed" },
                { name: "Canara Bank", rate: "8.55", fee: "0.50%", tenure: "30 Years", eligibility: "Salaried/Self-employed" }
            ]
        },
        car: {
            title: "Car Loan",
            banks: [
                { name: "SBI", rate: "8.70", fee: "1.00%", tenure: "7 Years", eligibility: "Salaried/Self-employed" },
                { name: "HDFC Bank", rate: "8.90", fee: "1.50%", tenure: "7 Years", eligibility: "Salaried/Self-employed" },
                { name: "ICICI Bank", rate: "9.00", fee: "1.50%", tenure: "7 Years", eligibility: "Salaried/Self-employed" },
                { name: "Axis Bank", rate: "9.10", fee: "1.50%", tenure: "7 Years", eligibility: "Salaried/Self-employed" },
                { name: "Kotak Bank", rate: "9.25", fee: "2.00%", tenure: "7 Years", eligibility: "Salaried" },
                { name: "IDFC First", rate: "9.00", fee: "1.50%", tenure: "7 Years", eligibility: "Salaried" },
                { name: "Tata Capital", rate: "9.15", fee: "1.50%", tenure: "7 Years", eligibility: "Salaried" }
            ]
        },
        business: {
            title: "Business Loan",
            banks: [
                { name: "SBI", rate: "11.40", fee: "1.50%", tenure: "5 Years", eligibility: "Business vintage 3+ years" },
                { name: "HDFC Bank", rate: "11.50", fee: "2.00%", tenure: "5 Years", eligibility: "Turnover ₹40L+" },
                { name: "ICICI Bank", rate: "11.75", fee: "2.00%", tenure: "5 Years", eligibility: "Business vintage 3+ years" },
                { name: "Axis Bank", rate: "12.00", fee: "2.00%", tenure: "5 Years", eligibility: "Turnover ₹30L+" },
                { name: "Kotak Bank", rate: "11.75", fee: "2.50%", tenure: "4 Years", eligibility: "Business vintage 3+ years" },
                { name: "Bajaj Finserv", rate: "12.50", fee: "2.50%", tenure: "5 Years", eligibility: "Turnover ₹25L+" },
                { name: "IndusInd Bank", rate: "11.99", fee: "2.00%", tenure: "5 Years", eligibility: "Business vintage 2+ years" }
            ]
        },
        education: {
            title: "Education Loan",
            banks: [
                { name: "SBI", rate: "8.65", fee: "0.00%", tenure: "15 Years", eligibility: "India/Abroad studies" },
                { name: "Bank of Baroda", rate: "8.90", fee: "0.00%", tenure: "15 Years", eligibility: "India/Abroad studies" },
                { name: "PNB", rate: "8.75", fee: "0.00%", tenure: "15 Years", eligibility: "India/Abroad studies" },
                { name: "Canara Bank", rate: "8.95", fee: "0.00%", tenure: "15 Years", eligibility: "India/Abroad studies" },
                { name: "HDFC Credila", rate: "9.50", fee: "1.00%", tenure: "10 Years", eligibility: "Abroad studies" },
                { name: "Avanse", rate: "9.75", fee: "1.00%", tenure: "12 Years", eligibility: "Abroad studies" },
                { name: "ICICI Bank", rate: "9.75", fee: "1.00%", tenure: "10 Years", eligibility: "Abroad studies" }
            ]
        }
    };

    // Calculate EMI
    function calculateEMI(principal, annualRate, months) {
        if (principal <= 0 || months <= 0) return 0;
        const monthlyRate = annualRate / 12 / 100;
        if (monthlyRate === 0) return principal / months;
        const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
        return emi;
    }

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

    let currentLoanType = 'personal';

    function updateEMIComparison() {
        const loanAmount = parseFloat(document.getElementById('calcLoanAmount').value) || 1000000;
        const tenureYears = parseFloat(document.getElementById('calcTenure').value) || 5;
        const months = tenureYears * 12;
        
        const banks = rateData[currentLoanType].banks.slice(0, 3);
        const rates = banks.map(b => parseFloat(b.rate));
        
        const emis = rates.map(rate => calculateEMI(loanAmount, rate, months));
        const lowestRate = Math.min(...rates);
        const lowestEmi = Math.min(...emis);
        
        const container = document.getElementById('emiCompare');
        container.innerHTML = banks.map((bank, idx) => {
            const isBest = rates[idx] === lowestRate;
            const savings = lowestEmi > 0 && emis[idx] > 0 ? emis[idx] - lowestEmi : 0;
            return `
                <div class="compare-card">
                    <div class="rate-value">${banks[idx].rate}%</div>
                    <div class="emi-value">EMI: ${formatIndianCurrency(emis[idx])}</div>
                    ${isBest ? '<div class="savings-badge">⭐ Best Rate</div>' : `<div class="savings-badge">Save ${formatIndianCurrency(savings)}/month</div>`}
                    <div style="font-size: 12px; margin-top: 10px; color: var(--text-muted);">${bank.name}</div>
                </div>
            `;
        }).join('');
    }

    function updateRateTable() {
        const data = rateData[currentLoanType];
        document.getElementById('tableTitle').innerText = data.title;
        
        const tbody = document.getElementById('ratesTableBody');
        tbody.innerHTML = data.banks.map(bank => {
            const isBest = parseFloat(bank.rate) === Math.min(...data.banks.map(b => parseFloat(b.rate)));
            return `
                <tr>
                    <td><div class="bank-logo"><i class="fas fa-university"></i> ${bank.name}</div></td>
                    <td class="${isBest ? 'best-rate' : ''}">${bank.rate}% p.a.</td>
                    <td>${bank.fee}</td>
                    <td>${bank.tenure}</td>
                    <td>${bank.eligibility}</td>
                </tr>
            `;
        }).join('');
    }

    function switchLoanType(loanType) {
        currentLoanType = loanType;
        
        // Update active tab
        document.querySelectorAll('.loan-tab').forEach(tab => {
            tab.classList.remove('active');
            if (tab.getAttribute('data-loan') === loanType) {
                tab.classList.add('active');
            }
        });
        
        updateRateTable();
        updateEMIComparison();
    }

    // Event Listeners
    document.querySelectorAll('.loan-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            switchLoanType(tab.getAttribute('data-loan'));
        });
    });
    
    document.getElementById('calcLoanAmount').addEventListener('input', updateEMIComparison);
    document.getElementById('calcTenure').addEventListener('input', updateEMIComparison);
    
    // Initial load
    switchLoanType('personal');
</script>
</body>
</html>