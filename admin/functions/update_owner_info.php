<?php
include('../functions/config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract and sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $facebook = mysqli_real_escape_string($conn, $_POST['facebook']);
    $twitter = mysqli_real_escape_string($conn, $_POST['twitter']);
    $instagram = mysqli_real_escape_string($conn, $_POST['instagram']);
    $linkedin = mysqli_real_escape_string($conn, $_POST['linkedin']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    $office_time = mysqli_real_escape_string($conn, $_POST['office_time']);
    $google_map_link = mysqli_real_escape_string($conn, $_POST['google_map_link']);

    // Handle logo image upload
    $logo = isset($_POST['old_logo']) ? $_POST['old_logo'] : ''; // Use old logo if exists, otherwise empty

    if ($_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
        }

        $uploadFile = $uploadDir . basename($_FILES['logo']['name']);

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadFile)) {
                $logo = basename($_FILES['logo']['name']); // Update logo path
            } else {
                echo "error: Failed to move uploaded file.";
                exit;
            }
        } else {
            echo "error: Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            exit;
        }
    }

    // Update query
    $updateQuery = "UPDATE tbl_owner SET 
                    name = '$name',
                    logo = '$logo',
                    contact = '$contact',
                    email = '$email',
                    location = '$location',
                    facebook = '$facebook',
                    twitter = '$twitter',
                    instagram = '$instagram',
                    linkedin = '$linkedin',
                    whatsapp = '$whatsapp',
                    office_time = '$office_time',
                    google_map_link = '$google_map_link'
                    WHERE id = 1";

    if ($conn->query($updateQuery)) {
        echo "success"; // Ensure this is the only output
    } else {
        echo "error: " . $conn->error; // Debugging
    }
} else {
    echo "Invalid request!";
}
?>