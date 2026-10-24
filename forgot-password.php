<?php


require_once 'includes/dbConfig.php';
require_once 'includes/mail.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'error';
    } else {
        $emailSafe = mysqli_real_escape_string($conn, $email);
        $query = mysqli_query($conn, "SELECT id, fullname, email FROM users WHERE email = '$emailSafe' LIMIT 1");

        if ($query && mysqli_num_rows($query) > 0) {
            $user = mysqli_fetch_assoc($query);
            $otp = random_int(100000, 999999);
            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $update = mysqli_query($conn, "UPDATE users SET reset_otp = '$otp', reset_otp_expiry = '$expiry' WHERE id = " . (int)$user['id']);

            if ($update) {
                $subject = 'LoginToLoans - Password Reset OTP';
                $body = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto;">
                    <h2 style="color: #1a237e;">LoginToLoans</h2>
                    <p>Hello ' . htmlspecialchars($user['fullname']) . ',</p>
                    <p>We received a request to reset your password.</p>
                    <p>Your password reset OTP is:</p>
                    <div style="font-size: 32px; font-weight: bold; letter-spacing: 8px; padding: 15px; background: #f4f4f4; text-align: center; margin: 20px 0;">
                        ' . $otp . '
                    </div>
                    <p>This OTP is valid for <strong>10 minutes</strong>.</p>
                    <p>If you did not request a password reset, please ignore this email.</p>
                    <br>
                    <p>Regards,<br><strong>LoginToLoans Team</strong></p>
                </div>
                ';

                if (sendMail($email, $subject, $body)) {
                    $_SESSION['reset_email'] = $email;
                    header('Location: verify-otp.php');
                    exit;
                } else {
                    $message = 'Unable to send OTP. Please try again.';
                    $messageType = 'error';
                }
            } else {
                $message = 'Unable to generate OTP. Please try again.';
                $messageType = 'error';
            }
        } else {
            $message = 'No account found with this email address.';
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - LoginToLoans</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .forgot-box {
            width: 380px;
            max-width: 90%;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.10);
            text-align: center; /* added to center the logo */
        }
        /* ---- Logo container (added) ---- */
        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
        }
        .logo-wrap img {
            max-width: 240px;
            width: 100%;
            height: auto;
            object-fit: contain;
        }
        h2 {
            margin-top: 0;
            text-align: center;
            color: #1a237e;
        }
        .description {
            text-align: center;
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            font-size: 14px;
            text-align: left;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }
        input:focus {
            border-color: #1a237e;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 18px;
            background: #ff9800;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #f57c00;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .error {
            background: #ffebee;
            color: #c62828;
        }
        .success {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .back-login {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .back-login a {
            color: #1a237e;
            text-decoration: none;
            font-weight: bold;
        }
        .back-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="forgot-box">

    <!-- ✅ LOGO ADDED (same as register page) -->
    <div class="logo-wrap">
        <img src="https://mobile.logintoloans.com/assets/images/logintoloan.jpeg" 
             alt="Login To Loans"
             onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 280 76\'%3E%3Crect width=\'280\' height=\'76\' fill=\'%23f4782020\' rx=\'4\'/%3E%3Ctext x=\'140\' y=\'48\' font-family=\'Outfit,sans-serif\' font-size=\'22\' font-weight=\'800\' fill=\'%23f47820\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">
    </div>

    <h2>Forgot Password?</h2>
    <p class="description">
        Enter your registered email address. We will send you a 6-digit OTP to reset your password.
    </p>

    <?php if (!empty($message)): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="email">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your registered email"
            required
            autocomplete="email"
            autofocus
        >
        <button type="submit">Send OTP</button>
    </form>

    <div class="back-login">
        <a href="login.php">← Back to Login</a>
    </div>
</div>
</body>
</html>