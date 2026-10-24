<?php
// FILE: includes/navbar.php - FULLY RESPONSIVE WITH MOBILE MENU
// FIXED: Top network bar visible on ALL pages for ALL users
// UPDATED: Displays user name instead of avatar image when logged in
// ADDED: Hamburger "More Options" menu works on BOTH desktop and mobile
// FIXED: Insurance, Investment, Loans on left - Login/Register/More Options on right
// NEW: Realtyive-style side panel mega menu for More Options
// NEW: Beautiful popup modal for Login/Register with all features from login.php and register.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo SITE_NAME; ?> - Smart Investing Starts Here</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* ========== RESET ========== */
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%);
            min-height: 100vh;
            overflow-x: hidden;
            padding-top: 115px;
        }

        /* =====================================================
           TOP NETWORK BAR  (fixed, 36 px tall)
        ===================================================== */
        .top-network-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 36px;
            background: linear-gradient(135deg, #042b6c 0%, #fd9130 100%);
            z-index: 1002;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            border-bottom: 1px solid rgba(253, 145, 48, 0.25);
        }

        .top-bar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            height: 100%;
        }

        .top-scroll-content {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            height: 100%;
            overflow-x: auto;
            white-space: nowrap;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .top-scroll-content::-webkit-scrollbar {
            display: none;
        }

        .top-network-bar a {
            color: rgba(255, 255, 255, 0.82);
            text-decoration: none;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: color 0.2s;
        }

        .top-network-bar a:hover {
            color: #fff;
        }

        .network-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #ffd4a0;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            flex-shrink: 0;
        }

        .top-bar-sep {
            width: 1px;
            height: 14px;
            background: rgba(255, 255, 255, 0.22);
            flex-shrink: 0;
        }

        /* =====================================================
           MAIN NAVBAR  (fixed, sits at top:36px, height 72px)
        ===================================================== */
        .premium-navbar {
            position: fixed;
            top: 36px;
            left: 0;
            width: 100%;
            height: 72px;
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 4px 24px rgba(4, 43, 108, 0.08);
            border-bottom: 1px solid rgba(4, 43, 108, 0.07);
            z-index: 1000;
            transition: box-shadow 0.3s, background 0.3s;
        }

        .premium-navbar.scrolled {
            background: rgba(255, 255, 255, 0.99);
            box-shadow: 0 6px 32px rgba(4, 43, 108, 0.13);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 0;
        }

        .logo-container {
            flex-shrink: 0;
            cursor: pointer;
            transition: transform 0.2s;
            display: flex;
            align-items: center;
        }

        .logo-container:hover {
            transform: scale(1.02);
        }

        .logo-image img {
            height: 44px;
            width: auto;
            object-fit: contain;
            display: block;
        }

        .mobile-menu-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            margin-left: auto;
            flex-shrink: 0;
            background: rgba(4, 43, 108, 0.07);
            border: 1px solid rgba(4, 43, 108, 0.15);
            border-radius: 50%;
            font-size: 1.2rem;
            color: #042b6c;
            cursor: pointer;
            transition: all 0.25s;
        }

        .mobile-menu-btn:hover {
            background: linear-gradient(135deg, #042b6c, #fd9130);
            color: #fff;
            border-color: transparent;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 2px;
            flex: 1 1 auto;
            min-width: 0;
            margin: 0 12px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            margin-left: auto;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 11px;
            background: none;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e293b;
            cursor: pointer;
            border-radius: 8px;
            white-space: nowrap;
            transition: color 0.2s, background 0.2s;
        }

        .dropdown-btn:hover {
            color: #042b6c;
            background: rgba(4, 43, 108, 0.05);
        }

        .dropdown-btn>i:first-child {
            font-size: 0.95rem;
            color: #042b6c;
        }

        .dropdown-btn>i:last-child {
            font-size: 0.62rem;
            color: #94a3b8;
            transition: transform 0.28s;
        }

        .dropdown:hover .dropdown-btn>i:last-child {
            transform: rotate(180deg);
            color: #042b6c;
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            background: #fff;
            min-width: 240px;
            border-radius: 14px;
            box-shadow: 0 14px 40px rgba(4, 43, 108, 0.14);
            border: 1px solid rgba(4, 43, 108, 0.08);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: opacity 0.2s, transform 0.2s, visibility 0.2s;
            z-index: 1010;
            overflow: hidden;
        }

        .dropdown-menu.mega-menu {
            min-width: 320px;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu .menu-grid {
            padding: 10px 8px 6px;
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 12px;
            color: #334155;
            text-decoration: none;
            font-size: 0.855rem;
            border-radius: 8px;
            transition: background 0.15s, color 0.15s, padding-left 0.15s;
        }

        .dropdown-menu a i {
            width: 17px;
            font-size: 0.85rem;
            color: #042b6c;
            flex-shrink: 0;
        }

        .dropdown-menu a:hover {
            background: rgba(4, 43, 108, 0.06);
            color: #042b6c;
            padding-left: 16px;
        }

        .view-all {
            border-top: 1px solid #eef2f6;
            padding: 7px 8px 8px;
            background: #f8faff;
        }

        .view-all a {
            font-weight: 600;
            color: #042b6c !important;
            justify-content: center;
        }

        .view-all a:hover {
            background: rgba(4, 43, 108, 0.07) !important;
            padding-left: 12px !important;
        }

        .auth-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 50px;
            padding: 3px;
            background: linear-gradient(135deg, #042b6c, #fd9130);
            box-shadow: 0 3px 12px rgba(4, 43, 108, 0.22);
            flex-shrink: 0;
            cursor: pointer;
        }

        .auth-link {
            color: #fff;
            text-decoration: none;
            padding: 7px 18px;
            font-size: 0.84rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            border-radius: 50px;
            white-space: nowrap;
            transition: background 0.2s;
        }

        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            background: rgba(4, 43, 108, 0.05);
            border: 1px solid rgba(4, 43, 108, 0.1);
            padding: 7px 13px 7px 10px;
            border-radius: 50px;
            transition: background 0.2s, border-color 0.2s;
            flex-shrink: 0;
        }

        .user-menu:hover {
            background: rgba(4, 43, 108, 0.1);
            border-color: rgba(4, 43, 108, 0.2);
        }

        .user-avatar-icon {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #042b6c, #fd9130);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.76rem;
            flex-shrink: 0;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: #1e293b;
            max-width: 130px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-menu>i:last-child {
            font-size: 0.62rem;
            color: #64748b;
            transition: transform 0.25s;
        }

        .user-menu:hover>i:last-child {
            transform: rotate(180deg);
        }

        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: #fff;
            min-width: 210px;
            border-radius: 14px;
            box-shadow: 0 14px 40px rgba(4, 43, 108, 0.14);
            border: 1px solid rgba(4, 43, 108, 0.08);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: all 0.2s;
            z-index: 1010;
            overflow: hidden;
        }

        .user-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            color: #1e293b;
            text-decoration: none;
            font-size: 0.875rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s, color 0.15s;
        }

        .user-dropdown-menu a:last-child {
            border-bottom: none;
        }

        .user-dropdown-menu a i {
            width: 17px;
            font-size: 0.88rem;
            color: #042b6c;
        }

        .user-dropdown-menu a:hover {
            background: rgba(4, 43, 108, 0.05);
            color: #042b6c;
        }

        .more-menu-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 15px;
            background: rgba(4, 43, 108, 0.06);
            border: 1px solid rgba(4, 43, 108, 0.16);
            border-radius: 50px;
            font-family: 'Inter', sans-serif;
            font-size: 0.855rem;
            font-weight: 500;
            color: #042b6c;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
            transition: all 0.22s;
        }

        .more-menu-btn:hover {
            background: linear-gradient(135deg, #042b6c, #1e5aad);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 4px 14px rgba(4, 43, 108, 0.25);
        }

        /* =====================================================
           SIDE PANEL
        ===================================================== */
        .side-panel-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(2, 18, 50, 0.42);
            z-index: 1050;
            backdrop-filter: blur(3px);
        }

        .side-panel-overlay.show {
            display: block;
            animation: spOverlay 0.25s ease forwards;
        }

        @keyframes spOverlay {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .side-panel {
            position: fixed;
            top: 0;
            right: -420px;
            width: 400px;
            max-width: 100vw;
            height: 100dvh;
            background: #fff;
            z-index: 1051;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: -6px 0 40px rgba(4, 43, 108, 0.18);
            transition: right 0.32s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .side-panel.show {
            right: 0;
        }

        .side-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            background: linear-gradient(135deg, #042b6c 0%, #0d3d8f 100%);
            flex-shrink: 0;
        }

        .side-panel-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .side-panel-header h3 i {
            color: #fd9130;
        }

        .side-panel-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.14);
            border: none;
            color: #fff;
            font-size: 0.92rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .side-panel-close:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .side-panel-tabs {
            display: flex;
            background: #fff;
            border-bottom: 2px solid #eef2f6;
            flex-shrink: 0;
        }

        .side-panel-tab {
            flex: 1;
            padding: 13px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.22s;
        }

        .side-panel-tab i {
            font-size: 0.82rem;
        }

        .side-panel-tab.active {
            color: #042b6c;
            border-bottom-color: #fd9130;
        }

        .side-panel-tab:hover:not(.active) {
            color: #042b6c;
            background: rgba(4, 43, 108, 0.03);
        }

        .side-panel-body {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .panel-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.9px;
            color: #fd9130;
            padding: 14px 20px 6px;
        }

        .panel-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
            text-decoration: none;
            color: #1e293b;
            transition: background 0.15s, padding-left 0.15s;
        }

        .panel-item:hover {
            background: rgba(4, 43, 108, 0.04);
            padding-left: 24px;
        }

        .panel-item-left {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .panel-item-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(4, 43, 108, 0.08), rgba(253, 145, 48, 0.08));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .panel-item-icon i {
            font-size: 0.88rem;
            color: #042b6c;
        }

        .panel-item-text {
            font-size: 0.875rem;
            font-weight: 500;
            color: #1e293b;
            line-height: 1.3;
        }

        .panel-item-sub {
            font-size: 0.73rem;
            color: #94a3b8;
            margin-top: 2px;
            line-height: 1.3;
        }

        .panel-item-arrow {
            color: #c0ccd8;
            font-size: 0.72rem;
            flex-shrink: 0;
            transition: transform 0.2s, color 0.2s;
        }

        .panel-item:hover .panel-item-arrow {
            color: #042b6c;
            transform: translateX(2px);
        }

        .panel-item.expanded .panel-item-arrow {
            transform: rotate(90deg);
            color: #042b6c;
        }

        .panel-sub-items {
            display: none;
            background: #f8fafc;
        }

        .panel-sub-items.expanded {
            display: block;
        }

        .panel-sub-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px 10px 52px;
            color: #475569;
            text-decoration: none;
            font-size: 0.845rem;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s, color 0.15s, padding-left 0.15s;
        }

        .panel-sub-item i {
            font-size: 0.75rem;
            color: #042b6c;
            width: 14px;
            flex-shrink: 0;
        }

        .panel-sub-item:hover {
            background: rgba(4, 43, 108, 0.06);
            color: #042b6c;
            padding-left: 58px;
        }

        .side-panel-footer {
            padding: 12px 18px;
            border-top: 1px solid #eef2f6;
            background: #f8faff;
            flex-shrink: 0;
        }

        .side-panel-view-all {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #042b6c, #fd9130);
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.86rem;
            font-weight: 600;
            transition: opacity 0.2s, transform 0.2s;
        }

        .side-panel-view-all:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            color: #fff;
        }

        /* =====================================================
           BEAUTIFUL AUTH MODAL - PREMIUM DESIGN
        ===================================================== */
        .auth-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(12px);
            z-index: 1100;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .auth-modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .auth-modal {
            background: #fff;
            width: 90%;
            max-width: 460px;
            max-height: 90vh;
            border-radius: 48px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.4);
            transform: scale(0.95) translateY(20px);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .auth-modal-overlay.active .auth-modal {
            transform: scale(1) translateY(0);
        }

        .auth-modal::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #042b6c, #fd9130, #042b6c);
            z-index: 1;
        }

        .auth-modal-header {
            background: linear-gradient(135deg, #042b6c 0%, #1a4f8c 100%);
            padding: 28px 28px 24px;
            color: white;
            text-align: center;
            position: relative;
            flex-shrink: 0;
        }

        .auth-modal-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .auth-modal-header p {
            font-size: 0.85rem;
            opacity: 0.85;
            margin-top: 6px;
        }

        .auth-modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-modal-close:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: rotate(90deg);
        }

        .auth-modal-tabs {
            display: flex;
            background: #fff;
            padding: 0 24px;
            gap: 16px;
            border-bottom: 2px solid #f0f2f5;
            flex-shrink: 0;
        }

        .auth-tab {
            flex: 1;
            padding: 16px 0 14px;
            text-align: center;
            background: none;
            border: none;
            font-size: 0.95rem;
            font-weight: 700;
            color: #94a3b8;
            cursor: pointer;
            transition: all 0.25s ease;
            position: relative;
        }

        .auth-tab::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #042b6c, #fd9130);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .auth-tab.active {
            color: #042b6c;
        }

        .auth-tab.active::after {
            transform: scaleX(1);
        }

        .auth-tab:hover:not(.active) {
            color: #fd9130;
        }

        .auth-modal-body {
            padding: 28px 28px 32px;
            overflow-y: auto;
            flex: 1;
            scrollbar-width: thin;
        }

        .auth-modal-body::-webkit-scrollbar {
            width: 5px;
        }

        .auth-modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .auth-modal-body::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #042b6c, #fd9130);
            border-radius: 10px;
        }

        .auth-form {
            display: none;
            animation: fadeSlideUp 0.3s ease;
        }

        .auth-form.active {
            display: block;
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1e2f44;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .form-group label i {
            color: #fd9130;
            margin-right: 6px;
            font-size: 0.75rem;
        }

        .input-icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-wrapper i:first-child {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            outline: none;
            background: #ffffff;
            font-weight: 500;
        }

        .form-group input:focus {
            border-color: #042b6c;
            box-shadow: 0 0 0 4px rgba(4, 43, 108, 0.08);
        }

        .form-group input:hover {
            border-color: #fd9130;
        }

        .password-toggle-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            transition: color 0.2s;
            background: none;
            border: none;
        }

        .password-toggle-icon:hover {
            color: #042b6c;
        }

        .forgot-link {
            text-align: right;
            margin-top: -8px;
            margin-bottom: 20px;
        }

        .forgot-link a {
            font-size: 0.72rem;
            color: #fd9130;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link a:hover {
            color: #042b6c;
        }

        .auth-btn {
            width: 100%;
            background: linear-gradient(135deg, #042b6c, #fd9130);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 40px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .auth-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .auth-btn:hover::before {
            left: 100%;
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(253, 145, 48, 0.35);
            gap: 12px;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
            border-radius: 4px;
        }

        .strength-text {
            color: #64748b;
            min-width: 70px;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .terms-check {
            margin: 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .terms-check input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #fd9130;
        }

        .terms-check label {
            font-size: 0.75rem;
            color: #64748b;
            cursor: pointer;
        }

        .terms-check a {
            color: #042b6c;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .terms-check a:hover {
            color: #fd9130;
        }

        .match-message {
            font-size: 0.7rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .nav-get-started-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 14px 14px;
            margin-left: 320px;
            background: linear-gradient(135deg, #0f4db8, #1d6cff);
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 10px 25px rgba(13, 71, 161, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15);
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-get-started-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -120%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transition: 0.6s;
        }

        .nav-get-started-btn:hover::before {
            left: 120%;
        }

        .nav-get-started-btn:hover {
            transform: translateY(-3px) scale(1.02);
            background: linear-gradient(135deg, #0b3f97, #256dff);
            box-shadow: 0 18px 35px rgba(13, 71, 161, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        @media(max-width: 992px) {
            .nav-get-started-btn {
                margin-left: 0;
                padding: 10px 10px;
                border-radius: 14px;
                font-size: 14px;
            }
        }

        /* =====================================================
           RESPONSIVE BREAKPOINTS
        ===================================================== */
        @media (max-width: 1100px) {
            .navbar-container { padding: 0 14px; }
            .nav-menu { gap: 0; margin: 0 6px; }
            .dropdown-btn { font-size: 0.83rem; padding: 8px 9px; }
            .user-name { max-width: 100px; }
            .more-menu-btn { padding: 8px 11px; }
            .more-menu-btn span { display: none; }
        }

        @media (max-width: 900px) {
            .dropdown-btn span { display: none; }
            .dropdown-btn>i:last-child { display: none; }
            .dropdown-btn { padding: 8px 10px; gap: 0; }
            .user-name { display: none; }
        }

        @media (max-width: 768px) {
            body { padding-top: 96px; }
            .top-network-bar { height: 32px; }
            .top-network-bar a, .network-label { font-size: 0.72rem; }
            .premium-navbar { top: 32px; height: 64px; }
            .navbar-container { height: 64px; padding: 0 14px; }
            .mobile-menu-btn { display: flex; }
            .more-menu-btn.desktop-only { display: none !important; }
            .nav-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                width: 100%;
                flex-direction: column;
                background: rgba(255, 255, 255, 0.99);
                backdrop-filter: blur(16px);
                max-height: calc(100dvh - 96px);
                overflow-y: auto;
                display: none;
                z-index: 998;
            }
            .nav-menu.active { display: flex; animation: slideDown 0.28s ease; }
            @keyframes slideDown {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .dropdown { width: 100%; }
            .dropdown-btn {
                width: 100%;
                justify-content: space-between;
                padding: 14px 18px;
                border-bottom: 1px solid #f1f5f9;
                border-radius: 0;
            }
            .dropdown-btn span { display: inline !important; }
            .dropdown-btn>i:last-child { display: inline-block !important; }
            .dropdown-menu, .dropdown-menu.mega-menu {
                position: static !important;
                opacity: 1 !important;
                visibility: visible !important;
                background: #f8fafc;
                min-width: 100% !important;
                width: 100%;
                display: none;
            }
            .dropdown-menu.active-mobile { display: block; }
            .nav-right {
                flex-direction: column;
                width: 100%;
                padding: 10px 0 6px;
                border-top: 2px solid rgba(4, 43, 108, 0.06);
            }
            .auth-pill { margin: 8px 16px; justify-content: center; }
            .more-menu-btn.mobile-only { display: flex !important; margin: 6px 16px 10px; justify-content: center; }
            .more-menu-btn.mobile-only span { display: inline !important; }
            .user-menu-desktop { display: none !important; }
            .user-menu-mobile { display: flex !important; align-items: center; gap: 10px; margin: 8px 16px; padding: 11px 16px; background: rgba(4, 43, 108, 0.06); border: 1px solid rgba(4, 43, 108, 0.12); border-radius: 50px; cursor: pointer; }
            .user-menu-mobile .user-name { display: inline-block !important; flex: 1; }
            #mobileUserDropdownMenu { margin: 0 16px 12px !important; border-radius: 12px !important; overflow: hidden; background: #fff; }
            .side-panel { width: 100%; right: -100%; }
            .side-panel.show { right: 0; }
            .logo-image img { height: 38px; }
        }

        @media (max-width: 480px) {
            body { padding-top: 88px; }
            .top-network-bar { height: 28px; }
            .premium-navbar { top: 28px; height: 60px; }
            .navbar-container { height: 60px; padding: 0 12px; }
            .mobile-menu-btn { width: 38px; height: 38px; font-size: 1.1rem; }
            .logo-image img { height: 34px; }
        }

        @media (min-width: 769px) {
            .user-menu-mobile { display: none !important; }
            .more-menu-btn.mobile-only { display: none !important; }
            .more-menu-btn.desktop-only { display: inline-flex !important; }
        }

        .toast-notification {
            position: fixed;
            top: 120px;
            right: 20px;
            z-index: 10000;
            padding: 13px 22px;
            border-radius: 14px;
            font-size: 0.875rem;
            font-weight: 500;
            max-width: 360px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.14);
            animation: toastIn 0.38s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .menu-links-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2px 16px;
            padding: 8px 12px;
        }

        .menu-links-grid a {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            color: #334155;
            text-decoration: none;
            font-size: 0.855rem;
            border-radius: 8px;
            transition: background 0.15s, color 0.15s;
            white-space: nowrap;
        }

        .menu-links-grid a i {
            width: 17px;
            font-size: 0.85rem;
            color: #042b6c;
            flex-shrink: 0;
        }

        .menu-links-grid a:hover {
            background: rgba(4, 43, 108, 0.06);
            color: #042b6c;
            padding-left: 14px;
        }

        @media (max-width: 768px) {
            .menu-links-grid {
                grid-template-columns: 1fr;
                gap: 2px;
                padding: 4px 8px;
            }
            .menu-links-grid a { white-space: normal; padding: 10px 12px; }
        }
    </style>
</head>

<body>

    <!-- BEAUTIFUL AUTH MODAL (Login/Register Popup) -->
    <div class="auth-modal-overlay" id="authModalOverlay" onclick="closeAuthModal(event)">
        <div class="auth-modal" onclick="event.stopPropagation()">
            <button class="auth-modal-close" onclick="closeAuthModal()">&times;</button>
            <div class="auth-modal-header">
                <h2><i class="fas fa-user-astronaut"></i> Welcome Back</h2>
                <p>Access your investment dashboard</p>
            </div>
            <div class="auth-modal-tabs">
                <button class="auth-tab active" onclick="switchAuthTab('login')">Sign In</button>
                <button class="auth-tab" onclick="switchAuthTab('register')">Create Account</button>
            </div>
            <div class="auth-modal-body">
                <!-- Login Form -->
                <form class="auth-form active" id="loginForm" action="<?php echo BASE_URL; ?>auth/login_process.php" method="POST">
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="name@company.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Password</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required>
                            <button type="button" class="password-toggle-icon" onclick="togglePassword('loginPassword', this)">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="forgot-link">
                        <a href="#"><i class="fas fa-key"></i> Forgot Password?</a>
                    </div>
                    <button type="submit" class="auth-btn">Sign In <i class="fas fa-arrow-right"></i></button>
                </form>

                <!-- Register Form -->
                <form class="auth-form" id="registerForm" action="<?php echo BASE_URL; ?>auth/register_process.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" name="fullname" placeholder="John Doe" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email Address</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" placeholder="hello@company.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Phone Number</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-phone"></i>
                            <input type="tel" name="phone" placeholder="+91 98765 43210" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Password</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="regPassword" placeholder="Create a strong password" required onkeyup="checkRegPasswordStrength()">
                            <button type="button" class="password-toggle-icon" onclick="togglePassword('regPassword', this)">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="regStrengthFill"></div>
                            </div>
                            <span class="strength-text" id="regStrengthText">Enter password</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Confirm Password</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="confirm_password" id="regConfirmPassword" placeholder="Confirm your password" required onkeyup="checkRegPasswordMatch()">
                            <button type="button" class="password-toggle-icon" onclick="togglePassword('regConfirmPassword', this)">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        <div id="regMatchMsg" class="match-message"></div>
                    </div>
                    <div class="terms-check">
                        <input type="checkbox" id="regTerms" required>
                        <label for="regTerms">I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></label>
                    </div>
                    <button type="submit" class="auth-btn">Create Account <i class="fas fa-arrow-right"></i></button>
                </form>
            </div>
        </div>
    </div>

    <!-- SIDE PANEL OVERLAY -->
    <div class="side-panel-overlay" id="sidePanelOverlay" onclick="closeSidePanel()"></div>

    <!-- SIDE PANEL -->
    <div class="side-panel" id="sidePanel">
        <div class="side-panel-header">
            <h3><i class="fas fa-th-large"></i> Login To Loans</h3>
            <button class="side-panel-close" onclick="closeSidePanel()"><i class="fas fa-times"></i></button>
        </div>
        <div class="side-panel-tabs">
            <div class="side-panel-tab" onclick="switchTab('support')" id="tab-support"><i class="fas fa-headset"></i> Resources</div>
            <div class="side-panel-tab" onclick="switchTab('investment')" id="tab-investment"><i class="fas fa-chart-line"></i> Investment</div>
            <div class="side-panel-tab" onclick="switchTab('insurance')" id="tab-insurance"><i class="fas fa-headset"></i> Insurance</div>
        </div>
        <div class="side-panel-body">
            <!-- INSURANCE TAB -->
            <div class="tab-content active" id="content-insurance">
                <div class="panel-section-label">All Insurance</div>
                <div class="panel-item" onclick="toggleSubItems(this,'sub-term')">
                    <div class="panel-item-left">
                        <div class="panel-item-icon"><i class="fas fa-user-shield"></i></div>
                        <div>
                            <div class="panel-item-text">Term Insurance</div>
                            <div class="panel-item-sub">Pure life cover plans</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right panel-item-arrow"></i>
                </div>
                <div class="panel-sub-items" id="sub-term">
                    <a href="https://www.logintoinvest.com/insurance/Term-Insurance/term-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Term Insurance</a>
                    <a href="https://www.logintoinvest.com/insurance/Term-Insurance/best-term-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Best Term Insurance Plan</a>
                    <a href="https://www.logintoinvest.com/insurance/Term-Insurance/nri-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Term Insurance for NRI</a>
                    <a href="#" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Term Insurance Calculator</a>
                    <a href="https://www.logintoinvest.com/insurance/Term-Insurance/women-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Term Insurance for Women</a>
                </div>
                <div class="panel-item" onclick="toggleSubItems(this,'sub-life')">
                    <div class="panel-item-left">
                        <div class="panel-item-icon"><i class="fas fa-heart"></i></div>
                        <div>
                            <div class="panel-item-text">Life Insurance</div>
                            <div class="panel-item-sub">Savings + protection</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right panel-item-arrow"></i>
                </div>
                <div class="panel-sub-items" id="sub-life">
                    <a href="https://www.logintoinvest.com/insurance/Life-Insurance/Endowment-plans.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Endowment Plans</a>
                    <a href="https://www.logintoinvest.com/insurance/Life-Insurance/whole-life-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Whole Life Plans</a>
                    <a href="https://www.logintoinvest.com/insurance/Life-Insurance/moneyback-plans.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Money Back Plans</a>
                </div>
                <div class="panel-item" onclick="toggleSubItems(this,'sub-health')">
                    <div class="panel-item-left">
                        <div class="panel-item-icon"><i class="fas fa-heartbeat"></i></div>
                        <div>
                            <div class="panel-item-text">Health Insurance</div>
                            <div class="panel-item-sub">Medical expense cover</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right panel-item-arrow"></i>
                </div>
                <div class="panel-sub-items" id="sub-health">
                    <a href="https://www.logintoinvest.com/insurance/Health-Insurance/family-health-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Family Health Insurance</a>
                    <a href="https://www.logintoinvest.com/insurance/Health-Insurance/senior-citizen-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Senior Citizen Insurance</a>
                    <a href="https://www.logintoinvest.com/insurance/Health-Insurance/critical-illness-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Critical Illness</a>
                    <a href="https://www.logintoinvest.com/insurance/Health-Insurance/opd-cover-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> OPD Cover</a>
                    <a href="https://www.logintoinvest.com/insurance/Health-Insurance/network-hospitals.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Network Hospitals</a>
                </div>
                <div class="panel-item" onclick="toggleSubItems(this,'sub-general')">
                    <div class="panel-item-left">
                        <div class="panel-item-icon"><i class="fas fa-car"></i></div>
                        <div>
                            <div class="panel-item-text">General Insurance</div>
                            <div class="panel-item-sub">Vehicle, home &amp; more</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right panel-item-arrow"></i>
                </div>
                <div class="panel-sub-items" id="sub-general">
                    <a href="https://www.logintoinvest.com/insurance/General-Insurance/car-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Car Insurance</a>
                    <a href="https://www.logintoinvest.com/insurance/General-Insurance/Bike-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Bike Insurance</a>
                    <a href="https://www.logintoinvest.com/insurance/General-Insurance/electric-car.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Electric Car Insurance</a>
                    <a href="https://www.logintoinvest.com/insurance/General-Insurance/travel-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Travel Insurance</a>
                    <a href="https://www.logintoinvest.com/insurance/General-Insurance/commercial-insurance.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Commercial Insurance</a>
                </div>
            </div>
            <!-- INVESTMENT TAB -->
            <div class="tab-content" id="content-investment">
                <div class="panel-section-label">All Investments</div>
                <div class="panel-item" onclick="toggleSubItems(this,'sub-mutual')">
                    <div class="panel-item-left">
                        <div class="panel-item-icon"><i class="fas fa-heartbeat"></i></div>
                        <div>
                            <div class="panel-item-text">MUTUAL FUNDS</div>
                            <div class="panel-item-sub">Diversified fund investing</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right panel-item-arrow"></i>
                </div>
                <div class="panel-sub-items" id="sub-mutual">
                    <a href="https://www.logintoinvest.com/investment/equity-funds.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Equity Fund</a>
                    <a href="https://www.logintoinvest.com/investment/debt-funds.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Debt Fund</a>
                    <a href="https://www.logintoinvest.com/investment/hybrid-funds.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Hybrid Fund</a>
                </div>
                <div class="panel-item" onclick="toggleSubItems(this,'sub-iri')">
                    <div class="panel-item-left">
                        <div class="panel-item-icon"><i class="fas fa-heartbeat"></i></div>
                        <div>
                            <div class="panel-item-text">INCOME RETURN INVESTMENT</div>
                            <div class="panel-item-sub">Diversified fund investing</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right panel-item-arrow"></i>
                </div>
                <div class="panel-sub-items" id="sub-iri">
                    <a href="https://www.logintoinvest.com/investment/fixed-deposit.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Fixed Deposit</a>
                    <a href="https://www.logintoinvest.com/investment/recurring-deposit.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Recurring Deposit</a>
                    <a href="https://www.logintoinvest.com/investment/bonds.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Bonds</a>
                </div>
                <div class="panel-item" onclick="toggleSubItems(this,'sub-sip')">
                    <div class="panel-item-left">
                        <div class="panel-item-icon"><i class="fas fa-heartbeat"></i></div>
                        <div>
                            <div class="panel-item-text">SYSTEMATIC INVESTMENT PLAN</div>
                            <div class="panel-item-sub">Diversified fund investing</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right panel-item-arrow"></i>
                </div>
                <div class="panel-sub-items" id="sub-sip">
                    <a href="https://www.logintoinvest.com/investment/monthly-sip.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Monthly Sip</a>
                    <a href="https://www.logintoinvest.com/investment/step-up-sip.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Step Up SIP</a>
                </div>
                <div class="panel-item" onclick="toggleSubItems(this,'sub-gold')">
                    <div class="panel-item-left">
                        <div class="panel-item-icon"><i class="fas fa-heartbeat"></i></div>
                        <div>
                            <div class="panel-item-text">DIGITAL GOLD</div>
                            <div class="panel-item-sub">Diversified fund investing</div>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right panel-item-arrow"></i>
                </div>
                <div class="panel-sub-items" id="sub-gold">
                    <a href="https://www.logintoinvest.com/investment/digital-gold.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Digital Gold</a>
                    <a href="https://www.logintoinvest.com/investment/sovereign-bond.php" class="panel-sub-item"><i class="fas fa-arrow-right"></i> Sovereign Gold</a>
                </div>
            </div>
            <!-- SUPPORT TAB -->
            <div class="tab-content" id="content-support">
                <div class="panel-section-label">Help &amp; Guidance</div>
                <a href="#" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-question-circle"></i></div><div><div class="panel-item-text">FAQ</div><div class="panel-item-sub">Frequently asked questions</div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
                <a href="#" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-comments"></i></div><div><div class="panel-item-text">Live Chat</div><div class="panel-item-sub">Talk to an expert now</div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
                <a href="#" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-envelope"></i></div><div><div class="panel-item-text">Contact Support</div><div class="panel-item-sub">Email us anytime</div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
                <div class="panel-section-label">Resources</div>
                <a href="#" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-newspaper"></i></div><div><div class="panel-item-text">News</div><div class="panel-item-sub">Latest news for you</div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
                <a href="#" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-blog"></i></div><div><div class="panel-item-text">Blogs</div><div class="panel-item-sub">Read a treding blogs</div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
                <a href="#" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-image"></i></div><div><div class="panel-item-text">Images</div><div class="panel-item-sub">view images </div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
                <a href="#" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-video"></i></div><div><div class="panel-item-text">Videos</div><div class="panel-item-sub">view videos</div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
                <a href="#" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-download"></i></div><div><div class="panel-item-text">Downloads</div><div class="panel-item-sub">Download our resources</div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
                <div class="panel-section-label">Contact Us</div>
                <a href="mailto:info@logintoloan.com" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-envelope-open-text"></i></div><div><div class="panel-item-text">info@logintoloan.com</div><div class="panel-item-sub">Email us</div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
                <a href="tel:+918885545255" class="panel-item"><div class="panel-item-left"><div class="panel-item-icon"><i class="fas fa-phone-alt"></i></div><div><div class="panel-item-text">+91 8885545255</div><div class="panel-item-sub">Call us</div></div></div><i class="fas fa-chevron-right panel-item-arrow"></i></a>
            </div>
        </div>
        <div class="side-panel-footer">
            <a href="#" class="side-panel-view-all"><i class="fas fa-th"></i> View All Services</a>
        </div>
    </div>

    <!-- TOP NETWORK BAR -->
    <div class="top-network-bar">
        <div class="top-bar-container" style="display: flex; justify-content: center; align-items: center;">
            <div class="top-scroll-content" style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap;">
                <span class="network-label"><i class="fas fa-globe"></i> info@logintoloans.com</span>
                <span class="top-bar-sep"></span>
                <a href="https://www.realtyive.com/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Realtyive</a>
                <a href="https://leasingkart.in/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> LeasingKart</a>
                <a href="https://logintoinvest.com/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> LoginToInvest</a>
                <a href="https://www.kfinone.com/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Kfinone</a>
                <a href="https://kredithealthy.com/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> Kredithealthy</a>
                <a href="https://k-seva.com/" target="_blank"><i class="bi bi-box-arrow-up-right"></i> K-seva</a>
                <span class="top-bar-sep"></span>
                <a href="tel:+918885545255"><i class="fas fa-phone-alt"></i> +91 8885545255</a>
                <span class="top-bar-sep"></span>
                <a href="https://www.facebook.com/people/Logintoloans-K/61574524718831/"><i class="fab fa-facebook-f"></i></a>
                <a href="https://x.com/LOGINTOLOA5757"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/info.logintoloans/"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/in/loginto-loans-login-to-losns-3ba7a8357/"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>

    <!-- MAIN NAVBAR -->
    <nav class="premium-navbar" id="premiumNavbar">
        <div class="navbar-container">
            <div class="logo-container" onclick="window.location.href='<?php echo BASE_URL; ?>index.php'">
                <div class="logo-image">
                    <img src="<?php echo BASE_URL; ?>assets/images/logintoloan.jpeg" alt="LoginToLoan Logo">
                </div>
            </div>
            <button class="mobile-menu-btn" id="mobileMenuBtn" onclick="toggleMobileMenu()" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
            <div class="nav-menu" id="navMenu">
                <!-- Retail Loans -->
                <div class="dropdown" id="retailLoansDropdown">
                    <button class="dropdown-btn" onclick="toggleDropdownMobile(event,'retailLoansDropdown')">
                        <i class="fas fa-hand-holding-usd"></i><span>Retail</span><i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu mega-menu">
                        <div class="menu-grid">
                            <div class="menu-section-header"><h4>Retail Loans</h4></div>
                            <div class="menu-links-grid">
                                <a href="https://logintoloans.com/pages/retail/personal-loan.php"><i class="fas fa-user"></i> Personal Loan</a>
                                <a href="https://logintoloans.com/pages/retail/car-loan.php"><i class="fas fa-car"></i> Car Loan</a>
                                <a href="https://logintoloans.com/pages/retail/bike-loan.php"><i class="fas fa-motorcycle"></i> Bike Loan</a>
                                <a href="https://logintoloans.com/pages/retail/vehicle-loan.php"><i class="fas fa-truck"></i> Commercial Vehicle Loan</a>
                                <a href="https://logintoloans.com/pages/retail/home-loan.php"><i class="fas fa-home"></i> Home Loan</a>
                                <a href="https://logintoloans.com/pages/retail/gold-loan.php"><i class="fas fa-coins"></i> Gold Loan</a>
                                <a href="https://logintoloans.com/pages/retail/education-loan.php"><i class="fas fa-graduation-cap"></i> Education Loan</a>
                                <a href="https://logintoloans.com/pages/retail/mortgage-loan.php"><i class="fas fa-file-contract"></i> Mortgage Loan</a>
                                <a href="https://logintoloans.com/pages/retail/professional-loan.php"><i class="fas fa-user-tie"></i> Professional Loan</a>
                                <a href="https://logintoloans.com/pages/retail/creditcard-loan.php"><i class="fas fa-credit-card"></i> Credit Card Loan</a>
                                <a href="https://logintoloans.com/pages/retail/consumer-loan.php"><i class="fas fa-tv"></i> Consumer Durable Loan</a>
                                <a href="https://logintoloans.com/pages/retail/loan-against-property.php"><i class="fas fa-building"></i> Loan Against Property</a>
                                <a href="https://logintoloans.com/pages/retail/overdraft-loan.php"><i class="fas fa-university"></i> Salary Overdraft</a>
                            </div>
                        </div>
                        <div class="view-all"><a href="https://logintoloans.com/pages/retail/all-retails.php"><i class="fas fa-arrow-right"></i> View All Retail Loans</a></div>
                    </div>
                </div>
                <!-- Business Loans -->
                <div class="dropdown" id="businessLoansDropdown">
                    <button class="dropdown-btn" onclick="toggleDropdownMobile(event,'businessLoansDropdown')">
                        <i class="fas fa-building"></i><span>Business</span><i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu mega-menu">
                        <div class="menu-grid">
                            <div class="menu-section-header"><h4>Business Loans</h4></div>
                            <div class="menu-links-grid">
                                <a href="https://logintoloans.com/pages/business/msme-loan.php"><i class="fas fa-store"></i> MSME Loan</a>
                                <a href="https://logintoloans.com/pages/business/startup-loan.php"><i class="fas fa-rocket"></i> Startup Loan</a>
                                <a href="https://logintoloans.com/pages/business/unsecured-business-loan.php"><i class="fas fa-hand-holding-usd"></i> Unsecured Business Loan</a>
                                <a href="https://logintoloans.com/pages/business/secured-business-loan.php"><i class="fas fa-shield-alt"></i> Secured Business Loan</a>
                                <a href="https://logintoloans.com/pages/business/machinery-loan.php"><i class="fas fa-industry"></i> Machinery Loan</a>
                                <a href="https://logintoloans.com/pages/business/inventory-purchase-loan.php"><i class="fas fa-boxes"></i> Inventory/Purchase Loan</a>
                                <a href="https://logintoloans.com/pages/business/cash-credit.php"><i class="fas fa-credit-card"></i> Cash Credit (CC)</a>
                                <a href="https://logintoloans.com/pages/business/overdraft.php"><i class="fas fa-university"></i> Overdraft (OD)</a>
                                <a href="https://logintoloans.com/pages/business/invoice.php"><i class="fas fa-file-invoice-dollar"></i> Invoice Financing</a>
                                <a href="https://logintoloans.com/pages/business/vendor-financing.php"><i class="fas fa-handshake"></i> Vendor Financing</a>
                                <a href="https://logintoloans.com/pages/business/doctor-loan.php"><i class="fas fa-user-md"></i> Doctor Loan</a>
                                <a href="https://logintoloans.com/pages/business/ca-loan.php"><i class="fas fa-calculator"></i> CA Loan</a>
                                <a href="https://logintoloans.com/pages/business/term-loan.php"><i class="fas fa-file-contract"></i> Term Loan</a>
                            </div>
                        </div>
                        <div class="view-all"><a href="https://logintoloans.com/pages/business/all-business.php"><i class="fas fa-arrow-right"></i> View All Business Loans</a></div>
                    </div>
                </div>
                <!-- Professional Loans -->
                <div class="dropdown" id="commercialLoansDropdown">
                    <button class="dropdown-btn" onclick="toggleDropdownMobile(event,'commercialLoansDropdown')">
                        <i class="fas fa-building"></i><span>Professional</span><i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu mega-menu">
                        <div class="menu-grid">
                            <div class="menu-section-header"><h4>Professional Loans</h4></div>
                            <div class="menu-links-grid">
                                <a href="https://logintoloans.com/pages/business/doctor-loan.php"><i class="fas fa-user-md"></i> Doctor Loan</a>
                                <a href="https://logintoloans.com/pages/business/ca-loan.php"><i class="fas fa-calculator"></i> Chartered Accountant (CA) Loan</a>
                                <a href="https://logintoloans.com/pages/professional/company-secretary-loan.php"><i class="fas fa-file-signature"></i> Company Secretary (CS) Loan</a>
                                <a href="https://logintoloans.com/pages/professional/cost-account-loan.php"><i class="fas fa-coins"></i> Cost Accountant Loan</a>
                                <a href="https://logintoloans.com/pages/professional/architect-loan.php"><i class="fas fa-drafting-compass"></i> Architect Loan</a>
                                <a href="https://logintoloans.com/pages/professional/engineer-loan.php"><i class="fas fa-cogs"></i> Engineer Loan</a>
                                <a href="https://logintoloans.com/pages/professional/consultant-loan.php"><i class="fas fa-user-tie"></i> Consultant Loan</a>
                                <a href="https://logintoloans.com/pages/professional/medical-loan.php"><i class="fas fa-stethoscope"></i> Medical Equipment Loan</a>
                                <a href="https://logintoloans.com/pages/professional/hospital-loan.php"><i class="fas fa-hospital"></i> Clinic / Hospital Setup Loan</a>
                                <a href="https://logintoloans.com/pages/professional/professional-practice-expansion-loan.php"><i class="fas fa-chart-line"></i> Professional Practice Expansion Loan</a>
                                <a href="https://logintoloans.com/pages/professional/working-capital.php"><i class="fas fa-wallet"></i> Working Capital for Professionals</a>
                                <a href="https://logintoloans.com/pages/retail/loan-against-property.php"><i class="fas fa-building"></i> LAP (Loan Against Property) for Professionals</a>
                                <a href="https://logintoloans.com/pages/professional/overdraft-facility.php"><i class="fas fa-university"></i> OD (Overdraft) Facility for Professionals</a>
                                <a href="https://logintoloans.com/pages/business/machinery-loan.php"><i class="fas fa-tools"></i> Machinery / Equipment Purchase Loan</a>
                                <a href="https://logintoloans.com/pages/professional/business-loan.php"><i class="fas fa-briefcase"></i> Business Loan for Self-Employed Professionals</a>
                            </div>
                        </div>
                        <div class="view-all"><a href="https://logintoloans.com/pages/professional/all-professional.php"><i class="fas fa-arrow-right"></i> View All Professional Loans</a></div>
                    </div>
                </div>
                <!-- NRI Loans -->
                <div class="dropdown" id="workingCapitalDropdown">
                    <button class="dropdown-btn" onclick="toggleDropdownMobile(event,'workingCapitalDropdown')">
                        <i class="fas fa-coins"></i><span>NRI</span><i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu mega-menu">
                        <div class="menu-grid">
                            <div class="menu-section-header"><h4>NRI Loans</h4></div>
                            <div class="menu-links-grid">
                                <a href="https://logintoloans.com/pages/nri/nri-home-loan.php"><i class="fas fa-home"></i> NRI Home Loan</a>
                                <a href="https://logintoloans.com/pages/nri/nri-business-loan.php"><i class="fas fa-briefcase"></i> NRI Business Loan</a>
                                <a href="https://logintoloans.com/pages/nri/nri-lap.php"><i class="fas fa-building"></i> NRI Loan Against Property (LAP)</a>
                                <a href="https://logintoloans.com/pages/nri/nri-education-loan.php"><i class="fas fa-graduation-cap"></i> NRI Education Loan</a>
                                <a href="https://logintoloans.com/pages/nri/nri-car-loan.php"><i class="fas fa-car"></i> NRI Car Loan</a>
                                <a href="https://logintoloans.com/pages/nri/nri-gold-loan.php"><i class="fas fa-coins"></i> NRI Gold Loan</a>
                                <a href="https://logintoloans.com/pages/nri/nri-investment-property-loan.php"><i class="fas fa-city"></i> NRI Investment Property Loan</a>
                                <a href="https://logintoloans.com/pages/nri/nri-working-capital-loan.php"><i class="fas fa-wallet"></i> NRI Working Capital Loan</a>
                                <a href="https://logintoloans.com/pages/nri/nri-construction-loan.php"><i class="fas fa-hard-hat"></i> NRI Construction Loan</a>
                                <a href="https://logintoloans.com/pages/nri/nri-balance-transfer.php"><i class="fas fa-exchange-alt"></i> NRI Balance Transfer Loan</a>
                                <a href="https://logintoloans.com/pages/nri/nri-mortgage-loan.php"><i class="fas fa-file-contract"></i> NRI Mortgage Loan</a>
                            </div>
                        </div>
                        <div class="view-all"><a href="https://logintoloans.com/pages/nri/all-nri.php"><i class="fas fa-arrow-right"></i> View All NRI Loans</a></div>
                    </div>
                </div>
                <a href="https://logintoloans.com/free-credit-score.php" class="nav-get-started-btn">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Free Credit Score</span>
                </a>
                <!-- RIGHT CLUSTER -->
                <div class="nav-right">
                    <?php if (isLoggedIn()): ?>
                        <?php
                        $displayName = '';
                        if (!empty($_SESSION['fullname'])) {
                            $displayName = $_SESSION['fullname'];
                        } elseif (!empty($_SESSION['username'])) {
                            $displayName = $_SESSION['username'];
                        } elseif (!empty($_SESSION['email'])) {
                            $displayName = explode('@', $_SESSION['email'])[0];
                        } else {
                            $displayName = 'User';
                        }
                        if (strlen($displayName) > 25) {
                            $displayName = substr($displayName, 0, 22) . '...';
                        }
                        ?>
                        <div class="user-menu user-menu-desktop" onclick="toggleUserDropdown(event)">
                            <div class="user-avatar-icon"><i class="fas fa-user"></i></div>
                            <span class="user-name"><?php echo htmlspecialchars($displayName); ?></span>
                            <i class="fas fa-chevron-down"></i>
                            <div class="user-dropdown-menu" id="userDropdownMenu">
                                <?php if (isAdmin()): ?>
                                    <a href="<?php echo BASE_URL; ?>admin/admin-dashboard.php"><i class="fas fa-shield-alt"></i> Admin Dashboard</a>
                                    <div class="ud-divider"></div>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>user/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                                <a href="<?php echo BASE_URL; ?>user/investments.php"><i class="fas fa-chart-pie"></i> Investments</a>
                                <a href="<?php echo BASE_URL; ?>admin/add-insurance.php"><i class="fas fa-plus-circle"></i> Add Insurance</a>
                                <a href="<?php echo BASE_URL; ?>user/loans.php"><i class="fas fa-hand-holding-usd"></i> Loans</a>
                                <a href="<?php echo BASE_URL; ?>user/portfolio.php"><i class="fas fa-briefcase"></i> Portfolio</a>
                                <div class="ud-divider"></div>
                                <a href="<?php echo BASE_URL; ?>user/profile.php"><i class="fas fa-user"></i> My Profile</a>
                                <a href="<?php echo BASE_URL; ?>logout.php" style="color:#dc2626;"><i class="fas fa-sign-out-alt" style="color:#dc2626;"></i> Logout</a>
                            </div>
                        </div>
                        <div class="user-menu-mobile" onclick="toggleUserDropdownMobile()">
                            <i class="fas fa-user-circle" style="font-size:1.25rem;color:#042b6c;flex-shrink:0;"></i>
                            <span class="user-name"><?php echo htmlspecialchars($displayName); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div id="mobileUserDropdownMenu" style="display:none;flex-direction:column;">
                            <a href="<?php echo BASE_URL; ?>user/dashboard.php" style="padding:13px 20px;text-decoration:none;color:#1e293b;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:11px;"><i class="fas fa-tachometer-alt" style="color:#042b6c;"></i> Dashboard</a>
                            <a href="<?php echo BASE_URL; ?>user/investments.php" style="padding:13px 20px;text-decoration:none;color:#1e293b;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:11px;"><i class="fas fa-chart-pie" style="color:#042b6c;"></i> Investments</a>
                            <a href="<?php echo BASE_URL; ?>user/loans.php" style="padding:13px 20px;text-decoration:none;color:#1e293b;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:11px;"><i class="fas fa-hand-holding-usd" style="color:#042b6c;"></i> Loans</a>
                            <a href="<?php echo BASE_URL; ?>user/portfolio.php" style="padding:13px 20px;text-decoration:none;color:#1e293b;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:11px;"><i class="fas fa-briefcase" style="color:#042b6c;"></i> Portfolio</a>
                            <?php if (isAdmin()): ?>
                                <a href="<?php echo BASE_URL; ?>admin/admin-dashboard.php" style="padding:13px 20px;text-decoration:none;color:#1e293b;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:11px;"><i class="fas fa-shield-alt" style="color:#042b6c;"></i> Admin Dashboard</a>
                            <?php endif; ?>
                            <a href="<?php echo BASE_URL; ?>user/profile.php" style="padding:13px 20px;text-decoration:none;color:#1e293b;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:11px;"><i class="fas fa-user" style="color:#042b6c;"></i> My Profile</a>
                            <a href="<?php echo BASE_URL; ?>logout.php" style="padding:13px 20px;text-decoration:none;color:#dc2626;display:flex;align-items:center;gap:11px;"><i class="fas fa-sign-out-alt" style="color:#dc2626;"></i> Logout</a>
                        </div>
                    <?php else: ?>
                        <div class="auth-pill" onclick="openAuthModal()">
                            <div class="auth-link">
                                <i class="fas fa-sign-in-alt"></i> Sign In/Up
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="more-menu-btn desktop-only" onclick="openSidePanel()">
                        <i class="fas fa-bars"></i>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                    <div class="more-menu-btn mobile-only" onclick="openSidePanel()" style="display:none;">
                        <i class="fas fa-grip-horizontal"></i>
                        <span>More Options</span>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        /* === Auth Modal Functions === */
        function openAuthModal() {
            document.getElementById('authModalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeAuthModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('authModalOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        function switchAuthTab(tab) {
            const tabs = document.querySelectorAll('.auth-tab');
            const forms = document.querySelectorAll('.auth-form');
            tabs.forEach(t => t.classList.remove('active'));
            forms.forEach(f => f.classList.remove('active'));
            if (tab === 'login') {
                tabs[0].classList.add('active');
                document.getElementById('loginForm').classList.add('active');
            } else {
                tabs[1].classList.add('active');
                document.getElementById('registerForm').classList.add('active');
            }
        }

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        function checkRegPasswordStrength() {
            const password = document.getElementById('regPassword').value;
            const strengthFill = document.getElementById('regStrengthFill');
            const strengthText = document.getElementById('regStrengthText');
            let strength = 0;
            let color = '#ef4444';
            let text = 'Weak';
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            let width = (strength / 5) * 100;
            if (strength <= 2) { color = '#ef4444'; text = 'Weak'; }
            else if (strength <= 3) { color = '#f59e0b'; text = 'Fair'; }
            else if (strength <= 4) { color = '#10b981'; text = 'Good'; }
            else { color = '#fd9130'; text = 'Strong'; }
            strengthFill.style.width = width + '%';
            strengthFill.style.backgroundColor = color;
            strengthText.innerHTML = text;
            strengthText.style.color = color;
            if (password.length === 0) {
                strengthFill.style.width = '0%';
                strengthText.innerHTML = 'Enter password';
                strengthText.style.color = '#64748b';
            }
            checkRegPasswordMatch();
        }

        function checkRegPasswordMatch() {
            const password = document.getElementById('regPassword').value;
            const confirmPassword = document.getElementById('regConfirmPassword').value;
            const msgDiv = document.getElementById('regMatchMsg');
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    msgDiv.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981;"></i> Passwords match';
                    msgDiv.style.color = '#10b981';
                } else {
                    msgDiv.innerHTML = '<i class="fas fa-times-circle" style="color: #ef4444;"></i> Passwords do not match';
                    msgDiv.style.color = '#ef4444';
                }
            } else {
                msgDiv.innerHTML = '';
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('regPassword').value;
            const confirmPassword = document.getElementById('regConfirmPassword').value;
            const terms = document.getElementById('regTerms').checked;
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('❌ Passwords do not match!');
                return false;
            }
            if (password.length < 6) {
                e.preventDefault();
                alert('❌ Password must be at least 6 characters long!');
                return false;
            }
            if (!terms) {
                e.preventDefault();
                alert('❌ Please accept the Terms & Conditions to continue!');
                return false;
            }
            return true;
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAuthModal();
            }
        });

        /* === Side Panel Functions === */
        function openSidePanel() {
            document.getElementById('sidePanel').classList.add('show');
            document.getElementById('sidePanelOverlay').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidePanel() {
            document.getElementById('sidePanel').classList.remove('show');
            document.getElementById('sidePanelOverlay').classList.remove('show');
            document.body.style.overflow = '';
        }

        function switchTab(n) {
            document.querySelectorAll('.side-panel-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById('tab-' + n).classList.add('active');
            document.getElementById('content-' + n).classList.add('active');
        }

        function toggleSubItems(el, id) {
            const sub = document.getElementById(id);
            const was = sub.classList.contains('expanded');
            document.querySelectorAll('.panel-sub-items.expanded').forEach(s => s.classList.remove('expanded'));
            document.querySelectorAll('.panel-item.expanded').forEach(i => i.classList.remove('expanded'));
            if (!was) {
                sub.classList.add('expanded');
                el.classList.add('expanded');
            }
        }

        /* === Mobile Menu Functions === */
        function toggleMobileMenu() {
            const menu = document.getElementById('navMenu');
            const btn = document.getElementById('mobileMenuBtn');
            const icon = btn.querySelector('i');
            menu.classList.toggle('active');
            if (menu.classList.contains('active')) {
                icon.className = 'fas fa-times';
                document.body.style.overflow = 'hidden';
            } else {
                icon.className = 'fas fa-bars';
                document.body.style.overflow = '';
                closeAllMobileDropdowns();
                const mu = document.getElementById('mobileUserDropdownMenu');
                if (mu) mu.style.display = 'none';
            }
        }

        function closeAllMobileDropdowns() {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('active-mobile'));
        }

        function toggleDropdownMobile(event, id) {
            if (window.innerWidth <= 768) {
                event.stopPropagation();
                const dd = document.getElementById(id);
                if (!dd) return;
                const menu = dd.querySelector('.dropdown-menu');
                if (!menu) return;
                document.querySelectorAll('.dropdown .dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('active-mobile');
                });
                menu.classList.toggle('active-mobile');
            }
        }

        function toggleUserDropdown(e) {
            e.stopPropagation();
            document.getElementById('userDropdownMenu').classList.toggle('show');
        }

        function toggleUserDropdownMobile() {
            const mu = document.getElementById('mobileUserDropdownMenu');
            mu.style.display = mu.style.display === 'flex' ? 'none' : 'flex';
        }

        document.addEventListener('click', function(e) {
            const ud = document.getElementById('userDropdownMenu');
            const um = document.querySelector('.user-menu-desktop');
            if (ud && um && !um.contains(e.target)) ud.classList.remove('show');
            const menu = document.getElementById('navMenu');
            const btn = document.getElementById('mobileMenuBtn');
            if (window.innerWidth <= 768 && menu && menu.classList.contains('active') &&
                !menu.contains(e.target) && btn && !btn.contains(e.target)) {
                menu.classList.remove('active');
                btn.querySelector('i').className = 'fas fa-bars';
                document.body.style.overflow = '';
            }
            if (!e.target.closest('.dropdown')) closeAllMobileDropdowns();
        });

        window.addEventListener('resize', function() {
            const menu = document.getElementById('navMenu');
            const btn = document.getElementById('mobileMenuBtn');
            if (window.innerWidth > 768 && menu) {
                menu.classList.remove('active');
                if (btn) btn.querySelector('i').className = 'fas fa-bars';
                document.body.style.overflow = '';
                closeAllMobileDropdowns();
                const mu = document.getElementById('mobileUserDropdownMenu');
                if (mu) mu.style.display = 'none';
            }
        });

        window.addEventListener('scroll', function() {
            document.getElementById('premiumNavbar').classList.toggle('scrolled', window.scrollY > 50);
        });

        document.querySelectorAll('.dropdown-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                if (window.innerWidth > 768) e.preventDefault();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toast-notification').forEach(toast => {
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.3s, transform 0.3s';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(50px)';
                    setTimeout(() => {
                        if (toast.parentNode) toast.remove();
                    }, 320);
                }, 5000);
            });
        });
    </script>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="toast-notification" style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;">
            <i class="fas fa-check-circle"></i> ' . htmlspecialchars($_SESSION['success_message']) . '
          </div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="toast-notification" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;">
            <i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($_SESSION['error_message']) . '
          </div>';
        unset($_SESSION['error_message']);
    }
    ?>
</body>

</html>