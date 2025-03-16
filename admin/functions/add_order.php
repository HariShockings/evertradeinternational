<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    $order_amount = (float) $_POST['order_amount'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Insert new order record
    $insertQuery = "INSERT INTO tbl_orders (product_id, quantity, order_amount, status) 
                    VALUES ('$product_id', '$quantity', '$order_amount', '$status')";

    if ($conn->query($insertQuery)) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "error: Invalid request.";
}
?>