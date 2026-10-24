<?php
// Include database configuration (this already starts session)
include('includes/dbConfig.php');



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Apply Leads - Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --primary: #1a237e;
            --primary-dark: #0d1757;
            --primary-light: #283593;
            --secondary: #00c853;
            --accent: #ff6b35;
            --orange: #ff9800;
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

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
            min-height: 100vh;
            position: relative;
            padding-bottom: 75px;
        }

        /* ===== TOP HEADER (SAME AS INDEX.PHP) ===== */
        .top-header {
            position: sticky;
            top: 0;
            z-index: 200;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            border-bottom: 1px solid #f0f0f0;
        }

        .menu-btn {
            width: 42px;
            height: 42px;
            border: none;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .menu-btn:active { background: #f0f0f0; }
        .menu-btn span {
            display: block;
            width: 22px;
            height: 2.5px;
            background: #1a237e;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .header-logo {
            flex: 1;
            text-align: center;
        }
        .header-logo img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .logout-btn {
            width: 42px;
            height: 42px;
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 6px;
            border-radius: 10px;
            transition: background 0.2s;
            color: var(--primary);
            font-size: 20px;
            text-decoration: none;
        }
        .logout-btn:active { background: #f0f0f0; }

        /* ===== HERO SECTION (SAME AS INDEX.PHP) ===== */
        .hero-section {
            position: relative;
            padding: 30px 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .hero-section p {
            font-size: 14px;
            opacity: 0.9;
        }
        .hero-section .subtitle {
            font-size: 12px;
            opacity: 0.7;
            margin-top: 8px;
        }

        /* ===== PRODUCTS GRID ===== */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding: 20px 16px;
        }

        .product-card {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: block;
        }
        .product-card:active {
            transform: scale(0.97);
        }

        .product-card-badge {
            background: linear-gradient(135deg, #ff9800, #ff5722);
            color: white;
            font-size: 9px;
            font-weight: 800;
            padding: 6px 8px;
            text-align: center;
            line-height: 1.2;
            min-height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-card-icon {
            background: #e8f0fe;
            padding: 16px 8px 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 72px;
        }

        .product-card-icon i {
            font-size: 32px;
            color: var(--primary);
        }

        .product-card-name {
            padding: 10px 6px;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.3;
            background: #fff;
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

        /* ==================== MODAL STYLES (MODERN UI) ==================== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(12px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s ease;
        }
        .modal-overlay.active {
            visibility: visible;
            opacity: 1;
        }
        .modal-container {
            width: 90%;
            max-width: 380px;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
        }
        .modal-container::-webkit-scrollbar {
            width: 4px;
        }
        .modal-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .modal-container::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        .modal-card {
            background: var(--white);
            border-radius: 32px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }
        .modal-overlay.active .modal-card {
            transform: scale(1);
        }
        /* Modal Header */
        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            padding: 24px 20px;
            text-align: center;
            position: relative;
        }
        .modal-header-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            backdrop-filter: blur(10px);
        }
        .modal-header-icon i {
            font-size: 32px;
            color: white;
        }
        .modal-header h2 {
            color: white;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 4px;
        }
        .modal-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
        }
        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .modal-close:active {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0.95);
        }
        /* Modal Body */
        .modal-body {
            padding: 24px 20px;
        }
        /* Earn Badge */
        .earn-badge {
            background: linear-gradient(135deg, #fff9e6, #fff3d6);
            border-radius: 16px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #ffe4b5;
        }
        .earn-badge-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #ff9800, #ff5722);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .earn-badge-icon i {
            font-size: 22px;
            color: white;
        }
        .earn-badge-content {
            flex: 1;
        }
        .earn-badge-label {
            font-size: 11px;
            color: #d97706;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .earn-badge-value {
            font-size: 16px;
            font-weight: 800;
            color: #b45309;
        }
        /* Form Groups */
        .form-group {
            margin-bottom: 16px;
        }
        .form-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }
        .form-label i {
            font-size: 14px;
            color: var(--primary);
        }
        .form-label span {
            color: #ef4444;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid var(--border);
            border-radius: 16px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background: var(--white);
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 35, 126, 0.1);
        }
        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }
        /* Input with icon */
        .input-icon-group {
            position: relative;
        }
        .input-icon-group i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-tertiary);
            font-size: 16px;
        }
        .input-icon-group .form-input {
            padding-left: 44px;
        }
        /* Row Layout */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        /* Submit Button */
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            border: none;
            padding: 16px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
            margin-top: 24px;
        }
        .submit-btn:active {
            transform: scale(0.98);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }
        .submit-btn i {
            font-size: 18px;
        }
        /* Toast */
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--text-primary);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 13px;
            z-index: 1100;
            transition: transform 0.3s;
            white-space: nowrap;
        }
        .toast.show {
            transform: translateX(-50%) translateY(0);
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== TOP HEADER (SAME AS INDEX.PHP) ===== -->
    <header class="top-header">
        <a href="index.php" class="logout-btn" style="color: var(--primary);">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="header-logo">
            <img src="assets/images/logintoloan.jpeg" alt="Login To Loans" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 200 50\'%3E%3Crect width=\'200\' height=\'50\' fill=\'%231a237e\' rx=\'8\'/%3E%3Ctext x=\'100\' y=\'32\' font-family=\'Arial, sans-serif\' font-size=\'18\' font-weight=\'bold\' fill=\'%23ffffff\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">
        </div>
        <a href="logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to logout?')">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </header>

    <!-- ===== HERO SECTION ===== -->
    <div class="hero-section">
        <h1>APPLY NOW</h1>
        <p>Browse through our best products</p>
        <div class="subtitle">WORK TOGETHER SINCE 2004</div>
    </div>

    <!-- ===== PRODUCTS GRID ===== -->
    <div class="products-grid">
        <div class="product-card" onclick="openLeadForm('Credit Card', 'Earn Upto ₹3000', 'fa-credit-card')">
            <div class="product-card-badge">Earn Upto ₹3000</div>
            <div class="product-card-icon"><i class="fas fa-credit-card"></i></div>
            <div class="product-card-name">Credit Card</div>
        </div>
        <div class="product-card" onclick="openLeadForm('Personal Loan', 'Earn Upto 3.25%', 'fa-rupee-sign')">
            <div class="product-card-badge">Earn Upto 3.25%</div>
            <div class="product-card-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="product-card-name">Personal Loan</div>
        </div>
        <div class="product-card" onclick="openLeadForm('Home Loan', 'Earn Upto 0.60%', 'fa-home')">
            <div class="product-card-badge">Earn Upto 0.60%</div>
            <div class="product-card-icon"><i class="fas fa-home"></i></div>
            <div class="product-card-name">Home Loan</div>
        </div>
        <div class="product-card" onclick="openLeadForm('Mortgage Loan', 'Earn Upto 0.50%', 'fa-building')">
            <div class="product-card-badge">Earn Upto 0.50%</div>
            <div class="product-card-icon"><i class="fas fa-building"></i></div>
            <div class="product-card-name">Mortgage Loan</div>
        </div>
        <div class="product-card" onclick="openLeadForm('Car Loan', 'Earn Upto 0.40%', 'fa-car')">
            <div class="product-card-badge">Earn Upto 0.40%</div>
            <div class="product-card-icon"><i class="fas fa-car"></i></div>
            <div class="product-card-name">Car Loan</div>
        </div>
        <div class="product-card" onclick="openLeadForm('Business Loan', 'Earn Upto 4.50%', 'fa-briefcase')">
            <div class="product-card-badge">Earn Upto 4.50%</div>
            <div class="product-card-icon"><i class="fas fa-briefcase"></i></div>
            <div class="product-card-name">Business Loan</div>
        </div>
        <div class="product-card" onclick="openLeadForm('Professional Loan', 'Earn Upto 3.00%', 'fa-user-tie')">
            <div class="product-card-badge">Earn Upto 3.00%</div>
            <div class="product-card-icon"><i class="fas fa-user-tie"></i></div>
            <div class="product-card-name">Professional Loan</div>
        </div>
        <div class="product-card" onclick="openLeadForm('Gold Loan', 'Earn Upto 0.50%', 'fa-coins')">
            <div class="product-card-badge">Earn Upto 0.50%</div>
            <div class="product-card-icon"><i class="fas fa-coins"></i></div>
            <div class="product-card-name">Gold Loan</div>
        </div>
        <div class="product-card" onclick="openLeadForm('Education Loan', 'Earn Upto 0.60%', 'fa-graduation-cap')">
            <div class="product-card-badge">Earn Upto 0.60%</div>
            <div class="product-card-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="product-card-name">Education Loan</div>
        </div>
    </div>

    <!-- ===== BOTTOM NAVIGATION (SAME AS INDEX.PHP) ===== -->
    <div class="bottom-nav">
        <a href="index.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="applyleads.php" class="nav-item active">
            <i class="fas fa-users"></i>
            <span>Apply Leads</span>
        </a>
        <a href="credit-score.php" class="nav-item">
            <i class="fas fa-chart-line"></i>
            <span>Credit Score</span>
        </a>
        <a href="profile.php" class="nav-item">
            <i class="fas fa-user"></i>
            <span>You</span>
        </a>
    </div>
</div>

<!-- ===== MODERN LEAD FORM MODAL ===== -->
<div class="modal-overlay" id="leadModal">
    <div class="modal-container">
        <div class="modal-card">
            <!-- Modal Header -->
            <div class="modal-header">
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-header-icon">
                    <i class="fas fa-file-invoice" id="modalProductIcon"></i>
                </div>
                <h2 id="modalProductTitle">Apply for Loan</h2>
                <p>Fill the details below to get started</p>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Earn Badge -->
                <div class="earn-badge">
                    <div class="earn-badge-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="earn-badge-content">
                        <div class="earn-badge-label">Your Earning</div>
                        <div class="earn-badge-value" id="modalEarnValue">Earn Upto 3.25%</div>
                    </div>
                </div>

                <form id="leadForm" onsubmit="submitLeadForm(event)">
                    <!-- Full Name -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i> Full Name <span>*</span>
                        </label>
                        <div class="input-icon-group">
                            <i class="fas fa-user-circle"></i>
                            <input type="text" class="form-input" id="fullName" placeholder="Enter your full name" required>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-phone"></i> Phone Number <span>*</span>
                        </label>
                        <div class="input-icon-group">
                            <i class="fas fa-mobile-alt"></i>
                            <input type="tel" class="form-input" id="phoneNumber" placeholder="10-digit mobile number" pattern="[0-9]{10}" maxlength="10" required>
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <div class="input-icon-group">
                            <i class="fas fa-at"></i>
                            <input type="email" class="form-input" id="emailAddress" placeholder="your@email.com">
                        </div>
                    </div>

                    <!-- Row: Loan Amount + Employment Type -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-rupee-sign"></i> Loan Amount <span>*</span>
                            </label>
                            <div class="input-icon-group">
                                <i class="fas fa-rupee-sign"></i>
                                <input type="number" class="form-input" id="loanAmount" placeholder="Amount" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-briefcase"></i> Employment
                            </label>
                            <select class="form-select" id="employmentType">
                                <option value="">Select</option>
                                <option>Salaried</option>
                                <option>Self Employed</option>
                                <option>Business Owner</option>
                                <option>Professional</option>
                                <option>Student</option>
                            </select>
                        </div>
                    </div>

                    <!-- City -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-city"></i> City
                        </label>
                        <div class="input-icon-group">
                            <i class="fas fa-location-dot"></i>
                            <input type="text" class="form-input" id="city" placeholder="Your city">
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-pen"></i> Additional Notes
                        </label>
                        <textarea class="form-textarea" id="notes" placeholder="Any specific requirements..."></textarea>
                    </div>

                    <input type="hidden" id="productType" value="">

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
    // Open Lead Form Modal
    function openLeadForm(productName, earnText, iconClass) {
        // Update modal content
        document.getElementById('modalProductTitle').innerHTML = productName;
        document.getElementById('modalProductIcon').className = 'fas ' + iconClass;
        document.getElementById('modalEarnValue').innerHTML = earnText;
        document.getElementById('productType').value = productName;
        
        // Reset form
        document.getElementById('leadForm').reset();
        
        // Open modal
        document.getElementById('leadModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // Close Modal
    function closeModal() {
        document.getElementById('leadModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Submit Lead Form
    function submitLeadForm(e) {
        e.preventDefault();
        
        const fullName = document.getElementById('fullName').value;
        const phoneNumber = document.getElementById('phoneNumber').value;
        const emailAddress = document.getElementById('emailAddress').value;
        const loanAmount = document.getElementById('loanAmount').value;
        const employmentType = document.getElementById('employmentType').value;
        const city = document.getElementById('city').value;
        const notes = document.getElementById('notes').value;
        const productType = document.getElementById('productType').value;
        
        if(!fullName || !phoneNumber || !loanAmount) {
            showToast("❌ Please fill all required fields");
            return;
        }
        
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        submitBtn.disabled = true;
        
        // Send data to server
        fetch('save_lead_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `customer_name=${encodeURIComponent(fullName)}&mobile=${encodeURIComponent(phoneNumber)}&email=${encodeURIComponent(emailAddress)}&product_type=${encodeURIComponent(productType)}&loan_amount=${encodeURIComponent(loanAmount)}&employment_type=${encodeURIComponent(employmentType)}&city=${encodeURIComponent(city)}&notes=${encodeURIComponent(notes)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast(`✅ Application submitted for ${productType}! We'll contact you soon.`);
                closeModal();
                document.getElementById('leadForm').reset();
            } else {
                showToast('❌ ' + (data.message || 'Submission failed. Try again.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('✅ Application submitted! We will contact you within 24 hours.');
            closeModal();
            document.getElementById('leadForm').reset();
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // Show Toast Message
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }
    
    // Close modal when clicking outside
    document.getElementById('leadModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

</body>
</html>