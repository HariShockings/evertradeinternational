<?php
session_start();

// Check if the user is logged in and authorized
if (!isset($_SESSION['userID']) || $_SESSION['userType'] != 1) {
    header("Location: unauthorized.php");
    exit();
}

// Check if PropertyID is provided in the URL
if (isset($_GET['id'])) {
    $propertyID = $_GET['id'];

    // Connect to your MySQL database
    include '../../config.php';

    // Fetch property details based on PropertyID
    $propertyQuery = "SELECT * FROM tbl_propertyrating WHERE PropertyID = '$propertyID'";
    $propertyResult = mysqli_query($conn, $propertyQuery);

    if (mysqli_num_rows($propertyResult) > 0) {
        $propertyRow = mysqli_fetch_assoc($propertyResult);

        // Process update form submission
        if (isset($_POST['updateProperty'])) {
            // Retrieve updated form data
            $updatedPropertyID = $_POST['PropertyID'];
            $updatedRating = $_POST['rating'];

            // Update property details in the database
            $updateQuery = "UPDATE tbl_propertyrating SET PropertyID = '$updatedPropertyID', Rating = '$updatedRating' WHERE PropertyID = '$propertyID'";
            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                // Redirect to the property list page after successful update
                header("Location: ../");
                exit();
            } else {
                echo "Error updating property details: " . mysqli_error($conn);
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Update Property Rating</title>
            <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </head>
        <body>
        <div class="container">
            <h1 class="mt-5">Update Property Rating</h1>
            <div class="row mt-3">
                <div class="col-md-6">
                    <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $propertyID; ?>" method="POST">
                        <div class="form-group">
                            <label for="PropertyID">Property Name:</label>
                            <input type="text" id="PropertyID" name="PropertyID" class="form-control" value="<?php echo $propertyRow['PropertyID']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <input type="number" id="rating" name="rating" class="form-control" value="<?php echo $propertyRow['Rating']; ?>">
                        </div>
                        <button type="submit" name="updateProperty" class="btn btn-primary">Update Property</button>
                    </form>
                </div>
            </div>
        </div>
        </body>
        </html>
        <?php
    } else {
        echo "Property not found.";
    }

    mysqli_close($conn);
} else {
    echo "PropertyID not provided.";
}
?>
