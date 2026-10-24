<?php
// FILE: all-retails.php
include('../../includes/dbConfig.php');
include('../../includes/navbar.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>All Investment Plans | Smart Retail Solutions</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #1a1a2e;
            overflow-x: hidden;
        }

        /* ========== ELEGANT BLUE & ORANGE MNC THEME ========== */
        :root {
            --primary: #1E5AA8;
            --primary-dark: #0F3B6F;
            --primary-light: #3B7BC5;
            --secondary: #F39C12;
            --secondary-dark: #E67E22;
            --secondary-light: #F5B041;
            --orange-soft: #FFF3E0;
            --blue-soft: #E8F0FE;
            --gray-50: #f8f9fc;
            --gray-100: #f1f3f9;
            --gray-200: #e4e7ed;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Premium Progress Bar */
        .progress-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: transparent;
            z-index: 10000;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--primary));
            width: 0%;
            transition: width 0.1s linear;
            box-shadow: 0 0 8px rgba(243, 156, 18, 0.4);
        }

        /* Hero Section - Blue with Orange Accent Glow */
        .page-hero {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-light) 100%);
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(243, 156, 18, 0.25) 0%, transparent 70%);
            border-radius: 50%;
            animation: subtleGlow 8s ease-in-out infinite;
        }

        .page-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(243, 156, 18, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            animation: subtleGlow 6s ease-in-out infinite reverse;
        }

        @keyframes subtleGlow {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.6;
            }

            50% {
                transform: scale(1.08);
                opacity: 1;
            }
        }

        .page-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
            letter-spacing: -0.02em;
        }

        .page-hero p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            font-weight: 400;
        }

        /* Investment Section */
        .investment-section {
            padding: 60px 0;
            background: #ffffff;
        }

        /* Category Section - Orange Accent Border */
        .category-section {
            margin-bottom: 4rem;
        }

        .category-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.8rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--secondary);
        }

        .category-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, rgba(30, 90, 168, 0.1), rgba(243, 156, 18, 0.08));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .category-icon i {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .category-title h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--gray-800);
            margin: 0;
        }

        .category-title span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            margin-left: 10px;
            box-shadow: 0 2px 6px rgba(243, 156, 18, 0.3);
        }

        /* Investment Cards Grid */
        .investment-cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.8rem;
        }

        /* Investment Card - Sophisticated Hover with Orange Glow */
        .investment-card {
            background: white;
            border-radius: 24px;
            padding: 1.8rem;
            transition: all 0.35s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.02), 0 2px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-200);
            cursor: pointer;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .investment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--primary));
            transform: scaleX(0);
            transition: transform 0.5s ease;
            transform-origin: left;
        }

        .investment-card:hover::before {
            transform: scaleX(1);
        }

        .investment-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 40px -12px rgba(0, 0, 0, 0.2);
            border-color: rgba(243, 156, 18, 0.4);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(30, 90, 168, 0.08), rgba(243, 156, 18, 0.06));
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.2rem;
            transition: all 0.3s ease;
        }

        .investment-card:hover .card-icon {
            background: linear-gradient(135deg, rgba(30, 90, 168, 0.15), rgba(243, 156, 18, 0.12));
            transform: scale(1.02);
        }

        .card-icon i {
            font-size: 1.8rem;
            color: var(--primary);
        }

        .investment-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        .investment-card p {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .card-tag {
            display: inline-block;
            background: var(--orange-soft);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--secondary-dark);
            transition: all 0.2s ease;
        }

        .investment-card:hover .card-tag {
            background: var(--secondary);
            color: white;
        }

        .learn-more {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary);
            margin-top: 1rem;
            transition: gap 0.3s ease;
        }

        .investment-card:hover .learn-more {
            gap: 12px;
            color: var(--secondary-dark);
        }

        .learn-more i {
            font-size: 0.7rem;
        }

        /* CTA Section - Vibrant Orange-Blue Gradient */
        .cta-section {
            margin-top: 3rem;
            margin-bottom: 2rem;
        }

        .cta-card {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 32px;
            padding: 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.2);
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(243, 156, 18, 0.25), transparent);
            border-radius: 50%;
            animation: floatGlow 10s infinite alternate;
        }

        .cta-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(243, 156, 18, 0.2), transparent);
            border-radius: 50%;
            animation: floatGlow 8s infinite alternate-reverse;
        }

        @keyframes floatGlow {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 0.5;
            }

            100% {
                transform: translate(20px, -20px) scale(1.1);
                opacity: 0.8;
            }
        }

        .cta-card h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .cta-card p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .cta-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--secondary);
            color: white;
            padding: 12px 32px;
            border-radius: 60px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .cta-btn:hover {
            background: var(--secondary-dark);
            transform: translateY(-3px);
            gap: 15px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
        }

        /* Back to Top - Orange Accent */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 100;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .container {
                padding: 0 20px;
            }

            .page-hero h1 {
                font-size: 2.2rem;
            }

            .page-hero p {
                font-size: 1rem;
                padding: 0 1rem;
            }

            .category-title h2 {
                font-size: 1.3rem;
            }

            .investment-cards-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1.5rem;
            }

            .cta-card h3 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 768px) {
            .page-hero {
                padding: 60px 0 40px;
            }

            .page-hero h1 {
                font-size: 1.8rem;
            }

            .investment-section {
                padding: 40px 0;
            }

            .category-section {
                margin-bottom: 2.5rem;
            }

            .category-icon {
                width: 40px;
                height: 40px;
            }

            .category-icon i {
                font-size: 1.2rem;
            }

            .category-title h2 {
                font-size: 1.2rem;
            }

            .investment-cards-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .investment-card {
                padding: 1.4rem;
            }

            .cta-card {
                padding: 2rem 1.5rem;
            }

            .cta-card h3 {
                font-size: 1.2rem;
            }

            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 16px;
            }

            .page-hero h1 {
                font-size: 1.5rem;
            }

            .category-title {
                flex-wrap: wrap;
            }

            .category-title span {
                margin-left: 0;
            }
        }

        /* Animation Classes */
        .fade-up {
            animation: fadeUp 0.6s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress-bar" id="progressBar"></div>
    </div>

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <h1>Smart Professional Solutions</h1>
            <p>Discover our comprehensive range of professional loan options designed to meet your financial needs. From personal loans to home financing, we have the right solution for you.</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="investment-section">
        <div class="container">

            <!-- ========== Professional Loans SECTION ========== -->
            <div class="category-section fade-up">
                <div class="category-title">
                    <div class="category-icon"><i class="fas fa-chart-pie"></i></div>
                    <h2>Professional Loans <span>15 Plans</span></h2>
                </div>
                <div class="investment-cards-grid">
                    <!-- DOCTOR LOAN -->
                    <a href="https://logintoloans.com/pages/business/doctor-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-user-md"></i>
                        </div>

                        <h3>Doctor Loans</h3>

                        <p>
                            Get specialized financial support for doctors with easy approvals,
                            flexible repayment options, and attractive interest rates.
                        </p>

                        <div class="card-tag">
                            Medical Practice | Quick Funding
                        </div>

                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- COMPANY SECRETARY LOAN -->
                    <a href="company-secretary-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <h3>Company Secretary Loan</h3>
                        <p>
                            Financial solutions designed for Company Secretaries with flexible repayment options.
                        </p>
                        <div class="card-tag">
                            CS Professionals | Quick Approval
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- ARCHITECT LOAN -->
                    <a href="architect-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-drafting-compass"></i>
                        </div>
                        <h3>Architect Loan</h3>
                        <p>
                            Expand your architectural projects with customized loan solutions and easy funding.
                        </p>
                        <div class="card-tag">
                            Design Projects | Business Expansion
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- CONSULTANT LOAN -->
                    <a href="consultant-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h3>Consultant Loan</h3>
                        <p>
                            Get financial support for consultancy business growth with quick approvals.
                        </p>
                        <div class="card-tag">
                            Professional Services | Fast Funding
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- HOSPITAL LOAN -->
                    <a href="hospital-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h3>Hospital Setup Loan</h3>
                        <p>
                            Build or expand clinics and hospitals with specialized healthcare financing.
                        </p>
                        <div class="card-tag">
                            Healthcare Finance | Infrastructure Support
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- WORKING CAPITAL LOAN -->
                    <a href="working-capital.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h3>Working Capital Loan</h3>
                        <p>
                            Manage daily business operations smoothly with flexible working capital support.
                        </p>
                        <div class="card-tag">
                            Cash Flow | Business Operations
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- OVERDRAFT FACILITY -->
                    <a href="overdraft-facility.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3>Overdraft Facility</h3>
                        <p>
                            Access extra funds instantly for short-term business and professional needs.
                        </p>
                        <div class="card-tag">
                            Flexible Credit | Instant Access
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- BUSINESS LOAN -->
                    <a href="business-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3>Business Loan</h3>
                        <p>
                            Grow your business confidently with quick and affordable financing solutions.
                        </p>
                        <div class="card-tag">
                            Expansion | Quick Funding
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- CA LOAN -->
                    <a href="https://logintoloans.com/pages/business/ca-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h3>CA Loan</h3>
                        <p>
                            Exclusive loan solutions for Chartered Accountants with attractive interest rates.
                        </p>
                        <div class="card-tag">
                            Finance Professionals | Easy EMI
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- COST ACCOUNT LOAN -->
                    <a href="cost-account-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3>Cost Accountant Loan</h3>
                        <p>
                            Financial assistance tailored for Cost Accountants and finance professionals.
                        </p>
                        <div class="card-tag">
                            Professional Growth | Flexible Funding
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- ENGINEER LOAN -->
                    <a href="engineer-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3>Engineer Loan</h3>
                        <p>
                            Get specialized financing support for engineering professionals and projects.
                        </p>
                        <div class="card-tag">
                            Technical Professionals | Fast Approval
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- MEDICAL EQUIPMENT LOAN -->
                    <a href="medical-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h3>Medical Equipment Loan</h3>
                        <p>
                            Purchase advanced medical equipment with easy and flexible financing options.
                        </p>
                        <div class="card-tag">
                            Healthcare Equipment | Quick Disbursal
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- PROFESSIONAL PRACTICE EXPANSION LOAN -->
                    <a href="https://logintoloans.com/pages/professional/professional-practice-expansion-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Professional Practice Expansion Loan</h3>
                        <p>
                            Expand your professional practice with customized funding and flexible repayment.
                        </p>
                        <div class="card-tag">
                            Practice Growth | Business Expansion
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- LOAN AGAINST PROPERTY -->
                    <a href="https://logintoloans.com/pages/retail/loan-against-property.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3>Loan Against Property</h3>
                        <p>
                            Unlock the value of your property with high-value secured loan solutions.
                        </p>
                        <div class="card-tag">
                            Secured Loan | High Funding
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <!-- MACHINERY LOAN -->
                    <a href="https://logintoloans.com/pages/business/machinery-loan.php" class="investment-card">
                        <div class="card-icon">
                            <i class="fas fa-industry"></i>
                        </div>
                        <h3>Machinery Loan</h3>
                        <p>
                            Upgrade your business operations with easy machinery and equipment financing.
                        </p>
                        <div class="card-tag">
                            Equipment Finance | Industrial Growth
                        </div>
                        <div class="learn-more">
                            Apply Now <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="cta-section fade-up">
                <div class="cta-card">

                    <h3>Need the Right Loan for Your Dreams?</h3>

                    <p>
                        From personal loans to home and business financing,
                        our experts help you choose the perfect loan with
                        fast approvals and flexible EMI options.
                    </p>

                    <a href="reach-out.php" class="cta-btn">
                        <i class="fas fa-phone-volume"></i> Get Free Loan Consultation
                    </a>

                </div>
            </div>
        </div>
    </section>

    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Progress Bar
        const progressBar = document.getElementById('progressBar');
        window.addEventListener('scroll', () => {
            const winScroll = document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            if (progressBar) progressBar.style.width = scrolled + '%';
        });

        // Back to Top
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Fade up animation on scroll
        const fadeElements = document.querySelectorAll('.fade-up');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        fadeElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease';
            observer.observe(el);
        });
    </script>

    <?php include('../../includes/footer.php'); ?>
</body>

</html>