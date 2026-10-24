<?php
// FILE: personal-loan-eligibility.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Personal Loan Eligibility Calculator – Check Your Qualification</title>
    <meta name="description" content="Check your personal loan eligibility instantly. Calculate how much personal loan you can get based on your salary, credit score, and existing obligations.">
    <meta name="keywords" content="personal loan eligibility, personal loan calculator, check personal loan eligibility, salary loan eligibility, LOGINTOLOANS">
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
            max-width: 1100px;
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
            flex-wrap: wrap;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            background: #FAFBFD;
            padding: 10px 20px;
            border-radius: 50px;
            border: 1.5px solid var(--border);
            transition: all 0.25s ease;
        }
        .radio-option:hover {
            border-color: var(--orange);
            background: #FFF9F5;
        }
        .radio-option.selected {
            border-color: var(--orange);
            background: linear-gradient(135deg, #FFF9F5, #FFF5ED);
        }
        .radio-option input[type="radio"] {
            width: 16px;
            height: 16px;
            accent-color: var(--orange);
            cursor: pointer;
            margin: 0;
            padding: 0;
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
            font-size: 20px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }
        .result-value small {
            font-size: 10px;
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
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
        }
        .eligibility-details li:last-child { border-bottom: none; }
        .eligibility-details li i.fa-check-circle { color: var(--success); font-size: 14px; }
        .eligibility-details li i.fa-times-circle { color: var(--danger); font-size: 14px; }
        .eligibility-details li i.fa-chart-line { color: var(--orange); font-size: 14px; }
        .eligibility-details li i.fa-info-circle { color: var(--info); font-size: 14px; }

        /* Bank Offers Section */
        .bank-offers {
            margin-top: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 20px;
        }
        .bank-offers h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .bank-offers h4 i { color: var(--orange); }
        .bank-list {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .bank-item {
            background: var(--white);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 500;
            color: var(--navy);
            box-shadow: var(--shadow-sm);
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
        }
        @media (max-width: 768px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .calculator-card { padding: 24px; }
            .double-group, .triple-group { grid-template-columns: 1fr; gap: 16px; margin-bottom: 20px; }
            .result-grid { grid-template-columns: 1fr; }
            .calculator-header h1 { font-size: 32px; }
            .radio-group { flex-direction: column; gap: 10px; }
            .radio-option { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>



<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-hand-holding-usd"></i>
            <span>Personal Loan Eligibility Checker</span>
        </div>
        <h1>How Much Personal Loan Can You Get?</h1>
        <p>Check your eligibility instantly based on your salary, credit score, and existing obligations</p>
    </div>

    <div class="calculator-card">
        <!-- Monthly Income / Salary -->
        <div class="form-group">
            <label><i class="fas fa-rupee-sign"></i> MONTHLY SALARY / INCOME (₹)</label>
            <div class="input-prefix">
                <span class="prefix-icon">₹</span>
                <input type="number" id="monthlySalary" placeholder="Enter your monthly salary" value="50000" step="5000">
            </div>
        </div>

        <!-- Employment Type & City Category -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-briefcase"></i> EMPLOYMENT TYPE</label>
                <div class="radio-group" id="employmentGroup">
                    <div class="radio-option selected" data-value="salaried">
                        <input type="radio" name="employmentType" id="salaried" value="salaried" checked>
                        <label for="salaried">Salaried</label>
                    </div>
                    <div class="radio-option" data-value="self_employed">
                        <input type="radio" name="employmentType" id="selfEmployed" value="self_employed">
                        <label for="selfEmployed">Self-Employed</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-city"></i> CITY CATEGORY</label>
                <select id="cityCategory">
                    <option value="metro">Metro (Mumbai, Delhi, Bangalore, Chennai, Kolkata, Hyderabad, Pune)</option>
                    <option value="tier1" selected>Tier 1 (Ahmedabad, Surat, Lucknow, Jaipur, etc.)</option>
                    <option value="tier2">Tier 2 (Nagpur, Indore, Bhopal, Coimbatore, etc.)</option>
                    <option value="tier3">Tier 3 & Other Cities</option>
                </select>
            </div>
        </div>

        <!-- Credit Score & Work Experience -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-chart-line"></i> CIBIL / CREDIT SCORE</label>
                <select id="creditScore">
                    <option value="excellent">Excellent (750+)</option>
                    <option value="good" selected>Good (700-749)</option>
                    <option value="average">Average (650-699)</option>
                    <option value="poor">Poor (600-649)</option>
                    <option value="very-poor">Very Poor (Below 600)</option>
                    <option value="no-score">No Credit Score / New to Credit</option>
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-clock"></i> WORK EXPERIENCE</label>
                <select id="workExperience">
                    <option value="0">&lt; 6 Months</option>
                    <option value="1">6 Months - 1 Year</option>
                    <option value="2" selected>1 - 3 Years</option>
                    <option value="3">3 - 5 Years</option>
                    <option value="5">5 - 10 Years</option>
                    <option value="10">10+ Years</option>
                </select>
            </div>
        </div>

        <!-- Existing EMIs & Other Obligations -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-hand-holding-usd"></i> EXISTING MONTHLY EMIs (₹)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">₹</span>
                    <input type="number" id="existingEmis" placeholder="Enter total monthly EMI payments" value="0" step="1000">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-credit-card"></i> CREDIT CARD BILLS (Monthly)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">₹</span>
                    <input type="number" id="creditCardBills" placeholder="Enter monthly credit card dues" value="0" step="1000">
                </div>
            </div>
        </div>

        <!-- Desired Loan Amount & Tenure -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-bullseye"></i> DESIRED LOAN AMOUNT (₹)</label>
                <div class="input-prefix">
                    <span class="prefix-icon">₹</span>
                    <input type="number" id="desiredAmount" placeholder="Enter desired loan amount" value="500000" step="50000">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> PREFERRED TENURE (YEARS)</label>
                <select id="loanTenure">
                    <option value="1">1 Year</option>
                    <option value="2">2 Years</option>
                    <option value="3" selected>3 Years</option>
                    <option value="4">4 Years</option>
                    <option value="5">5 Years</option>
                    <option value="6">6 Years</option>
                    <option value="7">7 Years</option>
                </select>
            </div>
        </div>

        <!-- Results Grid -->
        <div class="result-grid">
            <div class="result-card">
                <i class="fas fa-hand-holding-usd"></i>
                <div class="result-label">MAX ELIGIBLE AMOUNT</div>
                <div class="result-value" id="eligibleAmount">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-chart-pie"></i>
                <div class="result-label">ESTIMATED EMI</div>
                <div class="result-value" id="estimatedEmi">₹0</div>
            </div>
            <div class="result-card">
                <i class="fas fa-percent"></i>
                <div class="result-label">INTEREST RATE (p.a.)</div>
                <div class="result-value" id="interestRate">10.5%</div>
            </div>
            <div class="result-card">
                <i class="fas fa-chart-line"></i>
                <div class="result-label">ELIGIBILITY SCORE</div>
                <div class="result-value" id="eligibilityScore">0%</div>
            </div>
        </div>

        <!-- Eligibility Status Badge -->
        <div id="eligibilityBadge" class="eligibility-badge medium">
            <i class="fas fa-chart-line"></i> Enter your details to check personal loan eligibility
        </div>

        <!-- Detailed Eligibility Breakdown -->
        <div class="eligibility-details" id="eligibilityDetails">
            <h4><i class="fas fa-info-circle"></i> Eligibility Analysis</h4>
            <ul id="breakdownList">
                <li><i class="fas fa-chart-line"></i> Enter your details to see detailed analysis</li>
            </ul>
        </div>

        <!-- Bank Offers Section -->
        <div class="bank-offers" id="bankOffers" style="display: none;">
            <h4><i class="fas fa-university"></i> Recommended Banks for You</h4>
            <div class="bank-list" id="bankList"></div>
        </div>
    </div>

    <!-- Formula Explanation Section -->
    <div class="formula-section">
        <h3>
            <i class="fas fa-square-root-alt"></i>
            How Personal Loan Eligibility is Calculated
        </h3>
        
        <div class="formula-box">
            <p>FOIR Formula: Maximum EMI = Monthly Income × FOIR Ratio</p>
            <div class="formula-desc">
                <strong>FOIR (Fixed Obligation to Income Ratio)</strong> = 40-50% of monthly income<br>
                Most banks allow up to 50% of your monthly salary towards EMI payments including existing EMIs and credit card bills.
            </div>
        </div>

        <div class="formula-box">
            <p>Eligible Loan Amount Formula: P = EMI × ((1+r)ⁿ - 1) / (r × (1+r)ⁿ)</p>
            <div class="formula-desc">
                Where:<br>
                <strong>EMI</strong> = Available EMI Capacity (Max EMI - Existing Obligations)<br>
                <strong>r</strong> = Monthly Interest Rate (varies by credit score and lender)<br>
                <strong>n</strong> = Loan Tenure in Months
            </div>
        </div>

        <div class="formula-box">
            <p>Key Factors Affecting Eligibility:</p>
            <div class="formula-desc">
                • <strong>Monthly Salary:</strong> Higher salary = Higher eligibility (10x to 24x monthly income)<br>
                • <strong>Credit Score:</strong> 750+ can get lower interest rates and higher loan amounts<br>
                • <strong>Existing EMIs:</strong> Should ideally be less than 40% of monthly income<br>
                • <strong>Employment Type:</strong> Salaried professionals with stable jobs get better terms<br>
                • <strong>City Category:</strong> Metro/Tier 1 cities have higher income multiples
            </div>
        </div>

        <div class="formula-note">
            <i class="fas fa-lightbulb"></i>
            <strong>Quick Tips to Improve Eligibility:</strong> Maintain a CIBIL score above 750, keep credit utilization below 30%, clear existing debts, add a co-applicant, or opt for a longer tenure.
        </div>

        <div class="formula-note" style="background: #ECFDF5; color: #059669; margin-top: 12px;">
            <i class="fas fa-chart-line"></i>
            <strong>Example:</strong> Monthly Salary: ₹50,000 | Existing EMI: ₹5,000 | Credit Card: ₹2,000<br>
            Max EMI (50%): ₹25,000 | Available EMI: ₹18,000 | At 10.5% for 3 years: ~₹5.6 Lakhs
        </div>
    </div>
</div>

<?php include('../../../includes/footer.php'); ?>

<script>
    // DOM Elements
    const salaryInput = document.getElementById('monthlySalary');
    const employmentRadios = document.querySelectorAll('input[name="employmentType"]');
    const radioOptions = document.querySelectorAll('.radio-option');
    const citySelect = document.getElementById('cityCategory');
    const creditScoreSelect = document.getElementById('creditScore');
    const experienceSelect = document.getElementById('workExperience');
    const existingEmisInput = document.getElementById('existingEmis');
    const creditCardInput = document.getElementById('creditCardBills');
    const desiredAmountInput = document.getElementById('desiredAmount');
    const tenureSelect = document.getElementById('loanTenure');
    
    const eligibleAmountSpan = document.getElementById('eligibleAmount');
    const estimatedEmiSpan = document.getElementById('estimatedEmi');
    const interestRateSpan = document.getElementById('interestRate');
    const eligibilityScoreSpan = document.getElementById('eligibilityScore');
    const eligibilityBadge = document.getElementById('eligibilityBadge');
    const breakdownList = document.getElementById('breakdownList');
    const bankOffersDiv = document.getElementById('bankOffers');
    const bankListDiv = document.getElementById('bankList');

    // Constants
    const FOIR_RATIO = 0.50; // 50% of income for EMI (personal loan max)
    
    // Base interest rates by credit score
    const interestRates = {
        'excellent': 9.5,
        'good': 10.5,
        'average': 12.5,
        'poor': 15.0,
        'very-poor': 18.0,
        'no-score': 14.0
    };
    
    // Income multiples by city and employment
    const incomeMultiples = {
        salaried: { metro: 24, tier1: 22, tier2: 18, tier3: 15 },
        self_employed: { metro: 18, tier1: 16, tier2: 14, tier3: 12 }
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

    // Format compact
    function formatCompact(amount) {
        if (amount >= 10000000) return '₹' + (amount / 10000000).toFixed(1) + 'Cr';
        if (amount >= 100000) return '₹' + (amount / 100000).toFixed(1) + 'L';
        if (amount >= 1000) return '₹' + (amount / 1000).toFixed(0) + 'K';
        return '₹' + amount;
    }

    // Calculate EMI
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

    // Get score based on credit score
    function getCreditScoreValue(score) {
        switch(score) {
            case 'excellent': return 100;
            case 'good': return 85;
            case 'average': return 65;
            case 'poor': return 40;
            case 'very-poor': return 20;
            case 'no-score': return 50;
            default: return 50;
        }
    }

    // Get work experience score
    function getExperienceValue(exp) {
        switch(exp) {
            case '0': return 30;
            case '1': return 50;
            case '2': return 70;
            case '3': return 85;
            case '5': return 90;
            case '10': return 95;
            default: return 60;
        }
    }

    // Update radio selection styling
    function updateRadioSelection() {
        radioOptions.forEach(option => {
            const radio = option.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                option.classList.add('selected');
            } else {
                option.classList.remove('selected');
            }
        });
    }
    
    radioOptions.forEach(option => {
        option.addEventListener('click', () => {
            const radio = option.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                updateRadioSelection();
                calculateEligibility();
            }
        });
    });

    // Main eligibility calculation
    function calculateEligibility() {
        // Get values
        let monthlySalary = parseFloat(salaryInput.value);
        let existingEmis = parseFloat(existingEmisInput.value);
        let creditCardBills = parseFloat(creditCardInput.value);
        let employmentType = document.querySelector('input[name="employmentType"]:checked').value;
        let city = citySelect.value;
        let creditScore = creditScoreSelect.value;
        let workExperience = experienceSelect.value;
        let desiredAmount = parseFloat(desiredAmountInput.value);
        let tenureYears = parseFloat(tenureSelect.value);
        let months = tenureYears * 12;
        
        // Validate
        if (isNaN(monthlySalary)) monthlySalary = 0;
        if (isNaN(existingEmis)) existingEmis = 0;
        if (isNaN(creditCardBills)) creditCardBills = 0;
        if (isNaN(desiredAmount)) desiredAmount = 0;
        
        let totalObligations = existingEmis + creditCardBills;
        
        // Get interest rate based on credit score
        let interestRate = interestRates[creditScore];
        interestRateSpan.innerText = interestRate + '%';
        
        // Calculate max EMI (50% of income)
        let maxEMI = monthlySalary * FOIR_RATIO;
        let availableEMI = Math.max(0, maxEMI - totalObligations);
        
        // Calculate eligible loan amount based on available EMI
        let eligibleLoan = calculatePrincipalFromEMI(availableEMI, interestRate, months);
        eligibleLoan = Math.floor(eligibleLoan / 1000) * 1000;
        if (eligibleLoan < 0) eligibleLoan = 0;
        
        // Also calculate based on income multiple (bank method)
        let multiplier = incomeMultiples[employmentType][city];
        let incomeBasedLoan = monthlySalary * multiplier;
        incomeBasedLoan = Math.floor(incomeBasedLoan / 1000) * 1000;
        
        // Final eligible amount is min of both methods, but not less than 25000
        let finalEligible = Math.min(eligibleLoan, incomeBasedLoan);
        if (finalEligible < 25000 && monthlySalary > 0) finalEligible = Math.max(25000, eligibleLoan * 0.5);
        
        // Calculate EMI for desired amount
        let desiredEmi = calculateEMI(desiredAmount, interestRate, months);
        
        // Calculate eligibility score
        let creditScoreValue = getCreditScoreValue(creditScore);
        let experienceValue = getExperienceValue(workExperience);
        
        let incomeScore = 0;
        if (monthlySalary >= 100000) incomeScore = 100;
        else if (monthlySalary >= 75000) incomeScore = 85;
        else if (monthlySalary >= 50000) incomeScore = 70;
        else if (monthlySalary >= 35000) incomeScore = 50;
        else if (monthlySalary >= 25000) incomeScore = 35;
        else incomeScore = 20;
        
        let cityScore = city === 'metro' ? 95 : (city === 'tier1' ? 85 : (city === 'tier2' ? 75 : 65));
        let debtRatio = (totalObligations / monthlySalary) * 100;
        let debtScore = debtRatio <= 30 ? 100 : (debtRatio <= 50 ? 70 : (debtRatio <= 70 ? 40 : 20));
        
        let rawScore = (incomeScore * 0.30) + (creditScoreValue * 0.30) + (experienceValue * 0.15) + (cityScore * 0.10) + (debtScore * 0.15);
        let finalScore = Math.min(100, Math.max(0, Math.round(rawScore)));
        
        // Update UI
        eligibleAmountSpan.innerText = formatIndianCurrency(finalEligible);
        estimatedEmiSpan.innerText = formatIndianCurrency(desiredEmi);
        eligibilityScoreSpan.innerText = finalScore + '%';
        
        // Update eligibility badge
        let statusMessage = '';
        let statusClass = '';
        
        if (monthlySalary === 0) {
            statusMessage = '⚠️ Please enter your monthly salary to check eligibility';
            statusClass = 'low';
        } else if (finalScore >= 80 && finalEligible >= desiredAmount) {
            statusMessage = '✅ Excellent! You have high chances of approval. You can get up to ' + formatIndianCurrency(finalEligible);
            statusClass = 'high';
        } else if (finalScore >= 60 && finalEligible >= desiredAmount * 0.7) {
            statusMessage = '📊 Good chances! Your desired loan may be approved. Eligible amount: ' + formatIndianCurrency(finalEligible);
            statusClass = 'medium';
        } else if (finalScore >= 40) {
            statusMessage = '⚠️ Moderate chances. Try adding a co-applicant or reducing existing EMIs. Eligible: ' + formatIndianCurrency(finalEligible);
            statusClass = 'low';
        } else {
            statusMessage = '❌ Low eligibility. Work on improving credit score and clearing existing debts.';
            statusClass = 'very-low';
        }
        
        eligibilityBadge.innerHTML = '<i class="fas fa-chart-line"></i> ' + statusMessage;
        eligibilityBadge.classList.remove('high', 'medium', 'low', 'very-low');
        eligibilityBadge.classList.add(statusClass);
        
        // Update breakdown
        updateBreakdown(monthlySalary, totalObligations, maxEMI, availableEMI, creditScore, workExperience, city, employmentType, finalScore, finalEligible, desiredAmount, desiredEmi);
        
        // Update bank offers
        updateBankOffers(finalScore, creditScore, employmentType);
    }
    
    function updateBreakdown(salary, obligations, maxEMI, availableEMI, creditScore, experience, city, employmentType, score, eligible, desired, desiredEmi) {
        let creditText = '';
        switch(creditScore) {
            case 'excellent': creditText = 'Excellent (750+) - Very Favorable'; break;
            case 'good': creditText = 'Good (700-749) - Favorable'; break;
            case 'average': creditText = 'Average (650-699) - Acceptable'; break;
            case 'poor': creditText = 'Poor (600-649) - Needs Improvement'; break;
            case 'very-poor': creditText = 'Very Poor (Below 600) - High Risk'; break;
            case 'no-score': creditText = 'No Credit Score - Consider secured credit card'; break;
        }
        
        let experienceText = '';
        switch(experience) {
            case '0': experienceText = 'Less than 6 months - Limited stability'; break;
            case '1': experienceText = '6-12 months - Acceptable'; break;
            case '2': experienceText = '1-3 years - Good'; break;
            case '3': experienceText = '3-5 years - Very Good'; break;
            case '5': experienceText = '5-10 years - Excellent'; break;
            case '10': experienceText = '10+ years - Outstanding'; break;
        }
        
        let cityText = city === 'metro' ? 'Metro City - Highest multiplier' : (city === 'tier1' ? 'Tier 1 City - High multiplier' : (city === 'tier2' ? 'Tier 2 City - Standard multiplier' : 'Tier 3 City - Lower multiplier'));
        let employmentText = employmentType === 'salaried' ? 'Salaried - Higher stability & better terms' : 'Self-Employed - May need additional income proof';
        
        let debtRatio = (obligations / salary) * 100;
        let debtText = debtRatio <= 30 ? 'Healthy - Within ideal limits' : (debtRatio <= 50 ? 'Moderate - Consider reducing debt' : 'High - Affecting eligibility');
        
        let approvalStatus = desired <= eligible ? 
            '<i class="fas fa-check-circle"></i> Your desired loan amount of ' + formatIndianCurrency(desired) + ' is within eligible limit' : 
            '<i class="fas fa-times-circle"></i> Desired amount ' + formatIndianCurrency(desired) + ' exceeds eligible limit of ' + formatIndianCurrency(eligible);
        
        breakdownList.innerHTML = `
            <li><i class="fas ${salary >= 35000 ? 'fa-check-circle' : 'fa-times-circle'}"></i> Monthly Salary: ${formatIndianCurrency(salary)} ${salary >= 35000 ? '(Good)' : '(Below ideal minimum)'}</li>
            <li><i class="fas ${creditScore === 'excellent' || creditScore === 'good' ? 'fa-check-circle' : 'fa-times-circle'}"></i> Credit Score: ${creditText}</li>
            <li><i class="fas ${employmentType === 'salaried' ? 'fa-check-circle' : 'fa-chart-line'}"></i> Employment: ${employmentText}</li>
            <li><i class="fas ${experience === '3' || experience === '5' || experience === '10' ? 'fa-check-circle' : 'fa-chart-line'}"></i> Work Experience: ${experienceText}</li>
            <li><i class="fas ${city === 'metro' || city === 'tier1' ? 'fa-check-circle' : 'fa-chart-line'}"></i> City Category: ${cityText}</li>
            <li><i class="fas ${debtRatio <= 50 ? 'fa-check-circle' : 'fa-times-circle'}"></i> Existing Obligations: ${formatIndianCurrency(obligations)} (${debtRatio.toFixed(1)}% of income) - ${debtText}</li>
            <li><i class="fas fa-chart-line"></i> EMI Capacity: Max ${formatIndianCurrency(maxEMI)} | Available: ${formatIndianCurrency(availableEMI)}</li>
            <li><i class="fas fa-chart-line"></i> ${approvalStatus}</li>
            <li><i class="fas fa-chart-line"></i> Monthly EMI for desired amount: ${formatIndianCurrency(desiredEmi)} (${((desiredEmi/salary)*100).toFixed(1)}% of income)</li>
            <li><i class="fas fa-chart-line"></i> Overall Eligibility Score: ${score}%</li>
        `;
    }
    
    function updateBankOffers(score, creditScore, employmentType) {
        const isHighEligible = score >= 70 && (creditScore === 'excellent' || creditScore === 'good');
        const isMediumEligible = score >= 50 && creditScore !== 'very-poor';
        
        if (isHighEligible) {
            bankOffersDiv.style.display = 'block';
            bankListDiv.innerHTML = `
                <div class="bank-item">🏦 HDFC Bank - Up to ₹40L @ 9.5%</div>
                <div class="bank-item">🏦 ICICI Bank - Up to ₹35L @ 10.0%</div>
                <div class="bank-item">🏦 SBI - Up to ₹30L @ 9.8%</div>
                <div class="bank-item">🏦 Axis Bank - Up to ₹35L @ 10.2%</div>
                <div class="bank-item">🏦 Kotak Mahindra - Up to ₹30L @ 10.5%</div>
            `;
        } else if (isMediumEligible) {
            bankOffersDiv.style.display = 'block';
            bankListDiv.innerHTML = `
                <div class="bank-item">🏦 Bajaj Finserv - Up to ₹25L @ 11.5%</div>
                <div class="bank-item">🏦 Tata Capital - Up to ₹20L @ 12.0%</div>
                <div class="bank-item">🏦 IDFC First Bank - Up to ₹20L @ 11.5%</div>
                <div class="bank-item">🏦 Yes Bank - Up to ₹15L @ 12.5%</div>
            `;
        } else {
            bankOffersDiv.style.display = 'block';
            bankListDiv.innerHTML = `
                <div class="bank-item">🏦 Consider secured loan against FD</div>
                <div class="bank-item">🏦 Gold Loan options available</div>
                <div class="bank-item">🏦 Improve credit score for better offers</div>
            `;
        }
    }
    
    // Event Listeners
    salaryInput.addEventListener('input', calculateEligibility);
    existingEmisInput.addEventListener('input', calculateEligibility);
    creditCardInput.addEventListener('input', calculateEligibility);
    desiredAmountInput.addEventListener('input', calculateEligibility);
    citySelect.addEventListener('change', calculateEligibility);
    creditScoreSelect.addEventListener('change', calculateEligibility);
    experienceSelect.addEventListener('change', calculateEligibility);
    tenureSelect.addEventListener('change', calculateEligibility);
    
    // Initial calculation
    updateRadioSelection();
    calculateEligibility();
</script>
</body>
</html>