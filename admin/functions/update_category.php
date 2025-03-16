<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    $updateQuery = "UPDATE tbl_category SET name = '$name' WHERE id = $id";
    if ($conn->query($updateQuery)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "Invalid request!";
}
?>