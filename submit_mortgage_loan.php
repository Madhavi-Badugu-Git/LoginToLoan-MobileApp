<?php
// FILE: submit_mortgage_loan.php

// Turn off error display
error_reporting(0);
ini_set('display_errors', 0);

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database config
require_once('includes/dbConfig.php');

// Set header for JSON response
header('Content-Type: application/json');

// Function to send JSON response
function sendJsonResponse($success, $message, $data = null) {
    $response = ['success' => $success, 'message' => $message];
    if ($data !== null) {
        $response = array_merge($response, $data);
    }
    echo json_encode($response);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    sendJsonResponse(false, 'Please login first');
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

// Check if we have data
if (empty($_POST)) {
    sendJsonResponse(false, 'No data received');
}

// Get POST data
$user_id = intval($_SESSION['user_id']);
$full_name = isset($_POST['full_name']) ? mysqli_real_escape_string($conn, $_POST['full_name']) : '';
$email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
$mobile_number = isset($_POST['mobile_number']) ? mysqli_real_escape_string($conn, $_POST['mobile_number']) : '';

$loan_type = isset($_POST['loan_type']) ? mysqli_real_escape_string($conn, $_POST['loan_type']) : 'Mortgage Loan';
$property_type = isset($_POST['property_type']) ? mysqli_real_escape_string($conn, $_POST['property_type']) : '';
$property_value = isset($_POST['property_value']) ? floatval($_POST['property_value']) : 0;
$loan_amount = isset($_POST['loan_amount']) ? floatval($_POST['loan_amount']) : 0;
$down_payment = isset($_POST['down_payment']) ? floatval($_POST['down_payment']) : 0;
$property_address = isset($_POST['property_address']) ? mysqli_real_escape_string($conn, $_POST['property_address']) : '';
$property_city = isset($_POST['property_city']) ? mysqli_real_escape_string($conn, $_POST['property_city']) : '';
$property_pincode = isset($_POST['property_pincode']) ? mysqli_real_escape_string($conn, $_POST['property_pincode']) : '';
$loan_tenure = isset($_POST['loan_tenure']) ? intval($_POST['loan_tenure']) : 0;
$loan_purpose = isset($_POST['loan_purpose']) ? mysqli_real_escape_string($conn, $_POST['loan_purpose']) : '';
$date_of_birth = isset($_POST['dob']) ? mysqli_real_escape_string($conn, $_POST['dob']) : '';
$gender = isset($_POST['gender']) ? mysqli_real_escape_string($conn, $_POST['gender']) : '';
$employment_type = isset($_POST['employment_type']) ? mysqli_real_escape_string($conn, $_POST['employment_type']) : '';
$company_name = isset($_POST['company_name']) ? mysqli_real_escape_string($conn, $_POST['company_name']) : '';
$monthly_income = isset($_POST['monthly_income']) ? floatval($_POST['monthly_income']) : 0;
$existing_loans = isset($_POST['existing_loans']) ? floatval($_POST['existing_loans']) : 0;
$work_experience = isset($_POST['work_experience']) ? intval($_POST['work_experience']) : 0;
$current_emi = isset($_POST['current_emi']) ? floatval($_POST['current_emi']) : 0;
$aadhar_number = isset($_POST['aadhar_number']) ? mysqli_real_escape_string($conn, $_POST['aadhar_number']) : '';
$pan_number = isset($_POST['pan_number']) ? mysqli_real_escape_string($conn, strtoupper($_POST['pan_number'])) : '';
$current_address = isset($_POST['current_address']) ? mysqli_real_escape_string($conn, $_POST['current_address']) : '';
$current_city = isset($_POST['current_city']) ? mysqli_real_escape_string($conn, $_POST['current_city']) : '';
$current_pincode = isset($_POST['current_pincode']) ? mysqli_real_escape_string($conn, $_POST['current_pincode']) : '';

// Validate required fields
$errors = [];
if (empty($full_name)) $errors[] = 'Full name is required';
if (empty($email)) $errors[] = 'Email address is required';
if (empty($mobile_number)) $errors[] = 'Mobile number is required';
if (empty($property_type)) $errors[] = 'Property type is required';
if ($property_value <= 0) $errors[] = 'Property value is required';
if ($loan_amount <= 0) $errors[] = 'Loan amount is required';
if (empty($property_address)) $errors[] = 'Property address is required';
if (empty($property_city)) $errors[] = 'Property city is required';
if (empty($property_pincode)) $errors[] = 'Property PIN code is required';
if ($loan_tenure <= 0) $errors[] = 'Loan tenure is required';
if (empty($loan_purpose)) $errors[] = 'Loan purpose is required';
if (empty($date_of_birth)) $errors[] = 'Date of birth is required';
if (empty($gender)) $errors[] = 'Gender is required';
if (empty($employment_type)) $errors[] = 'Employment type is required';
if (empty($company_name)) $errors[] = 'Company name is required';
if ($monthly_income <= 0) $errors[] = 'Monthly income is required';
if ($work_experience < 0) $errors[] = 'Work experience is required';
if (empty($aadhar_number)) $errors[] = 'Aadhar number is required';
if (empty($pan_number)) $errors[] = 'PAN number is required';
if (empty($current_address)) $errors[] = 'Current address is required';
if (empty($current_city)) $errors[] = 'Current city is required';
if (empty($current_pincode)) $errors[] = 'Current PIN code is required';

if (!empty($errors)) {
    sendJsonResponse(false, implode(', ', $errors));
}

// Validate Aadhar number (12 digits)
if (!preg_match('/^[0-9]{12}$/', $aadhar_number)) {
    sendJsonResponse(false, 'Invalid Aadhar number (must be 12 digits)');
}

// Validate PAN number (format: ABCDE1234F)
if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $pan_number)) {
    sendJsonResponse(false, 'Invalid PAN number (format: ABCDE1234F)');
}

