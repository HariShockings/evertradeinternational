<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $deleteQuery = "DELETE FROM tbl_category WHERE id = $id";
    if ($conn->query($deleteQuery)) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "Invalid request!";
}
?>