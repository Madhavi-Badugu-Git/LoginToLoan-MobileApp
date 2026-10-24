<?php
// FILE: car-loan.php
include('../../includes/dbConfig.php');
include('../../includes/navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LOGINTOLOANS | Car Loan – Drive Your Dream Car</title>
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
            --section-bg: #F7FAFF;
            --shadow-sm: 0 2px 12px rgba(7,27,62,0.06);
            --shadow-md: 0 8px 32px rgba(7,27,62,0.10);
            --shadow-lg: 0 24px 64px rgba(7,27,62,0.13);
            --shadow-xl: 0 40px 80px rgba(7,27,62,0.18);
            --radius-sm: 12px;
            --radius-md: 20px;
            --radius-lg: 32px;
            --radius-xl: 48px;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--text-body);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.4;
        }

        /* ─── HERO ─── */
        .hero-premium {
            min-height: 100vh;
            background: linear-gradient(135deg, #071B3E 0%, #0A2540 50%, #0D2D5F 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 110px 6% 100px;
            margin-top: -5px;
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

        .hero-lines {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
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
            z-index: 2;
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
        .hero-eyebrow .pulse-dot { width: 8px; height: 8px; background: #10B981; border-radius: 50%; animation: pulse-green 2s ease-in-out infinite; }
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

        .trust-stats { display: flex; align-items: center; gap: 40px; flex-wrap: wrap; }
        .stat-block { display: flex; align-items: baseline; gap: 8px; flex-wrap: wrap; }
        .stat-number { font-family: 'Playfair Display', serif; font-size: 34px; font-weight: 800; color: var(--white); line-height: 1; }
        .stat-number span { color: var(--orange); font-size: 28px; }
        .stat-label { font-size: 13px; color: rgba(255,255,255,0.5); font-weight: 400; letter-spacing: 0.03em; }
        .stat-divider { width: 1px; height: 45px; background: rgba(255,255,255,0.15); }

        /* ─── PREMIUM FORM CARD ─── */
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
            padding: 16px 12px;
            display: flex;
            align-items: center;
            gap: 14px;
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
            width: 48px; height: 48px;
            background: linear-gradient(135deg, #FEF3E8, #FDE8D5);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-option-icon i { font-size: 24px; color: var(--orange); }
        .card-option-content { flex: 1; }
        .card-option-title { font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 4px; }
        .card-option-desc { font-size: 11px; color: var(--text-muted); }
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

        /* Car Type Card Selection */
        .car-type-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .car-type-card {
            background: #FAFBFD;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .car-type-card i { font-size: 24px; color: var(--text-muted); margin-bottom: 8px; display: block; }
        .car-type-card span { font-size: 12px; font-weight: 500; color: var(--text-body); }
        .car-type-card.selected {
            border-color: var(--orange);
            background: linear-gradient(135deg, #FFF9F5, #FFF5ED);
        }
        .car-type-card.selected i { color: var(--orange); }
        .car-type-card.selected span { color: var(--orange-dark); font-weight: 600; }

        /* Rest of existing styles (main content sections) - keeping original */
        .main-content { max-width: 1360px; margin: 0 auto; padding: 100px 6% 80px; }
        .section-tag { display: inline-flex; align-items: center; gap: 8px; font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--orange); margin-bottom: 14px; }
        .section-tag::before { content: ''; width: 24px; height: 2px; background: var(--orange); border-radius: 2px; }
        .why-section { margin-bottom: 90px; }
        .why-section > h2 { font-family: 'Playfair Display', serif; font-size: clamp(28px, 3vw, 42px); font-weight: 800; color: var(--navy); margin-bottom: 48px; letter-spacing: -0.02em; }
        .why-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
        .why-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 30px 28px; transition: all 0.35s ease; position: relative; overflow: hidden; }
        .why-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--orange), var(--gold)); transform: scaleX(0); transform-origin: left; transition: transform 0.35s ease; }
        .why-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: rgba(249,115,22,0.2); }
        .why-card:hover::after { transform: scaleX(1); }
        .why-icon { width: 52px; height: 52px; background: linear-gradient(135deg, #FEF3E8, #FDE8D5); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; }
        .why-icon i { font-size: 22px; color: var(--orange); }
        .why-card h4 { font-size: 16px; font-weight: 700; color: var(--navy); margin-bottom: 10px; }
        .why-card p { font-size: 14px; color: var(--text-body); line-height: 1.65; font-weight: 300; }

        .loan-types-section { margin-bottom: 90px; }
        .loan-types-section > h2 { font-family: 'Playfair Display', serif; font-size: clamp(28px, 3vw, 42px); font-weight: 800; color: var(--navy); margin-bottom: 48px; letter-spacing: -0.02em; }
        .loan-types-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .loan-type-card { background: var(--navy); border-radius: var(--radius-lg); padding: 40px 32px; position: relative; overflow: hidden; transition: transform 0.35s ease; }
        .loan-type-card:nth-child(2) { background: linear-gradient(135deg, var(--orange-dark), var(--orange)); }
        .loan-type-card:nth-child(3) { background: linear-gradient(135deg, #163A78, #0D2D5F); }
        .loan-type-card::before { content: ''; position: absolute; top: -60px; right: -60px; width: 180px; height: 180px; background: rgba(255,255,255,0.06); border-radius: 50%; }
        .loan-type-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-xl); }
        .loan-type-num { font-family: 'Playfair Display', serif; font-size: 48px; font-weight: 900; color: rgba(255,255,255,0.1); line-height: 1; margin-bottom: 16px; }
        .loan-type-icon { width: 54px; height: 54px; background: rgba(255,255,255,0.12); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .loan-type-icon i { font-size: 22px; color: var(--white); }
        .loan-type-card h4 { font-size: 20px; font-weight: 700; color: var(--white); margin-bottom: 12px; }
        .loan-type-card p { font-size: 14px; color: rgba(255,255,255,0.65); line-height: 1.65; font-weight: 300; }

        .two-col-section { display: grid; grid-template-columns: 1fr 1fr; gap: 28px; margin-bottom: 90px; }
        .info-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 40px 36px; transition: all 0.35s ease; }
        .info-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); }
        .info-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--navy), var(--orange)); }
        .info-card { position: relative; overflow: hidden; }
        .info-card h3 { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 800; color: var(--navy); margin-bottom: 28px; display: flex; align-items: center; gap: 12px; }
        .info-card h3 i { color: var(--orange); font-size: 20px; }
        .check-list { list-style: none; }
        .check-list li { display: flex; align-items: flex-start; gap: 12px; font-size: 14px; color: var(--text-body); line-height: 1.6; padding: 10px 0; border-bottom: 1px solid var(--border); font-weight: 300; }
        .check-list li:last-child { border-bottom: none; }
        .check-list li i { color: var(--orange); font-size: 11px; margin-top: 4px; flex-shrink: 0; }

        .elig-docs-section { background: var(--section-bg); border-radius: var(--radius-xl); padding: 60px 52px; margin-bottom: 90px; border: 1px solid var(--border); position: relative; overflow: hidden; }
        .elig-docs-section::before { content: ''; position: absolute; top: -100px; right: -100px; width: 350px; height: 350px; background: radial-gradient(circle, rgba(249,115,22,0.06) 0%, transparent 70%); border-radius: 50%; }
        .elig-docs-section > h2 { font-family: 'Playfair Display', serif; font-size: clamp(26px, 3vw, 38px); font-weight: 800; color: var(--navy); margin-bottom: 48px; letter-spacing: -0.02em; }
        .elig-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 48px; }
        .elig-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 28px 26px; }
        .elig-card h4 { font-size: 15px; font-weight: 700; color: var(--navy); margin-bottom: 18px; display: flex; align-items: center; gap: 10px; }
        .elig-card h4 i { color: var(--orange); }
        .docs-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .doc-group { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 24px 22px; }
        .doc-group h5 { font-size: 13px; font-weight: 700; color: var(--navy); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; letter-spacing: 0.03em; }
        .doc-group h5 i { color: var(--orange); font-size: 12px; }
        .doc-group ul { list-style: none; }
        .doc-group ul li { font-size: 13px; color: var(--text-body); padding: 5px 0; display: flex; align-items: center; gap: 8px; font-weight: 300; border-bottom: 1px dashed var(--border); }
        .doc-group ul li:last-child { border-bottom: none; }
        .doc-group ul li::before { content: ''; width: 5px; height: 5px; background: var(--orange); border-radius: 50%; flex-shrink: 0; }

        .process-section { margin-bottom: 90px; }
        .process-section > h2 { font-family: 'Playfair Display', serif; font-size: clamp(28px, 3vw, 42px); font-weight: 800; color: var(--navy); margin-bottom: 52px; letter-spacing: -0.02em; }
        .steps-timeline { display: flex; gap: 0; position: relative; }
        .steps-timeline::before { content: ''; position: absolute; top: 38px; left: 38px; right: 38px; height: 2px; background: linear-gradient(90deg, var(--orange), var(--gold), var(--orange)); z-index: 0; }
        .step-item-custom { flex: 1; text-align: center; position: relative; z-index: 1; padding: 0 12px; }
        .step-num-custom { width: 76px; height: 76px; background: var(--navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 900; color: var(--white); border: 4px solid var(--white); box-shadow: 0 0 0 2px var(--orange); transition: all 0.35s ease; }
        .step-item-custom:hover .step-num-custom { background: var(--orange); transform: scale(1.08); }
        .step-item-custom h4 { font-size: 14px; font-weight: 700; color: var(--navy); margin-bottom: 8px; }
        .step-item-custom p { font-size: 12.5px; color: var(--text-muted); line-height: 1.55; font-weight: 300; }

        .faq-section { margin-bottom: 90px; }
        .faq-section > h2 { font-family: 'Playfair Display', serif; font-size: clamp(28px, 3vw, 42px); font-weight: 800; color: var(--navy); margin-bottom: 40px; letter-spacing: -0.02em; }
        .faq-list { display: flex; flex-direction: column; gap: 14px; }
        .faq-item { border: 1px solid var(--border); border-radius: var(--radius-md); overflow: hidden; transition: border-color 0.25s; }
        .faq-item.open { border-color: rgba(249,115,22,0.3); }
        .faq-q { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 22px 28px; cursor: pointer; background: var(--white); transition: background 0.25s; }
        .faq-q:hover { background: var(--section-bg); }
        .faq-q span { font-size: 15px; font-weight: 600; color: var(--navy); }
        .faq-toggle { width: 32px; height: 32px; background: var(--section-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.3s; }
        .faq-toggle i { font-size: 12px; color: var(--orange); transition: transform 0.3s; }
        .faq-item.open .faq-toggle { background: var(--orange); }
        .faq-item.open .faq-toggle i { color: var(--white); transform: rotate(180deg); }
        .faq-a { max-height: 0; overflow: hidden; transition: max-height 0.4s ease; background: var(--section-bg); }
        .faq-item.open .faq-a { max-height: 200px; }
        .faq-a-inner { padding: 0 28px 22px; font-size: 14px; color: var(--text-body); line-height: 1.7; font-weight: 300; }

        .section-divider { display: flex; align-items: center; gap: 24px; margin: 0 0 80px; }
        .section-divider::before, .section-divider::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, transparent, var(--border), transparent); }
        .divider-diamond { width: 8px; height: 8px; background: var(--orange); transform: rotate(45deg); border-radius: 1px; }

        .cta-section { background: var(--navy); border-radius: var(--radius-xl); padding: 80px 60px; text-align: center; position: relative; overflow: hidden; }
        .cta-section::before { content: ''; position: absolute; top: -150px; right: -150px; width: 500px; height: 500px; background: radial-gradient(circle, rgba(249,115,22,0.15) 0%, transparent 70%); border-radius: 50%; }
        .cta-inner { position: relative; z-index: 2; }
        .cta-eyebrow { display: inline-flex; align-items: center; gap: 8px; background: rgba(249,115,22,0.15); border: 1px solid rgba(249,115,22,0.25); color: var(--orange-light); font-size: 11px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; padding: 6px 18px; border-radius: 50px; margin-bottom: 24px; }
        .cta-section h3 { font-family: 'Playfair Display', serif; font-size: clamp(28px, 3.5vw, 46px); color: var(--white); margin-bottom: 18px; font-weight: 900; letter-spacing: -0.02em; }
        .cta-section p { font-size: 16px; color: rgba(255,255,255,0.55); margin-bottom: 40px; font-weight: 300; }
        .cta-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
        .cta-button { background: var(--white); color: var(--navy); padding: 18px 48px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 16px; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 10px; }
        .cta-button:hover { transform: scale(1.05) translateY(-2px); background: var(--cream); }
        .cta-button-outline { background: transparent; color: var(--white); padding: 18px 40px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 15px; border: 1.5px solid rgba(255,255,255,0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; }
        .cta-button-outline:hover { border-color: rgba(255,255,255,0.7); background: rgba(255,255,255,0.08); }

        .reveal { opacity: 0; transform: translateY(32px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        .hero-left { animation: fadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .hero-right { animation: fadeUp 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.18s both; }

        @media (max-width: 1100px) {
            .hero-content { grid-template-columns: 1fr; gap: 50px; }
            .hero-left p { max-width: 100%; }
            .floating-card { max-width: 560px; margin: 0 auto; }
            .loan-types-grid { grid-template-columns: 1fr; }
            .steps-timeline { flex-wrap: wrap; gap: 40px; }
            .steps-timeline::before { display: none; }
            .step-item-custom { flex: 0 0 calc(50% - 20px); }
        }
        @media (max-width: 900px) {
            .two-col-section { grid-template-columns: 1fr; }
            .elig-grid { grid-template-columns: 1fr; }
            .docs-grid { grid-template-columns: 1fr 1fr; }
            .car-type-options { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .hero-premium { padding: 100px 5% 70px; }
            .floating-card { padding: 28px 22px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
            .card-options { grid-template-columns: 1fr; }
            .step-label { display: none; }
            .main-content { padding: 70px 5% 60px; }
            .why-grid { grid-template-columns: 1fr; }
            .elig-docs-section { padding: 40px 24px; }
            .cta-section { padding: 50px 26px; }
            .trust-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
            .stat-divider { display: none; }
            .docs-grid { grid-template-columns: 1fr; }
            .step-item-custom { flex: 0 0 100%; }
        }
        @media (max-width: 480px) {
            .hero-left h1 { font-size: 32px; }
            .hero-buttons { flex-direction: column; }
            .btn-primary-hero, .btn-outline-hero { justify-content: center; }
            .loan-types-grid { grid-template-columns: 1fr; }
            .cta-buttons { flex-direction: column; align-items: center; }
        }
    </style>
</head>
<body>

<!-- ═══════════ PREMIUM HERO SECTION ═══════════ -->
<div class="hero-premium">
    <div class="hero-orb-1"></div>
    <div class="hero-orb-2"></div>
    <div class="hero-lines"></div>
    <div class="hero-arc"></div>

    <div class="hero-content" style="margin-top:-80px";>
        <div class="hero-left">
            <div class="hero-eyebrow">
                <span class="pulse-dot"></span>
                Trusted by 50,000+ Customers
            </div>

            <h1>
                Drive Your<br>
                Dream Car with<br>
                <span class="gradient-text">Easy Financing</span>
            </h1>

            <p>New or pre-owned — LOGINTOLOANS helps you compare the best car loan offers from trusted banks and financial institutions with affordable EMIs, fast approvals, and zero hidden charges.</p>

            <div class="hero-buttons">
                <a href="#apply-form" class="btn-primary-hero">
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

        <!-- PREMIUM FORM CARD -->
        <div class="hero-right" id="apply-form">
            <div class="floating-card">
                <div class="card-header">
                    <div class="card-badge">
                        <i class="fas fa-bolt"></i>
                        <span>Instant Approval</span>
                    </div>
                    <h3>Get Car Loan Quote</h3>
                    <p>New &amp; Used Cars • Competitive Rates</p>
                </div>

                <form class="loan-form" id="carLoanForm" onsubmit="handleFormSubmit(event)">
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

                    <!-- STEP 3 - FINANCIAL -->
                    <div class="form-step" id="step3">
                        <!-- Car Type Selection - Card Style -->
                        <div class="card-select-group">
                            <label><i class="fas fa-car"></i> Car Type</label>
                            <div class="car-type-options">
                                <div class="car-type-card" data-value="new" onclick="selectCarType('new')">
                                    <i class="fas fa-car-side"></i>
                                    <span>New Car</span>
                                </div>
                                <div class="car-type-card" data-value="used" onclick="selectCarType('used')">
                                    <i class="fas fa-exchange-alt"></i>
                                    <span>Used Car</span>
                                </div>
                                <div class="car-type-card" data-value="loan-against" onclick="selectCarType('loan-against')">
                                    <i class="fas fa-key"></i>
                                    <span>Loan Against Car</span>
                                </div>
                            </div>
                            <input type="hidden" id="carType" name="carType" value="">
                        </div>

                        <!-- Employment Type - Card Style -->
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
                                    <option value="100000">₹1,00,000</option>
                                    <option value="300000">₹3,00,000</option>
                                    <option value="500000">₹5,00,000</option>
                                    <option value="1000000">₹10,00,000</option>
                                    <option value="1500000">₹15,00,000</option>
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

    <!-- WHY CHOOSE -->
    <section class="why-section reveal">
        <div class="section-tag">Why LOGINTOLOANS</div>
        <h2>Everything You Need for<br>a Smooth Car Loan Experience</h2>
        <div class="why-grid">
            <div class="why-card"><div class="why-icon"><i class="fas fa-bolt"></i></div><h4>Quick &amp; Hassle-Free Process</h4><p>Apply online without visiting multiple banks. Our simplified process saves your time and effort.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-percentage"></i></div><h4>Competitive Interest Rates</h4><p>Get attractive interest rates from leading lenders based on your eligibility and credit profile.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-sliders-h"></i></div><h4>Flexible Loan Amount</h4><p>Finance up to a high percentage of your car's on-road price depending on lender policies.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-calendar-alt"></i></div><h4>Easy EMI Repayment Options</h4><p>Choose repayment tenures that suit your monthly budget and long-term financial goals.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-check-circle"></i></div><h4>Fast Loan Approval</h4><p>Get faster loan approvals with simple documentation and hassle-free verification assistance.</p></div>
            <div class="why-card"><div class="why-icon"><i class="fas fa-eye"></i></div><h4>Transparent Process</h4><p>No hidden charges. Clear information on interest rates, processing fees, and repayment terms.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- LOAN TYPES -->
    <section class="loan-types-section reveal">
        <div class="section-tag">Loan Variants</div>
        <h2>Types of Car Loans<br>We Help With</h2>
        <div class="loan-types-grid">
            <div class="loan-type-card"><div class="loan-type-num">01</div><div class="loan-type-icon"><i class="fas fa-car-side"></i></div><h4>New Car Loan</h4><p>Finance your brand-new car with attractive interest rates and flexible EMI options.</p></div>
            <div class="loan-type-card"><div class="loan-type-num">02</div><div class="loan-type-icon"><i class="fas fa-exchange-alt"></i></div><h4>Used Car Loan</h4><p>Get funding support for purchasing certified pre-owned vehicles at competitive rates.</p></div>
            <div class="loan-type-card"><div class="loan-type-num">03</div><div class="loan-type-icon"><i class="fas fa-key"></i></div><h4>Loan Against Car</h4><p>Unlock funds using your existing car as collateral while continuing to use your vehicle.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- FEATURES + BENEFITS -->
    <div class="two-col-section reveal">
        <div class="info-card"><h3><i class="fas fa-star"></i> Features of Car Loan</h3><ul class="check-list"><li><i class="fas fa-check"></i> Loan amount based on income and eligibility</li><li><i class="fas fa-check"></i> Flexible repayment tenure</li><li><i class="fas fa-check"></i> Affordable monthly EMIs</li><li><i class="fas fa-check"></i> Online application support</li><li><i class="fas fa-check"></i> Minimal documentation required</li><li><i class="fas fa-check"></i> Quick eligibility check</li></ul></div>
        <div class="info-card"><h3><i class="fas fa-gift"></i> Benefits of Taking a Car Loan</h3><ul class="check-list"><li><i class="fas fa-check"></i> Buy your dream car without financial pressure</li><li><i class="fas fa-check"></i> Preserve your savings for future investments</li><li><i class="fas fa-check"></i> Choose a repayment period matching your comfort</li><li><i class="fas fa-check"></i> Timely repayment can improve your credit score</li><li><i class="fas fa-check"></i> Easy availability for eligible applicants</li><li><i class="fas fa-check"></i> Transparent terms with no hidden charges</li></ul></div>
    </div>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- ELIGIBILITY & DOCS -->
    <section class="elig-docs-section reveal">
        <div class="section-tag">Requirements</div>
        <h2>Eligibility &amp; Documents<br>Required for Car Loan</h2>
        <div class="elig-grid">
            <div class="elig-card"><h4><i class="fas fa-user-tie"></i> For Salaried Individuals</h4><ul class="check-list"><li><i class="fas fa-check"></i> Stable monthly income</li><li><i class="fas fa-check"></i> Minimum work experience as per lender criteria</li><li><i class="fas fa-check"></i> Good repayment history</li><li><i class="fas fa-check"></i> Age within lender criteria</li></ul></div>
            <div class="elig-card"><h4><i class="fas fa-briefcase"></i> For Self-Employed Individuals</h4><ul class="check-list"><li><i class="fas fa-check"></i> Stable business income</li><li><i class="fas fa-check"></i> Business continuity proof</li><li><i class="fas fa-check"></i> Financial documents and bank statements</li><li><i class="fas fa-check"></i> Satisfactory credit profile</li></ul></div>
        </div>
        <div class="section-tag" style="margin-bottom:20px;">Documents Checklist</div>
        <div class="docs-grid">
            <div class="doc-group"><h5><i class="fas fa-id-card"></i> Identity Proof</h5><ul><li>Aadhaar Card</li><li>PAN Card</li><li>Passport</li><li>Voter ID</li></ul></div>
            <div class="doc-group"><h5><i class="fas fa-home"></i> Address Proof</h5><ul><li>Utility Bills</li><li>Rental Agreement</li><li>Driving License</li></ul></div>
            <div class="doc-group"><h5><i class="fas fa-file-invoice-dollar"></i> Income Proof</h5><ul><li>Salary slips (salaried)</li><li>Form 16 (salaried)</li><li>ITR (self-employed)</li><li>Business proof</li><li>Bank statements</li></ul></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- PROCESS STEPS -->
    <section class="process-section reveal">
        <div class="section-tag">How It Works</div>
        <h2>Apply for a Car Loan in<br>5 Simple Steps</h2>
        <div class="steps-timeline">
            <div class="step-item-custom"><div class="step-num-custom">1</div><h4>Fill Online Application</h4><p>Submit your basic details through our simple online form in minutes.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">2</div><h4>Eligibility Verification</h4><p>Our team checks your profile and connects you with suitable lenders.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">3</div><h4>Compare Loan Offers</h4><p>Review available offers, interest rates, and repayment options.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">4</div><h4>Document Submission</h4><p>Upload or share required documents for quick verification.</p></div>
            <div class="step-item-custom"><div class="step-num-custom">5</div><h4>Approval &amp; Disbursement</h4><p>Loan amount processed as per lender guidelines after approval.</p></div>
        </div>
    </section>

    <div class="section-divider reveal"><div class="divider-diamond"></div></div>

    <!-- FAQ -->
    <section class="faq-section reveal">
        <div class="section-tag">FAQs</div>
        <h2>Frequently Asked<br>Questions</h2>
        <div class="faq-list">
            <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)"><span>Is it possible to avail a car loan with a low credit score?</span><div class="faq-toggle"><i class="fas fa-chevron-down"></i></div></div><div class="faq-a"><div class="faq-a-inner">Yes, some lenders may offer car loans based on income stability and other eligibility factors even if your credit score is not perfect. Our team helps identify lenders best suited to your profile.</div></div></div>
            <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)"><span>What is the maximum tenure for a car loan?</span><div class="faq-toggle"><i class="fas fa-chevron-down"></i></div></div><div class="faq-a"><div class="faq-a-inner">Loan tenure depends on the lender and vehicle type. Typically tenures range from 12 to 84 months. Our team will help you select a tenure that suits your monthly repayment comfort.</div></div></div>
            <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)"><span>Can I finance a used car?</span><div class="faq-toggle"><i class="fas fa-chevron-down"></i></div></div><div class="faq-a"><div class="faq-a-inner">Yes, used car financing options are available through selected lenders. We help you compare offers for certified pre-owned vehicles with competitive interest rates.</div></div></div>
            <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)"><span>Is a down payment required?</span><div class="faq-toggle"><i class="fas fa-chevron-down"></i></div></div><div class="faq-a"><div class="faq-a-inner">Down payment requirements vary based on lender policies and vehicle value. Some lenders offer high LTV (Loan-to-Value) ratios, minimising the upfront amount you need to pay.</div></div></div>
            <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)"><span>How long does loan approval take?</span><div class="faq-toggle"><i class="fas fa-chevron-down"></i></div></div><div class="faq-a"><div class="faq-a-inner">Approval timelines depend on documentation completeness and lender verification processes. With proper documentation, many approvals are completed within a few working days.</div></div></div>
        </div>
    </section>

    <!-- CTA -->
    <div class="cta-section reveal">
        <div class="cta-inner">
            <div class="cta-eyebrow">Start Today</div>
            <h3>Ready to Drive Home<br>Your Dream Car?</h3>
            <p>Compare offers, enjoy affordable EMIs, and experience a smooth loan process designed around your needs</p>
            <div class="cta-buttons">
                <a href="#apply-form" class="cta-button" onclick="document.getElementById('apply-form').scrollIntoView({behavior:'smooth'}); return false;">
                    <i class="fas fa-bolt"></i>
                    <span>Apply for Car Loan</span>
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
    let selectedCarType = '';
    let selectedEmployment = '';

    // Car Type Selection
    function selectCarType(value) {
        selectedCarType = value;
        document.getElementById('carType').value = value;
        
        const options = document.querySelectorAll('.car-type-card');
        options.forEach(option => {
            option.classList.remove('selected');
            if (option.getAttribute('data-value') === value) {
                option.classList.add('selected');
            }
        });
    }

    // Employment Type Selection
    function selectEmployment(value) {
        selectedEmployment = value;
        document.getElementById('employmentType').value = value;
        
        const options = document.querySelectorAll('.card-option');
        options.forEach(option => {
            option.classList.remove('selected');
            if (option.getAttribute('data-value') === value) {
                option.classList.add('selected');
            }
        });
    }

    // Form Steps
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
                if (!selectedCarType) { alert('Please select car type'); return; }
                if (!selectedEmployment) { alert('Please select employment type'); return; }
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
        const carType = document.getElementById('carType').value;
        const employmentType = document.getElementById('employmentType').value;
        const loanAmount = document.getElementById('loanAmount').value;
        const monthlyIncome = document.getElementById('monthlyIncome').value;

        if (!fullName || !mobile || !email || !carType || !employmentType || !loanAmount || !monthlyIncome) {
            alert('Please fill in all fields');
            return;
        }
        if (mobile.length < 10) { alert('Please enter a valid 10-digit mobile number'); return; }

        const successDiv = document.getElementById('formSuccess');
        successDiv.style.display = 'flex';
        console.log('Car Loan Application Submitted:', { fullName, mobile, email, carType, employmentType, loanAmount, monthlyIncome });

        // Reset form
        document.getElementById('carLoanForm').reset();
        selectedCarType = '';
        selectedEmployment = '';
        document.querySelectorAll('.car-type-card, .card-option').forEach(el => el.classList.remove('selected'));
        showStep(0);

        setTimeout(() => { successDiv.style.display = 'none'; }, 5000);
    }

    // FAQ Accordion
    function toggleFaq(el) {
        const item = el.closest('.faq-item');
        const isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
        if (!isOpen) item.classList.add('open');
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
    }, { threshold: 0.10 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
</body>
</html>