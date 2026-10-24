<?php
// FILE: includes/submit_loan_application.php

// Turn off error display - we'll handle errors ourselves
error_reporting(0);
ini_set('display_errors', 0);

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database config - use correct path
require_once('dbConfig.php');

// Set header for JSON response - THIS MUST BE BEFORE ANY OUTPUT
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check if we have data
if (empty($_POST)) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

// Get POST data
$user_id = intval($_SESSION['user_id']);
$bank_name = isset($_POST['bank_name']) ? mysqli_real_escape_string($conn, $_POST['bank_name']) : '';
$loan_amount = isset($_POST['loan_amount']) ? floatval($_POST['loan_amount']) : 0;
$loan_tenure = isset($_POST['loan_tenure']) ? intval($_POST['loan_tenure']) : 0;
$employment_type = isset($_POST['employment_type']) ? mysqli_real_escape_string($conn, $_POST['employment_type']) : '';
$monthly_income = isset($_POST['monthly_income']) ? floatval($_POST['monthly_income']) : 0;
$date_of_birth = isset($_POST['date_of_birth']) ? mysqli_real_escape_string($conn, $_POST['date_of_birth']) : '';
$gender = isset($_POST['gender']) ? mysqli_real_escape_string($conn, $_POST['gender']) : '';
$aadhar_number = isset($_POST['aadhar_number']) ? mysqli_real_escape_string($conn, $_POST['aadhar_number']) : '';
$pan_number = isset($_POST['pan_number']) ? mysqli_real_escape_string($conn, strtoupper($_POST['pan_number'])) : '';
$address = isset($_POST['address']) ? mysqli_real_escape_string($conn, $_POST['address']) : '';
$city = isset($_POST['city']) ? mysqli_real_escape_string($conn, $_POST['city']) : '';
$pincode = isset($_POST['pincode']) ? mysqli_real_escape_string($conn, $_POST['pincode']) : '';
$company_name = isset($_POST['company_name']) ? mysqli_real_escape_string($conn, $_POST['company_name']) : '';
$work_experience = isset($_POST['work_experience']) ? intval($_POST['work_experience']) : 0;
$current_emi = isset($_POST['current_emi']) ? floatval($_POST['current_emi']) : 0;

// Validate required fields
$errors = [];
if (empty($bank_name)) $errors[] = 'Bank name is required';
if ($loan_amount <= 0) $errors[] = 'Loan amount is required';
if ($loan_tenure <= 0) $errors[] = 'Loan tenure is required';
if (empty($employment_type)) $errors[] = 'Employment type is required';
if ($monthly_income <= 0) $errors[] = 'Monthly income is required';
if (empty($date_of_birth)) $errors[] = 'Date of birth is required';
if (empty($gender)) $errors[] = 'Gender is required';
if (empty($aadhar_number)) $errors[] = 'Aadhar number is required';
if (empty($pan_number)) $errors[] = 'PAN number is required';
if (empty($address)) $errors[] = 'Address is required';
if (empty($city)) $errors[] = 'City is required';
if (empty($pincode)) $errors[] = 'PIN code is required';
if (empty($company_name)) $errors[] = 'Company name is required';
if ($work_experience < 0) $errors[] = 'Work experience is required';
if ($current_emi < 0) $errors[] = 'Current EMI is required';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Validate Aadhar number
if (!preg_match('/^[0-9]{12}$/', $aadhar_number)) {
    echo json_encode(['success' => false, 'message' => 'Invalid Aadhar number (must be 12 digits)']);
    exit;
}

// Validate PAN number
if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $pan_number)) {
    echo json_encode(['success' => false, 'message' => 'Invalid PAN number (format: ABCDE1234F)']);
    exit;
}

// Validate PIN code
if (!preg_match('/^[0-9]{6}$/', $pincode)) {
    echo json_encode(['success' => false, 'message' => 'Invalid PIN code (must be 6 digits)']);
    exit;
}

// Check if table exists, if not create it
$table_check = $conn->query("SHOW TABLES LIKE 'mobile_loan_application'");
if ($table_check->num_rows == 0) {
    $create_table = "CREATE TABLE mobile_loan_application (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        bank_name VARCHAR(100) NOT NULL,
        loan_amount DECIMAL(12,2) NOT NULL,
        loan_tenure INT NOT NULL,
        employment_type VARCHAR(50) NOT NULL,
        monthly_income DECIMAL(12,2) NOT NULL,
        date_of_birth DATE NOT NULL,
        gender VARCHAR(20) NOT NULL,
        aadhar_number VARCHAR(20) NOT NULL,
        pan_number VARCHAR(20) NOT NULL,
        address TEXT NOT NULL,
        city VARCHAR(100) NOT NULL,
        pincode VARCHAR(10) NOT NULL,
        company_name VARCHAR(200) NOT NULL,
        work_experience INT NOT NULL,
        current_emi DECIMAL(12,2) NOT NULL,
        application_status ENUM('pending', 'approved', 'rejected', 'processing') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($create_table)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create table: ' . $conn->error]);
        exit;
    }
}

// Insert into database
$sql = "INSERT INTO mobile_loan_application (
    user_id, bank_name, loan_amount, loan_tenure, employment_type, 
    monthly_income, date_of_birth, gender, aadhar_number, pan_number, 
    address, city, pincode, company_name, work_experience, current_emi,
    application_status
) VALUES (
    ?, ?, ?, ?, ?, 
    ?, ?, ?, ?, ?, 
    ?, ?, ?, ?, ?, ?,
    'pending'
)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database prepare failed']);
    exit;
}

mysqli_stmt_bind_param(
    $stmt,
    'isidssissssssidd',
    $user_id, $bank_name, $loan_amount, $loan_tenure, $employment_type,
    $monthly_income, $date_of_birth, $gender, $aadhar_number, $pan_number,
    $address, $city, $pincode, $company_name, $work_experience, $current_emi
);

if (mysqli_stmt_execute($stmt)) {
    $application_id = mysqli_insert_id($conn);
    echo json_encode([
        'success' => true,
        'message' => 'Application submitted successfully',
        'application_id' => $application_id
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . mysqli_stmt_error($stmt)
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>