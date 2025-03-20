<?php
// Add this at the very top to clean output
if (ob_get_level()) ob_clean();
header('Content-Type: text/plain');

include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log received data
    error_log("Received update request: " . print_r($_POST, true));

    $id = intval($_POST['id']);
    $altText = trim($conn->real_escape_string($_POST['alt_text']));

    // Validate inputs
    if (!empty($id) && !empty($altText)) {
        // Get current alt text for comparison
        $check = $conn->query("SELECT alt_text FROM tbl_carousel WHERE id = $id");
        $current = $check->fetch_assoc();
        
        if ($current['alt_text'] === $altText) {
            echo "no_changes";
            exit();
        }

        // Perform update with error handling
        $query = "UPDATE tbl_carousel SET alt_text = '$altText' WHERE id = $id";
        
        if ($conn->query($query)) {
            if ($conn->affected_rows > 0) {
                error_log("Update successful for ID: $id");
                echo "success";
            } else {
                error_log("No rows affected for ID: $id");
                echo "no_changes";
            }
        } else {
            error_log("Database error: " . $conn->error);
            echo "error";
        }
    } else {
        error_log("Invalid input - ID: $id, Alt Text: $altText");
        echo "invalid_input";
    }
    exit(); // Ensure no extra output
}
?>