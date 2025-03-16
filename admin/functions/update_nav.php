<?php
include('config.php'); // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']); // Sanitize the ID
    $title = $conn->real_escape_string($_POST['title']); // Sanitize the title
    $page_slug = $conn->real_escape_string($_POST['page_slug']); // Sanitize the page slug

    if (!empty($id) && !empty($title) && !empty($page_slug)) {
        // Update the navigation item in the database
        $query = "UPDATE tbl_navbar SET title = '$title', page_slug = '$page_slug' WHERE id = $id";
        if ($conn->query($query)) {
            echo "success"; // Return success response
        } else {
            error_log("Failed to update navigation item: " . $conn->error); // Log errors
            echo "error"; // Return error if the query fails
        }
    } else {
        echo "error"; // Return error if any required field is empty
    }
} else {
    echo "error"; // Return error if the request method is not POST
}
?>