<?php
// FILE: business-loan.php
include('../../includes/dbConfig.php');
include('../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Business Loan for Self-Employed Professionals – Financial Solutions for Professional Growth</title>
    <meta name="description" content="Get customized business loans for self-employed professionals including doctors, CAs, lawyers, architects, engineers, consultants. Competitive interest rates, quick approvals, and flexible repayment options at LOGINTOLOANS.">
    <meta name="keywords" content="business loan for self-employed, professional loan, doctor loan, CA loan, architect loan, business finance, working capital loan, LOGINTOLOANS">
    <meta name="author" content="LOGINTOLOANS">
    <link rel="canonical" href="https://www.logintoloans.com/business-loan.php">
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

        /* Professional Category Grid */
        .profession-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
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
        .profession-card i { font-size: 22px; color: var(--text-muted); margin-bottom: 6px; display: block; }
        .profession-card span { font-size: 11px; font-weight: 500; color: var(--text-body); }
        .profession-card.selected {
            border-color: var(--orange);
            background: linear-gradient(135deg, #FFF9F5, #FFF5ED);
        }
        .profession-card.selected i { color: var(--orange); }
        .profession-card.selected span { color: var(--orange-dark); font-weight: 600; }

        /* Loan Purpose Grid */
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

        /* Main Content Styles */
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

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 90px;
        }
        .feature-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 30px 24px;
            text-align: center;
            transition: all 0.35s ease;
        }
        .feature-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: rgba(249,115,22,0.2); }
        .feature-icon {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
        }
        .feature-icon i { font-size: 26px; color: var(--orange); }
        .feature-card h4 { font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 10px; }
        .feature-card p { font-size: 13px; color: var(--text-body); line-height: 1.6; }

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

        .use-cases-section {
            background: var(--section-bg);
            border-radius: var(--radius-xl);
            padding: 60px 52px;
            margin-bottom: 90px;
        }
        .use-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 40px;
        }
        .use-item {
            text-align: center;
            padding: 20px;
            background: var(--white);
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
        }
        .use-item:hover { transform: translateY(-4px); box-shadow: var(--shadow-sm); }
        .use-item i { font-size: 32px; color: var(--orange); margin-bottom: 12px; display: block; }
        .use-item span { font-size: 13px; font-weight: 500; color: var(--navy); }

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
            .features-grid { grid-template-columns: 1fr; }
            .benefits-strip { grid-template-columns: repeat(3,1fr); }
            .use-grid { grid-template-columns: repeat(2, 1fr); }
            .profession-grid { grid-template-columns: repeat(2, 1fr); }
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
            .step-label { display: none; }
            .main-content { padding: 70px 5% 60px; }
            .features-grid { grid-template-columns: 1fr; }
            .elig-docs-section { padding: 40px 22px; }
            .cta-section { padding: 50px 26px; }
            .trust-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
            .benefits-strip { grid-template-columns: 1fr 1fr; }
            .use-grid { grid-template-columns: 1fr; }
            .profession-grid { grid-template-columns: 1fr; }
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
                Business Loan Experts
            </div>
            <h1>
                Business Loans for<br>
                <span class="gradient-text">Self-Employed Professionals</span>
            </h1>
            <p>Self-employed professionals often require financial assistance to expand their practice, manage business operations, upgrade infrastructure, or handle working capital requirements. At LOGINTOLOANS, we provide customized Business Loan solutions specially designed for self-employed professionals to help them achieve their professional and financial goals with confidence.</p>
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
                    <div class="stat-label">Professionals Funded</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">2.5k<span>Cr+</span></div>
                    <div class="stat-label">Loan Disbursed</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24<span>hrs</span></div>
                    <div class="stat-label">Fast Disbursal</div>
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
                    <h3>Get Business Loan Quote</h3>
                    <p>Tailored for Self-Employed Professionals</p>
                </div>

                <form class="loan-form" id="businessLoanForm" onsubmit="handleFormSubmit(event)">
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
                            <div class="step-label">Loan Details</div>
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
                            <label><i class="fas fa-id-card"></i> Professional Qualification</label>
                            <input type="text" id="qualification" placeholder="e.g., MBBS, CA, LLB, B.Arch, MBA" required>
                        </div>

                        <div class="card-select-group">
                            <label><i class="fas fa-briefcase"></i> Professional Category</label>
                            <div class="profession-grid">
                                <div class="profession-card" data-value="doctor" onclick="selectProfession('doctor')">
                                    <i class="fas fa-stethoscope"></i>
                                    <span>Doctor</span>
                                </div>
                                <div class="profession-card" data-value="chartered-accountant" onclick="selectProfession('chartered-accountant')">
                                    <i class="fas fa-chart-line"></i>
                                    <span>CA</span>
                                </div>
                                <div class="profession-card" data-value="lawyer" onclick="selectProfession('lawyer')">
                                    <i class="fas fa-gavel"></i>
                                    <span>Lawyer</span>
                                </div>
                                <div class="profession-card" data-value="architect" onclick="selectProfession('architect')">
                                    <i class="fas fa-drafting-compass"></i>
                                    <span>Architect</span>
                                </div>
                                <div class="profession-card" data-value="engineer" onclick="selectProfession('engineer')">
                                    <i class="fas fa-microchip"></i>
                                    <span>Engineer</span>
                                </div>
                                <div class="profession-card" data-value="consultant" onclick="selectProfession('consultant')">
                                    <i class="fas fa-chart-simple"></i>
                                    <span>Consultant</span>
                                </div>
                                <div class="profession-card" data-value="designer" onclick="selectProfession('designer')">
                                    <i class="fas fa-paintbrush"></i>
                                    <span>Designer</span>
                                </div>
                                <div class="profession-card" data-value="other" onclick="selectProfession('other')">
                                    <i class="fas fa-briefcase"></i>
                                    <span>Other</span>
                                </div>
                            </div>
                            <input type="hidden" id="professionalCategory" value="">
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
                            <label><i class="fas fa-rupee-sign"></i> Annual Business Turnover (₹)</label>
                            <input type="number" id="turnover" placeholder="Your annual practice/business turnover" required>
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

                    <!-- STEP 4 - LOAN DETAILS -->
                    <div class="form-step" id="step4">
                        <div class="card-select-group">
                            <label><i class="fas fa-bullseye"></i> Loan Purpose</label>
                            <div class="purpose-grid">
                                <div class="purpose-card" data-value="office-setup" onclick="selectPurpose('office-setup')">
                                    <i class="fas fa-building"></i>
                                    <span>Office Setup</span>
                                </div>
                                <div class="purpose-card" data-value="equipment" onclick="selectPurpose('equipment')">
                                    <i class="fas fa-laptop"></i>
                                    <span>Equipment Purchase</span>
                                </div>
                                <div class="purpose-card" data-value="working-capital" onclick="selectPurpose('working-capital')">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Working Capital</span>
                                </div>
                                <div class="purpose-card" data-value="expansion" onclick="selectPurpose('expansion')">
                                    <i class="fas fa-expand-arrows-alt"></i>
                                    <span>Business Expansion</span>
                                </div>
                                <div class="purpose-card" data-value="staffing" onclick="selectPurpose('staffing')">
                                    <i class="fas fa-users"></i>
                                    <span>Staff Hiring</span>
                                </div>
                                <div class="purpose-card" data-value="technology" onclick="selectPurpose('technology')">
                                    <i class="fas fa-microchip"></i>
                                    <span>Tech/Software</span>
                                </div>
                            </div>
                            <input type="hidden" id="loanPurpose" value="">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-rupee-sign"></i> Loan Amount Required (₹)</label>
                            <select id="loanAmount" required>
                                <option value="">Select Amount</option>
                                <option value="250000">₹2,50,000</option>
                                <option value="500000">₹5,00,000</option>
                                <option value="1000000">₹10,00,000</option>
                                <option value="2500000">₹25,00,000</option>
                                <option value="5000000">₹50,00,000</option>
                                <option value="10000000">₹1 Cr+</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt"></i> Preferred Tenure</label>
                            <select id="tenure" required>
                                <option value="">Select Tenure</option>
                                <option value="12">12 Months</option>
                                <option value="24">24 Months</option>
                                <option value="36">36 Months</option>
                                <option value="48">48 Months</option>
                                <option value="60">60 Months</option>
                            </select>
                        </div>

                        <div class="multi-buttons">
                            <button type="button" class="btn-back" onclick="prevStep()">
                                <i class="fas fa-arrow-left"></i>
                                <span>Back</span>
                            </button>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-bolt"></i>
                                <span>Get Best Offer</span>
                            </button>
                        </div>
                    </div>

                    <div id="formSuccess" class="success-message">
                        <i class="fas fa-check-circle"></i>
                        Thank you! Our business loan expert will contact you shortly.
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

    <!-- WHAT IS BUSINESS LOAN SECTION -->
    <section class="elig-docs-section reveal">
        <div class="section-tag">What is a Business Loan?</div>
        <h2>Business Loan for<br>Self-Employed Professionals</h2>
        <p style="font-size: 16px; line-height: 1.7; margin-bottom: 30px; color: var(--text-body);">A Business Loan for Self-Employed Professionals is a financing solution offered to independent professionals who run their own practice or business. These loans help professionals manage business expansion, operational expenses, office setup, equipment purchases, and other financial requirements. Loan eligibility is generally based on professional income, business turnover, repayment capacity, and financial stability.</p>
        <div class="elig-grid">
            <div class="elig-card"><h4><i class="fas fa-chart-line"></i> Higher Loan Amounts</h4><p style="font-size: 14px;">Eligible applicants may receive substantial loan amounts depending on their income and business profile.</p></div>
            <div class="elig-card"><h4><i class="fas fa-percent"></i> Competitive Interest Rates</h4><p style="font-size: 14px;">Professional business loans often come with affordable interest rates and flexible repayment terms.</p></div>
            <div class="elig-card"><h4><i class="fas fa-calendar-alt"></i> Flexible Repayment Tenure</h4><p style="font-size: 14px;">Borrowers can choose repayment schedules according to their financial planning and cash flow.</p></div>
            <div class="elig-card"><h4><i class="fas fa-bolt"></i> Quick Loan Processing</h4><p style="font-size: 14px;">Fast approvals and timely disbursement help professionals manage urgent financial requirements efficiently.</p></div>
            <div class="elig-card"><h4><i class="fas fa-file-alt"></i> Minimal Documentation</h4><p style="font-size: 14px;">Simple documentation and easy verification processes make loan applications more convenient.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- BENEFITS SECTION -->
    <section class="reveal" style="margin-bottom: 90px;">
        <div class="section-tag">Benefits</div>
        <h2 style="font-family:'Playfair Display',serif;font-size:clamp(28px,3vw,42px);font-weight:800;color:var(--navy);margin-bottom:40px;">Benefits of Business Loans<br>for Self-Employed Professionals</h2>
        <div class="benefits-strip">
            <div class="benefit-tile"><i class="fas fa-chart-line"></i><h5>Business Expansion</h5><p>Scale your practice</p></div>
            <div class="benefit-tile"><i class="fas fa-tasks"></i><h5>Operational Expenses</h5><p>Manage daily costs</p></div>
            <div class="benefit-tile"><i class="fas fa-building"></i><h5>Office Setup</h5><p>Infrastructure growth</p></div>
            <div class="benefit-tile"><i class="fas fa-microchip"></i><h5>Equipment Purchase</h5><p>Upgrade technology</p></div>
            <div class="benefit-tile"><i class="fas fa-chart-pie"></i><h5>Working Capital</h5><p>Smooth cash flow</p></div>
            <div class="benefit-tile"><i class="fas fa-users"></i><h5>Staff Hiring</h5><p>Build your team</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- COMMON USES SECTION -->
    <section class="use-cases-section reveal">
        <div class="section-tag">Common Uses</div>
        <h2 style="font-family:'Playfair Display',serif;font-size:clamp(26px,3vw,38px);font-weight:800;color:var(--navy);margin-bottom:20px;">How Self-Employed Professionals<br>Use Business Loans</h2>
        <div class="use-grid">
            <div class="use-item"><i class="fas fa-building"></i><span>Office Setup & Infrastructure</span></div>
            <div class="use-item"><i class="fas fa-laptop"></i><span>Purchase of Equipment</span></div>
            <div class="use-item"><i class="fas fa-chart-line"></i><span>Working Capital Management</span></div>
            <div class="use-item"><i class="fas fa-users"></i><span>Staff Salaries & Operations</span></div>
            <div class="use-item"><i class="fas fa-chart-simple"></i><span>Business Expansion Projects</span></div>
            <div class="use-item"><i class="fas fa-microchip"></i><span>Technology & Software Upgrades</span></div>
            <div class="use-item"><i class="fas fa-certificate"></i><span>Professional Certifications</span></div>
            <div class="use-item"><i class="fas fa-ambulance"></i><span>Emergency Business Expenses</span></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- ELIGIBILITY & DOCS -->
    <section class="elig-docs-section reveal">
        <div class="section-tag">Requirements</div>
        <h2>Eligibility &amp; Documents<br>Required for Business Loan</h2>
        <div class="elig-grid">
            <div class="elig-card"><h4><i class="fas fa-user-check"></i> Eligibility Criteria</h4><ul class="check-list"><li><i class="fas fa-check"></i> Self-employed professional</li><li><i class="fas fa-check"></i> Stable income and business operations</li><li><i class="fas fa-check"></i> Minimum professional experience</li><li><i class="fas fa-check"></i> Good credit and repayment history</li><li><i class="fas fa-check"></i> Valid KYC and financial documents</li></ul></div>
            <div class="elig-card"><h4><i class="fas fa-file-alt"></i> Required Documents</h4><ul class="check-list"><li><i class="fas fa-check"></i> Aadhaar Card & PAN Card</li><li><i class="fas fa-check"></i> Address Proof</li><li><i class="fas fa-check"></i> Income Proof & Bank Statements</li><li><i class="fas fa-check"></i> Business Proof & Professional Certificate</li><li><i class="fas fa-check"></i> Passport-size Photographs</li></ul></div>
        </div>
        <div class="section-tag" style="margin-bottom:20px;">Documents Checklist</div>
        <div class="docs-grid">
            <div class="doc-category"><h5><i class="fas fa-id-card"></i> Identity & Address</h5><ul class="doc-list"><li><i class="fas fa-check-circle"></i> Aadhaar Card</li><li><i class="fas fa-check-circle"></i> PAN Card</li><li><i class="fas fa-check-circle"></i> Passport / Voter ID</li><li><i class="fas fa-check-circle"></i> Utility Bills</li></ul></div>
            <div class="doc-category"><h5><i class="fas fa-certificate"></i> Professional Documents</h5><ul class="doc-list"><li><i class="fas fa-check-circle"></i> Professional Qualification Certificate</li><li><i class="fas fa-check-circle"></i> Business Registration Proof</li><li><i class="fas fa-check-circle"></i> Office Address Proof</li><li><i class="fas fa-check-circle"></i> Practice License</li></ul></div>
            <div class="doc-category"><h5><i class="fas fa-file-invoice-dollar"></i> Financial Documents</h5><ul class="doc-list"><li><i class="fas fa-check-circle"></i> Bank Statements (6-12 months)</li><li><i class="fas fa-check-circle"></i> Income Tax Returns</li><li><i class="fas fa-check-circle"></i> Profit & Loss Statements</li><li><i class="fas fa-check-circle"></i> Balance Sheets</li></ul></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- TIPS BEFORE APPLYING -->
    <section class="elig-docs-section reveal">
        <div class="section-tag">Smart Tips</div>
        <h2>Tips Before Applying<br>for a Business Loan</h2>
        <div class="elig-grid">
            <div class="elig-card"><h4><i class="fas fa-lightbulb"></i> Financial Planning</h4><ul class="check-list"><li><i class="fas fa-check"></i> Assess your repayment capacity carefully</li><li><i class="fas fa-check"></i> Compare interest rates and loan terms</li><li><i class="fas fa-check"></i> Maintain a healthy credit score</li><li><i class="fas fa-check"></i> Keep financial and business records updated</li><li><i class="fas fa-check"></i> Understand processing fees and loan conditions</li><li><i class="fas fa-check"></i> Borrow according to actual business requirements</li></ul></div>
            <div class="elig-card"><h4><i class="fas fa-chart-line"></i> Professional Growth</h4><ul class="check-list"><li><i class="fas fa-check"></i> Plan loan utilization for business growth</li><li><i class="fas fa-check"></i> Consider seasonal business cycles</li><li><i class="fas fa-check"></i> Maintain organized expense records</li><li><i class="fas fa-check"></i> Review loan terms carefully</li><li><i class="fas fa-check"></i> Seek professional advice if needed</li></ul></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- FAQ SECTION -->
    <section class="faq-section reveal">
        <div class="section-tag">FAQs</div>
        <h2>Frequently Asked<br>Questions</h2>
        <div class="faq-grid">
            <div class="faq-item"><h4><i class="fas fa-question-circle"></i> Who can apply for a business loan?</h4><p>Self-employed professionals including doctors, CAs, lawyers, architects, engineers, consultants, designers, and other independent professionals.</p></div>
            <div class="faq-item"><h4><i class="fas fa-rupee-sign"></i> What is the maximum loan amount?</h4><p>Loan amounts vary based on professional income, business turnover, and repayment capacity.</p></div>
            <div class="faq-item"><h4><i class="fas fa-clock"></i> How long does approval take?</h4><p>Fast approvals with minimal documentation and quick processing.</p></div>
            <div class="faq-item"><h4><i class="fas fa-chart-line"></i> What is the repayment tenure?</h4><p>Flexible repayment tenures ranging from 12 to 60 months as per your financial planning.</p></div>
            <div class="faq-item"><h4><i class="fas fa-file-alt"></i> What documents are required?</h4><p>KYC documents, professional qualification certificate, bank statements, IT returns, and business proof.</p></div>
            <div class="faq-item"><h4><i class="fas fa-chart-line"></i> Is a good credit score important?</h4><p>Yes, a healthy credit score improves approval chances and may secure better interest rates.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- WHY CHOOSE LOGINTO LOANS -->
    <div class="insight-banner reveal">
        <div class="insight-text">
            <div class="section-tag">Why LOGINTOLOANS</div>
            <h3>Transparent, Reliable & Efficient Business Loan Solutions</h3>
            <p>At LOGINTOLOANS, we focus on simplifying the borrowing process for professionals by offering transparent and customer-friendly financial assistance. We help self-employed professionals compare and select suitable business loan options according to their financial needs with easy online application, fast approvals, flexible repayment options, and secure reliable process.</p>
        </div>
        <div class="insight-cta">
            <a href="#apply-form" onclick="document.getElementById('apply-form').scrollIntoView({behavior:'smooth'}); return false;">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Apply for Business Loan</span>
            </a>
        </div>
    </div>

    <!-- CTA SECTION -->
    <div class="cta-section reveal">
        <div class="cta-inner">
            <div class="cta-eyebrow">Grow Your Professional Practice</div>
            <h3>Business Loans for<br>Self-Employed Professionals</h3>
            <p>Business Loans for Self-Employed Professionals provide valuable financial support for managing business operations, expanding professional practice, and achieving long-term growth goals. With flexible repayment options, competitive interest rates, and quick processing, these loans help professionals maintain financial stability and business continuity.</p>
            <div class="cta-buttons">
                <a href="#apply-form" class="cta-button" onclick="document.getElementById('apply-form').scrollIntoView({behavior:'smooth'}); return false;">
                    <i class="fas fa-bolt"></i>
                    <span>Apply for Business Loan</span>
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
    let selectedProfession = '';
    let selectedPurpose = '';
    let currentStep = 0;
    const steps = ['step1', 'step2', 'step3', 'step4'];
    const stepIndicators = ['step1Indicator', 'step2Indicator', 'step3Indicator', 'step4Indicator'];

    function selectProfession(value) {
        selectedProfession = value;
        document.getElementById('professionalCategory').value = value;
        document.querySelectorAll('.profession-card').forEach(card => {
            card.classList.remove('selected');
            if (card.getAttribute('data-value') === value) card.classList.add('selected');
        });
    }

    function selectPurpose(value) {
        selectedPurpose = value;
        document.getElementById('loanPurpose').value = value;
        document.querySelectorAll('.purpose-card').forEach(card => {
            card.classList.remove('selected');
            if (card.getAttribute('data-value') === value) card.classList.add('selected');
        });
    }

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
                if (stepNum && index < currentStep) stepNum.innerHTML = '<i class="fas fa-check"></i>';
            } else {
                element.classList.remove('active', 'completed');
                const stepNum = element.querySelector('.step-number');
                if (stepNum) stepNum.innerHTML = (index + 1).toString();
            }
        });
    }

    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            document.getElementById(step).classList.toggle('active', index === stepIndex);
        });
        currentStep = stepIndex;
        updateStepIndicator();
    }

    function nextStep() {
        if (currentStep < steps.length - 1) {
            if (currentStep === 0 && !document.getElementById('fullName').value) { alert('Please enter your full name'); return; }
            if (currentStep === 1) {
                const mobile = document.getElementById('mobile').value;
                const email = document.getElementById('email').value;
                if (!mobile || mobile.length < 10) { alert('Please enter a valid 10-digit mobile number'); return; }
                if (!email || !email.includes('@')) { alert('Please enter a valid email address'); return; }
            }
            if (currentStep === 2) {
                if (!document.getElementById('qualification').value) { alert('Please enter your professional qualification'); return; }
                if (!selectedProfession) { alert('Please select your professional category'); return; }
                if (!document.getElementById('experience').value) { alert('Please select years of experience'); return; }
                if (!document.getElementById('turnover').value) { alert('Please enter annual turnover'); return; }
            }
            showStep(currentStep + 1);
        }
    }

    function prevStep() { if (currentStep > 0) showStep(currentStep - 1); }

    function handleFormSubmit(event) {
        event.preventDefault();
        const fullName = document.getElementById('fullName').value;
        const mobile = document.getElementById('mobile').value;
        const email = document.getElementById('email').value;
        const qualification = document.getElementById('qualification').value;
        const professionalCategory = document.getElementById('professionalCategory').value;
        const experience = document.getElementById('experience').value;
        const turnover = document.getElementById('turnover').value;
        const loanPurpose = document.getElementById('loanPurpose').value;
        const loanAmount = document.getElementById('loanAmount').value;
        const tenure = document.getElementById('tenure').value;

        if (!fullName || !mobile || !email || !qualification || !professionalCategory || !experience || !turnover || !loanPurpose || !loanAmount || !tenure) {
            alert('Please fill in all fields');
            return;
        }
        if (mobile.length < 10) { alert('Please enter a valid 10-digit mobile number'); return; }

        const successDiv = document.getElementById('formSuccess');
        successDiv.style.display = 'flex';
        console.log('Business Loan Application:', { fullName, mobile, email, qualification, professionalCategory, experience, turnover, loanPurpose, loanAmount, tenure });

        document.getElementById('businessLoanForm').reset();
        selectedProfession = ''; selectedPurpose = '';
        document.querySelectorAll('.profession-card, .purpose-card').forEach(el => el.classList.remove('selected'));
        showStep(0);
        setTimeout(() => { successDiv.style.display = 'none'; }, 5000);
    }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
    }, { threshold: 0.08 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    const vid = document.querySelector('.hero-video-wrap video');
    if (vid) {
        vid.addEventListener('error', () => {
            vid.closest('.hero-video-wrap').style.background = 'linear-gradient(135deg, #060F22 0%, #122F66 50%, #0A1E40 100%)';
        });
    }
</script>
</body>
</html>