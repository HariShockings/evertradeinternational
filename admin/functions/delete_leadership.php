<?php
include('config.php');

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Get image path
    $result = $conn->query("SELECT image_url FROM tbl_leadership WHERE id = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = '../../uploads/' . $row['image_url'];
        
        // Check if the file exists and is a file (not a directory)
        if (file_exists($imagePath) && !is_dir($imagePath)) {
            unlink($imagePath);  // Delete file
        }
    }
    
    // Delete the record regardless of image presence
    if ($conn->query("DELETE FROM tbl_leadership WHERE id = $id")) {
        echo 'success';
    } else {
        echo 'Error deleting record: ' . $conn->error;
    }
}
?>
