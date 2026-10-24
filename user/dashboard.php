<?php

include('../includes/dbConfig.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ===== PROFILE COMPLETENESS CHECK – FIXED =====
$user_id = $_SESSION['user_id'];
// Now query the loan_requests table (where the data is actually stored)
$loan_check = $conn->query("SELECT dob, gender, aadhaar_number, pan_number, annual_income FROM loan_requests WHERE user_id = $user_id");
$loan_data = $loan_check->fetch_assoc();

$profile_complete = (
    !empty($loan_data['dob']) &&
    !empty($loan_data['gender']) &&
    !empty($loan_data['aadhaar_number']) &&
    !empty($loan_data['pan_number']) &&
    $loan_data['annual_income'] > 0
);

// If the user has no record in loan_requests yet, the query returns false,
// so $profile_complete will be false anyway – perfect.

$fullname      = htmlspecialchars($_SESSION['fullname']      ?? 'User');
$email         = htmlspecialchars($_SESSION['email']         ?? 'user@email.com');
$phone         = htmlspecialchars($_SESSION['phone']         ?? '+91 98765 43210');
$role          = htmlspecialchars($_SESSION['role']          ?? 'user');
$profile_image = htmlspecialchars($_SESSION['profile_image'] ?? '');
$firstname     = htmlspecialchars(explode(' ', trim($_SESSION['fullname'] ?? 'User'))[0]);

$offers = [];
$baseImageUrl = 'https://crm.logintoloans.com/';

$result = $conn->query(
   "SELECT id, image_path, category FROM tbl_loan_offers WHERE status = 1 ORDER BY id DESC"
);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['image_path'])) {
            $imageUrl = filter_var($row['image_path'], FILTER_VALIDATE_URL)
                ? $row['image_path']
                : $baseImageUrl . ltrim($row['image_path'], '/');
            $offers[] = [
                'id'          => $row['id'],
                'image_path'  => $imageUrl,
                'category'    => $row['category'] ?? 'Loan Offer',
                'title'       => $row['category'] ?? 'Special Offer',
                'description' => 'Exclusive loan offer for you',
            ];
        }
    }
}

if (empty($offers)) {
    $offers = [
        ['id'=>1,'image_path'=>'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800&h=400&fit=crop','category'=>'Personal Loan','title'=>'Get Instant Personal Loan','description'=>'Up to ₹50 Lakhs at low interest rates'],
        ['id'=>2,'image_path'=>'https://images.unsplash.com/photo-1560520653-9e0e4c89d9e8?w=800&h=400&fit=crop','category'=>'Home Loan','title'=>'Dream Home Loan','description'=>'Up to ₹5 Crore with easy EMI'],
        ['id'=>3,'image_path'=>'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&h=400&fit=crop','category'=>'Business Loan','title'=>'Business Growth Loan','description'=>'MSME approved loans up to ₹2 Crore'],
    ];
}

