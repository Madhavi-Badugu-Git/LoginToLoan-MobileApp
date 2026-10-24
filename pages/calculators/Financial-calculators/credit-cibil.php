<?php
// FILE: credit-cibil-calculator.php
include('../../../includes/dbConfig.php');
include('../../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Credit Score Simulator – Check Your CIBIL Score</title>
    <meta name="description" content="Simulate your credit score based on your financial behavior. Understand what affects your CIBIL score and how to improve it with our interactive calculator.">
    <meta name="keywords" content="credit score calculator, CIBIL score simulator, credit score checker, improve credit score, LOGINTOLOANS">
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

        /* Radio & Select Styles */
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

        /* Score Display */
        .score-display {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #F8FAFF, #F1F5F9);
            border-radius: 30px;
            margin-bottom: 30px;
        }
        .score-circle {
            width: 200px;
            height: 200px;
            margin: 0 auto 20px;
            position: relative;
        }
        .score-circle svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }
        .score-circle circle {
            fill: none;
            stroke-width: 12;
            stroke-linecap: round;
        }
        .score-circle .bg-circle {
            stroke: #E2E8F0;
        }
        .score-circle .progress-circle {
            stroke: var(--orange);
            stroke-dasharray: 565.48;
            stroke-dashoffset: 565.48;
            transition: stroke-dashoffset 0.8s ease;
        }
        .score-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        .score-number {
            font-size: 48px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--navy);
        }
        .score-label {
            font-size: 12px;
            color: var(--text-muted);
        }
        .score-rating {
            margin-top: 15px;
            font-size: 18px;
            font-weight: 700;
        }
        .score-rating.excellent { color: var(--success); }
        .score-rating.good { color: #10B981; }
        .score-rating.fair { color: var(--warning); }
        .score-rating.poor { color: var(--danger); }

        /* Factors Grid */
        .factors-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 30px 0;
        }
        .factor-card {
            background: #F8FAFF;
            border-radius: 16px;
            padding: 20px;
            border-left: 4px solid var(--orange);
        }
        .factor-card h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .factor-progress {
            height: 8px;
            background: #E2E8F0;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .factor-progress-bar {
            height: 100%;
            background: var(--orange);
            border-radius: 10px;
            width: 0%;
            transition: width 0.5s ease;
        }
        .factor-score {
            font-size: 12px;
            font-weight: 600;
            color: var(--orange);
            margin-top: 8px;
        }

        /* Improvement Tips */
        .tips-section {
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 20px;
            padding: 24px;
            margin-top: 30px;
        }
        .tips-section h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tips-list {
            list-style: none;
            padding-left: 0;
        }
        .tips-list li {
            padding: 10px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(249,115,22,0.2);
            font-size: 14px;
        }
        .tips-list li:last-child { border-bottom: none; }
        .tips-list li i {
            width: 24px;
            color: var(--orange);
        }

        /* Loan Eligibility Prediction */
        .eligibility-predict {
            margin-top: 30px;
            padding: 20px;
            background: #F8FAFF;
            border-radius: 20px;
        }
        .eligibility-predict h4 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .loan-types {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .loan-type-badge {
            background: var(--white);
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border);
        }
        .loan-type-badge.approved {
            background: #ECFDF5;
            border-color: var(--success);
            color: var(--success);
        }
        .loan-type-badge.maybe {
            background: #FEF3E8;
            border-color: var(--warning);
            color: var(--warning);
        }
        .loan-type-badge.rejected {
            background: #FEF2F2;
            border-color: var(--danger);
            color: var(--danger);
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
        .formula-note {
            background: #FEF3E8;
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 13px;
            color: var(--orange-dark);
            margin-top: 16px;
        }

        @media (max-width: 900px) {
            .factors-grid { grid-template-columns: 1fr; }
            .double-group { grid-template-columns: 1fr; gap: 16px; }
        }
        @media (max-width: 768px) {
            .calculator-wrapper { margin: 100px auto 40px; padding: 0 16px; }
            .calculator-card { padding: 24px; }
            .calculator-header h1 { font-size: 32px; }
            .radio-group { flex-direction: column; gap: 10px; }
            .radio-option { width: 100%; justify-content: center; }
            .score-circle { width: 150px; height: 150px; }
            .score-number { font-size: 32px; }
        }
    </style>
</head>
<body>



<div class="calculator-wrapper" style="margin-top: 20px;">
    <div class="calculator-header">
        <div class="badge">
            <i class="fas fa-chart-line"></i>
            <span>Credit Score Simulator</span>
        </div>
        <h1>Check Your CIBIL Score</h1>
        <p>Simulate your credit score based on your financial behavior and get personalized tips to improve it</p>
    </div>

    <div class="calculator-card">
        <!-- Payment History -->
        <div class="form-group">
            <label><i class="fas fa-calendar-check"></i> PAYMENT HISTORY (Most Important - 35%)</label>
            <select id="paymentHistory">
                <option value="excellent">All payments on time - No defaults</option>
                <option value="good" selected>1-2 late payments in last 2 years</option>
                <option value="average">3-5 late payments in last 2 years</option>
                <option value="poor">6+ late payments or 30+ days delay</option>
                <option value="very-poor">Default / Settlement / Written off</option>
            </select>
        </div>

        <!-- Credit Utilization & Credit Age -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-credit-card"></i> CREDIT UTILIZATION (30%)</label>
                <select id="creditUtilization">
                    <option value="excellent" selected>&lt; 30% - Excellent utilization</option>
                    <option value="good">30% - 50% - Good</option>
                    <option value="average">50% - 70% - Average</option>
                    <option value="poor">70% - 90% - Poor</option>
                    <option value="very-poor">&gt; 90% - Very Poor</option>
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-clock"></i> CREDIT HISTORY AGE (15%)</label>
                <select id="creditAge">
                    <option value="excellent">&gt; 10 years - Excellent</option>
                    <option value="good" selected>5 - 10 years - Good</option>
                    <option value="average">2 - 5 years - Average</option>
                    <option value="poor">6 months - 2 years - Poor</option>
                    <option value="very-poor">&lt; 6 months - No history</option>
                </select>
            </div>
        </div>

        <!-- Credit Mix & Hard Inquiries -->
        <div class="double-group">
            <div class="form-group">
                <label><i class="fas fa-layer-group"></i> CREDIT MIX (10%)</label>
                <select id="creditMix">
                    <option value="excellent" selected>Mix of secured & unsecured loans (Home + Personal)</option>
                    <option value="good">Mostly secured loans (Home/Car)</option>
                    <option value="average">Only credit cards</option>
                    <option value="poor">Only unsecured loans (Personal/Consumer)</option>
                    <option value="very-poor">No credit mix / Only payday loans</option>
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-search"></i> HARD INQUIRIES (Last 6 months - 10%)</label>
                <select id="hardInquiries">
                    <option value="excellent" selected>0-1 inquiries - Excellent</option>
                    <option value="good">2-3 inquiries - Good</option>
                    <option value="average">4-5 inquiries - Average</option>
                    <option value="poor">6-7 inquiries - Poor</option>
                    <option value="very-poor">8+ inquiries - Very Poor</option>
                </select>
            </div>
        </div>

        <!-- Score Display -->
        <div class="score-display">
            <div class="score-circle">
                <svg viewBox="0 0 200 200">
                    <circle class="bg-circle" cx="100" cy="100" r="90"></circle>
                    <circle class="progress-circle" cx="100" cy="100" r="90" id="scoreProgressCircle"></circle>
                </svg>
                <div class="score-value">
                    <div class="score-number" id="creditScore">750</div>
                    <div class="score-label">CIBIL Score</div>
                </div>
            </div>
            <div class="score-rating" id="scoreRating">Good</div>
            <div class="score-description" id="scoreDescription" style="font-size: 13px; margin-top: 10px; color: var(--text-muted);"></div>
        </div>

        <!-- Factor Analysis -->
        <div class="factors-grid">
            <div class="factor-card">
                <h4><i class="fas fa-calendar-check"></i> Payment History</h4>
                <div class="factor-progress"><div class="factor-progress-bar" id="paymentProgress" style="width: 0%;"></div></div>
                <div class="factor-score" id="paymentScore">Weight: 35%</div>
            </div>
            <div class="factor-card">
                <h4><i class="fas fa-credit-card"></i> Credit Utilization</h4>
                <div class="factor-progress"><div class="factor-progress-bar" id="utilizationProgress" style="width: 0%;"></div></div>
                <div class="factor-score" id="utilizationScore">Weight: 30%</div>
            </div>
            <div class="factor-card">
                <h4><i class="fas fa-clock"></i> Credit Age</h4>
                <div class="factor-progress"><div class="factor-progress-bar" id="ageProgress" style="width: 0%;"></div></div>
                <div class="factor-score" id="ageScore">Weight: 15%</div>
            </div>
            <div class="factor-card">
                <h4><i class="fas fa-layer-group"></i> Credit Mix</h4>
                <div class="factor-progress"><div class="factor-progress-bar" id="mixProgress" style="width: 0%;"></div></div>
                <div class="factor-score" id="mixScore">Weight: 10%</div>
            </div>
            <div class="factor-card">
                <h4><i class="fas fa-search"></i> Hard Inquiries</h4>
                <div class="factor-progress"><div class="factor-progress-bar" id="inquiriesProgress" style="width: 0%;"></div></div>
                <div class="factor-score" id="inquiriesScore">Weight: 10%</div>
            </div>
        </div>

        <!-- Improvement Tips -->
        <div class="tips-section" id="tipsSection">
            <h4><i class="fas fa-lightbulb"></i> Personalized Tips to Improve Your Score</h4>
            <ul class="tips-list" id="tipsList">
                <li><i class="fas fa-check-circle"></i> Based on your inputs, here are personalized recommendations</li>
            </ul>
        </div>

        <!-- Loan Eligibility Prediction -->
        <div class="eligibility-predict">
            <h4><i class="fas fa-hand-holding-usd"></i> Loan Eligibility Prediction</h4>
            <div class="loan-types" id="loanEligibility">
                <div class="loan-type-badge">Personal Loan</div>
                <div class="loan-type-badge">Home Loan</div>
                <div class="loan-type-badge">Car Loan</div>
                <div class="loan-type-badge">Credit Card</div>
                <div class="loan-type-badge">Business Loan</div>
            </div>
        </div>
    </div>

    <!-- Formula Explanation Section -->
    <div class="formula-section">
        <h3>
            <i class="fas fa-square-root-alt"></i>
            Credit Score Calculation Factors
        </h3>
        
        <div class="formula-box">
            <p>CIBIL Score Range: 300 - 900</p>
            <div class="formula-desc">
                • <strong>Excellent (750-900):</strong> High chances of loan approval with best rates<br>
                • <strong>Good (700-749):</strong> Good chances of loan approval<br>
                • <strong>Fair (650-699):</strong> Moderate chances, may get higher interest rates<br>
                • <strong>Poor (550-649):</strong> Low chances, may need co-applicant<br>
                • <strong>Very Poor (300-549):</strong> Difficult to get approved
            </div>
        </div>

        <div class="formula-box">
            <p>Weightage Breakdown:</p>
            <div class="formula-desc">
                • <strong>Payment History (35%):</strong> Timely payments are crucial<br>
                • <strong>Credit Utilization (30%):</strong> Keep below 30% of limit<br>
                • <strong>Credit Age (15%):</strong> Longer history is better<br>
                • <strong>Credit Mix (10%):</strong> Mix of secured & unsecured loans<br>
                • <strong>Hard Inquiries (10%):</strong> Too many applications hurt score
            </div>
        </div>

        <div class="formula-note">
            <i class="fas fa-lightbulb"></i>
            <strong>Quick Tips to Improve Score:</strong> Pay all bills on time, keep credit card usage below 30%, avoid multiple loan applications, maintain a healthy mix of credit, and regularly check your credit report for errors.
        </div>
    </div>
</div>

<?php include('../../../includes/footer.php'); ?>

<script>
    // DOM Elements
    const paymentHistory = document.getElementById('paymentHistory');
    const creditUtilization = document.getElementById('creditUtilization');
    const creditAge = document.getElementById('creditAge');
    const creditMix = document.getElementById('creditMix');
    const hardInquiries = document.getElementById('hardInquiries');
    
    const creditScoreSpan = document.getElementById('creditScore');
    const scoreRatingSpan = document.getElementById('scoreRating');
    const scoreDescriptionSpan = document.getElementById('scoreDescription');
    const progressCircle = document.getElementById('scoreProgressCircle');
    
    // Factor progress bars
    const paymentProgress = document.getElementById('paymentProgress');
    const utilizationProgress = document.getElementById('utilizationProgress');
    const ageProgress = document.getElementById('ageProgress');
    const mixProgress = document.getElementById('mixProgress');
    const inquiriesProgress = document.getElementById('inquiriesProgress');
    
    const paymentScoreSpan = document.getElementById('paymentScore');
    const utilizationScoreSpan = document.getElementById('utilizationScore');
    const ageScoreSpan = document.getElementById('ageScore');
    const mixScoreSpan = document.getElementById('mixScore');
    const inquiriesScoreSpan = document.getElementById('inquiriesScore');
    
    const tipsList = document.getElementById('tipsList');
    const loanEligibility = document.getElementById('loanEligibility');

    // Score mappings
    const scoreMap = {
        payment: { excellent: 100, good: 75, average: 50, poor: 25, 'very-poor': 10 },
        utilization: { excellent: 100, good: 75, average: 50, poor: 25, 'very-poor': 10 },
        age: { excellent: 100, good: 75, average: 50, poor: 25, 'very-poor': 10 },
        mix: { excellent: 100, good: 75, average: 50, poor: 25, 'very-poor': 10 },
        inquiries: { excellent: 100, good: 75, average: 50, poor: 25, 'very-poor': 10 }
    };

    // Get score value
    function getScoreValue(category, value) {
        return scoreMap[category][value] || 50;
    }

    // Calculate total credit score
    function calculateCreditScore() {
        // Get selected values
        let paymentVal = paymentHistory.value;
        let utilizationVal = creditUtilization.value;
        let ageVal = creditAge.value;
        let mixVal = creditMix.value;
        let inquiriesVal = hardInquiries.value;
        
        // Get scores (0-100)
        let paymentScore = getScoreValue('payment', paymentVal);
        let utilizationScore = getScoreValue('utilization', utilizationVal);
        let ageScore = getScoreValue('age', ageVal);
        let mixScore = getScoreValue('mix', mixVal);
        let inquiriesScore = getScoreValue('inquiries', inquiriesVal);
        
        // Apply weights (35%, 30%, 15%, 10%, 10%)
        let weightedScore = (paymentScore * 0.35) + (utilizationScore * 0.30) + (ageScore * 0.15) + (mixScore * 0.10) + (inquiriesScore * 0.10);
        
        // Convert to 300-900 scale
        let creditScore = Math.round(300 + (weightedScore * 6));
        creditScore = Math.min(900, Math.max(300, creditScore));
        
        // Update UI
        creditScoreSpan.innerText = creditScore;
        
        // Update progress circle
        let percent = (creditScore - 300) / 6;
        let circumference = 565.48;
        let offset = circumference - (percent / 100) * circumference;
        progressCircle.style.strokeDashoffset = offset;
        
        // Update rating and description
        let rating = '';
        let ratingClass = '';
        let description = '';
        
        if (creditScore >= 750) {
            rating = 'Excellent';
            ratingClass = 'excellent';
            description = 'Excellent! You have a high credit score. You are likely to get loan approvals with the best interest rates.';
        } else if (creditScore >= 700) {
            rating = 'Good';
            ratingClass = 'good';
            description = 'Good score! Most lenders will approve your loan application. Keep up the good habits.';
        } else if (creditScore >= 650) {
            rating = 'Fair';
            ratingClass = 'fair';
            description = 'Fair score. You may get loan approval but possibly at higher interest rates. Work on improving.';
        } else if (creditScore >= 550) {
            rating = 'Poor';
            ratingClass = 'poor';
            description = 'Poor score. Loan approval may be difficult. Consider adding a co-applicant or secured loan.';
        } else {
            rating = 'Very Poor';
            ratingClass = 'poor';
            description = 'Very poor score. Focus on building credit through secured credit cards or small loans.';
        }
        
        scoreRatingSpan.innerText = rating;
        scoreRatingSpan.className = 'score-rating ' + ratingClass;
        scoreDescriptionSpan.innerText = description;
        
        // Update factor progress bars
        paymentProgress.style.width = paymentScore + '%';
        utilizationProgress.style.width = utilizationScore + '%';
        ageProgress.style.width = ageScore + '%';
        mixProgress.style.width = mixScore + '%';
        inquiriesProgress.style.width = inquiriesScore + '%';
        
        paymentScoreSpan.innerText = `Score: ${Math.round(paymentScore)}% | Weight: 35%`;
        utilizationScoreSpan.innerText = `Score: ${Math.round(utilizationScore)}% | Weight: 30%`;
        ageScoreSpan.innerText = `Score: ${Math.round(ageScore)}% | Weight: 15%`;
        mixScoreSpan.innerText = `Score: ${Math.round(mixScore)}% | Weight: 10%`;
        inquiriesScoreSpan.innerText = `Score: ${Math.round(inquiriesScore)}% | Weight: 10%`;
        
        // Update tips
        updateTips(paymentVal, utilizationVal, ageVal, mixVal, inquiriesVal, creditScore);
        
        // Update loan eligibility
        updateLoanEligibility(creditScore);
    }
    
    function updateTips(payment, utilization, age, mix, inquiries, score) {
        let tips = [];
        
        if (payment !== 'excellent') {
            tips.push('📌 Pay all EMIs and credit card bills on time. Set up auto-pay to avoid late payments.');
        }
        if (utilization !== 'excellent' && utilization !== 'good') {
            tips.push('📌 Keep credit card usage below 30% of your limit. Pay before statement generation.');
        }
        if (age === 'poor' || age === 'very-poor') {
            tips.push('📌 Maintain old credit accounts. Closing old cards can reduce your credit age.');
        }
        if (mix === 'poor' || mix === 'very-poor') {
            tips.push('📌 Add a secured loan or credit card to improve credit mix diversity.');
        }
        if (inquiries !== 'excellent' && inquiries !== 'good') {
            tips.push('📌 Avoid multiple loan applications in a short period. Each inquiry dings your score.');
        }
        
        if (score >= 750) {
            tips.push('🎉 Great job! You have an excellent credit score. Consider applying for premium credit cards.');
        } else if (score >= 700) {
            tips.push('📈 You\'re on the right track! Keep monitoring your credit report for errors.');
        } else if (score >= 650) {
            tips.push('🔄 Work on reducing credit card balances and paying all bills on time.');
        } else {
            tips.push('🚀 Start with a secured credit card or small loan to build credit history.');
        }
        
        if (tips.length === 0) {
            tips.push('✅ Excellent! You\'re doing everything right. Keep maintaining your good credit habits.');
        }
        
        tipsList.innerHTML = tips.map(tip => `<li><i class="fas fa-check-circle"></i> ${tip}</li>`).join('');
    }
    
    function updateLoanEligibility(score) {
        const loans = [
            { name: 'Personal Loan', minScore: 700 },
            { name: 'Home Loan', minScore: 650 },
            { name: 'Car Loan', minScore: 680 },
            { name: 'Credit Card', minScore: 700 },
            { name: 'Business Loan', minScore: 680 }
        ];
        
        loanEligibility.innerHTML = loans.map(loan => {
            let status = '';
            let statusClass = '';
            if (score >= loan.minScore + 50) {
                status = 'Approved';
                statusClass = 'approved';
            } else if (score >= loan.minScore) {
                status = 'Maybe';
                statusClass = 'maybe';
            } else {
                status = 'Low Chance';
                statusClass = 'rejected';
            }
            return `<div class="loan-type-badge ${statusClass}"><i class="fas ${score >= loan.minScore ? 'fa-check-circle' : 'fa-times-circle'}"></i> ${loan.name} - ${status}</div>`;
        }).join('');
    }
    
    // Radio group styling
    const radioOptions = document.querySelectorAll('.radio-option');
    radioOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        if (radio && radio.checked) {
            option.classList.add('selected');
        }
        option.addEventListener('click', () => {
            const radio = option.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                radioOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                calculateCreditScore();
            }
        });
    });
    
    // Event Listeners
    paymentHistory.addEventListener('change', calculateCreditScore);
    creditUtilization.addEventListener('change', calculateCreditScore);
    creditAge.addEventListener('change', calculateCreditScore);
    creditMix.addEventListener('change', calculateCreditScore);
    hardInquiries.addEventListener('change', calculateCreditScore);
    
    // Initialize
    calculateCreditScore();
</script>
</body>
</html>