-- FILE: database.sql (Same as before - keeping for completeness)
CREATE DATABASE IF NOT EXISTS invest_portal;
USE invest_portal;

-- Users Table
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    address TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Investment Plans Table
CREATE TABLE investment_plans (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    plan_name VARCHAR(100) NOT NULL,
    min_amount DECIMAL(10,2) NOT NULL,
    max_amount DECIMAL(10,2),
    interest_rate DECIMAL(5,2) NOT NULL,
    duration_months INT(3) NOT NULL,
    risk_level ENUM('Low', 'Medium', 'High') DEFAULT 'Medium',
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User Investments Table
CREATE TABLE user_investments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    plan_id INT(11) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    expected_returns DECIMAL(10,2),
    status ENUM('active', 'completed', 'cancelled', 'pending') DEFAULT 'pending',
    invest_date DATE NOT NULL,
    maturity_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES investment_plans(id)
);

-- Loans Table
CREATE TABLE loans (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    loan_type ENUM('personal', 'business', 'education', 'home') DEFAULT 'personal',
    amount DECIMAL(10,2) NOT NULL,
    interest_rate DECIMAL(5,2) NOT NULL,
    tenure_months INT(3) NOT NULL,
    monthly_emi DECIMAL(10,2),
    purpose TEXT,
    status ENUM('pending', 'approved', 'rejected', 'active', 'closed') DEFAULT 'pending',
    applied_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_date DATE,
    rejection_reason TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Transactions Table
CREATE TABLE transactions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    type ENUM('investment', 'loan_disbursement', 'loan_repayment', 'withdrawal') NOT NULL,
    reference_id INT(11),
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'success', 'failed') DEFAULT 'pending',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    remarks TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Sample Investment Plans
INSERT INTO investment_plans (plan_name, min_amount, max_amount, interest_rate, duration_months, risk_level, description) VALUES
('Fixed Income Plan', 10000.00, 500000.00, 8.50, 12, 'Low', 'Safe and steady returns with guaranteed principal protection'),
('Growth Plus Plan', 25000.00, 1000000.00, 12.00, 24, 'Medium', 'Balanced portfolio for moderate risk takers'),
('Ultra Growth Plan', 50000.00, 5000000.00, 18.00, 36, 'High', 'High risk high reward equity linked investment'),
('Retirement Secure', 100000.00, 2000000.00, 10.00, 60, 'Low', 'Long term wealth creation for retirement'),
('Flexi Saver', 5000.00, 200000.00, 7.50, 6, 'Low', 'Short term investment with liquidity option');

-- Insert Sample Admin User (password: Admin@123)
INSERT INTO users (fullname, email, password, phone, role) VALUES 
('Admin User', 'admin@investportal.com', 'Admin@123', '9876543210', 'admin');

-- Insert Sample Regular User (password: User@123)
INSERT INTO users (fullname, email, password, phone, role) VALUES 
('John Investor', 'john@example.com', 'User@123', '9876543211', 'user');