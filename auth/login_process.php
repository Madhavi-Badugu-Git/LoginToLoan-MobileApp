<?php
// FILE: auth/login_process.php
include('../includes/dbConfig.php');

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = '$email' AND status = 'active'";
    $result = $conn->query($sql);
    
    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        if($password == $user['password']){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['profile_image'] = $user['profile_image'];
            $_SESSION['success_message'] = "Welcome back, " . $user['fullname'] . "!";
            
            if($user['role'] == 'admin'){
                redirect('admin/admin-dashboard.php');
            } else {
                redirect('user/dashboard.php');
            }
        } else {
            $_SESSION['error_message'] = "Invalid password!";
            redirect('login.php');
        }
    } else {
        $_SESSION['error_message'] = "Email not found or account inactive!";
        redirect('login.php');
    }
} else {
    redirect('login.php');
}
?>