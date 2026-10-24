<?php
// =========================================================
// SESSION is already started in dbConfig.php – no need to start again
// =========================================================
include('../includes/dbConfig.php');

// =========================================================
// AJAX HANDLERS (for dynamic location dropdowns)
// =========================================================
if (isset($_GET['ajax'])) {
    // ---- GET LOCATIONS ----
    if ($_GET['ajax'] == 'get_locations') {
        $state_id = isset($_GET['state_id']) ? $_GET['state_id'] : 0;
        $selected_location = isset($_GET['selected']) ? intval($_GET['selected']) : 0;
        
        if ($state_id > 0) {
            $sql = "SELECT id, location FROM tbl_location WHERE state_id = ? AND status = 1 ORDER BY location";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $state_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            echo '<option value="">Select Location</option>';
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($selected_location == $row['id']) ? 'selected' : '';
                echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['location']) . '</option>';
            }
        } else {
            echo '<option value="">Select Location</option>';
        }
        exit();
    }

    // ---- GET SUB-LOCATIONS ----
    if ($_GET['ajax'] == 'get_sublocations') {
        $state_id = isset($_GET['state_id']) ? $_GET['state_id'] : 0;
        $location_id = isset($_GET['location_id']) ? $_GET['location_id'] : 0;
        $selected_sublocation = isset($_GET['selected']) ? intval($_GET['selected']) : 0;
        
        if ($state_id > 0 && $location_id > 0) {
            $sql = "SELECT id, sub_location FROM tbl_sub_location WHERE state_id = ? AND location_id = ? AND status = 1 ORDER BY sub_location";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $state_id, $location_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            echo '<option value="">Select Sub Location</option>';
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($selected_sublocation == $row['id']) ? 'selected' : '';
                echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['sub_location']) . '</option>';
            }
        } else {
            echo '<option value="">Select Sub Location</option>';
        }
        exit();
    }

    // ---- GET PINCODES ----
    if ($_GET['ajax'] == 'get_pincodes') {
        $state_id = isset($_GET['state_id']) ? $_GET['state_id'] : 0;
        $location_id = isset($_GET['location_id']) ? $_GET['location_id'] : 0;
        $sub_location_id = isset($_GET['sub_location_id']) ? $_GET['sub_location_id'] : 0;
        $selected_pincode = isset($_GET['selected']) ? intval($_GET['selected']) : 0;
        
        if ($state_id > 0 && $location_id > 0 && $sub_location_id > 0) {
            $sql = "SELECT id, pincode FROM tbl_pincode WHERE state_id = ? AND location_id = ? AND sub_location_id = ? AND status = 1 ORDER BY pincode";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $state_id, $location_id, $sub_location_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            echo '<option value="">Select Pincode</option>';
            while ($row = mysqli_fetch_assoc($result)) {
                $selected = ($selected_pincode == $row['id']) ? 'selected' : '';
                echo '<option value="' . $row['id'] . '" ' . $selected . '>' . htmlspecialchars($row['pincode']) . '</option>';
            }
        } else {
            echo '<option value="">Select Pincode</option>';
        }
        exit();
    }
}

// =========================================================
// CHECK LOGIN
// =========================================================
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data (only basic fields from `users`)
$sql = "SELECT id, fullname, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: ../login.php");
    exit;
}

// Extract basic user data
$fullname = $user['fullname'] ?? 'User';
$email    = $user['email'] ?? 'user@email.com';
$phone    = $user['phone'] ?? '+91 98765 43210';
$firstname = explode(' ', trim($fullname))[0] ?? 'User';

// Fetch additional details from loan_requests (if any)
$request_data = [];
$sql_req = "SELECT * FROM loan_requests WHERE user_id = ?";
$stmt_req = $conn->prepare($sql_req);
$stmt_req->bind_param("i", $user_id);
$stmt_req->execute();
$req_result = $stmt_req->get_result();
if ($req_result->num_rows > 0) {
    $request_data = $req_result->fetch_assoc();
}
$stmt_req->close();

