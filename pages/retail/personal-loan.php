<?php
// FILE: personal-loan.php
include('../../includes/dbConfig.php');
include('../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Personal, Two-Wheeler, Commercial & Gold Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #071B3E;
            --navy-mid: #0D2D5F;
            --navy-light: #163A78;
            --orange: #F97316;
            --orange-dark: #D95E06;
            --orange-light: #FFB57A;
            --gold: #E8C55A;
            --cream: #FDF8F2;
            --white: #FFFFFF;
            --text-muted: #8C9DB8;
            --text-body: #3D5070;
            --border: #E3EAF4;
            --card-bg: #FFFFFF;
            --section-bg: #F7FAFF;
            --shadow-sm: 0 2px 12px rgba(7, 27, 62, 0.06);
            --shadow-md: 0 8px 32px rgba(7, 27, 62, 0.10);
            --shadow-lg: 0 24px 64px rgba(7, 27, 62, 0.13);
            --shadow-xl: 0 40px 80px rgba(7, 27, 62, 0.18);
            --radius-sm: 12px;
            --radius-md: 20px;
            --radius-lg: 32px;
            --radius-xl: 48px;
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--text-body);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── NOISE TEXTURE OVERLAY ─── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.4;
        }

        /* ════════════════════════════════════════
           PREMIUM MNC-STYLE HERO SECTION
        ════════════════════════════════════════ */
        .hero-premium {
            min-height: 100vh;
            background: linear-gradient(135deg, #071B3E 0%, #0A2540 50%, #0D2D5F 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 110px 6% 80px;
            margin-top: -5px;
        }

        /* Animated Gradient Orbs */
        .hero-orb-1 {
            position: absolute;
            top: -150px;
            right: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.25) 0%, transparent 70%);
            border-radius: 50%;
            animation: orbFloat 12s ease-in-out infinite;
        }

        .hero-orb-2 {
            position: absolute;
            bottom: -100px;
            left: -150px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(232, 197, 90, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: orbFloat 15s ease-in-out infinite reverse;
        }

        .hero-orb-3 {
            position: absolute;
            top: 40%;
            left: 20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(22, 58, 120, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            animation: orbFloat 18s ease-in-out infinite;
        }

        @keyframes orbFloat {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -30px) scale(1.05);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.98);
            }
        }

        /* Grid Pattern Overlay */
        .hero-grid-overlay {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

   
        .hero-content {
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 540px;
            gap: 80px;
            align-items: center;
            position: relative;
            z-index: 4;
        }

        /* Hero Left */
        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(249, 115, 22, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(249, 115, 22, 0.25);
            color: var(--orange-light);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 8px 20px;
            border-radius: 100px;
            margin-bottom: 32px;
        }

        .hero-eyebrow .pulse-dot {
            width: 8px;
            height: 8px;
            background: #10B981;
            border-radius: 50%;
            animation: pulse-green 2s ease-in-out infinite;
        }

        @keyframes pulse-green {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }

            50% {
                opacity: 0.6;
                transform: scale(0.9);
                box-shadow: 0 0 0 4px rgba(16, 185, 129, 0);
            }
        }

        .hero-left h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(42px, 5vw, 68px);
            font-weight: 900;
            line-height: 1.1;
            color: var(--white);
            margin-bottom: 28px;
            letter-spacing: -0.02em;
        }

        .hero-left h1 .gradient-text {
            background: linear-gradient(135deg, var(--gold) 0%, var(--orange) 60%, var(--orange-light) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
        }

        .hero-left p {
            font-size: 17px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 520px;
            font-weight: 400;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            margin-bottom: 56px;
            flex-wrap: wrap;
        }

        .btn-primary-hero {
            background: linear-gradient(135deg, var(--orange) 0%, var(--orange-dark) 100%);
            color: white;
            padding: 16px 40px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.02em;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(249, 115, 22, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-primary-hero:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(249, 115, 22, 0.45);
        }

        .btn-outline-hero {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 16px 36px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline-hero:hover {
            border-color: var(--orange);
            background: rgba(249, 115, 22, 0.1);
            transform: translateY(-2px);
        }

        /* Trust Stats */
        .trust-stats {
            display: flex;
            align-items: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .stat-block {
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex-wrap: wrap;
        }

        .stat-number {
            font-family: 'Playfair Display', serif;
            font-size: 34px;
            font-weight: 800;
            color: var(--white);
            line-height: 1;
        }

        .stat-number span {
            color: var(--orange);
            font-size: 28px;
        }

        .stat-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 400;
            letter-spacing: 0.03em;
        }

        .stat-divider {
            width: 1px;
            height: 45px;
            background: rgba(255, 255, 255, 0.15);
        }

        /* ════════════════════════════════════════
           PREMIUM MNC-STYLE FORM CARD
        ════════════════════════════════════════ */
        .hero-right {
            position: relative;
        }

        .floating-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(2px);
            border-radius: 32px;
            padding: 36px;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.2);
            position: relative;
            transition: all 0.4s ease;
        }

        .floating-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--navy), var(--orange), var(--gold), var(--orange), var(--navy));
            border-radius: 32px 32px 0 0;
        }

        /* Card Header */
        .card-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .card-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            padding: 6px 16px;
            border-radius: 100px;
            margin-bottom: 16px;
        }

        .card-badge i {
            color: var(--orange);
            font-size: 12px;
        }

        .card-badge span {
            font-size: 12px;
            font-weight: 600;
            color: var(--orange-dark);
            letter-spacing: 0.05em;
        }

        .card-header h3 {
            font-family: 'Playfair Display', serif;
            color: var(--navy);
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .card-header p {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Premium Step Indicator */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            padding: 0 8px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
            position: relative;
        }

        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #E2E8F0;
            z-index: 0;
        }

        .step-item.active:not(:last-child)::after {
            background: linear-gradient(90deg, var(--orange), var(--navy));
        }

        .step-number {
            width: 38px;
            height: 38px;
            background: #F1F5F9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-muted);
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .step-item.active .step-number {
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        }

        .step-item.completed .step-number {
            background: #10B981;
            color: white;
        }

        .step-item.completed .step-number i {
            font-size: 12px;
        }

        .step-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .step-item.active .step-label {
            color: var(--orange);
        }

        /* Form Styles */
        .loan-form {
            display: flex;
            flex-direction: column;
        }

        .form-step {
            display: none;
            animation: slideUp 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }

        .form-step.active {
            display: block;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            font-size: 15px;
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
            color: var(--navy);
            transition: all 0.25s ease;
            background: #FAFBFD;
        }

        .form-group select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238C9DB8' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 18px center;
            padding-right: 45px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1);
            background: var(--white);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ─── CARD SELECTION STYLES (Employment Type) ─── */
        .card-select-group {
            margin-bottom: 20px;
        }

        .card-select-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .card-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .card-option {
            background: #FAFBFD;
            border: 1.5px solid var(--border);
            border-radius: 20px;
            padding: 16px 12px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .card-option:hover {
            border-color: var(--orange);
            background: #FFF9F5;
            transform: translateY(-2px);
        }

        .card-option.selected {
            border-color: var(--orange);
            background: linear-gradient(135deg, #FFF9F5, #FFF5ED);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.15);
        }

        .card-option-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-option-icon i {
            font-size: 24px;
            color: var(--orange);
        }

        .card-option-content {
            flex: 1;
        }

        .card-option-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 4px;
        }

        .card-option-desc {
            font-size: 11px;
            color: var(--text-muted);
        }

        .card-option-radio {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .card-option.selected .card-option-radio {
            border-color: var(--orange);
        }

        .card-option.selected .card-option-radio::after {
            content: '';
            width: 10px;
            height: 10px;
            background: var(--orange);
            border-radius: 50%;
        }

        /* Hidden radio input */
        .employment-radio {
            display: none;
        }

        .btn-next, .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            color: white;
            padding: 16px;
            border: none;
            border-radius: 60px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 8px;
        }

        .btn-next:hover, .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(7, 27, 62, 0.25);
            background: linear-gradient(135deg, var(--navy-light) 0%, var(--navy) 100%);
        }

        .btn-back {
            flex: 1;
            background: #F1F5F9;
            border: none;
            border-radius: 60px;
            padding: 14px;
            font-weight: 600;
            font-size: 14px;
            color: var(--navy);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: #E2E8F0;
        }

        .multi-buttons {
            display: flex;
            gap: 14px;
            margin-top: 8px;
        }

        .success-message {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            padding: 16px;
            border-radius: 16px;
            text-align: center;
            margin-top: 20px;
            display: none;
            font-size: 14px;
            font-weight: 500;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }

        .form-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 11px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        /* Floating animation */
        @keyframes float-card {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .floating-card {
            animation: float-card 5s ease-in-out infinite;
        }

        /* ════════════════════════════════════════
           MAIN CONTENT STYLES
        ════════════════════════════════════════ */
        .main-content {
            max-width: 1360px;
            margin: 0 auto;
            padding: 100px 6% 80px;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 14px;
        }

        .section-tag::before {
            content: '';
            width: 24px;
            height: 2px;
            background: var(--orange);
            border-radius: 2px;
        }

        .loan-section {
            margin-bottom: 100px;
            scroll-margin-top: 100px;
        }

        .loan-header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 36px;
        }

        .loan-icon-wrap {
            flex-shrink: 0;
            width: 68px;
            height: 68px;
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 28px rgba(7, 27, 62, 0.20);
        }

        .loan-icon-wrap i {
            font-size: 26px;
            color: var(--white);
        }

        .loan-header-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(26px, 3vw, 40px);
            font-weight: 800;
            color: var(--navy);
            line-height: 1.18;
            letter-spacing: -0.02em;
        }

        .loan-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: 52px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .loan-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
        }

        .loan-description {
            font-size: 15.5px;
            line-height: 1.80;
            color: var(--text-body);
            margin-bottom: 20px;
            font-weight: 300;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }

        .feature-item {
            background: var(--section-bg);
            padding: 26px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            transition: border-color 0.25s;
        }

        .feature-item:hover {
            border-color: rgba(249, 115, 22, 0.25);
        }

        .feature-item h4 {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature-item h4 i {
            color: var(--orange);
            font-size: 15px;
        }

        .feature-item ul {
            list-style: none;
        }

        .feature-item li {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13.5px;
            color: var(--text-body);
            line-height: 1.5;
        }

        .feature-item li i {
            color: var(--orange);
            font-size: 11px;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .info-badge {
            background: linear-gradient(135deg, #F7FAFF 0%, #F0F4FD 100%);
            border: 1px solid var(--border);
            padding: 28px 32px;
            border-radius: var(--radius-md);
            margin: 32px 0;
            position: relative;
            overflow: hidden;
        }

        .info-badge::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, var(--orange), var(--gold));
            border-radius: 2px 0 0 2px;
        }

        .apply-btn-card {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--navy);
            color: white;
            padding: 14px 32px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            margin-top: 12px;
        }

        .apply-btn-card:hover {
            background: var(--orange);
            transform: translateX(6px) translateY(-2px);
            box-shadow: 0 12px 28px rgba(249, 115, 22, 0.28);
        }

        .section-divider {
            display: flex;
            align-items: center;
            gap: 24px;
            margin: 0 0 80px;
        }

        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }

        .divider-diamond {
            width: 8px;
            height: 8px;
            background: var(--orange);
            transform: rotate(45deg);
            border-radius: 1px;
        }

        .cta-section {
            background: var(--navy);
            border-radius: var(--radius-xl);
            padding: 80px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -150px;
            right: -150px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .cta-inner {
            position: relative;
            z-index: 2;
        }

        .cta-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(249, 115, 22, 0.15);
            border: 1px solid rgba(249, 115, 22, 0.25);
            color: var(--orange-light);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 50px;
            margin-bottom: 24px;
        }

        .cta-section h3 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 3.5vw, 46px);
            color: var(--white);
            margin-bottom: 18px;
            font-weight: 900;
        }

        .cta-section p {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.55);
            margin-bottom: 40px;
        }

        .cta-button {
            background: var(--white);
            color: var(--navy);
            padding: 18px 48px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .cta-button:hover {
            transform: scale(1.05) translateY(-2px);
            background: var(--cream);
        }

        .reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-left {
            animation: fadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .hero-right {
            animation: fadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.18s both;
        }

        /* Responsive */
        @media (max-width: 1100px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 50px;
            }
            .hero-left p {
                max-width: 100%;
            }
            .floating-card {
                max-width: 560px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .hero-premium {
                padding: 100px 5% 70px;
            }
            .hero-left h1 {
                font-size: 38px;
            }
            .trust-stats {
                gap: 20px;
            }
            .stat-number {
                font-size: 26px;
            }
            .floating-card {
                padding: 28px 22px;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .step-label {
                display: none;
            }
            .card-options {
                grid-template-columns: 1fr;
            }
            .loan-card {
                padding: 28px 22px;
            }
            .features-grid {
                grid-template-columns: 1fr;
            }
            .loan-header {
                flex-direction: column;
                gap: 14px;
            }
            .cta-section {
                padding: 50px 26px;
            }
        }

        @media (max-width: 480px) {
            .hero-left h1 {
                font-size: 32px;
            }
            .hero-buttons {
                flex-direction: column;
            }
            .btn-primary-hero, .btn-outline-hero {
                justify-content: center;
            }
            .trust-stats {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
            }
            .stat-divider {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- ═══════════ PREMIUM MNC-STYLE HERO SECTION ═══════════ -->
    <div class="hero-premium">
        <div class="hero-orb-1"></div>
        <div class="hero-orb-2"></div>
        <div class="hero-orb-3"></div>
        <div class="hero-grid-overlay"></div>
     

        <div class="hero-content" style="margin-top:-80px";>

            <!-- LEFT CONTENT -->
            <div class="hero-left">
                <div class="hero-eyebrow">
                    <span class="pulse-dot"></span>
                    Trusted by 50,000+ Customers
                </div>

                <h1>
                    Smart Financial<br>
                    Support with<br>
                    <span class="gradient-text">Personal Loans</span>
                    
                </h1>

                <p>Unexpected expenses? Medical, wedding, business, or vehicle — LOGINTOLOANS provides fast, flexible, and uncomplicated loan solutions with competitive rates &amp; digital ease.</p>

                <div class="hero-buttons">
                    <a href="#personal" class="btn-primary-hero">
                        <span>Explore Loan Options</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#apply-form" class="btn-outline-hero">
                        <i class="fas fa-bolt"></i>
                        <span>Apply in 2 Minutes</span>
                    </a>
                </div>

                <div class="trust-stats">
                    <div class="stat-block">
                        <div class="stat-number">50<span>k+</span></div>
                        <div class="stat-label">Happy Borrowers</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-block">
                        <div class="stat-number">24<span>/7</span></div>
                        <div class="stat-label">Customer Support</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-block">
                        <div class="stat-number">100<span>%</span></div>
                        <div class="stat-label">Digital Process</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: PREMIUM FORM CARD -->
            <div class="hero-right" id="apply-form">
                <div class="floating-card">
                    <div class="card-header">
                        <div class="card-badge">
                            <i class="fas fa-bolt"></i>
                            <span>Instant Approval</span>
                        </div>
                        <h3>Get Instant Loan</h3>
                        <p>Up to ₹50 Lakhs • 10.99% onwards</p>
                    </div>

                    <form class="loan-form" id="instantLoanForm" onsubmit="handleFormSubmit(event)">

                        <!-- PREMIUM STEP INDICATOR -->
                        <div class="step-indicator">
                            <div class="step-item active" id="step1Indicator">
                                <div class="step-number">1</div>
                                <div class="step-label">Identity</div>
                            </div>
                            <div class="step-item" id="step2Indicator">
                                <div class="step-number">2</div>
                                <div class="step-label">Contact</div>
                            </div>
                            <div class="step-item" id="step3Indicator">
                                <div class="step-number">3</div>
                                <div class="step-label">Financial</div>
                            </div>
                        </div>

                        <!-- STEP 1 -->
                        <div class="form-step active" id="step1">
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Full Name</label>
                                <input type="text" id="fullName" placeholder="Enter your full name" required>
                            </div>

                            <button type="button" class="btn-next" onclick="nextStep()">
                                <span>Continue</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>

                        <!-- STEP 2 -->
                        <div class="form-step" id="step2">
                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-mobile-alt"></i> Mobile Number</label>
                                    <input type="tel" id="mobile" placeholder="10-digit number" required>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-envelope"></i> Email Address</label>
                                    <input type="email" id="email" placeholder="you@email.com" required>
                                </div>
                            </div>

                            <div class="multi-buttons">
                                <button type="button" class="btn-back" onclick="prevStep()">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Back</span>
                                </button>
                                <button type="button" class="btn-next" onclick="nextStep()">
                                    <span>Continue</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- STEP 3 - FINANCIAL with CARD SELECTION -->
                        <div class="form-step" id="step3">
                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-tag"></i> Loan Type</label>
                                    <select id="loanType" required>
                                        <option value="">Select Loan Type</option>
                                        <option value="personal">Personal Loan</option>
                                        <option value="two-wheeler">Two-Wheeler Loan</option>
                                        <option value="commercial">Commercial Vehicle Loan</option>
                                        <option value="gold">Gold Loan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- CARD-BASED EMPLOYMENT TYPE SELECTION -->
                            <div class="card-select-group">
                                <label><i class="fas fa-briefcase"></i> Employment Type</label>
                                <div class="card-options">
                                    <div class="card-option" data-value="salaried" onclick="selectEmployment('salaried')">
                                        <div class="card-option-icon">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="card-option-content">
                                            <div class="card-option-title">Salaried Professional</div>
                                            <div class="card-option-desc">Working with a company or organization</div>
                                        </div>
                                        <div class="card-option-radio"></div>
                                    </div>
                                    <div class="card-option" data-value="self-employed" onclick="selectEmployment('self-employed')">
                                        <div class="card-option-icon">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="card-option-content">
                                            <div class="card-option-title">Self-Employed</div>
                                            <div class="card-option-desc">Business owner, freelancer, or professional</div>
                                        </div>
                                        <div class="card-option-radio"></div>
                                    </div>
                                </div>
                                <input type="hidden" id="employmentType" name="employmentType" value="">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-rupee-sign"></i> Loan Amount</label>
                                    <select id="loanAmount" required>
                                        <option value="">Select Loan Amount</option>
                                        <option value="50000">₹50,000</option>
                                        <option value="100000">₹1,00,000</option>
                                        <option value="250000">₹2,50,000</option>
                                        <option value="500000">₹5,00,000</option>
                                        <option value="1000000">₹10,00,000</option>
                                        <option value="2500000">₹25,00,000</option>
                                        <option value="5000000">₹50,00,000+</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-chart-line"></i> Monthly Income</label>
                                    <input type="number" id="monthlyIncome" placeholder="₹ per month" required>
                                </div>
                            </div>

                            <div class="multi-buttons">
                                <button type="button" class="btn-back" onclick="prevStep()">
                                    <i class="fas fa-arrow-left"></i>
                                    <span>Back</span>
                                </button>
                                <button type="submit" class="btn-submit">
                                    <i class="fas fa-bolt"></i>
                                    <span>Get Instant Offer</span>
                                </button>
                            </div>
                        </div>

                        <div id="formSuccess" class="success-message">
                            <i class="fas fa-check-circle"></i>
                            Thank you! Our loan expert will contact you shortly.
                        </div>

                        <div class="form-footer">
                            <i class="fas fa-shield-alt"></i>
                            Your information is secure & encrypted
                            <i class="fas fa-lock"></i>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <!-- ═══════════ END HERO ═══════════ -->


    <!-- ═══════════ MAIN CONTENT ═══════════ -->
    <div class="main-content">

        <!-- ── PERSONAL LOAN ── -->
        <section id="personal" class="loan-section reveal">
            <div class="loan-header">
                <div class="loan-icon-wrap">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="loan-header-text">
                    <div class="section-tag">Personal Loan</div>
                    <h2>Smart Financial Support<br>from LOGINTOLOANS</h2>
                </div>
            </div>

            <div class="loan-card">
                <p class="loan-description">Unexpected expenses can arise at any point in life, whether it is for medical emergencies, higher education, wedding arrangements, home renovation, travel plans, or other personal commitments. A personal loan from LOGINTOLOANS can help you manage these financial needs conveniently and without stress.</p>
                <p class="loan-description">At LOGINTOLOANS, we provide quick and flexible personal loan solutions designed to match your financial goals. Our unsecured loans come with competitive interest rates, simple eligibility requirements, and flexible repayment options that make borrowing easier and more convenient.</p>

                <div class="features-grid">
                    <div class="feature-item">
                        <h4><i class="fas fa-briefcase"></i> For Salaried &amp; Self-Employed</h4>
                        <ul>
                            <li><i class="fas fa-check"></i> Salaried: travel, education, wedding, gadgets, home improvement</li>
                            <li><i class="fas fa-check"></i> Self-employed: working capital, business expansion, equipment</li>
                            <li><i class="fas fa-check"></i> Balance transfer options to lower EMI burden</li>
                        </ul>
                    </div>
                    <div class="feature-item">
                        <h4><i class="fas fa-star"></i> Why Choose LOGINTOLOANS?</h4>
                        <ul>
                            <li><i class="fas fa-check"></i> Loan amounts matching your needs</li>
                            <li><i class="fas fa-check"></i> Competitive &amp; custom interest rates</li>
                            <li><i class="fas fa-check"></i> Flexible repayment tenures (EMI plans)</li>
                            <li><i class="fas fa-check"></i> Minimal paperwork + digital approval</li>
                            <li><i class="fas fa-check"></i> No collateral required</li>
                            <li><i class="fas fa-check"></i> Fast approval &amp; instant disbursal</li>
                        </ul>
                    </div>
                </div>

                <div class="info-badge">
                    <p><strong>📋 Eligibility Criteria:</strong> Salaried individuals with stable income | Self-employed with consistent business income | Satisfactory credit profile</p>
                    <p><strong>📄 Documents Required:</strong> Identity proof | Address proof | PAN card | Income proof/salary slips | Bank statements | Passport-sized photographs</p>
                </div>

                <a href="#apply-form" class="apply-btn-card" onclick="document.getElementById('apply-form').scrollIntoView({behavior: 'smooth'}); return false;">
                    <span>Apply for Personal Loan</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </section>

        <div class="section-divider reveal">
            <div class="divider-diamond"></div>
        </div>

        <!-- ── TWO-WHEELER LOAN ── -->
        <section id="two-wheeler" class="loan-section reveal reveal-delay-1">
            <div class="loan-header">
                <div class="loan-icon-wrap">
                    <i class="fas fa-motorcycle"></i>
                </div>
                <div class="loan-header-text">
                    <div class="section-tag">Two-Wheeler Loan</div>
                    <h2>Ride Your Dream Vehicle<br>with LOGINTOLOANS</h2>
                </div>
            </div>

            <div class="loan-card">
                <p class="loan-description">Owning a bike or scooter is no longer just a convenience — it has become an essential part of daily life. With a two-wheeler loan from LOGINTOLOANS, you can bring home your preferred vehicle without putting pressure on your savings.</p>

                <div class="features-grid">
                    <div class="feature-item">
                        <h4><i class="fas fa-check-circle"></i> Why Choose Us?</h4>
                        <ul>
                            <li><i class="fas fa-check"></i> High loan coverage (up to 90% on-road price)</li>
                            <li><i class="fas fa-check"></i> Affordable EMI options</li>
                            <li><i class="fas fa-check"></i> Quick loan approval &amp; disbursal</li>
                            <li><i class="fas fa-check"></i> Minimal documentation</li>
                        </ul>
                    </div>
                    <div class="feature-item">
                        <h4><i class="fas fa-id-card"></i> Eligibility &amp; Documents</h4>
                        <ul>
                            <li><i class="fas fa-check"></i> Salaried: stable income, recognized organization</li>
                            <li><i class="fas fa-check"></i> Self-employed: consistent business income</li>
                            <li><i class="fas fa-check"></i> Documents: Aadhaar, PAN, address proof, income proof</li>
                        </ul>
                    </div>
                </div>

                <a href="#apply-form" class="apply-btn-card" onclick="document.getElementById('apply-form').scrollIntoView({behavior: 'smooth'}); return false;">
                    <span>Apply for Two-Wheeler Loan</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </section>

        <div class="section-divider reveal">
            <div class="divider-diamond"></div>
        </div>

        <!-- ── COMMERCIAL VEHICLE LOAN ── -->
        <section id="commercial" class="loan-section reveal reveal-delay-2">
            <div class="loan-header">
                <div class="loan-icon-wrap">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="loan-header-text">
                    <div class="section-tag">Commercial Vehicle Loan</div>
                    <h2>Drive Business Growth<br>with LOGINTOLOANS</h2>
                </div>
            </div>

            <div class="loan-card">
                <p class="loan-description">Commercial vehicles play a major role in transportation, logistics, and construction businesses. With a commercial vehicle loan from LOGINTOLOANS, businesses and individuals can finance new or used commercial vehicles with ease and flexibility.</p>

                <div class="features-grid">
                    <div class="feature-item">
                        <h4><i class="fas fa-check-circle"></i> Why Choose Us?</h4>
                        <ul>
                            <li><i class="fas fa-check"></i> Financing for new &amp; used vehicles</li>
                            <li><i class="fas fa-check"></i> Flexible repayment tenure</li>
                            <li><i class="fas fa-check"></i> Competitive interest rates</li>
                            <li><i class="fas fa-check"></i> Quick loan processing</li>
                        </ul>
                    </div>
                    <div class="feature-item">
                        <h4><i class="fas fa-building"></i> Who Can Apply?</h4>
                        <ul>
                            <li><i class="fas fa-check"></i> Individual operators &amp; fleet owners</li>
                            <li><i class="fas fa-check"></i> Transport businesses &amp; partnership firms</li>
                            <li><i class="fas fa-check"></i> Contractors, logistics providers</li>
                        </ul>
                    </div>
                </div>

                <a href="#apply-form" class="apply-btn-card" onclick="document.getElementById('apply-form').scrollIntoView({behavior: 'smooth'}); return false;">
                    <span>Apply for Commercial Vehicle Loan</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </section>

        <div class="section-divider reveal">
            <div class="divider-diamond"></div>
        </div>

        <!-- ── GOLD LOAN ── -->
        <section id="gold" class="loan-section reveal reveal-delay-3">
            <div class="loan-header">
                <div class="loan-icon-wrap">
                    <i class="fas fa-gem"></i>
                </div>
                <div class="loan-header-text">
                    <div class="section-tag">Gold Loan</div>
                    <h2>Unlock Instant Funds<br>with LOGINTOLOANS</h2>
                </div>
            </div>

            <div class="loan-card">
                <p class="loan-description">Financial emergencies can arise without warning. Instead of selling your valuable gold assets, pledge them to access quick financial support through a gold loan from LOGINTOLOANS — fast, secure, and convenient.</p>

                <div class="features-grid">
                    <div class="feature-item">
                        <h4><i class="fas fa-check-circle"></i> Why Choose Us?</h4>
                        <ul>
                            <li><i class="fas fa-check"></i> High loan value against gold</li>
                            <li><i class="fas fa-check"></i> Competitive interest rates</li>
                            <li><i class="fas fa-check"></i> Fast processing &amp; quick disbursal</li>
                            <li><i class="fas fa-check"></i> Safe &amp; secure storage</li>
                        </ul>
                    </div>
                    <div class="feature-item">
                        <h4><i class="fas fa-id-card"></i> Eligibility</h4>
                        <ul>
                            <li><i class="fas fa-check"></i> Salaried, self-employed, business owners</li>
                            <li><i class="fas fa-check"></i> Ownership of eligible gold jewellery</li>
                            <li><i class="fas fa-check"></i> Valid KYC documents</li>
                        </ul>
                    </div>
                </div>

                <a href="#apply-form" class="apply-btn-card" onclick="document.getElementById('apply-form').scrollIntoView({behavior: 'smooth'}); return false;">
                    <span>Apply for Gold Loan</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </section>

        <!-- ── CTA ── -->
        <div class="cta-section reveal">
            <div class="cta-inner">
                <div class="cta-eyebrow">Start Today</div>
                <h3>Ready to Achieve Your<br>Financial Goals?</h3>
                <p>Get instant approval and funds disbursed directly to your bank account</p>
                <a href="#apply-form" class="cta-button" onclick="document.getElementById('apply-form').scrollIntoView({behavior: 'smooth'}); return false;">
                    <i class="fas fa-bolt"></i>
                    <span>Start Your Application</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>
    <!-- ═══════════ END MAIN CONTENT ═══════════ -->

    <?php include('../../includes/footer.php'); ?>

    <script>
        /* ── CARD SELECTION FOR EMPLOYMENT TYPE ── */
        let selectedEmployment = '';

        function selectEmployment(value) {
            selectedEmployment = value;
            document.getElementById('employmentType').value = value;
            
            // Update UI
            const options = document.querySelectorAll('.card-option');
            options.forEach(option => {
                option.classList.remove('selected');
                if (option.getAttribute('data-value') === value) {
                    option.classList.add('selected');
                }
            });
        }

        /* ── FORM STEPS LOGIC ── */
        let currentStep = 0;
        const steps = ['step1', 'step2', 'step3'];
        const stepIndicators = ['step1Indicator', 'step2Indicator', 'step3Indicator'];

        function updateStepIndicator() {
            stepIndicators.forEach((id, index) => {
                const element = document.getElementById(id);
                if (index === currentStep) {
                    element.classList.add('active');
                    element.classList.remove('completed');
                } else if (index < currentStep) {
                    element.classList.add('completed');
                    element.classList.remove('active');
                    const stepNum = element.querySelector('.step-number');
                    if (stepNum && index < currentStep) {
                        stepNum.innerHTML = '<i class="fas fa-check"></i>';
                    }
                } else {
                    element.classList.remove('active', 'completed');
                    const stepNum = element.querySelector('.step-number');
                    if (stepNum) {
                        stepNum.innerHTML = (index + 1).toString();
                    }
                }
            });
        }

        function showStep(stepIndex) {
            steps.forEach((step, index) => {
                const stepElement = document.getElementById(step);
                if (stepElement) {
                    stepElement.classList.toggle('active', index === stepIndex);
                }
            });
            currentStep = stepIndex;
            updateStepIndicator();
        }

        function nextStep() {
            if (currentStep < steps.length - 1) {
                // Validate current step
                if (currentStep === 0) {
                    const fullName = document.getElementById('fullName').value;
                    if (!fullName) {
                        alert('Please enter your full name');
                        return;
                    }
                }
                if (currentStep === 1) {
                    const mobile = document.getElementById('mobile').value;
                    const email = document.getElementById('email').value;
                    if (!mobile || mobile.length < 10) {
                        alert('Please enter a valid 10-digit mobile number');
                        return;
                    }
                    if (!email || !email.includes('@')) {
                        alert('Please enter a valid email address');
                        return;
                    }
                }
                if (currentStep === 2) {
                    // Validate employment type
                    if (!selectedEmployment) {
                        alert('Please select your employment type');
                        return;
                    }
                }
                showStep(currentStep + 1);
            }
        }

        function prevStep() {
            if (currentStep > 0) {
                showStep(currentStep - 1);
            }
        }

        /* ── FORM SUBMIT ── */
        function handleFormSubmit(event) {
            event.preventDefault();

            const fullName = document.getElementById('fullName').value;
            const mobile = document.getElementById('mobile').value;
            const email = document.getElementById('email').value;
            const loanType = document.getElementById('loanType').value;
            const employmentType = document.getElementById('employmentType').value;
            const loanAmount = document.getElementById('loanAmount').value;
            const monthlyIncome = document.getElementById('monthlyIncome').value;

            if (!fullName || !mobile || !email || !loanType || !employmentType || !loanAmount || !monthlyIncome) {
                alert('Please fill in all fields');
                return;
            }

            if (mobile.length < 10) {
                alert('Please enter a valid 10-digit mobile number');
                return;
            }

            const successDiv = document.getElementById('formSuccess');
            successDiv.style.display = 'flex';

            console.log('Loan Application Submitted:', {
                fullName, mobile, email, loanType, employmentType, loanAmount, monthlyIncome
            });

            // Reset form and go to step 1
            document.getElementById('instantLoanForm').reset();
            selectedEmployment = '';
            document.getElementById('employmentType').value = '';
            document.querySelectorAll('.card-option').forEach(opt => opt.classList.remove('selected'));
            showStep(0);

            setTimeout(() => {
                successDiv.style.display = 'none';
            }, 5000);
        }

        /* ── SMOOTH SCROLL ── */
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        /* ── SCROLL REVEAL ── */
        const revealEls = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.10 });
        revealEls.forEach(el => observer.observe(el));
    </script>
</body>

</html>