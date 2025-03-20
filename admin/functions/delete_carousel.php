<?php
include('config.php');

if(isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    // Get image path
    $result = $conn->query("SELECT image_url FROM tbl_carousel WHERE id = $id");
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagePath = '../../uploads/' . $row['image_url'];
        
        // Delete image file
        if(file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    
    // Delete record
    $conn->query("DELETE FROM tbl_carousel WHERE id = $id");
    
    echo 'success';
}
?>