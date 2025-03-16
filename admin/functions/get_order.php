<?php
include('config.php'); // Include your database connection file

if (isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    // Fetch order details
    $query = "SELECT o.*, h.name AS product_name 
              FROM tbl_orders o 
              LEFT JOIN tbl_hardware h ON o.product_id = h.id 
              WHERE o.id = $id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        echo json_encode($order); // Return order data as JSON
    } else {
        echo "error: Order not found.";
    }
} else {
    echo "error: Invalid request.";
}
?>