<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Fetch product price to calculate order amount
    $priceQuery = "SELECT price FROM tbl_hardware WHERE id = $product_id";
    $priceResult = $conn->query($priceQuery);

    if ($priceResult->num_rows > 0) {
        $product = $priceResult->fetch_assoc();
        $order_amount = $product['price'] * $quantity;

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
        echo "error: Product not found.";
    }
} else {
    echo "error: Invalid request.";
}
?>