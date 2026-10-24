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

include('../includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Education Loan - Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
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
            --background: #f5f7fa;
            --white: #ffffff;
            --border: #eef2f6;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --success: #00c853;
            --warning: #ff9800;
            --danger: #ef4444;
            --blue: #1565c0;
            --blue-light: #e3f2fd;
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

        /* ===== PAGE HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, #1565c0, #1e88e5, #42a5f5);
            padding: 20px 16px 30px;
            color: white;
            border-radius: 0 0 24px 24px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .page-header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
        }
        .page-header .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }
        .page-header .back-btn i { font-size: 14px; }
        .page-header h1 {
            font-size: 24px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }
        .page-header p {
            font-size: 13px;
            opacity: 0.8;
            margin-top: 6px;
            position: relative;
            z-index: 1;
        }
        .page-header .header-icon {
            position: absolute;
            right: 16px;
            bottom: 16px;
            font-size: 60px;
            opacity: 0.15;
            z-index: 0;
        }

        /* ===== SECTION HEADERS ===== */
        .section-header { display: flex; justify-content: space-between; align-items: baseline; padding: 0 14px; margin: 20px 0 10px; }
        .section-header h2 { font-size: 16px; font-weight: 700; color: var(--text-primary); }
        .section-header a { font-size: 11px; color: var(--blue); text-decoration: none; font-weight: 600; cursor: pointer; }

        /* ===== FEATURES ===== */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            padding: 0 14px;
            margin: 10px 0 16px;
        }
        .feature-card {
            background: var(--white);
            border-radius: 12px;
            padding: 12px 6px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .feature-card .feature-icon {
            font-size: 22px;
            margin-bottom: 4px;
            color: var(--blue);
        }
        .feature-card .feature-title {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .feature-card .feature-desc {
            font-size: 8px;
            color: var(--text-tertiary);
            margin-top: 2px;
        }

        /* ===== COURSE TYPES ===== */
        .course-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            padding: 0 14px;
            margin: 10px 0 16px;
        }
        .course-card {
            background: var(--white);
            border-radius: 10px;
            padding: 10px 6px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .course-card:active { transform: scale(0.95); background: var(--blue-light); }
        .course-card .course-icon {
            font-size: 22px;
            color: var(--blue);
            margin-bottom: 3px;
        }
        .course-card .course-name {
            font-size: 9px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ===== LOAN OFFERS ===== */
        .offers-list {
            padding: 0 14px;
        }
        .offer-item {
            background: var(--white);
            border-radius: 14px;
            padding: 14px 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .offer-item:active { transform: scale(0.98); background: var(--blue-light); }
        .offer-item .offer-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .offer-item .offer-info {
            flex: 1;
        }
        .offer-item .offer-info .offer-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .offer-item .offer-info .offer-desc {
            font-size: 10px;
            color: var(--text-secondary);
            margin-top: 2px;
        }
        .offer-item .offer-info .offer-tag {
            font-size: 8px;
            color: var(--blue);
            font-weight: 600;
            background: var(--blue-light);
            padding: 2px 8px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 3px;
        }
        .offer-item .offer-arrow {
            color: var(--text-tertiary);
            font-size: 12px;
        }

        /* ===== EMI CALCULATOR ===== */
        .emi-section {
            padding: 0 14px;
            margin: 16px 0 10px;
        }
        .emi-box {
            background: var(--white);
            border-radius: 14px;
            padding: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .emi-box .emi-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
        }
        .emi-box .emi-title i { color: var(--blue); margin-right: 6px; }
        .emi-box .form-group {
            margin-bottom: 10px;
        }
        .emi-box .form-group label {
            display: block;
            font-size: 10px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 3px;
        }
        .emi-box .form-group label i { margin-right: 4px; color: var(--blue); }
        .emi-box .form-group input,
        .emi-box .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
            background: var(--white);
        }
        .emi-box .form-group input:focus,
        .emi-box .form-group select:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(21,101,192,0.1);
        }
        .emi-box .emi-result {
            margin-top: 12px;
            padding: 12px;
            background: var(--blue-light);
            border-radius: 10px;
            text-align: center;
        }
        .emi-box .emi-result .emi-amount {
            font-size: 22px;
            font-weight: 800;
            color: var(--blue);
        }
        .emi-box .emi-result .emi-label {
            font-size: 10px;
            color: var(--text-secondary);
        }
        .emi-box .calculate-btn {
            width: 100%;
            padding: 8px;
            background: linear-gradient(135deg, #1565c0, #1e88e5);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            margin-top: 4px;
        }
        .emi-box .calculate-btn:active { transform: scale(0.98); }

        /* ===== WHY CHOOSE ===== */
        .why-section {
            padding: 0 14px;
            margin: 16px 0 10px;
        }
        .why-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .why-card {
            background: var(--white);
            border-radius: 12px;
            padding: 12px 10px;
            text-align: center;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }
        .why-card .why-icon {
            font-size: 20px;
            color: var(--blue);
            margin-bottom: 4px;
        }
        .why-card .why-text {
            font-size: 10px;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ===== APPLY BUTTON ===== */
        .apply-fixed-btn {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            max-width: 480px;
            margin: 0 auto;
            padding: 12px 16px;
            background: var(--white);
            border-top: 1px solid var(--border);
            box-shadow: 0 -4px 12px rgba(0,0,0,0.08);
            z-index: 100;
        }
        .apply-fixed-btn .apply-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #1565c0, #1e88e5);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .apply-fixed-btn .apply-btn:active { transform: scale(0.97); }

        /* ============================================================ */
        /* STEP-BY-STEP FORM */
        /* ============================================================ */
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
            padding: 20px;
            width: 94%;
            max-width: 400px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalPop 0.3s ease;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }
        @keyframes modalPop {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border);
        }
        .modal-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }
        .modal-header h3 i { color: var(--blue); margin-right: 6px; }
        .modal-close {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--border);
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: 0.2s;
        }
        .modal-close:active { background: #e0e0e0; }

        /* Step Indicators */
        .step-indicators {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 18px;
            padding: 0 10px;
        }
        .step-dot {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .step-dot .dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-tertiary);
            background: var(--border);
            border: 2px solid var(--border);
            transition: all 0.3s ease;
        }
        .step-dot .dot.active {
            color: white;
            background: var(--blue);
            border-color: var(--blue);
        }
        .step-dot .dot.done {
            color: white;
            background: var(--success);
            border-color: var(--success);
        }
        .step-dot .step-label {
            font-size: 9px;
            color: var(--text-tertiary);
            font-weight: 500;
            display: none;
        }
        .step-dot .step-label.active { display: block; color: var(--blue); }
        .step-dot .step-label.done { display: block; color: var(--success); }
        .step-line {
            width: 30px;
            height: 2px;
            background: var(--border);
            transition: all 0.3s ease;
        }
        .step-line.done { background: var(--success); }

        .form-step {
            display: none;
            animation: fadeIn 0.3s ease;
        }
        .form-step.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 12px;
        }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--text-secondary);
        }
        .form-group label .required-star { color: #c62828; }
        .form-group label i { margin-right: 6px; color: var(--blue); width: 16px; }
        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
            background: var(--white);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(21,101,192,0.1);
        }
        .form-input.file-input {
            padding: 6px 10px;
            cursor: pointer;
        }
        .form-input.file-input::-webkit-file-upload-button {
            background: var(--blue);
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 10px;
        }
        .form-input.file-input::-webkit-file-upload-button:hover {
            background: #1e88e5;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .form-section-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 14px 0 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid var(--border);
        }
        .form-section-title i { color: var(--blue); margin-right: 6px; }

        .file-upload-status {
            font-size: 9px;
            color: var(--text-tertiary);
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .file-upload-status .uploaded { color: var(--success); }
        .file-upload-status .pending { color: var(--warning); }

        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 12px 0;
        }
        .terms-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            flex-shrink: 0;
            cursor: pointer;
            accent-color: var(--blue);
        }
        .terms-check label {
            font-size: 12px;
            color: var(--text-secondary);
            cursor: pointer;
            line-height: 1.4;
        }
        .terms-check label a {
            color: var(--blue);
            text-decoration: underline;
        }

        .step-buttons {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }
        .step-buttons .btn-prev,
        .step-buttons .btn-next,
        .step-buttons .btn-submit {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            flex: 1;
        }
        .step-buttons .btn-prev:active,
        .step-buttons .btn-next:active,
        .step-buttons .btn-submit:active { transform: scale(0.97); }
        .step-buttons .btn-prev {
            background: var(--border);
            color: var(--text-secondary);
        }
        .step-buttons .btn-next {
            background: linear-gradient(135deg, #1565c0, #1e88e5);
            color: white;
        }
        .step-buttons .btn-submit {
            background: linear-gradient(135deg, #00c853, #00e676);
            color: white;
        }
        .step-buttons .btn-prev:disabled,
        .step-buttons .btn-next:disabled,
        .step-buttons .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ===== SUCCESS MODAL ===== */
        .success-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-overlay.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }
        .success-modal {
            background: var(--white);
            border-radius: 24px;
            padding: 24px 20px;
            width: 100%;
            max-width: 400px;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalPop 0.3s ease;
        }
        .success-modal .success-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--blue-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 32px;
            color: var(--blue);
        }
        .success-modal .success-title {
            text-align: center;
            font-size: 20px;
            font-weight: 800;
            color: var(--text-primary);
        }
        .success-modal .success-sub {
            text-align: center;
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 4px;
        }
        .success-modal .success-divider {
            height: 1px;
            background: var(--border);
            margin: 16px 0;
        }
        .success-modal .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .success-modal .detail-row:last-child {
            border-bottom: none;
        }
        .success-modal .detail-row .label {
            font-size: 12px;
            color: var(--text-tertiary);
            font-weight: 500;
        }
        .success-modal .detail-row .value {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .success-modal .detail-row .value.blue { color: var(--blue); }
        .success-modal .success-btn {
            width: 100%;
            padding: 12px;
            margin-top: 16px;
            background: linear-gradient(135deg, #1565c0, #1e88e5);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }
        .success-modal .success-btn:active { transform: scale(0.97); }

        /* ===== TOAST ===== */
        .toast {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--text-primary);
            color: white;
            padding: 10px 20px;
            border-radius: 24px;
            font-size: 13px;
            z-index: 999;
            transition: transform 0.3s;
            white-space: nowrap;
            max-width: 90%;
            text-align: center;
        }
        .toast.show { transform: translateX(-50%) translateY(0); }

        .footer-note {
            text-align: center;
            font-size: 10px;
            color: var(--text-tertiary);
            padding: 14px;
            border-top: 1px solid var(--border);
            margin-top: 16px;
            padding-bottom: 80px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 374px) {
            .page-header h1 { font-size: 20px; }
            .page-header .header-icon { font-size: 40px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
            .feature-card { padding: 10px 4px; }
            .feature-card .feature-icon { font-size: 18px; }
            .course-grid { grid-template-columns: repeat(2, 1fr); }
            .form-row { grid-template-columns: 1fr; }
            .step-dot .dot { width: 28px; height: 28px; font-size: 10px; }
            .step-line { width: 20px; }
        }

        @media (max-width: 480px) {
            .page-header { padding: 16px 14px 24px; }
            .page-header h1 { font-size: 22px; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .feature-card .feature-title { font-size: 9px; }
            .feature-card .feature-desc { font-size: 7px; }
            .offer-item { padding: 12px 14px; }
            .offer-item .offer-icon { width: 38px; height: 38px; font-size: 16px; }
            .offer-item .offer-info .offer-name { font-size: 12px; }
            .course-grid { grid-template-columns: repeat(3, 1fr); }
            .course-card .course-name { font-size: 8px; }
            .modal-content { padding: 16px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
            .step-dot .dot { width: 28px; height: 28px; font-size: 10px; }
            .step-line { width: 20px; }
            .step-dot .step-label { display: none !important; }
            .success-modal { padding: 16px; }
            .success-modal .success-icon { width: 56px; height: 56px; font-size: 26px; }
            .success-modal .success-title { font-size: 18px; }
        }
    </style>
</head>
<body>

<div class="app-container">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <a href="javascript:history.back()" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1>🎓 Education Loan</h1>
        <p>Finance your dreams with affordable education loans</p>
        <div class="header-icon"><i class="fas fa-graduation-cap"></i></div>
    </div>

    <!-- FEATURES -->
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-percent"></i></div>
            <div class="feature-title">Low Interest</div>
            <div class="feature-desc">From 6.5% p.a.</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-clock"></i></div>
            <div class="feature-title">Quick Approval</div>
            <div class="feature-desc">2-3 days</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="feature-title">High Amount</div>
            <div class="feature-desc">Up to ₹50L</div>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="feature-title">Flexible Tenure</div>
            <div class="feature-desc">Up to 15 years</div>
        </div>
    </div>

    <!-- COURSE TYPES -->
    <div class="section-header">
        <h2>📚 Course Types</h2>
        <a href="#" onclick="openApplyModal()">Apply Now</a>
    </div>
    <div class="course-grid">
        <div class="course-card" onclick="openApplyModal()">
            <div class="course-icon"><i class="fas fa-university"></i></div>
            <div class="course-name">Undergraduate</div>
        </div>
        <div class="course-card" onclick="openApplyModal()">
            <div class="course-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="course-name">Postgraduate</div>
        </div>
        <div class="course-card" onclick="openApplyModal()">
            <div class="course-icon"><i class="fas fa-flask"></i></div>
            <div class="course-name">PhD/Research</div>
        </div>
        <div class="course-card" onclick="openApplyModal()">
            <div class="course-icon"><i class="fas fa-plane"></i></div>
            <div class="course-name">Study Abroad</div>
        </div>
        <div class="course-card" onclick="openApplyModal()">
            <div class="course-icon"><i class="fas fa-laptop-code"></i></div>
            <div class="course-name">Vocational</div>
        </div>
        <div class="course-card" onclick="openApplyModal()">
            <div class="course-icon"><i class="fas fa-certificate"></i></div>
            <div class="course-name">Professional</div>
        </div>
    </div>

    <!-- LOAN OFFERS -->
    <div class="section-header">
        <h2>💰 Loan Offers</h2>
        <a href="#" onclick="openApplyModal()">Apply Now</a>
    </div>
    <div class="offers-list">
        <div class="offer-item" onclick="openApplyModal()">
            <div class="offer-icon" style="background: var(--blue-light); color: var(--blue);">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">SBI Education Loan</div>
                <div class="offer-desc">Government bank education scheme</div>
                <span class="offer-tag">6.5% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal()">
            <div class="offer-icon" style="background: #e8f5e9; color: #2e7d32;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">HDFC Education Loan</div>
                <div class="offer-desc">Quick approval for education</div>
                <span class="offer-tag">7.0% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal()">
            <div class="offer-icon" style="background: #fff3e0; color: #e65100;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">ICICI Education Loan</div>
                <div class="offer-desc">Study abroad special scheme</div>
                <span class="offer-tag">6.75% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>

        <div class="offer-item" onclick="openApplyModal()">
            <div class="offer-icon" style="background: #fce4ec; color: #c62828;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="offer-info">
                <div class="offer-name">Axis Education Loan</div>
                <div class="offer-desc">Flexible funding for students</div>
                <span class="offer-tag">6.9% p.a. onwards</span>
            </div>
            <i class="fas fa-chevron-right offer-arrow"></i>
        </div>
    </div>

    <!-- EMI CALCULATOR -->
    <div class="emi-section">
        <div class="emi-box">
            <div class="emi-title"><i class="fas fa-calculator"></i> EMI Calculator</div>
            <div class="form-group">
                <label><i class="fas fa-rupee-sign"></i> Loan Amount (₹)</label>
                <input type="number" id="emiLoanAmount" placeholder="Enter loan amount" value="500000">
            </div>
            <div class="form-group">
                <label><i class="fas fa-percent"></i> Interest Rate (% p.a.)</label>
                <input type="number" id="emiInterestRate" placeholder="Enter interest rate" value="6.5" step="0.1">
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Loan Tenure</label>
                <select id="emiTenure">
                    <option value="1">1 Year</option>
                    <option value="2">2 Years</option>
                    <option value="3">3 Years</option>
                    <option value="5" selected>5 Years</option>
                    <option value="7">7 Years</option>
                    <option value="10">10 Years</option>
                    <option value="15">15 Years</option>
                </select>
            </div>
            <button class="calculate-btn" onclick="calculateEMI()"><i class="fas fa-calculator"></i> Calculate EMI</button>
            <div class="emi-result" id="emiResult">
                <div class="emi-label">Monthly EMI</div>
                <div class="emi-amount" id="emiAmount">₹ 9,786</div>
                <div style="font-size:9px;color:var(--text-tertiary);margin-top:3px;">
                    Total: ₹<span id="totalPayment">5,87,160</span> | Interest: ₹<span id="totalInterest">87,160</span>
                </div>
            </div>
        </div>
    </div>

    <!-- WHY CHOOSE -->
    <div class="why-section">
        <div class="section-header" style="padding: 0; margin: 0 0 10px 0;">
            <h2>✅ Why Choose Education Loan</h2>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Low Interest Rates</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Moratorium Period</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">Tax Benefits</div>
            </div>
            <div class="why-card">
                <div class="why-icon"><i class="fas fa-check-circle"></i></div>
                <div class="why-text">No Collateral</div>
            </div>
        </div>
    </div>

    <!-- FIXED APPLY BUTTON -->
    <div class="apply-fixed-btn">
        <button class="apply-btn" onclick="openApplyModal()">
            <i class="fas fa-graduation-cap"></i> Apply for Education Loan
        </button>
    </div>

    <!-- ============================================================ -->
    <!-- STEP-BY-STEP APPLICATION FORM -->
    <!-- ============================================================ -->
    <div id="applyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-graduation-cap"></i> Apply for Education Loan</h3>
                <button class="modal-close" onclick="closeModal('applyModal')">✕</button>
            </div>

            <!-- Step Indicators -->
            <div class="step-indicators">
                <div class="step-dot">
                    <div class="dot active" id="dot1">1</div>
                    <span class="step-label active" id="label1">Personal</span>
                </div>
                <div class="step-line" id="line1"></div>
                <div class="step-dot">
                    <div class="dot" id="dot2">2</div>
                    <span class="step-label" id="label2">Education</span>
                </div>
                <div class="step-line" id="line2"></div>
                <div class="step-dot">
                    <div class="dot" id="dot3">3</div>
                    <span class="step-label" id="label3">Financial</span>
                </div>
                <div class="step-line" id="line3"></div>
                <div class="step-dot">
                    <div class="dot" id="dot4">4</div>
                    <span class="step-label" id="label4">Documents</span>
                </div>
            </div>

            <!-- STEP 1: Personal Information -->
            <div class="form-step active" id="step1">
                <div class="form-section-title"><i class="fas fa-user"></i> Personal Information</div>

                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Loan Type</label>
                    <input type="text" class="form-input" readonly style="background:#f5f5f5;font-weight:600;" value="Education Loan">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Full Name <span class="required-star">*</span></label>
                        <input type="text" class="form-input" id="applicantName" value="<?php echo $fullname; ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Date of Birth <span class="required-star">*</span></label>
                        <input type="date" class="form-input" id="dob">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> Email <span class="required-star">*</span></label>
                        <input type="email" class="form-input" id="applicantEmail" value="<?php echo $email; ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Phone <span class="required-star">*</span></label>
                        <input type="tel" class="form-input" id="applicantPhone" value="<?php echo $phone; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Address <span class="required-star">*</span></label>
                    <input type="text" class="form-input" id="address" placeholder="Enter your current address">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-city"></i> City <span class="required-star">*</span></label>
                        <input type="text" class="form-input" id="city" placeholder="Enter your city">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-code"></i> PIN Code <span class="required-star">*</span></label>
                        <input type="text" class="form-input" id="pincode" placeholder="PIN code" maxlength="6">
                    </div>
                </div>

                <div class="step-buttons">
                    <button class="btn-prev" disabled>Previous</button>
                    <button class="btn-next" onclick="goToStep(2)">Next →</button>
                </div>
            </div>

            <!-- STEP 2: Education Details -->
            <div class="form-step" id="step2">
                <div class="form-section-title"><i class="fas fa-graduation-cap"></i> Education Details</div>

                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Course Type <span class="required-star">*</span></label>
                    <select class="form-input" id="courseType">
                        <option value="">Select Course Type</option>
                        <option value="undergraduate">Undergraduate</option>
                        <option value="postgraduate">Postgraduate</option>
                        <option value="phd">PhD/Research</option>
                        <option value="study_abroad">Study Abroad</option>
                        <option value="vocational">Vocational Training</option>
                        <option value="professional">Professional Course</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-university"></i> Institution/College Name <span class="required-star">*</span></label>
                    <input type="text" class="form-input" id="institutionName" placeholder="Enter college/university name">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Institution Location <span class="required-star">*</span></label>
                        <input type="text" class="form-input" id="institutionLocation" placeholder="City, State">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Course Duration (Years)</label>
                        <input type="number" class="form-input" id="courseDuration" placeholder="Duration in years" step="0.5">
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Course Name/Specialization</label>
                    <input type="text" class="form-input" id="courseName" placeholder="Enter course name">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-file-alt"></i> Academic Background</label>
                    <textarea class="form-input" id="academicBg" rows="2" placeholder="Brief description of your academic background" style="resize:vertical;"></textarea>
                </div>

                <div class="step-buttons">
                    <button class="btn-prev" onclick="goToStep(1)">← Previous</button>
                    <button class="btn-next" onclick="goToStep(3)">Next →</button>
                </div>
            </div>

            <!-- STEP 3: Financial Information -->
            <div class="form-step" id="step3">
                <div class="form-section-title"><i class="fas fa-coins"></i> Financial Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-rupee-sign"></i> Total Course Fee (₹) <span class="required-star">*</span></label>
                        <input type="number" class="form-input" id="courseFee" placeholder="Total course fee">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-rupee-sign"></i> Scholarship/Grants (₹)</label>
                        <input type="number" class="form-input" id="scholarship" placeholder="Scholarship amount" value="0">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-rupee-sign"></i> Family Annual Income (₹) <span class="required-star">*</span></label>
                        <input type="number" class="form-input" id="familyIncome" placeholder="Family annual income">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-rupee-sign"></i> Monthly Expenses</label>
                        <input type="number" class="form-input" id="monthlyExpenses" placeholder="Monthly expenses">
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-rupee-sign"></i> Existing Loan/Debt</label>
                    <input type="number" class="form-input" id="existingDebt" placeholder="Existing debt amount" value="0">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-hand-holding-usd"></i> Loan Amount (₹) <span class="required-star">*</span></label>
                        <input type="number" class="form-input" id="loanAmount" placeholder="Loan amount" min="50000">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Tenure</label>
                        <select class="form-input" id="loanTenure">
                            <option value="1">1 Year</option>
                            <option value="2">2 Years</option>
                            <option value="3">3 Years</option>
                            <option value="5" selected>5 Years</option>
                            <option value="7">7 Years</option>
                            <option value="10">10 Years</option>
                            <option value="15">15 Years</option>
                        </select>
                    </div>
                </div>

                <div class="step-buttons">
                    <button class="btn-prev" onclick="goToStep(2)">← Previous</button>
                    <button class="btn-next" onclick="goToStep(4)">Next →</button>
                </div>
            </div>

            <!-- STEP 4: Document Upload & Terms -->
            <div class="form-step" id="step4">
                <div class="form-section-title"><i class="fas fa-file-upload"></i> Upload Documents</div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> PAN Card <span class="required-star">*</span></label>
                        <input type="file" class="form-input file-input" id="panFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> Aadhaar Card <span class="required-star">*</span></label>
                        <input type="file" class="form-input file-input" id="aadhaarFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-university"></i> Admission Letter <span class="required-star">*</span></label>
                        <input type="file" class="form-input file-input" id="admissionFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> ID Card (Student/College)</label>
                        <input type="file" class="form-input file-input" id="idCardFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-bank"></i> Bank Statement</label>
                        <input type="file" class="form-input file-input" id="bankStatementFile" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> PDF/JPG/PNG, max 5MB</div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Passport Photo <span class="required-star">*</span></label>
                        <input type="file" class="form-input file-input" id="photoFile" accept=".jpg,.jpeg,.png">
                        <div class="file-upload-status"><span class="pending">⚠️</span> JPG/PNG, max 2MB</div>
                    </div>
                </div>

                <div class="form-section-title"><i class="fas fa-check-circle"></i> Terms & Conditions</div>

                <div class="terms-check">
                    <input type="checkbox" id="termsCheck">
                    <label for="termsCheck">I agree to the <a href="#" onclick="showToast('Terms & Conditions accepted')">Terms & Conditions</a> and confirm all information is true.</label>
                </div>

                <div class="step-buttons">
                    <button class="btn-prev" onclick="goToStep(3)">← Previous</button>
                    <button class="btn-submit" onclick="submitEducationApplication()">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SUCCESS MODAL -->
    <!-- ============================================================ -->
    <div id="successOverlay" class="success-overlay">
        <div class="success-modal">
            <div class="success-icon"><i class="fas fa-check-circle"></i></div>
            <div class="success-title">🎓 Application Submitted!</div>
            <div class="success-sub">Your education loan application has been submitted successfully.</div>
            <div class="success-divider"></div>

            <div class="detail-row">
                <span class="label">Student Name</span>
                <span class="value" id="successName"><?php echo $fullname; ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Email</span>
                <span class="value" id="successEmail"><?php echo $email; ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Phone</span>
                <span class="value" id="successPhone"><?php echo $phone; ?></span>
            </div>
            <div class="detail-row">
                <span class="label">Course Type</span>
                <span class="value" id="successCourse">-</span>
            </div>
            <div class="detail-row">
                <span class="label">Institution</span>
                <span class="value" id="successInstitution">-</span>
            </div>
            <div class="detail-row">
                <span class="label">Loan Amount</span>
                <span class="value blue" id="successAmount">-</span>
            </div>
            <div class="detail-row">
                <span class="label">Course Fee</span>
                <span class="value" id="successFee">-</span>
            </div>
            <div class="detail-row" style="border-top:2px solid var(--blue);padding-top:10px;margin-top:6px;">
                <span class="label" style="font-weight:700;color:var(--blue);">Status</span>
                <span class="value blue">✅ Approved</span>
            </div>

            <button class="success-btn" onclick="closeSuccess()">
                <i class="fas fa-check"></i> Done
            </button>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="toast"></div>

    <div class="footer-note"><i class="fas fa-lock"></i> Secure & Trusted | RBI Compliant</div>
</div>

<script>
    // ============================================================
    // STEP NAVIGATION
    // ============================================================
    let currentStep = 1;
    const totalSteps = 4;

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;

        if (step > currentStep) {
            if (!validateStep(currentStep)) {
                return;
            }
        }

        document.querySelectorAll('.form-step').forEach(el => el.classList.remove('active'));

        const targetStep = document.getElementById('step' + step);
        if (targetStep) targetStep.classList.add('active');

        for (let i = 1; i <= totalSteps; i++) {
            const dot = document.getElementById('dot' + i);
            const label = document.getElementById('label' + i);
            const line = document.getElementById('line' + (i - 1));

            dot.classList.remove('active', 'done');
            if (label) label.classList.remove('active', 'done');

            if (i < step) {
                dot.classList.add('done');
                if (label) label.classList.add('done');
                if (line) line.classList.add('done');
            } else if (i === step) {
                dot.classList.add('active');
                if (label) label.classList.add('active');
                if (line) line.classList.remove('done');
            } else {
                if (line) line.classList.remove('done');
            }
        }

        currentStep = step;
    }

    function validateStep(step) {
        let isValid = true;
        let fields = [];

        if (step === 1) {
            fields = ['applicantName', 'applicantEmail', 'applicantPhone', 'dob', 'address', 'city', 'pincode'];
        } else if (step === 2) {
            fields = ['courseType', 'institutionName', 'institutionLocation'];
        } else if (step === 3) {
            fields = ['courseFee', 'familyIncome', 'loanAmount'];
        }

        fields.forEach(id => {
            const field = document.getElementById(id);
            if (!field || !field.value || field.value.trim() === '') {
                field.style.borderColor = '#c62828';
                isValid = false;
            } else {
                field.style.borderColor = '';
            }
        });

        if (!isValid) {
            showToast('Please fill all required fields');
        }

        return isValid;
    }

    // ============================================================
    // EMI CALCULATOR
    // ============================================================
    function calculateEMI() {
        const amount = parseFloat(document.getElementById('emiLoanAmount').value);
        const rate = parseFloat(document.getElementById('emiInterestRate').value);
        const years = parseInt(document.getElementById('emiTenure').value);
        const tenure = years * 12;

        if (!amount || !rate || !years) {
            return;
        }

        const monthlyRate = rate / 12 / 100;
        const emi = amount * monthlyRate * Math.pow(1 + monthlyRate, tenure) / (Math.pow(1 + monthlyRate, tenure) - 1);
        const totalPayment = emi * tenure;
        const totalInterest = totalPayment - amount;

        document.getElementById('emiAmount').textContent = '₹ ' + emi.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('totalPayment').textContent = totalPayment.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        document.getElementById('totalInterest').textContent = totalInterest.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateEMI();
        document.getElementById('emiLoanAmount').addEventListener('input', calculateEMI);
        document.getElementById('emiInterestRate').addEventListener('input', calculateEMI);
        document.getElementById('emiTenure').addEventListener('change', calculateEMI);
    });

    // ============================================================
    // APPLY MODAL
    // ============================================================
    function openApplyModal() {
        document.getElementById('applyModal').classList.add('active');
        goToStep(1);
        document.querySelectorAll('.file-upload-status .pending').forEach(el => {
            el.textContent = '⚠️';
            el.className = 'pending';
        });
    }

    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function() {
            const statusDiv = this.parentElement.querySelector('.file-upload-status span');
            if (this.files.length > 0) {
                statusDiv.textContent = '✅';
                statusDiv.className = 'uploaded';
            } else {
                statusDiv.textContent = '⚠️';
                statusDiv.className = 'pending';
            }
        });
    });

    function submitEducationApplication() {
        const requiredFiles = ['panFile', 'aadhaarFile', 'admissionFile', 'photoFile'];
        let missingFiles = [];
        requiredFiles.forEach(id => {
            const file = document.getElementById(id);
            if (!file.files || file.files.length === 0) {
                missingFiles.push(id);
            }
        });

        if (!document.getElementById('termsCheck').checked) {
            showToast('Please accept the Terms & Conditions');
            return;
        }

        if (missingFiles.length > 0) {
            showToast('Please upload all required documents');
            return;
        }

        const name = document.getElementById('applicantName').value;
        const courseType = document.getElementById('courseType');
        const courseTypeText = courseType.options[courseType.selectedIndex].text;
        const institution = document.getElementById('institutionName').value;
        const amount = document.getElementById('loanAmount').value;
        const fee = document.getElementById('courseFee').value;

        // Update success modal
        document.getElementById('successName').textContent = name;
        document.getElementById('successEmail').textContent = document.getElementById('applicantEmail').value;
        document.getElementById('successPhone').textContent = document.getElementById('applicantPhone').value;
        document.getElementById('successCourse').textContent = courseTypeText;
        document.getElementById('successInstitution').textContent = institution;
        document.getElementById('successAmount').textContent = '₹' + parseInt(amount).toLocaleString('en-IN');
        document.getElementById('successFee').textContent = '₹' + parseInt(fee).toLocaleString('en-IN');

        closeModal('applyModal');
        document.getElementById('successOverlay').classList.add('show');
        showToast('✅ Education loan application submitted successfully!');

        // Reset form
        document.querySelectorAll('.file-input').forEach(input => input.value = '');
        document.querySelectorAll('.file-upload-status span').forEach(el => {
            el.textContent = '⚠️';
            el.className = 'pending';
        });
        document.getElementById('termsCheck').checked = false;
        goToStep(1);
    }

    function closeSuccess() {
        document.getElementById('successOverlay').classList.remove('show');
    }

    // ============================================================
    // TOAST & MODAL HELPERS
    // ============================================================
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(t._timeout);
        t._timeout = setTimeout(() => t.classList.remove('show'), 3000);
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    document.querySelectorAll('.modal').forEach(m => {
        m.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    document.getElementById('successOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeSuccess();
    });

    document.getElementById('pincode').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });

    // ============================================================
    // EXPOSE GLOBALLY
    // ============================================================
    Object.assign(window, {
        goToStep, calculateEMI, openApplyModal, submitEducationApplication,
        closeSuccess, showToast, closeModal
    });
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>