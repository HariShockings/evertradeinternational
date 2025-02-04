<?php
// Assuming you have started the session
session_start();

if (isset($_SESSION['userType'])) {
    $userType = intval($_SESSION['userType']);
}

if (!isset($_SESSION['userID']) && $_SESSION['userType'] != 1) {
    header("Location: unauthorized.php");
    exit();
}

// Check if PropertyID is provided in the URL
if (isset($_GET['id'])) {
    $propertyID = $_GET['id'];
    $userID = $_SESSION['userID'];

    // Include your database configuration file
    include '../../config.php';

    // Check if the logged-in user is authorized to update the property
    $checkOwnershipQuery = "SELECT * FROM tbl_property WHERE PropertyID = '$propertyID' AND (AdvertiserID = '$userID' OR '$userType' = 1)";
    $checkOwnershipResult = mysqli_query($conn, $checkOwnershipQuery);    

    if (mysqli_num_rows($checkOwnershipResult) > 0) {
        // Fetch property details for the update form
        $fetchPropertyQuery = "SELECT * FROM tbl_property WHERE PropertyID = '$propertyID'";
        $fetchPropertyResult = mysqli_query($conn, $fetchPropertyQuery);

        if (mysqli_num_rows($fetchPropertyResult) > 0) {
            $propertyData = mysqli_fetch_assoc($fetchPropertyResult);

            // Check if the update form is submitted
            if (isset($_POST['updateProperty'])) {
                // Retrieve form data
                $location = $_POST['location'];
                $users = $_POST['users'];
                $price = $_POST['price'];
                $type = $_POST['type'];
                $totalSpace = $_POST['totalSpace'];
                $availableSpace = $_POST['availableSpace'];
                $description = $_POST['description'];

                // Update the property in the database
                $updateQuery = "UPDATE tbl_property SET propertyLocation = '$location', UserID = '$users', price = '$price', type = '$type', totalSpace = '$totalSpace', availableSpace = '$availableSpace', Description = '$description' WHERE PropertyID = '$propertyID'";
                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    echo "<code>Property updated successfully.</code>";
                } else {
                    echo " <code>Error updating property: " . mysqli_error($conn) . "</code>";
                }
            }
        } else {
            echo "Property not found.";
        }
    } else {
        echo "You are not authorized to update this property.";
    }

    mysqli_close($conn);
} else {
    echo "PropertyID not provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Property</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php if (isset($propertyData)) : ?>
    <div class="container mt-5">
        <h1 class="mb-4">Update Property</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $propertyID; ?>" method="POST">
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" class="form-control" value="<?php echo $propertyData['propertyLocation']; ?>">
            </div>

            <div class="form-group">
                <label for="users">Users Sharing Space:</label>
                <input type="text" id="users" name="users" class="form-control" value="<?php echo $propertyData['UserID']; ?>">
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" class="form-control" value="<?php echo $propertyData['price']; ?>">
            </div>

            <div class="form-group">
                <label for="type">Type:</label>
                <input type="text" id="type" name="type" class="form-control" value="<?php echo $propertyData['type']; ?>">
            </div>

            <div class="form-group">
                <label for="totalSpace">Total Space:</label>
                <input type="text" id="totalSpace" name="totalSpace" class="form-control" value="<?php echo $propertyData['totalSpace']; ?>">
            </div>

            <div class="form-group">
                <label for="availableSpace">Available Space:</label>
                <input type="text" id="availableSpace" name="availableSpace" class="form-control" value="<?php echo $propertyData['availableSpace']; ?>">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control" rows="4" cols="50"><?php echo $propertyData['Description']; ?></textarea>
            </div>

            <button type="submit" name="updateProperty" class="btn btn-primary">Update Property</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
