<?php
// FILE: includes/insuranceFunctions.php
require_once 'dbConfig.php';

/**
 * Get all insurance plans from database
 */
function getInsurancePlans($limit = null, $type = null) {
    global $conn;
    
    $sql = "SELECT * FROM insurance_plans WHERE status = 1";
    
    if ($type && $type != 'all') {
        $sql .= " AND plan_type = '" . mysqli_real_escape_string($conn, $type) . "'";
    }
    
    $sql .= " ORDER BY is_popular DESC, created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    return $conn->query($sql);
}

/**
 * Get all insurance plans for admin
 */
function getAllInsurancePlansAdmin() {
    global $conn;
    $sql = "SELECT * FROM insurance_plans ORDER BY created_at DESC";
    return $conn->query($sql);
}

/**
 * Get single insurance plan by ID
 */
function getInsurancePlanById($id) {
    global $conn;
    $id = intval($id);
    $sql = "SELECT * FROM insurance_plans WHERE id = $id";
    return $conn->query($sql);
}

/**
 * Add new insurance plan
 */
function addInsurancePlan($data) {
    global $conn;
    
    $plan_name = mysqli_real_escape_string($conn, $data['plan_name']);
    $plan_type = mysqli_real_escape_string($conn, $data['plan_type']);
    $min_amount = floatval($data['min_amount']);
    $coverage_amount = floatval($data['coverage_amount']);
    $duration_years = intval($data['duration_years']);
    $premium_frequency = mysqli_real_escape_string($conn, $data['premium_frequency']);
    $tax_benefit = !empty($data['tax_benefit']) ? floatval($data['tax_benefit']) : 'NULL';
    $description = mysqli_real_escape_string($conn, $data['description']);
    $features = mysqli_real_escape_string($conn, $data['features']);
    $is_popular = isset($data['is_popular']) ? 1 : 0;
    
    $sql = "INSERT INTO insurance_plans (plan_name, plan_type, min_amount, coverage_amount, duration_years, premium_frequency, tax_benefit, description, features, is_popular) 
            VALUES ('$plan_name', '$plan_type', $min_amount, $coverage_amount, $duration_years, '$premium_frequency', $tax_benefit, '$description', '$features', $is_popular)";
    
    if ($conn->query($sql)) {
        return $conn->insert_id;
    }
    return false;
}

/**
 * Update insurance plan
 */
function updateInsurancePlan($id, $data) {
    global $conn;
    $id = intval($id);
    
    $plan_name = mysqli_real_escape_string($conn, $data['plan_name']);
    $plan_type = mysqli_real_escape_string($conn, $data['plan_type']);
    $min_amount = floatval($data['min_amount']);
    $coverage_amount = floatval($data['coverage_amount']);
    $duration_years = intval($data['duration_years']);
    $premium_frequency = mysqli_real_escape_string($conn, $data['premium_frequency']);
    $tax_benefit = !empty($data['tax_benefit']) ? floatval($data['tax_benefit']) : 'NULL';
    $description = mysqli_real_escape_string($conn, $data['description']);
    $features = mysqli_real_escape_string($conn, $data['features']);
    $is_popular = isset($data['is_popular']) ? 1 : 0;
    $status = isset($data['status']) ? 1 : 0;
    
    $sql = "UPDATE insurance_plans SET 
            plan_name = '$plan_name',
            plan_type = '$plan_type',
            min_amount = $min_amount,
            coverage_amount = $coverage_amount,
            duration_years = $duration_years,
            premium_frequency = '$premium_frequency',
            tax_benefit = $tax_benefit,
            description = '$description',
            features = '$features',
            is_popular = $is_popular,
            status = $status
            WHERE id = $id";
    
    return $conn->query($sql);
}

/**
 * Delete insurance plan
 */
function deleteInsurancePlan($id) {
    global $conn;
    $id = intval($id);
    $sql = "DELETE FROM insurance_plans WHERE id = $id";
    return $conn->query($sql);
}

/**
 * Get user's insurance purchases
 */
function getUserInsurancePurchases($user_id) {
    global $conn;
    $user_id = intval($user_id);
    $sql = "SELECT ui.*, ip.plan_name, ip.plan_type, ip.coverage_amount, ip.duration_years 
            FROM user_insurance ui 
            JOIN insurance_plans ip ON ui.plan_id = ip.id 
            WHERE ui.user_id = $user_id 
            ORDER BY ui.purchase_date DESC";
    return $conn->query($sql);
}

/**
 * Purchase insurance
 */
function purchaseInsurance($user_id, $plan_id, $nominee_name, $nominee_relation, $payment_method) {
    global $conn;
    
    $user_id = intval($user_id);
    $plan_id = intval($plan_id);
    $nominee_name = mysqli_real_escape_string($conn, $nominee_name);
    $nominee_relation = mysqli_real_escape_string($conn, $nominee_relation);
    $payment_method = mysqli_real_escape_string($conn, $payment_method);
    $policy_number = 'POL' . time() . rand(1000, 9999);
    $start_date = date('Y-m-d');
    
    // Get plan details to calculate end date
    $plan = getInsurancePlanById($plan_id);
    $plan_data = $plan->fetch_assoc();
    $end_date = date('Y-m-d', strtotime("+{$plan_data['duration_years']} years"));
    
    $sql = "INSERT INTO user_insurance (user_id, plan_id, policy_number, nominee_name, nominee_relation, start_date, end_date, payment_method, status) 
            VALUES ($user_id, $plan_id, '$policy_number', '$nominee_name', '$nominee_relation', '$start_date', '$end_date', '$payment_method', 'active')";
    
    if ($conn->query($sql)) {
        return $conn->insert_id;
    }
    return false;
}
?>