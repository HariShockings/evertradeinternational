<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $images = mysqli_real_escape_string($conn, $_POST['images']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $category_id = (int) $_POST['category_id'];
    $page_slug = mysqli_real_escape_string($conn, $_POST['page_slug']);
    $use_cases = mysqli_real_escape_string($conn, $_POST['use_cases']);

    // Update product record
    $updateQuery = "UPDATE tbl_hardware SET 
                    name = '$name', 
                    images = '$images', 
                    description = '$description', 
                    price = '$price', 
                    stock = '$stock', 
                    category_id = '$category_id', 
                    page_slug = '$page_slug', 
                    use_cases = '$use_cases' 
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
