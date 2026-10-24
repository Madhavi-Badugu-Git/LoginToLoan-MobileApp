<?php
// FILE: privacy.php
// Full-width elegant Privacy Policy page with Orange & Navy Blue theme
// Integrated with main app header and footer

include('includes/dbConfig.php');



include('includes/header-nav.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Privacy Policy | LoginToLoans</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Syne:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F8FAFE;
            color: #1A2C3E;
            line-height: 1.65;
            overflow-x: hidden;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: #F8FAFE;
            min-height: 100vh;
            position: relative;
            padding-bottom: 75px;
        }

        /* Navy & Orange Gradient Theme */
        :root {
            --navy-deep: #0B2B44;
            --navy-soft: #1E4A6E;
            --orange-primary: #F05A28;
            --orange-light: #FF7A38;
            --orange-glow: #FFB07C;
            --gray-muted: #5B6E8C;
            --primary: #1a237e;
            --primary-dark: #0d1757;
            --primary-light: #283593;
            --secondary: #00c853;
            --accent: #ff6b35;
            --text-primary: #1a1a2e;
            --text-secondary: #666666;
            --text-tertiary: #999999;
            --background: #f8f9fc;
            --white: #ffffff;
            --border: #eef2f6;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

     
       

        /* Hero section - full width with gradient */
        .privacy-hero {
            width: 100%;
            background: linear-gradient(135deg, #0B2B44 0%, #1E4A6E 50%, #0F3B5C 100%);
            padding: 60px 5% 50px;
            text-align: center;
            border-bottom: 4px solid #F05A28;
        }

        .privacy-hero h1 {
            font-family: 'Syne', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
        }

        .privacy-hero p {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.85);
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .last-updated {
            display: inline-block;
            margin-top: 20px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.1);
            padding: 6px 18px;
            border-radius: 40px;
        }

        /* Content container - full width, inner padding only */
        .privacy-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px 60px;
        }

        /* Each section - clean, no boxes, just bottom border separator */
        .policy-section {
            margin-bottom: 40px;
            padding-bottom: 28px;
            border-bottom: 1px solid #E8EDF2;
        }

        .policy-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        /* Section headers with orange gradient accent */
        .policy-section h2 {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #F05A28 0%, #1E4A6E 80%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
            letter-spacing: -0.01em;
        }

        .policy-section h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 20px 0 12px 0;
            color: #1E4A6E;
        }

        .policy-section p {
            font-size: 0.95rem;
            color: #2C3E50;
            margin-bottom: 16px;
            line-height: 1.7;
        }

        /* Two column grid for info lists */
        .grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 20px;
            margin: 16px 0 12px;
        }

        .grid-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 0;
            font-size: 0.9rem;
            color: #2C3E50;
            border-bottom: 1px dashed #EDF2F7;
        }

        .grid-item i {
            width: 20px;
            color: #F05A28;
            font-size: 0.9rem;
        }

        /* Bullet list */
        .bullet-list {
            list-style: none;
            margin: 14px 0;
        }

        .bullet-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: #2C3E50;
        }

        .bullet-list li i {
            color: #F05A28;
            margin-top: 3px;
            font-size: 0.8rem;
        }

        /* Tech info in clean rows */
        .tech-rows {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 20px;
            margin: 14px 0 6px;
        }

        .tech-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 0;
            font-size: 0.88rem;
            border-bottom: 1px solid #F0F4F9;
        }

        .tech-row i {
            width: 22px;
            color: #F05A28;
        }

        /* Rights grid - clean cards without borders */
        .rights-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 20px 0 12px;
        }

        .right-item {
            text-align: center;
            padding: 14px 10px;
            background: #F8FAFE;
            border-radius: 16px;
            transition: all 0.2s;
        }

        .right-item i {
            font-size: 1.4rem;
            color: #F05A28;
            margin-bottom: 8px;
            display: block;
        }

        .right-item span {
            font-size: 0.8rem;
            font-weight: 500;
            color: #1E4A6E;
        }

        .right-item:active {
            background: #FFF4ED;
            transform: scale(0.97);
        }

        /* Highlight badge */
        .highlight-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FFF4ED;
            padding: 8px 16px;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #F05A28;
            margin: 12px 0 6px;
            flex-wrap: wrap;
        }

        /* Contact section - minimal */
        .contact-block {
            background: #F8FAFE;
            padding: 24px 20px;
            border-radius: 20px;
            margin-top: 16px;
            text-align: center;
        }

        .contact-block i {
            font-size: 1.8rem;
            color: #F05A28;
            margin-bottom: 10px;
        }

        .contact-block p {
            margin-bottom: 6px;
        }

        .btn-soft {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            border: 1.5px solid #F05A28;
            padding: 8px 24px;
            border-radius: 40px;
            font-weight: 600;
            color: #F05A28;
            text-decoration: none;
            margin-top: 12px;
            transition: all 0.25s;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .btn-soft:active {
            background: #F05A28;
            color: white;
            transform: scale(0.97);
        }

        
      

        /* Back to top */
        .back-to-top {
            position: fixed;
            bottom: 90px;
            right: 20px;
            background: linear-gradient(135deg, #F05A28, #FF7A38);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(240,90,40,0.3);
            z-index: 100;
            border: none;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:active {
            transform: scale(0.92);
        }

        /* Responsive */
        @media (max-width: 600px) {
            .grid-2col { grid-template-columns: 1fr; }
            .tech-rows { grid-template-columns: 1fr; }
            .rights-grid { grid-template-columns: repeat(2, 1fr); }
            .privacy-hero h1 { font-size: 2rem; }
            .privacy-hero { padding: 40px 5% 35px; }
            .privacy-content { padding: 30px 16px 50px; }
            .policy-section h2 { font-size: 1.3rem; }
        }

        @media (max-width: 400px) {
            .rights-grid { grid-template-columns: repeat(2, 1fr); }
            .privacy-hero h1 { font-size: 1.6rem; }
            .privacy-hero p { font-size: 0.85rem; }
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
<div class="app-container">

   

    <!-- Hero Section - Full Width Gradient -->
    <div class="privacy-hero">
        <h1>Privacy Policy</h1>
        <p>At LoginToLoans, protecting your privacy and personal information is important to us. This Privacy Policy explains how we collect, use, store, disclose, and safeguard information when you access our website, submit enquiries, apply for financial services, or interact with our platform.</p>
        <div class="last-updated">
            <i class="far fa-calendar-alt"></i> Last Updated: June 15, 2026
        </div>
    </div>

    <!-- Content - No boxes, just clean flowing sections -->
    <div class="privacy-content">

        <!-- Section 1 -->
        <div class="policy-section">
            <h2>1. Information We Collect</h2>
            <p>We may collect information including:</p>
            
            <h3>Personal Information</h3>
            <div class="grid-2col">
                <div class="grid-item"><i class="fas fa-user"></i> Full Name</div>
                <div class="grid-item"><i class="fas fa-mobile-alt"></i> Mobile Number</div>
                <div class="grid-item"><i class="fas fa-envelope"></i> Email Address</div>
                <div class="grid-item"><i class="fas fa-map-marker-alt"></i> Address Details</div>
                <div class="grid-item"><i class="fas fa-briefcase"></i> Employment Information</div>
                <div class="grid-item"><i class="fas fa-store"></i> Business Information</div>
                <div class="grid-item"><i class="fas fa-rupee-sign"></i> Financial Details</div>
                <div class="grid-item"><i class="fas fa-hand-holding-usd"></i> Loan Requirements</div>
                <div class="grid-item"><i class="fas fa-id-card"></i> Identity & verification documents</div>
            </div>

            <h3>Technical Information</h3>
            <div class="tech-rows">
                <div class="tech-row"><i class="fas fa-network-wired"></i> IP Address</div>
                <div class="tech-row"><i class="fab fa-chrome"></i> Browser Type</div>
                <div class="tech-row"><i class="fas fa-mobile-alt"></i> Device Information</div>
                <div class="tech-row"><i class="fab fa-windows"></i> Operating System</div>
                <div class="tech-row"><i class="fas fa-chart-line"></i> Website activity & interaction data</div>
                <div class="tech-row"><i class="fas fa-cookie-bite"></i> Cookies & analytics information</div>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="policy-section">
            <h2>2. How We Collect Information</h2>
            <p>We collect information through:</p>
            <div class="grid-2col">
                <div class="grid-item"><i class="fas fa-file-alt"></i> Online enquiry forms</div>
                <div class="grid-item"><i class="fas fa-file-signature"></i> Loan application forms</div>
                <div class="grid-item"><i class="fas fa-id-card"></i> Contact forms</div>
                <div class="grid-item"><i class="fas fa-envelope-open-text"></i> Email communication</div>
                <div class="grid-item"><i class="fas fa-headset"></i> Customer support interactions</div>
                <div class="grid-item"><i class="fas fa-chart-pie"></i> Cookies & website analytics tools</div>
                <div class="grid-item"><i class="fas fa-user-check"></i> Account registration & login processes</div>
            </div>
        </div>

        <!-- Section 3 -->
        <div class="policy-section">
            <h2>3. How We Use Your Information</h2>
            <p>Your information may be used to:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-check-circle"></i> Process enquiries and service requests</li>
                <li><i class="fas fa-check-circle"></i> Assist with loan and financial applications</li>
                <li><i class="fas fa-check-circle"></i> Verify submitted details</li>
                <li><i class="fas fa-check-circle"></i> Improve website functionality and user experience</li>
                <li><i class="fas fa-check-circle"></i> Communicate updates and service information</li>
                <li><i class="fas fa-check-circle"></i> Respond to customer support requests</li>
                <li><i class="fas fa-check-circle"></i> Prevent fraud and maintain security</li>
                <li><i class="fas fa-check-circle"></i> Comply with legal and regulatory obligations</li>
            </ul>
        </div>

        <!-- Section 4 -->
        <div class="policy-section">
            <h2>4. Sharing of Information</h2>
            <p>LoginToLoans may share information only where necessary with:</p>
            <div class="grid-2col">
                <div class="grid-item"><i class="fas fa-university"></i> Financial institutions</div>
                <div class="grid-item"><i class="fas fa-landmark"></i> Banks and lending partners</div>
                <div class="grid-item"><i class="fas fa-shield-alt"></i> Verification service providers</div>
                <div class="grid-item"><i class="fas fa-cloud"></i> Technology & operational service providers</div>
                <div class="grid-item"><i class="fas fa-gavel"></i> Government or regulatory authorities when required by law</div>
            </div>
            <div class="highlight-badge">
                <i class="fas fa-ban"></i> We do not sell personal information to unrelated third parties.
            </div>
        </div>

        <!-- Section 5 -->
        <div class="policy-section">
            <h2>5. Data Security</h2>
            <p>We implement reasonable technical and administrative safeguards to protect information against unauthorized access, misuse, alteration, or disclosure.</p>
            <p>While we strive to protect your information, no online transmission or storage system can be guaranteed to be completely secure.</p>
            <div class="highlight-badge">
                <i class="fas fa-shield-halved"></i> 256-bit SSL • Regular security audits • Role-based access
            </div>
        </div>

        <!-- Section 6 -->
        <div class="policy-section">
            <h2>6. Cookies & Tracking Technologies</h2>
            <p>Our website may use cookies and similar technologies to:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-tachometer-alt"></i> Improve website performance</li>
                <li><i class="fas fa-user-check"></i> Remember user preferences</li>
                <li><i class="fas fa-chart-line"></i> Monitor website traffic</li>
                <li><i class="fas fa-globe"></i> Enhance browsing experience</li>
            </ul>
            <p>Users may control cookie preferences through browser settings.</p>
        </div>

        <!-- Section 7 -->
        <div class="policy-section">
            <h2>7. Data Retention</h2>
            <p>Personal information is retained only for as long as necessary to:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-concierge-bell"></i> Deliver requested services</li>
                <li><i class="fas fa-chalkboard-user"></i> Meet operational requirements</li>
                <li><i class="fas fa-balance-scale"></i> Fulfill legal and regulatory obligations</li>
                <li><i class="fas fa-folder-open"></i> Resolve disputes and maintain records</li>
            </ul>
        </div>

        <!-- Section 8 -->
        <div class="policy-section">
            <h2>8. Third-Party Links</h2>
            <p>Our website may contain links to external websites or partner services. LoginToLoans is not responsible for the privacy practices or content of third-party websites. Users are advised to review the privacy policies of such third-party websites before sharing any personal information.</p>
        </div>

        <!-- Section 9 -->
        <div class="policy-section">
            <h2>9. User Rights</h2>
            <p>Subject to applicable laws, users may request to:</p>
            <div class="rights-grid">
                <div class="right-item"><i class="fas fa-eye"></i><span>Access personal information</span></div>
                <div class="right-item"><i class="fas fa-edit"></i><span>Correct inaccurate information</span></div>
                <div class="right-item"><i class="fas fa-sync-alt"></i><span>Update details</span></div>
                <div class="right-item"><i class="fas fa-user-slash"></i><span>Withdraw consent where permitted</span></div>
                <div class="right-item"><i class="fas fa-trash-alt"></i><span>Request deletion of eligible information</span></div>
            </div>
            <p>Requests may be submitted through our contact channels below.</p>
        </div>

        <!-- Section 10 -->
        <div class="policy-section">
            <h2>10. Children's Privacy</h2>
            <p>Our website and services are not intended for individuals under 18 years of age. We do not intentionally collect or maintain personal information from individuals under the age of 18.</p>
        </div>

        <!-- Section 11 -->
        <div class="policy-section">
            <h2>11. Policy Updates</h2>
            <p>We may revise this Privacy Policy periodically. Updated versions shall become effective once published on this website. Continued use of the website constitutes acceptance of the updated Privacy Policy.</p>
            <div class="highlight-badge">
                <i class="fas fa-bell"></i> We'll notify you about significant changes via email or website notice.
            </div>
        </div>

    </div>

  

</div>

<script>
    // Back to top functionality
    const backBtn = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            backBtn.classList.add('show');
        } else {
            backBtn.classList.remove('show');
        }
    });
    backBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Simple fade-in for sections
    const sections = document.querySelectorAll('.policy-section');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.05 });

    sections.forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(section);
    });
</script>
</body>
<?php include('includes/footer-nav.php'); ?>
</html>