<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $images = mysqli_real_escape_string($conn, $_POST['images']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $category_id = (int) $_POST['category_id'];
    $page_slug = mysqli_real_escape_string($conn, $_POST['page_slug']);
    $use_cases = mysqli_real_escape_string($conn, $_POST['use_cases']);

    // Insert new product
    $insertQuery = "INSERT INTO tbl_hardware (name, images, description, price, stock, category_id, page_slug, use_cases) 
                    VALUES ('$name', '$images', '$description', '$price', '$stock', '$category_id', '$page_slug', '$use_cases')";

    if ($conn->query($insertQuery)) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}
?>
