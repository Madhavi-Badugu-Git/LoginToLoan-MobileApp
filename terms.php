<?php
// FILE: terms.php
// Full-width elegant Terms & Conditions page with Orange & Navy Blue theme
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
    <title>Terms & Conditions | LoginToLoans</title>
    
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
        .terms-hero {
            width: 100%;
            background: linear-gradient(135deg, #0B2B44 0%, #1E4A6E 50%, #0F3B5C 100%);
            padding: 60px 5% 50px;
            text-align: center;
            border-bottom: 4px solid #F05A28;
        }

        .terms-hero h1 {
            font-family: 'Syne', sans-serif;
            font-size: 2.4rem;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
        }

        .terms-hero p {
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
        .terms-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px 60px;
        }

        /* Each section - clean, no boxes, just bottom border separator */
        .terms-section {
            margin-bottom: 40px;
            padding-bottom: 28px;
            border-bottom: 1px solid #E8EDF2;
        }

        .terms-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        /* Section headers with orange gradient accent */
        .terms-section h2 {
            font-family: 'Syne', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 18px;
            background: linear-gradient(135deg, #F05A28 0%, #1E4A6E 80%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
            letter-spacing: -0.01em;
        }

        .terms-section h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 18px 0 10px 0;
            color: #1E4A6E;
        }

        .terms-section p {
            font-size: 0.95rem;
            color: #2C3E50;
            margin-bottom: 14px;
            line-height: 1.7;
        }

        /* Service grid */
        .service-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 16px 0 12px;
        }

        .service-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            font-size: 0.85rem;
            color: #2C3E50;
            background: #F8FAFE;
            border-radius: 12px;
            border: 1px solid #EDF2F7;
            transition: all 0.2s;
        }

        .service-item:active {
            background: #FFF4ED;
            transform: scale(0.98);
        }

        .service-item i {
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

        .warning-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FEF3C7;
            padding: 8px 16px;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #D97706;
            margin: 12px 0 6px;
            flex-wrap: wrap;
        }

        /* Two column grid */
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

        /* Document grid */
        .doc-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 14px 0;
        }

        .doc-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background: #F8FAFE;
            border-radius: 12px;
            font-size: 0.85rem;
            border: 1px solid #EDF2F7;
        }

        .doc-item:active {
            background: #FFF4ED;
            transform: scale(0.98);
        }

        .doc-item i {
            color: #F05A28;
        }

        /* ===== BOTTOM NAV (SAME AS INDEX.PHP) ===== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            max-width: 480px;
            width: 100%;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(20px);
            display: flex;
            justify-content: space-around;
            padding: 8px 16px 20px;
            border-top: 1px solid var(--border);
            z-index: 100;
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: var(--text-tertiary);
            font-weight: 500;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 30px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .nav-item i { font-size: 20px; }
        .nav-item.active { color: var(--primary); background: rgba(26,35,126,0.1); }

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
            .service-grid { grid-template-columns: 1fr; }
            .grid-2col { grid-template-columns: 1fr; }
            .doc-grid { grid-template-columns: 1fr; }
            .terms-hero h1 { font-size: 2rem; }
            .terms-hero { padding: 40px 5% 35px; }
            .terms-content { padding: 30px 16px 50px; }
            .terms-section h2 { font-size: 1.3rem; }
        }

        @media (max-width: 400px) {
            .terms-hero h1 { font-size: 1.6rem; }
            .terms-hero p { font-size: 0.85rem; }
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Link styling */
        .terms-section a {
            color: #F05A28;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-section a:active {
            opacity: 0.7;
        }
    </style>
