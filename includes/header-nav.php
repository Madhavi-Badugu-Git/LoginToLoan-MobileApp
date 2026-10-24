<?php
// FILE: includes/header-nav.php
// Complete Header Component - Includes all CSS, HTML structure, menu drawer, logout modal, and toast
// Include this at the top of your pages after session start

// These variables should be defined in your main page before including this file:
// $fullname, $email, $phone, $profile_image, $initials, $firstname
$email = $_SESSION['email'] ?? '';
$firstLetter = strtoupper(substr($email, 0, 1));
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
      .profile-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f47820;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
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

        .page { display: none; animation: fadeIn 0.3s ease; }
        .page.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== TOP HEADER ===== */
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

        .header-logo { flex: 1; text-align: center; }
        .header-logo img { height: 40px; width: auto; object-fit: contain; }

        .header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff9800, #ff5722);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            cursor: pointer;
            flex-shrink: 0;
            border: 2.5px solid #eef2f6;
            overflow: hidden;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(255,107,53,0.35);
            transition: transform 0.2s, box-shadow 0.2s;
            line-height: 1;
        }
        .header-avatar:active { transform: scale(0.93); }
        .header-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }

        /* ===== MENU DRAWER ===== */
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
            padding-top: 20px;
            overflow-y: auto;
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

        .drawer-user {
            padding: 20px 20px 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .drawer-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff9800, #ff5722);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
            overflow: hidden;
        }
        .drawer-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .drawer-user-info .name {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
        }
        .drawer-user-info .email {
            font-size: 11px;
            color: #999;
            margin-top: 2px;
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
            text-decoration: none;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }
        .drawer-item:active { background: #f5f7ff; }
        .drawer-item i { width: 22px; color: #1a237e; font-size: 17px; }

        .drawer-item.logout-drawer-item {
            color: #ef4444 !important;
            border-top: 1px solid #f0f0f0;
            margin-top: 12px;
        }
        .drawer-item.logout-drawer-item i {
            color: #ef4444 !important;
        }
        .drawer-item.logout-drawer-item:active {
            background: rgba(239, 68, 68, 0.1) !important;
        }

        .social-section {
            padding: 16px 24px;
            border-top: 1px solid #f0f0f0;
            margin-top: 16px;
        }

        .social-title {
            font-size: 12px;
            font-weight: 600;
            color: #ff9800;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .social-icons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ff9800;
            color: #1a237e;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }
        .social-icon:active { transform: scale(0.92); }
        .social-icon.facebook:active { background: #1877f2; color: white; }
        .social-icon.twitter:active { background: #1da1f2; color: white; }
        .social-icon.instagram:active { background: #e4405f; color: white; }
        .social-icon.linkedin:active { background: #0077b5; color: white; }

        /* ===== LOGOUT CONFIRMATION MODAL ===== */
        .custom-confirm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .custom-confirm-modal {
            background: var(--white);
            border-radius: 24px;
            width: 320px;
            max-width: 85%;
            padding: 24px;
            text-align: center;
            animation: modalPop 0.3s ease;
            box-shadow: var(--shadow-lg);
        }
        @keyframes modalPop {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .custom-confirm-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #fee2e2;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }
        .custom-confirm-icon i { font-size: 32px; color: #ef4444; }
        .custom-confirm-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }
        .custom-confirm-message {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 24px;
            line-height: 1.5;
        }
        .custom-confirm-buttons { display: flex; gap: 12px; }
        .custom-confirm-btn {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-family: 'Inter', sans-serif;
        }
        .custom-confirm-btn:active { transform: scale(0.97); }
        .custom-confirm-btn-cancel { background: #f0f0f0; color: var(--text-secondary); }
        .custom-confirm-btn-logout {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

     

        /* ===== HERO SECTION ===== */
        .hero-section {
            position: relative;
            padding: 30px 20px 35px;
            color: white;
            overflow: hidden;
            border-radius: 0 0 30px 30px;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .hero-section .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(26,35,126,0.75) 0%, rgba(57,73,171,0.85) 100%);
            z-index: 1;
        }
        .hero-section .content {
            position: relative;
            z-index: 2;
        }

        .sub-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        .sub-header i {
            cursor: pointer;
            font-size: 20px;
        }
        .sub-header span {
            font-size: 18px;
            font-weight: 700;
        }

        /* ===== SECTION HEADERS ===== */
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
        .section-header a {
            font-size: 12px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        /* ===== RECOMMENDED GRID ===== */
        .recommended-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            padding: 0 16px;
        }
        .recommend-card {
            background: var(--white);
            border-radius: 20px;
            padding: 16px;
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .recommend-card:active { transform: scale(0.96); background: #fef3e8; }
        .recommend-card i { font-size: 32px; margin-bottom: 10px; }
        .recommend-card .card-title {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 4px;
            color: var(--text-primary);
        }
        .recommend-card .card-subtitle {
            font-size: 10px;
            color: var(--text-secondary);
        }
        .cashback-badge {
            background: linear-gradient(135deg, #ff9800, #ff5722);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            display: inline-block;
            margin-top: 8px;
        }

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
        .loan-image i { font-size: 28px; }
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

        /* ===== CREDIT CARD ===== */
        .credit-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 16px;
            border-radius: 20px;
            padding: 20px;
            color: white;
        }
        .score-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .score-value { font-size: 42px; font-weight: 800; }
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

        /* ===== LEADS PAGE ===== */
        .leads-container { padding: 20px; }
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
        .lead-details i {
            width: 20px;
            color: var(--accent);
            margin-right: 8px;
        }
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
            text-decoration: none;
            background: transparent;
            border: none;
        }
        .nav-item i { font-size: 20px; }
        .nav-item.active {
            color: var(--primary);
            background: rgba(26,35,126,0.1);
        }

        /* ===== PROFILE PAGE ===== */
        .profile-info-card {
            background: #fff;
            border-radius: 20px;
            margin: 16px;
            padding: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }
        .profile-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .profile-row:last-child { border-bottom: none; }
        .profile-row i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 14px;
            flex-shrink: 0;
        }
        .profile-row .p-label {
            font-size: 11px;
            color: #999;
            margin-bottom: 2px;
        }
        .profile-row .p-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* ===== MODAL STYLES ===== */
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
            border-radius: 28px;
            padding: 24px;
            width: 90%;
            max-width: 380px;
            max-height: 85vh;
            overflow-y: auto;
            animation: modalPop 0.3s ease;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border);
        }
        .modal-header h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }
        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--border);
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: 0.2s;
        }
        .modal-close:hover { background: #e0e0e0; }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-secondary);
        }
        .form-group label i { margin-right: 6px; color: var(--accent); }
        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .apply-button {
            background: linear-gradient(135deg, var(--accent), #ff8c42);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 40px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
            font-size: 14px;
            margin-top: 10px;
        }
        .apply-button:active { transform: scale(0.98); }

        .footer-note {
            text-align: center;
            font-size: 10px;
            color: var(--text-tertiary);
            padding: 16px;
            border-top: 1px solid var(--border);
            margin-top: 20px;
        }

        @media (max-width: 400px) {
            .nav-item { font-size: 9px; padding: 4px 8px; min-width: 50px; }
            .nav-item i { font-size: 16px; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== TOP HEADER ===== -->
    <header class="top-header">
        <button class="menu-btn" id="menuBtn" onclick="toggleMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

        <div class="header-logo">
            <img src="https://mobile.logintoloans.com/assets/images/logintoloan.jpeg" alt="Login To Loans"
                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 200 50\'%3E%3Crect width=\'200\' height=\'50\' fill=\'%231a237e\' rx=\'8\'/%3E%3Ctext x=\'100\' y=\'32\' font-family=\'Arial\' font-size=\'18\' font-weight=\'bold\' fill=\'%23ffffff\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">
        </div>

        <!-- PROFILE AVATAR — TOP RIGHT -->
        <div class="header-right">
            <a href="https://localhost/mobile.login-to-loan/user/profile.php" class="header-avatar" title="Profile">
            <div class="profile-avatar">
              <?php echo $firstLetter; ?>
          	</div>
            </a>
        </div>
    </header>

    <!-- ===== MENU DRAWER ===== -->
    <div class="menu-overlay" id="menuOverlay" onclick="closeMenu()"></div>
    <div class="menu-drawer" id="menuDrawer">
        <button class="drawer-close" onclick="closeMenu()">✕</button>

        <div class="drawer-user">
            <div class="drawer-avatar">
             <?php echo $firstLetter; ?>
            </div>
            <div class="drawer-user-info">
                <div class="name"><?php echo $fullname ?? 'User'; ?></div>
                <div class="email"><?php echo $email ?? 'user@email.com'; ?></div>
            </div>
        </div>

        <nav class="drawer-nav">
           
           
           
            
           <a href="https://mobile.logintoloans.com/resources/news.php" class="drawer-item">
    <i class="fas fa-newspaper"></i> News
</a>
            <a href="https://mobile.logintoloans.com/privacy.php" class="drawer-item"><i class="fas fa-shield-alt"></i> Privacy & Policy</a>
            <a href="https://mobile.logintoloans.com/terms.php" class="drawer-item"><i class="fas fa-file-contract"></i> Terms & Conditions</a>

            <button class="drawer-item logout-drawer-item" onclick="closeMenu();logout()">
                <i class="fas fa-sign-out-alt"></i> LOGOUT
            </button>
        </nav>

        <div class="social-section">
            <div class="social-title">FOLLOW US ON</div>
            <div class="social-icons">
                <button class="social-icon facebook" onclick="window.open('https://www.facebook.com/people/Logintoloans-K/61574524718831/','_blank')">
                    <i class="fab fa-facebook-f"></i>
                </button>
                <button class="social-icon twitter" onclick="window.open('https://www.instagram.com/info.logintoloans/','_blank')">
                    <i class="fab fa-twitter"></i>
                </button>
                <button class="social-icon instagram" onclick="window.open('https://www.instagram.com/info.logintoloans/','_blank')">
                    <i class="fab fa-instagram"></i>
                </button>
                <button class="social-icon linkedin" onclick="window.open('https://www.linkedin.com/in/loginto-loans-login-to-losns-3ba7a8357','_blank')">
                    <i class="fab fa-linkedin-in"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ===== LOGOUT CONFIRMATION MODAL ===== -->
    <div id="logoutConfirmModal" class="custom-confirm-overlay">
        <div class="custom-confirm-modal">
            <div class="custom-confirm-icon">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <div class="custom-confirm-title">Logout Confirmation</div>
            <div class="custom-confirm-message">Are you sure you want to logout from your account?</div>
            <div class="custom-confirm-buttons">
                <button class="custom-confirm-btn custom-confirm-btn-cancel" onclick="closeLogoutModal()">Cancel</button>
                <button class="custom-confirm-btn custom-confirm-btn-logout" onclick="confirmLogout()">Logout</button>
            </div>
        </div>
    </div>


  

    <script>
        // ===== MENU DRAWER =====
        function toggleMenu() {
            document.getElementById('menuBtn').classList.toggle('open');
            document.getElementById('menuDrawer').classList.toggle('open');
            document.getElementById('menuOverlay').classList.toggle('open');
        }

        function closeMenu() {
            document.getElementById('menuBtn').classList.remove('open');
            document.getElementById('menuDrawer').classList.remove('open');
            document.getElementById('menuOverlay').classList.remove('open');
        }

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMenu();
        });

        // ===== LOGOUT =====
        function logout() {
            document.getElementById('logoutConfirmModal').style.display = 'flex';
        }

        function closeLogoutModal() {
            document.getElementById('logoutConfirmModal').style.display = 'none';
        }

        function confirmLogout() {
            closeLogoutModal();
            showToast('Logging out...');
            setTimeout(function() {
                window.location.href = 'logout.php';
            }, 500);
        }

        document.getElementById('logoutConfirmModal').addEventListener('click', function(e) {
            if (e.target === this) closeLogoutModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                var modal = document.getElementById('logoutConfirmModal');
                if (modal.style.display === 'flex') closeLogoutModal();
            }
        });

    
        // ===== NAVIGATION =====
        function navigateTo(pageId, element) {
            var pages = document.querySelectorAll('.page');
            for (var i = 0; i < pages.length; i++) {
                pages[i].classList.remove('active');
            }
            var target = document.getElementById(pageId);
            if (target) target.classList.add('active');
            if (element) {
                var navItems = document.querySelectorAll('.nav-item');
                for (var j = 0; j < navItems.length; j++) {
                    navItems[j].classList.remove('active');
                }
                element.classList.add('active');
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
            closeMenu();
        }
    </script>