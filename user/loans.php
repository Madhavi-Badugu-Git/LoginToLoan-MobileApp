<?php
// FILE: user/loans.php
include('../includes/dbConfig.php');
include('../includes/navbar.php');

if(!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Handle loan application
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_loan'])) {
    $loan_type = sanitize($_POST['loan_type']);
    $amount = sanitize($_POST['amount']);
    $tenure = sanitize($_POST['tenure']);
    $purpose = sanitize($_POST['purpose']);
    
    // Calculate EMI (simple interest calculation)
    $interest_rate = 10.5; // Base rate
    $monthly_rate = $interest_rate / 12 / 100;
    $emi = ($amount * $monthly_rate * pow(1 + $monthly_rate, $tenure)) / (pow(1 + $monthly_rate, $tenure) - 1);
    
    $insert = "INSERT INTO loans (user_id, loan_type, amount, interest_rate, tenure_months, monthly_emi, purpose, status) 
               VALUES ($user_id, '$loan_type', $amount, $interest_rate, $tenure, $emi, '$purpose', 'pending')";
    
    if($conn->query($insert)) {
        $_SESSION['success_message'] = "Loan application submitted successfully!";
        redirect('user/loans.php');
    }
}

// Get user's loans
$loans = $conn->query("SELECT * FROM loans WHERE user_id = $user_id ORDER BY applied_date DESC");
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

.ln-wrap { max-width: 1360px; margin: 0 auto; padding: 32px 28px 60px; }