</head>
<body>
<div class="app-container">

  

    <!-- Hero Section - Full Width Gradient -->
    <div class="terms-hero">
        <h1>Terms & Conditions</h1>
        <p>Please read these Terms & Conditions carefully before using LoginToLoans website and services. By accessing or using our platform, you agree to be bound by these terms.</p>
        <div class="last-updated">
            <i class="far fa-calendar-alt"></i> Last Updated: June 15, 2026
        </div>
    </div>

    <!-- Content - No boxes, just clean flowing sections -->
    <div class="terms-content">

        <!-- Section 1: About LoginToLoans -->
        <div class="terms-section">
            <h2>1. About LoginToLoans</h2>
            <p>LoginToLoans is a financial solutions and loan assistance platform that helps customers explore and apply for suitable financial products through partner institutions and service networks.</p>
            <p>Our services may include:</p>
            <div class="service-grid">
                <div class="service-item"><i class="fas fa-user-friends"></i> Personal Loans</div>
                <div class="service-item"><i class="fas fa-home"></i> Home Loans</div>
                <div class="service-item"><i class="fas fa-car"></i> Vehicle Loans</div>
                <div class="service-item"><i class="fas fa-briefcase"></i> Business Loans</div>
                <div class="service-item"><i class="fas fa-chalkboard-user"></i> Professional Loans</div>
                <div class="service-item"><i class="fas fa-gem"></i> Gold Loans</div>
                <div class="service-item"><i class="fas fa-building"></i> Loan Against Property</div>
                <div class="service-item"><i class="fas fa-chart-line"></i> Startup & MSME Financing</div>
                <div class="service-item"><i class="fas fa-globe"></i> NRI Financial Solutions</div>
                <div class="service-item"><i class="fas fa-hand-holding-heart"></i> Credit & Financial Support Services</div>
            </div>
            <div class="highlight-badge">
                <i class="fas fa-info-circle"></i> Availability of services may vary by eligibility, location, and partner policies.
            </div>
        </div>

        <!-- Section 2: Acceptance of Terms -->
        <div class="terms-section">
            <h2>2. Acceptance of Terms</h2>
            <p>By using this website, submitting forms, creating accounts, requesting quotes, or applying for any service, you confirm that:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-check-circle"></i> You have read and understood these Terms.</li>
                <li><i class="fas fa-check-circle"></i> You are legally capable of entering agreements.</li>
                <li><i class="fas fa-check-circle"></i> Information submitted by you is accurate and lawful.</li>
            </ul>
            <p>Continued use of the website means acceptance of future updates to these Terms.</p>
        </div>

        <!-- Section 3: Eligibility -->
        <div class="terms-section">
            <h2>3. Eligibility</h2>
            <p>To use LoginToLoans services, you must:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-check-circle"></i> Be at least 18 years of age.</li>
                <li><i class="fas fa-check-circle"></i> Be legally eligible under applicable Indian laws.</li>
                <li><i class="fas fa-check-circle"></i> Provide authentic information and supporting documents.</li>
                <li><i class="fas fa-check-circle"></i> Meet lender-specific qualification requirements.</li>
            </ul>
            <p>Business users confirm they are authorized to represent their organization.</p>
        </div>

        <!-- Section 4: Nature of Services -->
        <div class="terms-section">
            <h2>4. Nature of Services</h2>
            <p>LoginToLoans provides assistance and facilitation services related to financial products, except where specifically mentioned otherwise.</p>
            <p>Important points:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-exclamation-circle"></i> Submission of an enquiry does not guarantee approval.</li>
                <li><i class="fas fa-exclamation-circle"></i> Loan approval remains subject to lender policies.</li>
                <li><i class="fas fa-exclamation-circle"></i> Interest rates, processing timelines, and loan amounts may vary.</li>
                <li><i class="fas fa-exclamation-circle"></i> Final sanction decisions are controlled by the financial institution involved.</li>
            </ul>
        </div>

        <!-- Section 5: User Responsibilities -->
        <div class="terms-section">
            <h2>5. User Responsibilities</h2>
            <p>You agree to:</p>
            <div class="grid-2col">
                <div class="grid-item"><i class="fas fa-check"></i> Provide complete and truthful information</div>
                <div class="grid-item"><i class="fas fa-check"></i> Submit valid documentation when requested</div>
                <div class="grid-item"><i class="fas fa-check"></i> Maintain confidentiality of account credentials</div>
                <div class="grid-item"><i class="fas fa-check"></i> Use the website only for lawful purposes</div>
                <div class="grid-item"><i class="fas fa-check"></i> Avoid misuse of platform features</div>
            </div>
            <p>Users remain responsible for information submitted through their accounts.</p>
        </div>

        <!-- Section 6: Application & Verification Process -->
        <div class="terms-section">
            <h2>6. Application & Verification Process</h2>
            <p>During the application process, users may be required to provide:</p>
            <div class="doc-grid">
                <div class="doc-item"><i class="fas fa-id-card"></i> Identity proof</div>
                <div class="doc-item"><i class="fas fa-map-marker-alt"></i> Address proof</div>
                <div class="doc-item"><i class="fas fa-chart-line"></i> Income verification</div>
                <div class="doc-item"><i class="fas fa-briefcase"></i> Employment details</div>
                <div class="doc-item"><i class="fas fa-store"></i> Business documents</div>
                <div class="doc-item"><i class="fas fa-file-alt"></i> Property-related documentation</div>
            </div>
            <div class="warning-badge">
                <i class="fas fa-exclamation-triangle"></i> Failure to provide accurate information may result in delays, rejection, or suspension of service.
            </div>
        </div>

        <!-- Section 7: Pricing, Charges & Payments -->
        <div class="terms-section">
            <h2>7. Pricing, Charges & Payments</h2>
            <p>Some services may involve:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-rupee-sign"></i> Processing fees</li>
                <li><i class="fas fa-rupee-sign"></i> Documentation charges</li>
                <li><i class="fas fa-rupee-sign"></i> Consultation charges</li>
                <li><i class="fas fa-rupee-sign"></i> Service-related payments</li>
            </ul>
            <p>Where applicable, charges will be communicated before confirmation.</p>
            <div class="highlight-badge">
                <i class="fas fa-exclamation-circle"></i> Unless expressly stated otherwise, fees paid for completed services are non-refundable.
            </div>
        </div>

        <!-- Section 8: Website Usage Restrictions -->
        <div class="terms-section">
            <h2>8. Website Usage Restrictions</h2>
            <p>Users must not:</p>
            <div class="grid-2col">
                <div class="grid-item"><i class="fas fa-ban"></i> Attempt unauthorized access</div>
                <div class="grid-item"><i class="fas fa-ban"></i> Upload malicious content</div>
                <div class="grid-item"><i class="fas fa-ban"></i> Misrepresent identity</div>
                <div class="grid-item"><i class="fas fa-ban"></i> Copy or reproduce platform materials</div>
                <div class="grid-item"><i class="fas fa-ban"></i> Disrupt website performance</div>
                <div class="grid-item"><i class="fas fa-ban"></i> Engage in fraudulent activities</div>
            </div>
        </div>

        <!-- Section 9: Intellectual Property -->
        <div class="terms-section">
            <h2>9. Intellectual Property</h2>
            <p>All content displayed on LoginToLoans including:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-trademark"></i> Branding</li>
                <li><i class="fas fa-trademark"></i> Logos</li>
                <li><i class="fas fa-trademark"></i> Website content</li>
                <li><i class="fas fa-trademark"></i> Graphics</li>
                <li><i class="fas fa-trademark"></i> Designs</li>
                <li><i class="fas fa-trademark"></i> Service descriptions</li>
                <li><i class="fas fa-trademark"></i> Digital assets</li>
            </ul>
            <p>remains the property of LoginToLoans unless otherwise stated.</p>
            <div class="warning-badge">
                <i class="fas fa-copyright"></i> Unauthorized use is prohibited.
            </div>
        </div>

        <!-- Section 10: Privacy & Data Usage -->
        <div class="terms-section">
            <h2>10. Privacy & Data Usage</h2>
            <p>By using LoginToLoans, you consent to the collection and use of information required to:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-check"></i> Process enquiries</li>
                <li><i class="fas fa-check"></i> Improve services</li>
                <li><i class="fas fa-check"></i> Provide customer support</li>
                <li><i class="fas fa-check"></i> Communicate updates</li>
            </ul>
            <p>Please review our <a href="privacy.php"><i class="fas fa-external-link-alt"></i> Privacy Policy</a> for complete details.</p>
        </div>

        <!-- Section 11: Third-Party Services -->
        <div class="terms-section">
            <h2>11. Third-Party Services</h2>
            <p>LoginToLoans may connect users with banks, lenders, NBFCs, insurers, or service partners.</p>
            <p>We are not responsible for:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-exclamation-circle"></i> Loan approval decisions</li>
                <li><i class="fas fa-exclamation-circle"></i> Third-party fees</li>
                <li><i class="fas fa-exclamation-circle"></i> External website policies</li>
                <li><i class="fas fa-exclamation-circle"></i> Independent partner terms</li>
            </ul>
            <p>Users are encouraged to review partner documentation independently.</p>
        </div>

        <!-- Section 12: Disclaimer -->
        <div class="terms-section">
            <h2>12. Disclaimer</h2>
            <p>The information provided on this website is intended solely for general informational purposes and should not be considered as financial, legal, or professional advice.</p>
            <p>LoginToLoans does not guarantee:</p>
            <div class="grid-2col">
                <div class="grid-item"><i class="fas fa-exclamation-triangle"></i> Loan sanction</div>
                <div class="grid-item"><i class="fas fa-exclamation-triangle"></i> Approval timelines</div>
                <div class="grid-item"><i class="fas fa-exclamation-triangle"></i> Specific interest rates</div>
                <div class="grid-item"><i class="fas fa-exclamation-triangle"></i> Availability of products</div>
                <div class="grid-item"><i class="fas fa-exclamation-triangle"></i> Funding commitments</div>
            </div>
            <p>Actual outcomes depend on eligibility and lender review.</p>
        </div>

        <!-- Section 13: Limitation of Liability -->
        <div class="terms-section">
            <h2>13. Limitation of Liability</h2>
            <p>To the extent permitted under applicable law, LoginToLoans shall not be liable for:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-clock"></i> Delayed approvals</li>
                <li><i class="fas fa-wifi"></i> Data interruptions</li>
                <li><i class="fas fa-chart-line"></i> Financial losses</li>
                <li><i class="fas fa-server"></i> Technical downtime</li>
                <li><i class="fas fa-handshake"></i> Third-party decisions</li>
            </ul>
            <p>Users agree to use the platform at their discretion.</p>
        </div>

        <!-- Section 14: Suspension & Termination -->
        <div class="terms-section">
            <h2>14. Suspension & Termination</h2>
            <p>We may suspend, limit, or terminate user access to the website and related services in situations such as:</p>
            <ul class="bullet-list">
                <li><i class="fas fa-gavel"></i> Terms are violated.</li>
                <li><i class="fas fa-user-secret"></i> Fraud is suspected.</li>
                <li><i class="fas fa-edit"></i> False information is submitted.</li>
                <li><i class="fas fa-shield-alt"></i> Platform security is compromised.</li>
            </ul>
        </div>

        <!-- Section 15: Updates to Terms -->
        <div class="terms-section">
            <h2>15. Updates to Terms</h2>
            <p>LoginToLoans may modify these Terms periodically.</p>
            <p>Any revisions or updates will take effect immediately upon being published on this website. Continued use indicates acceptance of revised Terms.</p>
            <div class="highlight-badge">
                <i class="fas fa-bell"></i> We'll notify you about significant changes via email or website notice.
            </div>
        </div>

        <!-- Section 16: Governing Law -->
        <div class="terms-section">
            <h2>16. Governing Law</h2>
            <p>This agreement shall be governed by and construed under the laws of India.</p>
            <p>Any dispute related to access to or use of this website shall be governed by the jurisdiction of the relevant courts located in India.</p>
            <div class="warning-badge" style="margin-top: 16px;">
                <i class="fas fa-gavel"></i> Jurisdiction: Courts in Mumbai, Maharashtra, India
            </div>
        </div>

        <!-- Acceptance Footer -->
        <div class="terms-section" style="border-bottom: none; padding-bottom: 10px;">
            <p style="font-size: 0.85rem; text-align: center; color: #6B7A8F; padding-top: 10px;">
                By accessing, browsing, or using this website, you agree to the terms and practices described in these Terms & Conditions.
            </p>
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
    const sections = document.querySelectorAll('.terms-section');
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
<?php
include('includes/footer-nav.php'); 
?>
</html>