include('../includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Login To Loans - Financial Super App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* (copy your exact styles – unchanged) */
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        :root {
            --primary: #1a237e; --primary-dark: #0d1757; --primary-light: #283593;
            --secondary: #00c853; --accent: #ff6b35; --orange: #ff9800;
            --text-primary: #1a1a2e; --text-secondary: #666666; --text-tertiary: #999999;
            --background: #f8f9fc; --white: #ffffff; --border: #eef2f6;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
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

        .page, .modal-content, .menu-drawer {
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        .page { display: none; animation: fadeIn 0.3s ease; }
        .page.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* ===== HERO ===== */
        .hero-section {
            position: relative;
            padding: 16px 16px 30px;
            color: white;
            overflow: hidden;
            border-radius: 0 0 24px 24px;
            min-height: 340px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            overscroll-behavior: contain;
            touch-action: pan-y;
        }

        .hero-slideshow {
            position: absolute; top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0; overflow: hidden;
            border-radius: 0 0 24px 24px;
            overscroll-behavior: contain;
            touch-action: pan-y pinch-zoom;
        }

        .slides-wrapper {
            display: flex; width: 100%; height: 100%;
            transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            touch-action: none;
        }

        .slide {
            flex: 0 0 100%;
            width: 100%;
            max-width: 100%;
            height: 100%;
            position: relative;
            overscroll-behavior: contain;
        }
        .slide img { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; touch-action: none; }

        .slide-category-badge {
            position: absolute; bottom: 70px; left: 16px; z-index: 3;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);
            padding: 4px 12px; border-radius: 16px; color: white;
            font-size: 10px; font-weight: 600;
            border: 1px solid rgba(255,255,255,0.2);
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .hero-section .overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(26,35,126,0.5) 0%, rgba(57,73,171,0.6) 100%);
            z-index: 1; border-radius: 0 0 24px 24px;
        }

        .hero-content-wrapper { position: relative; z-index: 2; padding: 10px 0 5px; }
        .hero-greeting { font-size: 12px; opacity: 0.9; text-shadow: 0 2px 8px rgba(0,0,0,0.3); margin-bottom: 2px; }
        .hero-title { font-size: 22px; font-weight: 800; line-height: 1.2; text-shadow: 0 2px 12px rgba(0,0,0,0.4); margin-bottom: 6px; }
        .hero-title .highlight { color: #ff9800; }

        .rate-box {
            background: rgba(0,0,0,0.5); backdrop-filter: blur(12px);
            border-radius: 12px; padding: 10px 16px; margin: 4px 0 10px;
            display: inline-block; border: 1px solid rgba(255,255,255,0.1); max-width: 100%;
        }
        .rate-box .rate-value { font-size: 28px; font-weight: 800; color: #ff9800; line-height: 1; }
        .rate-box .rate-label { font-size: 12px; opacity: 0.8; margin-left: 4px; }
        .rate-box .rate-detail { font-size: 10px; opacity: 0.7; margin-top: 2px; }
        .rate-box .rate-detail i { color: #ff9800; margin-right: 4px; }

        .hero-apply-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            padding: 10px 28px; border-radius: 50px; font-size: 13px;
            font-weight: 700; cursor: pointer; border: none; color: white;
            box-shadow: 0 4px 20px rgba(255,152,0,0.3);
            transition: all 0.3s ease; margin-top: 4px; width: fit-content;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .hero-apply-btn:active { transform: scale(0.96); }
        .hero-apply-btn i { font-size: 14px; }

        .slide-indicators {
            position: absolute; bottom: 10px; left: 0; right: 0;
            display: flex; justify-content: center; gap: 6px; z-index: 3;
        }
        .indicator {
            width: 6px; height: 6px; border-radius: 50%;
            background: rgba(255,255,255,0.4); cursor: pointer;
            transition: all 0.3s ease; border: none; padding: 0;
        }
        .indicator.active { width: 20px; border-radius: 10px; background: white; }

        .slide-nav {
            position: absolute; top: 50%; transform: translateY(-50%);
            z-index: 3; background: #ff9800; backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.2); border-radius: 50%;
            width: 32px; height: 32px; display: flex; align-items: center;
            justify-content: center; cursor: pointer; transition: all 0.3s ease;
            color: white; font-size: 12px; padding: 0;
        }
        .slide-nav:active { transform: translateY(-50%) scale(0.92); }
        .slide-nav-prev { left: 8px; }
        .slide-nav-next { right: 8px; }

        /* ===== SECTION HEADERS ===== */
        .section-header { display: flex; justify-content: space-between; align-items: baseline; padding: 0 14px; margin: 20px 0 10px; }
        .section-header h2 { font-size: 20px; font-weight: 800; color: var(--text-primary); }
        .section-header a { font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600; cursor: pointer; }

        /* ===== BIG PRODUCT CARDS - 3 COLUMNS ===== */
        .products-section {
            padding: 0 10px;
            margin: 16px 0 10px;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .product-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            border: 2px solid var(--border);
            position: relative;
            min-height: 130px;
        }
        .product-card:active {
            transform: scale(0.96);
            border-color: var(--orange);
            box-shadow: var(--shadow-lg);
        }
        .product-card-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            font-size: 7px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            z-index: 2;
        }
        .product-card-badge.popular {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .product-card-badge.best {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }
        .product-card-badge.hot {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }
        .product-card-badge.low {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
            color: #1a1a2e;
        }
        .product-card-badge.quick {
            background: linear-gradient(135deg, #fa709a, #fee140);
            color: #1a1a2e;
        }
        .product-card-icon {
            padding: 18px 10px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80px;
            background: linear-gradient(180deg, #f8f9fc 0%, #ffffff 100%);
        }
        .product-card-icon .icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(26, 35, 126, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-card-icon i {
            font-size: 26px;
            color: var(--primary);
        }
      .product-card-name {
    padding: 8px 6px 16px;
    text-align: center;
    font-size: 14px !important;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.4;
    background: #fff;
    min-height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.product-card-name small {
    display: block;
    font-size: 12px !important;
    font-weight: 500;
    color: var(--text-tertiary);
    margin-top: 5px;
    line-height: 1.3;
}
        /* ===== LOANS HORIZONTAL SCROLL ===== */
        .loans-scroll-wrapper {
            position: relative;
            overflow: hidden;
            padding: 0 10px;
            margin: 10px 0 6px;
            width: 100%;
        }

        .loans-scroll-track {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 4px 2px 10px 2px;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            animation: scrollLoans 25s linear infinite;
            width: max-content;
        }

        .loans-scroll-track::-webkit-scrollbar {
            display: none;
        }

        .loans-scroll-track .loan-card-scroll {
            flex: 0 0 160px;
            scroll-snap-align: start;
            border-radius: 16px;
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
            min-height: 120px;
            background: linear-gradient(135deg, #1a237e, #283593);
            border: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
            justify-content: center;
            gap: 8px;
        }

        .loans-scroll-track .loan-card-scroll:active {
            transform: scale(0.95);
        }

        .loans-scroll-track .loan-card-scroll .loan-image {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
            z-index: 2;
        }

        .loans-scroll-track .loan-card-scroll .loan-image i {
            font-size: 24px;
        }

        .loans-scroll-track .loan-card-scroll .loan-info {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .loans-scroll-track .loan-card-scroll .loan-info h4 {
            font-size: 12px;
            font-weight: 700;
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 3px;
        }

        .loans-scroll-track .loan-card-scroll .loan-info p {
            font-size: 9px;
            color: rgba(255,255,255,0.85);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .loans-scroll-track:hover {
            animation-play-state: paused;
        }

        .loans-scroll-track.touch-paused {
            animation-play-state: paused;
        }

        @keyframes scrollLoans {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* ===== HERO OFFERS - BIG IMAGES ===== */
        .hero-offers-section {
            padding: 0 10px;
            margin: 16px 0 10px;
            overflow: hidden;
        }
        .hero-offers-scroll {
            display: flex;
            gap: 14px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 4px 2px 12px 2px;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            animation: scrollHeroOffers 22s linear infinite;
            width: max-content;
        }
        .hero-offers-scroll::-webkit-scrollbar { display: none; }
        .hero-offers-scroll.touch-paused { animation-play-state: paused; }
        .hero-offers-scroll:hover { animation-play-state: paused; }

        .hero-offer-card-sm {
            flex: 0 0 200px;
            scroll-snap-align: start;
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            min-height: 150px;
            background: #eef2f6;
            text-decoration: none;
            display: block;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 2px solid var(--border);
        }
        .hero-offer-card-sm:active { 
            transform: scale(0.95);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .hero-offer-card-sm img {
            width: 100%;
            height: 100%;
            min-height: 150px;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }
        .hero-offer-card-sm:hover img {
            transform: scale(1.05);
        }

        .hero-offer-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 30px 12px 14px;
            background: linear-gradient(transparent, rgba(0,0,0,0.75));
            pointer-events: none;
        }

        .hero-offer-tag {
            display: inline-block;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            font-size: 10px;
            font-weight: 800;
            padding: 5px 14px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            box-shadow: 0 2px 10px rgba(255,152,0,0.3);
        }

        @keyframes scrollHeroOffers {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* ===== CALCULATORS SECTION - BIG CARDS ===== */
        .calculators-section {
            padding: 0 10px;
            margin: 16px 0 10px;
        }
        .calculators-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .calculator-card {
            background: var(--white);
            border-radius: 16px;
            padding: 22px 14px;
            border: 2px solid var(--border);
            box-shadow: var(--shadow-md);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 130px;
            justify-content: center;
        }
        .calculator-card:active { 
            transform: scale(0.96); 
            background: #f0f3ff; 
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }
        .calculator-card .calc-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 12px;
        }
        .calculator-card .calc-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.3;
        }
        .calculator-card .calc-sub {
            font-size: 10px;
            color: var(--text-tertiary);
            margin-top: 6px;
            line-height: 1.3;
        }

        /* ===== INVESTMENTS SECTION - BIG ===== */
        .investments-section {
            padding: 0 10px;
            margin: 16px 0 10px;
            overflow: hidden;
        }
        .investments-scroll {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 4px 2px 10px 2px;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            width: 100%;
        }
        .investments-scroll::-webkit-scrollbar {
            display: none;
        }
        .investment-card-sm {
            flex: 0 0 150px;
            scroll-snap-align: start;
            background: var(--white);
            border-radius: 16px;
            padding: 18px 12px;
            border: 2px solid var(--border);
            box-shadow: var(--shadow-md);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 110px;
            justify-content: center;
        }
        .investment-card-sm:active { 
            transform: scale(0.95); 
            background: #f0f3ff; 
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }
        .investment-card-sm .inv-icon-sm {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 8px;
            flex-shrink: 0;
        }
        .investment-card-sm .inv-title-sm {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }
        .investment-card-sm .inv-sub-sm {
            font-size: 8px;
            color: var(--text-tertiary);
            margin-top: 4px;
            line-height: 1.2;
        }

        /* ===== INSURANCE SECTION - BIG ===== */
        .insurance-section {
            padding: 0 10px;
            margin: 16px 0 10px;
            overflow: hidden;
        }
        .insurance-scroll {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 4px 2px 10px 2px;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            width: 100%;
        }
        .insurance-scroll::-webkit-scrollbar {
            display: none;
        }
        .insurance-card-sm {
            flex: 0 0 150px;
            scroll-snap-align: start;
            background: var(--white);
            border-radius: 16px;
            padding: 18px 12px;
            border: 2px solid var(--border);
            box-shadow: var(--shadow-md);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 110px;
            justify-content: center;
        }
        .insurance-card-sm:active { 
            transform: scale(0.95); 
            background: #f0f3ff; 
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }
        .insurance-card-sm .ins-icon-sm {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 8px;
            flex-shrink: 0;
        }
        .insurance-card-sm .ins-title-sm {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.2;
        }
        .insurance-card-sm .ins-sub-sm {
            font-size: 8px;
            color: var(--text-tertiary);
            margin-top: 4px;
            line-height: 1.2;
        }

        /* ===== MODALS ===== */
        .modal { position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 1000; display: flex; align-items: center; justify-content: center; visibility: hidden; opacity: 0; transition: 0.2s; }
        .modal.active { visibility: visible; opacity: 1; }
        .modal-content { background: var(--white); border-radius: 24px; padding: 20px; width: 92%; max-width: 380px; max-height: 85vh; overflow-y: auto; animation: modalPop 0.3s ease; overscroll-behavior: contain; -webkit-overflow-scrolling: touch; }
        @keyframes modalPop { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 2px solid var(--border); }
        .modal-header h3 { font-size: 1rem; font-weight: 700; color: var(--primary); }
        .modal-close { width: 28px; height: 28px; border-radius: 50%; background: var(--border); border: none; cursor: pointer; font-size: 14px; transition: 0.2s; }
        .modal-close:active { background: #e0e0e0; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 11px; font-weight: 600; margin-bottom: 4px; color: var(--text-secondary); }
        .form-group label i { margin-right: 6px; color: var(--accent); }
        .form-input { width: 100%; padding: 10px 12px; border: 1.5px solid var(--border); border-radius: 12px; font-size: 13px; font-family: 'Inter', sans-serif; transition: 0.2s; }
        .form-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(255,107,53,0.1); }
        .apply-button { background: linear-gradient(135deg,var(--accent),#ff8c42); color: white; border: none; padding: 12px 20px; border-radius: 40px; font-weight: 700; cursor: pointer; width: 100%; transition: transform 0.2s; font-size: 13px; margin-top: 8px; }
        .apply-button:active { transform: scale(0.98); }
        .toast { position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%) translateY(100px); background: var(--text-primary); color: white; padding: 10px 18px; border-radius: 24px; font-size: 12px; z-index: 1000; transition: transform 0.3s; white-space: nowrap; }
        .toast.show { transform: translateX(-50%) translateY(0); }
        .footer-note { text-align: center; font-size: 9px; color: var(--text-tertiary); padding: 14px; border-top: 1px solid var(--border); margin-top: 16px; }

        /* ===== DRAWER ===== */
        .menu-drawer { position: fixed; top: 0; left: -280px; width: 280px; height: 100vh; background: #ffffff; z-index: 500; transition: left 0.3s ease; box-shadow: 4px 0 24px rgba(0,0,0,0.15); padding-top: 60px; overflow-y: auto; overscroll-behavior: contain; -webkit-overflow-scrolling: touch; }
        .menu-drawer.open { left: 0; }
        .menu-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 499; opacity: 0; pointer-events: none; transition: opacity 0.3s; }
        .menu-overlay.open { opacity: 1; pointer-events: all; }
        .drawer-close { position: absolute; top: 12px; right: 12px; width: 32px; height: 32px; border: none; background: #f5f5f5; border-radius: 50%; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .drawer-user { padding: 0 16px 16px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 10px; }
        .drawer-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #ff9800, #ff5722); display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; color: #fff; flex-shrink: 0; overflow: hidden; }
        .drawer-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .drawer-user-info .name { font-size: 14px; font-weight: 700; color: #1a1a2e; }
        .drawer-user-info .email { font-size: 10px; color: #999; margin-top: 1px; }
        .drawer-nav { padding: 12px 0; }
        .drawer-item { display: flex; align-items: center; gap: 12px; padding: 12px 20px; cursor: pointer; transition: background 0.2s; font-size: 14px; font-weight: 500; color: #1a1a2e; text-decoration: none; border: none; background: transparent; width: 100%; text-align: left; }
        .drawer-item:active { background: #f5f7ff; }
        .drawer-item i { width: 20px; color: #1a237e; font-size: 15px; }
        .drawer-item.logout-drawer-item { color: #ef4444 !important; border-top: 1px solid #f0f0f0; margin-top: 8px; }
        .drawer-item.logout-drawer-item i { color: #ef4444 !important; }

        .custom-confirm-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 10000; display: none; align-items: center; justify-content: center; }
        .custom-confirm-modal { background: var(--white); border-radius: 20px; width: 300px; max-width: 85%; padding: 20px; text-align: center; animation: modalPop 0.3s ease; box-shadow: var(--shadow-lg); }
        .custom-confirm-icon { width: 56px; height: 56px; border-radius: 50%; background: #fee2e2; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
        .custom-confirm-icon i { font-size: 26px; color: #ef4444; }
        .custom-confirm-title { font-size: 18px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
        .custom-confirm-message { font-size: 13px; color: var(--text-secondary); margin-bottom: 18px; line-height: 1.5; }
        .custom-confirm-buttons { display: flex; gap: 10px; }
        .custom-confirm-btn { flex: 1; padding: 10px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-family: 'Inter', sans-serif; }
        .custom-confirm-btn-cancel { background: #f0f0f0; color: var(--text-secondary); }
        .custom-confirm-btn-logout { background: linear-gradient(135deg,#ef4444,#dc2626); color: white; }

        /* ===== RESPONSIVE ===== */
        /* Default: 3 columns */
        .products-grid { grid-template-columns: repeat(3, 1fr); }
        .calculators-grid { grid-template-columns: repeat(2, 1fr); }

        /* Very small phones */
        @media (max-width: 360px) {
            .products-grid { gap: 6px; }
            
            .product-card { min-height: 105px; }
            .product-card-icon { min-height: 60px; padding: 12px 6px 8px; }
            .product-card-icon .icon-wrapper { width: 42px; height: 42px; }
            .product-card-icon i { font-size: 18px; }
            .product-card-name { font-size: 9px; padding: 4px 2px 10px; min-height: 40px; }
            .product-card-name small { font-size: 7px; }
            
            .calculator-card { min-height: 105px; padding: 16px 8px; }
            .calculator-card .calc-icon { width: 44px; height: 44px; font-size: 20px; }
            
            /* Hero offers - small phones */
            .hero-offer-card-sm { flex: 0 0 150px; min-height: 120px; }
            .hero-offer-card-sm img { min-height: 120px; }
            .hero-offer-tag { font-size: 8px; padding: 3px 10px; }
            .hero-offer-overlay { padding: 20px 10px 10px; }
        }

        @media (max-width: 480px) {
            /* Keep 3 columns */
            .products-grid { grid-template-columns: repeat(3, 1fr); gap: 8px; }
            .calculators-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
            
            .hero-section { min-height: 280px; padding: 12px 12px 24px; border-radius: 0 0 18px 18px; }
            .hero-title { font-size: 18px; }
            .hero-apply-btn { padding: 8px 18px; font-size: 11px; }
            
            .product-card { min-height: 115px; border-radius: 14px; }
            .product-card-icon { min-height: 65px; padding: 14px 8px 10px; }
            .product-card-icon .icon-wrapper { width: 46px; height: 46px; }
            .product-card-icon i { font-size: 20px; }
            .product-card-name { font-size: 10px; padding: 6px 4px 12px; min-height: 44px; }
            .product-card-name small { font-size: 8px; }
            
            .loans-scroll-track .loan-card-scroll { flex: 0 0 140px; min-height: 100px; padding: 14px 10px; }
            .loans-scroll-track .loan-card-scroll .loan-image { width: 40px; height: 40px; }
            .loans-scroll-track .loan-card-scroll .loan-image i { font-size: 18px; }
            .loans-scroll-track .loan-card-scroll .loan-info h4 { font-size: 10px; }
            .loans-scroll-track .loan-card-scroll .loan-info p { font-size: 8px; }
            
            /* Hero offers - mobile */
            .hero-offer-card-sm { flex: 0 0 170px; min-height: 130px; border-radius: 14px; }
            .hero-offer-card-sm img { min-height: 130px; }
            .hero-offer-overlay { padding: 25px 10px 10px; }
            .hero-offer-tag { font-size: 9px; padding: 4px 12px; }
            
            .calculator-card { padding: 18px 10px; min-height: 110px; border-radius: 14px; }
            .calculator-card .calc-icon { width: 48px; height: 48px; font-size: 22px; }
            .calculator-card .calc-title { font-size: 12px; }
            .calculator-card .calc-sub { font-size: 8px; }
            
            .investment-card-sm { flex: 0 0 130px; min-height: 95px; padding: 14px 10px; }
            .investment-card-sm .inv-icon-sm { width: 38px; height: 38px; font-size: 17px; }
            .investment-card-sm .inv-title-sm { font-size: 10px; }
            .investment-card-sm .inv-sub-sm { font-size: 7px; }
            
            .insurance-card-sm { flex: 0 0 130px; min-height: 95px; padding: 14px 10px; }
            .insurance-card-sm .ins-icon-sm { width: 38px; height: 38px; font-size: 17px; }
            .insurance-card-sm .ins-title-sm { font-size: 10px; }
            .insurance-card-sm .ins-sub-sm { font-size: 7px; }
            
            .section-header { padding: 0 8px; margin: 12px 0 6px; }
            .section-header h2 { font-size: 16px; }
            .section-header a { font-size: 10px; }
        }

        @media (min-width: 481px) and (max-width: 768px) {
            .hero-section { min-height: 400px; padding: 20px 24px 35px; }
            .hero-title { font-size: 28px; }

            .products-grid { grid-template-columns: repeat(4, 1fr); gap: 12px; }
            .calculators-grid { grid-template-columns: repeat(4, 1fr); gap: 12px; }
            
            .loans-scroll-track .loan-card-scroll { flex: 0 0 180px; }
            
            .hero-offer-card-sm { flex: 0 0 250px; min-height: 180px; }
            .hero-offer-card-sm img { min-height: 180px; }
            
            .investment-card-sm { flex: 0 0 170px; }
            .insurance-card-sm { flex: 0 0 170px; }
        }

        /*personal details alert message */
        
        /* ===== PROFILE MODAL STYLES ===== */
.profile-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(6px);
    z-index: 20000;
    display: none;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}
.profile-modal-overlay.active {
    display: flex;
}

.profile-modal-box {
    background: var(--white);
    border-radius: 24px;
    padding: 20px 18px 16px;      /* smaller padding */
    max-width: 320px;             /* smaller width */
    width: 90%;                   /* slightly narrower */
    text-align: center;
    animation: slideUp 0.3s ease;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    border-top: 4px solid var(--orange);
}

.profile-modal-icon {
    width: 50px;
    height: 50px;
    background: #fff3e0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;          /* reduced margin */
}
.profile-modal-icon i {
    font-size: 24px;
    color: var(--orange);
}

.profile-modal-box h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 6px;
}

.profile-modal-box p {
    font-size: 13px;
    color: var(--text-secondary);
    line-height: 1.5;
    margin-bottom: 18px;
}

.profile-modal-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.profile-modal-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 40px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    font-family: 'Inter', sans-serif;
    flex: 1;
}

.profile-modal-btn-cancel {
    background: #f1f5f9;
    color: var(--text-secondary);
}
.profile-modal-btn-cancel:hover {
    background: #e2e8f0;
}
.profile-modal-btn-cancel:active {
    transform: scale(0.96);
}

.profile-modal-btn-ok {
    background: linear-gradient(135deg, #ff9800, #f57c00);
    color: white;
    box-shadow: 0 4px 14px rgba(255, 152, 0, 0.4);
}
.profile-modal-btn-ok:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 152, 0, 0.5);
}
.profile-modal-btn-ok:active {
    transform: scale(0.96);
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
    </style>
</head>
<body>

<div class="app-container">

    <!-- LOGOUT CONFIRM MODAL -->
    <div id="logoutConfirmModal" class="custom-confirm-overlay">
        <div class="custom-confirm-modal">
            <div class="custom-confirm-icon"><i class="fas fa-sign-out-alt"></i></div>
            <div class="custom-confirm-title">Logout Confirmation</div>
            <div class="custom-confirm-message">Are you sure you want to logout from your account?</div>
            <div class="custom-confirm-buttons">
                <button class="custom-confirm-btn custom-confirm-btn-cancel" onclick="closeLogoutModal()">Cancel</button>
                <button class="custom-confirm-btn custom-confirm-btn-logout" onclick="confirmLogout()">Logout</button>
            </div>
        </div>
    </div>

    <!-- HOME PAGE -->
    <div id="homePage" class="page active">

        <!-- HERO SLIDESHOW -->
        <div class="hero-section">
            <div class="hero-slideshow" id="heroSlideshow">
                <div class="slides-wrapper" id="slidesWrapper">
                    <?php foreach ($offers as $index => $offer): ?>
                        <div class="slide" data-index="<?php echo $index; ?>">
                            <img
                                src="<?php echo htmlspecialchars($offer['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($offer['category']); ?>"
                                loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                                onerror="this.style.display='none';">
                            <?php if (!empty($offer['category'])): ?>
                                <div class="slide-category-badge">
                                    <i class="fas fa-tag"></i> <?php echo htmlspecialchars($offer['category']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="slide-nav slide-nav-prev" onclick="prevSlide()" aria-label="Previous slide">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slide-nav slide-nav-next" onclick="nextSlide()" aria-label="Next slide">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="slide-indicators" id="slideIndicators">
                <?php for ($i = 0; $i < count($offers); $i++): ?>
                    <button class="indicator <?php echo $i === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $i; ?>)"></button>
                <?php endfor; ?>
            </div>
        </div>

        <!-- ===== CHOOSE YOUR PRODUCT SECTION - 3 COLUMNS ===== -->
        <div class="products-section">
            <div class="section-header" style="padding: 0; margin: 16px 0 14px;">
                <h2>📦 Choose Your Product</h2>
            </div>
            <div class="products-grid">
                <a href="https://mobile.logintoloans.com/credit-cards.php" class="product-card">
                    <span class="product-card-badge popular">Popular</span>
                    <div class="product-card-icon">
                        <div class="icon-wrapper"><i class="fas fa-credit-card"></i></div>
                    </div>
                    <div class="product-card-name">Credit Card <small>Up to ₹5 Lakh</small></div>
                </a>

                <a href="https://mobile.logintoloans.com/personal.php" class="product-card">
                    <span class="product-card-badge best">Best Offer</span>
                    <div class="product-card-icon">
                        <div class="icon-wrapper"><i class="fas fa-rupee-sign"></i></div>
                    </div>
                    <div class="product-card-name">Personal Loan <small>Instant Approval</small></div>
                </a>

                <a href="https://mobile.logintoloans.com/home-loan.php" class="product-card">
                    <span class="product-card-badge low">Low Rate</span>
                    <div class="product-card-icon">
                        <div class="icon-wrapper"><i class="fas fa-home"></i></div>
                    </div>
                    <div class="product-card-name">Home Loan <small>Up to ₹5 Cr</small></div>
                </a>

                <a href="https://mobile.logintoloans.com/mortgage-loan.php" class="product-card">
                    <div class="product-card-icon">
                        <div class="icon-wrapper"><i class="fas fa-building"></i></div>
                    </div>
                    <div class="product-card-name">Mortgage Loan <small>Property Backed</small></div>
                </a>

                <a href="https://mobile.logintoloans.com/car-loan.php" class="product-card">
                    <div class="product-card-icon">
                        <div class="icon-wrapper"><i class="fas fa-car"></i></div>
                    </div>
                    <div class="product-card-name">Car Loan <small>New &amp; Used</small></div>
                </a>

                <a href="https://mobile.logintoloans.com/business-loans.php" class="product-card">
                    <span class="product-card-badge hot">Hot</span>
                    <div class="product-card-icon">
                        <div class="icon-wrapper"><i class="fas fa-briefcase"></i></div>
                    </div>
                    <div class="product-card-name">Business Loan <small>MSME &amp; SME</small></div>
                </a>

                <a href="https://mobile.logintoloans.com/professional-loans.php" class="product-card">
                    <div class="product-card-icon">
                        <div class="icon-wrapper"><i class="fas fa-user-tie"></i></div>
                    </div>
                    <div class="product-card-name">Professional Loan <small>Doctors &amp; CA</small></div>
                </a>

                <a href="https://mobile.logintoloans.com/gold-loan.php" class="product-card">
                    <span class="product-card-badge quick">Quick</span>
                    <div class="product-card-icon">
                        <div class="icon-wrapper"><i class="fas fa-coins"></i></div>
                    </div>
                    <div class="product-card-name">Gold Loan <small>Against Gold</small></div>
                </a>

                <a href="https://mobile.logintoloans.com/education-loan.php" class="product-card">
                    <div class="product-card-icon">
                        <div class="icon-wrapper"><i class="fas fa-graduation-cap"></i></div>
                    </div>
                    <div class="product-card-name">Education Loan <small>Study Abroad</small></div>
                </a>
            </div>
        </div>

        

        <!-- ===== HERO OFFERS - BIG IMAGES AUTO SCROLL ===== -->
        <div class="hero-offers-section">
            <div class="section-header" style="padding: 0; margin: 16px 0 12px;">
                <h2 style="font-size: 20px;">🔥 Featured Offers</h2>
                <a href="#" style="font-size: 12px; color: var(--orange); font-weight: 700;">View All →</a>
            </div>
            <div class="hero-offers-scroll" id="heroOffersScroll">
                <?php
                for ($repeat = 0; $repeat < 2; $repeat++):
                    foreach ($offers as $offer):
                ?>
                <a href="#" class="hero-offer-card-sm">
                    <img src="<?php echo htmlspecialchars($offer['image_path']); ?>"
                         alt="<?php echo htmlspecialchars($offer['category']); ?>"
                         loading="lazy"
                         onerror="this.parentElement.style.display='none';">
                    <?php if (!empty($offer['category'])): ?>
                    <div class="hero-offer-overlay">
                        <span class="hero-offer-tag"><?php echo htmlspecialchars($offer['category']); ?></span>
                    </div>
                    <?php endif; ?>
                </a>
                <?php
                    endforeach;
                endfor;
                ?>
            </div>
        </div>

        <!-- ===== CALCULATORS SECTION - 2 COLUMNS ===== -->
        <div class="calculators-section">
            <div class="section-header" style="padding: 0; margin: 16px 0 10px;">
                <h2>🧮 Calculators</h2>
            </div>
            <div class="calculators-grid">
                <div class="calculator-card" onclick="window.location.href='https://mobile.logintoloans.com/emi-calculators.php'">
                    <div class="calc-icon" style="background: #e8eaf6; color: #1a237e;">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="calc-title">EMI Calculators</div>
                    <div class="calc-sub">Click to expand all EMI calculators</div>
                </div>
                <div class="calculator-card" onclick="window.location.href='https://mobile.logintoloans.com/eligibility-calculators.php'">
                    <div class="calc-icon" style="background: #e3f2fd; color: #1565c0;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="calc-title">Eligibility Calculators</div>
                    <div class="calc-sub">Check if you qualify for loans</div>
                </div>
                <div class="calculator-card" onclick="window.location.href='https://mobile.logintoloans.com/interest-repayment-calculators.php'">
                    <div class="calc-icon" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="calc-title">Interest & Repayment</div>
                    <div class="calc-sub">Calculate interest and plan repayments</div>
                </div>
                <div class="calculator-card" onclick="window.location.href='https://mobile.logintoloans.com/comparison-financial-calculators.php'">
                    <div class="calc-icon" style="background: #fff3e0; color: #e65100;">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div class="calc-title">Comparison & Financial</div>
                    <div class="calc-sub">Compare loans and analyze finances</div>
                </div>
            </div>
        </div>

        <!-- ===== INVESTMENTS SECTION - 3 COLUMNS ===== -->
        <div class="investments-section">
            <div class="section-header" style="padding: 0; margin: 16px 0 10px;">
                <h2>📈 Investments</h2>
            </div>
            <div class="investments-scroll">
                <div class="investment-card-sm" onclick="window.location.href='https://mobile.logintoloans.com/mutual-funds.php'">
                    <div class="inv-icon-sm" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="inv-title-sm">Mutual Funds</div>
                    <div class="inv-sub-sm">Diversified fund investing</div>
                </div>

                <div class="investment-card-sm" onclick="window.location.href='#'">
                    <div class="inv-icon-sm" style="background: #fff3e0; color: #e65100;">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="inv-title-sm">Income Return</div>
                    <div class="inv-sub-sm">Fixed returns on savings</div>
                </div>

                <div class="investment-card-sm" onclick="window.location.href='#'">
                    <div class="inv-icon-sm" style="background: #e3f2fd; color: #1565c0;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="inv-title-sm">SIP</div>
                    <div class="inv-sub-sm">Regular monthly investing</div>
                </div>

                <div class="investment-card-sm" onclick="window.location.href='#'">
                    <div class="inv-icon-sm" style="background: #fff8e1; color: #f57f17;">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="inv-title-sm">Digital Gold</div>
                    <div class="inv-sub-sm">Buy gold electronically</div>
                </div>

                <div class="investment-card-sm" onclick="window.location.href='#'">
                    <div class="inv-icon-sm" style="background: #e3f2fd; color: #0d47a1;">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <div class="inv-title-sm">Fixed Deposit</div>
                    <div class="inv-sub-sm">Guaranteed returns</div>
                </div>
            </div>
        </div>

        <!-- ===== INSURANCE SECTION - 3 COLUMNS ===== -->
        <div class="insurance-section">
            <div class="section-header" style="padding: 0; margin: 16px 0 10px;">
                <h2>🛡️ Insurance</h2>
            </div>
            <div class="insurance-scroll">
                <div class="insurance-card-sm" onclick="window.location.href='#'">
                    <div class="ins-icon-sm" style="background: #fce4ec; color: #c62828;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="ins-title-sm">Term Insurance</div>
                    <div class="ins-sub-sm">Pure life cover</div>
                </div>

                <div class="insurance-card-sm" onclick="window.location.href='#'">
                    <div class="ins-icon-sm" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="ins-title-sm">Life Insurance</div>
                    <div class="ins-sub-sm">Savings + protection</div>
                </div>

                <div class="insurance-card-sm" onclick="window.location.href='#'">
                    <div class="ins-icon-sm" style="background: #e3f2fd; color: #1565c0;">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="ins-title-sm">Health Insurance</div>
                    <div class="ins-sub-sm">Medical expense cover</div>
                </div>

                <div class="insurance-card-sm" onclick="window.location.href='#'">
                    <div class="ins-icon-sm" style="background: #fff3e0; color: #e65100;">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="ins-title-sm">General Insurance</div>
                    <div class="ins-sub-sm">Vehicle, home &amp; more</div>
                </div>

                <div class="insurance-card-sm" onclick="window.location.href='#'">
                    <div class="ins-icon-sm" style="background: #f3e5f5; color: #7b1fa2;">
                        <i class="fas fa-plane"></i>
                    </div>
                    <div class="ins-title-sm">Travel Insurance</div>
                    <div class="ins-sub-sm">Trip protection</div>
                </div>
            </div>
        </div>

        <div class="footer-note"><i class="fas fa-lock"></i> Secure & Trusted | RBI Compliant</div>
    </div>
</div>

<!-- ===== LOAN APPLICATION FORM MODAL ===== -->
<div id="applicationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-hand-holding-usd"></i> Apply for Loan</h3>
            <button class="modal-close" onclick="closeModal('applicationModal')">✕</button>
        </div>
        <div class="form-group">
            <label><i class="fas fa-tag"></i> Loan Type</label>
            <input type="text" id="loanTypeDisplay" class="form-input" readonly style="background:#f5f5f5;font-weight:600;">
        </div>
        <div class="form-group">
            <label><i class="fas fa-user"></i> Full Name</label>
            <input type="text" class="form-input" id="applicantName" value="<?php echo $fullname; ?>">
        </div>
        <div class="form-group">
            <label><i class="fas fa-envelope"></i> Email</label>
            <input type="email" class="form-input" id="applicantEmail" value="<?php echo $email; ?>">
        </div>
        <div class="form-group">
            <label><i class="fas fa-phone"></i> Phone</label>
            <input type="tel" class="form-input" id="applicantPhone" value="<?php echo $phone; ?>">
        </div>
        <div class="form-group">
            <label><i class="fas fa-rupee-sign"></i> Monthly Income (₹)</label>
            <input type="number" class="form-input" id="monthlyIncome" placeholder="Monthly income">
        </div>
        <div class="form-group">
            <label><i class="fas fa-hand-holding-usd"></i> Loan Amount (₹)</label>
            <input type="number" class="form-input" id="loanAmount" placeholder="Required loan amount">
        </div>
        <div class="form-group">
            <label><i class="fas fa-calendar-alt"></i> Tenure</label>
            <select class="form-input" id="loanTenure">
                <option value="">Select tenure</option>
                <option value="12">12 months</option>
                <option value="24">24 months</option>
                <option value="36">36 months</option>
                <option value="48">48 months</option>
                <option value="60">60 months</option>
            </select>
        </div>
        <button class="apply-button" onclick="submitApplication()"><i class="fas fa-paper-plane"></i> Submit Application →</button>
    </div>
</div>
<!-- ===== PROFILE INCOMPLETE MODAL ===== -->
<div id="profileModal" class="profile-modal-overlay">
    <div class="profile-modal-box">
        <div class="profile-modal-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3>Complete Your Profile</h3>
        <p>Please complete your personal details (DOB, Gender, Aadhaar, PAN, Annual Income) to access all features.</p>
        <div class="profile-modal-actions">
            <button class="profile-modal-btn profile-modal-btn-cancel" onclick="closeProfileModal()">Cancel</button>
            <button class="profile-modal-btn profile-modal-btn-ok" onclick="goToProfile()">OK, Go to Profile</button>
        </div>
    </div>
</div>
<div id="toast" class="toast"></div>

<script>
    // ============================================================
    // CAROUSEL SLIDER
    // ============================================================
    let currentSlide = 0;
    let slideInterval;
    let isTransitioning = false;
    const slidesWrapper = document.getElementById('slidesWrapper');
    const totalSlides = slidesWrapper ? slidesWrapper.children.length : 0;
    const indicators = document.querySelectorAll('#slideIndicators .indicator');

    function getSlideWidth() {
        return slidesWrapper ? slidesWrapper.parentElement.offsetWidth : 0;
    }

    function showSlide(index) {
        if (isTransitioning || totalSlides === 0) return;
        isTransitioning = true;
        if (index < 0) index = totalSlides - 1;
        if (index >= totalSlides) index = 0;
        currentSlide = index;
        const slideWidth = getSlideWidth();
        if (slidesWrapper) {
            slidesWrapper.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            slidesWrapper.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
        }
        indicators.forEach((ind, i) => ind.classList.toggle('active', i === currentSlide));
        document.querySelectorAll('.slide').forEach((slide, i) => {
            const badge = slide.querySelector('.slide-category-badge');
            if (badge && i === currentSlide) {
                badge.style.animation = 'none';
                setTimeout(() => { badge.style.animation = 'fadeInUp 0.6s ease'; }, 50);
            }
        });
        setTimeout(() => { isTransitioning = false; }, 850);
    }

    function nextSlide() { if (totalSlides === 0) return; showSlide(currentSlide + 1); resetAutoPlay(); }
    function prevSlide() { if (totalSlides === 0) return; showSlide(currentSlide - 1); resetAutoPlay(); }
    function goToSlide(index) { if (totalSlides === 0) return; showSlide(index); resetAutoPlay(); }

    function resetAutoPlay() {
        if (slideInterval) { clearInterval(slideInterval); slideInterval = null; }
        if (totalSlides > 1) slideInterval = setInterval(nextSlide, 5000);
    }

    function handleResize() {
        if (!isTransitioning && slidesWrapper) {
            slidesWrapper.style.transition = 'none';
            slidesWrapper.style.transform = `translateX(-${currentSlide * getSlideWidth()}px)`;
            slidesWrapper.offsetHeight;
            slidesWrapper.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
        }
    }

    let touchStartX = 0, touchEndX = 0, isSwiping = false;
    function handleTouchStart(e) { touchStartX = e.changedTouches[0].screenX; isSwiping = true; if (slideInterval) clearInterval(slideInterval); }
    function handleTouchMove(e) { if (!isSwiping) return; touchEndX = e.changedTouches[0].screenX; if (Math.abs(touchStartX - touchEndX) > 10) e.preventDefault(); }
    function handleTouchEnd(e) {
        if (!isSwiping) return;
        isSwiping = false;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 50) { diff > 0 ? nextSlide() : prevSlide(); } else { resetAutoPlay(); }
        touchStartX = 0; touchEndX = 0;
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (totalSlides > 0) {
            if (slidesWrapper) {
                slidesWrapper.style.transition = 'none';
                slidesWrapper.style.transform = 'translateX(0px)';
                setTimeout(() => { slidesWrapper.style.transition = 'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)'; }, 100);
            }
            if (totalSlides > 1) slideInterval = setInterval(nextSlide, 5000);
            window.addEventListener('resize', handleResize);
            const heroSlideshow = document.getElementById('heroSlideshow');
            if (heroSlideshow) {
                heroSlideshow.addEventListener('touchstart', handleTouchStart, { passive: true });
                heroSlideshow.addEventListener('touchmove', handleTouchMove, { passive: false });
                heroSlideshow.addEventListener('touchend', handleTouchEnd, { passive: true });
            }
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.addEventListener('mouseenter', () => { if (slideInterval) clearInterval(slideInterval); });
                heroSection.addEventListener('mouseleave', resetAutoPlay);
            }
        }
        window.addEventListener('scroll', function () {}, { passive: true });
    });

    // ============================================================
    // LOANS AUTO SCROLL - Pause on interaction
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const scrollTrack = document.getElementById('loansScrollTrack');
        if (scrollTrack) {
            scrollTrack.addEventListener('mouseenter', function() {
                this.style.animationPlayState = 'paused';
            });
            scrollTrack.addEventListener('mouseleave', function() {
                this.style.animationPlayState = 'running';
            });
            scrollTrack.addEventListener('touchstart', function() {
                this.classList.add('touch-paused');
            });
            scrollTrack.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touch-paused');
                }, 3000);
            });
            scrollTrack.addEventListener('scroll', function() {
                this.style.animationPlayState = 'paused';
                clearTimeout(this.scrollTimeout);
                this.scrollTimeout = setTimeout(() => {
                    this.style.animationPlayState = 'running';
                }, 3000);
            });
        }
    });

    // ============================================================
    // HERO OFFERS AUTO SCROLL - Pause on interaction
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const heroOffersScroll = document.getElementById('heroOffersScroll');
        if (heroOffersScroll) {
            heroOffersScroll.addEventListener('mouseenter', function() {
                this.style.animationPlayState = 'paused';
            });
            heroOffersScroll.addEventListener('mouseleave', function() {
                this.style.animationPlayState = 'running';
            });
            heroOffersScroll.addEventListener('touchstart', function() {
                this.classList.add('touch-paused');
            });
            heroOffersScroll.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touch-paused');
                }, 3000);
            });
            heroOffersScroll.addEventListener('scroll', function() {
                this.style.animationPlayState = 'paused';
                clearTimeout(this.scrollTimeout);
                this.scrollTimeout = setTimeout(() => {
                    this.style.animationPlayState = 'running';
                }, 3000);
            });
        }
    });

    // ============================================================
    // APPLICATION FORM
    // ============================================================
    function openApplicationForm(loanType) {
        document.getElementById('loanTypeDisplay').value = loanType || 'Personal Loan';
        ['monthlyIncome','loanAmount','loanTenure'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('applicationModal').classList.add('active');
    }

    function submitApplication() {
        const type = document.getElementById('loanTypeDisplay').value;
        const name = document.getElementById('applicantName').value;
        if (!document.getElementById('loanAmount').value || !document.getElementById('loanTenure').value) {
            showToast('Please fill all required fields');
            return;
        }
        showToast('✓ ' + type + ' application submitted for ' + name + '!');
        closeModal('applicationModal');
    }

    // ============================================================
    // TOGGLE SECTIONS
    // ============================================================
    function toggleSection(sectionId, btnId) {
        const section = document.getElementById(sectionId);
        const btn = document.getElementById(btnId);
        section.classList.toggle('expanded');
        btn.classList.toggle('expanded');
        btn.innerHTML = section.classList.contains('expanded')
            ? '<i class="fas fa-chevron-up"></i> Show Less'
            : '<i class="fas fa-chevron-down"></i> View All Loans';
    }

    // ============================================================
    // MENU
    // ============================================================
    function toggleMenu() { ['menuBtn','menuDrawer','menuOverlay'].forEach(id => document.getElementById(id)?.classList.toggle('open')); }
    function closeMenu() { ['menuBtn','menuDrawer','menuOverlay'].forEach(id => document.getElementById(id)?.classList.remove('open')); }

    function logout() { document.getElementById('logoutConfirmModal').style.display = 'flex'; }
    function closeLogoutModal() { document.getElementById('logoutConfirmModal').style.display = 'none'; }
    function confirmLogout() { closeLogoutModal(); showToast('Logging out...'); setTimeout(() => { window.location.href = 'logout.php'; }, 500); }
    document.getElementById('logoutConfirmModal').addEventListener('click', function (e) { if (e.target === this) closeLogoutModal(); });

    // ============================================================
    // TOAST & MODAL HELPERS
    // ============================================================
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    function closeModal(id) { document.getElementById(id).classList.remove('active'); }
    document.querySelectorAll('.modal').forEach(m => m.addEventListener('click', function (e) { if (e.target === this) this.classList.remove('active'); }));

    // ============================================================
    // EXPOSE GLOBALLY
    // ============================================================
    Object.assign(window, {
        goToSlide, nextSlide, prevSlide, closeMenu, toggleMenu, logout, closeLogoutModal,
        confirmLogout, showToast, closeModal, toggleSection,
        openApplicationForm, submitApplication
    });


    // ============================================================
// PROFILE COMPLETENESS INTERCEPTOR (ADDED)
// ============================================================
// ===== PROFILE MODAL FUNCTIONS =====
function showProfileModal() {
    document.getElementById('profileModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeProfileModal() {
    document.getElementById('profileModal').classList.remove('active');
    document.body.style.overflow = '';
}

function goToProfile() {
    closeProfileModal();
    window.location.href = 'personal-details.php'; // change if needed
}

// Close modal when clicking outside
document.getElementById('profileModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProfileModal();
    }
});

// ============================================================
// PROFILE COMPLETENESS INTERCEPTOR (UPDATED – SHOWS MODAL)
// ============================================================
var profileComplete = <?php echo $profile_complete ? 'true' : 'false'; ?>;

if (!profileComplete) {
    document.addEventListener('click', function(e) {
        var target = e.target.closest('a, button, [onclick], .product-card, .calculator-card, .loan-card-scroll, .hero-offer-card-sm, .investment-card-sm, .insurance-card-sm');
        if (!target) return;

        // Allow safe actions: logout, menu, modal close, etc.
        if (target.closest('#logoutConfirmModal') || target.closest('.menu-drawer') || 
            target.closest('.modal-close') || target.closest('.drawer-close') ||
            target.closest('.custom-confirm-btn') || target.id === 'logoutBtn' ||
            target.closest('#profileModal')) { // allow clicks inside the modal itself
            return;
        }

        e.preventDefault();
        e.stopPropagation();
        showProfileModal(); // show the modal instead of toast
    }, true);
}

</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>