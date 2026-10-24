<?php
// FILE: loan-tenure.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Loan Tenure Calculator – Find Your Ideal Loan Term</title>
    <meta name="description" content="Compare how different loan tenures affect your EMI and total interest. Find the perfect balance between affordable EMIs and total interest cost.">
    <meta name="keywords" content="loan tenure calculator, loan term comparison, EMI vs tenure, loan repayment period, LOGINTOLOANS">
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

        /* Tenure Comparison Slider */
        .tenure-slider-section {
            background: #F8FAFF;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .tenure-slider-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .slider-container {
            margin: 20px 0;
        }
        .slider-labels {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 12px;
            color: var(--text-muted);
        }
        input[type="range"] {
            width: 100%;
            height: 6px;
            -webkit-appearance: none;
            background: linear-gradient(90deg, var(--orange) 0%, var(--orange) 50%, var(--border) 50%, var(--border) 100%);
            border-radius: 10px;
            outline: none;
        }
        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 22px;
            height: 22px;
            background: var(--orange);
            border-radius: 50%;
            box-shadow: 0 2px 10px rgba(249,115,22,0.4);
            border: 3px solid white;
            cursor: pointer;
        }

        /* Comparison Cards */
        .comparison-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 30px;
        }
        .tenure-card {
            background: var(--white);
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            position: relative;
        }
        .tenure-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        .tenure-card.recommended {
            border: 2px solid var(--orange);
            background: linear-gradient(135deg, var(--white), #FFF9F5);
        }
        .recommended-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--orange);
            color: white;
            padding: 4px 16px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }
        .tenure-value {
            font-size: 28px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--orange);
            margin-bottom: 5px;
        }
        .tenure-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 15px;
        }
        .emi-amount {
            font-size: 22px;
            font-weight: 700;
            color: var(--navy);
            margin: 15px 0;
        }
        .interest-total {
            font-size: 14px;
            color: var(--danger);
            margin: 10px 0;
        }
        .total-payment {
            font-size: 14px;
            color: var(--success);
            margin-bottom: 15px;
        }
        .tenure-card .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
        }

        /* Comparison Chart */
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
        .chart-bars {
            display: flex;
            align-items: flex-end;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
            padding: 20px 0;
        }
        .bar-item {
            text-align: center;
            width: 100px;
        }
        .bar-label {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .bar-emi {
            background: var(--orange);
            height: var(--height);
            width: 50px;
            margin: 0 auto;
            border-radius: 10px 10px 0 0;
            transition: height 0.3s ease;
        }
        .bar-interest {
            background: var(--danger);
            height: var(--height);
            width: 50px;
            margin: 0 auto;
            border-radius: 10px 10px 0 0;
            transition: height 0.3s ease;
        }
        .bar-value {
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            color: var(--navy);
        }

        /* Summary Section */
        .summary-section {
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: var(--radius-lg);
            padding: 30px;
            margin-top: 20px;
        }
        .summary-section h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .summary-card {
            background: var(--white);
            padding: 20px;
            border-radius: 16px;
            text-align: center;
        }
        .summary-card i {
            font-size: 28px;
            color: var(--orange);
            margin-bottom: 8px;
        }
        .summary-card .label {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 5px;
        }
        .summary-card .value {
            font-size: 20px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
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
            .double-group { grid-template-columns: 1fr; gap: 16px; }
            .summary-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .input-card { padding: 24px; }
            .calculator-header h1 { font-size: 32px; }
            .summary-grid { grid-template-columns: 1fr; }
            .bar-item { width: 70px; }
            .bar-emi, .bar-interest { width: 35px; }
        }
    </style>
</head>
<body>



<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-calendar-alt"></i>
            <span>Loan Tenure Calculator</span>
        </div>
        <h1>Find Your Perfect Loan Tenure</h1>
        <p>Compare how different tenures affect your EMI, total interest, and overall loan cost</p>
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
    </div>

    <!-- Tenure Slider -->
    <div class="tenure-slider-section">
        <h3><i class="fas fa-sliders-h"></i> Select Your Preferred Tenure Range</h3>
        <div class="slider-container">
            <div class="slider-labels">
                <span>1 Year</span>
                <span>5 Years</span>
                <span>10 Years</span>
                <span>15 Years</span>
                <span>20 Years</span>
                <span>25 Years</span>
                <span>30 Years</span>
            </div>
            <input type="range" id="tenureSlider" min="1" max="30" step="1" value="5">
            <div style="text-align: center; margin-top: 15px;">
                <span style="font-size: 18px; font-weight: 700; color: var(--orange);" id="selectedTenure">5</span>
                <span style="font-size: 14px;"> Years</span>
            </div>
        </div>
    </div>

    <!-- Tenure Comparison Cards -->
    <div class="comparison-grid" id="comparisonGrid">
        <!-- Will be populated by JavaScript -->
    </div>

    <!-- Chart Section -->
    <div class="chart-section">
        <h3><i class="fas fa-chart-bar"></i> EMI vs Interest Comparison</h3>
        <div class="chart-bars" id="chartBars">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <h3><i class="fas fa-lightbulb"></i> Key Insights</h3>
        <div class="summary-grid">
            <div class="summary-card">
                <i class="fas fa-chart-line"></i>
                <div class="label">Best for Lower EMI</div>
                <div class="value" id="bestLowerEmi">-</div>
            </div>
            <div class="summary-card">
                <i class="fas fa-coins"></i>
                <div class="label">Best for Lower Interest</div>
                <div class="value" id="bestLowerInterest">-</div>
            </div>
            <div class="summary-card">
                <i class="fas fa-balance-scale"></i>
                <div class="label">Balanced Tenure</div>
                <div class="value" id="balancedTenure">-</div>
            </div>
            <div class="summary-card">
                <i class="fas fa-hand-holding-usd"></i>
                <div class="label">Your EMI Capacity</div>
                <div class="value" id="emiCapacity">-</div>
            </div>
        </div>
    </div>

    <!-- Formula Section -->
    <div class="formula-section">
        <h3><i class="fas fa-square-root-alt"></i> How Tenure Affects Your Loan</h3>
        <div class="formula-box">
            <p>EMI = P × r × (1+r)ⁿ / ((1+r)ⁿ - 1)</p>
            <div class="formula-desc">As tenure (n) increases, EMI decreases but total interest increases significantly.</div>
        </div>
        <div class="formula-box">
            <p>Key Insights:</p>
            <div class="formula-desc">
                • <strong>Short Tenure (1-5 years):</strong> Higher EMI but lower total interest. Best for those who can afford higher payments.<br>
                • <strong>Medium Tenure (6-15 years):</strong> Balanced approach. Moderate EMI and moderate total interest.<br>
                • <strong>Long Tenure (16-30 years):</strong> Lower EMI but significantly higher total interest. Best for those wanting affordable monthly payments.
            </div>
        </div>
        <div class="formula-note" style="background: #FEF3E8; padding: 16px; border-radius: 12px;">
            <i class="fas fa-lightbulb"></i> <strong>Pro Tip:</strong> If you can afford higher EMIs, choose a shorter tenure to save lakhs in interest. You can always make partial prepayments to reduce tenure and interest.
        </div>
    </div>
</div>

<?php include('../../../includes/footer.php'); ?>

<script>
    // DOM Elements
    const loanAmountInput = document.getElementById('loanAmount');
    const interestRateInput = document.getElementById('interestRate');
    const tenureSlider = document.getElementById('tenureSlider');
    const selectedTenureSpan = document.getElementById('selectedTenure');
    const comparisonGrid = document.getElementById('comparisonGrid');
    const chartBars = document.getElementById('chartBars');
    const bestLowerEmiSpan = document.getElementById('bestLowerEmi');
    const bestLowerInterestSpan = document.getElementById('bestLowerInterest');
    const balancedTenureSpan = document.getElementById('balancedTenure');
    const emiCapacitySpan = document.getElementById('emiCapacity');

    // Tenures to compare (short, selected, long)
    let tenures = [3, 5, 7, 10, 15, 20];
    
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

    function calculateEMI(principal, annualRate, months) {
        if (principal <= 0 || months <= 0) return 0;
        const monthlyRate = annualRate / 12 / 100;
        if (monthlyRate === 0) return principal / months;
        const emi = principal * monthlyRate * Math.pow(1 + monthlyRate, months) / (Math.pow(1 + monthlyRate, months) - 1);
        return emi;
    }

    function calculateLoanDetails(principal, annualRate, years) {
        const months = years * 12;
        const emi = calculateEMI(principal, annualRate, months);
        const totalPayment = emi * months;
        const totalInterest = totalPayment - principal;
        return { emi, totalInterest, totalPayment, months };
    }

    function getRecommendedTenure(tenureData) {
        // Find tenure where EMI is around 20-25% of the "balanced" point
        const emis = tenureData.map(d => d.emi);
        const interests = tenureData.map(d => d.totalInterest);
        
        // Find where interest growth starts accelerating rapidly
        let bestBalance = tenureData[0];
        for (let i = 1; i < tenureData.length; i++) {
            const prev = tenureData[i-1];
            const curr = tenureData[i];
            const interestIncrease = curr.totalInterest - prev.totalInterest;
            const emiDecrease = prev.emi - curr.emi;
            
            if (interestIncrease > 50000 && emiDecrease < 2000) {
                bestBalance = prev;
                break;
            }
        }
        return bestBalance.years;
    }

    function updateCalculations() {
        const principal = parseFloat(loanAmountInput.value) || 1000000;
        const annualRate = parseFloat(interestRateInput.value) || 10.5;
        const selectedYears = parseInt(tenureSlider.value);
        
        selectedTenureSpan.innerText = selectedYears;
        
        // Update tenures array with selected tenure
        let displayTenures = [3, 5, 7, 10, 15, 20];
        if (!displayTenures.includes(selectedYears) && selectedYears !== 3 && selectedYears !== 5 && selectedYears !== 7 && selectedYears !== 10 && selectedYears !== 15 && selectedYears !== 20) {
            displayTenures = [3, 5, selectedYears, 10, 15, 20].sort((a,b) => a-b);
            displayTenures = [...new Set(displayTenures)];
        }
        
        const tenureData = displayTenures.map(years => {
            const { emi, totalInterest, totalPayment, months } = calculateLoanDetails(principal, annualRate, years);
            return { years, emi, totalInterest, totalPayment, months };
        });
        
        // Update comparison cards
        comparisonGrid.innerHTML = tenureData.map(data => {
            const isRecommended = data.years === getRecommendedTenure(tenureData);
            return `
                <div class="tenure-card ${isRecommended ? 'recommended' : ''}">
                    ${isRecommended ? '<div class="recommended-badge">⭐ Recommended</div>' : ''}
                    <div class="tenure-value">${data.years} Years</div>
                    <div class="tenure-label">(${data.months} months)</div>
                    <div class="emi-amount">${formatIndianCurrency(data.emi)}<span style="font-size: 12px;"> /month</span></div>
                    <div class="interest-total">Total Interest: ${formatIndianCurrency(data.totalInterest)}</div>
                    <div class="total-payment">Total Payment: ${formatIndianCurrency(data.totalPayment)}</div>
                    <div class="detail-row"><span>Principal</span><span>${formatIndianCurrency(principal)}</span></div>
                    <div class="detail-row"><span>Interest % of Principal</span><span>${((data.totalInterest/principal)*100).toFixed(1)}%</span></div>
                </div>
            `;
        }).join('');
        
        // Update chart
        const maxEmi = Math.max(...tenureData.map(d => d.emi));
        const maxInterest = Math.max(...tenureData.map(d => d.totalInterest));
        
        chartBars.innerHTML = tenureData.map(data => {
            const emiHeight = (data.emi / maxEmi) * 150;
            const interestHeight = (data.totalInterest / maxInterest) * 100;
            return `
                <div class="bar-item">
                    <div class="bar-label">${data.years}Y</div>
                    <div class="bar-emi" style="height: ${emiHeight}px; --height: ${emiHeight}px;"></div>
                    <div class="bar-value">${formatIndianCurrency(data.emi)}</div>
                    <div style="margin-top: 15px;">
                        <div class="bar-interest" style="height: ${interestHeight}px; background: var(--danger); --height: ${interestHeight}px;"></div>
                        <div class="bar-value" style="font-size: 10px;">${formatIndianCurrency(data.totalInterest)}</div>
                    </div>
                </div>
            `;
        }).join('');
        
        // Update summary
        const lowestEmiTenure = tenureData.reduce((min, curr) => curr.emi < min.emi ? curr : min, tenureData[0]);
        const lowestInterestTenure = tenureData.reduce((min, curr) => curr.totalInterest < min.totalInterest ? curr : min, tenureData[0]);
        const balancedTenureValue = getRecommendedTenure(tenureData);
        const balancedData = tenureData.find(d => d.years === balancedTenureValue);
        
        bestLowerEmiSpan.innerText = `${lowestEmiTenure.years} Years`;
        bestLowerInterestSpan.innerText = `${lowestInterestTenure.years} Years`;
        balancedTenureSpan.innerText = balancedData ? `${balancedData.years} Years` : '5 Years';
        
        // Calculate EMI capacity (assuming 40% of income - show as example)
        const suggestedIncome = (lowestEmiTenure.emi / 0.4);
        emiCapacitySpan.innerText = formatIndianCurrency(suggestedIncome);
    }
    
    // Event Listeners
    loanAmountInput.addEventListener('input', updateCalculations);
    interestRateInput.addEventListener('input', updateCalculations);
    tenureSlider.addEventListener('input', updateCalculations);
    
    // Update slider background
    function updateSliderBackground() {
        const slider = tenureSlider;
        const val = (slider.value - slider.min) / (slider.max - slider.min) * 100;
        slider.style.background = `linear-gradient(90deg, var(--orange) 0%, var(--orange) ${val}%, var(--border) ${val}%, var(--border) 100%)`;
    }
    tenureSlider.addEventListener('input', updateSliderBackground);
    
    // Initial calculations
    updateSliderBackground();
    updateCalculations();
</script>
</body>
</html>