/* Hero */
.ln-hero {
    background: var(--ink);
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
.ln-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 80% at 85% 40%, rgba(26,79,219,.32) 0%, transparent 65%),
        radial-gradient(ellipse 40% 55% at 5% 90%,  rgba(23,178,106,.2)  0%, transparent 60%);
    pointer-events: none;
}
.ln-hero::after {
    content: '';
    position: absolute; inset: 0;
    background-image: linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 38px 38px;
    pointer-events: none;
}
.hero-left { position: relative; z-index: 1; }
.hero-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(23,178,106,.15); border: 1px solid rgba(23,178,106,.3);
    border-radius: 50px; padding: 4px 14px;
    font-size: 10.5px; font-weight: 600; color: #4ade80; letter-spacing: .7px; text-transform: uppercase; margin-bottom: 14px;
}
.ln-hero h1 { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1.15; }
.ln-hero h1 span { background: linear-gradient(90deg, #6ee7b7, #60a5fa); -webkit-background-clip: text; background-clip: text; color: transparent; }
.ln-hero p { margin-top: 8px; font-size: .875rem; color: rgba(255,255,255,.42); }
.hero-right { position: relative; z-index: 1; flex-shrink: 0; }
.apply-btn {
    display: inline-flex; align-items: center; gap: 10px;
    background: #fff; color: var(--ink);
    padding: 13px 26px; border-radius: 50px;
    font-size: .88rem; font-weight: 700; font-family: var(--font);
    border: none; cursor: pointer; transition: var(--t);
    box-shadow: 0 4px 20px rgba(0,0,0,.18); text-decoration: none;
}
.apply-btn i { color: var(--blue); font-size: .85rem; }
.apply-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.25); background: #f0f6ff; }

/* Summary */
.summary-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 28px; }
.sum-card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); box-shadow: var(--shadow-sm);
    padding: 22px 22px 18px; position: relative; overflow: hidden; transition: var(--t);
}
.sum-card:hover { transform: translateY(-3px); box-shadow: var(--shadow); }
.sum-card-bar { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: var(--radius) var(--radius) 0 0; }
.sum-card:nth-child(1) .sum-card-bar { background: linear-gradient(90deg, #1a4fdb, #60a5fa); }
.sum-card:nth-child(2) .sum-card-bar { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
.sum-card:nth-child(3) .sum-card-bar { background: linear-gradient(90deg, #17b26a, #4ade80); }
.sum-icon { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: .95rem; margin-bottom: 14px; }
.sum-card:nth-child(1) .sum-icon { background: rgba(26,79,219,.1);  color: var(--blue);  }
.sum-card:nth-child(2) .sum-icon { background: rgba(245,158,11,.1); color: var(--amber); }
.sum-card:nth-child(3) .sum-icon { background: rgba(23,178,106,.1); color: var(--green); }
.sum-val   { font-size: 1.6rem; font-weight: 800; color: var(--ink); letter-spacing: -1px; line-height: 1; margin-bottom: 4px; font-family: var(--mono); }
.sum-label { font-size: .8rem; font-weight: 600; color: var(--ink-soft); }
.sum-sub   { font-size: .7rem; color: var(--muted); margin-top: 4px; }

/* Loans grid */
.loans-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(380px, 1fr)); gap: 20px; }

.loan-card {
    background: var(--card); border-radius: var(--radius-lg);
    border: 1px solid var(--border); box-shadow: var(--shadow-sm);
    overflow: hidden; transition: var(--t); display: flex; flex-direction: column;
}
.loan-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: transparent; }

.lc-strip { height: 4px; width: 100%; }
.strip-personal  { background: linear-gradient(90deg, #1a4fdb, #60a5fa); }
.strip-business  { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
.strip-education { background: linear-gradient(90deg, #7c3aed, #a78bfa); }
.strip-home      { background: linear-gradient(90deg, #17b26a, #4ade80); }

.lc-head { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px 14px; border-bottom: 1px solid var(--border); }
.lc-type { display: flex; align-items: center; gap: 10px; }
.lc-type-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: .95rem; flex-shrink: 0; }
.icon-personal  { background: rgba(26,79,219,.1);  color: var(--blue);   }
.icon-business  { background: rgba(245,158,11,.1); color: var(--amber);  }
.icon-education { background: rgba(124,58,237,.1); color: var(--violet); }
.icon-home      { background: rgba(23,178,106,.1); color: var(--green);  }
.lc-type-name { font-size: .95rem; font-weight: 700; color: var(--ink); }
.lc-type-sub  { font-size: .7rem; color: var(--muted); margin-top: 2px; }

.s-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 8px; font-size: .68rem; font-weight: 700; letter-spacing: .2px; flex-shrink: 0; }
.s-badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
.s-approved  { background: #dcfce7; color: #15803d; }
.s-pending   { background: #fef9c3; color: #92400e; }
.s-rejected  { background: #fee2e2; color: #b91c1c; }
.s-active    { background: #dbeafe; color: #1d4ed8; }
.s-closed    { background: #f1f5f9; color: #475569; }

.lc-body { padding: 18px 22px; flex: 1; }

.metrics-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 16px; }
.metric { background: var(--surface); border-radius: 12px; padding: 12px 10px; text-align: center; border: 1px solid transparent; transition: var(--t); }
.metric:hover { border-color: var(--border); background: var(--card); }
.metric-lbl { font-size: .65rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
.metric-val { font-size: .95rem; font-weight: 700; color: var(--ink); font-family: var(--mono); }
.metric-val.m-blue  { color: var(--blue);  }
.metric-val.m-green { color: var(--green); }

.lc-summary { background: var(--surface); border-radius: 12px; padding: 14px 16px; margin-bottom: 14px; display: flex; gap: 20px; flex-wrap: wrap; }
.lc-summary-item span   { font-size: .66rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; display: block; margin-bottom: 3px; }
.lc-summary-item strong { font-size: .82rem; font-weight: 700; color: var(--ink); font-family: var(--mono); }

.lc-purpose { display: flex; gap: 10px; align-items: flex-start; padding: 12px 14px; background: #f8fafd; border-radius: 10px; border-left: 3px solid var(--blue); margin-bottom: 10px; }
.lc-purpose i { color: var(--blue); font-size: .75rem; margin-top: 2px; flex-shrink: 0; }
.lc-purpose p { font-size: .78rem; color: var(--ink-soft); line-height: 1.55; }

.lc-rejection { display: flex; gap: 10px; align-items: flex-start; padding: 12px 14px; background: #fff5f5; border-radius: 10px; border-left: 3px solid var(--red); margin-bottom: 10px; }
.lc-rejection i { color: var(--red); font-size: .75rem; margin-top: 2px; flex-shrink: 0; }
.lc-rejection p { font-size: .78rem; color: #b91c1c; line-height: 1.55; }

/* Empty */
.empty-state { background: var(--card); border-radius: var(--radius-lg); border: 2px dashed var(--border); padding: 72px 40px; text-align: center; }
.empty-icon  { width: 72px; height: 72px; border-radius: 20px; background: var(--surface); border: 2px dashed var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem; color: var(--muted); }
.empty-state h3 { font-size: 1.25rem; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
.empty-state p  { font-size: .875rem; color: var(--muted); margin-bottom: 24px; max-width: 360px; margin-left: auto; margin-right: auto; }
.empty-apply-btn { display: inline-flex; align-items: center; gap: 9px; background: var(--ink); color: #fff; padding: 13px 28px; border-radius: 50px; font-size: .875rem; font-weight: 700; font-family: var(--font); border: none; cursor: pointer; transition: var(--t); }
.empty-apply-btn:hover { background: var(--blue); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(26,79,219,.25); }

/* Modal */
.modal { display: none; position: fixed; inset: 0; background: rgba(11,17,32,.65); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 20px; }
.modal-box { background: var(--card); border-radius: var(--radius-lg); width: 100%; max-width: 520px; max-height: 92vh; overflow-y: auto; box-shadow: var(--shadow-lg); animation: slideUp .28s cubic-bezier(.4,0,.2,1); }
@keyframes slideUp { from { opacity: 0; transform: translateY(24px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }

.modal-top { position: sticky; top: 0; background: var(--card); padding: 22px 26px 18px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; z-index: 1; }
.modal-title { display: flex; align-items: center; gap: 10px; }
.modal-title-icon { width: 38px; height: 38px; background: linear-gradient(135deg, rgba(26,79,219,.12), rgba(23,178,106,.09)); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--blue); font-size: .9rem; }
.modal-title h2 { font-size: 1.1rem; font-weight: 700; color: var(--ink); }
.modal-title p  { font-size: .72rem; color: var(--muted); }
.modal-close { width: 34px; height: 34px; border-radius: 9px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1.1rem; color: var(--muted); transition: var(--t); line-height: 1; }
.modal-close:hover { background: #fee2e2; border-color: #fecaca; color: var(--red); }

.modal-body { padding: 22px 26px 26px; }

.fg { margin-bottom: 18px; }
.fg label { display: block; margin-bottom: 7px; font-size: .8rem; font-weight: 700; color: var(--ink-soft); letter-spacing: .2px; }
.fg label i { color: var(--blue); margin-right: 6px; width: 14px; text-align: center; }
.fg select, .fg input, .fg textarea { width: 100%; padding: 11px 14px; border: 1.5px solid var(--border); border-radius: 11px; font-size: .875rem; font-family: var(--font); color: var(--ink); background: var(--card); transition: var(--t); outline: none; -webkit-appearance: none; }
.fg select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%236b7f99'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; cursor: pointer; }
.fg select:focus, .fg input:focus, .fg textarea:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(26,79,219,.1); }
.fg textarea { resize: vertical; min-height: 80px; }

.emi-preview { display: none; background: linear-gradient(135deg, #f0f6ff, #f0fdf4); border: 1px solid #bfdbfe; border-radius: 12px; padding: 16px 18px; margin-bottom: 18px; text-align: center; }
.emi-preview-lbl  { font-size: .72rem; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
.emi-preview-val  { font-size: 1.6rem; font-weight: 800; color: var(--blue); font-family: var(--mono); letter-spacing: -1px; margin: 6px 0 4px; }
.emi-preview-note { font-size: .68rem; color: var(--muted); }

.modal-submit { width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; background: var(--ink); color: #fff; padding: 14px; border-radius: 12px; font-size: .9rem; font-weight: 700; font-family: var(--font); border: none; cursor: pointer; transition: var(--t); margin-top: 4px; }
.modal-submit:hover { background: var(--blue); box-shadow: 0 8px 24px rgba(26,79,219,.3); transform: translateY(-1px); }

/* Animations */
@keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
.ln-hero     { animation: fadeUp .38s ease both; }
.summary-row { animation: fadeUp .38s .1s ease both; }
.loans-grid, .empty-state { animation: fadeUp .38s .18s ease both; }

/* Responsive */
@media (max-width: 1024px) { .ln-hero { padding: 30px 28px; flex-direction: column; align-items: flex-start; } .summary-row { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 768px)  { .ln-wrap { padding: 16px 16px 48px; } .ln-hero h1 { font-size: 1.5rem; } .loans-grid { grid-template-columns: 1fr; } .summary-row { grid-template-columns: 1fr; gap: 12px; } }
</style>

<div class="ln-wrap">

    <!-- Hero -->
    <div class="ln-hero">
        <div class="hero-left">
            <div class="hero-eyebrow"><i class="fas fa-hand-holding-usd"></i> Loan Management</div>
            <h1>Your <span>Loan</span> Dashboard</h1>
            <p>Track applications, monitor EMIs, and apply for new loans — all in one place.</p>
        </div>
        <div class="hero-right">
            <button class="apply-btn" onclick="showLoanModal()">
                <i class="fas fa-plus-circle"></i> Apply for a Loan
            </button>
        </div>
    </div>

    <!-- Summary -->
    <?php
    $total_loan_amount   = $conn->query("SELECT SUM(amount) as total FROM loans WHERE user_id = $user_id AND status IN ('approved', 'active')")->fetch_assoc()['total'] ?? 0;
    $pending_loans_count = $conn->query("SELECT COUNT(*) as count FROM loans WHERE user_id = $user_id AND status = 'pending'")->fetch_assoc()['count'] ?? 0;
    $active_loans_count  = $conn->query("SELECT COUNT(*) as count FROM loans WHERE user_id = $user_id AND status = 'active'")->fetch_assoc()['count'] ?? 0;
    ?>
    <div class="summary-row">
        <div class="sum-card">
            <div class="sum-card-bar"></div>
            <div class="sum-icon"><i class="fas fa-rupee-sign"></i></div>
            <div class="sum-val">&#8377;<?php echo number_format($total_loan_amount, 0); ?></div>
            <div class="sum-label">Active Loan Value</div>
            <div class="sum-sub">Approved &amp; active loans</div>
        </div>
        <div class="sum-card">
            <div class="sum-card-bar"></div>
            <div class="sum-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="sum-val"><?php echo $pending_loans_count; ?></div>
            <div class="sum-label">Pending Applications</div>
            <div class="sum-sub">Under review by admin</div>
        </div>
        <div class="sum-card">
            <div class="sum-card-bar"></div>
            <div class="sum-icon"><i class="fas fa-check-circle"></i></div>
            <div class="sum-val"><?php echo $active_loans_count; ?></div>
            <div class="sum-label">Approved Loans</div>
            <div class="sum-sub">Successfully funded</div>
        </div>
    </div>

    <!-- Loans Grid -->
    <?php if($loans && $loans->num_rows > 0): ?>
    <div class="loans-grid">
        <?php while($loan = $loans->fetch_assoc()):
            $type = $loan['loan_type'];
            $icon_map = ['personal' => 'fa-user', 'business' => 'fa-briefcase', 'education' => 'fa-graduation-cap', 'home' => 'fa-home'];
            $icon = $icon_map[$type] ?? 'fa-file-invoice';
        ?>
        <div class="loan-card">
            <div class="lc-strip strip-<?php echo $type; ?>"></div>
            <div class="lc-head">
                <div class="lc-type">
                    <div class="lc-type-icon icon-<?php echo $type; ?>">
                        <i class="fas <?php echo $icon; ?>"></i>
                    </div>
                    <div>
                        <div class="lc-type-name"><?php echo ucfirst($type); ?> Loan</div>
                        <div class="lc-type-sub">Applied <?php echo date('d M Y', strtotime($loan['applied_date'])); ?></div>
                    </div>
                </div>
                <span class="s-badge s-<?php echo $loan['status']; ?>"><?php echo ucfirst($loan['status']); ?></span>
            </div>
            <div class="lc-body">
                <div class="metrics-row">
                    <div class="metric">
                        <div class="metric-lbl">Principal</div>
                        <div class="metric-val m-blue">&#8377;<?php echo number_format($loan['amount'], 0); ?></div>
                    </div>
                    <div class="metric">
                        <div class="metric-lbl">Rate p.a.</div>
                        <div class="metric-val"><?php echo $loan['interest_rate']; ?>%</div>
                    </div>
                    <div class="metric">
                        <div class="metric-lbl">Monthly EMI</div>
                        <div class="metric-val m-green">&#8377;<?php echo number_format($loan['monthly_emi'], 0); ?></div>
                    </div>
                </div>
                <div class="lc-summary">
                    <div class="lc-summary-item">
                        <span>Tenure</span>
                        <strong><?php echo $loan['tenure_months']; ?> months</strong>
                    </div>
                    <div class="lc-summary-item">
                        <span>Total Payable</span>
                        <strong>&#8377;<?php echo number_format($loan['monthly_emi'] * $loan['tenure_months'], 0); ?></strong>
                    </div>
                    <?php if($loan['approved_date']): ?>
                    <div class="lc-summary-item">
                        <span>Approved</span>
                        <strong><?php echo date('d M Y', strtotime($loan['approved_date'])); ?></strong>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if($loan['purpose']): ?>
                <div class="lc-purpose">
                    <i class="fas fa-quote-left"></i>
                    <p><?php echo htmlspecialchars($loan['purpose']); ?></p>
                </div>
                <?php endif; ?>
                <?php if($loan['rejection_reason']): ?>
                <div class="lc-rejection">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p><strong>Rejection reason:</strong> <?php echo htmlspecialchars($loan['rejection_reason']); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <?php else: ?>
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-hand-holding-usd"></i></div>
        <h3>No Loan Applications Yet</h3>
        <p>Need funds? Apply for a loan with competitive rates starting at 10.5% p.a. — quick approval, no hidden fees.</p>
        <button class="empty-apply-btn" onclick="showLoanModal()">
            <i class="fas fa-rocket"></i> Apply Now
        </button>
    </div>
    <?php endif; ?>

</div>

<!-- Modal -->
<div id="loanModal" class="modal">
    <div class="modal-box">
        <div class="modal-top">
            <div class="modal-title">
                <div class="modal-title-icon"><i class="fas fa-file-invoice"></i></div>
                <div>
                    <h2>Apply for a Loan</h2>
                    <p>Fill in the details below to submit your application</p>
                </div>
            </div>
            <div class="modal-close" onclick="closeLoanModal()">&#215;</div>
        </div>
        <div class="modal-body">
            <form method="POST" action="" id="loanForm">
                <div class="fg">
                    <label><i class="fas fa-tag"></i> Loan Type</label>
                    <select name="loan_type" id="loan_type" required>
                        <option value="personal">Personal Loan</option>
                        <option value="business">Business Loan</option>
                        <option value="education">Education Loan</option>
                        <option value="home">Home Loan</option>
                    </select>
                </div>
                <div class="fg">
                    <label><i class="fas fa-rupee-sign"></i> Loan Amount (&#8377;)</label>
                    <input type="number" name="amount" id="loan_amount" required min="10000" step="10000" placeholder="e.g. 500000" oninput="calculateEMIPreview()">
                </div>
                <div class="fg">
                    <label><i class="fas fa-calendar-alt"></i> Tenure</label>
                    <select name="tenure" id="loan_tenure" required onchange="calculateEMIPreview()">
                        <option value="12">12 months — 1 year</option>
                        <option value="24">24 months — 2 years</option>
                        <option value="36">36 months — 3 years</option>
                        <option value="48">48 months — 4 years</option>
                        <option value="60">60 months — 5 years</option>
                    </select>
                </div>
                <div id="emiPreview" class="emi-preview">
                    <div class="emi-preview-lbl"><i class="fas fa-calculator"></i> Estimated Monthly EMI</div>
                    <div class="emi-preview-val" id="emiAmount">&#8377;0</div>
                    <div class="emi-preview-note">*Based on 10.5% p.a. interest rate</div>
                </div>
                <div class="fg">
                    <label><i class="fas fa-comment-alt"></i> Purpose of Loan</label>
                    <textarea name="purpose" rows="3" placeholder="Briefly describe the purpose — e.g. Business expansion, Home renovation, Higher education…" required></textarea>
                </div>
                <button type="submit" name="apply_loan" class="modal-submit">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function showLoanModal() {
    document.getElementById('loanModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLoanModal() {
    document.getElementById('loanModal').style.display = 'none';
    document.body.style.overflow = '';
}
function calculateEMIPreview() {
    const amount  = parseFloat(document.getElementById('loan_amount').value);
    const tenure  = parseInt(document.getElementById('loan_tenure').value);
    const preview = document.getElementById('emiPreview');
    const display = document.getElementById('emiAmount');
    if (amount && tenure && amount >= 10000) {
        const r   = 10.5 / 12 / 100;
        const emi = (amount * r * Math.pow(1 + r, tenure)) / (Math.pow(1 + r, tenure) - 1);
        if (isFinite(emi)) {
            display.textContent = '\u20B9' + emi.toLocaleString('en-IN', { maximumFractionDigits: 2 });
            preview.style.display = 'block';
            return;
        }
    }
    preview.style.display = 'none';
}
document.getElementById('loanForm')?.addEventListener('submit', function(e) {
    if (parseFloat(document.getElementById('loan_amount').value) < 10000) {
        e.preventDefault();
        alert('Minimum loan amount is \u20B910,000');
    }
});
window.addEventListener('click', function(e) {
    if (e.target === document.getElementById('loanModal')) closeLoanModal();
});
</script>

<?php include('../includes/footer.php'); ?>