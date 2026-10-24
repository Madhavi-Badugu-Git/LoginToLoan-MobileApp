<?php

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Only run the mail logic when it's an AJAX POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // ---- 1. Read the submitted email ----
    $input = json_decode(file_get_contents('php://input'), true);
    $recipientEmail = trim($input['email'] ?? '');

    if (!$recipientEmail || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Please provide a valid email address']);
        exit;
    }

    // ---- 2. Send email via Hostinger SMTP ----
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@logintoloans.com';   // your full mailbox email
        $mail->Password   = 'Info.kurakulas@123'; // <-- fill this in
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // SSL
        $mail->Port       = 465;                          // fallback: 587 + ENCRYPTION_STARTTLS

        // $mail->SMTPDebug = 2; // uncomment to debug

        $mail->setFrom('noreply@logintoloans.com', 'LoginToLoans');
        $mail->addAddress($recipientEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Test Email from Hostinger SMTP';
        $mail->Body    = "<h2>Hello!</h2><p>This is a test email sent via Hostinger SMTP using PHPMailer.</p>";
        $mail->AltBody = "Hello! This is a test email sent via Hostinger SMTP using PHPMailer.";

        $mail->send();

        echo json_encode([
            'status'  => 'success',
            'message' => 'Email sent successfully to ' . $recipientEmail
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status'  => 'error',
            'message' => 'Email could not be sent: ' . $mail->ErrorInfo
        ]);
    }
    exit; // stop here so HTML below doesn't render for AJAX calls
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMTP Test - Send Mail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            width: 320px;
        }
        h2 { margin-top: 0; font-size: 18px; color: #1a237e; }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #ff9800;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }
        button:disabled { opacity: 0.6; cursor: not-allowed; }
        #result {
            margin-top: 14px;
            font-size: 13px;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .success { color: #2e7d32; }
        .error { color: #c62828; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Test Hostinger SMTP</h2>
        <input type="email" id="testEmail" placeholder="Enter recipient email" required>
        <button id="sendBtn" onclick="sendTestMail()">Send Test Email</button>
        <div id="result"></div>
    </div>

    <script>
        function sendTestMail() {
            const email = document.getElementById('testEmail').value.trim();
            const resultDiv = document.getElementById('result');
            const btn = document.getElementById('sendBtn');

            if (!email) {
                resultDiv.className = 'error';
                resultDiv.textContent = 'Please enter an email address.';
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Sending...';
            resultDiv.textContent = '';

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email })
            })
            .then(res => res.json())
            .then(data => {
                resultDiv.className = data.status === 'success' ? 'success' : 'error';
                resultDiv.textContent = data.message;
            })
            .catch(err => {
                resultDiv.className = 'error';
                resultDiv.textContent = 'Request failed: ' + err;
            })
            .finally(() => {
                btn.disabled = false;
                btn.textContent = 'Send Test Email';
            });
        }
    </script>
</body>
</html>