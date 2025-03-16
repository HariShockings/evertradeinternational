<?php
include('config.php'); // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    
    // Fetch the product record to get the images field
    $fetchQuery = "SELECT images FROM tbl_hardware WHERE id = $id";
    $result = $conn->query($fetchQuery);
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $imagesJson = $row['images'];
        $imagesArray = json_decode($imagesJson, true);
        
        // Loop through each image path and delete the physical file
        if (is_array($imagesArray)) {
            foreach ($imagesArray as $imagePath) {
                // Convert preview path to physical path:
                // Assuming images in the DB are stored with preview path: "../uploads/filename"
                // And physical files are stored in "../../uploads/"
                $physicalPath = str_replace('../uploads/', '../../uploads/', $imagePath);
                if (file_exists($physicalPath)) {
                    unlink($physicalPath);
                }
            }
        }
    }
    
    // Now delete the record from the database
    $deleteQuery = "DELETE FROM tbl_hardware WHERE id = $id";
    if ($conn->query($deleteQuery)) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}
?>
