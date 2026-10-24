<?php
// FILE: overdraft-facility.php
include('../../includes/dbConfig.php');
include('../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Overdraft Facility – Flexible Financial Support for Professionals</title>
    <meta name="description" content="Get flexible overdraft facility for professionals. Access funds anytime, pay interest only on utilized amount. Quick approval for doctors, CAs, lawyers, architects & more.">
    <meta name="keywords" content="overdraft facility for professionals, professional overdraft, CA overdraft, doctor overdraft, architect overdraft, working capital overdraft, LOGINTOLOANS">
    <meta name="author" content="LOGINTOLOANS">
    <link rel="canonical" href="https://www.logintoloans.com/overdraft-facility.php">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700;1,800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:         #060F22;
            --navy-mid:     #0A1E40;
            --navy-light:   #122F66;
            --orange:       #F97316;
            --orange-dark:  #D95E06;
            --orange-light: #FFB57A;
            --gold:         #E8C55A;
            --cream:        #FDF8F2;
            --white:        #FFFFFF;
            --text-muted:   #8C9DB8;
            --text-body:    #3D5070;
            --border:       #E3EAF4;
            --section-bg:   #F7FAFF;
            --shadow-sm:  0 2px 12px rgba(6,15,34,0.07);
            --shadow-md:  0 8px 32px rgba(6,15,34,0.11);
            --shadow-lg:  0 24px 64px rgba(6,15,34,0.14);
            --shadow-xl:  0 40px 80px rgba(6,15,34,0.20);
            --radius-sm:  12px;
            --radius-md:  20px;
            --radius-lg:  32px;
            --radius-xl:  48px;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--text-body);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* noise overlay */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 9999; opacity: 0.4;
        }

        /* ════════════════════════════════════════
           HERO WITH VIDEO BACKGROUND - PREMIUM
        ════════════════════════════════════════ */
        .hero-premium {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 110px 6% 110px;
            margin-top: -5px;
            background: linear-gradient(135deg, #060F22 0%, #0A1E40 50%, #122F66 100%);
        }

        /* Animated Orbs */
        .hero-orb-1 {
            position: absolute;
            top: -150px; right: -100px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(249,115,22,0.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: orbFloat 12s ease-in-out infinite;
        }
        .hero-orb-2 {
            position: absolute;
            bottom: -100px; left: -150px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(232,197,90,0.12) 0%, transparent 70%);
            border-radius: 50%;
            animation: orbFloat 15s ease-in-out infinite reverse;
        }
        @keyframes orbFloat { 0%,100% { transform: translate(0,0) scale(1); } 33% { transform: translate(30px,-30px) scale(1.05); } 66% { transform: translate(-20px,20px) scale(0.98); } }

        /* Video BG */
        .hero-video-wrap {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .hero-video-wrap video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        /* Multi-layer overlay */
        .hero-overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            background:
                linear-gradient(
                    105deg,
                    rgba(6,15,34,0.92)  0%,
                    rgba(6,15,34,0.80) 45%,
                    rgba(6,15,34,0.50) 75%,
                    rgba(6,15,34,0.35) 100%
                );
        }
        .hero-overlay::after {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 60% at 75% 50%, rgba(249,115,22,0.1) 0%, transparent 70%);
        }

        /* Grid texture */
        .hero-grid {
            position: absolute; inset: 0; z-index: 2; pointer-events: none;
            background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 56px 56px;
        }

        /* Speed lines */
        .speed-lines {
            position: absolute; inset: 0; z-index: 2; pointer-events: none; overflow: hidden;
        }
        .speed-lines::before {
            content: '';
            position: absolute;
            top: 30%; left: -10%;
            width: 120%; height: 2px;
            background: linear-gradient(90deg, transparent, rgba(249,115,22,0.25), transparent);
            transform: rotate(-6deg);
            animation: speed-sweep 4s ease-in-out infinite;
        }
        .speed-lines::after {
            content: '';
            position: absolute;
            top: 55%; left: -10%;
            width: 120%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(232,197,90,0.15), transparent);
            transform: rotate(-6deg);
            animation: speed-sweep 4s ease-in-out 1.5s infinite;
        }
        @keyframes speed-sweep {
            0%   { transform: rotate(-6deg) translateX(-60%); opacity: 0; }
            40%  { opacity: 1; }
            100% { transform: rotate(-6deg) translateX(60%); opacity: 0; }
        }

        /* White arc */
        .hero-arc {
            position: absolute; bottom: -2px; left: 0; right: 0;
            height: 130px;
            background: var(--white);
            clip-path: ellipse(55% 100% at 50% 100%);
            z-index: 3;
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
            background: rgba(249,115,22,0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(249,115,22,0.25);
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
            width: 8px; height: 8px;
            background: #10B981;
            border-radius: 50%;
            animation: pulse-green 2s ease-in-out infinite;
        }
        @keyframes pulse-green { 0%,100% { opacity:1; transform:scale(1); box-shadow:0 0 0 0 rgba(16,185,129,0.7); } 50% { opacity:0.6; transform:scale(0.9); box-shadow:0 0 0 4px rgba(16,185,129,0); } }

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
        }
        .hero-left p {
            font-size: 17px;
            color: rgba(255,255,255,0.7);
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 520px;
            font-weight: 400;
        }

        .hero-buttons { display: flex; gap: 20px; margin-bottom: 56px; flex-wrap: wrap; }
        .btn-primary-hero {
            background: linear-gradient(135deg, var(--orange) 0%, var(--orange-dark) 100%);
            color: white;
            padding: 16px 40px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(249,115,22,0.35);
            transition: all 0.3s ease;
        }
        .btn-primary-hero:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(249,115,22,0.45); }
        .btn-outline-hero {
            background: transparent;
            border: 1.5px solid rgba(255,255,255,0.3);
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
        .btn-outline-hero:hover { border-color: var(--orange); background: rgba(249,115,22,0.1); transform: translateY(-2px); }

        .trust-row { display: flex; align-items: center; flex-wrap: wrap; gap: 30px; }
        .stat-item { text-align: left; }
        .stat-number { font-family: 'Playfair Display', serif; font-size: 34px; font-weight: 800; color: var(--white); line-height: 1; }
        .stat-number span { color: var(--orange); font-size: 28px; }
        .stat-label { font-size: 13px; color: rgba(255,255,255,0.5); font-weight: 400; letter-spacing: 0.03em; margin-top: 4px; }

        /* ════════════════════════════════════════
           PREMIUM FLOATING FORM CARD
        ════════════════════════════════════════ */
        .hero-right { position: relative; }
        .floating-card {
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(2px);
            border-radius: 32px;
            padding: 36px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.2);
            transition: all 0.4s ease;
            animation: float-card 5s ease-in-out infinite;
        }
        @keyframes float-card { 0%,100% { transform: translateY(0px); } 50% { transform: translateY(-6px); } }
        .floating-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--navy), var(--orange), var(--gold), var(--orange), var(--navy));
            border-radius: 32px 32px 0 0;
        }

        .card-header { text-align: center; margin-bottom: 28px; }
        .card-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            padding: 6px 16px;
            border-radius: 100px;
            margin-bottom: 16px;
        }
        .card-badge i { color: var(--orange); font-size: 12px; }
        .card-badge span { font-size: 12px; font-weight: 600; color: var(--orange-dark); letter-spacing: 0.05em; }
        .card-header h3 { font-family: 'Playfair Display', serif; color: var(--navy); font-size: 26px; font-weight: 800; margin-bottom: 8px; }
        .card-header p { font-size: 13px; color: var(--text-muted); }

        /* Step Indicator */
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
        .step-item.active:not(:last-child)::after { background: linear-gradient(90deg, var(--orange), var(--navy)); }
        .step-number {
            width: 38px; height: 38px;
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
            box-shadow: 0 4px 12px rgba(249,115,22,0.3);
        }
        .step-item.completed .step-number {
            background: #10B981;
            color: white;
        }
        .step-item.completed .step-number i { font-size: 12px; }
        .step-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .step-item.active .step-label { color: var(--orange); }

        /* Form Styles */
        .loan-form { display: flex; flex-direction: column; }
        .form-step {
            display: none;
            animation: slideUp 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }
        .form-step.active { display: block; }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .form-group input, .form-group select {
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
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 4px rgba(249,115,22,0.1);
            background: var(--white);
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* Card Selection Styles */
        .card-select-group { margin-bottom: 20px; }
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
            padding: 14px 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .card-option:hover { border-color: var(--orange); background: #FFF9F5; transform: translateY(-2px); }
        .card-option.selected {
            border-color: var(--orange);
            background: linear-gradient(135deg, #FFF9F5, #FFF5ED);
            box-shadow: 0 4px 12px rgba(249,115,22,0.15);
        }
        .card-option-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-option-icon i { font-size: 22px; color: var(--orange); }
        .card-option-content { flex: 1; }
        .card-option-title { font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
        .card-option-desc { font-size: 10px; color: var(--text-muted); }
        .card-option-radio {
            width: 20px; height: 20px;
            border: 2px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .card-option.selected .card-option-radio { border-color: var(--orange); }
        .card-option.selected .card-option-radio::after {
            content: '';
            width: 10px; height: 10px;
            background: var(--orange);
            border-radius: 50%;
        }

        /* Professional Category Grid */
        .profession-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .profession-card {
            background: #FAFBFD;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 12px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .profession-card i { font-size: 24px; color: var(--text-muted); margin-bottom: 6px; display: block; }
        .profession-card span { font-size: 11px; font-weight: 500; color: var(--text-body); }
        .profession-card.selected {
            border-color: var(--orange);
            background: linear-gradient(135deg, #FFF9F5, #FFF5ED);
        }
        .profession-card.selected i { color: var(--orange); }
        .profession-card.selected span { color: var(--orange-dark); font-weight: 600; }

        /* Practice Type Grid */
        .practice-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .practice-card {
            background: #FAFBFD;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 14px 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .practice-card i { font-size: 28px; color: var(--text-muted); margin-bottom: 8px; display: block; }
        .practice-card span { font-size: 13px; font-weight: 600; color: var(--text-body); }
        .practice-card small { font-size: 10px; color: var(--text-muted); display: block; margin-top: 4px; }
        .practice-card.selected {
            border-color: var(--orange);
            background: linear-gradient(135deg, #FFF9F5, #FFF5ED);
        }
        .practice-card.selected i { color: var(--orange); }
        .practice-card.selected span { color: var(--orange-dark); }

        /* Funding Purpose Grid */
        .purpose-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .purpose-card {
            background: #FAFBFD;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 12px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .purpose-card i { font-size: 20px; color: var(--text-muted); margin-bottom: 6px; display: block; }
        .purpose-card span { font-size: 10px; font-weight: 500; color: var(--text-body); }
        .purpose-card.selected {
            border-color: var(--orange);
            background: linear-gradient(135deg, #FFF9F5, #FFF5ED);
        }
        .purpose-card.selected i { color: var(--orange); }
        .purpose-card.selected span { color: var(--orange-dark); font-weight: 600; }

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
            box-shadow: 0 12px 28px rgba(7,27,62,0.25);
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
        .btn-back:hover { background: #E2E8F0; }
        .multi-buttons { display: flex; gap: 14px; margin-top: 8px; }
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

        /* Rest of existing styles (main content sections) */
        .main-content {
            max-width: 1360px; margin: 0 auto; padding: 100px 6% 80px;
        }
        .section-tag {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 11px; font-weight: 700;
            letter-spacing: .14em; text-transform: uppercase;
            color: var(--orange); margin-bottom: 14px;
        }
        .section-tag::before {
            content: ''; width: 24px; height: 2px;
            background: var(--orange); border-radius: 2px;
        }
        .why-section { margin-bottom: 90px; }
        .why-section > h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 3vw, 42px); font-weight: 800;
            color: var(--navy); margin-bottom: 48px; letter-spacing: -.02em;
        }
        .why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }
        .why-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 30px 28px;
            transition: all .35s ease; position: relative; overflow: hidden;
        }
        .why-card::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--orange), var(--gold));
            transform: scaleX(0); transform-origin: left; transition: transform .35s ease;
        }
        .why-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: rgba(249,115,22,.2); }
        .why-card:hover::after { transform: scaleX(1); }
        .why-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }
        .why-icon i { font-size: 22px; color: var(--orange); }
        .why-card h4 { font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 10px; }
        .why-card p { font-size: 14px; color: var(--text-body); line-height: 1.65; font-weight: 300; }

        .benefits-strip {
            display: grid; grid-template-columns: repeat(6, 1fr);
            gap: 0; margin-bottom: 90px;
            border: 1px solid var(--border); border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .benefit-tile {
            padding: 30px 20px; text-align: center;
            border-right: 1px solid var(--border);
            transition: all .3s ease; position: relative; overflow: hidden;
        }
        .benefit-tile:last-child { border-right: none; }
        .benefit-tile::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(249,115,22,.05), transparent);
            opacity: 0; transition: opacity .3s;
        }
        .benefit-tile:hover { background: var(--section-bg); }
        .benefit-tile:hover::before { opacity: 1; }
        .benefit-tile i { font-size: 26px; color: var(--orange); margin-bottom: 12px; display: block; }
        .benefit-tile h5 { font-size: 13px; font-weight: 700; color: var(--navy); margin-bottom: 6px; }
        .benefit-tile p { font-size: 11px; color: var(--text-muted); line-height: 1.4; font-weight: 300; }

        .process-section { margin-bottom: 90px; }
        .process-section > h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 3vw, 42px); font-weight: 800;
            color: var(--navy); margin-bottom: 52px; letter-spacing: -.02em;
        }
        .steps-timeline {
            display: flex; gap: 0; position: relative;
        }
        .steps-timeline::before {
            content: ''; position: absolute;
            top: 38px; left: 38px; right: 38px; height: 2px;
            background: linear-gradient(90deg, var(--orange), var(--gold), var(--orange));
            z-index: 0;
        }
        .step-item-custom {
            flex: 1; text-align: center; position: relative; z-index: 1; padding: 0 12px;
        }
        .step-num-custom {
            width: 76px; height: 76px; background: var(--navy);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 900; color: var(--white);
            border: 4px solid var(--white);
            box-shadow: 0 0 0 2px var(--orange);
            transition: all .35s ease;
        }
        .step-item-custom:hover .step-num-custom { background: var(--orange); transform: scale(1.08); }
        .step-item-custom h4 { font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
        .step-item-custom p { font-size: 12.5px; color: var(--text-muted); line-height: 1.55; font-weight: 300; }

        .elig-docs-section {
            background: var(--section-bg);
            border-radius: var(--radius-xl);
            padding: 60px 52px; margin-bottom: 90px;
            border: 1px solid var(--border);
            position: relative; overflow: hidden;
        }
        .elig-docs-section::before {
            content: ''; position: absolute; top: -100px; right: -100px;
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(249,115,22,.06) 0%, transparent 70%);
            border-radius: 50%;
        }
        .elig-docs-section > h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(26px, 3vw, 38px); font-weight: 800;
            color: var(--navy); margin-bottom: 48px; letter-spacing: -.02em;
        }
        .elig-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 48px;
        }
        .elig-card {
            background: var(--white); border: 1px solid var(--border);
            border-radius: var(--radius-md); padding: 28px 26px;
        }
        .elig-card h4 {
            font-size: 15px; font-weight: 700; color: var(--navy);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .elig-card h4 i { color: var(--orange); }
        .check-list { list-style: none; }
        .check-list li {
            display: flex; align-items: flex-start; gap: 12px;
            font-size: 14px; color: var(--text-body); line-height: 1.6;
            padding: 10px 0; border-bottom: 1px solid var(--border); font-weight: 300;
        }
        .check-list li:last-child { border-bottom: none; }
        .check-list li i { color: var(--orange); font-size: 11px; margin-top: 4px; flex-shrink: 0; }

        .docs-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        .doc-category {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 20px;
        }
        .doc-category h5 {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .doc-category h5 i { color: var(--orange); }
        .doc-list {
            list-style: none;
        }
        .doc-list li {
            font-size: 12.5px;
            color: var(--text-body);
            padding: 6px 0;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px dashed var(--border);
        }
        .doc-list li:last-child { border-bottom: none; }
        .doc-list li i { font-size: 10px; color: var(--orange); }

        .faq-section { margin-bottom: 90px; }
        .faq-section > h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 48px;
            letter-spacing: -0.02em;
        }
        .faq-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .faq-item {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 24px 28px;
            transition: all 0.3s ease;
        }
        .faq-item:hover { box-shadow: var(--shadow-sm); border-color: rgba(249,115,22,0.2); }
        .faq-item h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .faq-item h4 i { color: var(--orange); font-size: 14px; }
        .faq-item p { font-size: 14px; color: var(--text-body); line-height: 1.6; font-weight: 300; }

        .insight-banner {
            background: var(--navy);
            border-radius: var(--radius-lg);
            padding: 52px; margin-bottom: 90px;
            display: grid; grid-template-columns: 1fr auto;
            gap: 40px; align-items: center;
            position: relative; overflow: hidden;
        }
        .insight-banner::before {
            content: ''; position: absolute; top: -80px; right: -80px;
            width: 320px; height: 320px;
            background: radial-gradient(circle, rgba(249,115,22,.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .insight-text .section-tag { color: var(--orange-light); }
        .insight-text .section-tag::before { background: var(--orange-light); }
        .insight-text h3 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(20px, 2.2vw, 28px); font-weight: 800;
            color: var(--white); margin-bottom: 14px; letter-spacing: -.01em;
        }
        .insight-text p {
            font-size: 14.5px; color: rgba(255,255,255,.58);
            line-height: 1.75; font-weight: 300; max-width: 600px;
        }
        .insight-cta a {
            display: inline-flex; align-items: center; gap: 10px;
            background: var(--orange); color: white;
            padding: 14px 32px; border-radius: 50px;
            text-decoration: none; font-weight: 600; font-size: 14px;
            transition: all .3s cubic-bezier(.34,1.56,.64,1);
            white-space: nowrap;
        }
        .insight-cta a:hover { background: var(--orange-dark); transform: translateY(-3px); box-shadow: 0 12px 28px rgba(249,115,22,.35); }

        .section-divider {
            display: flex; align-items: center; gap: 24px; margin: 0 0 80px;
        }
        .section-divider::before, .section-divider::after {
            content: ''; flex: 1; height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }
        .divider-diamond {
            width: 8px; height: 8px; background: var(--orange);
            transform: rotate(45deg); border-radius: 1px;
        }

        .cta-section {
            background: var(--navy); border-radius: var(--radius-xl);
            padding: 80px 60px; text-align: center;
            position: relative; overflow: hidden;
        }
        .cta-section::before {
            content: ''; position: absolute; top: -150px; right: -150px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(249,115,22,.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .cta-inner { position: relative; z-index: 2; }
        .cta-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(249,115,22,.15); border: 1px solid rgba(249,115,22,.25);
            color: var(--orange-light); font-size: 11px; font-weight: 700;
            letter-spacing: .12em; text-transform: uppercase;
            padding: 6px 18px; border-radius: 50px; margin-bottom: 24px;
        }
        .cta-section h3 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 3.5vw, 46px); color: var(--white);
            margin-bottom: 18px; font-weight: 900; letter-spacing: -.02em;
        }
        .cta-section p {
            font-size: 16px; color: rgba(255,255,255,.52);
            margin-bottom: 40px; font-weight: 300;
        }
        .cta-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
        .cta-button {
            background: var(--white); color: var(--navy);
            padding: 18px 48px; border-radius: 50px; text-decoration: none;
            font-weight: 700; font-size: 16px;
            transition: all .3s cubic-bezier(.34,1.56,.64,1);
            display: inline-flex; align-items: center; gap: 10px;
        }
        .cta-button:hover { transform: scale(1.06) translateY(-2px); box-shadow: 0 20px 48px rgba(0,0,0,.25); background: var(--cream); }
        .cta-button-outline {
            background: transparent; color: var(--white);
            padding: 18px 40px; border-radius: 50px; text-decoration: none;
            font-weight: 600; font-size: 15px;
            border: 1.5px solid rgba(255,255,255,.3);
            transition: all .3s ease;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .cta-button-outline:hover { border-color: rgba(255,255,255,.7); background: rgba(255,255,255,.08); }

        .reveal {
            opacity: 0; transform: translateY(32px);
            transition: opacity .7s ease, transform .7s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero-left { animation: fadeUp .9s cubic-bezier(.22,1,.36,1) both; }
        .hero-right { animation: fadeUp .9s cubic-bezier(.22,1,.36,1) .18s both; }

        @media (max-width: 1100px) {
            .hero-content { grid-template-columns: 1fr; gap: 50px; }
            .hero-left p { max-width: 100%; }
            .floating-card { max-width: 560px; margin: 0 auto; }
            .why-grid { grid-template-columns: 1fr 1fr; }
            .steps-timeline { flex-wrap: wrap; gap: 40px; }
            .steps-timeline::before { display: none; }
            .step-item-custom { flex: 0 0 calc(50% - 20px); }
            .benefits-strip { grid-template-columns: repeat(3,1fr); }
            .profession-grid { grid-template-columns: repeat(2, 1fr); }
            .practice-grid { grid-template-columns: 1fr; }
            .purpose-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 900px) {
            .elig-grid { grid-template-columns: 1fr; }
            .docs-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .hero-premium { padding: 100px 5% 90px; }
            .floating-card { padding: 28px 22px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
            .card-options { grid-template-columns: 1fr; }
            .step-label { display: none; }
            .main-content { padding: 70px 5% 60px; }
            .why-grid { grid-template-columns: 1fr; }
            .elig-docs-section { padding: 40px 22px; }
            .cta-section { padding: 50px 26px; }
            .trust-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
            .benefits-strip { grid-template-columns: 1fr 1fr; }
            .step-item-custom { flex: 0 0 100%; }
            .profession-grid { grid-template-columns: 1fr; }
            .practice-grid { grid-template-columns: 1fr; }
            .purpose-grid { grid-template-columns: 1fr; }
            .faq-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 480px) {
            .hero-left h1 { font-size: 33px; }
            .hero-buttons { flex-direction: column; }
            .btn-primary-hero, .btn-outline-hero { justify-content: center; }
            .benefits-strip { grid-template-columns: 1fr; }
            .cta-buttons { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>

<!-- ═══════════ PREMIUM HERO SECTION ═══════════ -->
<div class="hero-premium">
    <div class="hero-orb-1"></div>
    <div class="hero-orb-2"></div>
    
    <!-- VIDEO BACKGROUND -->
    <div class="hero-video-wrap">
        <video autoplay muted loop playsinline preload="auto" poster="">
            <source src="https://videos.pexels.com/video-files/4339660/4339660-uhd_2560_1440_25fps.mp4" type="video/mp4">
            <source src="https://videos.pexels.com/video-files/4339660/4339660-hd_1920_1080_25fps.mp4" type="video/mp4">
        </video>
    </div>

    <!-- OVERLAY LAYERS -->
    <div class="hero-overlay"></div>
    <div class="hero-grid"></div>
    <div class="speed-lines"></div>
   

    <div class="hero-content" style="margin-top:-80px";>
        <!-- LEFT -->
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="pulse-dot"></span>
                Professional Overdraft Experts
            </div>
            <h1>
                Flexible Financial Support<br>
                for <span class="gradient-text">Professional Needs</span>
            </h1>
            <p>Managing professional expenses and maintaining smooth cash flow can be challenging. LOGINTOLOANS provides customized Overdraft Facility solutions to help professionals access funds whenever required without disrupting daily operations.</p>
            <div class="hero-buttons">
                <a href="#apply-form" class="btn-primary-hero">
                    <span>Apply Now</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="#apply-form" class="btn-outline-hero">
                    <i class="fas fa-bolt"></i>
                    <span>Apply in 2 Minutes</span>
                </a>
            </div>
            <div class="trust-row">
                <div class="stat-item">
                    <div class="stat-number">8k<span>+</span></div>
                    <div class="stat-label">Professionals Served</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1.2k<span>Cr+</span></div>
                    <div class="stat-label">OD Limit Sanctioned</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24<span>/7</span></div>
                    <div class="stat-label">Fund Access</div>
                </div>
            </div>
        </div>

        <!-- PREMIUM FORM CARD -->
        <div class="hero-right" id="apply-form">
            <div class="floating-card">
                <div class="card-header">
                    <div class="card-badge">
                        <i class="fas fa-bolt"></i>
                        <span>Instant Approval</span>
                    </div>
                    <h3>Get Overdraft Facility</h3>
                    <p>Pay Interest Only on Utilized Amount</p>
                </div>

                <form class="loan-form" id="overdraftForm" onsubmit="handleFormSubmit(event)">
                    <!-- STEP INDICATOR -->
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
                            <div class="step-label">Professional Details</div>
                        </div>
                        <div class="step-item" id="step4Indicator">
                            <div class="step-number">4</div>
                            <div class="step-label">OD Limit & Details</div>
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
                                <input type="email" id="email" placeholder="professional@email.com" required>
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

                    <!-- STEP 3 - PROFESSIONAL DETAILS -->
                    <div class="form-step" id="step3">
                        <div class="form-group">
                            <label><i class="fas fa-id-card"></i> Professional Registration No.</label>
                            <input type="text" id="registrationNumber" placeholder="Enter your professional registration number" required>
                        </div>

                        <div class="card-select-group">
                            <label><i class="fas fa-briefcase"></i> Professional Category</label>
                            <div class="profession-grid">
                                <div class="profession-card" data-value="doctor" onclick="selectProfession('doctor')">
                                    <i class="fas fa-stethoscope"></i>
                                    <span>Doctor</span>
                                </div>
                                <div class="profession-card" data-value="architect" onclick="selectProfession('architect')">
                                    <i class="fas fa-drafting-compass"></i>
                                    <span>Architect</span>
                                </div>
                                <div class="profession-card" data-value="ca" onclick="selectProfession('ca')">
                                    <i class="fas fa-chart-line"></i>
                                    <span>CA</span>
                                </div>
                                <div class="profession-card" data-value="lawyer" onclick="selectProfession('lawyer')">
                                    <i class="fas fa-gavel"></i>
                                    <span>Lawyer</span>
                                </div>
                                <div class="profession-card" data-value="engineer" onclick="selectProfession('engineer')">
                                    <i class="fas fa-microchip"></i>
                                    <span>Engineer</span>
                                </div>
                                <div class="profession-card" data-value="other" onclick="selectProfession('other')">
                                    <i class="fas fa-briefcase"></i>
                                    <span>Other</span>
                                </div>
                            </div>
                            <input type="hidden" id="professionalCategory" name="professionalCategory" value="">
                        </div>

                        <div class="card-select-group">
                            <label><i class="fas fa-chart-line"></i> Practice Type</label>
                            <div class="practice-grid">
                                <div class="practice-card" data-value="independent" onclick="selectPracticeType('independent')">
                                    <i class="fas fa-user"></i>
                                    <span>Independent Practice</span>
                                    <small>Solo practitioner</small>
                                </div>
                                <div class="practice-card" data-value="firm" onclick="selectPracticeType('firm')">
                                    <i class="fas fa-handshake"></i>
                                    <span>Partnership/Firm</span>
                                    <small>Multi-professional firm</small>
                                </div>
                            </div>
                            <input type="hidden" id="practiceType" name="practiceType" value="">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-chart-line"></i> Years of Professional Experience</label>
                            <select id="experience" required>
                                <option value="">Select Experience</option>
                                <option value="1">Less than 2 years</option>
                                <option value="2">2 - 5 years</option>
                                <option value="5">5 - 10 years</option>
                                <option value="10">10 - 15 years</option>
                                <option value="15">15+ years</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-rupee-sign"></i> Monthly Practice Income (₹)</label>
                            <input type="number" id="monthlyIncome" placeholder="Average monthly income from practice" required>
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

                    <!-- STEP 4 - OD LIMIT & DETAILS -->
                    <div class="form-step" id="step4">
                        <div class="card-select-group">
                            <label><i class="fas fa-bullseye"></i> Primary Usage Purpose</label>
                            <div class="purpose-grid">
                                <div class="purpose-card" data-value="salaries" onclick="selectPurpose('salaries')">
                                    <i class="fas fa-users"></i>
                                    <span>Salaries</span>
                                </div>
                                <div class="purpose-card" data-value="rent" onclick="selectPurpose('rent')">
                                    <i class="fas fa-building"></i>
                                    <span>Office Rent</span>
                                </div>
                                <div class="purpose-card" data-value="operations" onclick="selectPurpose('operations')">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Daily Operations</span>
                                </div>
                                <div class="purpose-card" data-value="emergency" onclick="selectPurpose('emergency')">
                                    <i class="fas fa-ambulance"></i>
                                    <span>Emergency</span>
                                </div>
                                <div class="purpose-card" data-value="cash-flow" onclick="selectPurpose('cash-flow')">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Cash Flow Gap</span>
                                </div>
                                <div class="purpose-card" data-value="other" onclick="selectPurpose('other')">
                                    <i class="fas fa-tasks"></i>
                                    <span>Other</span>
                                </div>
                            </div>
                            <input type="hidden" id="odPurpose" name="odPurpose" value="">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-rupee-sign"></i> Overdraft Limit Required (₹)</label>
                            <select id="odLimit" required>
                                <option value="">Select OD Limit</option>
                                <option value="100000">₹1,00,000</option>
                                <option value="250000">₹2,50,000</option>
                                <option value="500000">₹5,00,000</option>
                                <option value="1000000">₹10,00,000</option>
                                <option value="2500000">₹25,00,000</option>
                                <option value="5000000">₹50,00,000+</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-university"></i> Primary Bank Name</label>
                            <input type="text" id="bankName" placeholder="e.g., SBI, HDFC, ICICI" required>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-chart-line"></i> Average Monthly Bank Balance (₹)</label>
                            <input type="number" id="avgBalance" placeholder="Average monthly balance in your account" required>
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
                        Thank you! Our overdraft expert will contact you shortly.
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

    <!-- WHY CHOOSE -->
    <section class="why-section reveal">
        <div class="section-tag">Why LOGINTOLOANS</div>
        <h2>Overdraft Facility Solutions<br>Built for Professional Practices</h2>
        <div class="why-grid">
            <div class="why-card"><div class="why-icon"><i class="fas fa-hand-holding-usd"></i></div><h4>Flexible Withdrawal</h4><p>Access funds as needed within approved limit.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-percent"></i></div><h4>Interest on Usage Only</h4><p>Pay interest only on amount utilized.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-bolt"></i></div><h4>Quick Access</h4><p>Immediate support for urgent needs.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-sync-alt"></i></div><h4>Revolving Credit</h4><p>Repay and reuse multiple times.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-file-alt"></i></div><h4>Minimal Documentation</h4><p>Simplified verification process.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-headset"></i></div><h4>Dedicated Assistance</h4><p>Expert support throughout.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- BENEFITS STRIP -->
    <section class="reveal" style="margin-bottom:90px;">
        <div class="section-tag" style="margin-bottom:10px;">Benefits</div>
        <h2 style="font-family:'Playfair Display',serif;font-size:clamp(28px,3vw,42px);font-weight:800;color:var(--navy);margin-bottom:40px;letter-spacing:-.02em;">Why Choose an Overdraft Facility?</h2>
        <div class="benefits-strip">
            <div class="benefit-tile"><i class="fas fa-chart-line"></i><h5>Cash Flow</h5><p>Smooth operations</p></div>
            <div class="benefit-tile"><i class="fas fa-clock"></i><h5>Delayed Payments</h5><p>Bridge the gap</p></div>
            <div class="benefit-tile"><i class="fas fa-ambulance"></i><h5>Emergency Support</h5><p>Urgent needs</p></div>
            <div class="benefit-tile"><i class="fas fa-chart-pie"></i><h5>Financial Flexibility</h5><p>Use as needed</p></div>
            <div class="benefit-tile"><i class="fas fa-chart-line"></i><h5>Business Stability</h5><p>No disruptions</p></div>
            <div class="benefit-tile"><i class="fas fa-calendar-alt"></i><h5>Flexible Repayment</h5><p>Match income</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- HOW IT WORKS -->
    <section class="process-section reveal">
        <div class="section-tag">How It Works</div>
        <h2>Get Overdraft Facility in<br>4 Simple Steps</h2>
        <div class="steps-timeline">
            <div class="step-item-custom"><div class="step-num-custom">1</div><h4>Submit Application</h4><p>Enter professional & financial details.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">2</div><h4>Upload Documents</h4><p>Submit KYC & professional documents.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">3</div><h4>Verification</h4><p>Lender evaluates profile.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">4</div><h4>Limit Approval</h4><p>OD limit available for use.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- ELIGIBILITY & DOCS -->
    <section class="elig-docs-section reveal">
        <div class="section-tag">Requirements</div>
        <h2>Eligibility &amp; Documents<br>Required for Overdraft Facility</h2>
        <div class="elig-grid">
            <div class="elig-card"><h4><i class="fas fa-user-check"></i> Eligibility Criteria</h4><ul class="check-list"><li><i class="fas fa-check"></i> Qualified Professional</li><li><i class="fas fa-check"></i> Professional registration number</li><li><i class="fas fa-check"></i> Stable professional income</li><li><i class="fas fa-check"></i> Healthy credit score</li><li><i class="fas fa-check"></i> Active bank account</li></ul></div>
            <div class="elig-card"><h4><i class="fas fa-file-alt"></i> Required Documents</h4><ul class="check-list"><li><i class="fas fa-check"></i> Identity & Address Proof</li><li><i class="fas fa-check"></i> Professional Qualification Certificate</li><li><i class="fas fa-check"></i> Bank Statements (6-12 months)</li><li><i class="fas fa-check"></i> IT Returns & Financial Statements</li></ul></div>
        </div>
        <div class="section-tag" style="margin-bottom:20px;">Documents Checklist</div>
        <div class="docs-grid">
            <div class="doc-category"><h5><i class="fas fa-id-card"></i> Identity & Address</h5><ul class="doc-list"><li><i class="fas fa-check-circle"></i> Aadhaar Card</li><li><i class="fas fa-check-circle"></i> PAN Card</li><li><i class="fas fa-check-circle"></i> Passport / Voter ID</li><li><i class="fas fa-check-circle"></i> Utility Bills</li></ul></div>
            <div class="doc-category"><h5><i class="fas fa-certificate"></i> Professional Documents</h5><ul class="doc-list"><li><i class="fas fa-check-circle"></i> Professional Qualification Certificate</li><li><i class="fas fa-check-circle"></i> Professional Registration</li><li><i class="fas fa-check-circle"></i> Office Address Proof</li><li><i class="fas fa-check-circle"></i> Practice License</li></ul></div>
            <div class="doc-category"><h5><i class="fas fa-file-invoice-dollar"></i> Financial Documents</h5><ul class="doc-list"><li><i class="fas fa-check-circle"></i> Bank Statements (6-12 months)</li><li><i class="fas fa-check-circle"></i> Income Tax Returns</li><li><i class="fas fa-check-circle"></i> Profit & Loss Statements</li><li><i class="fas fa-check-circle"></i> Balance Sheets</li></ul></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- TIPS BEFORE APPLYING -->
    <section class="elig-docs-section reveal">
        <div class="section-tag">Smart Tips</div>
        <h2>Before Applying for<br>Overdraft Facility</h2>
        <div class="elig-grid">
            <div class="elig-card"><h4><i class="fas fa-lightbulb"></i> Financial Planning</h4><ul class="check-list"><li><i class="fas fa-check"></i> Evaluate your cash flow carefully</li><li><i class="fas fa-check"></i> Use funds responsibly within capacity</li><li><i class="fas fa-check"></i> Compare interest rates and charges</li><li><i class="fas fa-check"></i> Maintain a good credit score</li><li><i class="fas fa-check"></i> Keep financial documents updated</li><li><i class="fas fa-check"></i> Understand all terms and conditions</li></ul></div>
            <div class="elig-card"><h4><i class="fas fa-chart-line"></i> Business Growth</h4><ul class="check-list"><li><i class="fas fa-check"></i> Plan repayments based on income</li><li><i class="fas fa-check"></i> Consider seasonal cash flow variations</li><li><i class="fas fa-check"></i> Maintain organized expense records</li><li><i class="fas fa-check"></i> Review OD terms carefully</li><li><i class="fas fa-check"></i> Seek professional advice if needed</li></ul></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- FAQ SECTION -->
    <section class="faq-section reveal">
        <div class="section-tag">FAQs</div>
        <h2>Frequently Asked<br>Questions</h2>
        <div class="faq-grid">
            <div class="faq-item"><h4><i class="fas fa-question-circle"></i> What is an Overdraft Facility for Professionals?</h4><p>A flexible credit arrangement allowing professionals to withdraw funds beyond available balance up to a pre-approved limit.</p></div>
            <div class="faq-item"><h4><i class="fas fa-percent"></i> How is interest calculated?</h4><p>Interest is charged only on the amount utilized, not on the entire sanctioned limit.</p></div>
            <div class="faq-item"><h4><i class="fas fa-sync-alt"></i> Can I reuse the overdraft after repayment?</h4><p>Yes, you can repay and reuse multiple times during the approved tenure.</p></div>
            <div class="faq-item"><h4><i class="fas fa-clock"></i> What is the repayment tenure?</h4><p>Typically 12 months, renewable based on repayment behavior.</p></div>
            <div class="faq-item"><h4><i class="fas fa-briefcase"></i> Which professionals can apply?</h4><p>Doctors, CAs, lawyers, architects, engineers, consultants, and other professionals.</p></div>
            <div class="faq-item"><h4><i class="fas fa-chart-line"></i> Is a good credit score important?</h4><p>Yes, it improves approval chances and may secure better terms.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- INSIGHT BANNER -->
    <div class="insight-banner reveal">
        <div class="insight-text">
            <div class="section-tag">Why Professionals Trust Us</div>
            <h3>Transparent, Reliable & Efficient Overdraft Financing</h3>
            <p>LOGINTOLOANS focuses on providing hassle-free financial support for professionals with transparent processes and customer-friendly assistance. Our simple digital process, trusted lending partners, and flexible overdraft solutions help professionals maintain business stability and growth.</p>
        </div>
        <div class="insight-cta">
            <a href="#apply-form" onclick="document.getElementById('apply-form').scrollIntoView({behavior:'smooth'}); return false;">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Apply for Overdraft</span>
            </a>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-section reveal">
        <div class="cta-inner">
            <div class="cta-eyebrow">Stay Financially Prepared</div>
            <h3>Get Flexible Overdraft<br>Facility for Your Practice</h3>
            <p>Quick access to funds, interest only on usage, and reliable support whenever you need it most.</p>
            <div class="cta-buttons">
                <a href="#apply-form" class="cta-button" onclick="document.getElementById('apply-form').scrollIntoView({behavior:'smooth'}); return false;">
                    <i class="fas fa-bolt"></i>
                    <span>Apply for Overdraft Facility</span>
                </a>
                <a href="tel:+91" class="cta-button-outline">
                    <i class="fas fa-phone"></i>
                    <span>Contact Our Team</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>

<script>
    // Selected values
    let selectedProfession = '';
    let selectedPracticeType = '';
    let selectedPurpose = '';

    // Professional Category Selection
    function selectProfession(value) {
        selectedProfession = value;
        document.getElementById('professionalCategory').value = value;
        
        const options = document.querySelectorAll('.profession-card');
        options.forEach(option => {
            option.classList.remove('selected');
            if (option.getAttribute('data-value') === value) {
                option.classList.add('selected');
            }
        });
    }

    // Practice Type Selection
    function selectPracticeType(value) {
        selectedPracticeType = value;
        document.getElementById('practiceType').value = value;
        
        const options = document.querySelectorAll('.practice-card');
        options.forEach(option => {
            option.classList.remove('selected');
            if (option.getAttribute('data-value') === value) {
                option.classList.add('selected');
            }
        });
    }

    // OD Purpose Selection
    function selectPurpose(value) {
        selectedPurpose = value;
        document.getElementById('odPurpose').value = value;
        
        const options = document.querySelectorAll('.purpose-card');
        options.forEach(option => {
            option.classList.remove('selected');
            if (option.getAttribute('data-value') === value) {
                option.classList.add('selected');
            }
        });
    }

    // Form Steps
    let currentStep = 0;
    const steps = ['step1', 'step2', 'step3', 'step4'];
    const stepIndicators = ['step1Indicator', 'step2Indicator', 'step3Indicator', 'step4Indicator'];

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
            if (currentStep === 0) {
                const fullName = document.getElementById('fullName').value;
                if (!fullName) { alert('Please enter your full name'); return; }
            }
            if (currentStep === 1) {
                const mobile = document.getElementById('mobile').value;
                const email = document.getElementById('email').value;
                if (!mobile || mobile.length < 10) { alert('Please enter a valid 10-digit mobile number'); return; }
                if (!email || !email.includes('@')) { alert('Please enter a valid email address'); return; }
            }
            if (currentStep === 2) {
                const registrationNumber = document.getElementById('registrationNumber').value;
                if (!registrationNumber) { alert('Please enter your professional registration number'); return; }
                if (!selectedProfession) { alert('Please select your professional category'); return; }
                if (!selectedPracticeType) { alert('Please select practice type'); return; }
                const experience = document.getElementById('experience').value;
                if (!experience) { alert('Please select years of experience'); return; }
                const monthlyIncome = document.getElementById('monthlyIncome').value;
                if (!monthlyIncome) { alert('Please enter monthly practice income'); return; }
            }
            showStep(currentStep + 1);
        }
    }

    function prevStep() {
        if (currentStep > 0) { showStep(currentStep - 1); }
    }

    // Form Submit
    function handleFormSubmit(event) {
        event.preventDefault();

        const fullName = document.getElementById('fullName').value;
        const mobile = document.getElementById('mobile').value;
        const email = document.getElementById('email').value;
        const registrationNumber = document.getElementById('registrationNumber').value;
        const professionalCategory = document.getElementById('professionalCategory').value;
        const practiceType = document.getElementById('practiceType').value;
        const experience = document.getElementById('experience').value;
        const monthlyIncome = document.getElementById('monthlyIncome').value;
        const odPurpose = document.getElementById('odPurpose').value;
        const odLimit = document.getElementById('odLimit').value;
        const bankName = document.getElementById('bankName').value;
        const avgBalance = document.getElementById('avgBalance').value;

        if (!fullName || !mobile || !email || !registrationNumber || !professionalCategory || !practiceType || !experience || !monthlyIncome || !odPurpose || !odLimit || !bankName || !avgBalance) {
            alert('Please fill in all fields');
            return;
        }
        if (mobile.length < 10) { alert('Please enter a valid 10-digit mobile number'); return; }

        const successDiv = document.getElementById('formSuccess');
        successDiv.style.display = 'flex';
        console.log('Overdraft Facility Application Submitted:', { fullName, mobile, email, registrationNumber, professionalCategory, practiceType, experience, monthlyIncome, odPurpose, odLimit, bankName, avgBalance });

        // Reset form
        document.getElementById('overdraftForm').reset();
        selectedProfession = '';
        selectedPracticeType = '';
        selectedPurpose = '';
        document.querySelectorAll('.profession-card, .practice-card, .purpose-card').forEach(el => el.classList.remove('selected'));
        showStep(0);

        setTimeout(() => { successDiv.style.display = 'none'; }, 5000);
    }

    // Smooth Scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });

    // Scroll Reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // Video Fallback
    const vid = document.querySelector('.hero-video-wrap video');
    if (vid) {
        vid.addEventListener('error', () => {
            vid.closest('.hero-video-wrap').style.background =
                'linear-gradient(135deg, #060F22 0%, #122F66 50%, #0A1E40 100%)';
        });
    }
</script>
</body>
</html>