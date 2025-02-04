<?php
session_start();

// Check if the user is logged in and their userType is either 1 (admin) or 2 (advertiser)
if (isset($_SESSION['userType'])) {
    $userType = intval($_SESSION['userType']);
}

// Check if the userID is set and user type is either admin or advertiser
if (isset($_SESSION['userID']) && ($_SESSION['userType'] == 2 || $_SESSION['userType'] == 1)) {
    // Check if the advertisement ID is provided in the URL
    if (isset($_GET['id'])) {
        // Sanitize the advertisement ID
        $advertisementID = intval($_GET['id']);

        // Include your database configuration file
        include '../../config.php';

        // Check if the logged-in user is the owner of the advertisement or an admin
        if ($_SESSION['userType'] == 1) {
            // Admin user can delete any advertisement
            $deleteQuery = "DELETE FROM tbl_third_party_advertisement WHERE ThirdPartyAdvertisementID = '$advertisementID'";
        } else {
            // Advertiser can only delete their own advertisement
            $userID = $_SESSION['userID'];
            $deleteQuery = "DELETE FROM tbl_third_party_advertisement WHERE ThirdPartyAdvertisementID = '$advertisementID' AND UserID = '$userID'";
        }

        // Execute the delete query
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            // Fetch the image path from the database
            $fetchImageQuery = "SELECT Image FROM tbl_third_party_advertisement WHERE ThirdPartyAdvertisementID = '$advertisementID'";
            $fetchImageResult = mysqli_query($conn, $fetchImageQuery);

            if ($fetchImageResult && mysqli_num_rows($fetchImageResult) > 0) {
                $row = mysqli_fetch_assoc($fetchImageResult);
                $imagePath = $row['Image'];

                // Check if the file exists and then delete it
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                    echo "Advertisement and associated image deleted successfully.";
                } else {
                    echo "Image file not found.";
                }
            } else {
                echo "Image path not found in the database.";
            }
        } else {
            echo "Error deleting advertisement: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        echo "Advertisement ID not provided.";
    }
} else {
    echo "You are not authorized to delete this advertisement.";
}
?>
