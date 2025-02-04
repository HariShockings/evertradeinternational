<?php

error_reporting(E_ERROR | E_WARNING);

session_start();

// Check if the user is logged in and their user type is authorized (2 for advertiser)
if (!isset($_SESSION['userID']) || !isset($_SESSION['userType']) || !in_array($_SESSION['userType'], [1, 2])) {
    header("Location: unauthorized.php");
    exit();
}

// Include your database configuration file
include '../../config.php';

// Check if the add form is submitted
if (isset($_POST['addProperty'])) {
    // Retrieve form data
    $propertyLocation = $_POST['propertyLocation'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $totalSpace = $_POST['totalSpace'];
    $availableSpace = $_POST['availableSpace'];
    $description = $_POST['description'];
    $userID = $_SESSION['userID'];

    // Generate a random PropertyID integer
    $propertyID = generateRandomID();

    // Check if the generated PropertyID already exists in the database
    while (checkPropertyIDExists($propertyID)) {
        $propertyID = generateRandomID(); // Regenerate until a unique ID is found
    }

    // File upload handling
    $targetDir = "../../advertiser/img/"; // Directory where uploaded images will be stored
    $imageNames = array(); // Array to store image names

    // Loop through each image file input
    for ($i = 1; $i <= 4; $i++) {
        if (isset($_FILES["image$i"]) && $_FILES["image$i"]["error"] == UPLOAD_ERR_OK) {
            $imageName = basename($_FILES["image$i"]["name"]); // Get only the image name
            $targetFile = $targetDir . $imageName;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
            // Check if file already exists
            if (file_exists($targetFile)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
    
            // Check file size (max allowed size in bytes)
            $maxFileSize = 5000000; // 5MB
            if ($_FILES["image$i"]["size"] > $maxFileSize) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
    
            // Allow certain file formats
            $allowedFileTypes = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowedFileTypes)) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
    
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file and store image name in array
            } else {
                if (move_uploaded_file($_FILES["image$i"]["tmp_name"], $targetFile)) {
                    $imageNames[] = $imageName;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
    
    // Insert new property into the database
    $insertQuery = "INSERT INTO tbl_property (PropertyID, IsDeleted, PropertyLocation, Price, Type, TotalSpace, AvailableSpace, Description, AdvertiserID, Image, Image2, Image3, Image4, DateTime) 
                    VALUES ('$propertyID', 0, '$propertyLocation', '$price', '$type', '$totalSpace', '$availableSpace', '$description', '$userID', '$imageNames[0]', '$imageNames[1]', '$imageNames[2]', '$imageNames[3]', NOW())";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        echo "<code>Property added successfully.</code>";
    } else {
        echo "<code>Error adding property: " . mysqli_error($conn) . "</code>";
    }
}

/**
 * Generates a random integer ID for properties.
 *
 * @return int Random integer ID.
 */
function generateRandomID() {
    return rand(100000, 999999); // You can adjust the range as needed
}

/**
 * Checks if a PropertyID already exists in the database.
 *
 * @param int $propertyID The PropertyID to check.
 * @return bool True if the PropertyID exists, false otherwise.
 */
function checkPropertyIDExists($propertyID) {
    global $conn;
    $checkQuery = "SELECT PropertyID FROM tbl_property WHERE PropertyID = '$propertyID'";
    $checkResult = mysqli_query($conn, $checkQuery);
    return mysqli_num_rows($checkResult) > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Add Property</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="propertyLocation">Property Location:</label>
            <input type="text" id="propertyLocation" name="propertyLocation" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="totalSpace">Total Space:</label>
            <input type="number" id="totalSpace" name="totalSpace" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="availableSpace">Available Space:</label>
            <input type="number" id="availableSpace" name="availableSpace" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="image1">Image 1:</label>
            <input type="file" id="image1" name="image1" class="form-control-file" required>
        </div>

        <div class="form-group">
            <label for="image2">Image 2:</label>
            <input type="file" id="image2" name="image2" class="form-control-file">
        </div>

        <div class="form-group">
            <label for="image3">Image 3:</label>
            <input type="file" id="image3" name="image3" class="form-control-file">
        </div>

        <div class="form-group">
            <label for="image4">Image 4:</label>
            <input type="file" id="image4" name="image4" class="form-control-file">
        </div>

        <button type="submit" name="addProperty" class="btn btn-primary">Add Property</button>
    </form>
</div>

</body>
</html>