// Validate PIN codes (6 digits)
if (!preg_match('/^[0-9]{6}$/', $property_pincode)) {
    sendJsonResponse(false, 'Invalid property PIN code (must be 6 digits)');
}
if (!preg_match('/^[0-9]{6}$/', $current_pincode)) {
    sendJsonResponse(false, 'Invalid current PIN code (must be 6 digits)');
}

// Validate mobile number (10 digits)
if (!preg_match('/^[0-9]{10}$/', $mobile_number)) {
    sendJsonResponse(false, 'Invalid mobile number (must be 10 digits)');
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonResponse(false, 'Invalid email address');
}

// Check if table exists, if not create it
$table_check = $conn->query("SHOW TABLES LIKE 'mobile_mortgage_applications'");
if ($table_check->num_rows == 0) {
    $create_table = "CREATE TABLE mobile_mortgage_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        loan_type VARCHAR(100) NOT NULL,
        property_type VARCHAR(50) NOT NULL,
        property_value DECIMAL(12,2) NOT NULL,
        loan_amount DECIMAL(12,2) NOT NULL,
        down_payment DECIMAL(12,2) DEFAULT 0,
        property_address TEXT NOT NULL,
        property_city VARCHAR(100) NOT NULL,
        property_pincode VARCHAR(10) NOT NULL,
        loan_tenure INT NOT NULL,
        loan_purpose VARCHAR(100) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        mobile_number VARCHAR(15) NOT NULL,
        email VARCHAR(100) NOT NULL,
        date_of_birth DATE NOT NULL,
        gender VARCHAR(20) NOT NULL,
        employment_type VARCHAR(50) NOT NULL,
        company_name VARCHAR(200) NOT NULL,
        monthly_income DECIMAL(12,2) NOT NULL,
        existing_loans DECIMAL(12,2) DEFAULT 0,
        work_experience INT NOT NULL,
        current_emi DECIMAL(12,2) DEFAULT 0,
        aadhar_number VARCHAR(20) NOT NULL,
        pan_number VARCHAR(20) NOT NULL,
        current_address TEXT NOT NULL,
        current_city VARCHAR(100) NOT NULL,
        current_pincode VARCHAR(10) NOT NULL,
        application_status ENUM('pending', 'approved', 'rejected', 'processing') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($create_table)) {
        sendJsonResponse(false, 'Failed to create table: ' . $conn->error);
    }
}

// Insert into database
$sql = "INSERT INTO mobile_mortgage_applications (
    user_id, loan_type, property_type, property_value, loan_amount, 
    down_payment, property_address, property_city, property_pincode, 
    loan_tenure, loan_purpose, full_name, mobile_number, email, 
    date_of_birth, gender, employment_type, company_name, monthly_income, 
    existing_loans, work_experience, current_emi, aadhar_number, pan_number, 
    current_address, current_city, current_pincode, application_status
) VALUES (
    ?, ?, ?, ?, ?, 
    ?, ?, ?, ?, 
    ?, ?, ?, ?, ?, 
    ?, ?, ?, ?, ?, 
    ?, ?, ?, ?, ?, 
    ?, ?, ?, 'pending'
)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    sendJsonResponse(false, 'Database prepare failed: ' . $conn->error);
}

mysqli_stmt_bind_param(
    $stmt,
    'iisddsssissssssssddississss',
    $user_id, $loan_type, $property_type, $property_value, $loan_amount,
    $down_payment, $property_address, $property_city, $property_pincode,
    $loan_tenure, $loan_purpose, $full_name, $mobile_number, $email,
    $date_of_birth, $gender, $employment_type, $company_name, $monthly_income,
    $existing_loans, $work_experience, $current_emi, $aadhar_number, $pan_number,
    $current_address, $current_city, $current_pincode
);

if (mysqli_stmt_execute($stmt)) {
    $application_id = mysqli_insert_id($conn);
    sendJsonResponse(true, 'Application submitted successfully', ['application_id' => $application_id]);
} else {
    sendJsonResponse(false, 'Database error: ' . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>