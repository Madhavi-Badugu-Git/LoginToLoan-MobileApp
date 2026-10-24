<?php
// FILE: user/investments.php
include('../includes/dbConfig.php');
include('../includes/navbar.php');

if(!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Handle new investment
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['invest'])) {
    $plan_id = sanitize($_POST['plan_id']);
    $amount = sanitize($_POST['amount']);
    
    // Get plan details
    $plan = $conn->query("SELECT * FROM investment_plans WHERE id = $plan_id")->fetch_assoc();
    
    if($amount >= $plan['min_amount']) {
        $maturity_date = date('Y-m-d', strtotime("+{$plan['duration_months']} months"));
        $expected_returns = $amount + ($amount * $plan['interest_rate'] * $plan['duration_months'] / 1200);
        
        $insert = "INSERT INTO user_investments (user_id, plan_id, amount, expected_returns, status, invest_date, maturity_date) 
                   VALUES ($user_id, $plan_id, $amount, $expected_returns, 'active', CURDATE(), '$maturity_date')";
        
        if($conn->query($insert)) {
            $_SESSION['success_message'] = "Investment successful!";
            redirect('user/investments.php');
        }
    } else {
        $_SESSION['error_message'] = "Minimum investment amount is ₹" . number_format($plan['min_amount']);
    }
}

