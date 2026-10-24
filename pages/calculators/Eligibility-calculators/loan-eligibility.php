<?php
// FILE: loan-eligibility.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Loan Eligibility Calculator – Check Your Loan Qualification</title>
    <meta name="description" content="Check your personal loan eligibility based on your income, employment type, credit score, and existing loans. Instant results with LOGINTOLOANS.">
    <meta name="keywords" content="loan eligibility calculator, personal loan eligibility, check loan qualification, loan eligibility checker, LOGINTOLOANS">
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
            max-width: 1000px;
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

        .triple-group {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 28px;
        }

        /* Radio Group Styles */
        .radio-group {
            display: flex;
            gap: 20px;
            padding: 8px 0;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: var(--orange);
            cursor: pointer;
        }
        .radio-option label {
            margin-bottom: 0;
            text-transform: none;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-body);
            cursor: pointer;
        }

        /* Results Grid */
        .result-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
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
            font-size: 22px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }
        .result-value small {
            font-size: 12px;
            font-weight: 500;
        }

        .eligibility-badge {
            background: var(--navy);
            color: white;
            border-radius: 40px;
            padding: 16px 24px;
            text-align: center;
            font-weight: 600;
            font-size: 15px;
            margin-top: 16px;
        }
        .eligibility-badge.high { background: linear-gradient(135deg, var(--success), #059669); }
        .eligibility-badge.medium { background: linear-gradient(135deg, var(--orange), var(--orange-dark)); }
        .eligibility-badge.low { background: linear-gradient(135deg, #6B7280, #4B5563); }
        .eligibility-badge.very-low { background: linear-gradient(135deg, var(--danger), #B91C1C); }
        .eligibility-badge i { margin-right: 8px; }

        .eligibility-details {
            margin-top: 20px;
            padding: 20px;
            background: #F8FAFF;
            border-radius: 20px;
            border-left: 4px solid var(--orange);
        }
        .eligibility-details h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
        }
        .eligibility-details ul {
            list-style: none;
            padding-left: 0;
        }
        .eligibility-details li {
            font-size: 13px;
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
        }
        .eligibility-details li:last-child { border-bottom: none; }
        .eligibility-details li i.fa-check-circle { color: var(--success); font-size: 14px; }
        .eligibility-details li i.fa-times-circle { color: var(--danger); font-size: 14px; }
        .eligibility-details li i.fa-chart-line { color: var(--orange); font-size: 14px; }

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

        @media (max-width: 768px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .calculator-card { padding: 24px; }
            .double-group, .triple-group { grid-template-columns: 1fr; gap: 16px; margin-bottom: 20px; }
            .result-grid { grid-template-columns: 1fr; }
            .calculator-header h1 { font-size: 32px; }
            .radio-group { flex-direction: column; gap: 12px; }
        }
    </style>
</head>
<body>


<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-clipboard-list"></i>
            <span>Instant Eligibility Check</span>
        </div>
        <h1>Check Your Loan Eligibility</h1>
        <p>Enter your details below to find out if you qualify for a personal loan</p>
    </div>

    <div class="calculator-card">
        <!-- Monthly Income -->
        <div class="form-group">
            <label><i class="fas fa-rupee-sign"></i> MONTHLY INCOME (₹)</label>
            <div class="input-prefix">
                <span class="prefix-icon">₹</span>
                <input type="number" id="monthlyIncome" placeholder="Enter your monthly income" value="50000" step="5000">
            </div>
        </div>

        <!-- Employment Type -->
        <div class="form-group">
            <label><i class="fas fa-briefcase"></i> EMPLOYMENT TYPE</label>
            <div class="radio-group">
                <div class="radio-option">
                    <input type="radio" name="employmentType" id="salaried" value="salaried" checked>
                    <label for="salaried">Salaried</label>
                </div>
                <div class="radio-option">
                    <input type="radio" name="employmentType" id="selfEmployed" value="self_employed">
                    <label for="selfEmployed">Self-Employed</label>
                </div>
            </div>
        </div>

        <!-- Credit Score & Work Experience -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-chart-line"></i> CREDIT SCORE (CIBIL)</label>
                <select id="creditScore">
                    <option value="excellent">Excellent (750+)</option>
                    <option value="good" selected>Good (700-749)</option>
                    <option value="average">Average (650-699)</option>
                    <option value="poor">Poor (600-649)</option>
                    <option value="very-poor">Very Poor (Below 600)</option>
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-clock"></i> WORK EXPERIENCE (YEARS)</label>
                <select id="workExperience">
                    <option value="0">&lt; 1 Year</option>
                    <option value="1">1 - 2 Years</option>
                    <option value="2" selected>2 - 5 Years</option>
                    <option value="5">5 - 10 Years</option>
                    <option value="10">10+ Years</option>
                </select>
            </div>
        </div>

        <!-- Existing EMIs & Age -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-hand-holding-usd"></i> EXISTING MONTHLY EMIs (₹)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">₹</span>
                    <input type="number" id="existingEmis" placeholder="Enter existing EMI amount" value="0" step="1000">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> AGE (YEARS)</label>
                <select id="age">
                    <option value="21">21 - 25 Years</option>
                    <option value="26" selected>26 - 35 Years</option>
                    <option value="36">36 - 45 Years</option>
                    <option value="46">46 - 55 Years</option>
                    <option value="56">56 - 60 Years</option>
                </select>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="result-grid">
            <div class="result-card">
                <i class="fas fa-hand-holding-usd"></i>
                <div class="result-label">MAX ELIGIBLE LOAN</div>
                <div class="result-value" id="eligibleLoan">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-percent"></i>
                <div class="result-label">ELIGIBILITY SCORE</div>
                <div class="result-value" id="eligibilityScore">0%</div>
            </div>
            <div class="result-card">
                <i class="fas fa-chart-pie"></i>
                <div class="result-label">MAX EMI CAPACITY</div>
                <div class="result-value" id="maxEmiCapacity">₹0</div>
            </div>
        </div>

        <!-- Eligibility Status Badge -->
        <div id="eligibilityBadge" class="eligibility-badge medium">
            <i class="fas fa-chart-line"></i> Enter details to check your eligibility
        </div>

        <!-- Detailed Eligibility Breakdown -->
        <div class="eligibility-details" id="eligibilityDetails">
            <h4><i class="fas fa-info-circle"></i> Eligibility Breakdown</h4>
            <ul id="breakdownList">
                <li><i class="fas fa-chart-line"></i> Enter your details to see analysis</li>
            </ul>
        </div>
    </div>

    <!-- Formula Explanation Section -->
    <div class="formula-section">
        <h3>
            <i class="fas fa-square-root-alt"></i>
            How Loan Eligibility is Calculated
        </h3>
        
        <div class="formula-box">
            <p>FOIR Formula: Maximum EMI = Monthly Income × FOIR Ratio</p>
            <div class="formula-desc">
                <strong>FOIR (Fixed Obligation to Income Ratio)</strong> = 40-50% of monthly income<br>
                Banks typically allow up to 50% of your monthly income towards EMI payments including existing loans.
            </div>
        </div>

        <div class="formula-box">
            <p>Eligible Loan Amount = EMI × ((1+r)ⁿ - 1) / (r × (1+r)ⁿ)</p>
            <div class="formula-desc">
                Where:<br>
                <strong>EMI</strong> = Available EMI Capacity (Max EMI - Existing EMIs)<br>
                <strong>r</strong> = Monthly Interest Rate (10.5% ÷ 12 ÷ 100)<br>
                <strong>n</strong> = Loan Tenure in Months (standard 5 years = 60 months)
            </div>
        </div>

        <div class="formula-box">
            <p>Eligibility Score Factors:</p>
            <div class="formula-desc">
                • <strong>Income Level:</strong> Higher income = Better score<br>
                • <strong>Credit Score:</strong> 750+ adds up to 25% to score<br>
                • <strong>Employment Type:</strong> Salaried professionals get higher weightage<br>
                • <strong>Work Experience:</strong> Stable employment history improves score<br>
                • <strong>Existing Obligations:</strong> Lower EMIs = Better eligibility
            </div>
        </div>

        <div class="formula-note">
            <i class="fas fa-lightbulb"></i>
            <strong>Tip:</strong> A credit score above 750, stable income, and low existing EMIs significantly improve your loan eligibility. You can also increase your eligibility by adding a co-applicant or opting for a longer tenure.
        </div>

        <div class="formula-note" style="background: #ECFDF5; color: #059669; margin-top: 12px;">
            <i class="fas fa-chart-line"></i>
            <strong>Example:</strong> Monthly Income: ₹50,000 | Existing EMI: ₹5,000 | Max EMI (45%): ₹22,500<br>
            Available EMI: ₹17,500 | At 10.5% for 5 years, Eligible Loan: ~₹8.2 Lakhs
        </div>
    </div>
</div>

<?php include('../../../includes/footer.php'); ?>

<script>
    // DOM Elements
    const incomeInput = document.getElementById('monthlyIncome');
    const employmentRadios = document.querySelectorAll('input[name="employmentType"]');
    const creditScoreSelect = document.getElementById('creditScore');
    const workExperienceSelect = document.getElementById('workExperience');
    const existingEmisInput = document.getElementById('existingEmis');
    const ageSelect = document.getElementById('age');
    
    const eligibleLoanSpan = document.getElementById('eligibleLoan');
    const eligibilityScoreSpan = document.getElementById('eligibilityScore');
    const maxEmiCapacitySpan = document.getElementById('maxEmiCapacity');
    const eligibilityBadge = document.getElementById('eligibilityBadge');
    const breakdownList = document.getElementById('breakdownList');

    // Constants
    const FOIR_RATIO = 0.45; // 45% of income for EMI
    const STANDARD_INTEREST = 10.5; // 10.5% per annum
    const STANDARD_TENURE_YEARS = 5;
    const MONTHS = STANDARD_TENURE_YEARS * 12;

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

    // Get score based on credit score selection
    function getCreditScoreValue(score) {
        switch(score) {
            case 'excellent': return 95;
            case 'good': return 80;
            case 'average': return 60;
            case 'poor': return 40;
            case 'very-poor': return 20;
            default: return 50;
        }
    }

    // Get score based on work experience
    function getExperienceValue(exp) {
        switch(exp) {
            case '0': return 30;
            case '1': return 50;
            case '2': return 70;
            case '5': return 85;
            case '10': return 95;
            default: return 60;
        }
    }

    // Get score based on age
    function getAgeValue(age) {
        const ageNum = parseInt(age);
        if (ageNum >= 26 && ageNum <= 45) return 90;
        if (ageNum >= 21 && ageNum <= 25) return 70;
        if (ageNum >= 46 && ageNum <= 55) return 65;
        return 50;
    }

    // Get employment type multiplier
    function getEmploymentMultiplier(employmentType) {
        return employmentType === 'salaried' ? 1.0 : 0.85;
    }

    // Main eligibility calculation
    function calculateEligibility() {
        // Get values
        let monthlyIncome = parseFloat(incomeInput.value);
        let existingEmis = parseFloat(existingEmisInput.value);
        let employmentType = document.querySelector('input[name="employmentType"]:checked').value;
        let creditScore = creditScoreSelect.value;
        let workExperience = workExperienceSelect.value;
        let age = ageSelect.value;
        
        // Validate
        if (isNaN(monthlyIncome)) monthlyIncome = 0;
        if (isNaN(existingEmis)) existingEmis = 0;
        
        // Calculate Max EMI capacity (45% of income)
        let maxEMI = monthlyIncome * FOIR_RATIO;
        let availableEMI = Math.max(0, maxEMI - existingEmis);
        
        // Calculate eligible loan amount
        let eligibleLoan = calculatePrincipalFromEMI(availableEMI, STANDARD_INTEREST, MONTHS);
        eligibleLoan = Math.floor(eligibleLoan / 1000) * 1000;
        if (eligibleLoan < 0) eligibleLoan = 0;
        
        // Calculate eligibility score components
        let incomeScore = 0;
        if (monthlyIncome >= 100000) incomeScore = 100;
        else if (monthlyIncome >= 75000) incomeScore = 85;
        else if (monthlyIncome >= 50000) incomeScore = 70;
        else if (monthlyIncome >= 35000) incomeScore = 50;
        else if (monthlyIncome >= 25000) incomeScore = 35;
        else incomeScore = 20;
        
        let creditScoreValue = getCreditScoreValue(creditScore);
        let experienceValue = getExperienceValue(workExperience);
        let ageValue = getAgeValue(age);
        let employmentMultiplier = getEmploymentMultiplier(employmentType);
        
        // Weighted score calculation
        let rawScore = (incomeScore * 0.35) + (creditScoreValue * 0.30) + (experienceValue * 0.20) + (ageValue * 0.15);
        let finalScore = Math.min(100, Math.max(0, rawScore * employmentMultiplier));
        
        // Adjust score based on debt-to-income ratio
        let debtToIncomeRatio = (existingEmis / monthlyIncome) * 100;
        if (debtToIncomeRatio > 50) finalScore *= 0.5;
        else if (debtToIncomeRatio > 40) finalScore *= 0.7;
        else if (debtToIncomeRatio > 30) finalScore *= 0.85;
        
        finalScore = Math.round(finalScore);
        
        // Update UI
        eligibleLoanSpan.innerText = formatIndianCurrency(eligibleLoan);
        eligibilityScoreSpan.innerText = finalScore + '%';
        maxEmiCapacitySpan.innerText = formatIndianCurrency(maxEMI);
        
        // Update eligibility badge
        let statusMessage = '';
        let statusClass = '';
        
        if (monthlyIncome === 0) {
            statusMessage = '⚠️ Please enter your monthly income to check eligibility';
            statusClass = 'low';
        } else if (finalScore >= 80 && eligibleLoan > 50000) {
            statusMessage = '✅ Excellent! You have high chances of loan approval. Eligible amount: ' + formatIndianCurrency(eligibleLoan);
            statusClass = 'high';
        } else if (finalScore >= 60 && eligibleLoan > 25000) {
            statusMessage = '📊 Good! You have moderate chances. Consider improving credit score or reducing existing EMIs.';
            statusClass = 'medium';
        } else if (finalScore >= 40) {
            statusMessage = '⚠️ Low Eligibility. Try adding a co-applicant or improving your credit score.';
            statusClass = 'low';
        } else {
            statusMessage = '❌ Very Low Eligibility. Work on improving credit score, clearing existing debts, and increasing income.';
            statusClass = 'very-low';
        }
        
        eligibilityBadge.innerHTML = '<i class="fas fa-chart-line"></i> ' + statusMessage;
        eligibilityBadge.classList.remove('high', 'medium', 'low', 'very-low');
        eligibilityBadge.classList.add(statusClass);
        
        // Update breakdown list
        updateBreakdownList(monthlyIncome, existingEmis, maxEMI, availableEMI, creditScore, workExperience, age, employmentType, finalScore);
    }
    
    function updateBreakdownList(income, existingEmis, maxEMI, availableEMI, creditScore, experience, age, employmentType, score) {
        let creditScoreText = '';
        switch(creditScore) {
            case 'excellent': creditScoreText = 'Excellent (750+) - Very Favorable'; break;
            case 'good': creditScoreText = 'Good (700-749) - Favorable'; break;
            case 'average': creditScoreText = 'Average (650-699) - Acceptable'; break;
            case 'poor': creditScoreText = 'Poor (600-649) - Needs Improvement'; break;
            case 'very-poor': creditScoreText = 'Very Poor (Below 600) - High Risk'; break;
        }
        
        let employmentText = employmentType === 'salaried' ? 'Salaried - Higher stability' : 'Self-Employed - Additional income proof may be required';
        
        let experienceText = '';
        switch(experience) {
            case '0': experienceText = 'Less than 1 year - Limited stability'; break;
            case '1': experienceText = '1-2 years - Acceptable'; break;
            case '2': experienceText = '2-5 years - Good stability'; break;
            case '5': experienceText = '5-10 years - Very Good'; break;
            case '10': experienceText = '10+ years - Excellent'; break;
        }
        
        let ageText = '';
        let ageNum = parseInt(age);
        if (ageNum >= 26 && ageNum <= 45) ageText = 'Prime working age - Ideal';
        else if (ageNum >= 21 && ageNum <= 25) ageText = 'Young professional - Good';
        else ageText = 'Age considered - Standard';
        
        let debtRatio = (existingEmis / income) * 100;
        let debtText = debtRatio <= 30 ? 'Healthy - Low existing obligations' : (debtRatio <= 50 ? 'Moderate - Consider reducing debt' : 'High - Existing EMIs impacting eligibility');
        
        breakdownList.innerHTML = `
            <li><i class="fas ${income >= 35000 ? 'fa-check-circle' : 'fa-times-circle'}"></i> Monthly Income: ₹${income.toLocaleString('en-IN')} ${income >= 35000 ? '(Good)' : '(Consider increasing income)'}</li>
            <li><i class="fas ${creditScore === 'excellent' || creditScore === 'good' ? 'fa-check-circle' : 'fa-times-circle'}"></i> Credit Score: ${creditScoreText}</li>
            <li><i class="fas ${employmentType === 'salaried' ? 'fa-check-circle' : 'fa-chart-line'}"></i> Employment Type: ${employmentText}</li>
            <li><i class="fas ${experience === '5' || experience === '10' ? 'fa-check-circle' : 'fa-chart-line'}"></i> Work Experience: ${experienceText}</li>
            <li><i class="fas ${ageNum >= 26 && ageNum <= 45 ? 'fa-check-circle' : 'fa-chart-line'}"></i> Age: ${ageText}</li>
            <li><i class="fas ${debtRatio <= 30 ? 'fa-check-circle' : 'fa-times-circle'}"></i> Debt-to-Income Ratio: ${debtRatio.toFixed(1)}% - ${debtText}</li>
            <li><i class="fas fa-chart-line"></i> Max EMI Capacity: ${formatIndianCurrency(maxEMI)} | Available for new loan: ${formatIndianCurrency(availableEMI)}</li>
            <li><i class="fas fa-chart-line"></i> Overall Eligibility Score: ${score}%</li>
        `;
    }
    
    // Event Listeners
    incomeInput.addEventListener('input', calculateEligibility);
    existingEmisInput.addEventListener('input', calculateEligibility);
    employmentRadios.forEach(radio => radio.addEventListener('change', calculateEligibility));
    creditScoreSelect.addEventListener('change', calculateEligibility);
    workExperienceSelect.addEventListener('change', calculateEligibility);
    ageSelect.addEventListener('change', calculateEligibility);
    
    // Initial calculation
    calculateEligibility();
</script>
</body>
</html>