// Populate variables with existing data or defaults
$dob          = $request_data['dob'] ?? '';
$gender       = $request_data['gender'] ?? '';
$aadhar       = $request_data['aadhaar_number'] ?? '';
$pan          = $request_data['pan_number'] ?? '';
$income       = $request_data['annual_income'] ?? 0;
$address      = $request_data['address'] ?? '';
$city         = $request_data['city'] ?? '';
$state        = $request_data['state'] ?? '';
$location     = $request_data['location'] ?? '';
$sub_location = $request_data['sub_location'] ?? '';
$pincode      = $request_data['pincode'] ?? '';

// Fetch states for dropdown
$states = [];
$sql_states = "SELECT id, state_name FROM tbl_state WHERE status = 1 ORDER BY state_name";
$result_states = mysqli_query($conn, $sql_states);
if ($result_states) {
    while ($row = mysqli_fetch_assoc($result_states)) {
        $states[] = $row;
    }
}

// =========================================================
// FORM SUBMISSION HANDLER (split data into users & loan_requests)
// =========================================================
$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_form'])) {
    // Sanitize and validate inputs
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $dob      = $_POST['dob'] ?? null;
    $gender   = $_POST['gender'] ?? '';
    $aadhar   = preg_replace('/\s+/', '', trim($_POST['aadhar'] ?? ''));
    $pan      = strtoupper(trim($_POST['pan'] ?? ''));
    $income   = floatval($_POST['income'] ?? 0);
    $address  = trim($_POST['address'] ?? '');
    $city     = trim($_POST['city'] ?? '');
    $state    = $_POST['state'] ?? '';
    $location = $_POST['location'] ?? '';
    $sub_location = $_POST['sub_location'] ?? '';
    $pincode  = $_POST['pincode'] ?? '';

    // Basic validation
    $errors = [];
    if (empty($fullname)) $errors[] = "Full name is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($phone)) $errors[] = "Phone number is required.";
    if (empty($dob)) $errors[] = "Date of birth is required.";
    if (empty($gender)) $errors[] = "Gender is required.";
    if (empty($aadhar) || strlen($aadhar) < 12) $errors[] = "Valid Aadhar number (12 digits) is required.";
    if (empty($pan) || strlen($pan) < 10) $errors[] = "Valid PAN number (10 characters) is required.";
    if ($income <= 0) $errors[] = "Annual income must be greater than 0.";
    if (empty($address)) $errors[] = "Address is required.";

    if (empty($errors)) {
        // Escape values
        $fullname = mysqli_real_escape_string($conn, $fullname);
        $email = mysqli_real_escape_string($conn, $email);
        $phone = mysqli_real_escape_string($conn, $phone);
        $dob = mysqli_real_escape_string($conn, $dob);
        $gender = mysqli_real_escape_string($conn, $gender);
        $aadhar = mysqli_real_escape_string($conn, $aadhar);
        $pan = mysqli_real_escape_string($conn, $pan);
        $income = floatval($income);
        $address = mysqli_real_escape_string($conn, $address);
        $city = mysqli_real_escape_string($conn, $city);
        $state = mysqli_real_escape_string($conn, $state);
        $location = mysqli_real_escape_string($conn, $location);
        $sub_location = mysqli_real_escape_string($conn, $sub_location);
        $pincode = mysqli_real_escape_string($conn, $pincode);

        // ---------- UPDATE `users` (only basic info) ----------
        $update_user_sql = "UPDATE users SET 
            fullname = '$fullname',
            email = '$email',
            phone = '$phone'
            WHERE id = $user_id";
        $user_updated = mysqli_query($conn, $update_user_sql);

        if ($user_updated) {
            // ---------- UPDATE/INSERT `loan_requests` (all other fields) ----------
            // Check if a record already exists for this user
            $check_sql = "SELECT id FROM loan_requests WHERE user_id = $user_id";
            $check_result = mysqli_query($conn, $check_sql);

            if ($check_result && mysqli_num_rows($check_result) > 0) {
                // Update existing record
                $row = mysqli_fetch_assoc($check_result);
                $request_id = $row['id'];
                $update_request_sql = "UPDATE loan_requests SET
                    address = '$address',
                    city = '$city',
                    state = '$state',
                    location = '$location',
                    sub_location = '$sub_location',
                    pincode = '$pincode',
                    aadhaar_number = '$aadhar',
                    pan_number = '$pan',
                    annual_income = '$income',
                    dob = '$dob',
                    gender = '$gender'
                    WHERE id = $request_id";
                mysqli_query($conn, $update_request_sql);
            } else {
                // Insert new record
                $insert_request_sql = "INSERT INTO loan_requests (
                    user_id, address, city, state, location, sub_location,
                    pincode, aadhaar_number, pan_number, annual_income,
                    dob, gender, application_status, source
                ) VALUES (
                    '$user_id', '$address', '$city', '$state', '$location', '$sub_location',
                    '$pincode', '$aadhar', '$pan', '$income',
                    '$dob', '$gender', 'Pending', 'Website'
                )";
                mysqli_query($conn, $insert_request_sql);
            }

            // Update session with new name/email/phone
            $_SESSION['fullname'] = $fullname;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['profile_completed'] = true;

            $message = "Profile updated successfully!";
            $message_type = "success";
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "Database error (users): " . mysqli_error($conn);
            $message_type = "error";
        }
    } else {
        $message = implode("<br>", $errors);
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Complete Profile - Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* (same as before – no changes) */
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; min-height: 100vh; padding: 16px; display: flex; flex-direction: column; align-items: center; }
        .container { max-width: 480px; width: 100%; margin: 0 auto; }
        .app-header { background: linear-gradient(135deg, #1a237e, #283593); padding: 24px 20px 30px; border-radius: 16px 16px 24px 24px; margin-bottom: 20px; position: relative; overflow: hidden; text-align: center; }
        .app-header::before { content: ''; position: absolute; top: -50%; right: -20%; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; }
        .app-header .logo { font-size: 20px; font-weight: 800; color: #fff; position: relative; z-index: 2; }
        .app-header .logo span { color: #ff9800; }
        .app-header .greeting { color: rgba(255,255,255,0.85); font-size: 14px; margin-top: 6px; position: relative; z-index: 2; }
        .app-header .greeting strong { color: #fff; font-weight: 700; }
        .app-header .step-text { color: rgba(255,255,255,0.6); font-size: 11px; margin-top: 10px; display: flex; align-items: center; justify-content: center; gap: 8px; position: relative; z-index: 2; }
        .app-header .step-text .step-num { background: rgba(255,255,255,0.15); padding: 2px 10px; border-radius: 12px; font-weight: 600; color: #ff9800; }
        .app-header .step-text .step-num i { color: #ff9800; margin-right: 6px; }
        .progress-wrap { background: #fff; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .progress-wrap .steps { display: flex; justify-content: space-between; align-items: center; position: relative; }
        .progress-wrap .steps::before { content: ''; position: absolute; top: 50%; left: 15%; right: 15%; height: 2px; background: #e8e8e8; transform: translateY(-50%); z-index: 0; }
        .progress-wrap .steps .step { display: flex; flex-direction: column; align-items: center; gap: 4px; position: relative; z-index: 1; }
        .progress-wrap .steps .step .dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; background: #e8e8e8; color: #999; transition: all 0.3s; }
        .progress-wrap .steps .step.active .dot { background: #1a237e; color: #fff; box-shadow: 0 4px 12px rgba(26, 35, 126, 0.3); }
        .progress-wrap .steps .step.done .dot { background: #4caf50; color: #fff; }
        .progress-wrap .steps .step .label { font-size: 9px; font-weight: 600; color: #999; text-transform: uppercase; letter-spacing: 0.5px; }
        .progress-wrap .steps .step.active .label { color: #1a237e; }
        .progress-wrap .steps .step.done .label { color: #4caf50; }
        .card { background: #fff; border-radius: 16px; padding: 24px 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); margin-bottom: 16px; }
        .card-title { font-size: 18px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .card-subtitle { font-size: 13px; color: #999; margin-bottom: 20px; }
        .card-subtitle i { color: #ff9800; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px; }
        .form-group label i { color: #ff9800; margin-right: 5px; width: 16px; }
        .form-group label .required { color: #f44336; }
        .form-group input, .form-group select { width: 100%; padding: 12px 14px; border: 2px solid #eef2f6; border-radius: 10px; font-family: 'Inter', sans-serif; font-size: 14px; transition: all 0.2s; outline: none; background: #fafbfc; color: #1a1a2e; }
        .form-group input:focus, .form-group select:focus { border-color: #ff9800; background: #fff; box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.08); }
        .form-group input::placeholder { color: #bbb; }
        .form-group select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23999' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 40px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .btn-primary { width: 100%; padding: 14px; background: linear-gradient(135deg, #ff9800, #f57c00); color: #fff; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px; font-family: 'Inter', sans-serif; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255, 152, 0, 0.35); }
        .btn-primary:active { transform: scale(0.97); }
        .btn-skip { width: 100%; padding: 12px; background: #f0f0f0; color: #666; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; font-family: 'Inter', sans-serif; margin-top: 10px; }
        .btn-skip:hover { background: #e0e0e0; }
        .info-box { background: #fff8e1; border-radius: 12px; padding: 14px 16px; display: flex; gap: 12px; align-items: flex-start; border-left: 4px solid #ff9800; margin-top: 16px; }
        .info-box i { color: #ff9800; font-size: 18px; margin-top: 1px; }
        .info-box .text { font-size: 12px; color: #795548; line-height: 1.5; }
        .info-box .text strong { color: #4e342e; }
        .footer { text-align: center; font-size: 11px; color: #999; padding: 16px; }
        .footer i { color: #4caf50; }
        .message-box { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 14px; font-weight: 500; }
        .message-box.success { background: #e8f5e9; color: #2e7d32; border-left: 4px solid #4caf50; }
        .message-box.error { background: #ffebee; color: #c62828; border-left: 4px solid #f44336; }
        @media (max-width: 480px) {
            body { padding: 12px; }
            .card { padding: 20px 16px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
            .app-header { padding: 20px 16px 24px; }
            .progress-wrap { padding: 12px 16px; }
            .progress-wrap .steps .step .dot { width: 28px; height: 28px; font-size: 10px; }
            .progress-wrap .steps .step .label { font-size: 8px; }
            .btn-primary { font-size: 14px; padding: 12px; }
        }
        @media (min-width: 481px) and (max-width: 768px) { .container { max-width: 400px; } }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="app-header">
        <div class="logo">Login To <span>Loans</span></div>
        <div class="greeting">👋 Welcome, <strong><?php echo htmlspecialchars($firstname); ?></strong> Check Your CIBIL Score</div>
        <div class="step-text">
            <span class="step-num">Step 2 of 3</span>
            <span>Complete your profile</span>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="progress-wrap">
        <div class="steps">
            <div class="step done">
                <div class="dot"><i class="fas fa-check" style="font-size:10px;"></i></div>
                <span class="label">Login</span>
            </div>
            <div class="step active">
                <div class="dot">2</div>
                <span class="label">Profile</span>
            </div>
            <div class="step">
                <div class="dot">3</div>
                <span class="label">Dashboard</span>
            </div>
        </div>
    </div>

    <!-- Message display -->
    <?php if (!empty($message)): ?>
        <div class="message-box <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="card">
        <div class="card-title">📋 Complete Your Profile</div>
        <div class="card-subtitle"><i class="fas fa-info-circle"></i> Please provide your details to continue</div>

        <form method="POST" action="">
            <input type="hidden" name="submit_form" value="1">

            <!-- ========== Basic Info (users table) ========== -->
            <h4 style="font-size:14px; color:#666; margin:16px 0 8px; border-bottom:1px solid #eee; padding-bottom:6px;">
                <i class="fas fa-user-circle"></i> Personal Information
            </h4>

            <div class="form-group">
                <label><i class="fas fa-user"></i> Full Name <span class="required">*</span></label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email Address <span class="required">*</span></label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="your@email.com">
            </div>

            <div class="form-group">
                <label><i class="fas fa-phone"></i> Phone Number <span class="required">*</span></label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required placeholder="+91 98765 43210">
            </div>

            <!-- ========== Additional Details (loan_requests) ========== -->
            <h4 style="font-size:14px; color:#666; margin:20px 0 8px; border-bottom:1px solid #eee; padding-bottom:6px;">
                <i class="fas fa-address-card"></i> Additional Details
            </h4>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-calendar-alt"></i> Date of Birth <span class="required">*</span></label>
                    <input type="date" name="dob" value="<?php echo $dob; ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-venus-mars"></i> Gender <span class="required">*</span></label>
                    <select name="gender" required>
                        <option value="">Select</option>
                        <option value="male" <?php echo ($gender == 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($gender == 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo ($gender == 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-id-card"></i> Aadhar Number <span class="required">*</span></label>
                <input type="text" name="aadhar" value="<?php echo htmlspecialchars($aadhar); ?>" placeholder="XXXX XXXX XXXX" required maxlength="14">
            </div>

            <div class="form-group">
                <label><i class="fas fa-file-invoice"></i> PAN Number <span class="required">*</span></label>
                <input type="text" name="pan" value="<?php echo htmlspecialchars($pan); ?>" placeholder="ABCDE1234F" required maxlength="10">
            </div>

            <div class="form-group">
                <label><i class="fas fa-money-bill-wave"></i> Annual Income (₹) <span class="required">*</span></label>
                <input type="number" name="income" value="<?php echo $income; ?>" placeholder="Enter your annual income" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-home"></i> Address <span class="required">*</span></label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="Enter your address" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-city"></i> City <span class="required">*</span></label>
                <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" placeholder="City" required>
            </div>

            <!-- ====== ADDRESS DROPDOWNS (State, Location, Sub‑Location, Pincode) ====== -->
            <div class="form-group">
                <label><i class="fas fa-map"></i> State <span class="required">*</span></label>
                <select name="state" id="state" required>
                    <option value="">Select State</option>
                    <?php foreach ($states as $row): ?>
                        <option value="<?= $row['id']; ?>" <?= ($state == $row['id']) ? 'selected' : ''; ?>><?= htmlspecialchars($row['state_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-map-pin"></i> Location <span class="required">*</span></label>
                <select name="location" id="location" required>
                    <option value="">Select Location</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Sub Location <span class="required">*</span></label>
                <select name="sub_location" id="sub_location" required>
                    <option value="">Select Sub Location</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-mail-bulk"></i> Pincode <span class="required">*</span></label>
                <select name="pincode" id="pincode" required>
                    <option value="">Select Pincode</option>
                </select>
            </div>

            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Save & Continue
            </button>
            <button type="button" class="btn-skip" onclick="window.location.href='dashboard.php'">
                Skip for now
            </button>
        </form>

        <div class="info-box">
            <i class="fas fa-shield-alt"></i>
            <div class="text">
                <strong>Your data is secure.</strong> We use 256-bit encryption and never share your information without consent.
            </div>
        </div>
    </div>

    <div class="footer">
        <i class="fas fa-lock"></i> Secured &bull; LoginToLoans &bull; v2.0
    </div>
</div>

<script>
    // Aadhar auto-format
    document.querySelector('input[name="aadhar"]')?.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 12) value = value.slice(0, 12);
        let formatted = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) formatted += ' ';
            formatted += value[i];
        }
        this.value = formatted;
    });

    // PAN uppercase
    document.querySelector('input[name="pan"]')?.addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });

    // ------ AJAX for location dropdowns (FIXED) ------
    document.addEventListener('DOMContentLoaded', function() {
        const currentUrl = window.location.href;
        const baseUrl = currentUrl.split('?')[0];
        const hasQuery = currentUrl.indexOf('?') !== -1;
        const separator = hasQuery ? '&' : '?';

        const stateSelect = document.getElementById('state');
        const locationSelect = document.getElementById('location');
        const subLocationSelect = document.getElementById('sub_location');
        const pincodeSelect = document.getElementById('pincode');

        // PHP saved values
        const savedState = '<?= $state ?>';
        const savedLocation = '<?= $location ?>';
        const savedSubLocation = '<?= $sub_location ?>';
        const savedPincode = '<?= $pincode ?>';

        function updateSelect(selectElement, htmlContent) {
            selectElement.innerHTML = htmlContent;
        }

        function loadLocations(stateId, selectedLocation = null) {
            if (!stateId) {
                updateSelect(locationSelect, '<option value="">Select Location</option>');
                updateSelect(subLocationSelect, '<option value="">Select Sub Location</option>');
                updateSelect(pincodeSelect, '<option value="">Select Pincode</option>');
                return;
            }
            let url = baseUrl + separator + 'ajax=get_locations&state_id=' + stateId;
            if (selectedLocation) url += '&selected=' + selectedLocation;
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    updateSelect(locationSelect, data);
                    if (selectedLocation && locationSelect.querySelector('option[value="' + selectedLocation + '"]')) {
                        locationSelect.value = selectedLocation;
                        loadSubLocations(stateId, selectedLocation, savedSubLocation);
                    } else if (savedLocation && locationSelect.querySelector('option[value="' + savedLocation + '"]')) {
                        locationSelect.value = savedLocation;
                        loadSubLocations(stateId, savedLocation, savedSubLocation);
                    }
                })
                .catch(err => console.error('Error loading locations:', err));
        }

        function loadSubLocations(stateId, locationId, selectedSubLocation = null) {
            if (!stateId || !locationId) {
                updateSelect(subLocationSelect, '<option value="">Select Sub Location</option>');
                updateSelect(pincodeSelect, '<option value="">Select Pincode</option>');
                return;
            }
            let url = baseUrl + separator + 'ajax=get_sublocations&state_id=' + stateId + '&location_id=' + locationId;
            if (selectedSubLocation) url += '&selected=' + selectedSubLocation;
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    updateSelect(subLocationSelect, data);
                    if (selectedSubLocation && subLocationSelect.querySelector('option[value="' + selectedSubLocation + '"]')) {
                        subLocationSelect.value = selectedSubLocation;
                        loadPincodes(stateId, locationId, selectedSubLocation, savedPincode);
                    } else if (savedSubLocation && subLocationSelect.querySelector('option[value="' + savedSubLocation + '"]')) {
                        subLocationSelect.value = savedSubLocation;
                        loadPincodes(stateId, locationId, savedSubLocation, savedPincode);
                    }
                })
                .catch(err => console.error('Error loading sublocations:', err));
        }

        function loadPincodes(stateId, locationId, subLocationId, selectedPincode = null) {
            if (!stateId || !locationId || !subLocationId) {
                updateSelect(pincodeSelect, '<option value="">Select Pincode</option>');
                return;
            }
            let url = baseUrl + separator + 'ajax=get_pincodes&state_id=' + stateId + '&location_id=' + locationId + '&sub_location_id=' + subLocationId;
            if (selectedPincode) url += '&selected=' + selectedPincode;
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    updateSelect(pincodeSelect, data);
                    if (selectedPincode && pincodeSelect.querySelector('option[value="' + selectedPincode + '"]')) {
                        pincodeSelect.value = selectedPincode;
                    } else if (savedPincode && pincodeSelect.querySelector('option[value="' + savedPincode + '"]')) {
                        pincodeSelect.value = savedPincode;
                    }
                })
                .catch(err => console.error('Error loading pincodes:', err));
        }

        // Event listeners
        stateSelect.addEventListener('change', function() {
            loadLocations(this.value);
        });

        locationSelect.addEventListener('change', function() {
            const stateId = stateSelect.value;
            const locationId = this.value;
            if (stateId && locationId) {
                loadSubLocations(stateId, locationId);
            } else {
                updateSelect(subLocationSelect, '<option value="">Select Sub Location</option>');
                updateSelect(pincodeSelect, '<option value="">Select Pincode</option>');
            }
        });

        subLocationSelect.addEventListener('change', function() {
            const stateId = stateSelect.value;
            const locationId = locationSelect.value;
            const subLocationId = this.value;
            if (stateId && locationId && subLocationId) {
                loadPincodes(stateId, locationId, subLocationId);
            } else {
                updateSelect(pincodeSelect, '<option value="">Select Pincode</option>');
            }
        });

        // Initial load: if a state is saved, load its locations
        if (savedState) {
            stateSelect.value = savedState;
            loadLocations(savedState, savedLocation);
        }
    });
</script>

</body>
</html>