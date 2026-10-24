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
    <meta name="theme-color" content="#1a237e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Home Loan - Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        :root {
            --primary: #1a237e;
            --primary-light: #283593;
            --primary-dark: #0d1757;
            --orange: #ff9800;
            --success: #00c853;
            --danger: #ef4444;
            --text-primary: #1a1a2e;
            --text-secondary: #666666;
            --text-tertiary: #999999;
            --background: #f0f4ff;
            --white: #ffffff;
            --border: #e8edf5;
            --shadow: 0 2px 12px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.12);
            --radius: 16px;
        }

        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
            min-height: 100vh;
            padding-bottom: 20px;
        }

        /* ===== HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, #0d1757, #1a237e, #283593);
            padding: 16px 16px 28px;
            color: white;
            border-radius: 0 0 28px 28px;
            position: relative;
            overflow: hidden;
        }
        .page-header .back-btn {
            background: none;
            border: none;
            color: white;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 0;
            margin-bottom: 8px;
            font-family: 'Inter', sans-serif;
        }
        .page-header h1 {
            font-size: 22px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }
        .page-header p {
            font-size: 13px;
            opacity: 0.8;
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }
        .page-header .header-icon {
            position: absolute;
            right: 10px;
            bottom: 10px;
            font-size: 56px;
            opacity: 0.1;
        }

        /* ============================================================ */
        /* STEP 1: INCOME SELECTION */
        /* ============================================================ */
        .step-income { display: block; padding: 0 12px; }
        .step-income.hidden { display: none; }

        .progress-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 14px 16px;
            margin-top: -12px;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 2;
        }
        .progress-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .progress-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .progress-percent {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
        }
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #eef2f7;
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), #5c6bc0);
            border-radius: 10px;
            width: 75%;
            transition: width 0.5s ease;
        }

        .income-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 18px 16px;
            margin-top: 14px;
            box-shadow: var(--shadow);
        }
        .income-card .title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .income-card .subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 4px;
            margin-bottom: 16px;
        }
        .income-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .income-option {
            padding: 14px 10px;
            border: 2px solid var(--border);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: var(--white);
        }
        .income-option:active { transform: scale(0.96); }
        .income-option.active {
            border-color: var(--primary);
            background: #eef3ff;
            box-shadow: 0 0 0 3px rgba(26,35,126,0.08);
        }
        .income-option .range {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
        }
        .income-option .label {
            font-size: 10px;
            color: var(--text-tertiary);
            margin-top: 2px;
        }
        .income-option.active .range { color: var(--primary); }
        .income-option.active .label { color: var(--primary); }

        .get-offers-btn {
            width: 100%;
            padding: 14px;
            margin-top: 14px;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(26,35,126,0.25);
        }
        .get-offers-btn:active { transform: scale(0.97); }

        /* ============================================================ */
        /* STEP 2: OFFERS */
        /* ============================================================ */
        .step-offers { display: none; padding: 0 12px; }
        .step-offers.show { display: block; animation: fadeUp 0.4s ease; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .back-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
        }
        .back-row button {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: var(--shadow);
        }
        .back-row button:active { transform: scale(0.95); }

        .summary-bar {
            background: var(--white);
            border-radius: var(--radius);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow);
            margin-bottom: 12px;
        }
        .summary-bar .text {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }
        .summary-bar .text span { color: var(--primary); }
        .summary-bar .edit-btn {
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .filters-row {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 10px 0 14px;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .filters-row::-webkit-scrollbar { display: none; }
        .filter-chip {
            padding: 6px 14px;
            border: 1.5px solid var(--border);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            background: var(--white);
            color: var(--text-secondary);
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .filter-chip.active {
            border-color: var(--primary);
            background: #eef3ff;
            color: var(--primary);
        }
        .filter-chip .count {
            background: #eef2f7;
            padding: 1px 8px;
            border-radius: 12px;
            font-size: 10px;
            margin-left: 4px;
        }
        .filter-chip.active .count {
            background: var(--primary);
            color: white;
        }
        .filter-clear-btn {
            font-size: 12px;
            color: var(--danger);
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            white-space: nowrap;
        }

        .offers-header {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
        }
        .offers-header i { color: var(--primary); margin-right: 6px; }

        .offer-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 12px;
            transition: all 0.2s;
        }
        .offer-card:active { transform: scale(0.99); }

        .offer-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .bank-detail {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .bank-logo-sm {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }
        .bank-logo-sm.canara { background: linear-gradient(135deg, #1a5276, #2e86c1); }
        .bank-logo-sm.boi { background: linear-gradient(135deg, #1a237e, #283593); }
        .bank-logo-sm.sbi { background: linear-gradient(135deg, #1a5276, #2e86c1); }
        .bank-logo-sm.hdfc { background: linear-gradient(135deg, #004d7a, #006699); }
        .bank-logo-sm.icici { background: linear-gradient(135deg, #004080, #0066b3); }
        .bank-logo-sm.axis { background: linear-gradient(135deg, #800000, #cc0000); }
        .bank-logo-sm.kotak { background: linear-gradient(135deg, #2c3e50, #e67e22); }
        .bank-logo-sm.pnb { background: linear-gradient(135deg, #004d7a, #006699); }
        .bank-logo-sm.federal { background: linear-gradient(135deg, #2c3e50, #34495e); }
        .bank-logo-sm.indusind { background: linear-gradient(135deg, #1a237e, #283593); }
        .bank-logo-sm.yes { background: linear-gradient(135deg, #008080, #00a86b); }
        .bank-logo-sm.idfc { background: linear-gradient(135deg, #0066b3, #0099ff); }
        .bank-logo-sm.bob { background: linear-gradient(135deg, #1a237e, #283593); }

        .bank-name-sm { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .bank-sub-sm { font-size: 10px; color: var(--text-tertiary); }

        .offer-badge-sm {
            font-size: 9px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 12px;
            white-space: nowrap;
        }
        .offer-badge-sm.popular { background: #e8f5e9; color: #2e7d32; }
        .offer-badge-sm.lowest { background: #e3f2fd; color: #1565c0; }
        .offer-badge-sm.trusted { background: #fff3e0; color: #e65100; }
        .offer-badge-sm.quick { background: #fce4ec; color: #c62828; }
        .offer-badge-sm.highest { background: #f3e5f5; color: #7b1fa2; }

        .offer-stats {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 6px;
            margin-bottom: 10px;
            padding: 8px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .stat-item { text-align: center; }
        .stat-label {
            font-size: 9px;
            color: var(--text-tertiary);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .stat-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 1px;
        }

        .offer-features-sm {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .feature-sm {
            font-size: 10px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .feature-sm i { color: var(--primary); font-size: 12px; }

        .offer-actions-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .compare-label {
            font-size: 12px;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
        }
        .compare-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }
        .show-details-btn-sm {
            font-size: 12px;
            color: var(--primary);
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .apply-btn-sm {
            margin-left: auto;
            padding: 6px 18px;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .apply-btn-sm:active { transform: scale(0.95); }

        .eligibility-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 14px 16px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            margin-top: 4px;
        }
        .eligibility-card:active { transform: scale(0.98); }
        .elig-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #eef3ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 16px;
        }
        .elig-info { flex: 1; }
        .elig-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .elig-desc { font-size: 11px; color: var(--text-secondary); }

        /* ============================================================ */
        /* APPLICATION FORM MODAL - MOBILE OPTIMIZED */
        /* ============================================================ */
        .app-modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 3000;
            display: none;
            align-items: flex-end;
            justify-content: center;
        }
        .app-modal.active { display: flex; }
        .app-modal-content {
            background: var(--white);
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 480px;
            max-height: 92vh;
            overflow-y: auto;
            padding: 0;
            animation: slideUp 0.35s cubic-bezier(0.32, 0.72, 0, 1);
        }
        @keyframes slideUp {
            from { transform: translateY(60px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .app-modal-header {
            padding: 18px 20px 14px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: var(--white);
            z-index: 5;
            border-radius: 24px 24px 0 0;
        }
        .app-modal-header h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .app-modal-header h3 i { color: var(--orange); }
        .app-modal-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f0f2f5;
            border: none;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
        }
        .app-modal-close:active { background: #e0e2e5; }

        .app-modal-body {
            padding: 20px;
        }

        /* Form Group - Mobile Optimized */
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
        .form-group label i {
            margin-right: 6px;
            color: var(--primary);
            width: 16px;
        }
        .form-group label .required {
            color: var(--danger);
            margin-left: 2px;
        }
        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background: var(--white);
            color: var(--text-primary);
            -webkit-appearance: none;
            appearance: none;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,35,126,0.08);
        }
        .form-input.error {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(239,68,68,0.08);
        }
        .form-input::placeholder { color: var(--text-tertiary); }

        .form-error {
            font-size: 11px;
            color: var(--danger);
            margin-top: 4px;
            display: none;
        }
        .form-error.show { display: block; }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-select {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            background: var(--white);
            color: var(--text-primary);
            -webkit-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26,35,126,0.08);
        }

        /* Section Card */
        .section-card {
            background: #f8faff;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 18px;
            border: 1px solid var(--border);
        }
        .section-card .section-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-card .section-title i { font-size: 14px; }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(255,152,0,0.3);
            margin-top: 6px;
        }
        .submit-btn:active { transform: scale(0.97); }
        .submit-btn:disabled { opacity: 0.6; }

        .form-note {
            font-size: 11px;
            color: var(--text-tertiary);
            text-align: center;
            margin-top: 14px;
            padding: 10px;
            background: #f8faff;
            border-radius: 10px;
        }
        .form-note i { color: var(--success); margin-right: 4px; }

        /* ============================================================ */
        /* DETAILS OVERLAY */
        /* ============================================================ */
        .details-overlay {
            position: fixed;
            inset: 0;
            background: var(--background);
            z-index: 500;
            display: none;
            flex-direction: column;
            animation: fadeUp 0.3s ease;
            overflow-y: auto;
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
        }
        .details-overlay.active { display: flex; }

        .details-header {
            background: var(--white);
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 5;
            box-shadow: var(--shadow);
        }
        .details-header .back-details {
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'Inter', sans-serif;
        }
        .details-header .back-details i { font-size: 18px; }
        .details-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            flex: 1;
        }

        .details-body { padding: 16px 18px 20px; flex: 1; }

        .loan-highlights {
            background: var(--white);
            border-radius: var(--radius);
            padding: 12px 16px;
            border: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 14px;
        }
        .highlight-item {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .highlight-item i { color: var(--primary); }

        .bank-detail-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 14px 16px;
            border: 1px solid var(--border);
            margin-bottom: 14px;
        }
        .bank-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
        }
        .bank-detail-row:last-child { border-bottom: none; }
        .bank-detail-row .label { font-size: 13px; color: var(--text-secondary); }
        .bank-detail-row .value { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .bank-detail-row .value.emi { color: var(--orange); }

        .detail-tabs {
            display: flex;
            gap: 0;
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            margin-bottom: 14px;
        }
        .detail-tab {
            flex: 1;
            padding: 10px 6px;
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            cursor: pointer;
            background: var(--white);
            border: none;
            font-family: 'Inter', sans-serif;
            border-bottom: 3px solid transparent;
        }
        .detail-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: #f8faff;
        }
        .detail-tab:active { transform: scale(0.95); }

        .tab-content {
            display: none;
            background: var(--white);
            border-radius: var(--radius);
            padding: 14px 16px;
            border: 1px solid var(--border);
            margin-bottom: 14px;
        }
        .tab-content.active { display: block; animation: fadeUp 0.25s ease; }

        .charges-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
        }
        .charges-item:last-child { border-bottom: none; }
        .charges-item .label { color: var(--text-secondary); }
        .charges-item .value { font-weight: 700; color: var(--text-primary); }
        .charges-item .value .sub { font-size: 10px; font-weight: 400; color: var(--text-tertiary); display: block; }

        .process-list {
            list-style: none;
            padding: 0;
        }
        .process-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.4;
        }
        .process-list li:last-child { border-bottom: none; }
        .process-list li i { color: var(--primary); margin-top: 2px; min-width: 18px; }
        .process-list li .step-num { font-weight: 700; color: var(--primary); margin-right: 2px; }

        .doc-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }
        .doc-item {
            background: #f8faff;
            border-radius: 10px;
            padding: 8px 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-secondary);
        }
        .doc-item i { color: var(--primary); font-size: 14px; }

        .details-apply-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 4px;
        }
        .details-apply-btn:active { transform: scale(0.97); }

        /* ===== TOAST ===== */
        .toast {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: #1a1a2e;
            color: white;
            padding: 10px 20px;
            border-radius: 24px;
            font-size: 13px;
            z-index: 9999;
            transition: transform 0.3s;
            max-width: 90%;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }
        .toast.show { transform: translateX(-50%) translateY(0); }
        .toast.success { background: var(--success); }

        /* ===== SUCCESS MODAL ===== */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: 0.2s;
            padding: 20px;
        }
        .modal.active { visibility: visible; opacity: 1; }
        .modal-content {
            background: var(--white);
            border-radius: 24px;
            padding: 24px 20px 20px;
            width: 100%;
            max-width: 380px;
            animation: modalPop 0.3s ease;
        }
        @keyframes modalPop {
            from { transform: scale(0.92); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 4px;
        }
        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f0f2f5;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .success-check {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #e8f5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
        .success-check i { font-size: 28px; color: var(--success); }
        .success-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--success);
            text-align: center;
        }
        .success-sub {
            font-size: 13px;
            color: var(--text-secondary);
            text-align: center;
            margin-top: 4px;
            margin-bottom: 14px;
        }
        .success-details-box {
            background: #f8faff;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 14px;
            border: 1px solid var(--border);
        }
        .success-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            font-size: 13px;
        }
        .success-detail-row:not(:last-child) {
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .success-detail-row .s-label { color: var(--text-secondary); }
        .success-detail-row .s-value { font-weight: 600; color: var(--text-primary); }

        .apply-btn-modal {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--success), #00e676);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .apply-btn-modal:active { transform: scale(0.97); }

        .footer-note {
            text-align: center;
            font-size: 10px;
            color: var(--text-tertiary);
            padding: 14px;
            border-top: 1px solid var(--border);
            margin-top: 16px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 380px) {
            .page-header h1 { font-size: 19px; }
            .income-options { grid-template-columns: 1fr 1fr; gap: 6px; }
            .income-option { padding: 10px 6px; }
            .income-option .range { font-size: 12px; }
            .offer-stats { grid-template-columns: 1fr 1fr 1fr; gap: 4px; }
            .stat-value { font-size: 14px; }
            .offer-actions-row { flex-wrap: wrap; }
            .apply-btn-sm { margin-left: 0; width: 100%; }
            .form-row { grid-template-columns: 1fr; }
            .doc-grid { grid-template-columns: 1fr; }
            .app-modal-body { padding: 16px; }
            .section-card { padding: 12px; }
        }

        @media (max-width: 480px) {
            .page-header { padding: 14px 14px 24px; }
            .page-header h1 { font-size: 20px; }
            .progress-card { padding: 12px 14px; }
            .income-card { padding: 14px; }
            .income-options { gap: 8px; }
            .offer-card { padding: 14px; }
            .app-modal-content { border-radius: 20px 20px 0 0; }
            .app-modal-header { padding: 14px 16px 12px; }
            .app-modal-body { padding: 16px; }
            .section-card { padding: 14px; }
            .details-header { padding: 12px 14px; }
            .details-body { padding: 12px 14px; }
            .loan-highlights { padding: 10px 12px; }
            .bank-detail-card { padding: 12px 14px; }
            .tab-content { padding: 12px 14px; }
            .doc-grid { gap: 4px; }
            .doc-item { padding: 6px 8px; font-size: 11px; }
            .modal-content { padding: 20px 16px 16px; }
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --background: #121212;
                --white: #1e1e1e;
                --border: #2a2a2a;
                --text-primary: #e8e8e8;
                --text-secondary: #a0a0a0;
                --text-tertiary: #666666;
                --shadow: 0 2px 12px rgba(0,0,0,0.3);
            }
            .page-header { background: linear-gradient(135deg, #0a0a3a, #1a237e, #283593); }
            .income-option { background: var(--white); }
            .income-option.active { background: #1a1a3e; }
            .filter-chip { background: var(--white); }
            .filter-chip.active { background: #1a1a3e; }
            .offer-card { background: var(--white); }
            .bank-detail-card { background: var(--white); }
            .tab-content { background: var(--white); }
            .section-card { background: #1a1a2a; }
            .success-details-box { background: #1a1a2a; }
            .modal-content { background: #1e1e1e; }
            .app-modal-content { background: #1e1e1e; }
            .app-modal-header { background: #1e1e1e; }
            .details-header { background: #1e1e1e; }
            .loan-highlights { background: var(--white); }
            .detail-tab { background: var(--white); }
            .detail-tab.active { background: #1a1a2a; }
            .summary-bar { background: var(--white); }
            .form-input { background: #2a2a2a; color: #e8e8e8; border-color: #3a3a3a; }
            .form-select { background: #2a2a2a; color: #e8e8e8; border-color: #3a3a3a; }
            .form-note { background: #1a1a2a; }
            .eligibility-card { background: var(--white); }
            .elig-icon { background: #1a1a3e; }
            .back-row button { background: var(--white); }
            .app-modal-close { background: #2a2a2a; color: var(--text-secondary); }
            .modal-close { background: #2a2a2a; color: var(--text-secondary); }
            .progress-card { background: var(--white); }
            .income-card { background: var(--white); }
            .doc-item { background: #1a1a2a; }
            .filter-clear-btn { color: #ef5350; }
            .toast { background: #333333; }
        }
    </style>
</head>
<body>

<div class="app-container">

    <!-- ===== HEADER ===== -->
    <div class="page-header">
        <button class="back-btn" onclick="history.back()">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <h1>🏠 Home Loan</h1>
        <p>Find the best home loan offers from top banks</p>
        <div class="header-icon"><i class="fas fa-home"></i></div>
    </div>

    <!-- ============================================================ -->
    <!-- STEP 1: INCOME SELECTION -->
    <!-- ============================================================ -->
    <div class="step-income" id="stepIncome">
        
        <div class="progress-card">
            <div class="progress-top">
                <span class="progress-label"><i class="fas fa-check-circle" style="color:var(--success);"></i> 75% Complete</span>
                <span class="progress-percent">75%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:75%;"></div>
            </div>
        </div>

        <div class="income-card">
            <div class="title">← What is your Annual Income?</div>
            <div class="subtitle">This helps us calculate the maximum loan you can get</div>
            <div class="income-options">
                <div class="income-option" onclick="selectIncome(this)">
                    <div class="range">Below ₹3L</div>
                    <div class="label">Entry Level</div>
                </div>
                <div class="income-option" onclick="selectIncome(this)">
                    <div class="range">₹3 - ₹6L</div>
                    <div class="label">Starter</div>
                </div>
                <div class="income-option active" onclick="selectIncome(this)">
                    <div class="range">₹6 - ₹12L</div>
                    <div class="label">Mid Level</div>
                </div>
                <div class="income-option" onclick="selectIncome(this)">
                    <div class="range">₹12 - ₹18L</div>
                    <div class="label">Senior</div>
                </div>
                <div class="income-option" onclick="selectIncome(this)" style="grid-column: span 2;">
                    <div class="range">Over ₹18 Lacs</div>
                    <div class="label">Executive</div>
                </div>
            </div>
            <button class="get-offers-btn" onclick="submitIncomeForm()">
                <i class="fas fa-search"></i> Get Offers
            </button>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- STEP 2: OFFERS -->
    <!-- ============================================================ -->
    <div class="step-offers" id="stepOffers">

        <div class="back-row">
            <button onclick="goBackToIncome()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </div>

        <div class="summary-bar">
            <div class="text">🏦 You Need <span>₹10L</span> • Tenure <span>20 yrs</span></div>
            <button class="edit-btn" onclick="showToast('Edit loan details')"><i class="fas fa-edit"></i> Edit</button>
        </div>

        <div class="filters-row">
            <button class="filter-chip active" onclick="filterOffers('all', this)">All Offers <span class="count">13</span></button>
            <button class="filter-chip" onclick="filterOffers('popular', this)">Popular <span class="count">4</span></button>
            <button class="filter-chip" onclick="filterOffers('lowest', this)">Lowest Rate <span class="count">4</span></button>
            <button class="filter-chip" onclick="filterOffers('highest', this)">Highest Amount <span class="count">2</span></button>
            <button class="filter-chip" onclick="filterOffers('quick', this)">Quick Approval <span class="count">3</span></button>
            <button class="filter-clear-btn" onclick="clearFilters()">Clear</button>
        </div>

        <div class="offers-header"><i class="fas fa-university"></i> 13 Loan Offers</div>

        <!-- Offer 1 - Canara Bank -->
        <div class="offer-card" data-category="popular">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm canara">C</div>
                    <div>
                        <div class="bank-name-sm">Canara Bank</div>
                        <div class="bank-sub-sm">Government Undertaking</div>
                    </div>
                </div>
                <span class="offer-badge-sm popular"><i class="fas fa-star"></i> Popular</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹10L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.20%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹7,873</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹10Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Door Step Service</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('Canara Bank', '₹10L', '₹7,873', '7.20%', '₹10 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('Canara Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 2 - Bank of India -->
        <div class="offer-card" data-category="lowest">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm boi">B</div>
                    <div>
                        <div class="bank-name-sm">Bank of India</div>
                        <div class="bank-sub-sm">Retaining your savings</div>
                    </div>
                </div>
                <span class="offer-badge-sm lowest"><i class="fas fa-tag"></i> Lowest Rate</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹10L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.10%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹7,813</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹10Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Zero Processing Fee</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('Bank of India', '₹10L', '₹7,813', '7.10%', '₹10 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('Bank of India')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 3 - SBI -->
        <div class="offer-card" data-category="popular">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm sbi">S</div>
                    <div>
                        <div class="bank-name-sm">State Bank of India</div>
                        <div class="bank-sub-sm">India's Largest Bank</div>
                    </div>
                </div>
                <span class="offer-badge-sm trusted"><i class="fas fa-crown"></i> Trusted</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹15L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.35%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹8,050</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹15Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Government Bank</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('State Bank of India', '₹15L', '₹8,050', '7.35%', '₹15 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('State Bank of India')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 4 - HDFC -->
        <div class="offer-card" data-category="quick">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm hdfc">H</div>
                    <div>
                        <div class="bank-name-sm">HDFC Bank</div>
                        <div class="bank-sub-sm">Home Loan Specialists</div>
                    </div>
                </div>
                <span class="offer-badge-sm quick"><i class="fas fa-bolt"></i> Quick</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹10L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.45%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹7,950</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹8Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> 24hr Approval</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('HDFC Bank', '₹10L', '₹7,950', '7.45%', '₹8 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('HDFC Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 5 - ICICI -->
        <div class="offer-card" data-category="highest">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm icici">I</div>
                    <div>
                        <div class="bank-name-sm">ICICI Bank</div>
                        <div class="bank-sub-sm">Leading Home Loan Provider</div>
                    </div>
                </div>
                <span class="offer-badge-sm highest"><i class="fas fa-arrow-up"></i> Highest</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹20L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.55%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹8,850</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹20Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Door Step Service</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('ICICI Bank', '₹20L', '₹8,850', '7.55%', '₹20 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('ICICI Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 6 - Axis Bank -->
        <div class="offer-card" data-category="popular">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm axis">A</div>
                    <div>
                        <div class="bank-name-sm">Axis Bank</div>
                        <div class="bank-sub-sm">Trusted Banking Partner</div>
                    </div>
                </div>
                <span class="offer-badge-sm popular"><i class="fas fa-star"></i> Popular</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹10L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.30%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹7,900</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹12Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Quick Processing</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('Axis Bank', '₹10L', '₹7,900', '7.30%', '₹12 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('Axis Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 7 - Kotak Mahindra -->
        <div class="offer-card" data-category="lowest">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm kotak">K</div>
                    <div>
                        <div class="bank-name-sm">Kotak Mahindra Bank</div>
                        <div class="bank-sub-sm">Premium Banking</div>
                    </div>
                </div>
                <span class="offer-badge-sm lowest"><i class="fas fa-tag"></i> Lowest Rate</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹10L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.05%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹7,780</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹15Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Zero Processing Fee</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('Kotak Mahindra Bank', '₹10L', '₹7,780', '7.05%', '₹15 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('Kotak Mahindra Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 8 - PNB -->
        <div class="offer-card" data-category="popular">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm pnb">P</div>
                    <div>
                        <div class="bank-name-sm">Punjab National Bank</div>
                        <div class="bank-sub-sm">Government Bank</div>
                    </div>
                </div>
                <span class="offer-badge-sm trusted"><i class="fas fa-crown"></i> Trusted</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹12L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.25%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹7,950</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹10Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Government Bank</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('Punjab National Bank', '₹12L', '₹7,950', '7.25%', '₹10 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('Punjab National Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 9 - Federal Bank -->
        <div class="offer-card" data-category="quick">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm federal">F</div>
                    <div>
                        <div class="bank-name-sm">Federal Bank</div>
                        <div class="bank-sub-sm">Your Perfect Banking Partner</div>
                    </div>
                </div>
                <span class="offer-badge-sm quick"><i class="fas fa-bolt"></i> Quick</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹8L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.40%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹6,800</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹8Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> 48hr Approval</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('Federal Bank', '₹8L', '₹6,800', '7.40%', '₹8 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('Federal Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 10 - IndusInd Bank -->
        <div class="offer-card" data-category="highest">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm indusind">I</div>
                    <div>
                        <div class="bank-name-sm">IndusInd Bank</div>
                        <div class="bank-sub-sm">Modern Banking</div>
                    </div>
                </div>
                <span class="offer-badge-sm highest"><i class="fas fa-arrow-up"></i> Highest</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹18L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.60%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹8,200</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹18Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Flexible Terms</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('IndusInd Bank', '₹18L', '₹8,200', '7.60%', '₹18 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('IndusInd Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 11 - Yes Bank -->
        <div class="offer-card" data-category="popular">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm yes">Y</div>
                    <div>
                        <div class="bank-name-sm">Yes Bank</div>
                        <div class="bank-sub-sm">Customer First</div>
                    </div>
                </div>
                <span class="offer-badge-sm popular"><i class="fas fa-star"></i> Popular</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹10L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.35%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹7,850</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹10Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Quick Processing</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('Yes Bank', '₹10L', '₹7,850', '7.35%', '₹10 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('Yes Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 12 - IDFC First Bank -->
        <div class="offer-card" data-category="lowest">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm idfc">I</div>
                    <div>
                        <div class="bank-name-sm">IDFC First Bank</div>
                        <div class="bank-sub-sm">First Choice Banking</div>
                    </div>
                </div>
                <span class="offer-badge-sm lowest"><i class="fas fa-tag"></i> Lowest Rate</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹10L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.00%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹7,750</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹12Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Zero Processing Fee</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('IDFC First Bank', '₹10L', '₹7,750', '7.00%', '₹12 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('IDFC First Bank')">Apply Now</button>
            </div>
        </div>

        <!-- Offer 13 - Bank of Baroda -->
        <div class="offer-card" data-category="popular">
            <div class="offer-top">
                <div class="bank-detail">
                    <div class="bank-logo-sm bob">B</div>
                    <div>
                        <div class="bank-name-sm">Bank of Baroda</div>
                        <div class="bank-sub-sm">India's International Bank</div>
                    </div>
                </div>
                <span class="offer-badge-sm trusted"><i class="fas fa-crown"></i> Trusted</span>
            </div>
            <div class="offer-stats">
                <div class="stat-item"><div class="stat-label">Amount</div><div class="stat-value">₹12L</div></div>
                <div class="stat-item"><div class="stat-label">ROI</div><div class="stat-value">7.15%</div></div>
                <div class="stat-item"><div class="stat-label">EMI</div><div class="stat-value">₹7,900</div></div>
            </div>
            <div class="offer-features-sm">
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Max Loan: ₹10Cr</span>
                <span class="feature-sm"><i class="fas fa-check-circle"></i> Government Bank</span>
            </div>
            <div class="offer-actions-row">
                <label class="compare-label">
                    <input type="checkbox" onchange="toggleCompare(this)"> Compare
                </label>
                <button class="show-details-btn-sm" onclick="openDetailsModal('Bank of Baroda', '₹12L', '₹7,900', '7.15%', '₹10 Crores')">
                    <i class="fas fa-chevron-down"></i> Details
                </button>
                <button class="apply-btn-sm" onclick="openApplicationForm('Bank of Baroda')">Apply Now</button>
            </div>
        </div>

        <!-- Eligibility Card -->
        <div class="eligibility-card" onclick="showToast('Add co-applicant feature coming soon')">
            <div class="elig-icon"><i class="fas fa-user-plus"></i></div>
            <div class="elig-info">
                <div class="elig-title">Add Co-Applicant</div>
                <div class="elig-desc">Tip: A female co-applicant reduces ROI by 0.05%</div>
            </div>
            <i class="fas fa-chevron-right" style="color:var(--text-tertiary);"></i>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- DETAILS OVERLAY -->
    <!-- ============================================================ -->
    <div class="details-overlay" id="detailsOverlay">
        <div class="details-header">
            <button class="back-details" onclick="closeDetailsModal()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <h3>Loan Details</h3>
        </div>
        <div class="details-body">

            <div class="loan-highlights">
                <span class="highlight-item"><i class="fas fa-money-bill-wave"></i> Max: <strong id="detailMaxLoan">₹10Cr</strong></span>
                <span class="highlight-item"><i class="fas fa-door-open"></i> Door Step Service</span>
            </div>

            <div class="bank-detail-card">
                <div class="bank-detail-row"><span class="label">Lender</span><span class="value" id="detailBankName">Bank of India</span></div>
                <div class="bank-detail-row"><span class="label">Max. Loan Amount</span><span class="value" id="detailLoanAmount">₹10Cr</span></div>
                <div class="bank-detail-row"><span class="label">ROI Starting From</span><span class="value" id="detailRoi">7.1%</span></div>
                <div class="bank-detail-row"><span class="label">EMI</span><span class="value emi" id="detailEmi">₹ 7,813</span></div>
            </div>

            <div class="detail-tabs" id="detailTabs">
                <button class="detail-tab active" onclick="switchTab('charges', this)">Charges</button>
                <button class="detail-tab" onclick="switchTab('process', this)">Process</button>
                <button class="detail-tab" onclick="switchTab('documents', this)">Documents</button>
            </div>

            <div class="tab-content active" id="tabCharges">
                <div class="charges-item"><span class="label">Processing Fee</span><span class="value">Up to ₹2,500 + GST</span></div>
                <div class="charges-item"><span class="label">Part Prepayment</span><span class="value">3–7%* <span class="sub">Varies as per duration</span></span></div>
                <div class="charges-item"><span class="label">Foreclosure</span><span class="value">3–7%* <span class="sub">Varies as per duration</span></span></div>
                <div class="charges-item"><span class="label">Late Payment</span><span class="value">2% p.m.</span></div>
            </div>

            <div class="tab-content" id="tabProcess">
                <ul class="process-list">
                    <li><i class="fas fa-check-circle"></i> <span class="step-num">1.</span> Eligibility Check</li>
                    <li><i class="fas fa-file-signature"></i> <span class="step-num">2.</span> Document Verification</li>
                    <li><i class="fas fa-home"></i> <span class="step-num">3.</span> In-Person Visit</li>
                    <li><i class="fas fa-handshake"></i> <span class="step-num">4.</span> Agreement Signing</li>
                    <li><i class="fas fa-rupee-sign"></i> <span class="step-num">5.</span> Final Disbursement</li>
                </ul>
            </div>

            <div class="tab-content" id="tabDocuments">
                <div class="doc-grid">
                    <div class="doc-item"><i class="fas fa-id-card"></i> Aadhar Card</div>
                    <div class="doc-item"><i class="fas fa-id-card"></i> PAN Card</div>
                    <div class="doc-item"><i class="fas fa-university"></i> Bank Statement</div>
                    <div class="doc-item"><i class="fas fa-file-invoice"></i> Salary Slips</div>
                    <div class="doc-item"><i class="fas fa-home"></i> Address Proof</div>
                    <div class="doc-item"><i class="fas fa-file-alt"></i> Form 16</div>
                    <div class="doc-item"><i class="fas fa-file-contract"></i> Property Papers</div>
                    <div class="doc-item"><i class="fas fa-camera"></i> Passport Photo</div>
                </div>
            </div>

            <button class="details-apply-btn" onclick="handleDetailsApply()">
                <i class="fas fa-hand-holding-usd"></i> Apply Now
            </button>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- APPLICATION FORM MODAL - NO DOCUMENT UPLOAD -->
    <!-- ============================================================ -->
    <div class="app-modal" id="applicationModal">
        <div class="app-modal-content">
            <div class="app-modal-header">
                <h3><i class="fas fa-home"></i> Apply for <span id="selectedBank">Home Loan</span></h3>
                <button class="app-modal-close" onclick="closeApplicationForm()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="app-modal-body">
                <form id="applicationForm" onsubmit="submitApplication(event)">
                    
                    <!-- Loan Details -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-hand-holding-usd"></i> Loan Details</div>
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-rupee-sign"></i> Loan Amount <span class="required">*</span></label>
                                <input type="number" class="form-input" id="appLoanAmount" placeholder="Enter amount" required>
                                <div class="form-error" id="loanAmountError">Please enter loan amount</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-calendar-alt"></i> Tenure <span class="required">*</span></label>
                                <select class="form-select" id="appLoanTenure" required>
                                    <option value="">Select</option>
                                    <option value="120">10 years</option>
                                    <option value="180">15 years</option>
                                    <option value="240">20 years</option>
                                    <option value="300">25 years</option>
                                    <option value="360">30 years</option>
                                </select>
                                <div class="form-error" id="loanTenureError">Please select tenure</div>
                            </div>
                        </div>
                        <div class="form-row" style="margin-top:12px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-briefcase"></i> Employment <span class="required">*</span></label>
                                <select class="form-select" id="appEmployment" required>
                                    <option value="">Select</option>
                                    <option value="Salaried">Salaried</option>
                                    <option value="Self-Employed">Self-Employed</option>
                                    <option value="Business">Business</option>
                                    <option value="Retired">Retired</option>
                                    <option value="Others">Others</option>
                                </select>
                                <div class="form-error" id="employmentError">Please select employment</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-rupee-sign"></i> Monthly Income <span class="required">*</span></label>
                                <input type="number" class="form-input" id="appIncome" placeholder="Enter income" required>
                                <div class="form-error" id="incomeError">Please enter income</div>
                            </div>
                        </div>
                    </div>

                    <!-- Identification -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-id-card"></i> Identification</div>
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-calendar-alt"></i> DOB <span class="required">*</span></label>
                                <input type="date" class="form-input" id="appDob" required>
                                <div class="form-error" id="dobError">Please select DOB</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-venus-mars"></i> Gender <span class="required">*</span></label>
                                <select class="form-select" id="appGender" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div class="form-error" id="genderError">Please select gender</div>
                            </div>
                        </div>
                        <div class="form-row" style="margin-top:12px;">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-id-card"></i> Aadhar <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appAadhar" placeholder="12-digit Aadhar" maxlength="12" required>
                                <div class="form-error" id="aadharError">Enter valid 12-digit Aadhar</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-id-card"></i> PAN <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appPan" placeholder="ABCDE1234F" maxlength="10" required>
                                <div class="form-error" id="panError">Enter valid PAN</div>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-map-marker-alt"></i> Address</div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label><i class="fas fa-map-marker-alt"></i> Current Address <span class="required">*</span></label>
                            <input type="text" class="form-input" id="appAddress" placeholder="Enter your address" required>
                            <div class="form-error" id="addressError">Please enter address</div>
                        </div>
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-city"></i> City <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appCity" placeholder="City" required>
                                <div class="form-error" id="cityError">Please enter city</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-map-pin"></i> PIN <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appPincode" placeholder="6-digit PIN" maxlength="6" required>
                                <div class="form-error" id="pincodeError">Enter valid 6-digit PIN</div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Details -->
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-home"></i> Property Details</div>
                        <div class="form-group" style="margin-bottom:10px;">
                            <label><i class="fas fa-map-marker-alt"></i> Property Address <span class="required">*</span></label>
                            <input type="text" class="form-input" id="appPropertyAddress" placeholder="Enter property address" required>
                            <div class="form-error" id="propertyAddressError">Please enter property address</div>
                        </div>
                        <div class="form-row">
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-city"></i> City <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appPropertyCity" placeholder="City" required>
                                <div class="form-error" id="propertyCityError">Please enter city</div>
                            </div>
                            <div class="form-group" style="margin-bottom:0;">
                                <label><i class="fas fa-map-pin"></i> PIN <span class="required">*</span></label>
                                <input type="text" class="form-input" id="appPropertyPincode" placeholder="6-digit PIN" maxlength="6" required>
                                <div class="form-error" id="propertyPincodeError">Enter valid 6-digit PIN</div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>

                    <div class="form-note">
                        <i class="fas fa-lock"></i> Your information is secure
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SUCCESS MODAL -->
    <!-- ============================================================ -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <button class="modal-close" onclick="closeModal('successModal')">✕</button>
            </div>
            <div style="text-align:center;">
                <div class="success-check"><i class="fas fa-check-circle"></i></div>
                <div class="success-title">Application Submitted!</div>
                <div class="success-sub">Your home loan application has been submitted successfully.</div>
                <div class="success-details-box">
                    <div class="success-detail-row"><span class="s-label">Bank</span><span class="s-value" id="successBank">Bank of India</span></div>
                    <div class="success-detail-row"><span class="s-label">Loan Amount</span><span class="s-value" id="successAmount">₹ 10 Lakhs</span></div>
                    <div class="success-detail-row"><span class="s-label">Application ID</span><span class="s-value" id="successAppId">#12345</span></div>
                    <div class="success-detail-row"><span class="s-label">Status</span><span class="s-value" style="color: var(--orange);">Pending</span></div>
                </div>
                <button class="apply-btn-modal" onclick="closeModal('successModal')">
                    <i class="fas fa-check"></i> Done
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div id="toast" class="toast"></div>

    <div class="footer-note"><i class="fas fa-lock"></i> Secure & Trusted | RBI Compliant</div>
</div>

<script>
    // ============================================================
    // STEP 1: INCOME SELECTION
    // ============================================================
    function selectIncome(element) {
        document.querySelectorAll('.income-option').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    function submitIncomeForm() {
        const active = document.querySelector('.income-option.active');
        const income = active ? active.querySelector('.range').textContent : 'Not selected';
        document.getElementById('stepIncome').classList.add('hidden');
        document.getElementById('stepOffers').classList.add('show');
        showToast('Showing offers for: ' + income);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function goBackToIncome() {
        document.getElementById('stepOffers').classList.remove('show');
        document.getElementById('stepIncome').classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ============================================================
    // FILTERS
    // ============================================================
    function filterOffers(category, btn) {
        document.querySelectorAll('.filter-chip').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.offer-card').forEach(card => {
            if (category === 'all') {
                card.style.display = 'block';
            } else {
                card.style.display = card.dataset.category === category ? 'block' : 'none';
            }
        });
    }

    function clearFilters() {
        document.querySelectorAll('.filter-chip').forEach(el => el.classList.remove('active'));
        document.querySelector('.filter-chip:first-child').classList.add('active');
        document.querySelectorAll('.offer-card').forEach(card => card.style.display = 'block');
    }

    function toggleCompare(checkbox) {
        showToast(checkbox.checked ? 'Added to compare' : 'Removed from compare');
    }

    // ============================================================
    // DETAILS OVERLAY
    // ============================================================
    function openDetailsModal(bank, amount, emi, roi, maxLoan) {
        document.getElementById('detailBankName').textContent = bank;
        document.getElementById('detailLoanAmount').textContent = maxLoan || '₹10Cr';
        document.getElementById('detailRoi').textContent = roi || '7.1%';
        document.getElementById('detailEmi').textContent = emi || '₹7,813';
        document.getElementById('detailMaxLoan').textContent = maxLoan || '₹10Cr';
        
        document.getElementById('detailsOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailsModal() {
        document.getElementById('detailsOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    function switchTab(tab, btn) {
        document.querySelectorAll('.detail-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add('active');
    }

    function handleDetailsApply() {
        const bank = document.getElementById('detailBankName').textContent;
        closeDetailsModal();
        setTimeout(() => openApplicationForm(bank), 300);
    }

    // ============================================================
    // APPLICATION FORM - WITH DATABASE SUBMISSION
    // ============================================================
    let selectedBank = '';

    function openApplicationForm(bank) {
        selectedBank = bank || 'Home Loan';
        document.getElementById('selectedBank').textContent = selectedBank;
        
        // Reset form
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
        const propertyAddress = document.getElementById('appPropertyAddress').value.trim();
        const propertyCity = document.getElementById('appPropertyCity').value.trim();
        const propertyPincode = document.getElementById('appPropertyPincode').value.trim();

        let isValid = true;

        // Validate Loan Details
        if (!loanAmount || parseInt(loanAmount) <= 0) { showError('appLoanAmount', 'loanAmountError'); isValid = false; } else { clearError('appLoanAmount', 'loanAmountError'); }
        if (!loanTenure) { showError('appLoanTenure', 'loanTenureError'); isValid = false; } else { clearError('appLoanTenure', 'loanTenureError'); }
        if (!employment) { showError('appEmployment', 'employmentError'); isValid = false; } else { clearError('appEmployment', 'employmentError'); }
        if (!income || parseInt(income) <= 0) { showError('appIncome', 'incomeError'); isValid = false; } else { clearError('appIncome', 'incomeError'); }

        // Validate Identification
        if (!dob) { showError('appDob', 'dobError'); isValid = false; } else { clearError('appDob', 'dobError'); }
        if (!gender) { showError('appGender', 'genderError'); isValid = false; } else { clearError('appGender', 'genderError'); }
        
        const aadharRegex = /^[0-9]{12}$/;
        if (!aadharRegex.test(aadhar)) { showError('appAadhar', 'aadharError'); isValid = false; } else { clearError('appAadhar', 'aadharError'); }
        
        const panRegex = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        if (!panRegex.test(pan)) { showError('appPan', 'panError'); isValid = false; } else { clearError('appPan', 'panError'); }

        // Validate Address
        if (address.length < 5) { showError('appAddress', 'addressError'); isValid = false; } else { clearError('appAddress', 'addressError'); }
        if (city.length < 2) { showError('appCity', 'cityError'); isValid = false; } else { clearError('appCity', 'cityError'); }
        
        const pincodeRegex = /^[0-9]{6}$/;
        if (!pincodeRegex.test(pincode)) { showError('appPincode', 'pincodeError'); isValid = false; } else { clearError('appPincode', 'pincodeError'); }

        // Validate Property Details
        if (propertyAddress.length < 5) { showError('appPropertyAddress', 'propertyAddressError'); isValid = false; } else { clearError('appPropertyAddress', 'propertyAddressError'); }
        if (propertyCity.length < 2) { showError('appPropertyCity', 'propertyCityError'); isValid = false; } else { clearError('appPropertyCity', 'propertyCityError'); }
        if (!pincodeRegex.test(propertyPincode)) { showError('appPropertyPincode', 'propertyPincodeError'); isValid = false; } else { clearError('appPropertyPincode', 'propertyPincodeError'); }

        if (!isValid) {
            showToast('Please fill all required fields');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        // Prepare data for AJAX
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
        formData.append('property_address', propertyAddress);
        formData.append('property_city', propertyCity);
        formData.append('property_pincode', propertyPincode);

        // Send data to server
        fetch('submit_home_loan.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data);
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

    // ============================================================
    // SUCCESS MODAL
    // ============================================================
    function showSuccessModal(bank, amount, appId) {
        document.getElementById('successBank').textContent = bank || 'Home Loan';
        document.getElementById('successAmount').textContent = '₹ ' + (amount || '50,000');
        document.getElementById('successAppId').textContent = '#' + (appId || '12345');
        document.getElementById('successModal').classList.add('active');
    }

    // ============================================================
    // TOAST & HELPERS
    // ============================================================
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

    // Close modals on backdrop click
    document.querySelectorAll('.modal, .app-modal').forEach(m => {
        m.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // Close details overlay with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('detailsOverlay').classList.contains('active')) {
            closeDetailsModal();
        }
    });

    // Real-time input validation
    document.addEventListener('DOMContentLoaded', function() {
        // PIN codes - only numbers
        ['appPincode', 'appPropertyPincode'].forEach(id => {
            document.getElementById(id).addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
                if (this.value.length === 6) {
                    const errId = id === 'appPincode' ? 'pincodeError' : 'propertyPincodeError';
                    clearError(id, errId);
                }
            });
        });

        // Aadhar - only numbers
        document.getElementById('appAadhar').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
            if (this.value.length === 12) clearError('appAadhar', 'aadharError');
        });

        // PAN - auto uppercase
        document.getElementById('appPan').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            if (this.value.length === 10 && /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(this.value)) {
                clearError('appPan', 'panError');
            }
        });

        // Close modal on backdrop click
        document.getElementById('applicationModal').addEventListener('click', function(e) {
            if (e.target === this) closeApplicationForm();
        });
    });

    // ============================================================
    // EXPOSE GLOBALLY
    // ============================================================
    Object.assign(window, {
        selectIncome, submitIncomeForm, goBackToIncome,
        filterOffers, clearFilters, toggleCompare,
        openDetailsModal, closeDetailsModal, switchTab, handleDetailsApply,
        openApplicationForm, closeApplicationForm, submitApplication,
        showSuccessModal, showToast, closeModal
    });
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>