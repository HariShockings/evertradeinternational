<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hardware_id = isset($_POST['hardware_id']) ? intval($_POST['hardware_id']) : 0;
    $reviewer_name = isset($_POST['reviewer_name']) ? trim($_POST['reviewer_name']) : '';
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if ($hardware_id > 0 && !empty($reviewer_name) && !empty($description) && $rating >= 1 && $rating <= 5) {
        $reviewer_name = $conn->real_escape_string($reviewer_name);
        $description = $conn->real_escape_string($description);

        $query = "INSERT INTO tbl_review (hardware_id, reviewer_name, rating, description) 
                  VALUES ('$hardware_id', '$reviewer_name', '$rating', '$description')";

        if ($conn->query($query) === TRUE) {
            session_start();
            $_SESSION['success_msg'] = "Your review has been submitted successfully!";
        } else {
            session_start();
            $_SESSION['error_msg'] = "Error submitting review: " . $conn->error;
        }
    } else {
        session_start();
        $_SESSION['error_msg'] = "Invalid input. Please fill out all fields correctly.";
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect back to the page
exit();
?>
