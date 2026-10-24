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
    <title>Personal Loan - Login To Loans</title>
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

        /* ===== PAGE CONTENT ===== */
        .personal-page {
            padding: 0 16px 20px;
        }
        .personal-header {
            padding: 16px 0 12px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 16px;
        }
        .personal-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .personal-header h2 i { color: var(--primary); }
        .personal-header p {
            font-size: 12px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        /* ===== APPLICATION CARDS ===== */
        .application-card {
            background: var(--white);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 14px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            display: block;
        }
        .application-card:active { transform: scale(0.98); }

        .application-card .bank-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .application-card .bank-name .bank-logo {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: white;
            flex-shrink: 0;
            font-weight: 700;
        }
        .bank-logo.icici { background: linear-gradient(135deg, #004080, #0066b3); }
        .bank-logo.hdfc { background: linear-gradient(135deg, #004d7a, #006699); }
        .bank-logo.axis { background: linear-gradient(135deg, #800000, #cc0000); }
        .bank-logo.sbi { background: linear-gradient(135deg, #1a5276, #2e86c1); }
        .bank-logo.kotak { background: linear-gradient(135deg, #2c3e50, #34495e); }
        .bank-logo.bob { background: linear-gradient(135deg, #1a237e, #283593); }
        .bank-logo.idfc { background: linear-gradient(135deg, #0066b3, #0099ff); }
        .bank-logo.ram { background: linear-gradient(135deg, #2c3e50, #e67e22); }
        .bank-logo.canara { background: linear-gradient(135deg, #1a5276, #2e86c1); }
        .bank-logo.pnb { background: linear-gradient(135deg, #004d7a, #006699); }
        .bank-logo.yes { background: linear-gradient(135deg, #008080, #00a86b); }
        .bank-logo.indusind { background: linear-gradient(135deg, #1a237e, #283593); }
        .bank-logo.federal { background: linear-gradient(135deg, #2c3e50, #34495e); }
        .bank-logo.boi { background: linear-gradient(135deg, #1a5276, #2e86c1); }

        .app-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 16px;
            margin-bottom: 12px;
        }
        .app-detail-item {
            display: flex;
            flex-direction: column;
        }
        .app-detail-item .label {
            font-size: 9px;
            color: var(--text-tertiary);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .app-detail-item .value {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-top: 2px;
        }
        .app-detail-item .value .apr {
            font-size: 10px;
            font-weight: 400;
            color: var(--text-secondary);
            display: block;
        }
        .app-detail-item .value .badge-status {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-status.excellent { background: #e8f5e9; color: #2e7d32; }
        .badge-status.good { background: #e3f2fd; color: #1565c0; }
        .badge-status.average { background: #fff3e0; color: #e65100; }
        .app-detail-item .value .emi { color: var(--warning); font-weight: 700; }

        .app-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }
        .app-actions .action-btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-family: 'Inter', sans-serif;
            flex: 1;
            min-width: 80px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .app-actions .action-btn:active { transform: scale(0.95); }
        .app-actions .action-btn-secondary {
            background: var(--border);
            color: var(--text-secondary);
        }
        .app-actions .action-btn-success {
            background: linear-gradient(135deg, #00c853, #00e676);
            color: white;
        }
        .app-actions .action-btn-warning {
            background: linear-gradient(135deg, #ff9800, #ffb74d);
            color: white;
        }

        .progress-container {
            margin: 6px 0 10px;
        }
        .progress-container .progress-bar {
            width: 100%;
            height: 4px;
            background: var(--border);
            border-radius: 4px;
            overflow: hidden;
        }
        .progress-container .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.8s ease;
        }
        .progress-fill.green { background: var(--success); }
        .progress-fill.orange { background: var(--warning); }
        .progress-fill.blue { background: #1565c0; }
        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: var(--text-tertiary);
            margin-top: 3px;
        }

        /* ===== MORE DETAILS MODAL ===== */
        .more-details-modal {
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
        .more-details-modal.active { display: flex; }
        .more-details-content {
            background: var(--white);
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 480px;
            max-height: 92vh;
            overflow-y: auto;
            padding: 0;
            animation: slideUp 0.3s ease;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }
        @keyframes slideUp {
            from { transform: translateY(100%); }
            to { transform: translateY(0); }
        }
        .more-details-header {
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
        .more-details-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .more-details-close {
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
        .more-details-close:active {
            background: #e0e0e0;
            transform: scale(0.95);
        }

        .more-details-body {
            padding: 20px;
        }

        /* ===== APPLICATION FORM MODAL ===== */
        .application-modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 3000;
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
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
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

        /* ===== FILTER SECTION ===== */
        .filter-section {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 16px;
            border: 1px solid var(--border);
        }
        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
        }
        .filter-row:not(:last-child) {
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .filter-row .filter-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
        }
        .filter-row .filter-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .filter-row .filter-value.emi {
            color: var(--orange);
            font-weight: 700;
        }
        .show-details-link {
            color: var(--primary);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 0;
            margin-top: 4px;
        }
        .show-details-link i {
            font-size: 10px;
            transition: transform 0.3s ease;
        }
        .show-details-link i.rotated {
            transform: rotate(180deg);
        }

        .detail-section {
            margin-bottom: 20px;
        }
        .detail-section-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
        }
        .detail-info-item {
            display: flex;
            flex-direction: column;
            background: #f8f9fc;
            border-radius: 10px;
            padding: 10px 12px;
            text-align: center;
        }
        .detail-info-item .dlabel {
            font-size: 9px;
            color: var(--text-tertiary);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .detail-info-item .dvalue {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 3px;
        }
        .detail-info-item .dvalue.emi {
            color: var(--warning);
        }

        .charges-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .charge-item {
            background: #f8f9fc;
            border-radius: 10px;
            padding: 12px 10px;
            text-align: center;
            border: 1px solid var(--border);
        }
        .charge-item .charge-label {
            font-size: 9px;
            color: var(--text-tertiary);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .charge-item .charge-value {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 3px;
        }
        .charge-item .charge-value .small-note {
            font-size: 9px;
            font-weight: 400;
            color: var(--text-tertiary);
            display: block;
            margin-top: 1px;
        }
        .charge-item.highlight {
            grid-column: span 2;
            background: linear-gradient(135deg, #fff8f0, #fff0e0);
            border-color: var(--orange);
        }
        .charge-item.highlight .charge-value {
            color: var(--orange);
            font-size: 16px;
        }

        .kfs-box {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 14px 16px;
            margin-top: 8px;
            border-left: 3px solid var(--orange);
        }
        .kfs-box p {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.6;
        }
        .kfs-box .kfs-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .apply-now-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(255,152,0,0.3);
        }
        .apply-now-btn:active { transform: scale(0.97); }

        /* ===== MODAL ===== */
        .modal { position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 1000; display: flex; align-items: center; justify-content: center; visibility: hidden; opacity: 0; transition: 0.2s; }
        .modal.active { visibility: visible; opacity: 1; }
        .modal-content { background: var(--white); border-radius: 24px; padding: 20px; width: 92%; max-width: 380px; max-height: 85vh; overflow-y: auto; animation: modalPop 0.3s ease; }
        @keyframes modalPop { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 2px solid var(--border); }
        .modal-header h3 { font-size: 1rem; font-weight: 700; color: var(--primary); }
        .modal-close { width: 28px; height: 28px; border-radius: 50%; background: var(--border); border: none; cursor: pointer; font-size: 14px; transition: 0.2s; }
        .modal-close:active { background: #e0e0e0; }

        /* ===== TOAST ===== */
        .toast { position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%) translateY(100px); background: var(--text-primary); color: white; padding: 10px 18px; border-radius: 24px; font-size: 12px; z-index: 9999; transition: transform 0.3s; white-space: nowrap; max-width: 90%; }
        .toast.show { transform: translateX(-50%) translateY(0); }
        .toast.success { background: var(--success); }

        /* ===== SUCCESS MODAL ===== */
        .success-check {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e8f5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }
        .success-check i {
            font-size: 30px;
            color: var(--success);
        }
        .success-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--success);
            text-align: center;
            margin-bottom: 6px;
        }
        .success-sub {
            font-size: 14px;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 16px;
        }
        .success-details-box {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 16px;
            text-align: left;
            margin-bottom: 16px;
            border: 1px solid var(--border);
        }
        .success-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }
        .success-detail-row:not(:last-child) {
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .success-detail-row .s-label {
            color: var(--text-secondary);
        }
        .success-detail-row .s-value {
            font-weight: 600;
            color: var(--text-primary);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .app-details { grid-template-columns: 1fr 1fr; gap: 6px 12px; }
            .app-detail-item .value { font-size: 12px; }
            .application-card { padding: 14px; }
            .personal-header h2 { font-size: 18px; }
            .back-btn { font-size: 14px; }
            .detail-info-grid { grid-template-columns: 1fr 1fr 1fr; gap: 8px; }
            .charges-grid { grid-template-columns: 1fr 1fr; gap: 6px; }
            .charge-item { padding: 10px 8px; }
            .charge-item .charge-value { font-size: 12px; }
            .charge-item .charge-value .small-note { font-size: 8px; }
            .more-details-content { border-radius: 20px 20px 0 0; }
            .more-details-header { padding: 16px 16px 12px; }
            .more-details-body { padding: 16px; }
            .filter-section { padding: 12px; }
            .filter-row .filter-value { font-size: 13px; }
            .detail-info-item { padding: 8px 10px; }
            .detail-info-item .dvalue { font-size: 12px; }
            .form-row { grid-template-columns: 1fr; }
            .application-content { border-radius: 20px 20px 0 0; }
        }
        @media (max-width: 374px) {
            .app-details { grid-template-columns: 1fr 1fr; gap: 4px 10px; }
            .app-detail-item .value { font-size: 11px; }
            .application-card { padding: 12px; }
            .app-actions .action-btn { font-size: 10px; padding: 6px 10px; min-width: 60px; }
            .detail-info-grid { grid-template-columns: 1fr 1fr; gap: 6px; }
            .charges-grid { grid-template-columns: 1fr 1fr; gap: 4px; }
            .charge-item { padding: 6px 8px; }
            .charge-item .charge-value { font-size: 11px; }
            .filter-row .filter-label { font-size: 12px; }
            .filter-row .filter-value { font-size: 12px; }
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
    </div>

    <!-- PERSONAL LOAN APPLICATIONS -->
    <div class="personal-page">
        <div class="personal-header">
            <h2><i class="fas fa-hand-holding-usd"></i> Personal Loan Applications</h2>
            <p>Here are the best loan offers for you</p>
        </div>

        <!-- Ram Fincorp -->
        <div class="application-card" id="cardRam">
            <div class="bank-name">
                <div class="bank-logo ram">R</div>
                Ram Fincorp
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-clock" style="color: var(--warning);"></i> Pending
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill orange" style="width: 25%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>25%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">2 - 4 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">20% onwards <span class="apr">APR: 21.14%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status good">GOOD</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 13,247</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Ram Fincorp', '₹5 Lakhs', '₹13,247', '20%', '21.14%', '3–7%*', '2–5%', '3–7%*')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-warning" onclick="openApplicationForm('Ram Fincorp', '₹5 Lakhs')">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- ICICI Bank -->
        <div class="application-card" id="cardIcici">
            <div class="bank-name">
                <div class="bank-logo icici">I</div>
                ICICI Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i> Active
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill green" style="width: 75%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>75%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">3 - 5 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">9.99% onwards <span class="apr">APR: 11.29%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status excellent">EXCELLENT</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,621 onwards</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('ICICI Bank', '₹5 Lakhs', '₹10,621', '9.99%', '11.29%', '2% p.m.', '1.5% + GST', '3%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-success" onclick="openApplicationForm('ICICI Bank', '₹5 Lakhs')">
                    <i class="fas fa-play"></i> Resume
                </button>
            </div>
        </div>

        <!-- HDFC Bank -->
        <div class="application-card" id="cardHdfc">
            <div class="bank-name">
                <div class="bank-logo hdfc">H</div>
                HDFC Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-clock" style="color: var(--warning);"></i> Pending
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill orange" style="width: 40%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>40%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">3 - 5 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">9.99% onwards <span class="apr">APR: 10.5%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status excellent">EXCELLENT</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 1</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('HDFC Bank', '₹3 Lakhs', '₹1', '9.99%', '10.5%', '2% p.m.', '1% + GST', '3%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-warning" onclick="openApplicationForm('HDFC Bank', '₹3 Lakhs')">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- SBI -->
        <div class="application-card" id="cardSbi">
            <div class="bank-name">
                <div class="bank-logo sbi">S</div>
                State Bank of India
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i> Approved
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill green" style="width: 100%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>100%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">2 - 3 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">8.75% onwards <span class="apr">APR: 9.85%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status excellent">EXCELLENT</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 9,200 onwards</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('SBI', '₹7.5 Lakhs', '₹9,200', '8.75%', '9.85%', '1.5% p.m.', '0.5% + GST', '2%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-success" onclick="showToast('Loan approved! Proceed to disbursement', true)">
                    <i class="fas fa-check"></i> Disburse
                </button>
            </div>
        </div>

        <!-- Axis Bank -->
        <div class="application-card" id="cardAxis">
            <div class="bank-name">
                <div class="bank-logo axis">A</div>
                Axis Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-clock" style="color: var(--warning);"></i> Pending
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill orange" style="width: 30%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>30%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">3 - 6 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">10.25% onwards <span class="apr">APR: 11.85%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status good">GOOD</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,685</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Axis Bank', '₹5 Lakhs', '₹10,685', '10.25%', '11.85%', '2.5% p.m.', '1.5% + GST', '4%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-warning" onclick="openApplicationForm('Axis Bank', '₹5 Lakhs')">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- Kotak Mahindra Bank -->
        <div class="application-card" id="cardKotak">
            <div class="bank-name">
                <div class="bank-logo kotak">K</div>
                Kotak Mahindra Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i> Active
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill green" style="width: 60%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>60%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">2 - 4 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">9.50% onwards <span class="apr">APR: 10.75%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status excellent">EXCELLENT</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,513</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Kotak Mahindra Bank', '₹5 Lakhs', '₹10,513', '9.50%', '10.75%', '2% p.m.', '1.2% + GST', '3.5%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-success" onclick="openApplicationForm('Kotak Mahindra Bank', '₹5 Lakhs')">
                    <i class="fas fa-play"></i> Resume
                </button>
            </div>
        </div>

        <!-- Bank of Baroda -->
        <div class="application-card" id="cardBob">
            <div class="bank-name">
                <div class="bank-logo bob">B</div>
                Bank of Baroda
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-clock" style="color: var(--warning);"></i> Pending
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill orange" style="width: 20%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>20%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">4 - 7 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">8.90% onwards <span class="apr">APR: 10.10%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status good">GOOD</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,350</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Bank of Baroda', '₹5 Lakhs', '₹10,350', '8.90%', '10.10%', '1.8% p.m.', '0.8% + GST', '2.5%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-warning" onclick="openApplicationForm('Bank of Baroda', '₹5 Lakhs')">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- IDFC First Bank -->
        <div class="application-card" id="cardIdfc">
            <div class="bank-name">
                <div class="bank-logo idfc">I</div>
                IDFC First Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i> Approved
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill green" style="width: 100%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>100%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">1 - 3 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">8.75% onwards <span class="apr">APR: 9.90%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status excellent">EXCELLENT</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,300</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('IDFC First Bank', '₹5 Lakhs', '₹10,300', '8.75%', '9.90%', '1.5% p.m.', '0.5% + GST', '2%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-success" onclick="showToast('Loan approved! Proceed to disbursement', true)">
                    <i class="fas fa-check"></i> Disburse
                </button>
            </div>
        </div>

        <!-- Canara Bank -->
        <div class="application-card" id="cardCanara">
            <div class="bank-name">
                <div class="bank-logo canara">C</div>
                Canara Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-clock" style="color: var(--warning);"></i> Pending
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill orange" style="width: 35%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>35%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">3 - 6 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">9.25% onwards <span class="apr">APR: 10.45%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status good">GOOD</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,440</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Canara Bank', '₹5 Lakhs', '₹10,440', '9.25%', '10.45%', '2% p.m.', '1% + GST', '3%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-warning" onclick="openApplicationForm('Canara Bank', '₹5 Lakhs')">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- Punjab National Bank -->
        <div class="application-card" id="cardPnb">
            <div class="bank-name">
                <div class="bank-logo pnb">P</div>
                Punjab National Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i> Active
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill green" style="width: 80%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>80%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">3 - 5 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">8.85% onwards <span class="apr">APR: 10.05%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status excellent">EXCELLENT</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,320</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Punjab National Bank', '₹5 Lakhs', '₹10,320', '8.85%', '10.05%', '1.5% p.m.', '0.7% + GST', '2.5%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-success" onclick="openApplicationForm('Punjab National Bank', '₹5 Lakhs')">
                    <i class="fas fa-play"></i> Resume
                </button>
            </div>
        </div>

        <!-- Yes Bank -->
        <div class="application-card" id="cardYes">
            <div class="bank-name">
                <div class="bank-logo yes">Y</div>
                Yes Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-clock" style="color: var(--warning);"></i> Pending
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill orange" style="width: 15%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>15%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">4 - 7 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">9.75% onwards <span class="apr">APR: 11.15%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status average">AVERAGE</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,550</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Yes Bank', '₹5 Lakhs', '₹10,550', '9.75%', '11.15%', '2.2% p.m.', '1.8% + GST', '4%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-warning" onclick="openApplicationForm('Yes Bank', '₹5 Lakhs')">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- IndusInd Bank -->
        <div class="application-card" id="cardIndusInd">
            <div class="bank-name">
                <div class="bank-logo indusind">I</div>
                IndusInd Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i> Active
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill green" style="width: 70%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>70%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">2 - 4 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">9.25% onwards <span class="apr">APR: 10.60%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status good">GOOD</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,450</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('IndusInd Bank', '₹5 Lakhs', '₹10,450', '9.25%', '10.60%', '2% p.m.', '1.2% + GST', '3.5%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-success" onclick="openApplicationForm('IndusInd Bank', '₹5 Lakhs')">
                    <i class="fas fa-play"></i> Resume
                </button>
            </div>
        </div>

        <!-- Federal Bank -->
        <div class="application-card" id="cardFederal">
            <div class="bank-name">
                <div class="bank-logo federal">F</div>
                Federal Bank
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-clock" style="color: var(--warning);"></i> Pending
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill orange" style="width: 10%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>10%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">4 - 8 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">10.00% onwards <span class="apr">APR: 11.50%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status average">AVERAGE</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,620</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Federal Bank', '₹5 Lakhs', '₹10,620', '10.00%', '11.50%', '2.5% p.m.', '2% + GST', '4.5%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-warning" onclick="openApplicationForm('Federal Bank', '₹5 Lakhs')">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </button>
            </div>
        </div>

        <!-- Bank of India -->
        <div class="application-card" id="cardBoi">
            <div class="bank-name">
                <div class="bank-logo boi">B</div>
                Bank of India
                <span style="margin-left: auto; font-size: 11px; font-weight: 400; color: var(--text-tertiary);">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i> Approved
                </span>
            </div>
            <div class="progress-container">
                <div class="progress-bar"><div class="progress-fill green" style="width: 100%;"></div></div>
                <div class="progress-label"><span>Application Progress</span><span>100%</span></div>
            </div>
            <div class="app-details">
                <div class="app-detail-item"><span class="label">Processing Time</span><span class="value">2 - 5 Days</span></div>
                <div class="app-detail-item"><span class="label">Interest Rate (p.a.)</span><span class="value">8.80% onwards <span class="apr">APR: 9.95%</span></span></div>
                <div class="app-detail-item"><span class="label">Chances Of Approval</span><span class="value"><span class="badge-status excellent">EXCELLENT</span></span></div>
                <div class="app-detail-item"><span class="label">EMI</span><span class="value"><span class="emi">₹ 10,280</span></span></div>
            </div>
            <div class="app-actions">
                <button class="action-btn action-btn-secondary" onclick="openMoreDetails('Bank of India', '₹5 Lakhs', '₹10,280', '8.80%', '9.95%', '1.5% p.m.', '0.6% + GST', '2%')">
                    <i class="fas fa-info-circle"></i> More Details
                </button>
                <button class="action-btn action-btn-success" onclick="showToast('Loan approved! Proceed to disbursement', true)">
                    <i class="fas fa-check"></i> Disburse
                </button>
            </div>
        </div>
    </div>

    <!-- MORE DETAILS MODAL -->
    <div id="moreDetailsModal" class="more-details-modal">
        <div class="more-details-content">
            <div class="more-details-header">
                <h3><i class="fas fa-info-circle" style="color: var(--primary);"></i> More Details</h3>
                <button class="more-details-close" onclick="closeMoreDetails()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="more-details-body">
                <div class="filter-section">
                    <div class="filter-row">
                        <span class="filter-label">Loan Amount</span>
                        <span class="filter-value" id="mdFilterAmount">₹ 5 Lakhs</span>
                    </div>
                    <div class="filter-row">
                        <span class="filter-label">Loan Tenure</span>
                        <span class="filter-value">5 Years</span>
                    </div>
                    <div class="filter-row">
                        <span class="filter-label">APR</span>
                        <span class="filter-value emi" id="mdFilterApr">13.06%</span>
                    </div>
                    <div style="margin-top: 4px;">
                        <span class="show-details-link" onclick="toggleDetails()">
                            <i class="fas fa-chevron-down" id="toggleIcon"></i> Show Details
                        </span>
                    </div>
                </div>

                <div id="hiddenDetails" style="display: none;">
                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="fas fa-building" style="color: var(--primary);"></i> Summary
                        </div>
                        <div class="detail-info-grid">
                            <div class="detail-info-item">
                                <span class="dlabel">Lender</span>
                                <span class="dvalue" id="mdLender">Ram Fincorp</span>
                            </div>
                            <div class="detail-info-item">
                                <span class="dlabel">Loan Amount</span>
                                <span class="dvalue" id="mdAmount">₹ 5 Lakhs</span>
                            </div>
                            <div class="detail-info-item">
                                <span class="dlabel">EMI</span>
                                <span class="dvalue emi" id="mdEmi">₹ 13,247</span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="fas fa-tag" style="color: var(--orange);"></i> Summary of Charges
                        </div>
                        <div class="charges-grid" id="mdChargesGrid">
                            <div class="charge-item">
                                <div class="charge-label">Part Prepayment</div>
                                <div class="charge-value" id="mdPrepayment">3–7%* <span class="small-note">Varies as per duration</span></div>
                            </div>
                            <div class="charge-item">
                                <div class="charge-label">Processing Fees</div>
                                <div class="charge-value" id="mdProcessingFee">2–5% <span class="small-note">on Total Loan Amount</span></div>
                            </div>
                            <div class="charge-item">
                                <div class="charge-label">Foreclosure</div>
                                <div class="charge-value" id="mdForeclosure">3–7%* <span class="small-note">Varies as per duration</span></div>
                            </div>
                            <div class="charge-item">
                                <div class="charge-label">Interest Rate (p.a.)</div>
                                <div class="charge-value" id="mdInterestRate">20% onwards <span class="small-note">on Total Loan Amount</span></div>
                            </div>
                            <div class="charge-item highlight">
                                <div class="charge-label">Annual Percentage Rate</div>
                                <div class="charge-value" id="mdApr">21.14%</div>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <div class="detail-section-title">
                            <i class="fas fa-bullhorn" style="color: var(--orange);"></i> Key Facts Statement
                        </div>
                        <div class="kfs-box">
                            <p><i class="fas fa-info-circle" style="color: var(--orange);"></i> For more details of charges and other loan terms, kindly refer to the provisional kfs below.</p>
                            <p style="margin-top: 8px;">
                                <i class="fas fa-file-pdf" style="color: var(--orange);"></i> 
                                <span class="kfs-link" onclick="showToast('Provisional KFS downloaded', true)">Provisional KFS can be found here.</span>
                            </p>
                        </div>
                    </div>
                </div>

                <button class="apply-now-btn" onclick="handleApplyNow()">
                    <i class="fas fa-hand-holding-usd"></i> Apply Now
                </button>
            </div>
        </div>
    </div>

    <!-- APPLICATION FORM MODAL -->
    <div id="applicationModal" class="application-modal">
        <div class="application-content">
            <div class="application-header">
                <h3><i class="fas fa-hand-holding-usd"></i> Apply for <span id="selectedBank">Personal Loan</span></h3>
                <button class="application-close" onclick="closeApplicationForm()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="application-body">
                <form id="applicationForm" onsubmit="submitApplication(event)">
                    
                    <!-- Loan Details Section -->
                    <div style="background: #f8f9fc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">
                            <i class="fas fa-hand-holding-usd"></i> Loan Details
                        </div>

                        <div class="form-row" style="margin-bottom: 10px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-rupee-sign"></i> Loan Amount <span class="required">*</span></label>
                                <input type="number" class="form-input" id="appLoanAmount" placeholder="Enter loan amount" required>
                                <div class="form-error" id="loanAmountError">Please enter loan amount</div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-calendar-alt"></i> Loan Tenure <span class="required">*</span></label>
                                <select class="form-select" id="appLoanTenure" required>
                                    <option value="">Select Tenure</option>
                                    <option value="12">12 months</option>
                                    <option value="24">24 months</option>
                                    <option value="36">36 months</option>
                                    <option value="48">48 months</option>
                                    <option value="60">60 months</option>
                                </select>
                                <div class="form-error" id="loanTenureError">Please select loan tenure</div>
                            </div>
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

                    <!-- Identification Details Section -->
                    <div style="background: #f8f9fc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">
                            <i class="fas fa-id-card"></i> Identification Details
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

                    <!-- Employment & Income Details Section -->
                    <div style="background: #f8f9fc; border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                        <div style="font-size: 13px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">
                            <i class="fas fa-briefcase"></i> Employment Details
                        </div>

                        <div class="form-group" style="margin-bottom: 10px;">
                            <label><i class="fas fa-building"></i> Company / Organization Name <span class="required">*</span></label>
                            <input type="text" class="form-input" id="appCompany" placeholder="Enter company name" required>
                            <div class="form-error" id="companyError">Please enter company name</div>
                        </div>

                        <div class="form-row" style="margin-bottom: 0;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-briefcase"></i> Work Experience (Years) <span class="required">*</span></label>
                                <input type="number" class="form-input" id="appExperience" placeholder="Years of experience" required>
                                <div class="form-error" id="experienceError">Please enter work experience</div>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label><i class="fas fa-credit-card"></i> Current EMI (₹) <span class="required">*</span></label>
                                <input type="number" class="form-input" id="appCurrentEmi" placeholder="Enter current EMI" required>
                                <div class="form-error" id="currentEmiError">Please enter current EMI</div>
                            </div>
                        </div>
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

    <!-- SUCCESS MODAL -->
   <!-- SUCCESS MODAL -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <div class="modal-header" style="justify-content: flex-end; border-bottom: none; padding-bottom: 0;">
            <button class="modal-close" onclick="closeModal('successModal')">✕</button>
        </div>
        <div style="text-align: center;">
            <div class="success-check">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="success-title">Application Successful!</div>
            <div class="success-sub">Your loan application has been submitted successfully.</div>
            
            <div class="success-details-box">
                <div class="success-detail-row">
                    <span class="s-label">Bank</span>
                    <span class="s-value" id="successBank">Ram Fincorp</span>
                </div>
                <div class="success-detail-row">
                    <span class="s-label">Loan Amount</span>
                    <span class="s-value" id="successAmount">₹ 5 Lakhs</span>
                </div>
                <div class="success-detail-row">
                    <span class="s-label">Application ID</span>
                    <span class="s-value" id="successAppId">#12345</span>
                </div>
                <div class="success-detail-row">
                    <span class="s-label">Status</span>
                    <span class="s-value" style="color: var(--warning);">Pending</span>
                </div>
            </div>
            
            <button class="apply-now-btn" onclick="closeModal('successModal')" style="margin-top: 0; background: linear-gradient(135deg, var(--success), #00e676); box-shadow: 0 4px 15px rgba(0,200,83,0.3);">
                <i class="fas fa-check"></i> Done
            </button>
        </div>
    </div>
</div>

    <!-- TOAST -->
    <div id="toast" class="toast"></div>
</div>

<script>
    // ============================================================
    // APPLICATION FORM
    // ============================================================
    let selectedBank = '';
    let selectedAmount = '';

    function openApplicationForm(bank, amount) {
        selectedBank = bank || 'Personal Loan';
        selectedAmount = amount || '₹ 5 Lakhs';
        document.getElementById('selectedBank').textContent = selectedBank;
        
        document.querySelectorAll('.form-error').forEach(el => el.classList.remove('show'));
        document.querySelectorAll('.form-input, .form-select').forEach(el => el.classList.remove('error'));
        
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
        const loanAmount = document.getElementById('appLoanAmount').value.trim();
        const loanTenure = document.getElementById('appLoanTenure').value;
        const employment = document.getElementById('appEmployment').value;
        const income = document.getElementById('appIncome').value.trim();
        const dob = document.getElementById('appDob').value;
        const gender = document.getElementById('appGender').value;
        const aadhar = document.getElementById('appAadhar').value.trim();
        const pan = document.getElementById('appPan').value.trim().toUpperCase();
        const address = document.getElementById('appAddress').value.trim();
        const city = document.getElementById('appCity').value.trim();
        const pincode = document.getElementById('appPincode').value.trim();
        const company = document.getElementById('appCompany').value.trim();
        const experience = document.getElementById('appExperience').value.trim();
        const currentEmi = document.getElementById('appCurrentEmi').value.trim();

        let isValid = true;

        // Validation
        if (!loanAmount || parseInt(loanAmount) <= 0) { showError('appLoanAmount', 'loanAmountError'); isValid = false; } else { clearError('appLoanAmount', 'loanAmountError'); }
        if (!loanTenure) { showError('appLoanTenure', 'loanTenureError'); isValid = false; } else { clearError('appLoanTenure', 'loanTenureError'); }
        if (!employment) { showError('appEmployment', 'employmentError'); isValid = false; } else { clearError('appEmployment', 'employmentError'); }
        if (!income || parseInt(income) <= 0) { showError('appIncome', 'incomeError'); isValid = false; } else { clearError('appIncome', 'incomeError'); }
        if (!dob) { showError('appDob', 'dobError'); isValid = false; } else { clearError('appDob', 'dobError'); }
        if (!gender) { showError('appGender', 'genderError'); isValid = false; } else { clearError('appGender', 'genderError'); }
        
        const aadharRegex = /^[0-9]{12}$/;
        if (!aadharRegex.test(aadhar)) { showError('appAadhar', 'aadharError'); isValid = false; } else { clearError('appAadhar', 'aadharError'); }
        
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        if (!panRegex.test(pan)) { showError('appPan', 'panError'); isValid = false; } else { clearError('appPan', 'panError'); }
        
        if (address.length < 5) { showError('appAddress', 'addressError'); isValid = false; } else { clearError('appAddress', 'addressError'); }
        if (city.length < 2) { showError('appCity', 'cityError'); isValid = false; } else { clearError('appCity', 'cityError'); }
        
        const pincodeRegex = /^[0-9]{6}$/;
        if (!pincodeRegex.test(pincode)) { showError('appPincode', 'pincodeError'); isValid = false; } else { clearError('appPincode', 'pincodeError'); }
        
        if (company.length < 2) { showError('appCompany', 'companyError'); isValid = false; } else { clearError('appCompany', 'companyError'); }
        if (!experience || parseInt(experience) < 0) { showError('appExperience', 'experienceError'); isValid = false; } else { clearError('appExperience', 'experienceError'); }
        if (!currentEmi || parseInt(currentEmi) < 0) { showError('appCurrentEmi', 'currentEmiError'); isValid = false; } else { clearError('appCurrentEmi', 'currentEmiError'); }

        if (!isValid) {
            showToast('Please fill all required fields');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        // Prepare data
        const formData = new FormData();
        formData.append('bank_name', selectedBank);
        formData.append('loan_amount', loanAmount);
        formData.append('loan_tenure', loanTenure);
        formData.append('employment_type', employment);
        formData.append('monthly_income', income);
        formData.append('date_of_birth', dob);
        formData.append('gender', gender);
        formData.append('aadhar_number', aadhar);
        formData.append('pan_number', pan);
        formData.append('address', address);
        formData.append('city', city);
        formData.append('pincode', pincode);
        formData.append('company_name', company);
        formData.append('work_experience', experience);
        formData.append('current_emi', currentEmi);

        // Correct path for your structure
        const apiUrl = 'includes/submit_loan_application.php';
        
        console.log('Sending to:', apiUrl);

        // Send data
        fetch(apiUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
            
            if (data.success) {
                showSuccessModal(selectedBank, loanAmount, data.application_id);
                closeApplicationForm();
                showToast('Application submitted successfully!', true);
            } else {
                showToast(data.message || 'Failed to submit application');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
            showToast('Network error: ' + error.message);
        });
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

    function showSuccessModal(bank, amount, appId) {
        document.getElementById('successBank').textContent = bank || 'Personal Loan';
        document.getElementById('successAmount').textContent = '₹ ' + (amount || '50,000');
        document.getElementById('successModal').classList.add('active');
    }

    function showToast(msg, isSuccess = false) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast' + (isSuccess ? ' success' : '');
        t.classList.add('show');
        clearTimeout(t._timeout);
        t._timeout = setTimeout(() => t.classList.remove('show'), 3000);
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    function goBack() {
        window.location.href = 'user/dashboard.php';
    }

    // ... rest of your functions (openMoreDetails, toggleDetails, etc.) ...

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('appPincode').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });

        document.getElementById('appAadhar').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
        });

        document.getElementById('appPan').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });

        document.getElementById('applicationModal').addEventListener('click', function(e) {
            if (e.target === this) closeApplicationForm();
        });
    });

    // Expose functions globally
    Object.assign(window, {
        openApplicationForm, closeApplicationForm, submitApplication,
        openMoreDetails, closeMoreDetails, toggleDetails, handleApplyNow,
        showToast, closeModal, goBack, showSuccessModal
    });
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>