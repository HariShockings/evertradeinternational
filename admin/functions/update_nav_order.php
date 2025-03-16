<?php
include('config.php'); // Include your database configuration file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order = $_POST['order']; // Get the order array from the AJAX request

    if (!empty($order)) {
        foreach ($order as $index => $id) {
            $id = intval($id); // Sanitize the ID
            $display_order = $index + 1; // Set the display order (starting from 1)

            // Update the display_order in the database
            $query = "UPDATE tbl_navbar SET display_order = $display_order WHERE id = $id";
            if (!$conn->query($query)) {
                error_log("Failed to update order for ID $id: " . $conn->error); // Log errors
            }
        }

        echo "success"; // Return success response
    } else {
        echo "error"; // Return error if the order array is empty
    }
} else {
    echo "error"; // Return error if the request method is not POST
}
?>