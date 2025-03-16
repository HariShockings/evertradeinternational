<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    $order_amount = (float) $_POST['order_amount'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Update order record
    $updateQuery = "UPDATE tbl_orders SET 
                    product_id = '$product_id', 
                    quantity = '$quantity', 
                    order_amount = '$order_amount', 
                    status = '$status' 
                    WHERE id = $id";

    if ($conn->query($updateQuery)) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "error: Invalid request.";
}
?>