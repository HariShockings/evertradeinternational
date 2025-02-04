<?php
// Start the session
session_start();

// Check if the user is logged in and their userType is admin (userType = 1)
if (isset($_SESSION['userID']) && $_SESSION['userType'] == 1) {
    // Include your database configuration file
    include '../../config.php';

    // Check if the rating ID is provided in the URL
    if (isset($_GET['id'])) {
        $ratingID = $_GET['id'];

        // Perform deletion query
        $deleteQuery = "DELETE FROM tbl_propertyrating WHERE PropertyID = '$ratingID'";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            // Deletion successful, redirect back to the ratings list
            echo '<script>';
            echo 'window.alert("Rating deleted successfully.");';
            echo 'window.location.href = "../";';
            echo '</script>';
            exit();
        } else {
            // Error in deletion query
            echo '<script>';
            echo 'window.alert("Error deleting rating.");';
            echo 'window.location.href = "javascript:history.back()";';
            echo '</script>';
            exit();
        }
    } else {
        // RatingID not provided in the URL
        echo '<script>';
        echo 'window.alert("Rating ID not specified.");';
        echo 'window.location.href = "javascript:history.back()";';
        echo '</script>';
        exit();
    }

    mysqli_close($conn);
} else {
    // Redirect or handle unauthorized access
    header("Location: unauthorized.php");
    exit();
}
?>
