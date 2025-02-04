<?php
// Start the session
session_start();

if(isset($_SESSION['userType'])){
    $userType = intval($_SESSION['userType']);
}
// Check if the user is logged in and their userType is 1 (admin)
if (isset($_SESSION['userID']) && $_SESSION['userType'] == 1) {
    // Connect to your MySQL database
    include '../../config.php';

    // Check if the user ID is provided in the URL
    if (isset($_GET['id'])) {
        $userID = $_GET['id'];

        // Perform deletion query
        $deleteQuery = "DELETE FROM tbl_user WHERE UserID = '$userID'";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            // Deletion successful, redirect back to the user list
            header("Location: ../");
            exit();
        } else {
            // Error in deletion query
            echo "Error deleting user.";
        }
    } else {
        // UserID not provided in the URL
        echo "User ID not specified.";
    }

    mysqli_close($conn);
} else {
    // Redirect or handle unauthorized access
    header("Location: unauthorized.php");
    exit();
}
?>
