<?php
include('config.php'); // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']); // Sanitize the title
    $page_slug = $conn->real_escape_string($_POST['page_slug']); // Sanitize the page slug
    $display_order = intval($_POST['display_order']); // Sanitize the display order

    if (!empty($title) && !empty($page_slug)) {
        // Insert the new navigation item into the database
        $query = "INSERT INTO tbl_navbar (title, page_slug, display_order) VALUES ('$title', '$page_slug', $display_order)";
        if ($conn->query($query)) {
            echo "success"; // Return success response
        } else {
            error_log("Failed to add new navigation item: " . $conn->error); // Log errors
            echo "error"; // Return error if the query fails
        }
    } else {
        echo "error"; // Return error if any required field is empty
    }
} else {
    echo "error"; // Return error if the request method is not POST
}
?>