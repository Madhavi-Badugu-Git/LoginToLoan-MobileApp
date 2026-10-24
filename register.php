<?php
// FILE: register.php
include('includes/dbConfig.php');


// if (isLoggedIn()) {
//     redirect('user/dashboard.php');
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover" />
    <title>Login To Loans · Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --navy: #0d1b40;
            --navy-mid: #132254;
            --navy-dark: #080f27;
            --orange: #f47820;
            --orange-lt: #ff9a45;
            --orange-dk: #d45f00;
            --white: #ffffff;
            --off-white: #f5f7fa;
            --text-muted: #8fa0be;
            --border: #dce3ef;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: 'Outfit', sans-serif;
            overflow: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-mid) 60%, #1a3a7a 100%);
            overflow-x: hidden;
        }

        .logo-wrap {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: navy-blue;
            animation: fadeDown 0.6s ease both;
            margin-top: 30px;
        }

        .logo-wrap img {
            max-width: 240px;
            width: 100%;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 6px 18px rgba(0, 0, 0, 0.35));
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-mid) 60%, #1a3a7a 100%);
        }

        .tagline {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-mid) 60%, #1a3a7a 100%);
            border: 1px solid rgba(244, 120, 32, 0.45);
            color: red;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.6px;
            padding: 5px 14px;
            text-transform: uppercase;
        }

        /* ── FORM SECTION ── */
        .form-section {
            flex: 1;
            background: var(--off-white);
            padding: 3.2rem 2rem 2.8rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: fadeUp 0.55s 0.15s ease both;
            position: relative;
            margin-top: 10px;
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--orange-dk), var(--orange-lt), var(--orange-dk));

        }

        .form-inner {
            max-width: 440px;
            width: 100%;
            margin: 0 auto;
        }

        .welcome-text {
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--navy);
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .welcome-text span {
            color: var(--orange);
        }

        .sub-text {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 400;
            margin-bottom: 2rem;
        }

        /* Fields */
        .field {
            margin-bottom: 1.3rem;
        }

        .field label {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--navy);
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .field label i {
            color: var(--orange);
            font-size: 0.85rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap>i:first-child {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-wrap input,
        .input-wrap select {
            width: 100%;
            padding: 14px 46px 14px 44px;
            font-size: 0.95rem;
            font-family: 'Outfit', sans-serif;
            border: 2px solid var(--border);
            border-radius: 0;
            background: var(--white);
            color: var(--navy);
            outline: none;
            transition: all 0.2s;
            font-weight: 500;
            -webkit-appearance: none;
            appearance: none;
        }

        .input-wrap select {
            padding-right: 46px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238fa0be' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            cursor: pointer;
        }

        .input-wrap input:focus,
        .input-wrap select:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(244, 120, 32, 0.12);
        }

        .input-wrap input:focus~i,
        .input-wrap select:focus~i {
            color: var(--orange);
        }

        /* Password Strength */
        .password-strength {
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            background: var(--border);
            border-radius: 4px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
            border-radius: 4px;
        }

        .strength-text {
            color: var(--text-muted);
            min-width: 60px;
            text-align: right;
        }

        /* Password Match Message */
        #passwordMatchMsg {
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Terms */
        .terms-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 1.4rem 0 1.6rem;
        }

        .terms-group input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: var(--orange);
            flex-shrink: 0;
            border-radius: 0;
        }

        .terms-group label {
            font-size: 0.8rem;
            color: #445;
            font-weight: 500;
            cursor: pointer;
            line-height: 1.4;
        }

        .terms-group a {
            color: var(--orange-dk);
            font-weight: 700;
            text-decoration: none;
        }

        .terms-group a:hover {
            text-decoration: underline;
        }

        /* Button */
        .btn-register {
            width: 100%;
            padding: 16px 12px;
            border: none;
            border-radius: 0;
            font-size: 0.95rem;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: linear-gradient(90deg, var(--orange-dk) 0%, var(--orange) 50%, var(--orange-lt) 100%);
            color: var(--white);
            cursor: pointer;
            transition: all 0.25s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 6px 20px rgba(244, 120, 32, 0.38), inset 0 1px 0 rgba(255, 255, 255, 0.15);
            position: relative;
            overflow: hidden;
            margin-top: -10px;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.18), transparent);
            transition: left 0.4s ease;
        }

        .btn-register:hover::before {
            left: 160%;
        }

        .btn-register:hover {
            box-shadow: 0 10px 28px rgba(244, 120, 32, 0.5);
            transform: translateY(-1px);
        }

        .btn-register:active {
            transform: translateY(1px);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.6rem 0;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 20px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Footer */
        .footer-band {
            background: var(--navy);
            text-align: center;
            padding: 1.1rem 1rem;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 400;
            flex-shrink: 0;
            border-top: 3px solid var(--orange);
        }

        .footer-band a {
            color: var(--orange-lt);
            font-weight: 700;
            text-decoration: none;
        }

        .footer-band a:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 420px) {
            .header-band {
                padding: 2.2rem 1.2rem 2.4rem;
            }

            .form-section {
                padding: 2.8rem 1.2rem 2rem;
            }

            .welcome-text {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>

    <!-- TOP HEADER -->

    <div class="logo-wrap">
        <img src="https://mobile.logintoloans.com/assets/images/logintoloan.jpeg" alt="Login To Loans"
            onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 280 76\'%3E%3Crect width=\'280\' height=\'76\' fill=\'%23f4782020\' rx=\'4\'/%3E%3Ctext x=\'140\' y=\'48\' font-family=\'Outfit,sans-serif\' font-size=\'22\' font-weight=\'800\' fill=\'%23f47820\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">

    </div>


    <!-- FORM SECTION -->
    <div class="form-section">
        <div class="form-inner">


            <form action="<?php echo BASE_URL; ?>auth/register_process.php" method="POST" id="registerForm"
                enctype="multipart/form-data" style="margin-top:-40px;">
                <div class="field">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <div class="input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" name="fullname" placeholder="Enter your full name" required>
                    </div>
                </div>

                <div class="field">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="your@email.com" required>
                    </div>
                </div>

                <div class="field">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <div class="input-wrap">
                        <i class="fas fa-phone"></i>
                        <input type="tel" name="phone" placeholder="Enter your mobile number" required>
                    </div>
                </div>
                <!-- ===== PINCODE FIELD ===== -->
                <div class="field">
                    <label><i class="fas fa-mail-bulk"></i> Pincode</label>
                    <div class="input-wrap">
                        <i class="fas fa-mail-bulk"></i>
                        <input type="text" name="pincode" placeholder="Enter 6-digit pincode" maxlength="6"
                            pattern="[0-9]{6}" title="Please enter a valid 6-digit pincode">
                    </div>
                </div>

                <div class="field">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <div class="input-wrap" id="pwWrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" placeholder="Minimum 6 characters" required
                            onkeyup="checkPasswordStrength()">
                    </div>
                </div>

                <div class="field">
                    <label><i class="fas fa-check-circle"></i> Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fas fa-check-circle"></i>
                        <input type="password" name="confirm_password" id="confirmPassword"
                            placeholder="Confirm your password" required onkeyup="checkPasswordMatch()">
                    </div>
                    <div id="passwordMatchMsg"></div>
                </div>
                <button type="submit" class="btn-register" id="registerBtn">
                    <i class="fas fa-user-plus"></i> REGISTER
                </button>
            </form>
        </div>
        <div class="divider"> Already have an account?
            <a href="<?php echo BASE_URL; ?>login.php"
                style="color: var(--orange-dk); font-weight: 700; text-decoration: none;">Login <i
                    class="fas fa-sign-in-alt"></i></a>
        </div>
    </div>



    <script>
        // Password Strength Checker
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            let color = '#ef4444';
            let text = 'Weak';

            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            let width = (strength / 5) * 100;

            if (strength <= 2) {
                color = '#ef4444';
                text = 'Weak';
            } else if (strength <= 3) {
                color = '#f59e0b';
                text = 'Fair';
            } else if (strength <= 4) {
                color = '#10b981';
                text = 'Good';
            } else {
                color = '#fd9130';
                text = 'Strong';
            }

            strengthFill.style.width = width + '%';
            strengthFill.style.backgroundColor = color;
            strengthText.innerHTML = text;
            strengthText.style.color = color;

            if (password.length === 0) {
                strengthFill.style.width = '0%';
                strengthText.innerHTML = 'Enter password';
                strengthText.style.color = '#8fa0be';
            }
        }

        // Password Match Checker
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const msgDiv = document.getElementById('passwordMatchMsg');

            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    msgDiv.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981;"></i> Passwords match';
                    msgDiv.style.color = '#10b981';
                } else {
                    msgDiv.innerHTML = '<i class="fas fa-times-circle" style="color: #ef4444;"></i> Passwords do not match';
                    msgDiv.style.color = '#ef4444';
                }
            } else {
                msgDiv.innerHTML = '';
            }
        }

        // Form validation before submit
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const terms = document.getElementById('terms').checked;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('❌ Passwords do not match!');
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('❌ Password must be at least 6 characters long!');
                return false;
            }

            if (!terms) {
                e.preventDefault();
                alert('❌ Please accept the Terms & Conditions to continue!');
                return false;
            }

            return true;
        });
    </script>
</body>

</html>