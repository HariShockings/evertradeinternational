<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;
    $name = $conn->real_escape_string($_POST['name']);
    $position = $conn->real_escape_string($_POST['position']);
    $quote = $conn->real_escape_string($_POST['quote']);
    $removeImage = $_POST['remove_image'] ?? 0;
    $fileName = null;
    $uploadDir = '../../uploads/';

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    // Handle file upload
    if (isset($_FILES['image']['error']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
            echo 'Error uploading file';
            exit;
        }
    }

    if ($id) { // Update
        $current = $conn->query("SELECT image_url FROM tbl_leadership WHERE id = $id")->fetch_assoc();
        if (!$current) {
            echo 'Error: Record not found';
            exit;
        }
        
        if ($removeImage || $fileName) {
            if ($current['image_url'] && file_exists($uploadDir . $current['image_url'])) {
                unlink($uploadDir . $current['image_url']);
            }
        }
        
        $imageUpdate = $fileName ? "image_url = '$fileName'," : '';
        if ($removeImage) $imageUpdate = "image_url = NULL,";

        $query = "UPDATE tbl_leadership SET $imageUpdate name = '$name', position = '$position', quote = '$quote' WHERE id = $id";
        if ($conn->query($query)) {
            echo 'success';
        } else {
            echo 'Error: ' . $conn->error;
        }
    } else { // Insert
        $query = "INSERT INTO tbl_leadership (name, position, quote, image_url) VALUES ('$name', '$position', '$quote', " . ($fileName ? "'$fileName'" : "NULL") . ")";
        if ($conn->query($query)) {
            echo 'success';
        } else {
            echo 'Error: ' . $conn->error;
        }
    }
}
?>