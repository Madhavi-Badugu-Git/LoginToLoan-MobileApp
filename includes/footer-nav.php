<?php
// FILE: includes/footer-nav.php
// Bottom Navigation component - Include this at the bottom of your pages

// Determine current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- ===== BOTTOM NAVIGATION ===== -->
<div class="bottom-nav">
    <a href="https://localhost/mobile.login-to-loan/user/dashboard.php" class="nav-item <?php echo $current_page == 'admin-dashboard.php' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
   
    <a href="https://localhost/mobile.login-to-loan/resources/blog.php" class="nav-item <?php echo $current_page == 'blog.php' ? 'active' : ''; ?>">
        <i class="fas fa-blog"></i>
        <span>Blogs</span>
    </a>
    <a href="https://localhost/mobile.login-to-loan/credit-score.php" class="nav-item <?php echo $current_page == 'credit-score.php' ? 'active' : ''; ?>">
        <i class="fas fa-chart-line"></i>
        <span>Credit Score</span>
    </a>
   <a href="https://localhost/mobile.login-to-loan/pages/calculators/Expenses-Calculator/expenses-calculator.php" class="nav-item <?php echo $current_page == 'expenses-calculator.php' ? 'active' : ''; ?>">
    <i class="fas fa-wallet"></i>
    <span>Expenses</span>
</a>
    <a href="https://localhost/mobile.login-to-loan/calculator.php" class="nav-item <?php echo $current_page == 'calculator.php' ? 'active' : ''; ?>">
        <i class="fas fa-calculator"></i>
        <span>Calculator</span>
    </a>
</div>

<!-- Close app-container div and body/html tags -->
</div>
</body>
</html>