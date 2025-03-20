<?php
include('config.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $altText = $conn->real_escape_string($_POST['alt_text']);
    $removeImage = $_POST['remove_image'] ?? 0;
    $fileName = null;

    // Handle file upload
    $uploadDir = '../../uploads/';
    if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    if(isset($_FILES['image']['error']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if(!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            die("Failed to move uploaded file");
        }
    }

    if($id) { // Update existing
        // Get current image
        $current = $conn->query("SELECT image_url FROM tbl_carousel WHERE id = $id")->fetch_assoc();
        
        // Delete old image if needed
        if($removeImage || $fileName) {
            if(!empty($current['image_url'])) {
                $oldPath = $uploadDir . $current['image_url'];
                if(file_exists($oldPath)) unlink($oldPath);
            }
        }
        
        // Build query
        $imageUpdate = '';
        if($fileName) {
            $imageUpdate = "image_url = '$fileName',";
        } elseif($removeImage) {
            $imageUpdate = "image_url = NULL,";
        }
        
        $conn->query("UPDATE tbl_carousel SET 
            $imageUpdate
            alt_text = '$altText' 
            WHERE id = $id
        ");
    } else { 
 $maxOrder = $conn->query("SELECT MAX(display_order) as max_order FROM tbl_carousel")->fetch_assoc()['max_order'];
 $displayOrder = $maxOrder ? $maxOrder + 1 : 1;

 $imageValue = $fileName ? "'$fileName'" : "NULL";
 
 $query = "INSERT INTO tbl_carousel (image_url, alt_text, display_order) 
          VALUES ($imageValue, '$altText', $displayOrder)";
 
 if(!$conn->query($query)) {
     die("Insert failed: " . $conn->error);
 }
    }
    
    echo 'success';
}
?>