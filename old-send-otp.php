<?php

session_start();

require_once 'includes/mail.php';
require_once 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request.";
    exit;
}

// Get email from Forgot Password form
$email = trim($_POST['email'] ?? '');


// Validate email
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please enter a valid email address.";
    exit;
}


// Escape email
$emailSafe = mysqli_real_escape_string($conn, $email);


// Check email in users table
$query = mysqli_query(
    $conn,
    "SELECT id, fullname, email
     FROM users
     WHERE email = '$emailSafe'
     LIMIT 1"
);


if (!$query) {
    echo "Database error: " . mysqli_error($conn);
    exit;
}


if (mysqli_num_rows($query) == 0) {

    echo "Email address is not registered.";
    exit;
}


$user = mysqli_fetch_assoc($query);


// Generate 6-digit OTP
$otp = random_int(100000, 999999);


// OTP valid for 10 minutes
$expiry = date(
    'Y-m-d H:i:s',
    strtotime('+10 minutes')
);


// Save OTP in users table
$update = mysqli_query(
    $conn,
    "UPDATE users
     SET reset_otp = '$otp',
         reset_otp_expiry = '$expiry'
     WHERE id = " . (int)$user['id']
);


if (!$update) {

    echo "Unable to save OTP: " . mysqli_error($conn);
    exit;
}


// Email subject
$subject = 'LoginToLoans - Password Reset OTP';


// Email body
$body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f4f6fa; padding:30px;">

    <div style="
        max-width:600px;
        margin:auto;
        background:#ffffff;
        padding:30px;
        border-radius:10px;
    ">

        <h2 style="color:#1a237e;">
            LoginToLoans
        </h2>

        <p>
            Hello ' . htmlspecialchars($user['fullname']) . ',
        </p>

        <p>
            We received a request to reset your password.
        </p>

        <p>
            Your password reset OTP is:
        </p>

        <div style="
            font-size:32px;
            font-weight:bold;
            letter-spacing:8px;
            text-align:center;
            background:#f1f1f1;
            padding:15px;
            margin:20px 0;
            border-radius:8px;
        ">
            ' . $otp . '
        </div>

        <p>
            This OTP is valid for <strong>10 minutes</strong>.
        </p>

        <p>
            If you did not request a password reset,
            please ignore this email.
        </p>

        <br>

        <p>
            Regards,<br>
            <strong>LoginToLoans Team</strong>
        </p>

    </div>

</body>
</html>
';


// Send email
if (sendMail($email, $subject, $body)) {

    // Store email in session
    $_SESSION['reset_email'] = $email;

    // Redirect to OTP verification page
    header('Location: verify-otp.php');
    exit;

} else {

    echo "Failed to send OTP.";
    exit;
}
?>