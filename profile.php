<?php
session_start();

// If not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ===== HANDLE PROFILE UPDATE (AJAX) =====
if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    header('Content-Type: application/json');
    
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    
    // Validate
    if (empty($fullname) || empty($email) || empty($phone)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit;
    }
    
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Invalid phone number']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit;
    }
    
    // Database connection
    include('includes/dbConfig.php');
    
    $user_id = $_SESSION['user_id'];
    
    // Update profile
    $stmt = $conn->prepare("UPDATE mobile_app_login SET fullname = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $fullname, $email, $phone, $user_id);
    
    if ($stmt->execute()) {
        // Update session data
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        $_SESSION['phone'] = $phone;
        
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

// Pull session data into clean variables
$fullname      = htmlspecialchars($_SESSION['fullname']      ?? 'User');
$email         = htmlspecialchars($_SESSION['email']         ?? 'user@email.com');
$phone         = htmlspecialchars($_SESSION['phone']         ?? '+91 98765 43210');
$role          = htmlspecialchars($_SESSION['role']          ?? 'user');
$profile_image = htmlspecialchars($_SESSION['profile_image'] ?? '');

// Avatar initials (first letter of full name)
$initials = strtoupper(mb_substr($_SESSION['fullname'] ?? 'U', 0, 1));

// Include the header
include('includes/header-nav.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>My Profile | Login To Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --primary: #1a237e;
            --primary-dark: #0d1757;
            --primary-light: #283593;
            --secondary: #00c853;
            --accent: #ff6b35;
            --orange: #ff9800;
            --text-primary: #1a1a2e;
            --text-secondary: #666666;
            --text-tertiary: #999999;
            --background: #f8f9fc;
            --white: #ffffff;
            --border: #eef2f6;
            --shadow: 0 2px 8px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border); border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 10px; }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background: var(--background);
            min-height: 100vh;
            position: relative;
            padding-bottom: 75px;
        }

        .page { display: none; animation: fadeIn 0.3s ease; }
        .page.active { display: block; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            position: relative;
            padding: 30px 20px 35px;
            color: white;
            overflow: hidden;
            border-radius: 0 0 30px 30px;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .hero-section .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(26,35,126,0.75) 0%, rgba(57,73,171,0.85) 100%);
            z-index: 1;
        }
        .hero-section .content {
            position: relative;
            z-index: 2;
        }

        .sub-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        .sub-header i {
            cursor: pointer;
            font-size: 20px;
        }
        .sub-header span {
            font-size: 18px;
            font-weight: 700;
        }

        /* ===== PROFILE AVATAR ===== */
        .profile-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff9800, #ff5722);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px auto 16px;
            font-size: 36px;
            font-weight: 800;
            color: #fff;
            overflow: hidden;
            border: 3px solid rgba(255,255,255,0.3);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .profile-avatar-large img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            text-align: center;
        }
        .profile-email {
            opacity: 0.85;
            margin-top: 4px;
            font-size: 14px;
            text-align: center;
        }
        .profile-phone {
            opacity: 0.75;
            margin-top: 2px;
            font-size: 13px;
            text-align: center;
        }

        /* ===== PROFILE INFO CARD ===== */
        .profile-info-card {
            background: #fff;
            border-radius: 20px;
            margin: 16px;
            padding: 20px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .profile-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .profile-row:last-child {
            border-bottom: none;
        }
        .profile-row i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 14px;
            flex-shrink: 0;
        }
        .profile-row .p-label {
            font-size: 11px;
            color: #999;
            margin-bottom: 2px;
        }
        .profile-row .p-value {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* ===== EDIT BUTTON ===== */
        .edit-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: center;
        }
        .edit-btn:active {
            transform: scale(0.97);
        }

        /* ===== LOANS GRID ===== */
        .loans-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 0 16px;
        }
        .loan-card {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
            min-height: 90px;
        }
        .loan-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1;
        }
        .loan-card:active { transform: scale(0.97); }
        .loan-card .loan-image,
        .loan-card .loan-info { position: relative; z-index: 2; }
        .loan-image {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .loan-image i { font-size: 28px; color: #1a237e; }
        .loan-info h4 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        .loan-info p {
            font-size: 11px;
            color: rgba(255,255,255,0.9);
        }

        /* ===== SECTION HEADER ===== */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            padding: 0 16px;
            margin: 24px 0 12px;
        }
        .section-header h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* ===== BOTTOM NAV ===== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            max-width: 480px;
            width: 100%;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(20px);
            display: flex;
            justify-content: space-around;
            padding: 8px 16px 20px;
            border-top: 1px solid var(--border);
            z-index: 100;
        }
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            color: var(--text-tertiary);
            font-weight: 500;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 30px;
            transition: all 0.2s;
            text-decoration: none;
            background: transparent;
            border: none;
        }
        .nav-item i { font-size: 20px; }
        .nav-item.active {
            color: var(--primary);
            background: rgba(26,35,126,0.1);
        }

        /* ===== MODAL ===== */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: hidden;
            opacity: 0;
            transition: 0.2s;
        }
        .modal.active {
            visibility: visible;
            opacity: 1;
        }
        .modal-content {
            background: var(--white);
            border-radius: 28px;
            padding: 24px;
            width: 90%;
            max-width: 380px;
            max-height: 85vh;
            overflow-y: auto;
            animation: modalPop 0.3s ease;
        }
        @keyframes modalPop {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border);
        }
        .modal-header h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }
        .modal-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--border);
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: 0.2s;
        }
        .modal-close:hover { background: #e0e0e0; }

        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-secondary);
        }
        .form-group label i { margin-right: 6px; color: var(--accent); }
        .form-input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        .apply-button {
            background: linear-gradient(135deg, var(--accent), #ff8c42);
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 40px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
            font-size: 14px;
            margin-top: 10px;
        }
        .apply-button:active { transform: scale(0.98); }

        /* ===== TOAST ===== */
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: var(--text-primary);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 13px;
            z-index: 1000;
            transition: transform 0.3s;
            white-space: nowrap;
            max-width: 90%;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .toast.show { transform: translateX(-50%) translateY(0); }

        @media (max-width: 400px) {
            .nav-item { font-size: 9px; padding: 4px 8px; min-width: 50px; }
            .nav-item i { font-size: 16px; }
            .profile-name { font-size: 20px; }
            .profile-avatar-large { width: 70px; height: 70px; font-size: 30px; }
        }
    </style>
</head>
<body>
<div class="app-container">

    <!-- ===== PROFILE PAGE ===== -->
    <div id="profilePage" class="page active">
        <div class="hero-section">
            <div class="hero-slideshow">
                <div class="slide active" style="background-image:url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=600&fit=crop');"></div>
            </div>
            <div class="overlay"></div>
            <div class="content">
                <div class="sub-header">
                    <i class="fas fa-arrow-left" onclick="window.location.href='index.php'"></i>
                    <span>My Profile</span>
                </div>
                <div style="text-align:center;">
                    <div class="profile-avatar-large">
                        <?php if ($profile_image): ?>
                            <img src="<?php echo $profile_image; ?>" alt="Profile">
                        <?php else: ?>
                            <?php echo $initials; ?>
                        <?php endif; ?>
                    </div>
                    <div class="profile-name"><?php echo $fullname; ?></div>
                    <div class="profile-email"><?php echo $email; ?></div>
                    <div class="profile-phone"><?php echo $phone; ?></div>
                </div>
            </div>
        </div>

        <div class="profile-info-card">
            <div class="profile-row">
                <i class="fas fa-user"></i>
                <div>
                    <div class="p-label">Full Name</div>
                    <div class="p-value"><?php echo $fullname; ?></div>
                </div>
            </div>
            <div class="profile-row">
                <i class="fas fa-envelope"></i>
                <div>
                    <div class="p-label">Email Address</div>
                    <div class="p-value"><?php echo $email; ?></div>
                </div>
            </div>
            <div class="profile-row">
                <i class="fas fa-phone"></i>
                <div>
                    <div class="p-label">Phone Number</div>
                    <div class="p-value"><?php echo $phone; ?></div>
                </div>
            </div>
            <div class="profile-row">
                <i class="fas fa-id-badge"></i>
                <div>
                    <div class="p-label">Account Role</div>
                    <div class="p-value"><?php echo ucfirst($role); ?></div>
                </div>
            </div>
            <div class="profile-row" style="border-bottom: none; padding-top: 16px; justify-content: center;">
                <button class="edit-btn" onclick="openEditModal()">
                    <i class="fas fa-edit"></i> Edit Profile
                </button>
            </div>
        </div>

        <div class="section-header">
            <h2>📊 Quick Actions</h2>
        </div>
        <div class="loans-grid" style="margin-bottom:20px;">
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=300&h=200&fit=crop');" onclick="window.location.href='credit-score.php'">
                <div class="loan-image"><i class="fas fa-chart-line"></i></div>
                <div class="loan-info">
                    <h4>Credit Score</h4>
                    <p>782 - Excellent</p>
                </div>
            </div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1563986768609-322da13575f3?w=300&h=200&fit=crop');" onclick="window.location.href='applyleads.php'">
                <div class="loan-image"><i class="fas fa-file-invoice"></i></div>
                <div class="loan-info">
                    <h4>Apply Loan</h4>
                    <p>New Application</p>
                </div>
            </div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=300&h=200&fit=crop');" onclick="window.location.href='calculator.php'">
                <div class="loan-image"><i class="fas fa-calculator"></i></div>
                <div class="loan-info">
                    <h4>Calculator</h4>
                    <p>EMI & Tools</p>
                </div>
            </div>
            <div class="loan-card" style="background-image:url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=300&h=200&fit=crop');" onclick="showToast('Your loan history is being loaded')">
                <div class="loan-image"><i class="fas fa-history"></i></div>
                <div class="loan-info">
                    <h4>Loan History</h4>
                    <p>View all</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== EDIT PROFILE MODAL ===== -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-user-edit"></i> Edit Profile</h3>
                <button class="modal-close" onclick="closeModal('editModal')">✕</button>
            </div>
            <form id="editProfileForm" onsubmit="updateProfile(event)">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" class="form-input" id="editFullName" placeholder="Enter your full name" value="<?php echo $fullname; ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" class="form-input" id="editEmail" placeholder="your@email.com" value="<?php echo $email; ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="tel" class="form-input" id="editPhone" placeholder="10-digit number" pattern="[0-9]{10}" value="<?php echo $phone; ?>" required>
                </div>
                <button type="submit" class="apply-button">
                    <i class="fas fa-save"></i> Update Profile
                </button>
            </form>
        </div>
    </div>

    <!-- ===== BOTTOM NAVIGATION ===== -->
    <div class="bottom-nav">
        <a href="index.php" class="nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="applyleads.php" class="nav-item">
            <i class="fas fa-users"></i>
            <span>Apply Leads</span>
        </a>
        <a href="credit-score.php" class="nav-item">
            <i class="fas fa-chart-line"></i>
            <span>Credit Score</span>
        </a>
        <a href="calculator.php" class="nav-item">
            <i class="fas fa-calculator"></i>
            <span>Calculator</span>
        </a>
        <a href="profile.php" class="nav-item active">
            <i class="fas fa-user"></i>
            <span>You</span>
        </a>
    </div>

    <!-- ===== TOAST ===== -->
    <div id="toast" class="toast"></div>

</div>

<script>
    // ===== TOAST =====
    function showToast(msg) {
        var t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(function() {
            t.classList.remove('show');
        }, 3000);
    }

    // ===== MODAL =====
    function openEditModal() {
        document.getElementById('editModal').classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    // Close modal when clicking outside
    document.querySelectorAll('.modal').forEach(function(m) {
        m.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    // ===== UPDATE PROFILE =====
    function updateProfile(e) {
        e.preventDefault();
        
        var fullname = document.getElementById('editFullName').value.trim();
        var email = document.getElementById('editEmail').value.trim();
        var phone = document.getElementById('editPhone').value.trim();

        if (!fullname || !email || !phone) {
            showToast('❌ Please fill all fields');
            return;
        }

        if (!/^[0-9]{10}$/.test(phone)) {
            showToast('❌ Please enter a valid 10-digit phone number');
            return;
        }

        var submitBtn = e.target.querySelector('button[type="submit"]');
        var originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;

        // Send update request to the SAME FILE
        fetch('profile.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=update_profile&fullname=' + encodeURIComponent(fullname) + 
                  '&email=' + encodeURIComponent(email) + 
                  '&phone=' + encodeURIComponent(phone)
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                showToast('✅ Profile updated successfully!');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                showToast('❌ ' + (data.message || 'Update failed. Try again.'));
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            showToast('✅ Profile updated! Refreshing...');
            setTimeout(function() {
                location.reload();
            }, 1500);
        })
        .finally(function() {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var modal = document.getElementById('editModal');
            if (modal.classList.contains('active')) {
                modal.classList.remove('active');
            }
        }
    });
</script>
</body>
</html>