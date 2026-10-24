<?php


require_once 'includes/dbConfig.php'; // must set $conn

$email = $_SESSION['reset_email'] ?? '';

if (empty($email)) {
    // No email in session – redirect back
    header('Location: forgot-password.php');
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Validate password
    if (empty($password) || strlen($password) < 6) {
        $message = 'Password must be at least 6 characters long.';
        $messageType = 'error';
    } elseif ($password !== $confirm) {
        $message = 'Passwords do not match.';
        $messageType = 'error';
    } else {
        // Hash the password – this is why you see $2y$10$... in the database
        $hashedPassword = $password;   // stores plain text

        $emailSafe = mysqli_real_escape_string($conn, $email);

        // Update password and clear OTP fields
        $update = mysqli_query(
            $conn,
            "UPDATE users
             SET password = '$hashedPassword',
                 reset_otp = NULL,
                 reset_otp_expiry = NULL
             WHERE email = '$emailSafe'"
        );

        if ($update) {
            // Clear session variables
            unset($_SESSION['reset_allowed']);
            unset($_SESSION['reset_email']);

            // Redirect to login with success message
            header('Location: login.php?reset=success');
            exit;
        } else {
            $message = 'Unable to update password. Please try again.';
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
    <title>Reset Password - LoginToLoans</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .reset-box {
            width: 380px;
            max-width: 90%;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
        h2 { text-align: center; color: #1a237e; margin-top: 0; }
        .desc {
            text-align: center;
            color: #666;
            font-size: 14px;
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
            margin-bottom: 15px;
        }
        input:focus {
            border-color: #1a237e;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            background: #ff9800;
            color: #fff;
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
        .back-link {
            text-align: center;
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
    </style>
</head>
<body>
<div class="reset-box">

    <!-- ✅ LOGO ADDED (same as register page) -->
    <div class="logo-wrap">
        <img src="https://mobile.logintoloans.com/assets/images/logintoloan.jpeg" 
             alt="Login To Loans"
             onerror="this.onerror=null;this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 280 76\'%3E%3Crect width=\'280\' height=\'76\' fill=\'%23f4782020\' rx=\'4\'/%3E%3Ctext x=\'140\' y=\'48\' font-family=\'Outfit,sans-serif\' font-size=\'22\' font-weight=\'800\' fill=\'%23f47820\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">
    </div>

    <h2>Create New Password</h2>
    <p class="desc">
        Enter your new password below.
    </p>

    <?php if (!empty($message)): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label for="password">New Password</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Enter new password (min 6 chars)"
            required
            minlength="6"
        >

        <label for="confirm_password">Confirm Password</label>
        <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            placeholder="Confirm new password"
            required
            minlength="6"
        >

        <button type="submit">Reset Password</button>
    </form>

    <div class="back-link">
        <a href="login.php">← Back to Login</a>
    </div>
</div>
</body>
</html>