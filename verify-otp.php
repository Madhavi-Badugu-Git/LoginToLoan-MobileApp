<?php

require_once 'includes/dbConfig.php';
require_once 'includes/mail.php';

date_default_timezone_set('Asia/Kolkata');

$email = $_SESSION['reset_email'];
$message = '';
$messageType = '';

// --- Resend OTP ---
if (isset($_POST['resend_otp'])) {
    $emailSafe = mysqli_real_escape_string($conn, $email);
    $userQuery = mysqli_query($conn, "SELECT id, fullname FROM users WHERE email = '$emailSafe' LIMIT 1");

    if ($userQuery && mysqli_num_rows($userQuery) > 0) {
        $user = mysqli_fetch_assoc($userQuery);
        $otp = random_int(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $update = mysqli_query($conn, "UPDATE users SET reset_otp = '$otp', reset_otp_expiry = '$expiry' WHERE id = " . (int)$user['id']);

        if ($update) {
            $subject = 'LoginToLoans - New OTP';
            $body = "<h2>Your new OTP is: <strong>$otp</strong></h2><p>Valid for 10 minutes.</p>";
            if (sendMail($email, $subject, $body)) {
                $message = '✅ New OTP sent!';
                $messageType = 'success';
            } else {
                $message = '❌ Failed to send email.';
                $messageType = 'error';
            }
        } else {
            $message = '❌ Database error.';
            $messageType = 'error';
        }
    } else {
        $message = '❌ User not found.';
        $messageType = 'error';
    }
}

// --- Verify OTP ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['resend_otp'])) {
    $otp = trim($_POST['otp'] ?? '');
    if (empty($otp) || !preg_match('/^\d{6}$/', $otp)) {
        $message = 'Please enter a valid 6-digit OTP.';
        $messageType = 'error';
    } else {
        $emailSafe = mysqli_real_escape_string($conn, $email);
        $otpSafe = mysqli_real_escape_string($conn, $otp);

        $query = mysqli_query($conn, "SELECT id FROM users WHERE email = '$emailSafe' AND reset_otp = '$otpSafe' AND reset_otp_expiry >= NOW() LIMIT 1");

        if ($query && mysqli_num_rows($query) > 0) {
            $_SESSION['reset_allowed'] = true;
            header('Location: reset-password.php');
            exit;
        } else {
            $message = '❌ Invalid or expired OTP.';
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
    <title>Verify OTP - LoginToLoans</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Outfit', Arial, sans-serif;
            background: #f4f6fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .verify-box {
            width: 380px;
            max-width: 90%;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        /* ---- Logo (same as register page) ---- */
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
            filter: drop-shadow(0 6px 18px rgba(0, 0, 0, 0.15));
        }
        h2 {
            margin-top: 0;
            color: #1a237e;
            font-size: 1.6rem;
            font-weight: 800;
        }
        .desc {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }
        .email-display {
            background: #f1f1f1;
            padding: 8px;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
            margin-bottom: 15px;
            color: #555;
        }
        .email-display strong { color: #1a237e; }
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
            border: 2px solid #dce3ef;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            text-align: center;
            letter-spacing: 6px;
            font-family: 'Outfit', Arial, sans-serif;
        }
        input:focus {
            border-color: #f47820;
            box-shadow: 0 0 0 3px rgba(244,120,32,0.12);
        }
        button {
            width: 100%;
            padding: 14px;
            margin-top: 18px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.2s;
        }
        .btn-verify {
            background: #f47820;
            color: #fff;
        }
        .btn-verify:hover {
            background: #d45f00;
        }
        .btn-resend {
            background: #2196F3;
            color: #fff;
            margin-top: 10px;
        }
        .btn-resend:hover {
            background: #1976D2;
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
        .back-link {
            margin-top: 20px;
            font-size: 14px;
        }
        .back-link a {
            color: #1a237e;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        hr {
            margin: 15px 0;
            border: 0;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
<div class="verify-box">

    <!-- ✅ LOGO – same as register page -->
    <div class="logo-wrap">
        <img src="https://mobile.logintoloans.com/assets/images/logintoloan.jpeg" 
             alt="Login To Loans"
             onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 280 76\'%3E%3Crect width=\'280\' height=\'76\' fill=\'%23f4782020\' rx=\'4\'/%3E%3Ctext x=\'140\' y=\'48\' font-family=\'Outfit,sans-serif\' font-size=\'22\' font-weight=\'800\' fill=\'%23f47820\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">
    </div>

    <h2>Verify OTP</h2>
    <p class="desc">Enter the 6-digit code sent to your email.</p>
    
    <div class="email-display">
         <strong><?php echo htmlspecialchars($email); ?></strong>
    </div>

    <?php if (!empty($message)): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="otp">One-Time Password</label>
        <input type="text" id="otp" name="otp" placeholder="123456" maxlength="6" required autofocus pattern="\d{6}" title="Enter exactly 6 digits">
        <button type="submit" class="btn-verify">Verify OTP</button>
    </form>

    <hr>
    <form method="POST">
        <button type="submit" name="resend_otp" class="btn-resend">📨 Resend OTP</button>
    </form>

    <div class="back-link">
        <a href="login.php">← Back to Login</a>
    </div>
</div>
</body>
</html>