<?php
// FILE: user/profile.php

include('../includes/dbConfig.php');

// Helper functions
if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}
if (!function_exists('redirect')) {
    function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}
if (!function_exists('sanitize')) {
    function sanitize($input)
    {
        return htmlspecialchars(strip_tags(trim($input)));
    }
}

if (!isLoggedIn()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Get user data
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_image'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $filename = $_FILES['profile_image']['name'];
        $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $file_size = $_FILES['profile_image']['size'];

        if ($file_size > 5 * 1024 * 1024) {
            $error_message = "File size too large! Maximum 5MB allowed.";
        } elseif (in_array($fileext, $allowed)) {
            $new_filename = 'user_' . $user_id . '_' . time() . '.' . $fileext;
            $upload_path = '../../uploads/profile/' . $new_filename;

            if (!is_dir('../../uploads/profile/')) {
                mkdir('../../uploads/profile/', 0777, true);
            }

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                $old_image = $user['profile_image'] ?? '';
                if ($old_image && file_exists('../../uploads/profile/' . $old_image)) {
                    unlink('../../uploads/profile/' . $old_image);
                }

                $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                $stmt->bind_param("si", $new_filename, $user_id);
                $stmt->execute();
                $success_message = "Profile picture updated successfully!";
                $user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
            } else {
                $error_message = "Failed to upload image. Please try again.";
            }
        } else {
            $error_message = "Invalid file type. Allowed: JPG, JPEG, PNG, GIF, WEBP";
        }
    } else {
        $error_message = "Please select an image file to upload.";
    }
}

// Handle profile update (from editable form)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = sanitize($_POST['fullname']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $dob = $_POST['dob'] ?? null;
    $gender = sanitize($_POST['gender']);
    $annual_income = floatval($_POST['annual_income']);

    $stmt = $conn->prepare("UPDATE users SET fullname = ?, phone = ?, address = ?, dob = ?, gender = ?, annual_income = ? WHERE id = ?");
    $stmt->bind_param("sssssdi", $fullname, $phone, $address, $dob, $gender, $annual_income, $user_id);

    if ($stmt->execute()) {
        $_SESSION['fullname'] = $fullname;
        $success_message = "Profile updated successfully!";
        $user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $user_query = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user_data = $user_query->get_result()->fetch_assoc();

    $password_valid = false;
    if (password_get_info($user_data['password'])['algo']) {
        $password_valid = password_verify($current_password, $user_data['password']);
    } else {
        $password_valid = ($current_password == $user_data['password']);
    }

    if ($password_valid) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 6) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $hashed_password, $user_id);
                $update_stmt->execute();
                $success_message = "Password changed successfully!";
            } else {
                $error_message = "New password must be at least 6 characters long!";
            }
        } else {
            $error_message = "New password and confirm password do not match!";
        }
    } else {
        $error_message = "Current password is incorrect!";
    }
}

// Get user statistics
$total_investments = $conn->query("SELECT COUNT(*) as count, SUM(amount) as total FROM user_investments WHERE user_id = $user_id AND status = 'active'")->fetch_assoc();
$total_loans = $conn->query("SELECT COUNT(*) as count, SUM(amount) as total FROM loans WHERE user_id = $user_id AND status IN ('approved', 'active')")->fetch_assoc();

