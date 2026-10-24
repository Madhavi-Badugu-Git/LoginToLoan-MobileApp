<?php
// FILE: free-credit-score.php
include('includes/dbConfig.php');
include('includes/navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Free Credit Score | Kredit Healthy</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1a1a2e;
            overflow-x: hidden;
        }
        
        /* Blue & Orange Theme */
        :root {
            --blue-deep: #0A2540;
            --blue-primary: #1E5AA8;
            --blue-light: #3B7BC5;
            --orange-primary: #F39C12;
            --orange-deep: #E67E22;
            --orange-light: #F5B041;
            --orange-soft: #FFF3E0;
            --gray-50: #f8f9fc;
            --gray-100: #f1f3f9;
            --gray-200: #e4e7ed;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }
        
        /* Hero Section */
        .credit-hero {
            background: linear-gradient(135deg, var(--blue-deep) 0%, var(--blue-primary) 50%, var(--blue-light) 100%);
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .credit-hero::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(243,156,18,0.2), transparent);
            border-radius: 50%;
        }
        .credit-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(243,156,18,0.15), transparent);
            border-radius: 50%;
        }
        .credit-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        .credit-hero p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        /* Features Bar */
        .features-bar {
            background: var(--orange-soft);
            padding: 20px 0;
            border-bottom: 1px solid rgba(243,156,18,0.2);
        }
        .features-grid {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .feature-item i {
            font-size: 1.8rem;
            color: var(--orange-primary);
        }
        .feature-item span {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--blue-deep);
        }
        
        /* Main Content */
        .credit-main {
            padding: 60px 0;
            background: #ffffff;
        }
        
        .two-column-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
        }
        
        /* Form Section */
        .form-section {
            background: white;
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--gray-200);
        }
        .form-section h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--blue-deep);
            margin-bottom: 8px;
        }
        .form-section > p {
            color: var(--gray-500);
            margin-bottom: 24px;
            font-size: 0.9rem;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 6px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid var(--gray-200);
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 3px rgba(243,156,18,0.1);
        }
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 20px 0;
        }
        .checkbox-group input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
        }
        .checkbox-group label {
            font-size: 0.8rem;
            color: var(--gray-600);
            line-height: 1.4;
        }
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-deep));
            color: white;
            padding: 14px 24px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(243,156,18,0.3);
            gap: 15px;
        }
        .security-note {
            text-align: center;
            margin-top: 20px;
            font-size: 0.75rem;
            color: var(--gray-500);
        }
        .security-note i {
            color: var(--orange-primary);
            margin-right: 6px;
        }
        
        /* Info Section */
        .info-section {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }
        .info-card {
            background: linear-gradient(135deg, #f8faff, #fff);
            border-radius: 24px;
            padding: 24px;
            border: 1px solid var(--gray-200);
        }
        .info-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--blue-deep);
            margin-bottom: 16px;
        }
        .info-card p {
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 16px;
        }
        .info-list {
            list-style: none;
            padding: 0;
        }
        .info-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            color: var(--gray-700);
        }
        .info-list li i {
            color: var(--orange-primary);
            font-size: 1rem;
        }
        
        /* Benefits Section */
        .benefits-section {
            background: var(--orange-soft);
            padding: 60px 0;
        }
        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--blue-deep);
            margin-bottom: 48px;
        }
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        .benefit-card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(243,156,18,0.15);
        }
        .benefit-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
            border-color: var(--orange-primary);
        }
        .benefit-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--blue-primary), var(--orange-primary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .benefit-icon i {
            font-size: 2rem;
            color: white;
        }
        .benefit-card h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--blue-deep);
            margin-bottom: 12px;
        }
        .benefit-card p {
            color: var(--gray-600);
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        /* Why Trust Section */
        .trust-section {
            padding: 60px 0;
            background: white;
        }
        .trust-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        .trust-item {
            text-align: center;
            padding: 20px;
        }
        .trust-item i {
            font-size: 2.5rem;
            color: var(--orange-primary);
            margin-bottom: 16px;
        }
        .trust-item h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--blue-deep);
            margin-bottom: 8px;
        }
        .trust-item p {
            color: var(--gray-600);
            font-size: 0.85rem;
        }
        
        /* Score Range Section */
        .score-range-section {
            background: linear-gradient(135deg, var(--blue-deep), var(--blue-primary));
            padding: 60px 0;
            color: white;
        }
        .score-range-title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 40px;
        }
        .score-range-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 60px;
            padding: 20px 30px;
        }
        .score-range {
            text-align: center;
            flex: 1;
        }
        .score-value {
            font-size: 2rem;
            font-weight: 800;
        }
        .score-label {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-top: 5px;
        }
        .score-divider {
            font-size: 1.5rem;
            opacity: 0.5;
        }
        .score-description {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Factors Section */
        .factors-section {
            padding: 60px 0;
            background: var(--gray-50);
        }
        .factors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .factor-card {
            background: white;
            border-radius: 20px;
            padding: 28px;
            border-left: 4px solid var(--orange-primary);
        }
        .factor-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--blue-deep);
            margin-bottom: 12px;
        }
        .factor-card p {
            color: var(--gray-600);
            line-height: 1.6;
        }
        .factor-tag {
            display: inline-block;
            background: var(--orange-soft);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--orange-deep);
            margin-top: 12px;
        }
        
        /* FAQ Section */
        .faq-section {
            padding: 60px 0;
            background: white;
        }
        .faq-grid {
            max-width: 800px;
            margin: 0 auto;
        }
        .faq-item {
            border-bottom: 1px solid var(--gray-200);
            padding: 20px 0;
        }
        .faq-question {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--blue-deep);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .faq-question i {
            color: var(--orange-primary);
            transition: transform 0.3s;
        }
        .faq-question.active i {
            transform: rotate(180deg);
        }
        .faq-answer {
            display: none;
            padding-top: 12px;
            color: var(--gray-600);
            line-height: 1.6;
        }
        .faq-answer.show {
            display: block;
        }
        
        /* CTA Bottom */
        .cta-bottom {
            background: linear-gradient(135deg, var(--orange-primary), var(--orange-deep));
            padding: 60px 0;
            text-align: center;
        }
        .cta-bottom h2 {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 16px;
        }
        .cta-bottom p {
            color: rgba(255,255,255,0.9);
            margin-bottom: 24px;
        }
        .cta-bottom-btn {
            background: white;
            color: var(--orange-deep);
            padding: 14px 36px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }
        .cta-bottom-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            gap: 15px;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .two-column-layout {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            .credit-hero h1 {
                font-size: 2rem;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .score-range-container {
                flex-direction: column;
                border-radius: 30px;
            }
            .score-divider {
                transform: rotate(90deg);
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 16px;
            }
            .credit-hero {
                padding: 50px 0 40px;
            }
            .credit-hero h1 {
                font-size: 1.6rem;
            }
            .features-grid {
                gap: 20px;
            }
            .feature-item span {
                font-size: 0.8rem;
            }
            .form-section {
                padding: 24px;
            }
        }
        
        /* Animation */
        .fade-up {
            animation: fadeUp 0.6s ease forwards;
        }
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="credit-hero">
    <div class="container">
        <h1>Your Credit Health Journey Starts Here</h1>
        <p>Professional credit solutions for a brighter financial future</p>
    </div>
</section>

<!-- Features Bar -->
<div class="features-bar">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item"><i class="fas fa-chart-line"></i><span>View Your Credit Rating At No Cost</span></div>
            <div class="feature-item"><i class="fas fa-file-alt"></i><span>Receive Your Comprehensive Credit Analysis</span></div>
            <div class="feature-item"><i class="fas fa-clock"></i><span>In Only 2 Minutes!</span></div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="credit-main">
    <div class="container">
        <div class="two-column-layout">
            <!-- Form Section -->
            <div class="form-section fade-up">
                <h2>GET YOUR FREE CREDIT ASSESSMENT</h2>
                <p>Complete the form below to receive your digital credit profile</p>
                
                <form action="" method="POST" id="creditForm">
                    <div class="form-group">
                        <label>Given Name *</label>
                        <input type="text" placeholder="Enter your first name" required>
                    </div>
                    <div class="form-group">
                        <label>Surname *</label>
                        <input type="text" placeholder="Enter your last name" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" placeholder="you@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Contact Number *</label>
                        <input type="tel" placeholder="Enter 10-digit mobile number" pattern="[0-9]{10}" required>
                    </div>
                    <div class="form-group">
                        <label>Location / City *</label>
                        <select required>
                            <option value="">Select your city</option>
                            <option>Mumbai</option>
                            <option>Delhi</option>
                            <option>Bangalore</option>
                            <option>Chennai</option>
                            <option>Kolkata</option>
                            <option>Hyderabad</option>
                            <option>Pune</option>
                            <option>Ahmedabad</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Query Type</label>
                        <select>
                            <option value="">Select your query</option>
                            <option>Credit Score Check</option>
                            <option>Credit Report Dispute</option>
                            <option>Credit Improvement</option>
                            <option>General Inquiry</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Your Message (Optional)</label>
                        <textarea rows="3" placeholder="Your message..."></textarea>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="consent" required>
                        <label for="consent">By proceeding, I acknowledge and consent to the Terms & Conditions regarding data processing, privacy protocols, and security measures. I recognize that I may withdraw this consent at any time through the designated opt-out procedures.</label>
                    </div>
                    <button type="submit" class="submit-btn">Proceed to Credit Assessment <i class="fas fa-arrow-right"></i></button>
                    <div class="security-note">
                        <i class="fas fa-shield-alt"></i> Your information is protected with bank-level security encryption
                    </div>
                </form>
            </div>
            
            <!-- Info Section -->
            <div class="info-section">
                <div class="info-card fade-up">
                    <h3>Why Check Your Credit?</h3>
                    <ul class="info-list">
                        <li><i class="fas fa-check-circle"></i> Better loan approval chances</li>
                        <li><i class="fas fa-check-circle"></i> Lower interest rates</li>
                        <li><i class="fas fa-check-circle"></i> Financial health awareness</li>
                    </ul>
                </div>
                
                <div class="info-card fade-up">
                    <h3>Complimentary Credit Consultation</h3>
                    <ul class="info-list">
                        <li><i class="fas fa-search"></i> Detection of discrepancies and inaccuracies</li>
                        <li><i class="fas fa-chart-line"></i> Action strategy to improve Credit Wellness</li>
                    </ul>
                </div>
                
                <div class="info-card fade-up">
                    <h3>How to Get a Free Credit Report Online</h3>
                    <p>If you want to understand your financial health, getting a free credit report online is a smart step. Your credit score plays a big role in whether you can get loans, credit cards, or other types of credit.</p>
                    <p>By checking your credit report, you can see where you stand financially and spot any issues early.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section">
    <div class="container">
        <h2 class="section-title fade-up">Benefits of Having a High Credit Score</h2>
        <div class="benefits-grid">
            <div class="benefit-card fade-up"><div class="benefit-icon"><i class="fas fa-dollar-sign"></i></div><h4>Save Money on Interest</h4><p>A good credit score helps you get lower interest rates on loans and credit cards.</p></div>
            <div class="benefit-card fade-up"><div class="benefit-icon"><i class="fas fa-handshake"></i></div><h4>Get Approved for Loans Easily</h4><p>Easier to get home loans, car loans, gold loans, and other types of credit.</p></div>
            <div class="benefit-card fade-up"><div class="benefit-icon"><i class="fas fa-credit-card"></i></div><h4>Qualify for Higher Limits</h4><p>Apply for new credit cards and receive higher credit limits.</p></div>
            <div class="benefit-card fade-up"><div class="benefit-icon"><i class="fas fa-briefcase"></i></div><h4>Better Chances for Your Dream Job</h4><p>Some employers check credit history. A healthy score improves your chances.</p></div>
            <div class="benefit-card fade-up"><div class="benefit-icon"><i class="fas fa-phone-slash"></i></div><h4>Fewer Collection Calls</h4><p>Maintaining a good credit score reduces missed payments and collection calls.</p></div>
        </div>
    </div>
</section>

<!-- Why Trust Section -->
<section class="trust-section">
    <div class="container">
        <h2 class="section-title fade-up">Why People Trust Kredit Healthy</h2>
        <div class="trust-grid">
            <div class="trust-item"><i class="fas fa-bolt"></i><h4>Fast Credit Score Check</h4><p>View your credit score online within minutes</p></div>
            <div class="trust-item"><i class="fas fa-gem"></i><h4>No Cost Involved</h4><p>Completely free — no hidden fees</p></div>
            <div class="trust-item"><i class="fas fa-tools"></i><h4>Support to Fix Credit Problems</h4><p>Find negative issues and create clear steps</p></div>
            <div class="trust-item"><i class="fas fa-chalkboard-user"></i><h4>One-on-One Credit Guidance</h4><p>Dedicated credit specialist for your needs</p></div>
            <div class="trust-item"><i class="fas fa-chart-simple"></i><h4>Planned Credit Score Growth</h4><p>Structured improvement plan for your targets</p></div>
            <div class="trust-item"><i class="fas fa-eye"></i><h4>Easy Credit Monitoring</h4><p>Regular updates on credit score changes</p></div>
        </div>
    </div>
</section>

<!-- Score Range Section -->
<section class="score-range-section">
    <div class="container">
        <h2 class="score-range-title">Understanding Your Credit Score Range</h2>
        <div class="score-range-container">
            <div class="score-range"><div class="score-value">300</div><div class="score-label">Needs Improvement</div></div>
            <div class="score-divider"><i class="fas fa-arrow-right"></i></div>
            <div class="score-range"><div class="score-value">660</div><div class="score-label">Fair</div></div>
            <div class="score-divider"><i class="fas fa-arrow-right"></i></div>
            <div class="score-range"><div class="score-value">714</div><div class="score-label">Good</div></div>
            <div class="score-divider"><i class="fas fa-arrow-right"></i></div>
            <div class="score-range"><div class="score-value">777</div><div class="score-label">Very Good</div></div>
            <div class="score-divider"><i class="fas fa-arrow-right"></i></div>
            <div class="score-range"><div class="score-value">900</div><div class="score-label">Excellent</div></div>
        </div>
        <div class="score-description">Higher scores indicate better credit health and increase your chances of approval for loans and credit cards with favorable terms.</div>
    </div>
</section>

<!-- Factors Affecting Credit Score -->
<section class="factors-section">
    <div class="container">
        <h2 class="section-title fade-up">Factors Affecting Credit Score</h2>
        <div class="factors-grid">
            <div class="factor-card"><h3>Payment History</h3><p>Your payment record has the biggest impact on your credit score. When payments are delayed or skipped, your credit score can drop quickly.</p><span class="factor-tag">Most Important Factor</span></div>
            <div class="factor-card"><h3>Age and Type of Credit</h3><p>Having a longer credit history helps lenders see that you have managed credit for a long time. A mix of credit accounts has a positive effect.</p><span class="factor-tag">Very Important</span></div>
            <div class="factor-card"><h3>Credit Utilization</h3><p>Experts suggest keeping your credit usage below 30%. Lower credit usage helps maintain a healthy credit score.</p><span class="factor-tag">Very Important</span></div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <h2 class="section-title fade-up">Frequently Asked Questions</h2>
        <div class="faq-grid">
            <div class="faq-item"><div class="faq-question" onclick="toggleFAQ(this)">What Is a Good Credit Score for Getting a Loan? <i class="fas fa-chevron-down"></i></div><div class="faq-answer">A credit score above 750 is generally considered good and increases your chances of loan approval with favorable interest rates.</div></div>
            <div class="faq-item"><div class="faq-question" onclick="toggleFAQ(this)">How Often Should You Check Your Credit Score? <i class="fas fa-chevron-down"></i></div><div class="faq-answer">You should check your credit score at least once every 3-6 months to monitor your financial health and detect any errors early.</div></div>
            <div class="faq-item"><div class="faq-question" onclick="toggleFAQ(this)">Does Checking My Credit Score Affect My Credit? <i class="fas fa-chevron-down"></i></div><div class="faq-answer">No, checking your own credit score is a soft inquiry and does not affect your credit score.</div></div>
            <div class="faq-item"><div class="faq-question" onclick="toggleFAQ(this)">Can I improve my credit score? <i class="fas fa-chevron-down"></i></div><div class="faq-answer">Yes, by paying bills on time, reducing credit utilization, and correcting errors in your credit report, you can improve your score over time.</div></div>
            <div class="faq-item"><div class="faq-question" onclick="toggleFAQ(this)">Why Is It Important to Check Your Credit Score? <i class="fas fa-chevron-down"></i></div><div class="faq-answer">Regular credit score checks help you understand your financial standing, detect fraud early, and improve your chances of loan approval.</div></div>
        </div>
    </div>
</section>

<!-- CTA Bottom -->
<section class="cta-bottom">
    <div class="container">
        <h2>Get Your Free Credit Score Now</h2>
        <p>Start your journey to better credit health today</p>
        <a href="#top" class="cta-bottom-btn">Get Started Now <i class="fas fa-arrow-right"></i></a>
    </div>
</section>

<script>
    function toggleFAQ(element) {
        element.classList.toggle('active');
        const answer = element.nextElementSibling;
        answer.classList.toggle('show');
    }
    
    // Smooth scroll for CTA button
    document.querySelector('.cta-bottom-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('.credit-hero')?.scrollIntoView({ behavior: 'smooth' });
    });
    
    // Form submission handling
    document.getElementById('creditForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Thank you for your request! Our credit expert will contact you shortly with your free credit assessment.');
        this.reset();
    });
    
    // Fade up animation
    const fadeElements = document.querySelectorAll('.fade-up');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    fadeElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>