<?php
// Include your database connection file
include '../../config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if orderID and newStatus are set in the POST data
    if (isset($_POST['orderID'], $_POST['newStatus'])) {
        $orderID = $_POST['orderID'];
        $newStatus = $_POST['newStatus'];

        // Sanitize input to prevent SQL injection
        $orderID = mysqli_real_escape_string($conn, $orderID);
        $newStatus = mysqli_real_escape_string($conn, $newStatus);

        // Update the status in the database
        $updateQuery = "UPDATE tbl_order SET Stts = '$newStatus' WHERE OrderID = '$orderID'";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}

// Close the database connection
mysqli_close($conn);
?>