$initials = strtoupper(mb_substr($user['fullname'] ?? 'U', 0, 1));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#001653">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>My Profile · Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        /* ===== RESET & ROOT ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --primary: #001653;
            --primary-dark: #000d3a;
            --primary-light: #002080;
            --secondary: #FE8402;
            --secondary-light: #ff9933;
            --secondary-dark: #e67300;
            --accent: #FE8402;
            --text-primary: #001653;
            --text-secondary: #4a4a6a;
            --text-tertiary: #8888aa;
            --background: #f5f6fa;
            --white: #ffffff;
            --border: #e8eaf0;
            --shadow: 0 2px 8px rgba(0, 22, 83, 0.06);
            --shadow-md: 0 4px 16px rgba(0, 22, 83, 0.10);
            --shadow-lg: 0 8px 30px rgba(0, 22, 83, 0.15);
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --gradient-primary: linear-gradient(135deg, #001653 0%, #002080 50%, #0033aa 100%);
            --gradient-secondary: linear-gradient(135deg, #FE8402 0%, #ff9933 50%, #e67300 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-y: auto;
        }

        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: var(--border);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 10px;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
            min-height: 100vh;
            position: relative;
            padding-bottom: 20px;
        }

        /* ===== TOP HEADER ===== */
        .top-header {
            position: sticky;
            top: 0;
            z-index: 200;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            box-shadow: 0 2px 16px rgba(0, 22, 83, 0.2);
            border-bottom: 2px solid var(--secondary);
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

        .header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 22, 83, 0.08);
            border: 1px solid rgba(0, 22, 83, 0.1);
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            margin-right: 8px;
        }

        .back-btn:hover {
            background: rgba(0, 22, 83, 0.12);
        }

        .back-btn:active {
            transform: scale(0.95);
        }

        .back-btn i {
            color: var(--secondary);
        }

        .logout-btn {
            width: 42px;
            height: 42px;
            border: 1px solid rgba(239, 68, 68, 0.2);
            background: rgba(239, 68, 68, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 6px;
            border-radius: 10px;
            transition: all 0.2s;
            color: #ef4444;
            font-size: 20px;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
        }

        .logout-btn:active {
            transform: scale(0.95);
        }

        /* ===== TOAST & DIALOG ===== */
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--primary);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 13px;
            z-index: 1000;
            transition: transform 0.3s;
            white-space: nowrap;
            border: 1px solid var(--secondary);
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
        }

        .logout-dialog-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 22, 83, 0.7);
            backdrop-filter: blur(8px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        .logout-dialog-overlay.active {
            display: flex;
        }

        .logout-dialog {
            background: var(--white);
            border-radius: 24px;
            padding: 32px 28px 28px;
            max-width: 360px;
            width: 90%;
            text-align: center;
            animation: slideUp 0.3s ease;
            box-shadow: 0 25px 60px rgba(0, 22, 83, 0.3);
            border-top: 4px solid var(--secondary);
        }

        .logout-dialog .dialog-icon {
            width: 72px;
            height: 72px;
            background: #fff3e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .logout-dialog .dialog-icon i {
            font-size: 32px;
            color: var(--secondary);
        }

        .logout-dialog h3 {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .logout-dialog p {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .logout-dialog .dialog-actions {
            display: flex;
            gap: 12px;
        }

        .logout-dialog .dialog-actions button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .logout-dialog .btn-cancel {
            background: #f1f5f9;
            color: var(--text-secondary);
        }

        .logout-dialog .btn-cancel:hover {
            background: #e2e8f0;
        }

        .logout-dialog .btn-logout {
            background: var(--gradient-secondary);
            color: white;
            box-shadow: 0 4px 14px rgba(254, 132, 2, 0.4);
        }

        .logout-dialog .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(254, 132, 2, 0.5);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ===== PROFILE LAYOUT ===== */
        .profile-container {
            max-width: 100%;
            margin: 0;
            padding: 16px;
        }

        .prof-hero {
            background: var(--gradient-primary);
            border-radius: 20px;
            padding: 24px 20px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(254, 132, 2, 0.2);
        }

        .prof-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 60% 80% at 85% 40%, rgba(254, 132, 2, 0.15) 0%, transparent 65%),
                radial-gradient(ellipse 40% 55% at 5% 90%, rgba(254, 132, 2, 0.10) 0%, transparent 60%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(254, 132, 2, 0.2);
            border: 1px solid rgba(254, 132, 2, 0.3);
            border-radius: 50px;
            padding: 4px 14px;
            font-size: 10.5px;
            font-weight: 600;
            color: var(--secondary);
            letter-spacing: .7px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .prof-hero h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.5px;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .prof-hero h1 span {
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .prof-hero p {
            font-size: .75rem;
            color: rgba(255, 255, 255, 0.75);
        }

        .alert-success {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #2e7d32;
            font-size: 0.8rem;
        }

        .alert-error {
            background: #fbe9e7;
            border-left: 4px solid #ef5350;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #c62828;
            font-size: 0.8rem;
        }

        .profile-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ===== SIDEBAR ===== */
        .profile-sidebar {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid rgba(0, 22, 83, 0.08);
            overflow: hidden;
        }

        .profile-avatar {
            background: var(--gradient-primary);
            padding: 24px;
            text-align: center;
            position: relative;
            border-bottom: 3px solid var(--secondary);
        }

        .avatar-image {
            width: 90px;
            height: 90px;
            background: var(--white);
            border-radius: 50%;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            border: 3px solid var(--secondary);
        }

        .avatar-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-image i {
            font-size: 2.5rem;
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .upload-btn {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: var(--gradient-secondary);
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid var(--white);
            transition: transform 0.2s;
            box-shadow: 0 4px 12px rgba(254, 132, 2, 0.4);
        }

        .upload-btn:active {
            transform: scale(0.95);
        }

        .upload-btn i {
            font-size: 0.8rem;
            color: var(--white);
        }

        .profile-avatar h3 {
            color: var(--white);
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .profile-avatar p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.7rem;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 600;
            margin-top: 8px;
            background: rgba(254, 132, 2, 0.3);
            color: var(--white);
            border: 1px solid rgba(254, 132, 2, 0.4);
        }

        .profile-stats {
            padding: 16px;
        }

        .stat-item-sidebar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
        }

        .stat-item-sidebar:last-child {
            border-bottom: none;
        }

        .stat-label-sidebar {
            color: #64748b;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-label-sidebar i {
            color: var(--secondary);
            width: 18px;
        }

        .stat-value-sidebar {
            font-weight: 700;
            color: var(--primary);
            font-size: 0.8rem;
        }

        .member-since {
            background: rgba(254, 132, 2, 0.08);
            padding: 12px;
            border-radius: 14px;
            margin-top: 8px;
            text-align: center;
            font-size: 0.7rem;
            color: var(--primary);
            border: 1px solid rgba(254, 132, 2, 0.1);
        }

        /* ===== CARDS ===== */
        .profile-card {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid rgba(0, 22, 83, 0.08);
            overflow: hidden;
            margin-bottom: 16px;
        }

        .card-header {
            background: linear-gradient(135deg, #f8f9fc, var(--white));
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            font-size: 1.1rem;
            color: var(--secondary);
        }

        .card-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
        }

        .card-body {
            padding: 18px;
        }

        /* ===== DISPLAY GRID (view mode) ===== */
        .info-grid {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        .info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-label {
            min-width: 110px;
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-label i {
            color: var(--secondary);
            width: 18px;
            text-align: center;
        }

        .info-value {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-primary);
            word-break: break-word;
            flex: 1;
        }

        .info-value .empty {
            color: var(--text-tertiary);
            font-style: italic;
        }

        /* ===== EDIT FORM (hidden by default) ===== */
        .edit-form {
            display: none;
        }

        .edit-form.active {
            display: block;
        }

        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.7rem;
        }

        .form-group label i {
            margin-right: 6px;
            color: var(--secondary);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            outline: none;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(254, 132, 2, 0.15);
        }

        .form-group input:disabled {
            background: #f8fafc;
            cursor: not-allowed;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            padding: 10px 20px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            width: 100%;
            border: 1px solid rgba(254, 132, 2, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 22, 83, 0.3);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid var(--border);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .btn-secondary:hover {
            border-color: var(--secondary);
            color: var(--secondary);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--primary);
        }

        .action-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .action-row .btn-secondary {
            flex: 1;
            text-align: center;
        }

        .image-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 22, 83, 0.7);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .image-modal-content {
            background: var(--white);
            border-radius: 24px;
            padding: 20px;
            max-width: 90%;
            width: 320px;
            text-align: center;
            border-top: 4px solid var(--secondary);
        }

        .image-modal-content h3 {
            color: var(--primary);
        }

        .image-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 15px auto;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 3px solid var(--secondary);
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview i {
            font-size: 2.5rem;
            color: var(--primary);
        }

        small {
            color: var(--text-tertiary);
            font-size: 0.65rem;
        }
    </style>
</head>

<body>
    <div class="app-container">

        <!-- ===== TOP HEADER ===== -->
        <header class="top-header">
            <button class="back-btn" onclick="window.location.href='../user/dashboard.php'">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <div class="header-logo">
                <img src="https://mobile.logintoloans.com/assets/images/logintoloan.jpeg" alt="Login To Loans"
                    onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 200 50\'%3E%3Crect width=\'200\' height=\'50\' fill=\'%23001653\' rx=\'8\'/%3E%3Ctext x=\'100\' y=\'32\' font-family=\'Arial, sans-serif\' font-size=\'18\' font-weight=\'bold\' fill=\'%23FE8402\' text-anchor=\'middle\'%3ELOGIN TO LOANS%3C/text%3E%3C/svg%3E'">
            </div>
            <div class="header-right">
                <button class="logout-btn" onclick="showLogoutDialog()">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </header>

        <!-- ===== LOGOUT DIALOG ===== -->
        <div class="logout-dialog-overlay" id="logoutDialog">
            <div class="logout-dialog">
                <div class="dialog-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h3>Logout?</h3>
                <p>Are you sure you want to logout from your account? You'll need to login again to access your profile.
                </p>
                <div class="dialog-actions">
                    <button class="btn-cancel" onclick="closeLogoutDialog()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn-logout" onclick="confirmLogout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>
        </div>

        <!-- ===== PROFILE CONTENT ===== -->
        <div class="profile-container">
            <!-- Hero -->
            <div class="prof-hero">
                <div class="hero-content">
                    <div class="hero-eyebrow"><i class="fas fa-user-circle"></i> Account Settings</div>
                    <h1>My <span>Profile</span></h1>
                    <p>Manage your personal information, security, and account preferences</p>
                </div>
            </div>

            <!-- Alerts -->
            <?php if ($success_message): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Profile Grid -->
            <div class="profile-grid">
                <!-- Sidebar -->
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <div class="avatar-image" id="avatarImage">
                            <?php if (!empty($user['profile_image']) && file_exists('../../uploads/profile/' . $user['profile_image'])): ?>
                                <img src="../../uploads/profile/<?php echo htmlspecialchars($user['profile_image']); ?>"
                                    alt="Profile Picture">
                            <?php else: ?>
                                <i class="fas fa-user-circle"></i>
                            <?php endif; ?>
                        </div>
                        <div class="upload-btn" onclick="showUploadModal()">
                            <i class="fas fa-camera"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($user['fullname']); ?></h3>
                        <p><?php echo htmlspecialchars($user['email']); ?></p>
                        <div class="role-badge">
                            <i class="fas <?php echo $user['role'] == 'admin' ? 'fa-shield-alt' : 'fa-user'; ?>"></i>
                            <?php echo ucfirst(htmlspecialchars($user['role'])); ?> Account
                        </div>
                    </div>

                    <div class="profile-stats">
                        <div class="stat-item-sidebar">
                            <span class="stat-label-sidebar"><i class="fas fa-chart-line"></i> Total Invested</span>
                            <span
                                class="stat-value-sidebar">₹<?php echo number_format($total_investments['total'] ?? 0, 2); ?></span>
                        </div>
                        <div class="stat-item-sidebar">
                            <span class="stat-label-sidebar"><i class="fas fa-briefcase"></i> Active Investments</span>
                            <span class="stat-value-sidebar"><?php echo $total_investments['count'] ?? 0; ?></span>
                        </div>
                        <div class="stat-item-sidebar">
                            <span class="stat-label-sidebar"><i class="fas fa-hand-holding-usd"></i> Total Loans</span>
                            <span
                                class="stat-value-sidebar">₹<?php echo number_format($total_loans['total'] ?? 0, 2); ?></span>
                        </div>
                        <div class="stat-item-sidebar">
                            <span class="stat-label-sidebar"><i class="fas fa-calendar-alt"></i> Member Since</span>
                            <span
                                class="stat-value-sidebar"><?php echo date('M Y', strtotime($user['created_at'])); ?></span>
                        </div>
                        <div class="stat-item-sidebar">
                            <span class="stat-label-sidebar"><i class="fas fa-tag"></i> Status</span>
                            <span class="stat-value-sidebar" style="color: var(--secondary);">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                <?php echo ucfirst(htmlspecialchars($user['status'])); ?>
                            </span>
                        </div>
                        
                        <div class="member-since">
                            <i class="fas fa-star" style="color: var(--secondary);"></i> Investor since
                            <?php echo date('F Y', strtotime($user['created_at'])); ?>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div>
                    <!-- Personal Information Card -->
                    <div class="profile-card" id="profileCard">
                        <div class="card-header">
                            <i class="fas fa-user-edit"></i>
                            <h3>Personal Information</h3>
                            <span style="margin-left: auto;">
                                <button class="btn-secondary" id="editToggleBtn" onclick="toggleEditMode()">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </span>
                        </div>
                        <div class="card-body">
                            <!-- ===== VIEW MODE ===== -->
                            <div id="viewMode" class="info-grid">
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-user"></i> Full Name</span>
                                    <span class="info-value"><?php echo htmlspecialchars($user['fullname']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-envelope"></i> Email</span>
                                    <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-phone"></i> Phone</span>
                                    <span
                                        class="info-value"><?php echo htmlspecialchars($user['phone'] ?? ''); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-calendar-alt"></i> Date of Birth</span>
                                    <span class="info-value"><?php echo htmlspecialchars($user['dob'] ?? ''); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-venus-mars"></i> Gender</span>
                                    <span
                                        class="info-value"><?php echo ucfirst(htmlspecialchars($user['gender'] ?? '')); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-money-bill-wave"></i> Annual Income</span>
                                    <span
                                        class="info-value">₹<?php echo number_format($user['annual_income'] ?? 0, 2); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-map-marker-alt"></i> Address</span>
                                    <span
                                        class="info-value"><?php echo nl2br(htmlspecialchars($user['address'] ?? '')); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-tag"></i> Role</span>
                                    <span
                                        class="info-value"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-flag-checkered"></i> Status</span>
                                    <span
                                        class="info-value"><?php echo ucfirst(htmlspecialchars($user['status'])); ?></span>
                                </div>
                            </div>

                            <!-- ===== EDIT MODE (hidden by default) ===== -->
                            <div id="editMode" class="edit-form">
                                <form method="POST" action="">
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label><i class="fas fa-user"></i> Full Name</label>
                                            <input type="text" name="fullname"
                                                value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-envelope"></i> Email (read‑only)</label>
                                            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                                                disabled>
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-phone"></i> Phone Number</label>
                                            <input type="tel" name="phone"
                                                value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                                placeholder="Enter your phone number">
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-calendar-alt"></i> Date of Birth</label>
                                            <input type="date" name="dob"
                                                value="<?php echo htmlspecialchars($user['dob'] ?? ''); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-venus-mars"></i> Gender</label>
                                            <select name="gender">
                                                <option value="">Select</option>
                                                <option value="male" <?php echo ($user['gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Male</option>
                                                <option value="female" <?php echo ($user['gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Female</option>
                                                <option value="other" <?php echo ($user['gender'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-money-bill-wave"></i> Annual Income (₹)</label>
                                            <input type="number" name="annual_income"
                                                value="<?php echo htmlspecialchars($user['annual_income'] ?? ''); ?>"
                                                placeholder="Enter annual income" min="0" step="1">
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-map-marker-alt"></i> Address</label>
                                            <textarea name="address" rows="3"
                                                placeholder="Enter your complete address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-tag"></i> Role (read‑only)</label>
                                            <input type="text"
                                                value="<?php echo ucfirst(htmlspecialchars($user['role'])); ?>"
                                                disabled>
                                        </div>
                                        <div class="form-group">
                                            <label><i class="fas fa-flag-checkered"></i> Status (read‑only)</label>
                                            <input type="text"
                                                value="<?php echo ucfirst(htmlspecialchars($user['status'])); ?>"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" class="btn-secondary" onclick="toggleEditMode()">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                        <button type="submit" name="update_profile" class="btn-primary">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password Card -->
                    <div class="profile-card">
                        <div class="card-header">
                            <i class="fas fa-lock"></i>
                            <h3>Change Password</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label><i class="fas fa-key"></i> Current Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" name="current_password" id="current_password"
                                                placeholder="Enter current password" required>
                                            <span class="password-toggle" onclick="togglePassword('current_password')">
                                                <i class="fas fa-eye-slash"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-lock"></i> New Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" name="new_password" id="new_password"
                                                placeholder="Minimum 6 characters" required>
                                            <span class="password-toggle" onclick="togglePassword('new_password')">
                                                <i class="fas fa-eye-slash"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-check-circle"></i> Confirm Password</label>
                                        <div class="password-wrapper">
                                            <input type="password" name="confirm_password" id="confirm_password"
                                                placeholder="Confirm new password" required>
                                            <span class="password-toggle" onclick="togglePassword('confirm_password')">
                                                <i class="fas fa-eye-slash"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="change_password" class="btn-primary">
                                        <i class="fas fa-key"></i> Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Account Actions Card -->
                    <div class="profile-card">
                        <div class="card-header">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Account Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="action-row">
                                <button type="button" class="btn-secondary"
                                    onclick="showToast('Download account statement feature coming soon!')">
                                    <i class="fas fa-download"></i> Statement
                                </button>
                                <button type="button" class="btn-secondary"
                                    onclick="showToast('KYC verification feature coming soon!')">
                                    <i class="fas fa-id-card"></i> KYC
                                </button>
                            </div>
                            <p style="font-size: 0.65rem; color: #94a3b8; margin-top: 12px;">
                                <i class="fas fa-info-circle"></i> For any account-related issues, please contact our
                                support team
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Upload Modal -->
        <div id="uploadModal" class="image-modal">
            <div class="image-modal-content">
                <h3><i class="fas fa-camera"></i> Update Profile Picture</h3>
                <div class="image-preview" id="imagePreview">
                    <?php if (!empty($user['profile_image']) && file_exists('../../uploads/profile/' . $user['profile_image'])): ?>
                        <img src="../../uploads/profile/<?php echo htmlspecialchars($user['profile_image']); ?>"
                            id="previewImg">
                    <?php else: ?>
                        <i class="fas fa-user-circle" id="previewIcon"></i>
                        <img src="" id="previewImg" style="display: none;">
                    <?php endif; ?>
                </div>
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" style="display: none;"
                        onchange="previewImage(this)">
                    <button type="button" class="btn-secondary" style="margin: 8px;"
                        onclick="document.getElementById('profile_image').click()">
                        <i class="fas fa-folder-open"></i> Choose Image
                    </button>
                    <button type="submit" name="upload_image" class="btn-primary" style="margin: 8px;">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                    <button type="button" class="btn-secondary" style="margin: 8px;" onclick="closeUploadModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </form>
                <p style="font-size: 0.6rem; color: #94a3b8; margin-top: 8px;">
                    <i class="fas fa-info-circle"></i> JPG, JPEG, PNG, GIF, WEBP (Max 5MB)
                </p>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div id="toast" class="toast"></div>

    <script>
        // ============================================================
        // LOGOUT FUNCTIONS
        // ============================================================
        function showLogoutDialog() {
            document.getElementById('logoutDialog').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLogoutDialog() {
            document.getElementById('logoutDialog').classList.remove('active');
            document.body.style.overflow = '';
        }

        function confirmLogout() {
            closeLogoutDialog();
            showToast('Logging out...');
            setTimeout(function () {
                window.location.href = '../logout.php';
            }, 500);
        }

        document.getElementById('logoutDialog').addEventListener('click', function (e) {
            if (e.target === this) closeLogoutDialog();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeLogoutDialog();
        });

        // ============================================================
        // TOAST
        // ============================================================
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        // ============================================================
        // TOGGLE PASSWORD VISIBILITY
        // ============================================================
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggleIcon = field.nextElementSibling.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                field.type = 'password';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }

        // ============================================================
        // EDIT MODE TOGGLE
        // ============================================================
        function toggleEditMode() {
            const viewMode = document.getElementById('viewMode');
            const editMode = document.getElementById('editMode');
            const toggleBtn = document.getElementById('editToggleBtn');

            if (viewMode.style.display !== 'none') {
                viewMode.style.display = 'none';
                editMode.classList.add('active');
                toggleBtn.innerHTML = '<i class="fas fa-times"></i> Cancel';
                toggleBtn.style.borderColor = '#ef4444';
                toggleBtn.style.color = '#ef4444';
            } else {
                viewMode.style.display = 'block';
                editMode.classList.remove('active');
                toggleBtn.innerHTML = '<i class="fas fa-edit"></i> Edit';
                toggleBtn.style.borderColor = '';
                toggleBtn.style.color = '';
            }
        }

        // ============================================================
        // IMAGE UPLOAD MODAL
        // ============================================================
        function showUploadModal() {
            document.getElementById('uploadModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewImg = document.getElementById('previewImg');
                    const previewIcon = document.getElementById('previewIcon');
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                    if (previewIcon) previewIcon.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        window.onclick = function (event) {
            if (event.target == document.getElementById('uploadModal')) {
                closeUploadModal();
            }
        };

        // ============================================================
        // FORM VALIDATION FOR PASSWORD CHANGE
        // ============================================================
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (this.querySelector('input[name="change_password"]') || this.querySelector('button[name="change_password"]')) {
                    const newPassword = document.getElementById('new_password');
                    const confirmPassword = document.getElementById('confirm_password');

                    if (newPassword && confirmPassword) {
                        if (newPassword.value !== confirmPassword.value) {
                            e.preventDefault();
                            alert('❌ New password and confirm password do not match!');
                            return false;
                        }
                        if (newPassword.value.length < 6) {
                            e.preventDefault();
                            alert('❌ New password must be at least 6 characters long!');
                            return false;
                        }
                    }
                }
                return true;
            });
        });
    </script>
</body>

</html>