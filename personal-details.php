<?php
/**
 * personal-details.php
 * -----------------------------------------------------------
 * Shown right after login (before the dashboard) so the user
 * can fill in / confirm their personal details.
 *
 * ASSUMPTIONS (adjust to match your real schema):
 *   Table: users
 *   Columns expected/added:
 *     address           VARCHAR(255)  NULL
 *     dob               DATE          NULL
 *     gender            VARCHAR(20)   NULL
 *     occupation        VARCHAR(100)  NULL
 *     monthly_income    DECIMAL(12,2) NULL
 *     profile_completed TINYINT(1)    NOT NULL DEFAULT 0
 *
 * Suggested migration if these columns don't exist yet:
 *
 *   ALTER TABLE users
 *     ADD COLUMN address VARCHAR(255) NULL,
 *     ADD COLUMN dob DATE NULL,
 *     ADD COLUMN gender VARCHAR(20) NULL,
 *     ADD COLUMN occupation VARCHAR(100) NULL,
 *     ADD COLUMN monthly_income DECIMAL(12,2) NULL,
 *     ADD COLUMN profile_completed TINYINT(1) NOT NULL DEFAULT 0;
 * -----------------------------------------------------------
 */

include('includes/dbConfig.php'); // adjust path if this file lives inside /user/

// ── Must be logged in ──
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error   = "";
$success = "";

// ── Handle form submission ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname       = mysqli_real_escape_string($conn, trim($_POST['fullname'] ?? ''));
    $phone          = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));
    $address        = mysqli_real_escape_string($conn, trim($_POST['address'] ?? ''));
    $dob            = mysqli_real_escape_string($conn, trim($_POST['dob'] ?? ''));
    $gender         = mysqli_real_escape_string($conn, trim($_POST['gender'] ?? ''));
    $occupation     = mysqli_real_escape_string($conn, trim($_POST['occupation'] ?? ''));
    $monthly_income = mysqli_real_escape_string($conn, trim($_POST['monthly_income'] ?? ''));

    if ($fullname === '' || $phone === '') {
        $error = "Full name and phone number are required.";
    } else {
        $dobValue = $dob !== '' ? "'$dob'" : "NULL";
        $incomeValue = $monthly_income !== '' ? "'$monthly_income'" : "NULL";

        $update = mysqli_query($conn, "
            UPDATE users SET
                fullname = '$fullname',
                phone = '$phone',
                address = '$address',
                dob = $dobValue,
                gender = '$gender',
                occupation = '$occupation',
                monthly_income = $incomeValue,
                profile_completed = 1
            WHERE id = '$user_id'
        ");

        if ($update) {
            // Keep session in sync
            $_SESSION['fullname'] = $fullname;
            $_SESSION['phone']    = $phone;

            header("Location: user/dashboard.php");
            exit;
        } else {
            $error = "Could not save your details. Please try again.";
        }
    }
}

// ── Pre-fill existing values ──
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user   = $result ? mysqli_fetch_assoc($result) : [];

function val($user, $key) {
    return htmlspecialchars($user[$key] ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover"/>
    <title>Personal Details · Login To Loans</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --navy:      #0d1b40;
            --navy-mid:  #132254;
            --navy-dark: #080f27;
            --orange:    #f47820;
            --orange-lt: #ff9a45;
            --orange-dk: #d45f00;
            --white:     #ffffff;
            --off-white: #f5f7fa;
            --text-muted:#8fa0be;
            --border:    #dce3ef;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { min-height: 100%; font-family: 'Outfit', sans-serif; }
        body {
            background: var(--off-white);
            display: flex;
            flex-direction: column;
        }
        .header-band {
            background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-mid) 60%, #1a3a7a 100%);
            padding: 2.4rem 1.5rem 2.6rem;
            text-align: center;
            border-bottom: 3px solid var(--orange);
        }
        .header-band h1 {
            color: var(--white);
            font-size: 1.4rem;
            font-weight: 800;
        }
        .header-band h1 span { color: var(--orange); }
        .header-band p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 6px;
        }
        .form-wrap {
            flex: 1;
            padding: 2rem 1.2rem 3rem;
            display: flex;
            justify-content: center;
        }
        .form-card {
            background: var(--white);
            width: 100%;
            max-width: 480px;
            padding: 1.8rem;
            border-radius: 4px;
            box-shadow: 0 6px 24px rgba(13,27,64,0.08);
        }
        .step-note {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--orange-dk);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 1.4rem;
        }
        .error, .success {
            padding: 11px 16px;
            font-size: 0.8rem;
            margin-bottom: 18px;
            font-weight: 600;
            border-left: 4px solid;
        }
        .error { background: #fff1ee; color: #b83300; border-color: var(--orange-dk); }
        .success { background: #eafff1; color: #16743a; border-color: #16a34a; }

        .field { margin-bottom: 1.1rem; }
        .field label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--navy);
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .field input, .field select {
            width: 100%;
            padding: 12px 14px;
            font-size: 0.92rem;
            font-family: 'Outfit', sans-serif;
            border: 2px solid var(--border);
            background: var(--white);
            color: var(--navy);
            outline: none;
            transition: border-color 0.2s;
        }
        .field input:focus, .field select:focus { border-color: var(--orange); }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        .btn-save {
            width: 100%;
            padding: 15px 12px;
            border: none;
            font-size: 0.92rem;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            background: linear-gradient(90deg, var(--orange-dk) 0%, var(--orange) 50%, var(--orange-lt) 100%);
            color: var(--white);
            cursor: pointer;
            margin-top: 0.6rem;
        }
        .skip-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.82rem;
            color: var(--text-muted);
            text-decoration: none;
        }
        @media (max-width: 420px) {
            .two-col { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="header-band">
        <h1>Complete Your <span>Profile</span></h1>
        <p>Just a few details before we take you to your dashboard</p>
    </div>

    <div class="form-wrap">
        <div class="form-card">
            <div class="step-note"><i class="fas fa-user-edit"></i> Step 1 of 1 &middot; Personal Details</div>

            <?php if ($error): ?>
                <div class="error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="personal-details.php" novalidate>

                <div class="field">
                    <label>Full Name</label>
                    <input type="text" name="fullname" required value="<?php echo val($user,'fullname'); ?>">
                </div>

                <div class="field">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" required value="<?php echo val($user,'phone'); ?>">
                </div>

                <div class="field">
                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo val($user,'address'); ?>">
                </div>

                <div class="two-col">
                    <div class="field">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?php echo val($user,'dob'); ?>">
                    </div>
                    <div class="field">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select</option>
                            <option value="Male" <?php echo (($user['gender'] ?? '')==='Male')?'selected':''; ?>>Male</option>
                            <option value="Female" <?php echo (($user['gender'] ?? '')==='Female')?'selected':''; ?>>Female</option>
                            <option value="Other" <?php echo (($user['gender'] ?? '')==='Other')?'selected':''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label>Occupation</label>
                    <input type="text" name="occupation" placeholder="e.g. Salaried, Self-employed" value="<?php echo val($user,'occupation'); ?>">
                </div>

                <div class="field">
                    <label>Monthly Income (₹)</label>
                    <input type="number" name="monthly_income" value="<?php echo val($user,'monthly_income'); ?>">
                </div>

                <button type="submit" class="btn-save"><i class="fas fa-check"></i> Save & Continue</button>
            </form>

            <a href="user/dashboard.php" class="skip-link">Skip for now →</a>
        </div>
    </div>

</body>
</html>