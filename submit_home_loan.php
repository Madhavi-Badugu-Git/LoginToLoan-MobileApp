<?php
// FILE: submit_home_loan.php (Root level - same folder as home-loan.php)

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database config from includes folder
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
$bank_name = isset($_POST['bank_name']) ? trim($_POST['bank_name']) : '';
$loan_amount = isset($_POST['loan_amount']) ? floatval($_POST['loan_amount']) : 0;
$loan_tenure = isset($_POST['loan_tenure']) ? intval($_POST['loan_tenure']) : 0;
$employment_type = isset($_POST['employment_type']) ? trim($_POST['employment_type']) : '';
$monthly_income = isset($_POST['monthly_income']) ? floatval($_POST['monthly_income']) : 0;
$date_of_birth = isset($_POST['date_of_birth']) ? trim($_POST['date_of_birth']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
$aadhar_number = isset($_POST['aadhar_number']) ? trim($_POST['aadhar_number']) : '';
$pan_number = isset($_POST['pan_number']) ? strtoupper(trim($_POST['pan_number'])) : '';
$address = isset($_POST['address']) ? trim($_POST['address']) : '';
$city = isset($_POST['city']) ? trim($_POST['city']) : '';
$pincode = isset($_POST['pincode']) ? trim($_POST['pincode']) : '';
$property_address = isset($_POST['property_address']) ? trim($_POST['property_address']) : '';
$property_city = isset($_POST['property_city']) ? trim($_POST['property_city']) : '';
$property_pincode = isset($_POST['property_pincode']) ? trim($_POST['property_pincode']) : '';

// Debug log
error_log("Received data: bank_name=$bank_name, user_id=$user_id");

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
if (empty($property_address)) $errors[] = 'Property address is required';
if (empty($property_city)) $errors[] = 'Property city is required';
if (empty($property_pincode)) $errors[] = 'Property PIN code is required';

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
if (!preg_match('/^[0-9]{6}$/', $pincode)) {
    sendJsonResponse(false, 'Invalid PIN code (must be 6 digits)');
}
if (!preg_match('/^[0-9]{6}$/', $property_pincode)) {
    sendJsonResponse(false, 'Invalid property PIN code (must be 6 digits)');
}

// Escape strings for database
$bank_name = mysqli_real_escape_string($conn, $bank_name);
$employment_type = mysqli_real_escape_string($conn, $employment_type);
$date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
$gender = mysqli_real_escape_string($conn, $gender);
$aadhar_number = mysqli_real_escape_string($conn, $aadhar_number);
$pan_number = mysqli_real_escape_string($conn, $pan_number);
$address = mysqli_real_escape_string($conn, $address);
$city = mysqli_real_escape_string($conn, $city);
$pincode = mysqli_real_escape_string($conn, $pincode);
$property_address = mysqli_real_escape_string($conn, $property_address);
$property_city = mysqli_real_escape_string($conn, $property_city);
$property_pincode = mysqli_real_escape_string($conn, $property_pincode);

// Check if table exists, if not create it
$table_check = $conn->query("SHOW TABLES LIKE 'mobile_home_loan_applications'");
if ($table_check->num_rows == 0) {
    $create_table = "CREATE TABLE mobile_home_loan_applications (
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
        property_address TEXT NOT NULL,
        property_city VARCHAR(100) NOT NULL,
        property_pincode VARCHAR(10) NOT NULL,
        application_status ENUM('pending', 'approved', 'rejected', 'processing') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($create_table)) {
        sendJsonResponse(false, 'Failed to create table: ' . $conn->error);
    }
}

// Insert into database
$sql = "INSERT INTO mobile_home_loan_applications (
    user_id, bank_name, loan_amount, loan_tenure, employment_type, 
    monthly_income, date_of_birth, gender, aadhar_number, pan_number, 
    address, city, pincode, property_address, property_city, property_pincode,
    application_status
) VALUES (
    ?, ?, ?, ?, ?, 
    ?, ?, ?, ?, ?, 
    ?, ?, ?, ?, ?, ?,
    'pending'
)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    sendJsonResponse(false, 'Database prepare failed: ' . $conn->error);
}

mysqli_stmt_bind_param(
    $stmt,
    'isidssisssssssss',
    $user_id, $bank_name, $loan_amount, $loan_tenure, $employment_type,
    $monthly_income, $date_of_birth, $gender, $aadhar_number, $pan_number,
    $address, $city, $pincode, $property_address, $property_city, $property_pincode
);

if (mysqli_stmt_execute($stmt)) {
    $application_id = mysqli_insert_id($conn);
    error_log("Application saved successfully! ID: $application_id");
    sendJsonResponse(true, 'Application submitted successfully', ['application_id' => $application_id]);
} else {
    error_log("Database error: " . mysqli_stmt_error($stmt));
    sendJsonResponse(false, 'Database error: ' . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>