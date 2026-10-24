<?php
require_once '../includes/dbConfig.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_insurances = getUserInsurancePurchases($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Insurance - <?php echo SITE_NAME; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%); }
        .my-insurance-container { max-width: 1200px; margin: 100px auto 40px; padding: 0 20px; }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { font-size: 2rem; color: #0f172a; }
        .insurance-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 25px; }
        .insurance-card { background: white; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; }
        .insurance-card:hover { transform: translateY(-5px); }
        .policy-header { display: flex; justify-content: space-between; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #e2e8f0; }
        .policy-number { font-weight: 700; color: #1E5AA8; }
        .status-badge { padding: 4px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; background: #10b98120; color: #10b981; }
        .insurance-card h3 { margin-bottom: 15px; color: #0f172a; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .detail-label { color: #64748b; font-size: 0.85rem; }
        .detail-value { font-weight: 600; color: #0f172a; }
        .empty-state { text-align: center; padding: 60px; background: white; border-radius: 20px; }
        .empty-state i { font-size: 4rem; color: #cbd5e1; margin-bottom: 20px; }
        .btn-buy { display: inline-block; margin-top: 20px; background: linear-gradient(135deg, #1E5AA8, #6DBE45); color: white; padding: 10px 24px; border-radius: 50px; text-decoration: none; }
        @media (max-width: 768px) { .my-insurance-container { margin-top: 80px; } .insurance-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="my-insurance-container">
        <div class="page-header">
            <h1><i class="fas fa-shield-alt"></i> My Insurance Policies</h1>
        </div>
        
        <?php if ($user_insurances && $user_insurances->num_rows > 0): ?>
            <div class="insurance-grid">
                <?php while ($policy = $user_insurances->fetch_assoc()): ?>
                    <div class="insurance-card">
                        <div class="policy-header">
                            <span class="policy-number"><i class="fas fa-file-alt"></i> <?php echo $policy['policy_number']; ?></span>
                            <span class="status-badge"><?php echo ucfirst($policy['status']); ?></span>
                        </div>
                        <h3><?php echo htmlspecialchars($policy['plan_name']); ?></h3>
                        <div class="detail-row"><span class="detail-label">Plan Type</span><span class="detail-value"><?php echo ucfirst($policy['plan_type']); ?></span></div>
                        <div class="detail-row"><span class="detail-label">Coverage</span><span class="detail-value">₹<?php echo number_format($policy['coverage_amount']); ?></span></div>
                        <div class="detail-row"><span class="detail-label">Premium Paid</span><span class="detail-value">₹<?php echo number_format($policy['premium_amount']); ?></span></div>
                        <div class="detail-row"><span class="detail-label">Start Date</span><span class="detail-value"><?php echo date('d M Y', strtotime($policy['start_date'])); ?></span></div>
                        <div class="detail-row"><span class="detail-label">End Date</span><span class="detail-value"><?php echo date('d M Y', strtotime($policy['end_date'])); ?></span></div>
                        <?php if ($policy['nominee_name']): ?>
                            <div class="detail-row"><span class="detail-label">Nominee</span><span class="detail-value"><?php echo htmlspecialchars($policy['nominee_name']); ?> (<?php echo $policy['nominee_relation']; ?>)</span></div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-shield-alt"></i>
                <p>You haven't purchased any insurance policy yet.</p>
                <a href="../index.php#insurance-plans" class="btn-buy">Browse Insurance Plans</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>