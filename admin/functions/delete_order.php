<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];

    // Delete order record
    $deleteQuery = "DELETE FROM tbl_orders WHERE id = $id";

    if ($conn->query($deleteQuery)) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "error: Invalid request.";
}
?>