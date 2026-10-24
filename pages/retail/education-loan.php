<?php
// FILE: education-loan.php
include('../../includes/dbConfig.php');
include('../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Education Loan – Invest in Your Future</title>
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
            background: radial-gradient(circle, rgba(232,197,90,0.2) 0%, transparent 70%);
            border-radius: 50%;
            animation: orbFloat 12s ease-in-out infinite;
        }
        .hero-orb-2 {
            position: absolute;
            bottom: -100px; left: -150px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(249,115,22,0.12) 0%, transparent 70%);
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
            background: radial-gradient(ellipse 70% 60% at 75% 50%, rgba(232,197,90,0.12) 0%, transparent 70%);
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
            background: linear-gradient(90deg, transparent, rgba(232,197,90,0.25), transparent);
            transform: rotate(-6deg);
            animation: speed-sweep 4s ease-in-out infinite;
        }
        .speed-lines::after {
            content: '';
            position: absolute;
            top: 55%; left: -10%;
            width: 120%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(249,115,22,0.15), transparent);
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
            background: rgba(232,197,90,0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(232,197,90,0.25);
            color: var(--gold);
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
        .hero-left h1 em {
            font-style: italic;
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
        .btn-outline-hero:hover { border-color: var(--gold); background: rgba(232,197,90,0.1); transform: translateY(-2px); }

        .trust-row { display: flex; align-items: center; flex-wrap: wrap; gap: 30px; }
        .stat-item { text-align: left; }
        .stat-number { font-family: 'Playfair Display', serif; font-size: 34px; font-weight: 800; color: var(--white); line-height: 1; }
        .stat-number span { color: var(--gold); font-size: 28px; }
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
            background: linear-gradient(90deg, var(--navy), var(--gold), var(--orange), var(--gold), var(--navy));
            border-radius: 32px 32px 0 0;
        }

        .card-header { text-align: center; margin-bottom: 28px; }
        .card-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #FEF8E8, #FDF0D5);
            padding: 6px 16px;
            border-radius: 100px;
            margin-bottom: 16px;
        }
        .card-badge i { color: var(--gold); font-size: 12px; }
        .card-badge span { font-size: 12px; font-weight: 600; color: #B8860B; letter-spacing: 0.05em; }
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
        .step-item.active:not(:last-child)::after { background: linear-gradient(90deg, var(--gold), var(--navy)); }
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
            background: linear-gradient(135deg, var(--gold), #B8860B);
            color: white;
            box-shadow: 0 4px 12px rgba(232,197,90,0.3);
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
        .step-item.active .step-label { color: var(--gold); }

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
            border-color: var(--gold);
            box-shadow: 0 0 0 4px rgba(232,197,90,0.15);
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
        .card-option:hover { border-color: var(--gold); background: #FFF9F5; transform: translateY(-2px); }
        .card-option.selected {
            border-color: var(--gold);
            background: linear-gradient(135deg, #FEF8E8, #FDF0D5);
            box-shadow: 0 4px 12px rgba(232,197,90,0.15);
        }
        .card-option-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #FEF8E8, #FDF0D5);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-option-icon i { font-size: 22px; color: var(--gold); }
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
        .card-option.selected .card-option-radio { border-color: var(--gold); }
        .card-option.selected .card-option-radio::after {
            content: '';
            width: 10px; height: 10px;
            background: var(--gold);
            border-radius: 50%;
        }

        /* Course Type Grid */
        .course-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .course-card {
            background: #FAFBFD;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 12px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .course-card i { font-size: 20px; color: var(--text-muted); margin-bottom: 6px; display: block; }
        .course-card span { font-size: 11px; font-weight: 500; color: var(--text-body); }
        .course-card.selected {
            border-color: var(--gold);
            background: linear-gradient(135deg, #FEF8E8, #FDF0D5);
        }
        .course-card.selected i { color: var(--gold); }
        .course-card.selected span { color: var(--gold); font-weight: 600; }

        /* Destination Grid */
        .destination-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .destination-card {
            background: #FAFBFD;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 12px 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .destination-card i { font-size: 20px; color: var(--text-muted); margin-bottom: 6px; display: block; }
        .destination-card span { font-size: 11px; font-weight: 500; color: var(--text-body); }
        .destination-card.selected {
            border-color: var(--gold);
            background: linear-gradient(135deg, #FEF8E8, #FDF0D5);
        }
        .destination-card.selected i { color: var(--gold); }
        .destination-card.selected span { color: var(--gold); font-weight: 600; }

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
            background: linear-gradient(90deg, var(--gold), var(--orange));
            transform: scaleX(0); transform-origin: left; transition: transform .35s ease;
        }
        .why-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: rgba(232,197,90,.2); }
        .why-card:hover::after { transform: scaleX(1); }
        .why-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #FEF8E8, #FDF0D5);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
        }
        .why-icon i { font-size: 22px; color: var(--gold); }
        .why-card h4 { font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 10px; }
        .why-card p { font-size: 14px; color: var(--text-body); line-height: 1.65; font-weight: 300; }

        .loan-types-section { margin-bottom: 90px; }
        .loan-types-section > h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 48px;
            letter-spacing: -0.02em;
        }
        .loan-types-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }
        .loan-type-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 28px 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .loan-type-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-md); border-color: rgba(232,197,90,0.3); }
        .loan-type-card i { font-size: 38px; color: var(--gold); margin-bottom: 16px; display: block; }
        .loan-type-card h5 { font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
        .loan-type-card p { font-size: 12.5px; color: var(--text-muted); line-height: 1.5; }

        .expenses-section {
            background: linear-gradient(135deg, #FEF8E8 0%, #FDF0D5 100%);
            border-radius: var(--radius-xl);
            padding: 60px 52px;
            margin-bottom: 90px;
            border: 1px solid rgba(232,197,90,0.3);
        }
        .expenses-section > h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }
        .expenses-desc { font-size: 15px; color: var(--text-body); margin-bottom: 40px; max-width: 800px; }
        .expenses-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }
        .expense-item {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 24px 20px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }
        .expense-item:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); }
        .expense-item i { font-size: 32px; color: var(--gold); margin-bottom: 14px; display: block; }
        .expense-item h5 { font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
        .expense-item p { font-size: 12px; color: var(--text-muted); line-height: 1.5; }

        .benefits-strip {
            display: grid; grid-template-columns: repeat(5, 1fr);
            gap: 0; margin-bottom: 90px;
            border: 1px solid var(--border); border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .benefit-tile {
            padding: 36px 24px; text-align: center;
            border-right: 1px solid var(--border);
            transition: all .3s ease; position: relative; overflow: hidden;
        }
        .benefit-tile:last-child { border-right: none; }
        .benefit-tile::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(232,197,90,.05), transparent);
            opacity: 0; transition: opacity .3s;
        }
        .benefit-tile:hover { background: var(--section-bg); }
        .benefit-tile:hover::before { opacity: 1; }
        .benefit-tile i { font-size: 28px; color: var(--gold); margin-bottom: 14px; display: block; }
        .benefit-tile h5 { font-size: 13px; font-weight: 700; color: var(--navy); margin-bottom: 6px; }
        .benefit-tile p { font-size: 12px; color: var(--text-muted); line-height: 1.5; font-weight: 300; }

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
            background: radial-gradient(circle, rgba(232,197,90,.06) 0%, transparent 70%);
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
            display: flex; align-items: center; gap: 10px;
        }
        .elig-card h4 i { color: var(--gold); }
        .check-list { list-style: none; }
        .check-list li {
            display: flex; align-items: flex-start; gap: 12px;
            font-size: 14px; color: var(--text-body); line-height: 1.6;
            padding: 10px 0; border-bottom: 1px solid var(--border); font-weight: 300;
        }
        .check-list li:last-child { border-bottom: none; }
        .check-list li i { color: var(--gold); font-size: 11px; margin-top: 4px; flex-shrink: 0; }

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
        .doc-category h5 i { color: var(--gold); }
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
        .doc-list li i { font-size: 10px; color: var(--gold); }

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
            background: linear-gradient(90deg, var(--gold), var(--orange), var(--gold));
            z-index: 0;
        }
        .step-item-custom {
            flex: 1; text-align: center; position: relative; z-index: 1; padding: 0 12px;
        }
        .step-num-custom {
            width: 76px; height: 76px; background: var(--navy);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 900; color: var(--white);
            border: 4px solid var(--white);
            box-shadow: 0 0 0 2px var(--gold);
            transition: all .35s ease;
        }
        .step-item-custom:hover .step-num-custom { background: var(--gold); color: var(--navy); transform: scale(1.08); }
        .step-item-custom h4 { font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
        .step-item-custom p { font-size: 12.5px; color: var(--text-muted); line-height: 1.55; font-weight: 300; }

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
        .faq-item:hover { box-shadow: var(--shadow-sm); border-color: rgba(232,197,90,0.2); }
        .faq-item h4 {
            font-size: 16px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .faq-item h4 i { color: var(--gold); font-size: 14px; }
        .faq-item p { font-size: 14px; color: var(--text-body); line-height: 1.6; font-weight: 300; }

        .moratorium-banner {
            background: var(--navy);
            border-radius: var(--radius-lg);
            padding: 52px;
            margin-bottom: 90px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .moratorium-banner::before {
            content: ''; position: absolute; top: -80px; right: -80px;
            width: 320px; height: 320px;
            background: radial-gradient(circle, rgba(232,197,90,0.12) 0%, transparent 70%);
            border-radius: 50%;
        }
        .moratorium-content { position: relative; z-index: 1; }
        .moratorium-content i { font-size: 48px; color: var(--gold); margin-bottom: 20px; display: inline-block; }
        .moratorium-content h3 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(24px, 2.5vw, 32px);
            font-weight: 800;
            color: var(--white);
            margin-bottom: 16px;
        }
        .moratorium-content p {
            font-size: 15px;
            color: rgba(255,255,255,0.65);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.7;
        }

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
            background: radial-gradient(circle, rgba(232,197,90,.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .insight-text .section-tag { color: var(--gold); }
        .insight-text .section-tag::before { background: var(--gold); }
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
            background: var(--gold); color: var(--navy);
            padding: 14px 32px; border-radius: 50px;
            text-decoration: none; font-weight: 700; font-size: 14px;
            transition: all .3s cubic-bezier(.34,1.56,.64,1);
            white-space: nowrap;
        }
        .insight-cta a:hover { background: var(--orange); color: white; transform: translateY(-3px); }

        .section-divider {
            display: flex; align-items: center; gap: 24px; margin: 0 0 80px;
        }
        .section-divider::before, .section-divider::after {
            content: ''; flex: 1; height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }
        .divider-diamond {
            width: 8px; height: 8px; background: var(--gold);
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
            background: radial-gradient(circle, rgba(232,197,90,.12) 0%, transparent 70%);
            border-radius: 50%;
        }
        .cta-inner { position: relative; z-index: 2; }
        .cta-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(232,197,90,.15); border: 1px solid rgba(232,197,90,.25);
            color: var(--gold); font-size: 11px; font-weight: 700;
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
        .cta-button-outline:hover { border-color: var(--gold); background: rgba(232,197,90,.1); }

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
            .loan-types-grid { grid-template-columns: repeat(2, 1fr); }
            .steps-timeline { flex-wrap: wrap; gap: 40px; }
            .steps-timeline::before { display: none; }
            .step-item-custom { flex: 0 0 calc(50% - 20px); }
            .benefits-strip { grid-template-columns: repeat(3,1fr); }
            .course-grid { grid-template-columns: repeat(2, 1fr); }
            .destination-grid { grid-template-columns: repeat(2, 1fr); }
            .expenses-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 900px) {
            .elig-grid { grid-template-columns: 1fr; }
            .docs-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .hero-premium { padding: 100px 5% 90px; }
            .floating-card { padding: 28px 22px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
            .step-label { display: none; }
            .main-content { padding: 70px 5% 60px; }
            .why-grid { grid-template-columns: 1fr; }
            .loan-types-grid { grid-template-columns: 1fr; }
            .elig-docs-section { padding: 40px 22px; }
            .cta-section { padding: 50px 26px; }
            .trust-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
            .benefits-strip { grid-template-columns: 1fr 1fr; }
            .step-item-custom { flex: 0 0 100%; }
            .course-grid { grid-template-columns: 1fr; }
            .destination-grid { grid-template-columns: 1fr; }
            .expenses-grid { grid-template-columns: 1fr; }
            .expenses-section { padding: 40px 24px; }
            .moratorium-banner { padding: 36px 24px; }
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
            <source src="https://videos.pexels.com/video-files/5473126/5473126-uhd_2560_1440_25fps.mp4" type="video/mp4">
            <source src="https://videos.pexels.com/video-files/5473126/5473126-hd_1920_1080_25fps.mp4" type="video/mp4">
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
                Education Loan Experts
            </div>
            <h1>
                Invest in Your<br>
                Future with <em>Education Loan</em>
            </h1>
            <p>Education is one of the most valuable investments for a successful future. LOGINTOLOANS helps students and parents access reliable education loan assistance for higher studies in India and abroad.</p>
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
                    <div class="stat-number">15k<span>+</span></div>
                    <div class="stat-label">Students Funded</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">500<span>+</span></div>
                    <div class="stat-label">Partner Institutes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30<span>+</span></div>
                    <div class="stat-label">Countries Covered</div>
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
                    <h3>Get Education Loan Quote</h3>
                    <p>Indian & Overseas Studies • Flexible Repayment</p>
                </div>

                <form class="loan-form" id="educationLoanForm" onsubmit="handleFormSubmit(event)">
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
                            <div class="step-label">Course</div>
                        </div>
                        <div class="step-item" id="step4Indicator">
                            <div class="step-number">4</div>
                            <div class="step-label">Destination & Loan</div>
                        </div>
                    </div>

                    <!-- STEP 1 -->
                    <div class="form-step active" id="step1">
                        <div class="form-group">
                            <label><i class="fas fa-user-graduate"></i> Student Full Name</label>
                            <input type="text" id="fullName" placeholder="Enter student's full name" required>
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
                                <input type="email" id="email" placeholder="student@email.com" required>
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

                    <!-- STEP 3 - COURSE TYPE -->
                    <div class="form-step" id="step3">
                        <div class="card-select-group">
                            <label><i class="fas fa-graduation-cap"></i> Course Type</label>
                            <div class="course-grid">
                                <div class="course-card" data-value="undergraduate" onclick="selectCourse('undergraduate')">
                                    <i class="fas fa-user-graduate"></i>
                                    <span>Undergraduate</span>
                                </div>
                                <div class="course-card" data-value="postgraduate" onclick="selectCourse('postgraduate')">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Postgraduate</span>
                                </div>
                                <div class="course-card" data-value="professional" onclick="selectCourse('professional')">
                                    <i class="fas fa-briefcase"></i>
                                    <span>Professional</span>
                                </div>
                                <div class="course-card" data-value="diploma" onclick="selectCourse('diploma')">
                                    <i class="fas fa-certificate"></i>
                                    <span>Diploma</span>
                                </div>
                                <div class="course-card" data-value="phd" onclick="selectCourse('phd')">
                                    <i class="fas fa-flask"></i>
                                    <span>PhD / Research</span>
                                </div>
                            </div>
                            <input type="hidden" id="courseType" name="courseType" value="">
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

                    <!-- STEP 4 - DESTINATION & LOAN -->
                    <div class="form-step" id="step4">
                        <div class="card-select-group">
                            <label><i class="fas fa-globe"></i> Study Destination</label>
                            <div class="destination-grid">
                                <div class="destination-card" data-value="india" onclick="selectDestination('india')">
                                    <i class="fas fa-flag-india"></i>
                                    <span>India</span>
                                </div>
                                <div class="destination-card" data-value="usa" onclick="selectDestination('usa')">
                                    <i class="fas fa-flag-usa"></i>
                                    <span>USA / Canada</span>
                                </div>
                                <div class="destination-card" data-value="uk" onclick="selectDestination('uk')">
                                    <i class="fas fa-passport"></i>
                                    <span>UK / Europe</span>
                                </div>
                                <div class="destination-card" data-value="australia" onclick="selectDestination('australia')">
                                    <i class="fas fa-tree"></i>
                                    <span>Australia / NZ</span>
                                </div>
                                <div class="destination-card" data-value="other" onclick="selectDestination('other')">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Other Countries</span>
                                </div>
                            </div>
                            <input type="hidden" id="destination" name="destination" value="">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-rupee-sign"></i> Loan Amount Required (₹)</label>
                            <select id="loanAmount" required>
                                <option value="">Select Loan Amount</option>
                                <option value="500000">₹5,00,000</option>
                                <option value="1000000">₹10,00,000</option>
                                <option value="2000000">₹20,00,000</option>
                                <option value="3500000">₹35,00,000</option>
                                <option value="5000000">₹50,00,000</option>
                                <option value="7500000">₹75,00,000+</option>
                            </select>
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
                        Thank you! Our education loan expert will contact you shortly.
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
        <h2>Education Loan Solutions<br>Built for Your Future</h2>
        <div class="why-grid">
            <div class="why-card"><div class="why-icon"><i class="fas fa-file-alt"></i></div><h4>Easy Loan Application Process</h4><p>Apply through a simplified process with professional guidance at every stage.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-globe"></i></div><h4>Indian & International Studies</h4><p>Loan assistance for courses within India and recognized universities abroad.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-calendar-alt"></i></div><h4>Flexible Repayment Options</h4><p>Choose repayment structures and tenures based on your financial comfort.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-book"></i></div><h4>Coverage for Multiple Expenses</h4><p>Tuition fees, hostel expenses, books, travel costs, and more.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-users"></i></div><h4>Support for Students & Parents</h4><p>Clear understanding of eligibility and documentation requirements.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-chart-line"></i></div><h4>Transparent Loan Guidance</h4><p>Clear info on interest rates, repayment schedules, and procedures.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- LOAN TYPES -->
    <section class="loan-types-section reveal">
        <div class="section-tag">Loan Options</div>
        <h2>Types of Education Loans<br>We Offer</h2>
        <div class="loan-types-grid">
            <div class="loan-type-card"><i class="fas fa-university"></i><h5>Domestic Education Loan</h5><p>Funding for recognized institutions within India.</p></div>
            <div class="loan-type-card"><i class="fas fa-plane"></i><h5>Overseas Education Loan</h5><p>Financial assistance for studying abroad.</p></div>
            <div class="loan-type-card"><i class="fas fa-briefcase"></i><h5>Professional Course Loan</h5><p>For MBA, Engineering, Medical, Law programs.</p></div>
            <div class="loan-type-card"><i class="fas fa-chart-bar"></i><h5>Skill Development Loan</h5><p>For vocational training and certification programs.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- EXPENSES COVERED -->
    <section class="expenses-section reveal">
        <div class="section-tag">Expenses Covered</div>
        <h2>What Does an Education Loan Cover?</h2>
        <p class="expenses-desc">Education loans may help cover a wide range of academic and living expenses.</p>
        <div class="expenses-grid">
            <div class="expense-item"><i class="fas fa-chalkboard-teacher"></i><h5>Academic Fees</h5><p>Tuition, examination, lab, library fees</p></div>
            <div class="expense-item"><i class="fas fa-home"></i><h5>Living Expenses</h5><p>Hostel, accommodation, food costs</p></div>
            <div class="expense-item"><i class="fas fa-book"></i><h5>Study Materials</h5><p>Books, laptop, equipment</p></div>
            <div class="expense-item"><i class="fas fa-plane"></i><h5>Travel Expenses</h5><p>For international education</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- BENEFITS STRIP -->
    <section class="reveal" style="margin-bottom:90px;">
        <div class="section-tag" style="margin-bottom:10px;">Benefits</div>
        <h2 style="font-family:'Playfair Display',serif;font-size:clamp(28px,3vw,42px);font-weight:800;color:var(--navy);margin-bottom:40px;letter-spacing:-.02em;">Why Take an Education Loan?</h2>
        <div class="benefits-strip">
            <div class="benefit-tile"><i class="fas fa-graduation-cap"></i><h5>Access Quality Education</h5><p>Pursue desired courses without financial burden.</p></div>
            <div class="benefit-tile"><i class="fas fa-stopwatch"></i><h5>Flexible Repayment</h5><p>Moratorium period during study.</p></div>
            <div class="benefit-tile"><i class="fas fa-chart-line"></i><h5>Financial Independence</h5><p>Focus on academic goals.</p></div>
            <div class="benefit-tile"><i class="fas fa-file-invoice-dollar"></i><h5>Tax Benefits</h5><p>Under Section 80E.</p></div>
            <div class="benefit-tile"><i class="fas fa-building"></i><h5>Build Credit History</h5><p>Positive credit profile.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- MORATORIUM PERIOD BANNER -->
    <div class="moratorium-banner reveal">
        <div class="moratorium-content">
            <i class="fas fa-hourglass-half"></i>
            <h3>Moratorium Period Facility</h3>
            <p>Many education loans provide a repayment holiday or moratorium period. During this period, students may not need to begin full EMI repayment immediately while pursuing studies. Repayment generally starts after course completion or after a specified time period depending on lender terms.</p>
        </div>
    </div>

    <!-- ELIGIBILITY & DOCS -->
    <section class="elig-docs-section reveal">
        <div class="section-tag">Requirements</div>
        <h2>Eligibility &amp; Documents<br>Required for Education Loan</h2>
        <div class="elig-grid">
            <div class="elig-card"><h4><i class="fas fa-user-graduate"></i> Student Eligibility</h4><ul class="check-list"><li><i class="fas fa-check"></i> Indian citizen (for domestic loans)</li><li><i class="fas fa-check"></i> Admission from recognized institution</li><li><i class="fas fa-check"></i> Minimum age as per lender</li><li><i class="fas fa-check"></i> Good academic record</li></ul></div>
            <div class="elig-card"><h4><i class="fas fa-handshake"></i> Co-Applicant Requirement</h4><ul class="check-list"><li><i class="fas fa-check"></i> Parent/guardian as co-applicant</li><li><i class="fas fa-check"></i> Income assessment of co-applicant</li><li><i class="fas fa-check"></i> Good credit history</li><li><i class="fas fa-check"></i> Collateral for higher amounts</li></ul></div>
        </div>
        <div class="section-tag" style="margin-bottom:20px;">Documents Checklist</div>
        <div class="docs-grid">
            <div class="doc-category"><h5><i class="fas fa-user"></i> Student Documents</h5><ul class="doc-list"><li><i class="fas fa-check-circle"></i> Aadhaar Card</li><li><i class="fas fa-check-circle"></i> PAN Card</li><li><i class="fas fa-check-circle"></i> Academic Mark Sheets</li><li><i class="fas fa-check-circle"></i> Admission Letter</li></ul></div>
            <div class="doc-category"><h5><i class="fas fa-users"></i> Co-Applicant Documents</h5><ul class="doc-list"><li><i class="fas fa-check-circle"></i> Identity & Address Proof</li><li><i class="fas fa-check-circle"></i> Income Documents</li><li><i class="fas fa-check-circle"></i> Bank Statements</li><li><i class="fas fa-check-circle"></i> IT Returns</li></ul></div>
            <div class="doc-category"><h5><i class="fas fa-globe"></i> For Overseas Education</h5><ul class="doc-list"><li><i class="fas fa-check-circle"></i> Visa Documents</li><li><i class="fas fa-check-circle"></i> Passport</li><li><i class="fas fa-check-circle"></i> University Fee Structure</li><li><i class="fas fa-check-circle"></i> Travel Cost Estimates</li></ul></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- PROCESS STEPS -->
    <section class="process-section reveal">
        <div class="section-tag">How It Works</div>
        <h2>Apply for an Education Loan in<br>5 Simple Steps</h2>
        <div class="steps-timeline">
            <div class="step-item-custom"><div class="step-num-custom">1</div><h4>Submit Application</h4><p>Fill online inquiry form.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">2</div><h4>Eligibility Review</h4><p>Evaluate suitable loan options.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">3</div><h4>Compare Options</h4><p>Review lenders & terms.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">4</div><h4>Documentation</h4><p>Submit required documents.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">5</div><h4>Approval & Disbursal</h4><p>Loan approval and fee disbursement.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- FAQ SECTION -->
    <section class="faq-section reveal">
        <div class="section-tag">FAQs</div>
        <h2>Frequently Asked<br>Questions</h2>
        <div class="faq-grid">
            <div class="faq-item"><h4><i class="fas fa-question-circle"></i> Can I get an education loan for studies abroad?</h4><p>Yes, loans are available for recognized international universities.</p></div>
            <div class="faq-item"><h4><i class="fas fa-users"></i> Is a co-applicant necessary?</h4><p>Most lenders require a parent or guardian as co-applicant.</p></div>
            <div class="faq-item"><h4><i class="fas fa-rupee-sign"></i> What expenses are covered?</h4><p>Tuition, hostel, books, travel, and other approved costs.</p></div>
            <div class="faq-item"><h4><i class="fas fa-hourglass-half"></i> Can I start repayment after finishing education?</h4><p>Yes, moratorium period is provided.</p></div>
            <div class="faq-item"><h4><i class="fas fa-building"></i> Is collateral required?</h4><p>Depends on loan value and lender guidelines.</p></div>
            <div class="faq-item"><h4><i class="fas fa-file-invoice"></i> Are tax benefits available?</h4><p>Yes, under Section 80E of Income Tax Act.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- INSIGHT BANNER -->
    <div class="insight-banner reveal">
        <div class="insight-text">
            <div class="section-tag">Market Insight</div>
            <h3>Education Financing Growing Rapidly</h3>
            <p>Education loans continue to grow in popularity as more students pursue higher education in India and abroad. Compare lenders based on interest rates, repayment flexibility, moratorium periods, and processing fees before finalizing.</p>
        </div>
        <div class="insight-cta">
            <a href="#apply-form" onclick="document.getElementById('apply-form').scrollIntoView({behavior:'smooth'}); return false;">
                <i class="fas fa-graduation-cap"></i>
                <span>Apply for Education Loan</span>
            </a>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-section reveal">
        <div class="cta-inner">
            <div class="cta-eyebrow">Start Your Journey</div>
            <h3>Invest in Your Future<br>with LOGINTOLOANS</h3>
            <p>Compare loan options, understand repayment plans, and receive expert guidance throughout the process.</p>
            <div class="cta-buttons">
                <a href="#apply-form" class="cta-button" onclick="document.getElementById('apply-form').scrollIntoView({behavior:'smooth'}); return false;">
                    <i class="fas fa-bolt"></i>
                    <span>Apply for Education Loan</span>
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
    let selectedCourse = '';
    let selectedDestination = '';

    // Course Type Selection
    function selectCourse(value) {
        selectedCourse = value;
        document.getElementById('courseType').value = value;
        
        const options = document.querySelectorAll('.course-card');
        options.forEach(option => {
            option.classList.remove('selected');
            if (option.getAttribute('data-value') === value) {
                option.classList.add('selected');
            }
        });
    }

    // Destination Selection
    function selectDestination(value) {
        selectedDestination = value;
        document.getElementById('destination').value = value;
        
        const options = document.querySelectorAll('.destination-card');
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
                if (!fullName) { alert('Please enter student name'); return; }
            }
            if (currentStep === 1) {
                const mobile = document.getElementById('mobile').value;
                const email = document.getElementById('email').value;
                if (!mobile || mobile.length < 10) { alert('Please enter a valid 10-digit mobile number'); return; }
                if (!email || !email.includes('@')) { alert('Please enter a valid email address'); return; }
            }
            if (currentStep === 2) {
                if (!selectedCourse) { alert('Please select course type'); return; }
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
        const courseType = document.getElementById('courseType').value;
        const destination = document.getElementById('destination').value;
        const loanAmount = document.getElementById('loanAmount').value;

        if (!fullName || !mobile || !email || !courseType || !destination || !loanAmount) {
            alert('Please fill in all fields');
            return;
        }
        if (mobile.length < 10) { alert('Please enter a valid 10-digit mobile number'); return; }

        const successDiv = document.getElementById('formSuccess');
        successDiv.style.display = 'flex';
        console.log('Education Loan Application Submitted:', { fullName, mobile, email, courseType, destination, loanAmount });

        // Reset form
        document.getElementById('educationLoanForm').reset();
        selectedCourse = '';
        selectedDestination = '';
        document.querySelectorAll('.course-card, .destination-card').forEach(el => el.classList.remove('selected'));
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