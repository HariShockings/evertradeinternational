<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Check if uploads directory exists and is writable
    $uploadDir = '../../uploads/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            die("Failed to create uploads directory");
        }
    }
    
    if (!is_writable($uploadDir)) {
        die("Uploads directory is not writable");
    }

    $altText = $conn->real_escape_string($_POST['alt_text']);
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Generate unique filename
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            die("Invalid file type. Only JPG, PNG, and GIF are allowed.");
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            // Get max display order
            $maxOrder = $conn->query("SELECT MAX(display_order) as max_order FROM tbl_carousel")->fetch_assoc()['max_order'];
            $displayOrder = $maxOrder ? $maxOrder + 1 : 1;
            
            // Insert into database
            $query = "INSERT INTO tbl_carousel (image_url, alt_text, display_order) 
                      VALUES ('$fileName', '$altText', $displayOrder)";
            
            if ($conn->query($query)) {
                echo "success";
            } else {
                // Clean up uploaded file if database insert fails
                unlink($targetPath);
                die("Database error: " . $conn->error);
            }
        } else {
            die("File upload failed. Check directory permissions.");
        }
    } else {
        die("No file uploaded or upload error occurred.");
    }
}
?>