<?php
session_start();
include('../includes/dbConfig.php');

// If not logged in, redirect to login
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

// ============================================================
// FETCH OFFERS — same DB, just build the CRM image URL
// ============================================================
$offers = [];
$baseImageUrl = 'https://crm.logintoloans.com/';  // images live here

// Use the $conn already opened by dbConfig.php
$result = $conn->query(
    "SELECT id, image_path, category FROM tbl_loan_offers WHERE status = 1 ORDER BY id ASC"
);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['image_path'])) {
            // DB stores:  uploads/loan_offers/filename.png
            // We need:    https://crm.logintoloans.com/uploads/loan_offers/filename.png
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

// Fallback only if table is truly empty
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
        body { font-family: 'Inter', sans-serif; background: var(--background); color: var(--text-primary); overflow-x: hidden; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        .app-container { max-width: 480px; margin: 0 auto; background: var(--background); min-height: 100vh; position: relative; padding-bottom: 20px; }
        .page { display: none; animation: fadeIn 0.3s ease; }
        .page.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* ===== HERO ===== */
        .hero-section {
            position: relative; padding: 20px 20px 35px; color: white; overflow: hidden;
            border-radius: 0 0 30px 30px; min-height: 380px;
            display: flex; flex-direction: column; justify-content: space-between;
            background: linear-gradient(135deg, #1a237e, #283593); /* fallback bg */
        }
        .hero-slideshow { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
        .slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 1s ease-in-out; }
        .slide.active { opacity: 1; }
        /* ✅ <img> tag — reliable cross-domain, no background-image issues */
        .slide img { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; }
        .slide-category-badge {
            position: absolute; bottom: 80px; left: 20px; z-index: 3;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(8px);
            padding: 6px 16px; border-radius: 20px; color: white;
            font-size: 12px; font-weight: 600; border: 1px solid rgba(255,255,255,0.2);
        }
        .hero-section .overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(135deg,rgba(26,35,126,0.55) 0%,rgba(57,73,171,0.65) 100%); z-index: 1;
        }
        .hero-section .content { position: relative; z-index: 2; }
        .greeting { margin-bottom: 20px; }
        .greeting h2 { font-size: 26px; font-weight: 700; margin-bottom: 6px; }
        .greeting p { font-size: 14px; opacity: 0.9; }
        .tagline h1 { font-size: 26px; font-weight: 800; line-height: 1.3; margin-bottom: 20px; }
        .features-list { margin: 15px 0 20px; }
        .feature-item { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; font-size: 15px; }
        .feature-item i { font-size: 18px; width: 24px; color: #ff9800; }
        .know-more-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.2); backdrop-filter: blur(10px);
            padding: 10px 24px; border-radius: 30px; font-size: 14px; font-weight: 600;
            cursor: pointer; margin-top: 10px; transition: all 0.2s; border: none; color: white;
        }
        .know-more-btn:active { background: rgba(255,255,255,0.3); transform: scale(0.97); }
        .slide-indicators { position: absolute; bottom: 15px; left: 0; right: 0; display: flex; justify-content: center; gap: 8px; z-index: 3; }
        .indicator { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.5); cursor: pointer; transition: all 0.3s; }
        .indicator.active { width: 20px; border-radius: 10px; background: white; }

        /* ===== SECTION HEADERS ===== */
        .section-header { display: flex; justify-content: space-between; align-items: baseline; padding: 0 16px; margin: 24px 0 12px; }
        .section-header h2 { font-size: 18px; font-weight: 700; color: var(--text-primary); }
        .section-header a { font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600; cursor: pointer; }

        /* ===== RECOMMENDED GRID ===== */
        .recommended-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 12px; padding: 0 16px; }
        .recommend-card { background: var(--white); border-radius: 20px; padding: 16px; box-shadow: var(--shadow); cursor: pointer; transition: all 0.2s; border: 1px solid var(--border); display: flex; flex-direction: column; align-items: center; text-align: center; }
        .recommend-card:active { transform: scale(0.96); background: #fef3e8; }
        .recommend-card i { font-size: 32px; margin-bottom: 10px; }
        .recommend-card .card-title { font-size: 13px; font-weight: 700; margin-bottom: 4px; color: var(--text-primary); }
        .recommend-card .card-subtitle { font-size: 10px; color: var(--text-secondary); }
        .cashback-badge { background: linear-gradient(135deg,#ff9800,#ff5722); color: white; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; display: inline-block; margin-top: 8px; }

        /* ===== LOANS GRID ===== */
        .loans-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 0 16px; }
        .loan-card {
            border-radius: 16px; padding: 16px; display: flex; align-items: center; gap: 14px;
            cursor: pointer; transition: all 0.2s; position: relative; overflow: hidden;
            min-height: 90px; background: linear-gradient(135deg, #1a237e, #283593);
        }
        .loan-card .loan-bg-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; z-index: 0; }
        .loan-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1; }
        .loan-card:active { transform: scale(0.97); }
        .loan-card .loan-image, .loan-card .loan-info { position: relative; z-index: 2; }
        .loan-image { width: 52px; height: 52px; border-radius: 50%; background: rgba(255,255,255,0.9); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .loan-image i { font-size: 28px; }
        .loan-info h4 { font-size: 14px; font-weight: 700; margin-bottom: 4px; color: white; text-shadow: 0 1px 2px rgba(0,0,0,0.3); }
        .loan-info p { font-size: 11px; color: rgba(255,255,255,0.9); }

        /* ===== MODALS ===== */
        .modal { position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 1000; display: flex; align-items: center; justify-content: center; visibility: hidden; opacity: 0; transition: 0.2s; }
        .modal.active { visibility: visible; opacity: 1; }
        .modal-content { background: var(--white); border-radius: 28px; padding: 24px; width: 90%; max-width: 380px; max-height: 85vh; overflow-y: auto; animation: modalPop 0.3s ease; }
        @keyframes modalPop { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid var(--border); }
        .modal-header h3 { font-size: 1.2rem; font-weight: 700; color: var(--primary); }
        .modal-close { width: 32px; height: 32px; border-radius: 50%; background: var(--border); border: none; cursor: pointer; font-size: 16px; transition: 0.2s; }
        .modal-close:hover { background: #e0e0e0; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; color: var(--text-secondary); }
        .form-group label i { margin-right: 6px; color: var(--accent); }
        .form-input { width: 100%; padding: 12px 14px; border: 1.5px solid var(--border); border-radius: 14px; font-size: 14px; font-family: 'Inter', sans-serif; transition: 0.2s; }
        .form-input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(255,107,53,0.1); }
        .apply-button { background: linear-gradient(135deg,var(--accent),#ff8c42); color: white; border: none; padding: 14px 24px; border-radius: 40px; font-weight: 700; cursor: pointer; width: 100%; transition: transform 0.2s; font-size: 14px; margin-top: 10px; }
        .apply-button:active { transform: scale(0.98); }
        .toast { position: fixed; bottom: 100px; left: 50%; transform: translateX(-50%) translateY(100px); background: var(--text-primary); color: white; padding: 12px 20px; border-radius: 30px; font-size: 13px; z-index: 1000; transition: transform 0.3s; white-space: nowrap; }
        .toast.show { transform: translateX(-50%) translateY(0); }
        .footer-note { text-align: center; font-size: 10px; color: var(--text-tertiary); padding: 16px; border-top: 1px solid var(--border); margin-top: 20px; }

        /* ===== DRAWER ===== */
        .menu-drawer { position: fixed; top: 0; left: -280px; width: 280px; height: 100vh; background: #ffffff; z-index: 500; transition: left 0.3s ease; box-shadow: 4px 0 24px rgba(0,0,0,0.15); padding-top: 70px; overflow-y: auto; }
        .menu-drawer.open { left: 0; }
        .menu-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 499; opacity: 0; pointer-events: none; transition: opacity 0.3s; }
        .menu-overlay.open { opacity: 1; pointer-events: all; }
        .drawer-close { position: absolute; top: 16px; right: 16px; width: 36px; height: 36px; border: none; background: #f5f5f5; border-radius: 50%; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .drawer-user { padding: 0 20px 20px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 12px; }
        .drawer-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #ff9800, #ff5722); display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800; color: #fff; flex-shrink: 0; overflow: hidden; }
        .drawer-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .drawer-user-info .name { font-size: 15px; font-weight: 700; color: #1a1a2e; }
        .drawer-user-info .email { font-size: 11px; color: #999; margin-top: 2px; }
        .drawer-nav { padding: 16px 0; }
        .drawer-item { display: flex; align-items: center; gap: 14px; padding: 14px 24px; cursor: pointer; transition: background 0.2s; font-size: 15px; font-weight: 500; color: #1a1a2e; text-decoration: none; border: none; background: transparent; width: 100%; text-align: left; }
        .drawer-item:active { background: #f5f7ff; }
        .drawer-item i { width: 22px; color: #1a237e; font-size: 17px; }
        .drawer-item.logout-drawer-item { color: #ef4444 !important; border-top: 1px solid #f0f0f0; margin-top: 12px; }
        .drawer-item.logout-drawer-item i { color: #ef4444 !important; }

        /* ===== LOGOUT CONFIRM ===== */
        .custom-confirm-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 10000; display: none; align-items: center; justify-content: center; }
        .custom-confirm-modal { background: var(--white); border-radius: 24px; width: 320px; max-width: 85%; padding: 24px; text-align: center; animation: modalPop 0.3s ease; box-shadow: var(--shadow-lg); }
        .custom-confirm-icon { width: 70px; height: 70px; border-radius: 50%; background: #fee2e2; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
        .custom-confirm-icon i { font-size: 32px; color: #ef4444; }
        .custom-confirm-title { font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; }
        .custom-confirm-message { font-size: 14px; color: var(--text-secondary); margin-bottom: 24px; line-height: 1.5; }
        .custom-confirm-buttons { display: flex; gap: 12px; }
        .custom-confirm-btn { flex: 1; padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-family: 'Inter', sans-serif; }
        .custom-confirm-btn-cancel { background: #f0f0f0; color: var(--text-secondary); }
        .custom-confirm-btn-logout { background: linear-gradient(135deg,#ef4444,#dc2626); color: white; }
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
                <?php foreach ($offers as $index => $offer): ?>
                    <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
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
            <div class="overlay"></div>
            <div class="content">
               

            </div>
            <div class="slide-indicators" id="slideIndicators">
                <?php for ($i = 0; $i < count($offers); $i++): ?>
                    <div class="indicator <?php echo $i === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $i; ?>)"></div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- RECOMMENDED -->
        <div id="loanSection">
            <div class="section-header">
                <h2>✨ RECOMMENDED FOR YOU</h2>
                <a href="#" onclick="window.location.href='offers.php'">View All</a>
            </div>
            <div class="recommended-grid">
                <div class="recommend-card" onclick="openLoanApplication('Cashback Offer','Get Rs.200 Cashback on first loan')">
                    <i class="fas fa-tag" style="color:#ff9800;"></i>
                    <div class="card-title">Cashback Offer</div>
                    <div class="cashback-badge">Rs. 200</div>
                </div>
                <div class="recommend-card" onclick="openLoanApplication('Personal Loan','Up to ₹50 Lakhs at low interest rates')">
                    <i class="fas fa-user-friends" style="color:#1a237e;"></i>
                    <div class="card-title">Personal Loan</div>
                    <div class="card-subtitle">Up to ₹50L</div>
                </div>
                <div class="recommend-card" onclick="openLoanApplication('Credit Cards','Unlimited rewards & cashback')">
                    <i class="fas fa-credit-card" style="color:#00c853;"></i>
                    <div class="card-title">Credit Cards</div>
                    <div class="card-subtitle">Rewards</div>
                </div>
                <div class="recommend-card" onclick="openLoanApplication('Home Loan','Low interest rates up to ₹5 Crore')">
                    <i class="fas fa-home" style="color:#9b59b6;"></i>
                    <div class="card-title">Loan above 25 Lakh</div>
                    <div class="card-subtitle">Low interest</div>
                </div>
                <div class="recommend-card" onclick="openLoanApplication('Instant Personal Loan','5 minute approval with minimal documents')">
                    <i class="fas fa-bolt" style="color:#ff5722;"></i>
                    <div class="card-title">Instant Personal Loan</div>
                    <div class="card-subtitle">5 min approval</div>
                </div>
                <div class="recommend-card" onclick="openLoanApplication('Festival Offers','Limited period special discounts')">
                    <i class="fas fa-gift" style="color:#e91e63;"></i>
                    <div class="card-title">Festival Offers</div>
                    <div class="card-subtitle">Limited period</div>
                </div>
            </div>
        </div>

        <!-- LOANS -->
        <div class="section-header">
            <h2>💰 LOANS</h2>
            <a href="#" onclick="window.location.href='offers.php'">See All</a>
        </div>
        <div class="loans-grid">
            <?php
            $loanIcons     = ['fas fa-tag','fas fa-gift','fas fa-user-friends','fas fa-briefcase','fas fa-bolt','fas fa-home'];
            $loanColors    = ['#ff9800','#e91e63','#1a237e','#00c853','#ff5722','#9b59b6'];
            $loanTitles    = ['Cashback Offer','Limited Period Offer','Personal Loan','Business Loan','Instant Personal Loan','Home Loan'];
            $loanSubtitles = ['Rs. 200 Cashback','Limited period','Up to ₹50 Lakhs','MSME approved','5 min approval','Up to ₹5 Crore'];
            $loanDescs     = ['Get Rs.200 instant cashback','Special festive discounts','Up to ₹50 Lakhs flexible tenure','MSME approved up to ₹2 Crore','Loan in 5 minutes zero paperwork','Up to ₹5 Crore lowest interest'];

            $displayOffers = array_slice($offers, 0, 6);
            for ($i = count($displayOffers); $i < 6; $i++) {
                $displayOffers[] = ['id'=>0,'image_path'=>'','category'=>$loanTitles[$i],'title'=>$loanTitles[$i],'description'=>$loanDescs[$i]];
            }

            foreach ($displayOffers as $index => $offer):
                $imgSrc   = htmlspecialchars($offer['image_path'] ?? '');
                $title    = htmlspecialchars($offer['title']       ?? $loanTitles[$index]);
                $subtitle = htmlspecialchars($offer['description'] ?? $loanSubtitles[$index]);
                $desc     = htmlspecialchars($offer['description'] ?? $loanDescs[$index]);
                $icon     = $loanIcons[$index % 6];
                $color    = $loanColors[$index % 6];
            ?>
            <div class="loan-card" onclick="openLoanApplication('<?php echo $title; ?>','<?php echo $desc; ?>')">
                <?php if (!empty($imgSrc)): ?>
                    <img class="loan-bg-img" src="<?php echo $imgSrc; ?>" alt="<?php echo $title; ?>" loading="lazy" onerror="this.remove();">
                <?php endif; ?>
                <div class="loan-image"><i class="<?php echo $icon; ?>" style="color:<?php echo $color; ?>;"></i></div>
                <div class="loan-info">
                    <h4><?php echo $title; ?></h4>
                    <p><?php echo $subtitle; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="footer-note"><i class="fas fa-lock"></i> Secure & Trusted | RBI Compliant</div>
    </div>
</div>

<!-- LOAN APPLICATION MODAL -->
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

<!-- NEW LEAD MODAL -->
<div id="newLeadModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Add New Lead</h3>
            <button class="modal-close" onclick="closeModal('newLeadModal')">✕</button>
        </div>
        <div class="form-group">
            <label><i class="fas fa-user"></i> Customer Name</label>
            <input type="text" class="form-input" id="leadName" placeholder="Enter customer name">
        </div>
        <div class="form-group">
            <label><i class="fas fa-phone"></i> Phone Number</label>
            <input type="tel" class="form-input" id="leadPhone" placeholder="10-digit number">
        </div>
        <div class="form-group">
            <label><i class="fas fa-hand-holding-usd"></i> Loan Type</label>
            <select class="form-input" id="leadLoanType">
                <option value="">Select Loan Type</option>
                <option>Personal Loan</option><option>Home Loan</option>
                <option>Car Loan</option><option>Business Loan</option>
                <option>Gold Loan</option><option>Education Loan</option>
            </select>
        </div>
        <div class="form-group">
            <label><i class="fas fa-rupee-sign"></i> Loan Amount (₹)</label>
            <input type="number" class="form-input" id="leadAmount" placeholder="Enter loan amount">
        </div>
        <button class="apply-button" onclick="submitNewLead()"><i class="fas fa-plus-circle"></i> Add Lead →</button>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
    // SLIDESHOW
    let currentSlide = 0, slideInterval;
    const getSlides = () => document.querySelectorAll('#heroSlideshow .slide');
    const getInds   = () => document.querySelectorAll('#slideIndicators .indicator');

    function showSlide(i) {
        const slides = getSlides(), inds = getInds();
        if (!slides.length) return;
        slides.forEach((s,j) => s.classList.toggle('active', j===i));
        inds.forEach((d,j)   => d.classList.toggle('active', j===i));
        currentSlide = i;
    }
    function nextSlide() { const s=getSlides(); if(s.length) showSlide((currentSlide+1)%s.length); }
    function goToSlide(i){ showSlide(i); startSlideshow(); }
    function startSlideshow() {
        if (slideInterval) clearInterval(slideInterval);
        if (getSlides().length > 1) slideInterval = setInterval(nextSlide, 4000);
    }
    document.addEventListener('DOMContentLoaded', () => { if(getSlides().length){ showSlide(0); startSlideshow(); } });
    const hero = document.querySelector('.hero-section');
    if (hero) {
        hero.addEventListener('mouseenter', () => clearInterval(slideInterval));
        hero.addEventListener('mouseleave', startSlideshow);
    }

    // NAVIGATION
    function navigateTo(pageId, el) {
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
        document.getElementById(pageId).classList.add('active');
        if (el) { document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active')); el.classList.add('active'); }
        window.scrollTo({top:0,behavior:'smooth'});
        closeMenu();
    }

    // MENU
    function toggleMenu(){ ['menuBtn','menuDrawer','menuOverlay'].forEach(id=>document.getElementById(id)?.classList.toggle('open')); }
    function closeMenu() { ['menuBtn','menuDrawer','menuOverlay'].forEach(id=>document.getElementById(id)?.classList.remove('open')); }

    // LOGOUT
    function logout()           { document.getElementById('logoutConfirmModal').style.display='flex'; }
    function closeLogoutModal() { document.getElementById('logoutConfirmModal').style.display='none'; }
    function confirmLogout()    { closeLogoutModal(); showToast('Logging out...'); setTimeout(()=>{ window.location.href='logout.php'; },500); }
    document.getElementById('logoutConfirmModal').addEventListener('click',function(e){ if(e.target===this) closeLogoutModal(); });

    // LOAN APPLICATION
    function openLoanApplication(type, desc) {
        document.getElementById('loanTypeDisplay').value = type;
        ['monthlyIncome','loanAmount','loanTenure'].forEach(id=>document.getElementById(id).value='');
        document.getElementById('applicationModal').classList.add('active');
    }
    function submitApplication() {
        const type=document.getElementById('loanTypeDisplay').value;
        const name=document.getElementById('applicantName').value;
        if(!document.getElementById('loanAmount').value||!document.getElementById('loanTenure').value){ showToast('Please fill all required fields'); return; }
        showToast('✓ '+type+' application submitted for '+name+'!');
        closeModal('applicationModal');
    }

    // LEADS
    function openNewLeadModal(){ document.getElementById('newLeadModal').classList.add('active'); }
    function submitNewLead() {
        const name=document.getElementById('leadName').value, type=document.getElementById('leadLoanType').value, amt=document.getElementById('leadAmount').value;
        if(!name||!type||!amt){ showToast('Please fill all required fields'); return; }
        showToast('✓ Lead added for '+name+' - '+type+' of ₹'+parseInt(amt).toLocaleString());
        closeModal('newLeadModal');
        ['leadName','leadPhone','leadAmount'].forEach(id=>document.getElementById(id).value='');
        document.getElementById('leadLoanType').value='';
    }

    // UTILS
    function showToast(msg){ const t=document.getElementById('toast'); t.textContent=msg; t.classList.add('show'); setTimeout(()=>t.classList.remove('show'),3000); }
    function closeModal(id){ document.getElementById(id).classList.remove('active'); }
    document.querySelectorAll('.modal').forEach(m=>m.addEventListener('click',function(e){ if(e.target===this) this.classList.remove('active'); }));

    Object.assign(window,{goToSlide,closeMenu,toggleMenu,logout,closeLogoutModal,confirmLogout,openLoanApplication,submitApplication,showToast,closeModal,navigateTo,openNewLeadModal,submitNewLead});
</script>
</body>
<?php include('../includes/footer-nav.php'); ?>
</html>