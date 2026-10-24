<?php
// FILE: user/portfolio.php
include('../includes/dbConfig.php');
include('../includes/navbar.php');

if(!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Get portfolio summary
$portfolio = $conn->query("SELECT 
    COUNT(*) as total_investments,
    SUM(amount) as total_invested,
    SUM(expected_returns) as total_expected,
    AVG(ip.interest_rate) as avg_returns
    FROM user_investments ui 
    JOIN investment_plans ip ON ui.plan_id = ip.id 
    WHERE ui.user_id = $user_id AND ui.status = 'active'")->fetch_assoc();

$profit = ($portfolio['total_expected'] ?? 0) - ($portfolio['total_invested'] ?? 0);
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
    --teal:      #14b8a6;
    --grad:      linear-gradient(135deg, #1a4fdb 0%, #17b26a 100%);
    --grad-gold: linear-gradient(135deg, #f59e0b 0%, #fcd34d 100%);
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

.pf-wrap { max-width: 1360px; margin: 0 auto; padding: 32px 28px 60px; }

/* Hero Section */
.pf-hero {
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
.pf-hero::before {
    content: '';
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 60% 80% at 85% 40%, rgba(26,79,219,.28) 0%, transparent 65%),
        radial-gradient(ellipse 40% 55% at 5% 90%,  rgba(23,178,106,.18)  0%, transparent 60%);
    pointer-events: none;
}
.pf-hero::after {
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
.pf-hero h1 { font-size: 2rem; font-weight: 800; color: #fff; letter-spacing: -.5px; line-height: 1.15; }
.pf-hero h1 span { background: linear-gradient(90deg, #6ee7b7, #60a5fa); -webkit-background-clip: text; background-clip: text; color: transparent; }
.pf-hero p { margin-top: 8px; font-size: .875rem; color: rgba(255,255,255,.42); }
.hero-right { position: relative; z-index: 1; flex-shrink: 0; }
.hero-stats {
    display: flex;
    gap: 28px;
    background: rgba(255,255,255,.05);
    backdrop-filter: blur(4px);
    border-radius: 48px;
    padding: 12px 24px;
    border: 1px solid rgba(255,255,255,.08);
}
.hero-stat-item { text-align: center; }
.hero-stat-val { font-size: 1.3rem; font-weight: 800; color: #fff; font-family: var(--mono); }
.hero-stat-lbl { font-size: .65rem; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: .5px; }

/* Summary Cards */
.summary-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
.sum-card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); box-shadow: var(--shadow-sm);
    padding: 20px 18px 16px; position: relative; overflow: hidden; transition: var(--t);
}
.sum-card:hover { transform: translateY(-3px); box-shadow: var(--shadow); border-color: transparent; }
.sum-card-bar { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: var(--radius) var(--radius) 0 0; }
.sum-card:nth-child(1) .sum-card-bar { background: linear-gradient(90deg, #1a4fdb, #60a5fa); }
.sum-card:nth-child(2) .sum-card-bar { background: linear-gradient(90deg, #14b8a6, #5eead4); }
.sum-card:nth-child(3) .sum-card-bar { background: linear-gradient(90deg, #17b26a, #4ade80); }
.sum-card:nth-child(4) .sum-card-bar { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
.sum-icon { width: 40px; height: 40px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: .95rem; margin-bottom: 14px; }
.sum-card:nth-child(1) .sum-icon { background: rgba(26,79,219,.1);  color: var(--blue);  }
.sum-card:nth-child(2) .sum-icon { background: rgba(20,184,166,.1); color: var(--teal); }
.sum-card:nth-child(3) .sum-icon { background: rgba(23,178,106,.1); color: var(--green); }
.sum-card:nth-child(4) .sum-icon { background: rgba(245,158,11,.1); color: var(--amber); }
.sum-val   { font-size: 1.5rem; font-weight: 800; color: var(--ink); letter-spacing: -1px; line-height: 1; margin-bottom: 4px; font-family: var(--mono); }
.sum-label { font-size: .75rem; font-weight: 600; color: var(--ink-soft); }
.sum-sub   { font-size: .65rem; color: var(--muted); margin-top: 4px; display: flex; align-items: center; gap: 4px; }
.sum-sub i { font-size: .55rem; }

/* 2-col grid */
.pf-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 28px; }

/* Cards */
.pf-card {
    background: var(--card); border-radius: var(--radius-lg);
    border: 1px solid var(--border); box-shadow: var(--shadow-sm);
    overflow: hidden; transition: var(--t);
}
.pf-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); border-color: transparent; }
.pf-card-header {
    padding: 18px 24px 12px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 10px;
}
.pf-card-header i { font-size: 1.2rem; color: var(--blue); background: rgba(26,79,219,.08); padding: 8px; border-radius: 12px; }
.pf-card-header h3 { font-size: 1rem; font-weight: 700; color: var(--ink); margin: 0; }
.pf-card-body { padding: 20px 24px 24px; }

/* Allocation items */
.allocation-item { margin-bottom: 18px; }
.allocation-label { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: .8rem; }
.allocation-name { font-weight: 600; color: var(--ink-soft); display: flex; align-items: center; gap: 8px; }
.allocation-name i { font-size: .7rem; color: var(--blue); }
.allocation-percent { font-weight: 700; color: var(--ink); font-family: var(--mono); }
.allocation-bar { background: #e9eef3; height: 8px; border-radius: 10px; overflow: hidden; }
.allocation-fill { height: 100%; border-radius: 10px; transition: width .5s ease; }
.fill-blue { background: linear-gradient(90deg, #1a4fdb, #60a5fa); }
.fill-teal { background: linear-gradient(90deg, #14b8a6, #5eead4); }
.fill-amber { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
.fill-green { background: linear-gradient(90deg, #17b26a, #4ade80); }

/* Metrics rows */
.metrics-list { display: flex; flex-direction: column; gap: 12px; }
.metric-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; border-bottom: 1px solid var(--border);
}
.metric-row:last-child { border-bottom: none; }
.metric-label { display: flex; align-items: center; gap: 10px; font-size: .8rem; color: var(--ink-soft); }
.metric-label i { width: 22px; color: var(--blue); font-size: .8rem; }
.metric-value { font-weight: 700; font-size: .9rem; color: var(--ink); font-family: var(--mono); }
.metric-value.positive { color: var(--green); }
.metric-value.highlight { color: var(--blue); font-size: 1rem; }

/* Health Score Circle */
.health-circle { text-align: center; margin-bottom: 20px; }
.circle-container { position: relative; width: 160px; height: 160px; margin: 0 auto 16px; }
.circle-svg { width: 100%; height: 100%; transform: rotate(-90deg); }
.circle-bg { fill: none; stroke: #e9eef3; stroke-width: 12; }
.circle-fill { fill: none; stroke: url(#healthGrad); stroke-width: 12; stroke-linecap: round; stroke-dasharray: 440; stroke-dashoffset: 110; transition: stroke-dashoffset 1s ease; }
.circle-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }
.circle-percent { font-size: 1.8rem; font-weight: 800; color: var(--blue); font-family: var(--mono); }
.circle-label { font-size: .65rem; color: var(--muted); }
.health-note { font-size: .75rem; color: var(--muted); text-align: center; }
.progress-track { margin-top: 16px; }
.progress-track .progress-label { display: flex; justify-content: space-between; margin-bottom: 6px; font-size: .7rem; color: var(--ink-soft); }
.progress-bar-bg { background: #e9eef3; height: 6px; border-radius: 10px; overflow: hidden; }
.progress-bar-fill { width: 75%; height: 100%; background: linear-gradient(90deg, #1a4fdb, #17b26a); border-radius: 10px; }

/* Risk Assessment */
.risk-meter { margin-bottom: 16px; }
.risk-level-badges { display: flex; gap: 10px; margin-top: 20px; }
.risk-badge { flex: 1; text-align: center; padding: 8px 4px; border-radius: 10px; font-size: .7rem; font-weight: 700; }
.risk-low { background: #dcfce7; color: #15803d; }
.risk-medium { background: #fef3c7; color: #92400e; }
.risk-high { background: #fee2e2; color: #b91c1c; }

/* Tips List */
.tips-list { list-style: none; padding: 0; margin: 0; }
.tips-list li { display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); font-size: .8rem; color: var(--ink-soft); }
.tips-list li:last-child { border-bottom: none; }
.tips-list li i { width: 28px; color: var(--green); font-size: .9rem; background: rgba(23,178,106,.1); padding: 6px; border-radius: 10px; text-align: center; }

/* Empty State */
.empty-state { background: var(--card); border-radius: var(--radius-lg); border: 2px dashed var(--border); padding: 60px 40px; text-align: center; margin: 20px 0; }
.empty-icon { width: 70px; height: 70px; border-radius: 20px; background: var(--surface); border: 2px dashed var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; font-size: 1.6rem; color: var(--muted); }
.empty-state h3 { font-size: 1.2rem; font-weight: 700; color: var(--ink); margin-bottom: 8px; }
.empty-state p { font-size: .85rem; color: var(--muted); margin-bottom: 20px; }
.empty-btn { display: inline-flex; align-items: center; gap: 8px; background: var(--ink); color: #fff; padding: 12px 24px; border-radius: 40px; font-size: .8rem; font-weight: 600; text-decoration: none; transition: var(--t); }
.empty-btn:hover { background: var(--blue); transform: translateY(-2px); }

/* Animations */
@keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
.pf-hero { animation: fadeUp .38s ease both; }
.summary-row { animation: fadeUp .38s .1s ease both; }
.pf-grid-2 { animation: fadeUp .38s .18s ease both; }
.pf-card:last-of-type { animation: fadeUp .38s .26s ease both; }

/* Responsive */
@media (max-width: 1100px) {
    .summary-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 900px) {
    .pf-grid-2 { grid-template-columns: 1fr; }
}
@media (max-width: 768px) {
    .pf-wrap { padding: 16px 16px 48px; }
    .pf-hero { flex-direction: column; align-items: flex-start; padding: 28px 24px; }
    .pf-hero h1 { font-size: 1.5rem; }
    .hero-stats { width: 100%; justify-content: space-around; padding: 10px 16px; }
    .hero-stat-val { font-size: 1rem; }
    .summary-row { grid-template-columns: 1fr; gap: 12px; }
    .pf-card-header { padding: 14px 18px; }
    .pf-card-body { padding: 16px 18px; }
    .circle-container { width: 130px; height: 130px; }
    .circle-percent { font-size: 1.4rem; }
}
@media (max-width: 480px) {
    .risk-level-badges { flex-direction: column; gap: 8px; }
}
</style>

<div class="pf-wrap">

    <!-- Hero Section -->
    <div class="pf-hero">
        <div class="hero-left">
            <div class="hero-eyebrow"><i class="fas fa-chart-pie"></i> Portfolio Overview</div>
            <h1>My <span>Investment</span> Portfolio</h1>
            <p>Track performance, monitor returns, and optimize your financial growth.</p>
        </div>
        <div class="hero-right">
            <div class="hero-stats">
                <div class="hero-stat-item">
                    <div class="hero-stat-val"><?php echo $portfolio['total_investments'] ?? 0; ?></div>
                    <div class="hero-stat-lbl">Active Plans</div>
                </div>
                <div class="hero-stat-item">
                    <div class="hero-stat-val"><?php echo number_format(($portfolio['avg_returns'] ?? 0), 1); ?>%</div>
                    <div class="hero-stat-lbl">Avg. ROI p.a.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-row">
        <div class="sum-card">
            <div class="sum-card-bar"></div>
            <div class="sum-icon"><i class="fas fa-chart-line"></i></div>
            <div class="sum-val">₹<?php echo number_format($portfolio['total_invested'] ?? 0, 2); ?></div>
            <div class="sum-label">Total Invested</div>
            <div class="sum-sub"><i class="fas fa-circle"></i> Active Portfolio</div>
        </div>
        <div class="sum-card">
            <div class="sum-card-bar"></div>
            <div class="sum-icon"><i class="fas fa-chart-bar"></i></div>
            <div class="sum-val">₹<?php echo number_format($portfolio['total_expected'] ?? 0, 2); ?></div>
            <div class="sum-label">Expected Value</div>
            <div class="sum-sub"><i class="fas fa-calendar-alt"></i> At Maturity</div>
        </div>
        <div class="sum-card">
            <div class="sum-card-bar"></div>
            <div class="sum-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="sum-val" style="color: var(--green);">₹<?php echo number_format($profit, 2); ?></div>
            <div class="sum-label">Expected Profit</div>
            <div class="sum-sub"><i class="fas fa-trophy"></i> Potential Earnings</div>
        </div>
        <div class="sum-card">
            <div class="sum-card-bar"></div>
            <div class="sum-icon"><i class="fas fa-percent"></i></div>
            <div class="sum-val"><?php echo number_format($portfolio['avg_returns'] ?? 0, 1); ?>%</div>
            <div class="sum-label">Average Returns</div>
            <div class="sum-sub"><i class="fas fa-fire"></i> Above Market</div>
        </div>
    </div>

    <?php 
    // Check if user has any active investments
    $has_investments = ($portfolio['total_investments'] ?? 0) > 0;
    
    if($has_investments):
    ?>
    
    <!-- 2 Column Grid -->
    <div class="pf-grid-2">
        <!-- Allocation Chart Card -->
        <div class="pf-card">
            <div class="pf-card-header">
                <i class="fas fa-chart-pie"></i>
                <h3>Portfolio Allocation</h3>
            </div>
            <div class="pf-card-body">
                <?php 
                // Get allocation by plan type
                $allocation = $conn->query("SELECT 
                    ip.plan_name,
                    ip.id,
                    SUM(ui.amount) as total_amount,
                    (SUM(ui.amount) / NULLIF((SELECT SUM(amount) FROM user_investments WHERE user_id = $user_id AND status = 'active'), 0) * 100) as percentage
                    FROM user_investments ui 
                    JOIN investment_plans ip ON ui.plan_id = ip.id 
                    WHERE ui.user_id = $user_id AND ui.status = 'active'
                    GROUP BY ip.plan_name, ip.id
                    ORDER BY total_amount DESC");
                
                $color_map = ['fill-blue', 'fill-teal', 'fill-amber', 'fill-green'];
                $idx = 0;
                while($item = $allocation->fetch_assoc()):
                    $color_class = $color_map[$idx % count($color_map)];
                ?>
                <div class="allocation-item">
                    <div class="allocation-label">
                        <span class="allocation-name"><i class="fas fa-chart-simple"></i> <?php echo htmlspecialchars($item['plan_name']); ?></span>
                        <span class="allocation-percent"><?php echo number_format($item['percentage'], 1); ?>%</span>
                    </div>
                    <div class="allocation-bar">
                        <div class="allocation-fill <?php echo $color_class; ?>" style="width: <?php echo $item['percentage']; ?>%"></div>
                    </div>
                </div>
                <?php 
                    $idx++;
                endwhile; 
                ?>
            </div>
        </div>
        
        <!-- Performance Metrics Card -->
        <div class="pf-card">
            <div class="pf-card-header">
                <i class="fas fa-chart-line"></i>
                <h3>Performance Metrics</h3>
            </div>
            <div class="pf-card-body">
                <div class="metrics-list">
                    <div class="metric-row">
                        <span class="metric-label"><i class="fas fa-briefcase"></i> Active Investments</span>
                        <span class="metric-value highlight"><?php echo $portfolio['total_investments'] ?? 0; ?></span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label"><i class="fas fa-rupee-sign"></i> Total Invested</span>
                        <span class="metric-value">₹<?php echo number_format($portfolio['total_invested'] ?? 0, 2); ?></span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label"><i class="fas fa-chart-line"></i> Expected Returns</span>
                        <span class="metric-value positive">₹<?php echo number_format($portfolio['total_expected'] ?? 0, 2); ?></span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label"><i class="fas fa-percent"></i> Average ROI</span>
                        <span class="metric-value positive"><?php echo number_format($portfolio['avg_returns'] ?? 0, 1); ?>% p.a.</span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label"><i class="fas fa-trophy"></i> Profit Projection</span>
                        <span class="metric-value positive">₹<?php echo number_format($profit, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Second Grid: Health & Risk -->
    <div class="pf-grid-2">
        <!-- Portfolio Health Card -->
        <div class="pf-card">
            <div class="pf-card-header">
                <i class="fas fa-heartbeat"></i>
                <h3>Portfolio Health</h3>
            </div>
            <div class="pf-card-body">
                <div class="health-circle">
                    <div class="circle-container">
                        <svg class="circle-svg" viewBox="0 0 180 180">
                            <defs>
                                <linearGradient id="healthGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#1a4fdb"/>
                                    <stop offset="100%" style="stop-color:#17b26a"/>
                                </linearGradient>
                            </defs>
                            <circle class="circle-bg" cx="90" cy="90" r="70"></circle>
                            <circle class="circle-fill" cx="90" cy="90" r="70" 
                                    stroke-dasharray="440" 
                                    stroke-dashoffset="110"></circle>
                        </svg>
                        <div class="circle-text">
                            <div class="circle-percent">75%</div>
                            <div class="circle-label">Health Score</div>
                        </div>
                    </div>
                    <div class="health-note">Your portfolio is on track! Keep investing to reach your goals.</div>
                </div>
                <div class="progress-track">
                    <div class="progress-label">
                        <span>Portfolio Growth</span>
                        <span>75% towards target</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Risk Assessment Card -->
        <div class="pf-card">
            <div class="pf-card-header">
                <i class="fas fa-shield-alt"></i>
                <h3>Risk Assessment</h3>
            </div>
            <div class="pf-card-body">
                <?php 
                // Calculate risk distribution
                $risk_distribution = $conn->query("SELECT 
                    ip.risk_level,
                    COUNT(*) as count,
                    SUM(ui.amount) as total_amount
                    FROM user_investments ui 
                    JOIN investment_plans ip ON ui.plan_id = ip.id 
                    WHERE ui.user_id = $user_id AND ui.status = 'active'
                    GROUP BY ip.risk_level");
                
                $risk_data = ['Low' => 0, 'Medium' => 0, 'High' => 0];
                $total_risk_amount = 0;
                while($risk = $risk_distribution->fetch_assoc()) {
                    $risk_data[$risk['risk_level']] = $risk['total_amount'];
                    $total_risk_amount += $risk['total_amount'];
                }
                ?>
                
                <div class="risk-meter">
                    <div class="allocation-item">
                        <div class="allocation-label">
                            <span class="allocation-name"><i class="fas fa-shield-alt"></i> Low Risk</span>
                            <span class="allocation-percent"><?php echo $total_risk_amount > 0 ? number_format(($risk_data['Low'] / $total_risk_amount) * 100, 1) : 0; ?>%</span>
                        </div>
                        <div class="allocation-bar">
                            <div class="allocation-fill fill-green" style="width: <?php echo $total_risk_amount > 0 ? ($risk_data['Low'] / $total_risk_amount) * 100 : 0; ?>%;"></div>
                        </div>
                    </div>
                    <div class="allocation-item">
                        <div class="allocation-label">
                            <span class="allocation-name"><i class="fas fa-chart-line"></i> Medium Risk</span>
                            <span class="allocation-percent"><?php echo $total_risk_amount > 0 ? number_format(($risk_data['Medium'] / $total_risk_amount) * 100, 1) : 0; ?>%</span>
                        </div>
                        <div class="allocation-bar">
                            <div class="allocation-fill fill-amber" style="width: <?php echo $total_risk_amount > 0 ? ($risk_data['Medium'] / $total_risk_amount) * 100 : 0; ?>%;"></div>
                        </div>
                    </div>
                    <div class="allocation-item">
                        <div class="allocation-label">
                            <span class="allocation-name"><i class="fas fa-chart-line"></i> High Risk</span>
                            <span class="allocation-percent"><?php echo $total_risk_amount > 0 ? number_format(($risk_data['High'] / $total_risk_amount) * 100, 1) : 0; ?>%</span>
                        </div>
                        <div class="allocation-bar">
                            <div class="allocation-fill fill-amber" style="width: <?php echo $total_risk_amount > 0 ? ($risk_data['High'] / $total_risk_amount) * 100 : 0; ?>%; background: #ef4444;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="risk-level-badges">
                    <div class="risk-badge risk-low">🟢 Low Risk</div>
                    <div class="risk-badge risk-medium">🟡 Medium Risk</div>
                    <div class="risk-badge risk-high">🔴 High Risk</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Investment Tips Card -->
    <div class="pf-card" style="margin-top: 0;">
        <div class="pf-card-header">
            <i class="fas fa-lightbulb"></i>
            <h3>Investment Tips & Insights</h3>
        </div>
        <div class="pf-card-body">
            <ul class="tips-list">
                <li><i class="fas fa-chart-line"></i> Diversify your portfolio across different risk levels for balanced growth.</li>
                <li><i class="fas fa-clock"></i> Long-term investments typically yield higher returns with compounding benefits.</li>
                <li><i class="fas fa-shield-alt"></i> Review your portfolio regularly to ensure it aligns with your financial goals.</li>
                <li><i class="fas fa-rocket"></i> Consider increasing your investment amount periodically to maximize returns.</li>
                <li><i class="fas fa-book"></i> Stay informed about market trends and economic indicators.</li>
            </ul>
        </div>
    </div>
    
    <?php else: ?>
    
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-icon"><i class="fas fa-chart-pie"></i></div>
        <h3>No Active Investments Yet</h3>
        <p>Start your investment journey today and watch your wealth grow with our high-return plans.</p>
        <a href="investment-plans.php" class="empty-btn">
            <i class="fas fa-arrow-right"></i> Explore Investment Plans
        </a>
    </div>
    
    <?php endif; ?>

</div>

<?php include('../includes/footer.php'); ?>