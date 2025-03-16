<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    // Insert new category
    $insertQuery = "INSERT INTO tbl_category (name) VALUES ('$name')";
    if ($conn->query($insertQuery)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "Invalid request!";
}
?>