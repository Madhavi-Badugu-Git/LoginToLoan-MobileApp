<?php
// ============================================================
// 1. INCLUDES (dbConfig.php already starts session & defines redirect/sanitize)
// ============================================================
include('../includes/dbConfig.php');  // Starts session, defines redirect() and sanitize()
require_once '../includes/mail.php'; // For sending welcome email

// ============================================================
// 2. PROCESS REGISTRATION
// ============================================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ✅ Use sanitize() from dbConfig.php
    $fullname = sanitize($_POST['fullname']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $pincode = sanitize($_POST['pincode'] ?? '');
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ================= IMAGE UPLOAD =================
    $profile_image = "";

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['name'] != '') {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
            $profile_image = $fileName;
        }
    }

    // ================= VALIDATION =================
    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match!";
        redirect('register.php');
    }

    if (strlen($password) < 6) {
        $_SESSION['error_message'] = "Password must be at least 6 characters!";
        redirect('register.php');
    }

    // ================= EMAIL CHECK =================
    $emailSafe = mysqli_real_escape_string($conn, $email);
    $check = "SELECT id FROM users WHERE email = '$emailSafe'";
    $result = mysqli_query($conn, $check);

    if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['error_message'] = "Email already registered!";
        redirect('register.php');
    }

    // ================= HASH PASSWORD =================
   $hashedPassword = $password;  // stores plain text

    // ================= INSERT QUERY =================
    $fullnameSafe = mysqli_real_escape_string($conn, $fullname);
    $phoneSafe = mysqli_real_escape_string($conn, $phone);
    $pincodeSafe = mysqli_real_escape_string($conn, $pincode);
    $profileImageSafe = mysqli_real_escape_string($conn, $profile_image);

    $sql = "INSERT INTO users (fullname, email, phone, pincode, password, role, status, profile_image) 
            VALUES ('$fullnameSafe', '$emailSafe', '$phoneSafe', '$pincodeSafe', '$hashedPassword', 'user', 'active', '$profileImageSafe')";

    if (mysqli_query($conn, $sql)) {

        $userId = mysqli_insert_id($conn);

        // ============================================================
        // 📧 SEND WELCOME EMAIL
        // ============================================================
        $subject = 'Welcome to LoginToLoans – Registration Successful!';

        $body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Welcome to LoginToLoans</title>
        </head>
        <body style="font-family: Arial, sans-serif; background: #f4f6fa; padding: 30px;">
            <div style="max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                <div style="text-align: center; margin-bottom: 20px;">
                    <img src="https://mobile.logintoloans.com/assets/images/logintoloan.jpeg" alt="LoginToLoans" style="max-width: 200px; height: auto;">
                </div>
                <h2 style="color: #1a237e; text-align: center;">Welcome to LoginToLoans!</h2>
                <p>Hello <strong>' . htmlspecialchars($fullname) . '</strong>,</p>
                <p>Thank you for registering with us. Your account has been successfully created.</p>
                <div style="background: #f1f1f1; padding: 15px; border-radius: 6px; margin: 20px 0;">
                    <p style="margin: 5px 0;"><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                    <p style="margin: 5px 0;"><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>
                </div>
                <div style="text-align: center; margin: 25px 0;">
                    <a href="https://yourdomain.com/login.php" style="background: #f47820; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                        Login Now
                    </a>
                </div>
                <p>If you did not create this account, please ignore this email.</p>
                <br>
                <p style="color: #666; font-size: 14px;">Regards,<br><strong>LoginToLoans Team</strong></p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <p style="color: #999; font-size: 12px; text-align: center;">
                    This is an automated message. Please do not reply to this email.
                </p>
            </div>
        </body>
        </html>
        ';

        $mailSent = sendMail($email, $subject, $body);

        if (!$mailSent) {
            error_log("Welcome email failed to send for: $email");
        }

        // ============================================================
        // SET SESSION & REDIRECT WITH SUCCESS FLAG
        // ============================================================
        $_SESSION['user_id'] = $userId;
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        $_SESSION['profile_image'] = $profile_image;
        $_SESSION['registration_success'] = "✅ Registration successful! A confirmation email has been sent to your inbox.";

        redirect('login.php?registered=1');

    } else {
        $_SESSION['error_message'] = "Registration failed: " . mysqli_error($conn);
        redirect('register.php');
    }

} else {
    redirect('register.php');
}
?>