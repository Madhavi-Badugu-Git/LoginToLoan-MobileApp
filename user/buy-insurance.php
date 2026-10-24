<?php
require_once '../includes/dbConfig.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$plan_id = isset($_GET['plan_id']) ? intval($_GET['plan_id']) : 0;
$plan = getInsurancePlanById($plan_id);
$plan_data = $plan->fetch_assoc();

if (!$plan_data || $plan_data['status'] != 1) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $purchase_id = purchaseInsurance($_SESSION['user_id'], $plan_id, $_POST['nominee_name'], $_POST['nominee_relation'], $_POST['payment_method']);
    
    if ($purchase_id) {
        $_SESSION['success_message'] = "Insurance purchased successfully! Policy Number: POL" . time();
        redirect('user/my-insurance.php');
    } else {
        $error = "Failed to purchase insurance. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Insurance - <?php echo htmlspecialchars($plan_data['plan_name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%); }
        .buy-container { max-width: 1200px; margin: 100px auto 40px; padding: 0 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .plan-details, .purchase-form { background: white; border-radius: 24px; padding: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
        .plan-details h2, .purchase-form h2 { margin-bottom: 20px; color: #0f172a; }
        .detail-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
        .detail-label { font-weight: 600; color: #64748b; }
        .detail-value { font-weight: 700; color: #0f172a; }
        .price { font-size: 1.5rem; color: #1E5AA8; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a; }
        input, select { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-family: 'Inter', sans-serif; }
        .btn-purchase { background: linear-gradient(135deg, #1E5AA8, #6DBE45); color: white; padding: 14px 30px; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; width: 100%; font-size: 1rem; transition: all 0.3s ease; }
        .btn-purchase:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(30, 90, 168, 0.3); }
        @media (max-width: 768px) { .buy-container { grid-template-columns: 1fr; margin-top: 80px; } }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="buy-container">
        <div class="plan-details">
            <h2><i class="fas fa-shield-alt"></i> Plan Details</h2>
            <div class="detail-row"><span class="detail-label">Plan Name</span><span class="detail-value"><?php echo htmlspecialchars($plan_data['plan_name']); ?></span></div>
            <div class="detail-row"><span class="detail-label">Plan Type</span><span class="detail-value"><?php echo ucfirst($plan_data['plan_type']); ?></span></div>
            <div class="detail-row"><span class="detail-label">Premium Amount</span><span class="detail-value price">₹<?php echo number_format($plan_data['min_amount']); ?></span></div>
            <div class="detail-row"><span class="detail-label">Coverage Amount</span><span class="detail-value">₹<?php echo number_format($plan_data['coverage_amount']); ?></span></div>
            <div class="detail-row"><span class="detail-label">Policy Term</span><span class="detail-value"><?php echo $plan_data['duration_years']; ?> years</span></div>
            <?php if ($plan_data['tax_benefit']): ?>
            <div class="detail-row"><span class="detail-label">Tax Benefit</span><span class="detail-value">Up to ₹<?php echo number_format($plan_data['tax_benefit']); ?></span></div>
            <?php endif; ?>
            <div class="detail-row"><span class="detail-label">Description</span><span class="detail-value"><?php echo htmlspecialchars($plan_data['description']); ?></span></div>
        </div>
        
        <div class="purchase-form">
            <h2><i class="fas fa-file-signature"></i> Purchase Insurance</h2>
            <?php if (isset($error)): ?>
                <div style="background: #ef444420; color: #ef4444; padding: 12px; border-radius: 10px; margin-bottom: 20px;"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Nominee Name *</label>
                    <input type="text" name="nominee_name" required placeholder="Full name of nominee">
                </div>
                <div class="form-group">
                    <label>Nominee Relationship *</label>
                    <select name="nominee_relation" required>
                        <option value="">Select relationship</option>
                        <option value="spouse">Spouse</option>
                        <option value="child">Child</option>
                        <option value="parent">Parent</option>
                        <option value="sibling">Sibling</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Payment Method *</label>
                    <select name="payment_method" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="upi">UPI</option>
                        <option value="net_banking">Net Banking</option>
                    </select>
                </div>
                <button type="submit" class="btn-purchase"><i class="fas fa-lock"></i> Pay ₹<?php echo number_format($plan_data['min_amount']); ?> & Buy Now</button>
            </form>
        </div>
    </div>
</body>
</html>