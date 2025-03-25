<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('config.php');
session_start();

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch user ID dynamically (if required)
$userId = 1; // Replace with dynamic user ID logic

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs and sanitize them
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Initialize error array
    $errors = [];

    // Validate inputs
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }

    // Handle password update logic (only if the password field is not empty)
    if (!empty($password)) {
        // Validate password (e.g., minimum length, etc.)
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }
    } else {
        // If password is empty, don't update it
        $password = null;
    }

    // Process form if there are no errors
    if (empty($errors)) {
        // Start building the query
        $updateQuery = "UPDATE tbl_user SET username = ?, email = ?";

        // Only add the password to the update query if it is not null (i.e., password is provided)
        if ($password !== null) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery .= ", password = ?";
        }

        // Finish the query
        $updateQuery .= " WHERE id = ?";

        $stmt = $conn->prepare($updateQuery);
        
        // Check for prepare failure
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters to the prepared statement
        if ($password !== null) {
            // Bind username, email, password, and userId
            $stmt->bind_param("sssi", $username, $email, $hashedPassword, $userId);
        } else {
            // Bind username, email, and userId (no password change)
            $stmt->bind_param("ssi", $username, $email, $userId);
        }

        // Execute the statement
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['success_msg'] = "User details updated successfully!";
            } else {
                $_SESSION['error_msg'] = "No changes were made. Did you actually modify any fields?";
            }
        } else {
            $_SESSION['error_msg'] = "Error updating user details: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $_SESSION['error_msg'] = implode("<br>", $errors);
    }

    // Redirect to avoid form resubmission
    header("Location: ../?page=settings.php");
    exit();
}
?>
