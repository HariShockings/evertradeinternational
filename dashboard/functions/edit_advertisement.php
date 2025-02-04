<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userID']) && $_SESSION['userType'] != 1) {
    header("Location: unauthorized.php");
    exit();
}

// Check if ThirdPartyAdvertisementID is provided in the URL
if (isset($_GET['id'])) {
    $advertisementID = $_GET['id'];
    $userID = $_SESSION['userID'];

    // Include your database configuration file
    include '../../config.php';

    // Check if the logged-in user is authorized to edit the advertisement
    $checkOwnershipQuery = "SELECT * FROM tbl_third_party_advertisement WHERE ThirdPartyAdvertisementID = '$advertisementID'";
    $checkOwnershipResult = mysqli_query($conn, $checkOwnershipQuery);

    if (mysqli_num_rows($checkOwnershipResult) > 0) {
        $advertisementData = mysqli_fetch_assoc($checkOwnershipResult);

        // Check if the edit form is submitted
        if (isset($_POST['editAdvertisement'])) {
            // Retrieve form data
            $description = $_POST['description'];
            $image = $_POST['image'];
            $showTime = $_POST['showTime'];
            $link = $_POST['link'];

            // Update the advertisement in the database
            $updateQuery = "UPDATE tbl_third_party_advertisement SET Description = '$description', Image = '$image', showTime = '$showTime', Link = '$link' WHERE ThirdPartyAdvertisementID = '$advertisementID'";
            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                echo "<code>Advertisement updated successfully.</code>";
            } else {
                echo "<code>Error updating advertisement: " . mysqli_error($conn) . "</code>";
            }
        }
    } else {
        echo "Advertisement not found.";
    }

    mysqli_close($conn);
} else {
    echo "ThirdPartyAdvertisementID not provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Advertisement</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php if (isset($advertisementData)) : ?>
    <div class="container mt-5">
        <h1 class="mb-4">Edit Advertisement</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $advertisementID; ?>" method="POST">
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="4"><?php echo $advertisementData['Description']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image URL:</label>
                <input type="text" id="image" name="image" class="form-control" value="<?php echo $advertisementData['Image']; ?>">
            </div>

            <div class="form-group">
                <label for="showTime">Show Time (seconds):</label>
                <input type="number" id="showTime" name="showTime" class="form-control" value="<?php echo $advertisementData['showTime']; ?>">
            </div>

            <div class="form-group">
                <label for="link">Link:</label>
                <input type="text" id="link" name="link" class="form-control" value="<?php echo $advertisementData['Link']; ?>">
            </div>

            <button type="submit" name="editAdvertisement" class="btn btn-primary">Edit Advertisement</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
