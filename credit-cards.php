<?php
session_start();
include('../includes/dbConfig.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$fullname      = htmlspecialchars($_SESSION['fullname']      ?? 'User');
$email         = htmlspecialchars($_SESSION['email']         ?? 'user@email.com');
$phone         = htmlspecialchars($_SESSION['phone']         ?? '+91 98765 43210');
$role          = htmlspecialchars($_SESSION['role']          ?? 'user');
$profile_image = htmlspecialchars($_SESSION['profile_image'] ?? '');
$firstname     = htmlspecialchars(explode(' ', trim($_SESSION['fullname'] ?? 'User'))[0]);

// Check profile completion status
$profile_complete = true;
$missing_fields = [];

// Check if required profile fields are filled
if (empty($_SESSION['fullname']) || $_SESSION['fullname'] == 'User') {
    $profile_complete = false;
    $missing_fields[] = 'Full Name';
}
if (empty($_SESSION['email']) || $_SESSION['email'] == 'user@email.com') {
    $profile_complete = false;
    $missing_fields[] = 'Email';
}
if (empty($_SESSION['phone']) || $_SESSION['phone'] == '+91 98765 43210') {
    $profile_complete = false;
    $missing_fields[] = 'Phone Number';
}
if (empty($_SESSION['profile_image'])) {
    $profile_complete = false;
    $missing_fields[] = 'Profile Image';
}

// Check additional fields from database if needed
// You can add more fields here like address, dob, etc.

include('../includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Credit Cards - Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        :root {
            --primary: #1a237e; --primary-dark: #0d1757; --primary-light: #283593;
            --secondary: #00c853; --accent: #ff6b35; --orange: #ff9800;
            --text-primary: #1a1a2e; --text-secondary: #666666; --text-tertiary: #999999;
            --background: #f8f9fc; --white: #ffffff; --border: #eef2f6;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --success: #00c853; --warning: #ff9800; --danger: #ef4444;
        }

        html { scroll-behavior: smooth; overscroll-behavior: contain; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
            min-height: 100vh;
            position: relative;
            padding-bottom: 20px;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        /* ===== BACK HEADER ===== */
        .back-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        .back-btn {
            background: none;
            border: none;
            font-size: 16px;
            color: var(--text-primary);
            cursor: pointer;
            padding: 6px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
        }
        .back-btn:active { opacity: 0.6; }
        .back-btn i { font-size: 18px; }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-actions .action-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f5f5f5;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--text-secondary);
            transition: 0.2s;
        }
        .header-actions .action-icon:active { background: #e0e0e0; transform: scale(0.95); }

        /* ===== PAGE HEADER ===== */
        .page-header {
            padding: 20px 16px 12px;
        }
        .page-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .page-header h1 i {
            color: var(--primary);
        }
        .page-header p {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        /* ===== PROFILE STATUS BANNER ===== */
        .profile-status {
            margin: 0 16px 16px;
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .profile-status.complete {
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
        }
        .profile-status.pending {
            background: #fff3e0;
            border: 1px solid #ffcc80;
        }
        .profile-status .status-icon {
            font-size: 20px;
            flex-shrink: 0;
        }
        .profile-status.complete .status-icon {
            color: #2e7d32;
        }
        .profile-status.pending .status-icon {
            color: #e65100;
        }
        .profile-status .status-text {
            flex: 1;
            font-size: 13px;
            font-weight: 500;
        }
        .profile-status.complete .status-text {
            color: #1b5e20;
        }
        .profile-status.pending .status-text {
            color: #bf360c;
        }
        .profile-status .status-text span {
            font-weight: 600;
        }
        .profile-status .status-action {
            background: var(--primary);
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
            transition: 0.2s;
        }
        .profile-status .status-action:active {
            transform: scale(0.95);
        }
        .profile-status .status-action i {
            margin-right: 4px;
        }

        /* ===== PRE-QUALIFIED BANNER ===== */
        .pre-qualified-banner {
            background: linear-gradient(135deg, #1a237e, #283593);
            border-radius: 16px;
            padding: 16px 18px;
            margin: 0 16px 16px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .pre-qualified-banner .pq-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .pre-qualified-banner .pq-left i {
            font-size: 28px;
            color: #ff9800;
        }
        .pre-qualified-banner .pq-left .pq-text {
            font-size: 14px;
            font-weight: 600;
        }
        .pre-qualified-banner .pq-left .pq-text span {
            display: block;
            font-size: 11px;
            font-weight: 400;
            opacity: 0.8;
        }
        .pre-qualified-banner .pq-badge {
            background: #ff9800;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            color: #1a237e;
        }

        /* ===== PROFILE CARD ===== */
        .profile-card {
            background: var(--white);
            border-radius: 16px;
            padding: 16px 18px;
            margin: 0 16px 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .profile-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a237e, #283593);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            overflow: hidden;
        }
        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-info {
            flex: 1;
        }
        .profile-info .profile-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .profile-info .profile-detail {
            font-size: 12px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 2px;
        }
        .profile-info .profile-detail i {
            color: var(--primary);
            width: 14px;
            font-size: 12px;
        }
        .profile-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }
        .profile-badge.pending {
            background: #fff3e0;
            color: #e65100;
        }

        /* ===== CREDIT CARD CARDS ===== */
        .cards-section {
            padding: 0 16px 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 16px 0 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-title i {
            color: var(--primary);
        }

        .credit-card {
            background: var(--white);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 14px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        .credit-card:active { transform: scale(0.98); }

        .credit-card .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }
        .credit-card .card-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }
        .credit-card .card-info {
            flex: 1;
        }
        .credit-card .card-info .card-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .credit-card .card-info .card-sub {
            font-size: 11px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .credit-card .card-info .card-sub .badge-free {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 1px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 700;
        }
        .credit-card .card-info .card-sub .badge-fee {
            background: #fff3e0;
            color: #e65100;
            padding: 1px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 700;
        }

        .credit-card .card-benefits {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 12px;
            margin: 10px 0 12px;
            padding: 10px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .credit-card .card-benefits .benefit {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-secondary);
        }
        .credit-card .card-benefits .benefit i {
            color: var(--success);
            font-size: 13px;
        }

        .credit-card .card-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .credit-card .card-actions .action-btn {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-family: 'Inter', sans-serif;
            flex: 1;
            min-width: 70px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .credit-card .card-actions .action-btn:active { transform: scale(0.95); }
        .credit-card .card-actions .action-btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
        }
        .credit-card .card-actions .action-btn-secondary {
            background: var(--border);
            color: var(--text-secondary);
        }
        .credit-card .card-actions .action-btn-success {
            background: linear-gradient(135deg, #00c853, #00e676);
            color: white;
        }
        .credit-card .card-actions .action-btn-warning {
            background: linear-gradient(135deg, #ff9800, #ffb74d);
            color: white;
        }

        .card-logo.roar { background: linear-gradient(135deg, #ff6b00, #ff9800); }
        .card-logo.flipkart { background: linear-gradient(135deg, #2874f0, #1a5bb5); }
        .card-logo.amazon { background: linear-gradient(135deg, #232f3e, #37475a); }
        .card-logo.hdfc { background: linear-gradient(135deg, #004d7a, #006699); }
        .card-logo.sbi { background: linear-gradient(135deg, #1a5276, #2e86c1); }
        .card-logo.icici { background: linear-gradient(135deg, #004080, #0066b3); }
        .card-logo.axis { background: linear-gradient(135deg, #800000, #cc0000); }
        .card-logo.kotak { background: linear-gradient(135deg, #2c3e50, #34495e); }
        .card-logo.yes { background: linear-gradient(135deg, #008080, #00a86b); }
        .card-logo.indusind { background: linear-gradient(135deg, #1a237e, #283593); }
        .card-logo.citi { background: linear-gradient(135deg, #003366, #004488); }
        .card-logo.amex { background: linear-gradient(135deg, #0066cc, #0088ff); }
        .card-logo.more { background: linear-gradient(135deg, #6a1b9a, #8e24aa); }

        /* ===== APPLICATION FORM MODAL ===== */
        .application-modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 2000;
            display: none;
            align-items: flex-end;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }
        .application-modal.active { display: flex; }
        .application-content {
            background: var(--white);
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 480px;
            max-height: 92vh;
            overflow-y: auto;
            padding: 0;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .application-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: var(--white);
            z-index: 10;
            border-radius: 24px 24px 0 0;
        }
        .application-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .application-header h3 i {
            color: var(--orange);
        }
        .application-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f5f5f5;
            border: none;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        .application-close:active { background: #e0e0e0; transform: scale(0.95); }

        .application-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 5px;
        }
        .form-group label .required {
            color: var(--danger);
            margin-left: 2px;
        }
        .form-group label i {
            margin-right: 6px;
            color: var(--primary);
            width: 16px;
        }
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background: var(--white);
            color: var(--text-primary);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
        }
        .form-input.error {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        .form-input:disabled {
            background: #f5f5f5;
            opacity: 0.7;
        }
        .form-error {
            font-size: 11px;
            color: var(--danger);
            margin-top: 4px;
            display: none;
        }
        .form-error.show {
            display: block;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background: var(--white);
            color: var(--text-primary);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
        }

        /* File Upload Styles */
        .file-upload-wrapper {
            position: relative;
            width: 100%;
        }
        .file-upload-wrapper input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .file-upload-box {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            transition: all 0.3s ease;
            background: #fafafa;
            cursor: pointer;
        }
        .file-upload-box:hover {
            border-color: var(--primary);
            background: #f0f3ff;
        }
        .file-upload-box.dragover {
            border-color: var(--primary);
            background: #e8eaf6;
        }
        .file-upload-box i {
            font-size: 28px;
            color: var(--primary);
            display: block;
            margin-bottom: 6px;
        }
        .file-upload-box .file-text {
            font-size: 12px;
            color: var(--text-secondary);
        }
        .file-upload-box .file-text span {
            color: var(--primary);
            font-weight: 600;
        }
        .file-name-display {
            font-size: 12px;
            color: var(--success);
            margin-top: 6px;
            display: none;
        }
        .file-name-display.show {
            display: block;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }
        .submit-btn:active { transform: scale(0.97); }
        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-note {
            font-size: 11px;
            color: var(--text-tertiary);
            text-align: center;
            margin-top: 12px;
            padding: 10px;
            background: #f8f9fc;
            border-radius: 8px;
        }
        .form-note i {
            color: var(--success);
        }

        /* ===== TOAST ===== */
        .toast { position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%) translateY(100px); background: var(--text-primary); color: white; padding: 10px 18px; border-radius: 24px; font-size: 12px; z-index: 9999; transition: transform 0.3s; white-space: nowrap; max-width: 90%; }
        .toast.show { transform: translateX(-50%) translateY(0); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .back-header { padding: 10px 14px; }
            .back-btn { font-size: 14px; }
            .page-header h1 { font-size: 20px; }
            .profile-card { padding: 12px 14px; flex-wrap: wrap; }
            .profile-avatar { width: 44px; height: 44px; font-size: 18px; }
            .profile-status { flex-wrap: wrap; justify-content: center; text-align: center; padding: 10px 14px; }
            .profile-status .status-action { width: 100%; text-align: center; justify-content: center; }
            .credit-card { padding: 14px; }
            .credit-card .card-benefits { grid-template-columns: 1fr 1fr; gap: 4px 8px; }
            .credit-card .card-benefits .benefit { font-size: 11px; }
            .credit-card .card-actions .action-btn { font-size: 11px; padding: 6px 10px; min-width: 60px; }
            .pre-qualified-banner { padding: 12px 14px; flex-direction: column; gap: 8px; text-align: center; }
            .pre-qualified-banner .pq-left { flex-direction: column; }
            .form-row { grid-template-columns: 1fr; }
            .application-content { border-radius: 20px 20px 0 0; }
            .file-upload-box { padding: 12px; }
            .file-upload-box i { font-size: 22px; }
        }
        @media (max-width: 374px) {
            .page-header h1 { font-size: 18px; }
            .credit-card .card-benefits { grid-template-columns: 1fr; }
            .credit-card .card-actions .action-btn { font-size: 10px; padding: 5px 8px; min-width: 50px; }
        }
    </style>
</head>
<body>

<div class="app-container">

    <!-- BACK HEADER -->
    <div class="back-header">
        <button class="back-btn" onclick="goBack()">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <div class="header-actions">
            <button class="action-icon" onclick="showToast('No new notifications')">
                <i class="fas fa-bell"></i>
            </button>
        </div>
    </div>

    <!-- PAGE HEADER -->
    <div class="page-header">
        <h1><i class="fas fa-credit-card"></i> Credit Cards</h1>
        <p>Apply for a Credit Card & Earn HUGE CASHBACKs</p>
    </div>

    <!-- PROFILE STATUS BANNER -->
    <?php if ($profile_complete): ?>
    <div class="profile-status complete">
        <div class="status-icon"><i class="fas fa-check-circle"></i></div>
        <div class="status-text">
            <span>✅ Profile Complete!</span> Your profile is fully verified. You can apply for credit cards.
        </div>
        <button class="status-action" onclick="showToast('Profile is complete')">
            <i class="fas fa-check"></i> Verified
        </button>
    </div>
    <?php else: ?>
    <div class="profile-status pending">
        <div class="status-icon"><i class="fas fa-exclamation-triangle"></i></div>
        <div class="status-text">
            <span>⚠️ Profile Incomplete!</span> Please complete your profile to apply for credit cards. 
            Missing: <strong><?php echo implode(', ', $missing_fields); ?></strong>
        </div>
        <button class="status-action" onclick="window.location.href='profile.php'">
            <i class="fas fa-edit"></i> Complete Now
        </button>
    </div>
    <?php endif; ?>

 
  
 

    <!-- CREDIT CARDS LIST -->
    <div class="cards-section">

        <!-- Roar Bank Credit Card -->
        <div class="credit-card">
            <div class="card-header">
                <div class="card-logo roar">R</div>
                <div class="card-info">
                    <div class="card-name">Roar Bank Credit Card</div>
                    <div class="card-sub">
                        <span class="badge-free">Lifetime Free</span>
                        <span>App Only Journey</span>
                    </div>
                </div>
            </div>
            <div class="card-benefits">
                <div class="benefit"><i class="fas fa-percent"></i> Up to 20% cashback</div>
                <div class="benefit"><i class="fas fa-calendar-alt"></i> 62 days interest-free</div>
            </div>
            <div class="card-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Roar Bank Credit Card', 'Lifetime Free', 'Up to 20% cashback', '62 days interest-free', '1.5% fuel surcharge waiver, Airport lounge access, Dining offers', 'Aadhar Card, PAN Card, Bank Statement, Salary Slips', 'Excellent', '₹1,00,000')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-secondary" onclick="showToast('Compare feature coming soon')">
                    <i class="fas fa-balance-scale"></i> Compare
                </button>
                <button class="action-btn action-btn-success" onclick="checkProfileAndApply('Roar Bank Credit Card')">
                    <i class="fas fa-check"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- Flipkart SBI Credit Card -->
        <div class="credit-card">
            <div class="card-header">
                <div class="card-logo flipkart">F</div>
                <div class="card-info">
                    <div class="card-name">Flipkart SBI Credit Card</div>
                    <div class="card-sub">
                        <span class="badge-fee">1st Year Fee: ₹ 500</span>
                    </div>
                </div>
            </div>
            <div class="card-benefits">
                <div class="benefit"><i class="fas fa-percent"></i> 5% cashback at Flipkart</div>
                <div class="benefit"><i class="fas fa-percent"></i> 7.5% cashback at Myntra</div>
            </div>
            <div class="card-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Flipkart SBI Credit Card', '1st Year Fee: ₹ 500', '5% cashback at Flipkart, 7.5% cashback at Myntra', '2% cashback on all other spends', '1% fuel surcharge waiver, Amazon prime membership', 'Aadhar Card, PAN Card, Bank Statement, Income Proof', 'Good', '₹2,00,000')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-secondary" onclick="showToast('Compare feature coming soon')">
                    <i class="fas fa-balance-scale"></i> Compare
                </button>
                <button class="action-btn action-btn-success" onclick="checkProfileAndApply('Flipkart SBI Credit Card')">
                    <i class="fas fa-check"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- Amazon Pay ICICI Credit Card -->
        <div class="credit-card">
            <div class="card-header">
                <div class="card-logo amazon">A</div>
                <div class="card-info">
                    <div class="card-name">Amazon Pay ICICI Credit Card</div>
                    <div class="card-sub">
                        <span class="badge-free">Lifetime Free</span>
                        <span>10% cashback on Amazon</span>
                    </div>
                </div>
            </div>
            <div class="card-benefits">
                <div class="benefit"><i class="fas fa-percent"></i> 10% cashback on Amazon</div>
                <div class="benefit"><i class="fas fa-percent"></i> 5% on all other spends</div>
            </div>
            <div class="card-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Amazon Pay ICICI Credit Card', 'Lifetime Free', '10% cashback on Amazon, 5% on other spends', '1% fuel surcharge waiver, No annual fee', 'Aadhar Card, PAN Card, Bank Statement, IT Returns', 'Excellent', '₹3,00,000')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-secondary" onclick="showToast('Compare feature coming soon')">
                    <i class="fas fa-balance-scale"></i> Compare
                </button>
                <button class="action-btn action-btn-success" onclick="checkProfileAndApply('Amazon Pay ICICI Credit Card')">
                    <i class="fas fa-check"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- HDFC Millennia Credit Card -->
        <div class="credit-card">
            <div class="card-header">
                <div class="card-logo hdfc">H</div>
                <div class="card-info">
                    <div class="card-name">HDFC Millennia Credit Card</div>
                    <div class="card-sub">
                        <span class="badge-fee">Annual Fee: ₹ 500</span>
                        <span>5% cashback on all online spends</span>
                    </div>
                </div>
            </div>
            <div class="card-benefits">
                <div class="benefit"><i class="fas fa-percent"></i> 5% cashback on online</div>
                <div class="benefit"><i class="fas fa-percent"></i> 1% on all other spends</div>
            </div>
            <div class="card-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('HDFC Millennia Credit Card', 'Annual Fee: ₹ 500', '5% cashback on online spends, 1% on other spends', '2% fuel surcharge waiver, 5x rewards on SmartBuy', 'Aadhar Card, PAN Card, Bank Statement, Salary Slips', 'Excellent', '₹2,50,000')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-secondary" onclick="showToast('Compare feature coming soon')">
                    <i class="fas fa-balance-scale"></i> Compare
                </button>
                <button class="action-btn action-btn-success" onclick="checkProfileAndApply('HDFC Millennia Credit Card')">
                    <i class="fas fa-check"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- SBI SimplySAVE Credit Card -->
        <div class="credit-card">
            <div class="card-header">
                <div class="card-logo sbi">S</div>
                <div class="card-info">
                    <div class="card-name">SBI SimplySAVE Credit Card</div>
                    <div class="card-sub">
                        <span class="badge-free">Lifetime Free</span>
                        <span>5% cashback on all spends</span>
                    </div>
                </div>
            </div>
            <div class="card-benefits">
                <div class="benefit"><i class="fas fa-percent"></i> 5% cashback on all spends</div>
                <div class="benefit"><i class="fas fa-percent"></i> 2.5% on dining & groceries</div>
            </div>
            <div class="card-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('SBI SimplySAVE Credit Card', 'Lifetime Free', '5% cashback on all spends, 2.5% on dining & groceries', '1% fuel surcharge waiver, 10x rewards on partner offers', 'Aadhar Card, PAN Card, Bank Statement, IT Returns', 'Excellent', '₹2,00,000')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-secondary" onclick="showToast('Compare feature coming soon')">
                    <i class="fas fa-balance-scale"></i> Compare
                </button>
                <button class="action-btn action-btn-success" onclick="checkProfileAndApply('SBI SimplySAVE Credit Card')">
                    <i class="fas fa-check"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- Axis Bank Neo Credit Card -->
        <div class="credit-card">
            <div class="card-header">
                <div class="card-logo axis">A</div>
                <div class="card-info">
                    <div class="card-name">Axis Bank Neo Credit Card</div>
                    <div class="card-sub">
                        <span class="badge-free">Lifetime Free</span>
                        <span>Cashback on every purchase</span>
                    </div>
                </div>
            </div>
            <div class="card-benefits">
                <div class="benefit"><i class="fas fa-percent"></i> 5% cashback on all spends</div>
                <div class="benefit"><i class="fas fa-percent"></i> 2% on dining & travel</div>
            </div>
            <div class="card-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Axis Bank Neo Credit Card', 'Lifetime Free', '5% cashback on all spends, 2% on dining & travel', '1% fuel surcharge waiver, Zero forex markup', 'Aadhar Card, PAN Card, Bank Statement, Income Proof', 'Good', '₹1,50,000')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-secondary" onclick="showToast('Compare feature coming soon')">
                    <i class="fas fa-balance-scale"></i> Compare
                </button>
                <button class="action-btn action-btn-success" onclick="checkProfileAndApply('Axis Bank Neo Credit Card')">
                    <i class="fas fa-check"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- 12 Other Card Offers -->
        <div style="margin-top: 16px; padding: 12px 16px; background: #f0f3ff; border-radius: 12px; text-align: center;">
            <span style="font-size: 13px; font-weight: 600; color: var(--primary);">
                <i class="fas fa-credit-card"></i> 12 Other Card Offers Available
            </span>
            <span style="display: block; font-size: 11px; color: var(--text-secondary); margin-top: 2px;">Apply now and get the best credit card for you</span>
        </div>
    </div>

    <!-- MORE DETAILS MODAL -->
    <div id="moreDetailsModal" class="application-modal">
        <div class="application-content">
            <div class="application-header">
                <h3><i class="fas fa-credit-card" style="color: var(--primary);"></i> Card Details</h3>
                <button class="application-close" onclick="closeMoreDetails()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="application-body">
                <div style="margin-bottom: 16px;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <span style="font-size: 10px; color: var(--text-tertiary); text-transform: uppercase; font-weight: 600;">Card Name</span>
                            <span style="font-size: 15px; font-weight: 700; display: block; margin-top: 2px;" id="mdCardName">Roar Bank Credit Card</span>
                        </div>
                        <div>
                            <span style="font-size: 10px; color: var(--text-tertiary); text-transform: uppercase; font-weight: 600;">Fee</span>
                            <span style="font-size: 15px; font-weight: 700; display: block; margin-top: 2px; color: var(--warning);" id="mdFee">Lifetime Free</span>
                        </div>
                        <div>
                            <span style="font-size: 10px; color: var(--text-tertiary); text-transform: uppercase; font-weight: 600;">Cashback</span>
                            <span style="font-size: 15px; font-weight: 700; display: block; margin-top: 2px; color: var(--success);" id="mdCashback">Up to 20%</span>
                        </div>
                        <div>
                            <span style="font-size: 10px; color: var(--text-tertiary); text-transform: uppercase; font-weight: 600;">Credit Limit</span>
                            <span style="font-size: 15px; font-weight: 700; display: block; margin-top: 2px;" id="mdLimit">₹1,00,000</span>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 2px solid var(--border);">
                        <i class="fas fa-gift" style="color: var(--orange);"></i> Benefits
                    </div>
                    <ul style="list-style: none; padding: 0;" id="mdBenefits">
                        <li style="padding: 8px 0; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--text-secondary);">
                            <i class="fas fa-percent" style="color: var(--primary); margin-top: 2px;"></i> Up to 20% cashback on card spends
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--text-secondary);">
                            <i class="fas fa-calendar-alt" style="color: var(--primary); margin-top: 2px;"></i> 62 days interest-free period
                        </li>
                    </ul>
                </div>

                <div style="margin-bottom: 16px;">
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 2px solid var(--border);">
                        <i class="fas fa-file-alt" style="color: var(--primary);"></i> Documents Required
                    </div>
                    <ul style="list-style: none; padding: 0;" id="mdDocuments">
                        <li style="padding: 8px 0; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--text-secondary);">
                            <i class="fas fa-id-card" style="color: var(--primary); margin-top: 2px;"></i> Aadhar Card
                        </li>
                        <li style="padding: 8px 0; display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--text-secondary);">
                            <i class="fas fa-id-card" style="color: var(--primary); margin-top: 2px;"></i> PAN Card
                        </li>
                    </ul>
                </div>

                <button class="submit-btn" onclick="closeMoreDetails(); checkProfileAndApply(document.getElementById('mdCardName').textContent)">
                    <i class="fas fa-credit-card"></i> Apply Now
                </button>
            </div>
        </div>
    </div>

    <!-- APPLICATION FORM MODAL -->
    <div id="applicationModal" class="application-modal">
        <div class="application-content">
            <div class="application-header">
                <h3><i class="fas fa-credit-card"></i> Apply for <span id="selectedCardName">Credit Card</span></h3>
                <button class="application-close" onclick="closeApplicationForm()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="application-body">
                <form id="applicationForm" onsubmit="submitApplication(event)">
                    
                    <!-- Personal Details Section -->
                    <div style="background: #f8f9fc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">
                            <i class="fas fa-id-card"></i> Personal Details
                        </div>

                        <div class="form-row" style="margin-bottom: 10px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-calendar-alt"></i> Date of Birth <span class="required">*</span></label>
                                <input type="date" class="form-input" id="appDob" required>
                                <div class="form-error" id="dobError">Please select your date of birth</div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-venus-mars"></i> Gender <span class="required">*</span></label>
                                <select class="form-select" id="appGender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="form-error" id="genderError">Please select your gender</div>
                            </div>
                        </div>

                        <div class="form-row" style="margin-bottom: 0;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-id-card"></i> Aadhar Number <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appAadhar" placeholder="Enter 12-digit Aadhar number" required maxlength="12">
                                <div class="form-error" id="aadharError">Please enter a valid 12-digit Aadhar number</div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-id-card"></i> PAN Number <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appPan" placeholder="Enter PAN number (e.g. ABCDE1234F)" required maxlength="10">
                                <div class="form-error" id="panError">Please enter a valid PAN number</div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Details Section -->
                    <div style="background: #f8f9fc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">
                            <i class="fas fa-map-marker-alt"></i> Address Details
                        </div>

                        <div class="form-group" style="margin-bottom: 10px;">
                            <label><i class="fas fa-map-marker-alt"></i> Current Address <span class="required">*</span></label>
                            <input type="text" class="form-input" id="appAddress" placeholder="Enter your current address" required>
                            <div class="form-error" id="addressError">Please enter your address</div>
                        </div>

                        <div class="form-row" style="margin-bottom: 0;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-city"></i> City <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appCity" placeholder="Enter city" required>
                                <div class="form-error" id="cityError">Please enter your city</div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-map-pin"></i> PIN Code <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appPincode" placeholder="Enter PIN code" required maxlength="6">
                                <div class="form-error" id="pincodeError">Please enter a valid 6-digit PIN code</div>
                            </div>
                        </div>
                    </div>

                    <!-- Employment & Income Section -->
                    <div style="background: #f8f9fc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">
                            <i class="fas fa-briefcase"></i> Employment & Income
                        </div>

                        <div class="form-row" style="margin-bottom: 0;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-briefcase"></i> Employment Type <span class="required">*</span></label>
                                <select class="form-select" id="appEmployment" required>
                                    <option value="">Select Employment Type</option>
                                    <option value="Salaried">Salaried</option>
                                    <option value="Self-Employed">Self-Employed</option>
                                    <option value="Business">Business</option>
                                    <option value="Student">Student</option>
                                    <option value="Retired">Retired</option>
                                    <option value="Others">Others</option>
                                </select>
                                <div class="form-error" id="employmentError">Please select your employment type</div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-rupee-sign"></i> Monthly Income (₹) <span class="required">*</span></label>
                                <input type="number" class="form-input" id="appIncome" placeholder="Enter monthly income" required>
                                <div class="form-error" id="incomeError">Please enter your monthly income</div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents Upload Section -->
                    <div style="background: #f8f9fc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">
                            <i class="fas fa-upload"></i> Upload Documents
                        </div>

                        <div class="form-group" style="margin-bottom: 12px;">
                            <label><i class="fas fa-id-card"></i> Aadhar Card <span class="required">*</span></label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="appAadharFile" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'aadharFileName')" required>
                                <div class="file-upload-box">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="file-text">Click or drag to upload <span>Aadhar Card</span></div>
                                    <div class="file-name-display" id="aadharFileName">📄 <span></span></div>
                                </div>
                            </div>
                            <div class="form-error" id="aadharFileError">Please upload your Aadhar Card</div>
                        </div>

                        <div class="form-group" style="margin-bottom: 12px;">
                            <label><i class="fas fa-id-card"></i> PAN Card <span class="required">*</span></label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="appPanFile" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'panFileName')" required>
                                <div class="file-upload-box">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="file-text">Click or drag to upload <span>PAN Card</span></div>
                                    <div class="file-name-display" id="panFileName">📄 <span></span></div>
                                </div>
                            </div>
                            <div class="form-error" id="panFileError">Please upload your PAN Card</div>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label><i class="fas fa-university"></i> Bank Statement (Last 3 Months) <span class="required">*</span></label>
                            <div class="file-upload-wrapper">
                                <input type="file" id="appBankStatement" accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileUpload(this, 'bankFileName')" required>
                                <div class="file-upload-box">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <div class="file-text">Click or drag to upload <span>Bank Statement</span></div>
                                    <div class="file-name-display" id="bankFileName">📄 <span></span></div>
                                </div>
                            </div>
                            <div class="form-error" id="bankFileError">Please upload your Bank Statement</div>
                        </div>
                    </div>

                    <!-- Card Selection -->
                    <div class="form-group">
                        <label><i class="fas fa-credit-card"></i> Selected Card</label>
                        <input type="text" class="form-input" id="appCardName" readonly style="background:#f5f5f5;font-weight:600;">
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>

                    <div class="form-note">
                        <i class="fas fa-lock"></i> Your information is secure and will only be used for processing your application.
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="toast"></div>
</div>

<script>
    // ============================================================
    // PROFILE CHECK
    // ============================================================
    function checkProfileAndApply(cardName) {
        <?php if ($profile_complete): ?>
            openApplicationForm(cardName);
        <?php else: ?>
            showToast('⚠️ Please complete your profile first!');
            // Highlight the profile status banner
            const statusBanner = document.querySelector('.profile-status.pending');
            if (statusBanner) {
                statusBanner.style.animation = 'none';
                setTimeout(() => {
                    statusBanner.style.animation = 'fadeIn 0.3s ease';
                    statusBanner.style.borderColor = '#ff9800';
                    statusBanner.style.boxShadow = '0 0 20px rgba(255,152,0,0.3)';
                }, 10);
                setTimeout(() => {
                    statusBanner.style.borderColor = '';
                    statusBanner.style.boxShadow = '';
                }, 3000);
            }
            // Scroll to profile status
            document.querySelector('.profile-status.pending')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        <?php endif; ?>
    }

    // ============================================================
    // FILE UPLOAD HANDLER
    // ============================================================
    function handleFileUpload(input, displayId) {
        const file = input.files[0];
        const display = document.getElementById(displayId);
        if (file) {
            const span = display.querySelector('span');
            span.textContent = file.name;
            display.classList.add('show');
            // Clear error if exists
            const errorId = input.id.replace('app', '').replace('File', 'FileError');
            const errorEl = document.getElementById(errorId.toLowerCase() + 'Error');
            if (errorEl) errorEl.classList.remove('show');
            if (input) input.classList.remove('error');
        }
    }

    // ============================================================
    // APPLICATION FORM
    // ============================================================
    let selectedCardName = '';

    function openApplicationForm(cardName) {
        selectedCardName = cardName || 'Credit Card';
        document.getElementById('selectedCardName').textContent = selectedCardName;
        document.getElementById('appCardName').value = selectedCardName;
        
        // Clear previous errors
        document.querySelectorAll('.form-error').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.form-input, .form-select').forEach(el => el.classList.remove('error'));
        document.querySelectorAll('.file-name-display').forEach(el => el.classList.remove('show'));
        
        document.getElementById('applicationModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeApplicationForm() {
        document.getElementById('applicationModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    function submitApplication(e) {
        e.preventDefault();
        
        // Get all form values
        const dob = document.getElementById('appDob').value;
        const gender = document.getElementById('appGender').value;
        const aadhar = document.getElementById('appAadhar').value.trim();
        const pan = document.getElementById('appPan').value.trim().toUpperCase();
        const address = document.getElementById('appAddress').value.trim();
        const city = document.getElementById('appCity').value.trim();
        const pincode = document.getElementById('appPincode').value.trim();
        const employment = document.getElementById('appEmployment').value;
        const income = document.getElementById('appIncome').value.trim();
        const cardName = document.getElementById('appCardName').value;
        
        // File inputs
        const aadharFile = document.getElementById('appAadharFile').files[0];
        const panFile = document.getElementById('appPanFile').files[0];
        const bankFile = document.getElementById('appBankStatement').files[0];

        // Validation
        let isValid = true;

        // DOB validation
        if (!dob) {
            showError('appDob', 'dobError');
            isValid = false;
        } else {
            clearError('appDob', 'dobError');
        }

        // Gender validation
        if (!gender) {
            showError('appGender', 'genderError');
            isValid = false;
        } else {
            clearError('appGender', 'genderError');
        }

        // Aadhar validation (12 digits)
        const aadharRegex = /^[0-9]{12}$/;
        if (!aadharRegex.test(aadhar)) {
            showError('appAadhar', 'aadharError');
            isValid = false;
        } else {
            clearError('appAadhar', 'aadharError');
        }

        // PAN validation (e.g., ABCDE1234F)
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        if (!panRegex.test(pan)) {
            showError('appPan', 'panError');
            isValid = false;
        } else {
            clearError('appPan', 'panError');
        }

        // Address validation
        if (address.length < 5) {
            showError('appAddress', 'addressError');
            isValid = false;
        } else {
            clearError('appAddress', 'addressError');
        }

        // City validation
        if (city.length < 2) {
            showError('appCity', 'cityError');
            isValid = false;
        } else {
            clearError('appCity', 'cityError');
        }

        // Pincode validation
        const pincodeRegex = /^[0-9]{6}$/;
        if (!pincodeRegex.test(pincode)) {
            showError('appPincode', 'pincodeError');
            isValid = false;
        } else {
            clearError('appPincode', 'pincodeError');
        }

        // Employment validation
        if (!employment) {
            showError('appEmployment', 'employmentError');
            isValid = false;
        } else {
            clearError('appEmployment', 'employmentError');
        }

        // Income validation
        if (!income || parseInt(income) <= 0) {
            showError('appIncome', 'incomeError');
            isValid = false;
        } else {
            clearError('appIncome', 'incomeError');
        }

        // File validations
        if (!aadharFile) {
            showError('appAadharFile', 'aadharFileError');
            isValid = false;
        } else {
            clearError('appAadharFile', 'aadharFileError');
        }

        if (!panFile) {
            showError('appPanFile', 'panFileError');
            isValid = false;
        } else {
            clearError('appPanFile', 'panFileError');
        }

        if (!bankFile) {
            showError('appBankStatement', 'bankFileError');
            isValid = false;
        } else {
            clearError('appBankStatement', 'bankFileError');
        }

        if (!isValid) {
            showToast('Please fill all required fields and upload documents');
            return;
        }

        // Disable submit button
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        // Simulate API call
        setTimeout(() => {
            showToast('✅ Application submitted for ' + cardName + '!');
            
            // Log form data (for demonstration)
            console.log('Application Data:', {
                dob, gender, aadhar, pan, address, city, pincode, employment, income, cardName,
                files: {
                    aadhar: aadharFile.name,
                    pan: panFile.name,
                    bank: bankFile.name
                }
            });
            
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
            closeApplicationForm();
        }, 2000);
    }

    function showError(inputId, errorId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        if (input) input.classList.add('error');
        if (error) error.classList.add('show');
    }

    function clearError(inputId, errorId) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        if (input) input.classList.remove('error');
        if (error) error.classList.remove('show');
    }

    // Real-time validation on input
    document.addEventListener('DOMContentLoaded', function() {
        // Aadhar input - only numbers, max 12 digits
        document.getElementById('appAadhar').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
            if (this.value.length === 12) {
                clearError('appAadhar', 'aadharError');
            }
        });

        // PAN input - auto uppercase
        document.getElementById('appPan').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
            const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
            if (this.value.length === 10 && panRegex.test(this.value)) {
                clearError('appPan', 'panError');
            }
        });

        // Pincode input - only numbers, max 6 digits
        document.getElementById('appPincode').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
            if (this.value.length === 6) {
                clearError('appPincode', 'pincodeError');
            }
        });

        // Income validation on blur
        document.getElementById('appIncome').addEventListener('blur', function() {
            if (this.value && parseInt(this.value) <= 0) {
                showError('appIncome', 'incomeError');
            } else {
                clearError('appIncome', 'incomeError');
            }
        });

        // Close modal on backdrop click
        document.getElementById('applicationModal').addEventListener('click', function(e) {
            if (e.target === this) closeApplicationForm();
        });

        // Drag and drop effects for file uploads
        document.querySelectorAll('.file-upload-box').forEach(box => {
            box.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            box.addEventListener('dragleave', function(e) {
                this.classList.remove('dragover');
            });
            box.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const input = this.parentElement.querySelector('input[type="file"]');
                if (input && e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    const event = new Event('change');
                    input.dispatchEvent(event);
                }
            });
        });
    });

    // ============================================================
    // MORE DETAILS MODAL
    // ============================================================
    function openMoreDetails(cardName, fee, cashback, benefit1, benefit2, documents, eligibility, limit) {
        document.getElementById('mdCardName').textContent = cardName;
        document.getElementById('mdFee').textContent = fee;
        document.getElementById('mdCashback').textContent = cashback;
        document.getElementById('mdLimit').textContent = limit || '₹1,00,000';

        // Benefits
        const benefits = [benefit1, benefit2];
        const benefitsHtml = benefits.map(b => 
            `<li style="padding: 8px 0; border-bottom: 1px solid var(--border); display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--text-secondary);">
                <i class="fas fa-percent" style="color: var(--primary); margin-top: 2px;"></i> ${b}
            </li>`
        ).join('');
        document.getElementById('mdBenefits').innerHTML = benefitsHtml;

        // Documents
        const docsList = documents.split(', ');
        const docsHtml = docsList.map((d, i) => 
            `<li style="padding: 8px 0; ${i < docsList.length - 1 ? 'border-bottom: 1px solid var(--border);' : ''} display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: var(--text-secondary);">
                <i class="fas fa-file" style="color: var(--primary); margin-top: 2px;"></i> ${d}
            </li>`
        ).join('');
        document.getElementById('mdDocuments').innerHTML = docsHtml;

        document.getElementById('moreDetailsModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMoreDetails() {
        document.getElementById('moreDetailsModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close more details modal on backdrop click
    document.getElementById('moreDetailsModal').addEventListener('click', function(e) {
        if (e.target === this) closeMoreDetails();
    });

    // ============================================================
    // NAVIGATION
    // ============================================================
    function goBack() {
        window.location.href = 'https://mobile.logintoloans.com/user/dashboard.php';
    }

    // ============================================================
    // TOAST
    // ============================================================
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(t._timeout);
        t._timeout = setTimeout(() => t.classList.remove('show'), 3000);
    }

    // ============================================================
    // EXPOSE GLOBALLY
    // ============================================================
    Object.assign(window, {
        goBack, showToast, openMoreDetails, closeMoreDetails,
        openApplicationForm, closeApplicationForm, submitApplication,
        handleFileUpload, checkProfileAndApply
    });
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>