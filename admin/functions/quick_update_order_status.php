<?php
include('../functions/config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Update the order status
    $updateQuery = "UPDATE tbl_orders SET status = '$status' WHERE id = $id";

    if ($conn->query($updateQuery)) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "error: Invalid request.";
}
?>