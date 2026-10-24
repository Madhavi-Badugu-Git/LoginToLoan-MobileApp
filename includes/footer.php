<?php
// FILE: includes/footer.php - PREMIUM NAVY & ORANGE THEME FOOTER WITH DUAL-SIDE FLOATING CONTACT ICONS
?>
<style>
    /* ========== PREMIUM NAVY & ORANGE FOOTER ========== */
    .premium-footer {
        background: linear-gradient(135deg, #060F22 0%, #0A1E40 50%, #060F22 100%);
        color: #8C9DB8;
        padding: 3.5rem 5% 1.5rem;
        margin-top: 5rem;
        position: relative;
        overflow: hidden;
        border-top: none;
    }

    /* Animated Gradient Border Top - Orange */
    .premium-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg,
                transparent,
                #F97316,
                #FFB57A,
                #E8C55A,
                #FFB57A,
                #F97316,
                transparent);
        animation: borderFlow 4s ease-in-out infinite;
    }

    @keyframes borderFlow {
        0% { left: -100%; }
        50% { left: 0%; }
        100% { left: 100%; }
    }

    /* Animated Background Orbs */
    .premium-footer::after {
        content: '';
        position: absolute;
        bottom: -50px;
        right: -50px;
        width: 250px;
        height: 250px;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.08), transparent);
        border-radius: 50%;
        pointer-events: none;
        animation: floatOrb 8s ease-in-out infinite;
    }

    @keyframes floatOrb {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-30px) scale(1.05); }
    }

    /* Floating Particles - Orange */
    .footer-particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
    }

    .footer-particle {
        position: absolute;
        background: linear-gradient(135deg, #F97316, #FFB57A);
        border-radius: 50%;
        opacity: 0.08;
        animation: floatParticle linear infinite;
    }

    @keyframes floatParticle {
        0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
        10% { opacity: 0.08; }
        90% { opacity: 0.08; }
        100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
    }

    .footer-content {
        display: grid;
        grid-template-columns: 1.5fr repeat(4, 1fr);
        gap: 2rem;
        margin-bottom: 2.5rem;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        z-index: 2;
    }

    /* Logo Section */
    .footer-logo-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 0.5rem;
    }

    .footer-logo img {
        height: 75px;
        width: auto;
        object-fit: contain;
        transition: all 0.3s ease;
    }

    .footer-logo:hover img {
        transform: scale(1.02);
        filter: drop-shadow(0 0 8px rgba(249, 115, 22, 0.5));
    }

    .footer-tagline {
        font-size: 0.85rem;
        color: #8C9DB8;
        line-height: 1.6;
        margin: 0.5rem 0;
    }

    .footer-stats {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
        flex-wrap: wrap;
    }

    .footer-stat-badge {
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.2);
        border-radius: 50px;
        padding: 0.3rem 0.8rem;
        font-size: 0.7rem;
        color: #F97316;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .footer-social {
        display: flex;
        gap: 0.8rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .footer-social a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .footer-social a::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.3), transparent);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.4s, height 0.4s;
    }

    .footer-social a:hover::before {
        width: 100px;
        height: 100px;
    }

    .footer-social a:hover {
        background: linear-gradient(135deg, #F97316, #FFB57A);
        transform: translateY(-5px) rotate(5deg);
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
    }

    .footer-social a i {
        font-size: 1.1rem;
        color: #cbd5e1;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .footer-social a:hover i {
        color: white;
        transform: scale(1.1);
    }

    /* Footer Sections */
    .footer-section {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .footer-section h4 {
        color: white;
        margin-bottom: 1rem;
        font-size: 1rem;
        font-weight: 700;
        position: relative;
        display: inline-block;
        letter-spacing: 0.5px;
    }

    .footer-section h4::before {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 30px;
        height: 2px;
        background: linear-gradient(90deg, #F97316, #FFB57A);
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .footer-section:hover h4::before {
        width: 50px;
    }

    .footer-section a {
        color: #8C9DB8;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0.6rem;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        padding: 4px 0;
    }

    .footer-section a i {
        font-size: 0.7rem;
        opacity: 0.7;
        transition: all 0.3s ease;
    }

    .footer-section a:hover {
        color: #F97316;
        transform: translateX(8px);
    }

    .footer-section a:hover i {
        opacity: 1;
        transform: translateX(3px);
        color: #F97316;
    }

    /* Newsletter Section */
    .footer-newsletter {
        margin-top: 1rem;
    }

    .footer-newsletter p {
        font-size: 0.8rem;
        margin-bottom: 0.8rem;
        color: #8C9DB8;
    }

    .newsletter-input-group {
        display: flex;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50px;
        padding: 0.2rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .newsletter-input-group:hover {
        border-color: rgba(249, 115, 22, 0.3);
        background: rgba(255, 255, 255, 0.08);
    }

    .newsletter-input-group input {
        flex: 1;
        background: transparent;
        border: none;
        padding: 0.6rem 1rem;
        color: white;
        font-size: 0.8rem;
        outline: none;
    }

    .newsletter-input-group input::placeholder {
        color: #64748b;
    }

    .newsletter-input-group button {
        background: linear-gradient(135deg, #F97316, #FFB57A);
        border: none;
        border-radius: 50px;
        padding: 0.5rem 1.2rem;
        color: white;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.75rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .newsletter-input-group button:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
        gap: 8px;
    }

    /* Footer Bottom */
    .footer-bottom {
        text-align: center;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 0.8rem;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .footer-bottom-links {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .footer-bottom-links a {
        color: #8C9DB8;
        text-decoration: none;
        font-size: 0.75rem;
        transition: color 0.3s ease;
    }

    .footer-bottom-links a:hover {
        color: #F97316;
    }

    .footer-payment-icons {
        display: flex;
        gap: 0.8rem;
        align-items: center;
        flex-wrap: wrap;
    }

    .footer-payment-icons i {
        font-size: 1.2rem;
        color: #64748b;
        transition: all 0.3s ease;
    }

    .footer-payment-icons i:hover {
        color: #F97316;
        transform: translateY(-2px);
    }

    /* ========== DUAL-SIDE FLOATING CONTACT ICONS ========== */
    .floating-contact-icons-left {
        position: fixed;
        left: 20px;
        bottom: 100px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .floating-contact-icons-right {
        position: fixed;
        right: 20px;
        bottom: 100px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .floating-contact-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        text-decoration: none;
    }

    .floating-contact-icon i {
        font-size: 1.6rem;
        color: white;
        transition: all 0.3s ease;
    }

    .floating-contact-icon:hover {
        transform: scale(1.1) translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
    }

    /* Tooltip for Left Side Icons (tooltip on right) */
    .floating-contact-icons-left .floating-contact-icon .tooltip-text {
        position: absolute;
        left: 70px;
        background: #060F22;
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.3);
        z-index: 10001;
    }

    .floating-contact-icons-left .floating-contact-icon .tooltip-text::after {
        content: '';
        position: absolute;
        left: -6px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-right: 6px solid #060F22;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
    }

    .floating-contact-icons-left .floating-contact-icon:hover .tooltip-text {
        opacity: 1;
        visibility: visible;
        left: 80px;
    }

    /* Tooltip for Right Side Icons (tooltip on left) */
    .floating-contact-icons-right .floating-contact-icon .tooltip-text {
        position: absolute;
        right: 70px;
        background: #060F22;
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 500;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.3);
        z-index: 10001;
    }

    .floating-contact-icons-right .floating-contact-icon .tooltip-text::after {
        content: '';
        position: absolute;
        right: -6px;
        top: 50%;
        transform: translateY(-50%);
        width: 0;
        height: 0;
        border-left: 6px solid #060F22;
        border-top: 5px solid transparent;
        border-bottom: 5px solid transparent;
    }

    .floating-contact-icons-right .floating-contact-icon:hover .tooltip-text {
        opacity: 1;
        visibility: visible;
        right: 80px;
    }

    /* Individual Icon Colors */
    .floating-contact-icon.whatsapp {
        background: linear-gradient(135deg, #25D366, #128C7E);
    }
    .floating-contact-icon.whatsapp:hover {
        box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
    }

    .floating-contact-icon.sms {
        background: linear-gradient(135deg, #1E5AA8, #3B82F6);
    }
    .floating-contact-icon.sms:hover {
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }

    .floating-contact-icon.call {
        background: linear-gradient(135deg, #F97316, #FFB57A);
    }
    .floating-contact-icon.call:hover {
        box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
    }

    .floating-contact-icon.chatbot {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
    }
    .floating-contact-icon.chatbot:hover {
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
    }

    /* Back to Top Button */
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 20px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #F97316, #FFB57A);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(249, 115, 22, 0.4);
    }

    .back-to-top i {
        color: white;
        font-size: 1.2rem;
    }

    /* Responsive */
    @media (max-width: 1100px) {
        .footer-content {
            grid-template-columns: 1.5fr repeat(2, 1fr);
            gap: 1.8rem;
        }
    }

    @media (max-width: 768px) {
        .premium-footer {
            padding: 2rem 1.5rem 1rem;
            margin-top: 3rem;
        }

        .footer-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .footer-logo-section {
            text-align: center;
            align-items: center;
        }

        .footer-logo {
            justify-content: center;
        }

        .footer-social {
            justify-content: center;
        }

        .footer-stats {
            justify-content: center;
        }

        .footer-section {
            text-align: center;
            align-items: center;
        }

        .footer-section h4::before {
            left: 50%;
            transform: translateX(-50%);
        }

        .footer-section a {
            justify-content: center;
        }

        .footer-section a:hover {
            transform: translateX(0);
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
            gap: 0.8rem;
        }

        .footer-bottom-links {
            justify-content: center;
        }

        .footer-payment-icons {
            justify-content: center;
        }

        .floating-contact-icons-left,
        .floating-contact-icons-right {
            bottom: 80px;
        }

        .floating-contact-icons-left {
            left: 10px;
        }
        .floating-contact-icons-right {
            right: 10px;
        }

        .floating-contact-icon {
            width: 45px;
            height: 45px;
        }

        .floating-contact-icon i {
            font-size: 1.3rem;
        }

        .back-to-top {
            bottom: 20px;
            right: 10px;
            width: 42px;
            height: 42px;
        }
    }

    @media (max-width: 480px) {
        .newsletter-input-group {
            flex-direction: column;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.08);
        }

        .newsletter-input-group input {
            text-align: center;
            padding: 0.8rem;
        }

        .newsletter-input-group button {
            justify-content: center;
            margin: 0.2rem;
        }

        .floating-contact-icon .tooltip-text {
            display: none;
        }
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #060F22;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #F97316, #FFB57A);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #FFB57A, #F97316);
    }
</style>

<footer class="premium-footer">
    <!-- Floating Particles -->
    <div class="footer-particles">
        <div class="footer-particle" style="width: 60px; height: 60px; left: 10%; animation-duration: 20s; animation-delay: 0s;"></div>
        <div class="footer-particle" style="width: 40px; height: 40px; left: 30%; animation-duration: 15s; animation-delay: 2s;"></div>
        <div class="footer-particle" style="width: 80px; height: 80px; left: 60%; animation-duration: 25s; animation-delay: 1s;"></div>
        <div class="footer-particle" style="width: 30px; height: 30px; left: 85%; animation-duration: 18s; animation-delay: 3s;"></div>
        <div class="footer-particle" style="width: 50px; height: 50px; left: 45%; animation-duration: 22s; animation-delay: 0.5s;"></div>
    </div>

    <div class="footer-content">
        <!-- Logo & Brand Section -->
        <div class="footer-logo-section">
            <div class="footer-logo">
                <a href="<?php echo BASE_URL; ?>index.php">
                    <img src="<?php echo BASE_URL; ?>assets/images/logintoloan.jpeg" 
                        alt="<?php echo SITE_NAME; ?> Logo" 
                        style="height:50px; width:auto;" 
                        onerror="this.style.display='none';this.nextElementSibling.style.display='inline'">
                    
                    <span style="display:none; font-size:20px; font-weight:600; color:white;">
                        <?php echo SITE_NAME; ?>
                    </span>
                </a>
            </div>
            <p class="footer-tagline">Your trusted partner for retail, business, professional, and NRI loans in India. Smart financing solutions tailored to your needs.</p>
            <div class="footer-stats">
                <span class="footer-stat-badge"><i class="fas fa-users"></i> 50K+ Customers</span>
                <span class="footer-stat-badge"><i class="fas fa-hand-holding-usd"></i> 5000Cr+ Disbursed</span>
                <span class="footer-stat-badge"><i class="fas fa-shield-alt"></i> RBI Compliant</span>
            </div>
            <div class="footer-social">
                <a href="https://www.facebook.com/people/Logintoloans-K/61574524718831/"><i class="fab fa-facebook-f"></i></a>
                <a href="https://x.com/LOGINTOLOA5757"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/info.logintoloans/"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/in/loginto-loans-login-to-losns-3ba7a8357/"><i class="fab fa-linkedin-in"></i></a>
                <!--<a href=""><i class="fab fa-youtube"></i></a>-->
            </div>
        </div>

        <!-- Retail Loans -->
        <div class="footer-section">
            <h4>Retail Loans</h4>
            <a href="<?php echo BASE_URL; ?>pages/retail/personal-loan.php"><i class="fas fa-chevron-right"></i> Personal Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/retail/car-loan.php"><i class="fas fa-chevron-right"></i> Car Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/retail/home-loan.php"><i class="fas fa-chevron-right"></i> Home Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/retail/gold-loan.php"><i class="fas fa-chevron-right"></i> Gold Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/retail/education-loan.php"><i class="fas fa-chevron-right"></i> Education Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/retail/loan-against-property.php"><i class="fas fa-chevron-right"></i> Loan Against Property</a>
            <a href="<?php echo BASE_URL; ?>pages/retail/all-retails.php"><i class="fas fa-arrow-right"></i> View All Retail Loans</a>
        </div>

        <!-- Business & Professional Loans -->
        <div class="footer-section">
            <h4>Business & Professional</h4>
            <a href="<?php echo BASE_URL; ?>pages/business/msme-loan.php"><i class="fas fa-chevron-right"></i> MSME Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/business/startup-loan.php"><i class="fas fa-chevron-right"></i> Startup Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/business/doctor-loan.php"><i class="fas fa-chevron-right"></i> Doctor Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/business/ca-loan.php"><i class="fas fa-chevron-right"></i> CA Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/professional/business-loan.php"><i class="fas fa-chevron-right"></i> Self-Employed Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/business/working-capital.php"><i class="fas fa-chevron-right"></i> Working Capital</a>
            <a href="<?php echo BASE_URL; ?>pages/business/all-business.php"><i class="fas fa-arrow-right"></i> View All Business Loans</a>
        </div>

        <!-- NRI Loans -->
        <div class="footer-section">
            <h4>NRI Loans</h4>
            <a href="<?php echo BASE_URL; ?>pages/nri/nri-home-loan.php"><i class="fas fa-chevron-right"></i> NRI Home Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/nri/nri-business-loan.php"><i class="fas fa-chevron-right"></i> NRI Business Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/nri/nri-lap.php"><i class="fas fa-chevron-right"></i> NRI Loan Against Property</a>
            <a href="<?php echo BASE_URL; ?>pages/nri/nri-education-loan.php"><i class="fas fa-chevron-right"></i> NRI Education Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/nri/nri-car-loan.php"><i class="fas fa-chevron-right"></i> NRI Car Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/nri/nri-gold-loan.php"><i class="fas fa-chevron-right"></i> NRI Gold Loan</a>
            <a href="<?php echo BASE_URL; ?>pages/nri/all-nri.php"><i class="fas fa-arrow-right"></i> View All NRI Loans</a>
        </div>

        <!-- Support & Newsletter -->
        <div class="footer-section">
            <h4>Support</h4>
            <a href="<?php echo BASE_URL; ?>contact.php"><i class="fas fa-chevron-right"></i> Contact Us</a>
            <a href="<?php echo BASE_URL; ?>about.php"><i class="fas fa-chevron-right"></i> About Us</a>
            <a href="<?php echo BASE_URL; ?>faq.php"><i class="fas fa-chevron-right"></i> FAQ</a>
            <a href="<?php echo BASE_URL; ?>terms.php"><i class="fas fa-chevron-right"></i> Terms & Conditions</a>
            <a href="<?php echo BASE_URL; ?>privacy.php"><i class="fas fa-chevron-right"></i> Privacy Policy</a>

            <!-- Newsletter -->
            <div class="footer-newsletter">
                <p><i class="fas fa-envelope"></i> Subscribe for loan updates</p>
                <div class="newsletter-input-group">
                    <input type="email" placeholder="Your email address">
                    <button><i class="fas fa-paper-plane"></i> Subscribe</button>
                </div>
            </div>
        </div>
        <div class="footer-section">
            <h4>CRM Login</h4>
            <a href="https://crm.logintoloans.com/login.php" target="_blank"> <i class="fas fa-chevron-right"></i>CRM-Logintoloans </a>
      	</div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved. | Smart Loan Solutions for Every Need</p>
        <div class="footer-bottom-links">
            <a href="<?php echo BASE_URL; ?>sitemap.php">Sitemap</a>
            <a href="<?php echo BASE_URL; ?>privacy.php">Privacy</a>
            <a href="<?php echo BASE_URL; ?>terms.php">Terms</a>
            <a href="<?php echo BASE_URL; ?>security.php">Security</a>
        </div>
    </div>
</footer>

<!-- ========== FLOATING CONTACT ICONS - LEFT SIDE ========== -->
<div class="floating-contact-icons-left">
    <!-- WhatsApp - Left Side -->
    <a href="https://wa.me/918885545255" target="_blank" class="floating-contact-icon whatsapp">
        <i class="fab fa-whatsapp"></i>
        <span class="tooltip-text">WhatsApp Us</span>
    </a>
    
    <!-- SMS - Left Side -->
    <a href="sms:+918885545255" class="floating-contact-icon sms">
        <i class="fas fa-sms"></i>
        <span class="tooltip-text">Send SMS</span>
    </a>
</div>

<!-- ========== FLOATING CONTACT ICONS - RIGHT SIDE ========== -->
<div class="floating-contact-icons-right">
    <!-- Call - Right Side -->
    <a href="tel:+918885545255" class="floating-contact-icon call">
        <i class="fas fa-phone-alt"></i>
        <span class="tooltip-text">Call Us</span>
    </a>
    
    <!-- Chatbot - Right Side -->
    <div class="floating-contact-icon chatbot" id="chatbotIcon">
        <i class="fas fa-robot"></i>
        <span class="tooltip-text">Chat with AI</span>
    </div>
</div>

<!-- Chatbot Modal/Popup -->
<div id="chatbotModal" style="display:none; position:fixed; bottom:100px; right:90px; width:350px; max-width:90vw; background:#060F22; border-radius:20px; box-shadow:0 20px 40px rgba(0,0,0,0.3); z-index:10001; border:1px solid rgba(249,115,22,0.3); overflow:hidden;">
    <div style="background:linear-gradient(135deg, #F97316, #FFB57A); padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
        <div style="display:flex; align-items:center; gap:10px;">
            <i class="fas fa-robot" style="color:white; font-size:1.3rem;"></i>
            <span style="color:white; font-weight:600;">Loan Assistant</span>
        </div>
        <button id="closeChatbot" style="background:transparent; border:none; color:white; font-size:1.2rem; cursor:pointer;">&times;</button>
    </div>
    <div style="padding:20px; height:300px; overflow-y:auto; display:flex; flex-direction:column; gap:12px;">
        <div style="background:#0A1E40; padding:10px 15px; border-radius:15px; max-width:85%; border-bottom-left-radius:4px;">
            <span style="color:white; font-size:0.85rem;">👋 Hello! I'm your loan assistant. How can I help you today?</span>
        </div>
        <div style="display:flex; gap:10px; margin-top:10px;">
            <input type="text" id="chatbotInput" placeholder="Type your question..." style="flex:1; padding:10px; border-radius:25px; border:1px solid #F97316; background:#0A1E40; color:white; outline:none;">
            <button id="sendChatbotMsg" style="background:#F97316; border:none; border-radius:50%; width:40px; height:40px; cursor:pointer;"><i class="fas fa-paper-plane" style="color:white;"></i></button>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<div class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
</div>

<script>
    // Auto-hide toast notifications after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        var toasts = document.querySelectorAll('.toast-notification');
        toasts.forEach(function(toast) {
            setTimeout(function() {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(function() {
                    if (toast && toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 5000);
        });
    });

    // Back to Top Button Functionality
    (function() {
        var backToTop = document.getElementById('backToTop');
        if (backToTop) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTop.classList.add('show');
                } else {
                    backToTop.classList.remove('show');
                }
            });

            backToTop.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
    })();

    // Newsletter Subscription
    (function() {
        var subscribeBtn = document.querySelector('.newsletter-input-group button');
        if (subscribeBtn) {
            subscribeBtn.addEventListener('click', function() {
                var input = this.closest('.newsletter-input-group').querySelector('input');
                var email = input.value.trim();
                if (email) {
                    alert('Thank you for subscribing! You will receive updates from ' + '<?php echo SITE_NAME; ?>');
                    input.value = '';
                } else {
                    alert('Please enter a valid email address.');
                }
            });
        }
    })();

    // Chatbot Functionality
    (function() {
        var chatbotIcon = document.getElementById('chatbotIcon');
        var chatbotModal = document.getElementById('chatbotModal');
        var closeChatbot = document.getElementById('closeChatbot');
        var sendBtn = document.getElementById('sendChatbotMsg');
        var chatInput = document.getElementById('chatbotInput');
        var chatContainer = document.querySelector('#chatbotModal > div:first-child + div');

        function openChatbot(e) {
            e.stopPropagation();
            chatbotModal.style.display = 'block';
        }

        function closeChatbotModal() {
            chatbotModal.style.display = 'none';
        }

        if (chatbotIcon) chatbotIcon.addEventListener('click', openChatbot);
        if (closeChatbot) closeChatbot.addEventListener('click', closeChatbotModal);

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (chatbotModal && chatbotModal.style.display === 'block') {
                if (!chatbotModal.contains(e.target) && e.target !== chatbotIcon) {
                    closeChatbotModal();
                }
            }
        });

        function addMessage(text, isUser) {
            var msgDiv = document.createElement('div');
            msgDiv.style.padding = '10px 15px';
            msgDiv.style.borderRadius = '15px';
            msgDiv.style.maxWidth = '85%';
            msgDiv.style.marginBottom = '8px';
            if (isUser) {
                msgDiv.style.background = '#F97316';
                msgDiv.style.marginLeft = 'auto';
                msgDiv.style.borderBottomRightRadius = '4px';
                msgDiv.innerHTML = '<span style="color:white;">' + text + '</span>';
            } else {
                msgDiv.style.background = '#0A1E40';
                msgDiv.style.borderBottomLeftRadius = '4px';
                msgDiv.innerHTML = '<span style="color:white;">🤖 ' + text + '</span>';
            }
            chatContainer.appendChild(msgDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function getBotReply(question) {
            var q = question.toLowerCase();
            if (q.includes('interest') || q.includes('rate')) {
                return 'Our interest rates start from as low as 8.5% per annum for secured loans, depending on your profile and loan type.';
            } else if (q.includes('eligibility')) {
                return 'Eligibility depends on your income, credit score, employment type, and existing financial obligations. NRIs and self-employed professionals are also eligible.';
            } else if (q.includes('nri') || q.includes('non resident')) {
                return 'Yes, we offer specialized NRI loans including Home Loan, Business Loan, Loan Against Property, Education Loan, Car Loan, and Gold Loan.';
            } else if (q.includes('documents')) {
                return 'Common documents required: KYC (Passport/Aadhaar/PAN), income proof, bank statements, property/business documents as per loan type.';
            } else if (q.includes('tenure') || q.includes('duration')) {
                return 'Loan tenures range from 12 months up to 30 years depending on the loan type and amount.';
            } else if (q.includes('contact') || q.includes('reach')) {
                return 'You can reach us at +91 8885545255, email info@logintoloans.com, or use the WhatsApp/Chat icons on this page.';
            } else if (q.includes('cibil') || q.includes('credit score')) {
                return 'A good CIBIL score (750+) improves approval chances and helps secure better interest rates.';
            } else {
                return 'Thank you for your question. Please call +91 8885545255 or email info@logintoloans.com for detailed assistance.';
            }
        }

        function sendMessage() {
            var message = chatInput.value.trim();
            if (message === '') return;
            addMessage(message, true);
            chatInput.value = '';
            setTimeout(function() {
                var reply = getBotReply(message);
                addMessage(reply, false);
            }, 500);
        }

        if (sendBtn) sendBtn.addEventListener('click', sendMessage);
        if (chatInput) {
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') sendMessage();
            });
        }
    })();
</script>

</body>
</html>