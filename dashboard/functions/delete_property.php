<?php
// Assuming you have started the session
session_start();

// Check if the user is logged in and their userType is 1 (admin) or 2 (advertiser)
if(isset($_SESSION['userType'])){
    $userType = intval($_SESSION['userType']);
}

// Check if the userID is set and user type is either admin or advertiser
if (isset($_SESSION['userID']) && ($_SESSION['userType'] == 2 || $_SESSION['userType'] == 1)) {
    // Check if the property ID is provided in the URL
    if (isset($_GET['id'])) {
        // Sanitize the property ID
        $propertyID = intval($_GET['id']);

        // Include your database configuration file
        include '../../config.php';

        // Check if the logged-in user is the owner of the property or an admin
        if ($_SESSION['userType'] == 1) {
            // Admin user can delete any property
            $deleteQuery = "DELETE FROM tbl_property WHERE PropertyID = '$propertyID'";
        } else {
            // Advertiser can only delete their own property
            $userID = $_SESSION['userID'];
            $deleteQuery = "DELETE FROM tbl_property WHERE PropertyID = '$propertyID' AND AdvertiserID = '$userID'";
        }

        // Execute the delete query
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            echo "Property deleted successfully.";
        } else {
            echo "Error deleting property: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        echo "Property ID not provided.";
    }
} else {
    echo "You are not authorized to delete this property.";
}
?>
