<?php
// Include database configuration
include('includes/dbConfig.php');


// Check if user is logged in
// if(!isset($_SESSION['user'])){
//     header("Location: login.php");
//     exit;
// }

// Get user data from database
// $username = $_SESSION['user'];
// $query = mysqli_query($conn, "SELECT * FROM mobile_app_login WHERE username='$username'");
// $user_data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Login To Loans - Financial Super App</title>
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

        /* Pages */
        .page {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .page.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== TOP HEADER with MENU on LEFT and LOGOUT on RIGHT ===== */
        .top-header {
            position: sticky;
            top: 0;
            z-index: 200;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            border-bottom: 1px solid #f0f0f0;
        }
        /* Left Menu Button */
        .menu-btn {
            width: 42px;
            height: 42px;
            border: none;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .menu-btn:active { background: #f0f0f0; }
        .menu-btn span {
            display: block;
            width: 22px;
            height: 2.5px;
            background: #1a237e;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .menu-btn.open span:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
        .menu-btn.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .menu-btn.open span:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }

        /* Center Logo - Image Logo */
        .header-logo {
            flex: 1;
            text-align: center;
        }
        .header-logo img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        /* Right Logout Button */
        .logout-btn {
            width: 42px;
            height: 42px;
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 6px;
            border-radius: 10px;
            transition: background 0.2s;
            color: var(--primary);
            font-size: 20px;
        }
        .logout-btn:active { background: #f0f0f0; }

        /* ===== SLIDE-IN MENU DRAWER (LEFT SIDE) ===== */
        .menu-drawer {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100vh;
            background: #ffffff;
            z-index: 500;
            transition: left 0.3s ease;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            padding-top: 70px;
        }
        .menu-drawer.open { left: 0; }
        .menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 499;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }
        .menu-overlay.open { opacity: 1; pointer-events: all; }
        .drawer-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            border: none;
            background: #f5f5f5;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .drawer-logo { padding: 0 20px 20px; border-bottom: 1px solid #f0f0f0; text-align: center; }
        .drawer-logo img {
            height: 36px;
            width: auto;
            object-fit: contain;
        }
        .drawer-nav { padding: 16px 0; }
        .drawer-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 24px;
            cursor: pointer;
            transition: background 0.2s;
            font-size: 15px;
            font-weight: 500;
            color: #1a1a2e;
        }
        .drawer-item:active { background: #f5f7ff; }
        .drawer-item i { width: 22px; color: #1a237e; font-size: 17px; }
        .drawer-footer {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #999;
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            position: relative;
            padding: 20px 20px 35px;
            color: white;
            overflow: hidden;
            border-radius: 0 0 30px 30px;
            min-height: 340px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .hero-slideshow { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
        .slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; background-position: center; background-repeat: no-repeat; opacity: 0; transition: opacity 1s ease-in-out; }
        .slide.active { opacity: 1; }
        .hero-section .overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(26,35,126,0.75) 0%, rgba(57,73,171,0.85) 100%); z-index: 1; }
        .hero-section .content { position: relative; z-index: 2; }
        .greeting { margin-bottom: 20px; }
        .greeting h2 { font-size: 26px; font-weight: 700; margin-bottom: 6px; }
        .greeting p { font-size: 14px; opacity: 0.9; }
        .tagline h1 { font-size: 26px; font-weight: 800; line-height: 1.3; margin-bottom: 20px; }
        .features-list { margin: 15px 0 20px; }
        .feature-item { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; font-size: 15px; }
        .feature-item i { font-size: 18px; width: 24px; color: #ff9800; }
        .know-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            padding: 10px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.2s;
        }
        .know-more-btn:active { background: rgba(255,255,255,0.3); transform: scale(0.97); }
        .slide-indicators { position: absolute; bottom: 15px; left: 0; right: 0; display: flex; justify-content: center; gap: 8px; z-index: 3; }
        .indicator { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.5); cursor: pointer; transition: all 0.3s; }
        .indicator.active { width: 20px; border-radius: 10px; background: white; }

        /* ===== SECTION HEADERS ===== */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 0 16px;
            margin: 24px 0 12px;
        }
        .section-header h2 { font-size: 18px; font-weight: 700; color: var(--text-primary); }
        .section-header a { font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600; cursor: pointer; }

        /* ===== SCROLL RECOMMEND ===== */
        .scroll-container { position: relative; overflow: hidden; padding: 0 12px; }
        .scroll-horizontal { overflow-x: auto; white-space: nowrap; display: flex; gap: 12px; scrollbar-width: thin; scroll-behavior: smooth; -webkit-overflow-scrolling: touch; }
        .scroll-horizontal::-webkit-scrollbar { display: none; }
        .scroll-horizontal.auto-scroll { animation: scrollLeft 25s linear infinite; }
        .scroll-horizontal.auto-scroll:hover { animation-play-state: paused; }
        @keyframes scrollLeft { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        .scroll-content { display: flex; gap: 12px; }
        .recommend-card {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            background: var(--white);
            border-radius: 20px;
            padding: 16px;
            min-width: 120px;
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid var(--border);
        }
        .recommend-card:active { transform: scale(0.96); background: #fef3e8; }
        .recommend-card i { font-size: 32px; margin-bottom: 10px; }
        .recommend-card .card-title { font-size: 13px; font-weight: 700; margin-bottom: 4px; text-align: center; }
        .recommend-card .card-subtitle { font-size: 10px; color: var(--text-secondary); text-align: center; }
        .cashback-badge { background: linear-gradient(135deg, #ff9800, #ff5722); color: white; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; display: inline-block; margin-top: 8px; }

        /* ===== LOANS GRID ===== */
        .loans-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 0 16px;
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
        .loan-card .loan-info {
            position: relative;
            z-index: 2;
        }
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
        .loan-image i { font-size: 28px; }
        .loan-info h4 { font-size: 14px; font-weight: 700; margin-bottom: 4px; color: white; text-shadow: 0 1px 2px rgba(0,0,0,0.3); }
        .loan-info p { font-size: 11px; color: rgba(255,255,255,0.9); }

        .loan-cashback-1 { background-image: url('https://images.unsplash.com/photo-1556742031-c6961e8560b0?w=300&h=200&fit=crop'); }
        .loan-cashback-2 { background-image: url('https://images.unsplash.com/photo-1601924994987-69e26d50dc26?w=300&h=200&fit=crop'); }
        .loan-personal { background-image: url('https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=300&h=200&fit=crop'); }
        .loan-business { background-image: url('https://images.unsplash.com/photo-1556761175-b413da4baf72?w=300&h=200&fit=crop'); }
        .loan-instant { background-image: url('https://images.unsplash.com/photo-1553729459-efe14ef6055d?w=300&h=200&fit=crop'); }
        .loan-home { background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=300&h=200&fit=crop'); }

        /* ===== CREDIT CARD ===== */
        .credit-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 16px;
            border-radius: 20px;
            padding: 20px;
            color: white;
        }
        .score-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .score-value { font-size: 42px; font-weight: 800; }
        .progress-bar { background: rgba(255,255,255,0.2); height: 8px; border-radius: 10px; overflow: hidden; margin: 15px 0; }
        .progress-fill { background: linear-gradient(90deg, #ffd700, #ff6b35); height: 100%; width: 78%; transition: width 1s ease; }

        /* ===== APPLY LEADS PAGE STYLES ===== */
        .leads-container {
            padding: 20px;
        }
        .lead-card {
            background: var(--white);
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: all 0.2s;
            cursor: pointer;
        }
        .lead-card:active { transform: scale(0.98); background: #fef3e8; }
        .lead-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .lead-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }
        .lead-status {
            background: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
        }
        .lead-status.pending { background: #f59e0b; }
        .lead-status.completed { background: #10b981; }
        .lead-details {
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }
        .lead-details i { width: 20px; color: var(--accent); margin-right: 8px; }
        .lead-amount {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            margin-top: 10px;
        }
        .apply-leads-btn {
            background: linear-gradient(135deg, var(--accent), #ff8c42);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: transform 0.2s;
        }
        .apply-leads-btn:active { transform: scale(0.98); }

        /* ===== BOTTOM NAV ===== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            max-width: 480px;
            width: 100%;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(20px);
            display: flex;
            justify-content: space-around;
            padding: 8px 16px 20px;
            border-top: 1px solid var(--border);
            z-index: 100;
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: var(--text-tertiary);
            font-weight: 500;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 30px;
            transition: all 0.2s;
        }
        .nav-item i { font-size: 20px; }
        .nav-item.active { color: var(--primary); background: rgba(26,35,126,0.1); }

        /* ===== MODAL ===== */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: 0.2s;
        }
        .modal.active { visibility: visible; opacity: 1; }
        .modal-content {
            background: var(--white);
            border-radius: 24px;
            padding: 24px;
            width: 90%;
            max-width: 360px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-close { width: 32px; height: 32px; border-radius: 50%; background: var(--border); border: none; cursor: pointer; font-size: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: var(--text-secondary); }
        .form-input { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; }
        .apply-button {
            background: linear-gradient(135deg, var(--accent), #ff8c42);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }
        .apply-button:active { transform: scale(0.98); }
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--text-primary);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 13px;
            z-index: 1000;
            transition: transform 0.3s;
            white-space: nowrap;
        }
        .toast.show { transform: translateX(-50%) translateY(0); }
        .footer-note { text-align: center; font-size: 10px; color: var(--text-tertiary); padding: 16px; border-top: 1px solid var(--border); margin-top: 20px; }

        .sub-header { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; }
        .sub-header i { cursor: pointer; font-size: 20px; }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== TOP HEADER with MENU on LEFT and LOGOUT on RIGHT ===== -->
    <header class="top-header">
        <!-- Left Menu Button -->
        <button class="menu-btn" id="menuBtn" onclick="toggleMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        
        <!-- Center Logo - Image Logo -->
        <div class="header-logo">
            <img src="assets/images/logintoloan.jpeg" alt="Login To Loans" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 200 50\'%3E%3Crect width=\'200\' height=\'50\' fill=\'%231a237e\' rx=\'8\'/%3E%3Ctext x=\'100\' y=\'32\' font-family=\'Arial, sans-serif\' font-size=\'18\' font-weight=\'bold\' fill=\'%23ffffff\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">
        </div>
        
        <!-- Right Logout Button -->
       <div class="drawer-item" onclick="window.location.href='logout.php';">
    <i class="fas fa-sign-out-alt"></i>
    <span>Logout</span>
</div>
    </header>

    <!-- ===== SLIDE-IN MENU DRAWER (LEFT SIDE - ALL PAGES) ===== -->
    <div class="menu-overlay" id="menuOverlay" onclick="closeMenu()"></div>
    <div class="menu-drawer" id="menuDrawer">
        <button class="drawer-close" onclick="closeMenu()">✕</button>
        <div class="drawer-logo">
            <img src="assets/images/logintoloan.jpeg" alt="Login To Loans" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 200 50\'%3E%3Crect width=\'200\' height=\'50\' fill=\'%231a237e\' rx=\'8\'/%3E%3Ctext x=\'100\' y=\'32\' font-family=\'Arial, sans-serif\' font-size=\'18\' font-weight=\'bold\' fill=\'%23ffffff\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">
        </div>
        <nav class="drawer-nav">
            <div class="drawer-item" onclick="closeMenu();navigateTo('homePage')"><i class="fas fa-home"></i> Home</div>
            <div class="drawer-item" onclick="closeMenu();navigateTo('offersPage')"><i class="fas fa-tag"></i> Loan Offers</div>
            <div class="drawer-item" onclick="closeMenu();navigateTo('creditPage')"><i class="fas fa-chart-line"></i> Credit Score</div>
            <div class="drawer-item" onclick="closeMenu();navigateTo('improvePage')"><i class="fas fa-arrow-up"></i> Improve Score</div>
            <div class="drawer-item" onclick="closeMenu();navigateTo('leadsPage')"><i class="fas fa-users"></i> Apply Leads</div>
            <div class="drawer-item" onclick="closeMenu();navigateTo('toolsPage')"><i class="fas fa-calculator"></i> Tools</div>
            <div class="drawer-item" onclick="closeMenu();navigateTo('profilePage')"><i class="fas fa-user"></i> My Profile</div>
            <div class="drawer-item" onclick="closeMenu();showToast('Support coming soon')"><i class="fas fa-headset"></i> Support</div>
        </nav>
        <div class="drawer-footer">© 2025 Login To Loans. All rights reserved.</div>
    </div>

    <!-- ========== PAGE 1: HOME ========== -->
    <div id="homePage" class="page active">
        <div class="hero-section">
            <div class="hero-slideshow" id="heroSlideshow">
                <div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1556742031-c6961e8560b0?w=800&h=600&fit=crop');"></div>
                <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&h=600&fit=crop');"></div>
                <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1553729459-efe14ef6055d?w=800&h=600&fit=crop');"></div>
                <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&h=600&fit=crop');"></div>
                <div class="slide" style="background-image:url('https://images.unsplash.com/photo-1556761175-b413da4baf72?w=800&h=600&fit=crop');"></div>
            </div>
            <div class="overlay"></div>
            <div class="content">
                <div class="greeting">
                    <h2>Hey BADUGU 👋</h2>
                    <p>Good to see you back again</p>
                </div>
                <div class="tagline">
                    <h1>Take control<br>of your Finances</h1>
                </div>
                <div class="features-list">
                    <div class="feature-item"><i class="fas fa-chart-line"></i><span>Track your Money</span></div>
                    <div class="feature-item"><i class="fas fa-tags"></i><span>Smart Categorisation</span></div>
                    <div class="feature-item"><i class="fas fa-layer-group"></i><span>One view for all Accounts</span></div>
                </div>
                <div class="know-more-btn" onclick="showToast('Coming soon! More features on the way')">Know More <i class="fas fa-arrow-right"></i></div>
            </div>
            <div class="slide-indicators" id="slideIndicators">
                <div class="indicator active" data-index="0"></div>
                <div class="indicator" data-index="1"></div>
                <div class="indicator" data-index="2"></div>
                <div class="indicator" data-index="3"></div>
                <div class="indicator" data-index="4"></div>
            </div>
        </div>

        <div class="section-header"><h2>✨ RECOMMENDED FOR YOU</h2><a href="#" onclick="navigateTo('offersPage')">View All</a></div>
        <div class="scroll-container">
            <div class="scroll-horizontal" id="autoScrollContainer">
                <div class="scroll-content" id="scrollContent">
                    <div class="recommend-card" onclick="showToast('Get Rs.200 Cashback!')"><i class="fas fa-tag" style="color:#ff9800;"></i><div class="card-title">Cashback Offer</div><div class="cashback-badge">Rs. 200</div></div>
                    <div class="recommend-card" onclick="openLoanApplication('Personal Loan')"><i class="fas fa-user-friends" style="color:#1a237e;"></i><div class="card-title">Personal Loan</div><div class="card-subtitle">Up to ₹50L</div></div>
                    <div class="recommend-card" onclick="showToast('Credit Cards coming soon')"><i class="fas fa-credit-card" style="color:#00c853;"></i><div class="card-title">Credit Cards</div><div class="card-subtitle">Rewards</div></div>
                    <div class="recommend-card" onclick="openLoanApplication('Home Loan')"><i class="fas fa-home" style="color:#9b59b6;"></i><div class="card-title">Loan above 25 Lakh</div><div class="card-subtitle">Low interest</div></div>
                    <div class="recommend-card" onclick="openLoanApplication('Personal Loan')"><i class="fas fa-bolt" style="color:#ff5722;"></i><div class="card-title">Instant Personal Loan</div><div class="card-subtitle">5 min</div></div>
                </div>
                <div class="scroll-content" id="scrollContentDuplicate"></div>
            </div>
        </div>

        <div class="section-header"><h2>💰 LOANS</h2><a href="#" onclick="navigateTo('offersPage')">See All</a></div>
        <div class="loans-grid">
            <div class="loan-card loan-cashback-1" onclick="showToast('Get Rs.200 cashback!')"><div class="loan-image"><i class="fas fa-tag" style="color:#ff9800;"></i></div><div class="loan-info"><h4>Cashback Offer</h4><p>Rs. 200 Cashback</p></div></div>
            <div class="loan-card loan-cashback-2" onclick="showToast('Limited period offer!')"><div class="loan-image"><i class="fas fa-gift" style="color:#e91e63;"></i></div><div class="loan-info"><h4>Cashback Offer</h4><p>Limited period</p></div></div>
            <div class="loan-card loan-personal" onclick="openLoanApplication('Personal Loan')"><div class="loan-image"><i class="fas fa-user-friends" style="color:#1a237e;"></i></div><div class="loan-info"><h4>Personal Loan</h4><p>Up to ₹50 Lakhs</p></div></div>
            <div class="loan-card loan-business" onclick="openLoanApplication('Business Loan')"><div class="loan-image"><i class="fas fa-briefcase" style="color:#00c853;"></i></div><div class="loan-info"><h4>Business Loan</h4><p>MSME approved</p></div></div>
            <div class="loan-card loan-instant" onclick="openLoanApplication('Personal Loan')"><div class="loan-image"><i class="fas fa-bolt" style="color:#ff5722;"></i></div><div class="loan-info"><h4>Instant Personal Loan</h4><p>5 min approval</p></div></div>
            <div class="loan-card loan-home" onclick="openLoanApplication('Home Loan')"><div class="loan-image"><i class="fas fa-home" style="color:#9b59b6;"></i></div><div class="loan-info"><h4>Home Loan</h4><p>Up to ₹5 Crore</p></div></div>
        </div>
        <div class="footer-note"><i class="fas fa-lock"></i> Secure & Trusted | RBI Compliant</div>
    </div>

    <!-- ========== PAGE 2: OFFERS ========== -->
    <div id="offersPage" class="page">
        <div class="hero-section" style="padding:30px 20px;min-height:220px;">
            <div class="hero-slideshow"><div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1556742031-c6961e8560b0?w=800&h=600&fit=crop');"></div></div>
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header"><i class="fas fa-arrow-left" onclick="navigateTo('homePage')"></i><span style="font-size:18px;font-weight:700;">All Loan Offers</span></div>
                <p>Compare & choose best deals</p>
            </div>
        </div>
        <div class="loans-grid" style="margin-top:20px;">
            <div class="loan-card loan-personal" onclick="openLoanApplication('Personal Loan')"><div class="loan-image"><i class="fas fa-user-friends"></i></div><div class="loan-info"><h4>Personal Loan</h4><p>from 9.6% p.a.</p></div></div>
            <div class="loan-card loan-home" onclick="openLoanApplication('Home Loan')"><div class="loan-image"><i class="fas fa-home"></i></div><div class="loan-info"><h4>Home Loan</h4><p>from 8.4% p.a.</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=300&h=200&fit=crop');" onclick="openLoanApplication('Car Loan')"><div class="loan-image"><i class="fas fa-car"></i></div><div class="loan-info"><h4>Car Loan</h4><p>from 7.8% p.a.</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1611107683227-e9060e5d9cbf?w=300&h=200&fit=crop');" onclick="openLoanApplication('Gold Loan')"><div class="loan-image"><i class="fas fa-coins"></i></div><div class="loan-info"><h4>Gold Loan</h4><p>from 9.2% p.a.</p></div></div>
            <div class="loan-card loan-business" onclick="openLoanApplication('Business Loan')"><div class="loan-image"><i class="fas fa-briefcase"></i></div><div class="loan-info"><h4>Business Loan</h4><p>from 10.5% p.a.</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=300&h=200&fit=crop');" onclick="openLoanApplication('Education Loan')"><div class="loan-image"><i class="fas fa-graduation-cap"></i></div><div class="loan-info"><h4>Education Loan</h4><p>from 8.9% p.a.</p></div></div>
        </div>
    </div>

    <!-- ========== PAGE 3: CREDIT SCORE ========== -->
    <div id="creditPage" class="page">
        <div class="hero-section" style="padding:30px 20px;min-height:220px;">
            <div class="hero-slideshow"><div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&h=600&fit=crop');"></div></div>
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header"><i class="fas fa-arrow-left" onclick="navigateTo('homePage')"></i><span style="font-size:18px;font-weight:700;">Credit Score</span></div>
                <p>Track & improve your score</p>
            </div>
        </div>
        <div class="credit-card">
            <div class="score-header"><div>Your CIBIL Score</div><div class="score-value" id="creditScore">782</div></div>
            <div class="progress-bar"><div class="progress-fill" id="scoreProgress"></div></div>
            <div style="display:flex;justify-content:space-between;font-size:10px;"><span>300</span><span>550</span><span>700</span><span>850</span></div>
            <div style="margin-top:15px;font-size:12px;">✓ Excellent | +12 vs last month</div>
        </div>
        <div class="section-header"><h2>📈 Improve Your Score</h2></div>
        <div class="loans-grid" style="margin-bottom:20px;">
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=300&h=200&fit=crop');" onclick="showToast('Pay all EMIs on time')"><div class="loan-image"><i class="fas fa-calendar-check"></i></div><div class="loan-info"><h4>Pay on Time</h4><p>Boosts your score</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=300&h=200&fit=crop');" onclick="showToast('Keep usage below 30%')"><div class="loan-image"><i class="fas fa-credit-card"></i></div><div class="loan-info"><h4>Reduce Usage</h4><p>Below 30% limit</p></div></div>
        </div>
        <button class="apply-button" onclick="showToast('Free credit report sent!')" style="margin:20px;width:calc(100% - 40px);">Download Free Report →</button>
    </div>

    <!-- ========== PAGE 4: IMPROVE SCORE ========== -->
    <div id="improvePage" class="page">
        <div class="hero-section" style="padding:30px 20px;min-height:220px;">
            <div class="hero-slideshow"><div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1554224154-26032ffc0c07?w=800&h=600&fit=crop');"></div></div>
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header"><i class="fas fa-arrow-left" onclick="navigateTo('homePage')"></i><span style="font-size:18px;font-weight:700;">Improve Score</span></div>
                <p>Tips to boost credit score</p>
            </div>
        </div>
        <div class="loans-grid" style="margin-top:20px;">
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1554224154-26032ffc0c07?w=300&h=200&fit=crop');" onclick="showToast('Set up auto-pay')"><div class="loan-image"><i class="fas fa-clock"></i></div><div class="loan-info"><h4>Never Miss Payment</h4><p>Auto-pay setup</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=300&h=200&fit=crop');" onclick="showToast('Check for errors')"><div class="loan-image"><i class="fas fa-file-alt"></i></div><div class="loan-info"><h4>Monitor Regularly</h4><p>Check for errors</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=300&h=200&fit=crop');" onclick="showToast('Mix of loans helps')"><div class="loan-image"><i class="fas fa-chart-pie"></i></div><div class="loan-info"><h4>Diverse Credit Mix</h4><p>Different loan types</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1563986768609-322da13575f3?w=300&h=200&fit=crop');" onclick="showToast('Avoid multiple applications')"><div class="loan-image"><i class="fas fa-shield-alt"></i></div><div class="loan-info"><h4>Limit Inquiries</h4><p>Avoid multiple apps</p></div></div>
        </div>
    </div>

    <!-- ========== PAGE 5: APPLY LEADS ========== -->
    <div id="leadsPage" class="page">
        <div class="hero-section" style="padding:30px 20px;min-height:220px;">
            <div class="hero-slideshow"><div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop');"></div></div>
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header"><i class="fas fa-arrow-left" onclick="navigateTo('homePage')"></i><span style="font-size:18px;font-weight:700;">Apply Leads</span></div>
                <p>Track your loan applications</p>
            </div>
        </div>
        
        <div class="leads-container">
            <!-- Lead 1 -->
            <div class="lead-card" onclick="viewLeadDetails('Personal Loan', '₹5,00,000', '9.6%')">
                <div class="lead-header">
                    <span class="lead-name">Rahul Sharma</span>
                    <span class="lead-status pending">Pending</span>
                </div>
                <div class="lead-details"><i class="fas fa-hand-holding-usd"></i> Personal Loan</div>
                <div class="lead-details"><i class="fas fa-calendar-alt"></i> Applied: 15 Mar 2025</div>
                <div class="lead-amount">₹5,00,000</div>
            </div>
            
            <!-- Lead 2 -->
            <div class="lead-card" onclick="viewLeadDetails('Home Loan', '₹35,00,000', '8.4%')">
                <div class="lead-header">
                    <span class="lead-name">Priya Mehta</span>
                    <span class="lead-status completed">Approved</span>
                </div>
                <div class="lead-details"><i class="fas fa-hand-holding-usd"></i> Home Loan</div>
                <div class="lead-details"><i class="fas fa-calendar-alt"></i> Applied: 10 Mar 2025</div>
                <div class="lead-amount">₹35,00,000</div>
            </div>
            
            <!-- Lead 3 -->
            <div class="lead-card" onclick="viewLeadDetails('Car Loan', '₹8,00,000', '7.8%')">
                <div class="lead-header">
                    <span class="lead-name">Amit Patel</span>
                    <span class="lead-status pending">Pending</span>
                </div>
                <div class="lead-details"><i class="fas fa-hand-holding-usd"></i> Car Loan</div>
                <div class="lead-details"><i class="fas fa-calendar-alt"></i> Applied: 5 Mar 2025</div>
                <div class="lead-amount">₹8,00,000</div>
            </div>
            
            <!-- Lead 4 -->
            <div class="lead-card" onclick="viewLeadDetails('Business Loan', '₹15,00,000', '10.5%')">
                <div class="lead-header">
                    <span class="lead-name">Sunil Reddy</span>
                    <span class="lead-status completed">Approved</span>
                </div>
                <div class="lead-details"><i class="fas fa-hand-holding-usd"></i> Business Loan</div>
                <div class="lead-details"><i class="fas fa-calendar-alt"></i> Applied: 25 Feb 2025</div>
                <div class="lead-amount">₹15,00,000</div>
            </div>
            
            <!-- Lead 5 -->
            <div class="lead-card" onclick="viewLeadDetails('Gold Loan', '₹2,50,000', '9.2%')">
                <div class="lead-header">
                    <span class="lead-name">Kavita Singh</span>
                    <span class="lead-status pending">Pending</span>
                </div>
                <div class="lead-details"><i class="fas fa-hand-holding-usd"></i> Gold Loan</div>
                <div class="lead-details"><i class="fas fa-calendar-alt"></i> Applied: 20 Feb 2025</div>
                <div class="lead-amount">₹2,50,000</div>
            </div>
            
            <button class="apply-leads-btn" onclick="openNewLeadModal()">
                <i class="fas fa-plus-circle"></i> Add New Lead
            </button>
        </div>
    </div>

    <!-- ========== PAGE 6: TOOLS ========== -->
    <div id="toolsPage" class="page">
        <div class="hero-section" style="padding:30px 20px;min-height:220px;">
            <div class="hero-slideshow"><div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop');"></div></div>
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header"><i class="fas fa-arrow-left" onclick="navigateTo('homePage')"></i><span style="font-size:18px;font-weight:700;">Financial Tools</span></div>
                <p>Plan your finances better</p>
            </div>
        </div>
        <div class="loans-grid" style="margin-top:20px;">
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=300&h=200&fit=crop');" onclick="showToast('EMI Calculator coming soon')"><div class="loan-image"><i class="fas fa-calculator"></i></div><div class="loan-info"><h4>EMI Calculator</h4><p>Calculate monthly payments</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=300&h=200&fit=crop');" onclick="showToast('SIP Calculator coming soon')"><div class="loan-image"><i class="fas fa-chart-line"></i></div><div class="loan-info"><h4>SIP Calculator</h4><p>Plan investments</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=300&h=200&fit=crop');" onclick="showToast('FD Calculator coming soon')"><div class="loan-image"><i class="fas fa-building-columns"></i></div><div class="loan-info"><h4>FD Calculator</h4><p>Fixed deposit returns</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=300&h=200&fit=crop');" onclick="showToast('Loan Eligibility Calculator')"><div class="loan-image"><i class="fas fa-check-circle"></i></div><div class="loan-info"><h4>Loan Eligibility</h4><p>Check how much you can borrow</p></div></div>
        </div>
    </div>

    <!-- ========== PAGE 7: PROFILE ========== -->
    <div id="profilePage" class="page">
        <div class="hero-section" style="padding:30px 20px;text-align:center;min-height:320px;">
            <div class="hero-slideshow"><div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop');"></div></div>
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header"><i class="fas fa-arrow-left" onclick="navigateTo('homePage')"></i><span style="font-size:18px;font-weight:700;">My Profile</span></div>
                <div style="width:80px;height:80px;background:linear-gradient(135deg,#ff9800,#ff5722);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:20px auto 16px;"><i class="fas fa-user" style="font-size:40px;color:white;"></i></div>
                <h2 style="font-size:24px;">BADUGU</h2>
                <p>badugu@email.com</p><p>+91 98765 43210</p>
            </div>
        </div>
        <div class="loans-grid" style="margin-top:20px;">
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=300&h=200&fit=crop');" onclick="navigateTo('creditPage')"><div class="loan-image"><i class="fas fa-chart-line"></i></div><div class="loan-info"><h4>Credit Score</h4><p>782 - Excellent</p></div></div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1563986768609-322da13575f3?w=300&h=200&fit=crop');" onclick="showToast('2 active loans')"><div class="loan-image"><i class="fas fa-file-invoice"></i></div><div class="loan-info"><h4>Active Loans</h4><p>2 Loans</p></div></div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-item active" onclick="navigateTo('homePage', this)"><i class="fas fa-home"></i><span>Home</span></div>
        <div class="nav-item" onclick="navigateTo('offersPage', this)"><i class="fas fa-tag"></i><span>Offers</span></div>
        <div class="nav-item" onclick="loadApplyLeadsPage()">
   <a href="applyleads.php" class="nav-item">
    <i class="fas fa-users"></i>
    <span>Apply Leads</span>
</a>
    <span>Apply Leads</span>
</div>
        <div class="nav-item" onclick="navigateTo('creditPage', this)"><i class="fas fa-chart-line"></i><span>Credit Score</span></div>
        <div class="nav-item" onclick="navigateTo('profilePage', this)"><i class="fas fa-user"></i><span>You</span></div>
    </div>
</div>

<!-- Application Modal -->
<div id="applicationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Apply for Loan</h3>
            <button class="modal-close" onclick="closeModal('applicationModal')">✕</button>
        </div>
        <form id="loanForm" onsubmit="submitApplication(event)">
            <div class="form-group"><label>Full Name</label><input type="text" class="form-input" placeholder="Enter your name" required></div>
            <div class="form-group"><label>Phone Number</label><input type="tel" class="form-input" placeholder="10-digit number" pattern="[0-9]{10}" required></div>
            <div class="form-group"><label>Email ID</label><input type="email" class="form-input" placeholder="Your email" required></div>
            <div class="form-group"><label>Monthly Income (₹)</label><input type="number" class="form-input" placeholder="Monthly income" required></div>
            <div class="form-group"><label>Loan Amount (₹)</label><input type="number" class="form-input" placeholder="Required amount" required></div>
            <button type="submit" class="apply-button">Submit →</button>
        </form>
    </div>
</div>

<!-- New Lead Modal -->
<div id="newLeadModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Lead</h3>
            <button class="modal-close" onclick="closeModal('newLeadModal')">✕</button>
        </div>
        <form id="newLeadForm" onsubmit="submitNewLead(event)">
            <div class="form-group"><label>Customer Name</label><input type="text" class="form-input" id="leadName" placeholder="Enter customer name" required></div>
            <div class="form-group"><label>Phone Number</label><input type="tel" class="form-input" id="leadPhone" placeholder="10-digit number" pattern="[0-9]{10}" required></div>
            <div class="form-group"><label>Loan Type</label>
                <select class="form-input" id="leadLoanType" required>
                    <option value="">Select Loan Type</option>
                    <option>Personal Loan</option>
                    <option>Home Loan</option>
                    <option>Car Loan</option>
                    <option>Business Loan</option>
                    <option>Gold Loan</option>
                    <option>Education Loan</option>
                </select>
            </div>
            <div class="form-group"><label>Loan Amount (₹)</label><input type="number" class="form-input" id="leadAmount" placeholder="Enter loan amount" required></div>
            <button type="submit" class="apply-button">Add Lead →</button>
        </form>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
    // Slideshow
    let currentSlide=0;
    const slides=document.querySelectorAll('#heroSlideshow .slide');
    const indicators=document.querySelectorAll('.indicator');
    let slideInterval;
    function showSlide(i){slides.forEach((s,j)=>s.classList.toggle('active',j===i));indicators.forEach((d,j)=>d.classList.toggle('active',j===i));currentSlide=i;}
    function nextSlide(){showSlide((currentSlide+1)%slides.length);}
    function startSlideshow(){if(slideInterval)clearInterval(slideInterval);slideInterval=setInterval(nextSlide,4000);}
    indicators.forEach((ind,i)=>ind.addEventListener('click',()=>{showSlide(i);startSlideshow();}));
    if(slides.length>0)startSlideshow();

    // Navigation
    function navigateTo(pageId,element){
        document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
        document.getElementById(pageId).classList.add('active');
        if(element){document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));element.classList.add('active');}
        window.scrollTo({top:0,behavior:'smooth'});
        closeMenu();
    }

    // Menu Drawer
    function toggleMenu(){
        const btn=document.getElementById('menuBtn');
        const drawer=document.getElementById('menuDrawer');
        const overlay=document.getElementById('menuOverlay');
        btn.classList.toggle('open');
        drawer.classList.toggle('open');
        overlay.classList.toggle('open');
    }
    function closeMenu(){
        document.getElementById('menuBtn').classList.remove('open');
        document.getElementById('menuDrawer').classList.remove('open');
        document.getElementById('menuOverlay').classList.remove('open');
    }

    // Logout Function
    function logout() {
    if(confirm('Are you sure you want to logout?')) {
        window.location.href = 'logout.php';
    }
}

    // Lead Functions
    function viewLeadDetails(loanType, amount, rate){
        showToast(`📋 Lead Details: ${loanType} of ${amount} at ${rate} interest rate`);
    }
    
    function openNewLeadModal(){
        document.getElementById('newLeadModal').classList.add('active');
    }
    
    function submitNewLead(e){
        e.preventDefault();
        const name = document.getElementById('leadName').value;
        const phone = document.getElementById('leadPhone').value;
        const loanType = document.getElementById('leadLoanType').value;
        const amount = document.getElementById('leadAmount').value;
        
        if(!name || !phone || !loanType || !amount){
            showToast("Please fill all fields");
            return;
        }
        
        showToast(`✓ Lead added for ${name} - ${loanType} of ₹${parseInt(amount).toLocaleString()}`);
        closeModal('newLeadModal');
        document.getElementById('newLeadForm').reset();
    }

    function showToast(msg){const t=document.getElementById('toast');t.textContent=msg;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000);}
    function openLoanApplication(){document.getElementById('applicationModal').classList.add('active');}
    function closeModal(id){document.getElementById(id).classList.remove('active');}
    function submitApplication(e){e.preventDefault();closeModal('applicationModal');showToast("✓ Application submitted! We'll contact you within 24 hours.");}
    document.querySelectorAll('.modal').forEach(m=>m.addEventListener('click',function(e){if(e.target===this)this.classList.remove('active');}));

    // Auto Scroll
    function setupAutoScroll(){
        const sc=document.getElementById('autoScrollContainer');
        const orig=document.getElementById('scrollContent');
        const dup=document.getElementById('scrollContentDuplicate');
        if(orig&&dup&&sc){
            dup.appendChild(orig.cloneNode(true));
            sc.classList.add('auto-scroll');
            sc.addEventListener('mouseenter',()=>sc.style.animationPlayState='paused');
            sc.addEventListener('mouseleave',()=>sc.style.animationPlayState='running');
            sc.addEventListener('touchstart',()=>sc.style.animationPlayState='paused');
            sc.addEventListener('touchend',()=>setTimeout(()=>sc.style.animationPlayState='running',3000));
        }
    }

    const creditScore=782;
    document.getElementById('scoreProgress').style.width=((creditScore-300)/(850-300)*100)+'%';
    setupAutoScroll();
</script>
</body>
</html>