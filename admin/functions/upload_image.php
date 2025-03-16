<?php
// This script handles file uploads. 
// The file will be saved in the upload directory ($uploadDir)
// while the preview URL (used by the client) is based on the preview directory ($previewDir).

$uploadDir = '../../uploads/';    // Physical server path to save uploads
$previewDir = '../uploads/';        // URL path for previewing images

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $filename = basename($file['name']);
    $targetFile = $uploadDir . time() . '_' . $filename;
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // Return the preview path for the uploaded image
        echo $previewDir . basename($targetFile);
    } else {
        echo "error: Upload failed.";
    }
} else {
    echo "Invalid request!";
}
?>
