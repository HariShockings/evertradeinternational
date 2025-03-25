<?php
include('../config.php');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Start session once at the beginning
    session_start();
    
    // Sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $created_at = date('Y-m-d H:i:s');

    // Validation flags
    $valid = true;
    $error_msg = '';

    // Name validation
    if (!preg_match("/^[A-Za-z\s.'-]{2,50}$/", $name)) {
        $valid = false;
        $error_msg = "Invalid name format.";
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $valid = false;
        $error_msg = "Invalid email format.";
    }

    // Message validation
    if (strlen($message) < 5 || strlen($message) > 300) {
        $valid = false;
        $error_msg = "Message must be between 5 and 300 characters.";
    }

    if (!$valid) {
        $_SESSION['error_msg'] = $error_msg;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    try {
        // Check database connection
        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        }

        // Use prepared statement
        $query = "INSERT INTO tbl_inquiry (name, email, message, created_at) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("ssss", $name, $email, $message, $created_at);
        
        // Execute query
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Inquiry submitted successfully!";
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        $_SESSION['error_msg'] = "An error occurred. Please try again later.";
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>