<?php
include('config.php'); // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']); // Sanitize the ID

    if (!empty($id)) {
        // Delete the navigation item from the database
        $query = "DELETE FROM tbl_navbar WHERE id = $id";
        if ($conn->query($query)) {
            echo "success"; // Return success response
        } else {
            error_log("Failed to delete navigation item: " . $conn->error); // Log errors
            echo "error"; // Return error if the query fails
        }
    } else {
        echo "error"; // Return error if the ID is empty
    }
} else {
    echo "error"; // Return error if the request method is not POST
}
?>