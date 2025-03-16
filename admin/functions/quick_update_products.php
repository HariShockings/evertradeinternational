<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    $updateQuery = "UPDATE tbl_hardware SET 
                    name = '$name', 
                    price = '$price', 
                    stock = '$stock', 
                    category_id = '$category_id' 
                    WHERE id = $id";

    if ($conn->query($updateQuery)) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}
?>
