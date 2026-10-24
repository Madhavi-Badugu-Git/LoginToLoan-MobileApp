<?php
session_start();

// If not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Pull session data into clean variables
$fullname      = htmlspecialchars($_SESSION['fullname']      ?? 'User');
$email         = htmlspecialchars($_SESSION['email']         ?? 'user@email.com');
$phone         = htmlspecialchars($_SESSION['phone']         ?? '+91 98765 43210');
$role          = htmlspecialchars($_SESSION['role']          ?? 'user');
$profile_image = htmlspecialchars($_SESSION['profile_image'] ?? '');
$firstname     = htmlspecialchars(explode(' ', trim($_SESSION['fullname'] ?? 'User'))[0]);
$initials      = strtoupper(mb_substr($_SESSION['fullname'] ?? 'U', 0, 1));

// Include the header - CORRECT PATH
include('includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Credit Score | Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --primary: #1a237e;
            --primary-dark: #0d1757;
            --primary-light: #283593;
            --secondary: #00c853;
            --accent: #ff6b35;
            --orange: #ff9800;
            --text-primary: #1a1a2e;
            --text-secondary: #666666;
            --text-tertiary: #999999;
            --background: #f8f9fc;
            --white: #ffffff;
            --border: #eef2f6;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
            min-height: 100vh;
            position: relative;
            padding-bottom: 75px;
        }

        .page { display: none; animation: fadeIn 0.3s ease; }
        .page.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            position: relative; padding: 30px 20px 35px;
            color: white; overflow: hidden;
            border-radius: 0 0 30px 30px;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .hero-slideshow { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
        .slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; background-position: center; background-repeat: no-repeat; opacity: 0; transition: opacity 1s ease-in-out; }
        .slide.active { opacity: 1; }
        .hero-section .overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg,rgba(26,35,126,0.75) 0%,rgba(57,73,171,0.85) 100%); z-index: 1; }
        .hero-section .content { position: relative; z-index: 2; }

        .sub-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        .sub-header i {
            cursor: pointer;
            font-size: 20px;
            color: white;
        }
        .sub-header span {
            font-size: 18px;
            font-weight: 700;
            color: white;
        }
        .sub-header p {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }

        /* ===== SECTION HEADER ===== */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 0 16px;
            margin: 24px 0 12px;
        }
        .section-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* ===== CREDIT CARD ===== */
        .credit-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 16px;
            border-radius: 20px;
            padding: 24px 20px 20px;
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .score-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .score-value {
            font-size: 42px;
            font-weight: 800;
        }
        .progress-bar {
            background: rgba(255,255,255,0.2);
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
            margin: 15px 0;
        }
        .progress-fill {
            background: linear-gradient(90deg, #ffd700, #ff6b35);
            height: 100%;
            width: 78%;
            transition: width 1s ease;
        }
        .score-labels {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: rgba(255,255,255,0.7);
        }
        .score-status {
            margin-top: 15px;
            font-size: 12px;
            color: #4ade80;
        }

        /* ===== CHECK SCORE BUTTON ===== */
        .check-score-btn {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 40px;
            font-weight: 700;
            cursor: pointer;
            width: calc(100% - 40px);
            margin: 0 20px 20px;
            transition: transform 0.2s;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        }
        .check-score-btn:active { transform: scale(0.98); }
        .check-score-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .check-score-btn .spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        /* ===== ELIGIBLE OFFERS SECTION ===== */
        .eligible-section {
            display: none;
            padding: 0 16px 20px;
        }
        .eligible-section.visible {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        .eligible-badge {
            background: var(--success);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }
        .eligible-badge.partial {
            background: var(--warning);
        }
        .eligible-badge.rejected {
            background: var(--danger);
        }

        /* ===== OFFERS GRID - 3 COLUMNS ===== */
        .offers-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .offer-card {
            background: var(--white);
            border-radius: 16px;
            padding: 16px 10px;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--border);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 120px;
            justify-content: center;
        }
        .offer-card:active {
            transform: scale(0.95);
            border-color: var(--orange);
            box-shadow: var(--shadow-lg);
        }
        .offer-card .offer-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(26, 35, 126, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-bottom: 8px;
        }
        .offer-card .offer-icon i {
            font-size: 22px;
            color: var(--primary);
        }
        .offer-card .offer-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
            margin-bottom: 2px;
        }
        .offer-card .offer-detail {
            font-size: 9px;
            color: var(--text-tertiary);
            line-height: 1.2;
        }
        .offer-card .offer-amount {
            font-size: 11px;
            font-weight: 700;
            color: var(--primary);
            margin-top: 4px;
        }
        .offer-card .offer-badge {
            font-size: 7px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-top: 4px;
        }
        .offer-card .offer-badge.popular {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .offer-card .offer-badge.best {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }
        .offer-card .offer-badge.hot {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }
        .offer-card .offer-badge.low {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
            color: #1a1a2e;
        }
        .offer-card .offer-badge.quick {
            background: linear-gradient(135deg, #fa709a, #fee140);
            color: #1a1a2e;
        }

        /* ===== LOANS GRID ===== */
        .loans-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 0 16px;
            margin-bottom: 20px;
        }
        .loan-card {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
            min-height: 90px;
        }
        .loan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1;
        }
        .loan-card:active { transform: scale(0.97); }
        .loan-card .loan-image,
        .loan-card .loan-info { position: relative; z-index: 2; }
        .loan-image {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .loan-image i { font-size: 28px; color: #1a237e; }
        .loan-info h4 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        .loan-info p {
            font-size: 11px;
            color: rgba(255,255,255,0.9);
        }

       

        /* ===== RESPONSIVE ===== */
        @media (max-width: 400px) {
            .score-value { font-size: 32px; }
            .offer-card { padding: 12px 6px; min-height: 100px; }
            .offer-card .offer-icon { width: 36px; height: 36px; }
            .offer-card .offer-icon i { font-size: 16px; }
            .offer-card .offer-name { font-size: 10px; }
            .offer-card .offer-detail { font-size: 8px; }
            .offer-card .offer-amount { font-size: 10px; }
        }

        @media (min-width: 401px) and (max-width: 480px) {
            .offer-card { padding: 14px 8px; min-height: 110px; }
            .offer-card .offer-icon { width: 42px; height: 42px; }
            .offer-card .offer-icon i { font-size: 18px; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== CREDIT SCORE PAGE ===== -->
    <div id="creditPage" class="page active">
        <div class="hero-section">
            <div class="hero-slideshow">
                <div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&h=600&fit=crop');"></div>
            </div>
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header">
                    <i class="fas fa-arrow-left" onclick="window.location.href='index.php'"></i>
                    <span>Credit Score</span>
                </div>
                <p>Check your score & get eligible offers</p>
            </div>
        </div>

        <!-- Credit Score Card -->
        <div class="credit-card" id="creditCard">
            <div class="score-header">
                <div>Your CIBIL Score</div>
                <div class="score-value" id="creditScore">--</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" id="scoreProgress" style="width:0%"></div>
            </div>
            <div class="score-labels">
                <span>300</span>
                <span>550</span>
                <span>700</span>
                <span>850</span>
            </div>
            <div class="score-status" id="scoreStatus">🔍 Click "Check Score" to view</div>
        </div>

        <!-- Check Score Button -->
        <button class="check-score-btn" id="checkScoreBtn" onclick="checkCibilScore()">
            <i class="fas fa-shield-alt"></i> Check Your CIBIL Score
        </button>

        <!-- ===== ELIGIBLE OFFERS SECTION ===== -->
        <div class="eligible-section" id="eligibleSection">
            <div class="section-header">
                <h2>✅ Eligible Offers For You</h2>
                <span class="eligible-badge" id="eligibilityBadge">Eligible</span>
            </div>
            <div class="offers-grid" id="offersContainer">
                <!-- Offers will be dynamically inserted here -->
            </div>
        </div>

        <!-- Improve Your Score Section -->
        <div class="section-header" style="margin-top: 10px;">
            <h2>📈 Improve Your Score</h2>
        </div>
        <div class="loans-grid">
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=300&h=200&fit=crop');" onclick="showToast('Pay all EMIs on time to boost your score')">
                <div class="loan-image"><i class="fas fa-calendar-check"></i></div>
                <div class="loan-info">
                    <h4>Pay on Time</h4>
                    <p>Boosts your score</p>
                </div>
            </div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=300&h=200&fit=crop');" onclick="showToast('Keep credit usage below 30% of your limit')">
                <div class="loan-image"><i class="fas fa-credit-card"></i></div>
                <div class="loan-info">
                    <h4>Reduce Usage</h4>
                    <p>Below 30% limit</p>
                </div>
            </div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=300&h=200&fit=crop');" onclick="showToast('Check your credit report regularly for errors')">
                <div class="loan-image"><i class="fas fa-file-alt"></i></div>
                <div class="loan-info">
                    <h4>Monitor Report</h4>
                    <p>Check for errors</p>
                </div>
            </div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=300&h=200&fit=crop');" onclick="showToast('Different credit types improve your score')">
                <div class="loan-image"><i class="fas fa-chart-pie"></i></div>
                <div class="loan-info">
                    <h4>Diverse Credit Mix</h4>
                    <p>Different loan types</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TOAST ===== -->
    <div id="toast" class="toast"></div>

</div>

<script>
    // ===== TOAST =====
    function showToast(msg) {
        var t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(function() {
            t.classList.remove('show');
        }, 3000);
    }

    // ===== CIBIL SCORE CHECK =====
    function checkCibilScore() {
        var btn = document.getElementById('checkScoreBtn');
        var scoreDisplay = document.getElementById('creditScore');
        var progressFill = document.getElementById('scoreProgress');
        var statusDisplay = document.getElementById('scoreStatus');
        
        // Disable button and show loading
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner spinner"></i> Checking Score...';
        
        // Simulate API call
        setTimeout(function() {
            // Generate random score between 600-850
            var score = Math.floor(Math.random() * (850 - 600 + 1)) + 600;
            
            // Display score
            scoreDisplay.textContent = score;
            
            // Update progress bar
            var percentage = ((score - 300) / (850 - 300) * 100);
            progressFill.style.width = percentage + '%';
            
            // Update status based on score
            var statusText = '';
            var statusColor = '';
            var badgeClass = '';
            var offers = [];
            
            if (score >= 750) {
                statusText = '✅ Excellent! High approval chances';
                statusColor = '#4ade80';
                badgeClass = 'eligible';
                offers = getOffers('excellent');
            } else if (score >= 650) {
                statusText = '👍 Good! Fair approval chances';
                statusColor = '#f59e0b';
                badgeClass = 'partial';
                offers = getOffers('good');
            } else if (score >= 550) {
                statusText = '⚠️ Average - Needs improvement';
                statusColor = '#f97316';
                badgeClass = 'partial';
                offers = getOffers('average');
            } else {
                statusText = '🔴 Poor - Please improve your score';
                statusColor = '#ef4444';
                badgeClass = 'rejected';
                offers = getOffers('poor');
            }
            
            statusDisplay.textContent = statusText;
            statusDisplay.style.color = statusColor;
            
            // Show eligible offers
            showEligibleOffers(offers, badgeClass, score);
            
            // Reset button
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-sync-alt"></i> Re-Check Score';
            
            // Show toast
            showToast('🎯 Score: ' + score + ' - ' + statusText);
            
        }, 2000);
    }

    // ===== GET OFFERS BASED ON SCORE =====
    function getOffers(category) {
        var allOffers = {
            'excellent': [
                { 
                    icon: 'fa-credit-card', 
                    name: 'Credit Card', 
                    detail: 'Up to ₹10 Lakh', 
                    amount: '₹10 Lakh',
                    link: 'https://mobile.logintoloans.com/credit-cards.php',
                    badge: 'Popular',
                    badgeClass: 'popular'
                },
                { 
                    icon: 'fa-rupee-sign', 
                    name: 'Personal Loan', 
                    detail: 'Instant Approval', 
                    amount: '₹50 Lakh',
                    link: 'https://mobile.logintoloans.com/personal.php',
                    badge: 'Best',
                    badgeClass: 'best'
                },
                { 
                    icon: 'fa-home', 
                    name: 'Home Loan', 
                    detail: 'Up to ₹5 Cr', 
                    amount: '₹5 Cr',
                    link: 'https://mobile.logintoloans.com/home-loan.php',
                    badge: 'Low Rate',
                    badgeClass: 'low'
                },
                { 
                    icon: 'fa-briefcase', 
                    name: 'Business Loan', 
                    detail: 'MSME Approved', 
                    amount: '₹2 Cr',
                    link: 'https://mobile.logintoloans.com/business-loans.php',
                    badge: 'Hot',
                    badgeClass: 'hot'
                },
                { 
                    icon: 'fa-car', 
                    name: 'Car Loan', 
                    detail: 'New & Used', 
                    amount: '₹1 Cr',
                    link: 'https://mobile.logintoloans.com/car-loan.php',
                    badge: 'Quick',
                    badgeClass: 'quick'
                },
                { 
                    icon: 'fa-coins', 
                    name: 'Gold Loan', 
                    detail: 'Against Gold', 
                    amount: '₹50 Lakh',
                    link: 'https://mobile.logintoloans.com/gold-loan.php',
                    badge: 'Quick',
                    badgeClass: 'quick'
                }
            ],
            'good': [
                { 
                    icon: 'fa-credit-card', 
                    name: 'Credit Card', 
                    detail: 'Up to ₹5 Lakh', 
                    amount: '₹5 Lakh',
                    link: 'https://mobile.logintoloans.com/credit-cards.php',
                    badge: 'Popular',
                    badgeClass: 'popular'
                },
                { 
                    icon: 'fa-rupee-sign', 
                    name: 'Personal Loan', 
                    detail: 'Good Rates', 
                    amount: '₹25 Lakh',
                    link: 'https://mobile.logintoloans.com/personal.php',
                    badge: 'Best',
                    badgeClass: 'best'
                },
                { 
                    icon: 'fa-home', 
                    name: 'Home Loan', 
                    detail: 'Up to ₹3 Cr', 
                    amount: '₹3 Cr',
                    link: 'https://mobile.logintoloans.com/home-loan.php',
                    badge: 'Low Rate',
                    badgeClass: 'low'
                },
                { 
                    icon: 'fa-car', 
                    name: 'Car Loan', 
                    detail: 'Competitive', 
                    amount: '₹50 Lakh',
                    link: 'https://mobile.logintoloans.com/car-loan.php',
                    badge: 'Quick',
                    badgeClass: 'quick'
                }
            ],
            'average': [
                { 
                    icon: 'fa-credit-card', 
                    name: 'Secured Card', 
                    detail: 'Against FD', 
                    amount: '₹1 Lakh',
                    link: 'https://mobile.logintoloans.com/credit-cards.php',
                    badge: 'Popular',
                    badgeClass: 'popular'
                },
                { 
                    icon: 'fa-rupee-sign', 
                    name: 'Personal Loan', 
                    detail: 'Higher Rate', 
                    amount: '₹10 Lakh',
                    link: 'https://mobile.logintoloans.com/personal.php',
                    badge: 'Best',
                    badgeClass: 'best'
                },
                { 
                    icon: 'fa-coins', 
                    name: 'Gold Loan', 
                    detail: 'Quick Approval', 
                    amount: '₹25 Lakh',
                    link: 'https://mobile.logintoloans.com/gold-loan.php',
                    badge: 'Quick',
                    badgeClass: 'quick'
                }
            ],
            'poor': [
                { 
                    icon: 'fa-shield-alt', 
                    name: 'Credit Builder', 
                    detail: 'Build Score', 
                    amount: '₹50,000',
                    link: 'https://mobile.logintoloans.com/personal.php',
                    badge: 'Best',
                    badgeClass: 'best'
                },
                { 
                    icon: 'fa-coins', 
                    name: 'Gold Loan', 
                    detail: 'High Approval', 
                    amount: '₹10 Lakh',
                    link: 'https://mobile.logintoloans.com/gold-loan.php',
                    badge: 'Quick',
                    badgeClass: 'quick'
                }
            ]
        };
        
        return allOffers[category] || allOffers['average'];
    }

    // ===== SHOW ELIGIBLE OFFERS =====
    function showEligibleOffers(offers, badgeClass, score) {
        var section = document.getElementById('eligibleSection');
        var container = document.getElementById('offersContainer');
        var badge = document.getElementById('eligibilityBadge');
        
        // Update badge
        badge.className = 'eligible-badge';
        if (badgeClass === 'partial') badge.classList.add('partial');
        if (badgeClass === 'rejected') badge.classList.add('rejected');
        
        var badgeText = '';
        if (badgeClass === 'eligible') badgeText = '✅ ' + offers.length + ' Offers';
        else if (badgeClass === 'partial') badgeText = '⚠️ ' + offers.length + ' Limited';
        else badgeText = '🔴 ' + offers.length + ' Offers';
        badge.textContent = badgeText;
        
        // Generate offer cards - 3 per row
        var html = '';
        offers.forEach(function(offer) {
            html += `
                <a href="${offer.link}" class="offer-card" onclick="showToast('🔍 Loading ${offer.name}...')">
                    <div class="offer-icon">
                        <i class="fas ${offer.icon}"></i>
                    </div>
                    <div class="offer-name">${offer.name}</div>
                    <div class="offer-detail">${offer.detail}</div>
                    <div class="offer-amount">${offer.amount}</div>
                    <span class="offer-badge ${offer.badgeClass}">${offer.badge}</span>
                </a>
            `;
        });
        
        container.innerHTML = html;
        section.classList.add('visible');
        
        // Scroll to offers
        setTimeout(function() {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 300);
    }

    // ===== AUTO CHECK ON PAGE LOAD =====
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-check score when page loads
        setTimeout(function() {
            checkCibilScore();
        }, 500);
    });
</script>
</body>
<?php include('includes/footer-nav.php'); ?>
</html>