// Get user's investments
$investments = $conn->query("SELECT ui.*, ip.plan_name, ip.interest_rate, ip.duration_months 
                             FROM user_investments ui 
                             JOIN investment_plans ip ON ui.plan_id = ip.id 
                             WHERE ui.user_id = $user_id 
                             ORDER BY ui.created_at DESC");

// Get available plans
$plans = $conn->query("SELECT * FROM investment_plans WHERE status = 'active'");
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
:root {
    --ink:       #0b1120;
    --ink-mid:   #1e2d45;
    --ink-soft:  #3a4f6a;
    --muted:     #6b7f99;
    --border:    #dde4ef;
    --surface:   #f4f7fc;
    --card:      #ffffff;
    --blue:      #1a4fdb;
    --green:     #17b26a;
    --red:       #ef4444;
    --amber:     #f59e0b;
    --violet:    #7c3aed;
    --grad:      linear-gradient(135deg, #1a4fdb 0%, #17b26a 100%);
    --radius:    16px;
    --radius-lg: 24px;
    --shadow-sm: 0 1px 3px rgba(11,17,32,.06), 0 1px 2px rgba(11,17,32,.04);
    --shadow:    0 4px 16px rgba(11,17,32,.08);
    --shadow-lg: 0 16px 48px rgba(11,17,32,.12);
    --t:         all .22s cubic-bezier(.4,0,.2,1);
    --font:      'Sora', sans-serif;
    --mono:      'JetBrains Mono', monospace;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: var(--font); background: var(--surface); color: var(--ink); -webkit-font-smoothing: antialiased; }

.inv-wrap { max-width: 1360px; margin: 0 auto; padding: 32px 28px 60px; }

/* Hero Section */
.inv-hero {
    background: linear-gradient(135deg, #0f172a 0%, #0f172a 100%);
    border-radius: var(--radius-lg);
    padding: 40px 44px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
}
.inv-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 80% at 85% 40%, rgba(26,79,219,.28) 0%, transparent 65%),
        radial-gradient(ellipse 40% 55% at 5% 90%,  rgba(23,178,106,.18)  0%, transparent 60%);
    pointer-events: none;
}
.inv-hero::after {
    content: '';
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px);
    background-size: 38px 38px;
    pointer-events: none;
}
.hero-left { position: relative; z-index: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(23,178,106,.12); border: 1px solid rgba(23,178,106,.3);
    border-radius: 50px; padding: 4px 14px;
    font-size: 10.5px; font-weight: 600; color: #4ade80; letter-spacing: .7px; text-transform: uppercase; margin-bottom: 14px;
}
.inv-hero h1 { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1.15; }
.inv-hero h1 span { background: linear-gradient(90deg, #6ee7b7, #60a5fa); -webkit-background-clip: text; background-clip: text; color: transparent; }
.inv-hero p { margin-top: 8px; font-size: .875rem; color: rgba(255,255,255,.42); }
.hero-right { position: relative; z-index: 1; flex-shrink: 0; }
.primary-btn {
    display: inline-flex; align-items: center; gap: 10px;
    background: #fff; color: var(--ink);
    padding: 13px 26px; border-radius: 50px;
    font-size: .88rem; font-weight: 700; font-family: var(--font);
    border: none; cursor: pointer; transition: var(--t);
    box-shadow: 0 4px 20px rgba(0,0,0,.18); text-decoration: none;
}
.primary-btn i { color: var(--blue); font-size: .85rem; }
.primary-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.25); background: #f0f6ff; }

/* Stats Cards */
.stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; }
.stat-card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); box-shadow: var(--shadow-sm);
    padding: 20px 22px 18px; position: relative; overflow: hidden; transition: var(--t);
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow); border-color: transparent; }
.stat-card-bar { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: var(--radius) var(--radius) 0 0; }
.stat-card:nth-child(1) .stat-card-bar { background: linear-gradient(90deg, #1a4fdb, #60a5fa); }
.stat-card:nth-child(2) .stat-card-bar { background: linear-gradient(90deg, #17b26a, #4ade80); }
.stat-card:nth-child(3) .stat-card-bar { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
.stat-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1rem; margin-bottom: 14px; }
.stat-card:nth-child(1) .stat-icon { background: rgba(26,79,219,.1); color: var(--blue); }
.stat-card:nth-child(2) .stat-icon { background: rgba(23,178,106,.1); color: var(--green); }
.stat-card:nth-child(3) .stat-icon { background: rgba(245,158,11,.1); color: var(--amber); }
.stat-value { font-size: 1.6rem; font-weight: 800; color: var(--ink); letter-spacing: -1px; line-height: 1; margin-bottom: 4px; font-family: var(--mono); }
.stat-label { font-size: .8rem; font-weight: 600; color: var(--ink-soft); }
.stat-sub { font-size: .7rem; color: var(--muted); margin-top: 4px; }

/* Investments Grid */
.inv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 20px; }

.inv-card {
    background: var(--card); border-radius: var(--radius-lg);
    border: 1px solid var(--border); box-shadow: var(--shadow-sm);
    overflow: hidden; transition: var(--t); display: flex; flex-direction: column;
}
.inv-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: transparent; }

.card-strip { height: 4px; width: 100%; background: var(--grad); }

.card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px 14px; border-bottom: 1px solid var(--border);
}
.card-title { display: flex; align-items: center; gap: 10px; }
.card-title-icon {
    width: 42px; height: 42px; border-radius: 12px;
    background: rgba(26,79,219,.1); color: var(--blue);
    display: flex; align-items: center; justify-content: center; font-size: 1rem;
}
.card-title-text h3 { font-size: 1rem; font-weight: 700; color: var(--ink); margin: 0; }
.card-title-text p { font-size: .7rem; color: var(--muted); margin: 2px 0 0; }

.badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 8px; font-size: .68rem; font-weight: 700;
}
.badge-active  { background: #dcfce7; color: #15803d; }
.badge-completed { background: #dbeafe; color: #1d4ed8; }
.badge-pending { background: #fef9c3; color: #92400e; }
.badge-cancelled { background: #fee2e2; color: #b91c1c; }

.card-body { padding: 18px 22px; flex: 1; }

.progress-section { margin-bottom: 18px; }
.progress-header { display: flex; justify-content: space-between; font-size: .7rem; color: var(--muted); margin-bottom: 6px; }
.progress-bar-bg { background: #e9eef3; height: 6px; border-radius: 10px; overflow: hidden; }
.progress-fill {
    background: var(--grad); height: 100%; border-radius: 10px;
    transition: width .5s ease; position: relative; overflow: hidden;
}
.progress-fill::after {
    content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.3), transparent);
    animation: shimmer 2s infinite;
}
@keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

.metrics-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 16px 0; }
.metric-item { text-align: center; }
.metric-label { font-size: .65rem; font-weight: 600; color: var(--muted); text-transform: uppercase; margin-bottom: 4px; }
.metric-value { font-size: .9rem; font-weight: 700; font-family: var(--mono); }
.metric-value.blue { color: var(--blue); }
.metric-value.green { color: var(--green); }
.metric-value.dark { color: var(--ink); }

.card-footer {
    padding: 12px 22px 16px;
    border-top: 1px solid var(--border);
    display: flex; justify-content: space-between;
    font-size: .7rem; color: var(--muted);
}
.card-footer i { margin-right: 4px; }

/* Empty State */
.empty-state {
    background: var(--card); border-radius: var(--radius-lg);
    border: 2px dashed var(--border); padding: 72px 40px;
    text-align: center;
}
.empty-icon {
    width: 80px; height: 80px; border-radius: 24px;
    background: var(--surface); border: 2px dashed var(--border);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px; font-size: 2rem; color: var(--muted);
}
.empty-state h3 { font-size: 1.25rem; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
.empty-state p { font-size: .875rem; color: var(--muted); margin-bottom: 24px; max-width: 360px; margin-left: auto; margin-right: auto; }

/* Modal */
.modal {
    display: none; position: fixed; inset: 0;
    background: rgba(11,17,32,.65); backdrop-filter: blur(6px);
    z-index: 1000; align-items: center; justify-content: center; padding: 20px;
}
.modal-box {
    background: var(--card); border-radius: var(--radius-lg);
    width: 100%; max-width: 520px; max-height: 92vh;
    overflow-y: auto; box-shadow: var(--shadow-lg);
    animation: slideUp .28s cubic-bezier(.4,0,.2,1);
}
@keyframes slideUp {
    from { opacity: 0; transform: translateY(24px) scale(.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.modal-top {
    position: sticky; top: 0; background: var(--card);
    padding: 22px 26px 18px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between; z-index: 1;
}
.modal-title { display: flex; align-items: center; gap: 10px; }
.modal-title-icon {
    width: 38px; height: 38px; background: linear-gradient(135deg, rgba(26,79,219,.12), rgba(23,178,106,.09));
    border-radius: 10px; display: flex; align-items: center; justify-content: center;
    color: var(--blue); font-size: .9rem;
}
.modal-title h2 { font-size: 1.1rem; font-weight: 700; color: var(--ink); }
.modal-title p { font-size: .72rem; color: var(--muted); }
.modal-close {
    width: 34px; height: 34px; border-radius: 9px;
    background: var(--surface); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; font-size: 1.1rem; color: var(--muted);
    transition: var(--t);
}
.modal-close:hover { background: #fee2e2; border-color: #fecaca; color: var(--red); }

.modal-body { padding: 22px 26px 26px; }

.form-group { margin-bottom: 20px; }
.form-group label {
    display: block; margin-bottom: 8px;
    font-size: .8rem; font-weight: 700; color: var(--ink-soft);
}
.form-group label i { color: var(--blue); margin-right: 6px; width: 16px; }
.form-group select, .form-group input {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid var(--border); border-radius: 12px;
    font-size: .875rem; font-family: var(--font);
    color: var(--ink); background: var(--card);
    transition: var(--t); outline: none;
}
.form-group select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7f99'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center;
    padding-right: 36px; cursor: pointer;
}
.form-group select:focus, .form-group input:focus {
    border-color: var(--blue); box-shadow: 0 0 0 3px rgba(26,79,219,.1);
}

.plan-details {
    background: linear-gradient(135deg, #f0f6ff, #f0fdf4);
    border: 1px solid #bfdbfe; border-radius: 14px;
    padding: 16px 18px; margin: 18px 0;
}
.plan-details p { margin: 6px 0; font-size: .8rem; color: var(--ink-soft); }
.plan-details strong { color: var(--ink); }
.plan-details hr { margin: 12px 0; border-color: rgba(26,79,219,.15); }

.min-amount-msg {
    font-size: .7rem; color: var(--muted);
    margin-top: 6px; display: flex; align-items: center; gap: 5px;
}

.modal-submit {
    width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;
    background: var(--ink); color: #fff; padding: 14px;
    border-radius: 12px; font-size: .9rem; font-weight: 700;
    font-family: var(--font); border: none; cursor: pointer;
    transition: var(--t); margin-top: 8px;
}
.modal-submit:hover { background: var(--blue); box-shadow: 0 8px 24px rgba(26,79,219,.3); transform: translateY(-1px); }

/* Animations */
@keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
.inv-hero { animation: fadeUp .38s ease both; }
.stats-row { animation: fadeUp .38s .1s ease both; }
.inv-grid, .empty-state { animation: fadeUp .38s .18s ease both; }

/* Responsive */
@media (max-width: 1024px) {
    .inv-hero { padding: 30px 28px; flex-direction: column; align-items: flex-start; }
    .stats-row { grid-template-columns: repeat(3,1fr); }
}
@media (max-width: 768px) {
    .inv-wrap { padding: 16px 16px 48px; }
    .inv-hero h1 { font-size: 1.5rem; }
    .inv-grid { grid-template-columns: 1fr; }
    .stats-row { grid-template-columns: 1fr; gap: 12px; }
    .metrics-3 { gap: 8px; }
    .metric-value { font-size: .8rem; }
}
</style>

<div class="inv-wrap">

    <!-- Hero Section -->
    <div class="inv-hero">
        <div class="hero-left">
            <div class="hero-eyebrow"><i class="fas fa-chart-line"></i> Grow Your Wealth</div>
            <h1>My <span>Investments</span></h1>
            <p>Track your portfolio, monitor returns, and start new investments.</p>
        </div>
        <div class="hero-right">
            <button class="primary-btn" onclick="showInvestModal()">
                <i class="fas fa-plus-circle"></i> New Investment
            </button>
        </div>
    </div>

    <!-- Stats Summary -->
    <?php 
    $total_invested_sum = $conn->query("SELECT SUM(amount) as total FROM user_investments WHERE user_id = $user_id AND status = 'active'")->fetch_assoc()['total'] ?? 0;
    $total_returns_sum = $conn->query("SELECT SUM(expected_returns) as total FROM user_investments WHERE user_id = $user_id AND status = 'active'")->fetch_assoc()['total'] ?? 0;
    $active_count = $conn->query("SELECT COUNT(*) as count FROM user_investments WHERE user_id = $user_id AND status = 'active'")->fetch_assoc()['count'] ?? 0;
    ?>
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-card-bar"></div>
            <div class="stat-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="stat-value">₹<?php echo number_format($total_invested_sum, 2); ?></div>
            <div class="stat-label">Total Invested</div>
            <div class="stat-sub">Active portfolio value</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-bar"></div>
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stat-value" style="color: var(--green);">₹<?php echo number_format($total_returns_sum, 2); ?></div>
            <div class="stat-label">Expected Returns</div>
            <div class="stat-sub">At maturity</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-bar"></div>
            <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
            <div class="stat-value"><?php echo $active_count; ?></div>
            <div class="stat-label">Active Investments</div>
            <div class="stat-sub">Working for you</div>
        </div>
    </div>

    <!-- Investments Grid -->
    <?php if($investments && $investments->num_rows > 0): ?>
    <div class="inv-grid">
        <?php while($inv = $investments->fetch_assoc()): 
            $total_days = (strtotime($inv['maturity_date']) - strtotime($inv['invest_date'])) / (60*60*24);
            $elapsed_days = (time() - strtotime($inv['invest_date'])) / (60*60*24);
            $progress = min(100, ($elapsed_days / $total_days) * 100);
            
            $status_class = $inv['status'];
            $status_icon = $inv['status'] == 'active' ? 'fa-play-circle' : ($inv['status'] == 'completed' ? 'fa-check-circle' : 'fa-clock');
        ?>
        <div class="inv-card">
            <div class="card-strip"></div>
            <div class="card-head">
                <div class="card-title">
                    <div class="card-title-icon">
                        <i class="fas fa-chart-simple"></i>
                    </div>
                    <div class="card-title-text">
                        <h3><?php echo htmlspecialchars($inv['plan_name']); ?></h3>
                        <p>Started <?php echo date('d M Y', strtotime($inv['invest_date'])); ?></p>
                    </div>
                </div>
                <span class="badge badge-<?php echo $status_class; ?>">
                    <i class="fas <?php echo $status_icon; ?>"></i> <?php echo ucfirst($inv['status']); ?>
                </span>
            </div>
            <div class="card-body">
                <div class="progress-section">
                    <div class="progress-header">
                        <span><i class="fas fa-calendar-alt"></i> Progress</span>
                        <span><?php echo round($progress); ?>% Complete</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-fill" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>
                
                <div class="metrics-3">
                    <div class="metric-item">
                        <div class="metric-label">Invested</div>
                        <div class="metric-value blue">₹<?php echo number_format($inv['amount'], 2); ?></div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Returns</div>
                        <div class="metric-value green">₹<?php echo number_format($inv['expected_returns'], 2); ?></div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-label">Maturity</div>
                        <div class="metric-value dark"><?php echo date('d M Y', strtotime($inv['maturity_date'])); ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <span><i class="fas fa-percent"></i> <?php echo $inv['interest_rate']; ?>% p.a.</span>
                <span><i class="fas fa-clock"></i> <?php echo $inv['duration_months']; ?> months</span>
                <span><i class="fas fa-calendar-day"></i> Invested: <?php echo date('d M Y', strtotime($inv['invest_date'])); ?></span>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-chart-line"></i></div>
        <h3>No Investments Yet</h3>
        <p>Start your investment journey today and watch your money grow with our high-return plans.</p>
        <button class="primary-btn" onclick="showInvestModal()">
            <i class="fas fa-rocket"></i> Start Investing
        </button>
    </div>
    <?php endif; ?>

</div>

<!-- Investment Modal -->
<div id="investModal" class="modal">
    <div class="modal-box">
        <div class="modal-top">
            <div class="modal-title">
                <div class="modal-title-icon"><i class="fas fa-chart-pie"></i></div>
                <div>
                    <h2>New Investment</h2>
                    <p>Choose a plan and start earning returns</p>
                </div>
            </div>
            <div class="modal-close" onclick="closeModal()">&#215;</div>
        </div>
        <div class="modal-body">
            <form method="POST" action="" id="investForm">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Select Plan</label>
                    <select name="plan_id" id="plan_id" required onchange="updatePlanDetails()">
                        <option value="">-- Choose an investment plan --</option>
                        <?php 
                        $plans->data_seek(0);
                        while($plan = $plans->fetch_assoc()): 
                        ?>
                        <option value="<?php echo $plan['id']; ?>" 
                                data-min="<?php echo $plan['min_amount']; ?>"
                                data-max="<?php echo $plan['max_amount']; ?>"
                                data-rate="<?php echo $plan['interest_rate']; ?>"
                                data-duration="<?php echo $plan['duration_months']; ?>"
                                data-risk="<?php echo $plan['risk_level']; ?>">
                            <?php echo htmlspecialchars($plan['plan_name']); ?> — <?php echo $plan['interest_rate']; ?>% p.a.
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-rupee-sign"></i> Investment Amount (₹)</label>
                    <input type="number" name="amount" id="amount" required step="1000" placeholder="Enter amount">
                    <div class="min-amount-msg" id="minAmountMsg"></div>
                </div>
                
                <div id="planDetails" class="plan-details" style="display: none;"></div>
                
                <button type="submit" name="invest" class="modal-submit">
                    <i class="fas fa-check-circle"></i> Invest Now
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function showInvestModal() {
    document.getElementById('investModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('investModal').style.display = 'none';
    document.body.style.overflow = '';
}

function updatePlanDetails() {
    const select = document.getElementById('plan_id');
    const selectedOption = select.options[select.selectedIndex];
    const planDetailsDiv = document.getElementById('planDetails');
    const minAmountMsg = document.getElementById('minAmountMsg');
    
    if(selectedOption.value) {
        const minAmount = parseInt(selectedOption.dataset.min);
        const maxAmount = parseInt(selectedOption.dataset.max);
        const rate = selectedOption.dataset.rate;
        const duration = selectedOption.dataset.duration;
        const risk = selectedOption.dataset.risk;
        
        let riskIcon = '🟢', riskColor = '#10b981';
        if(risk === 'Medium') { riskIcon = '🟡'; riskColor = '#f59e0b'; }
        if(risk === 'High') { riskIcon = '🔴'; riskColor = '#ef4444'; }
        
        minAmountMsg.innerHTML = `<i class="fas fa-info-circle"></i> Minimum investment: ₹${minAmount.toLocaleString()}`;
        
        planDetailsDiv.style.display = 'block';
        planDetailsDiv.innerHTML = `
            <strong><i class="fas fa-chart-line"></i> Plan Details</strong><br><br>
            <p><i class="fas fa-percent"></i> <strong>Interest Rate:</strong> ${rate}% per annum</p>
            <p><i class="fas fa-clock"></i> <strong>Duration:</strong> ${duration} months</p>
            <p><i class="fas fa-chart-line"></i> <strong>Risk Level:</strong> ${riskIcon} ${risk} Risk</p>
            <p><i class="fas fa-rupee-sign"></i> <strong>Investment Range:</strong> ₹${minAmount.toLocaleString()} — ₹${maxAmount.toLocaleString()}</p>
            <hr>
            <p style="color: #17b26a; margin-top: 8px;"><i class="fas fa-calculator"></i> Expected returns will be calculated at maturity</p>
        `;
    } else {
        planDetailsDiv.style.display = 'none';
        minAmountMsg.innerHTML = '';
    }
}

window.onclick = function(event) {
    const modal = document.getElementById('investModal');
    if (event.target == modal) closeModal();
}

document.getElementById('investForm')?.addEventListener('submit', function(e) {
    const select = document.getElementById('plan_id');
    const amount = document.getElementById('amount').value;
    const selectedOption = select.options[select.selectedIndex];
    
    if(!select.value) {
        e.preventDefault();
        alert('Please select an investment plan');
        return false;
    }
    
    const minAmount = parseInt(selectedOption.dataset.min);
    if(parseInt(amount) < minAmount) {
        e.preventDefault();
        alert(`Minimum investment amount is ₹${minAmount.toLocaleString()}`);
        return false;
    }
    
    return true;
});
</script>

<?php include('../includes/footer.php'); ?>