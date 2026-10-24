<?php
// ===== REMOVE session_start() FROM HERE - IT'S ALREADY IN dbConfig.php =====
// session_start(); // <-- REMOVE THIS LINE

include('includes/dbConfig.php');
// Password reset success message
$success = '';
if (isset($_GET['reset']) && $_GET['reset'] == 'success') {
    $success = 'Your password has been reset successfully. Please login with your new password.';
}

// ===== ENVIRONMENT DETECTION =====
$is_localhost = ($_SERVER['SERVER_NAME'] == 'localhost' ||
    $_SERVER['SERVER_NAME'] == '127.0.0.1' ||
    strpos($_SERVER['SERVER_NAME'], 'localhost') !== false);

// ===== AUTO LOGIN =====
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
    $id = mysqli_real_escape_string($conn, $_COOKIE['remember_user']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['profile_image'] = $user['profile_image'];
        header("Location: index.php");
        exit;
    }
}

$error = "";

// ── Logout ──
if (isset($_GET['logout'])) {
    // Clear session
    session_unset();
    session_destroy();

    // Delete cookie properly
    setcookie('remember_user', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    // Also try older method for compatibility
    setcookie('remember_user', '', time() - 3600, '/');

    header("Location: login.php");
    exit;
}

// ── Process POST ──
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE email='$email'
         AND password='$password'
         AND status='active'"
    );

    if (mysqli_num_rows($query) > 0) {
        $user_data = mysqli_fetch_assoc($query);

        // ── Set Remember Me Cookie ──
        if (isset($_POST['remember'])) {
            // Clear any existing cookie first
            setcookie('remember_user', '', time() - 3600, '/');

            // Set new cookie with proper parameters
            setcookie(
                'remember_user',
                $user_data['id'],
                [
                    'expires' => time() + (30 * 24 * 60 * 60),
                    'path' => '/',
                    'domain' => '', // Empty for localhost
                    'secure' => false, // false for localhost
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            );

            // Fallback for older PHP versions
            if (version_compare(PHP_VERSION, '7.3.0', '<')) {
                setcookie('remember_user', $user_data['id'], time() + (30 * 24 * 60 * 60), '/');
            }
        }

        // ── Set Session Variables ──
        $_SESSION['user_id']       = $user_data['id'];
        $_SESSION['fullname']      = $user_data['fullname'];
        $_SESSION['email']         = $user_data['email'];
        $_SESSION['role']          = $user_data['role'];
        $_SESSION['phone']         = $user_data['phone'];
        $_SESSION['profile_image'] = $user_data['profile_image'];

        // ============================================================
        // ===== ADDED: Profile completeness check =====
        // ============================================================
        $user_id = $user_data['id'];
        $check_query = mysqli_query(
            $conn,
            "SELECT dob, gender, aadhaar_number, pan_number, annual_income
             FROM users WHERE id = $user_id"
        );
        $profile = mysqli_fetch_assoc($check_query);
        $profile_complete = (
            !empty($profile['dob']) &&
            !empty($profile['gender']) &&
            !empty($profile['aadhaar_number']) &&
            !empty($profile['pan_number']) &&
            $profile['annual_income'] > 0
        );
        $_SESSION['profile_completed'] = $profile_complete;
        // ============================================================
        // ===== END ADDED ============================================
        // ============================================================

        // ── Redirect based on profile completeness (FIXED) ──
        // ============================================================
        // OLD REDIRECT (commented out):
        // if ($user_data['role'] == 'admin') {
        //     header("Location: user/dashboard.php");
        // } else {
        //     header("Location: user/personal-details.php");
        // }

        // NEW REDIRECT:
        if ($profile_complete) {
            header("Location: user/dashboard.php");
        } else {
            header("Location: user/personal-details.php");
        }
        exit;
        // ============================================================

    } else {
        $error = "Invalid Email or Password, or Account Inactive.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover" />
    <title>Login To Loans · Financial Super App</title>
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
            background: var(--navy-dark);
            overflow-x: hidden;
        }

        /* ── HEADER BAND ── */
        .header-band {
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-mid) 60%, #1a3a7a 100%);
            padding: 3rem 2rem 2.8rem;
            text-align: center;
            position: relative;
            flex-shrink: 0;
            border-bottom: 3px solid var(--orange);
        }

        .header-band::before {
            content: '';
            position: absolute;
            bottom: -18px;
            left: 0;
            right: 0;
            height: 18px;
            background: var(--orange);
            clip-path: polygon(0 0, 100% 0, 100% 55%, 0 100%);
            z-index: 2;
        }

        .header-band::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.07) 1px, transparent 1px);
            background-size: 22px 22px;
            pointer-events: none;
        }

        .logo-wrap {
            position: relative;
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            animation: fadeDown 0.6s ease both;
        }

        .logo-wrap img {
            max-width: 220px;
            width: 100%;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 6px 18px rgba(0, 0, 0, 0.35));
        }

        .tagline {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(244, 120, 32, 0.18);
            border: 1px solid rgba(244, 120, 32, 0.45);
            color: var(--orange-lt);
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

        /* Error */
        #errorContainer {
            margin-bottom: 0;
        }

        .error {
            background: #fff1ee;
            color: #b83300;
            padding: 11px 16px;
            font-size: 0.8rem;
            margin-bottom: 18px;
            font-weight: 600;
            border-left: 4px solid var(--orange-dk);
            display: flex;
            align-items: center;
            gap: 8px;
            animation: shake 0.3s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
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

        .input-wrap input {
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
        }

        .input-wrap input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(244, 120, 32, 0.12);
        }

        .input-wrap input:focus~i {
            color: var(--orange);
        }

        /* Row */
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.2rem 0 1.6rem;
        }

        .row label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 500;
            color: #445;
            cursor: pointer;
        }

        .row label input[type=checkbox] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: var(--orange);
            border-radius: 0;
        }

        .row a {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--orange-dk);
            text-decoration: none;
        }

        .row a:hover {
            text-decoration: underline;
        }

        /* Button */
        .btn-signin {
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
        }

        .btn-signin::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.18), transparent);
            transition: left 0.4s ease;
        }

        .btn-signin:hover::before {
            left: 160%;
        }

        .btn-signin:hover {
            box-shadow: 0 10px 28px rgba(244, 120, 32, 0.5);
            transform: translateY(-1px);
        }

        .btn-signin:active {
            transform: translateY(1px);
        }

        .btn-signin.loading {
            opacity: 0.8;
            cursor: wait;
        }

        .btn-signin.loading i {
            animation: spin 0.9s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg)
            }

            to {
                transform: rotate(360deg)
            }
        }

        /* Toggle pw */
        .toggle-pw {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            font-size: 1rem;
            z-index: 5;
            transition: color 0.2s;
        }

        .toggle-pw:hover {
            color: var(--orange);
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 1.2rem;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .register-link a {
            color: var(--orange);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s;
        }

        .register-link a:hover {
            color: var(--orange-dk);
            text-decoration: underline;
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
    <div class="header-band">
        <div class="logo-wrap">
            <img src="https://mobile.logintoloans.com/assets/images/logintoloan.jpeg" alt="Login To Loans"
                onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 280 76\'%3E%3Crect width=\'280\' height=\'76\' fill=\'%23f4782020\' rx=\'4\'/%3E%3Ctext x=\'140\' y=\'48\' font-family=\'Outfit,sans-serif\' font-size=\'22\' font-weight=\'800\' fill=\'%23f47820\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">
            <span class="tagline"><i class="fas fa-shield-alt"></i> Secure Access</span>
        </div>
    </div>

    <!-- FORM SECTION -->
    <div class="form-section">
        <div class="form-inner">
            <div class="welcome-text">Welcome <span>Back!</span></div>
            <div class="sub-text">Sign in to continue to your account</div>

            <div id="errorContainer">
                <?php if ($error != ""): ?>
                    <div class="error" id="errorMsg">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
            </div>

            <form method="POST" action="login.php" id="loginForm" novalidate>

                <div class="field">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="your@email.com" autocomplete="email"
                            required
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>

                <div class="field">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <div class="input-wrap" id="pwWrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••••••"
                            autocomplete="current-password" required>
                    </div>
                </div>

                <div class="row">
                    <label>
                        <input type="checkbox" name="remember" id="rememberCheck"> Remember me
                    </label>
                    <a href="forgot-password.php">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-signin" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> SIGN IN
                </button>

            </form>

            <div class="register-link">
                Don't have an account? <a href="register.php">Create Free Account</a>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-band">
        &copy; <?php echo date('Y'); ?> Login To Loans &bull; All rights reserved &bull;
        <a href="privacy-policy.php">Privacy</a> &bull;
        <a href="terms.php">Terms</a>
    </div>

    <script>
        // ── PASSWORD VISIBILITY TOGGLE ──
        const passwordInput = document.getElementById('password');
        const pwContainer = document.getElementById('pwWrapper');
        if (passwordInput && pwContainer) {
            const eyeIcon = document.createElement('i');
            eyeIcon.className = 'fas fa-eye-slash toggle-pw';
            eyeIcon.setAttribute('aria-label', 'Toggle password visibility');
            eyeIcon.addEventListener('click', () => {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                eyeIcon.className = type === 'password' ? 'fas fa-eye-slash toggle-pw' : 'fas fa-eye toggle-pw';
            });
            pwContainer.appendChild(eyeIcon);
            passwordInput.style.paddingRight = '48px';
        }

        // ── FORM VALIDATION + LOADING STATE ──
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        const errorContainer = document.getElementById('errorContainer');
        const emailField = document.getElementById('email');
        const passwordField = document.getElementById('password');

        function displayError(msg) {
            errorContainer.innerHTML = '';
            const d = document.createElement('div');
            d.className = 'error';
            d.id = 'errorMsg';
            d.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${msg}`;
            errorContainer.appendChild(d);
            // Auto-hide after 5 seconds
            setTimeout(() => {
                if (d.parentNode) {
                    d.style.opacity = '0';
                    d.style.transition = 'opacity 0.3s';
                    setTimeout(() => {
                        if (d.parentNode) d.remove();
                    }, 300);
                }
            }, 5000);
        }

        function clearErrors() {
            const errorMsg = document.getElementById('errorMsg');
            if (errorMsg) errorMsg.remove();
        }

        // ── Restore saved email from localStorage ──
        const rememberCheck = document.getElementById('rememberCheck');
        if (localStorage.getItem('savedLoginEmail')) {
            emailField.value = localStorage.getItem('savedLoginEmail');
            rememberCheck.checked = true;
        }

        // ── Clear errors on input ──
        emailField.addEventListener('input', clearErrors);
        passwordField.addEventListener('input', clearErrors);

        // ── Form Submit Handler ──
        loginForm.addEventListener('submit', function (e) {
            const email = emailField.value.trim();
            const password = passwordField.value;
            clearErrors();

            // Validation
            if (!email || !password) {
                e.preventDefault();
                displayError('Email and password are required to sign in.');
                return false;
            }
            if (!email.includes('@') || !email.includes('.')) {
                e.preventDefault();
                displayError('Please enter a valid email address (e.g. name@domain.com).');
                return false;
            }
            if (password.length < 3) {
                e.preventDefault();
                displayError('Password must be at least 3 characters.');
                return false;
            }

            // Save email if remember me is checked
            if (rememberCheck && rememberCheck.checked) {
                localStorage.setItem('savedLoginEmail', email);
            } else {
                localStorage.removeItem('savedLoginEmail');
            }

            // Show loading state
            loginBtn.classList.add('loading');
            loginBtn.innerHTML = '<i class="fas fa-spinner"></i> AUTHENTICATING...';
            loginBtn.disabled = true;

            // Fallback: reset button if form doesn't submit (e.g., network issues)
            setTimeout(() => {
                if (loginBtn.classList.contains('loading')) {
                    loginBtn.classList.remove('loading');
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> SIGN IN';
                    loginBtn.disabled = false;
                }
            }, 12000);

            return true;
        });

        // ── Debug: Log cookie info (remove in production) ──
        console.log('Document cookies:', document.cookie);
        console.log('Current domain:', window.location.hostname);
    </script>
</body>

</html>