<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $image = basename($_POST['image']);
    
    // Verify image exists
    $imagePath = '../../uploads/' . $image;
    if (!file_exists($imagePath)) {
        die("Image file not found");
    }

    // Delete image file
    if (!unlink($imagePath)) {
        die("Failed to delete image file");
    }

    // Update database
    $query = "UPDATE tbl_carousel SET image_url = '' WHERE id = $id";
    if ($conn->query($query)) {
        echo "success";
    } else {
        die("Database error: " . $conn->error);
    }
}
?>