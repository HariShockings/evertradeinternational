<?php
// Include your database connection file
include('../config.php');

// Check if the AJAX request is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['propertyID']) && isset($_POST['rating'])) {
    // Sanitize input data
    $propertyID = mysqli_real_escape_string($conn, $_POST['propertyID']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);

    // Insert rating into tbl_propertyrating
    $insertQuery = "INSERT INTO tbl_propertyrating (PropertyID, Rating) VALUES ('$propertyID', '$rating')";
    if (mysqli_query($conn, $insertQuery)) {
        echo 'Rating inserted successfully.';
    } else {
        echo 'Error inserting rating: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request.';
}
?>
