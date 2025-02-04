<?php
session_start();

// Check if the user is logged in and their user type is authorized (1 for admin)
if (!isset($_SESSION['userID']) || $_SESSION['userType'] != 1) {
    header("Location: unauthorized.php");
    exit();
}

// Include your database configuration file
include '../../config.php';

// Check if the add form is submitted
if (isset($_POST['addAdvertisement'])) {
    // Retrieve form data
    $description = $_POST['description'];
    $showTime = $_POST['showTime'];
    $link = $_POST['link'];
    $userID = $_SESSION['userID']; // Assuming userID is obtained from the session

    // File upload handling
    $targetDir = "../../advertiser/uploads/"; // Directory where uploaded images will be stored
    $imageName = basename($_FILES["image"]["name"]); // Get only the image name
    $targetFile = $targetDir . $imageName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["addAdvertisement"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (max allowed size in bytes)
    $maxFileSize = 500000; // 500KB
    if ($_FILES["image"]["size"] > $maxFileSize) {
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
    // if everything is ok, try to upload file, compress, resize, and then insert into database
    } else {
        // Compress and resize the image
        $compressedImage = compressAndResizeImage($_FILES["image"]["tmp_name"], $targetFile, 75, 800, 600); // Adjust quality (75) and dimensions (800x600) as needed

        if ($compressedImage) {
            echo "<code>The file " . htmlspecialchars($imageName) . " has been uploaded.</code>";

            // Insert new advertisement into the database
            $insertQuery = "INSERT INTO tbl_third_party_advertisement (UserID, IsDeleted, Description, Image, showTime, Link) VALUES ('$userID', 0, '$description', '$imageName', '$showTime', '$link')";
            $insertResult = mysqli_query($conn, $insertQuery);

            if ($insertResult) {
                echo "<code>Advertisement added successfully.</code>";
            } else {
                echo "<code>Error adding advertisement: " . mysqli_error($conn) . "</code>";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

/**
 * Compresses and resizes an image using GD library functions.
 *
 * @param string $sourceFile The path to the source image file.
 * @param string $targetFile The path to save the compressed and resized image.
 * @param int $quality The image quality (0-100, 100 being the best quality).
 * @param int $maxWidth The maximum width of the resized image.
 * @param int $maxHeight The maximum height of the resized image.
 * @return bool True if the image was successfully compressed and resized, false otherwise.
 */
function compressAndResizeImage($sourceFile, $targetFile, $quality, $maxWidth, $maxHeight) {
    // Get image dimensions
    list($width, $height) = getimagesize($sourceFile);

    // Calculate new dimensions while maintaining aspect ratio
    $aspectRatio = $width / $height;
    if ($aspectRatio > 1) {
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $aspectRatio;
    } else {
        $newHeight = $maxHeight;
        $newWidth = $maxHeight * $aspectRatio;
    }

    // Create a new image from the source file
    $sourceImage = imagecreatefromstring(file_get_contents($sourceFile));
    if (!$sourceImage) {
        return false;
    }

    // Create a new true color image with the new dimensions
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    if (!$newImage) {
        return false;
    }

    // Copy and resize the source image to the new image
    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Save the compressed and resized image to the target file
    $success = imagejpeg($newImage, $targetFile, $quality);

    // Free up memory
    imagedestroy($sourceImage);
    imagedestroy($newImage);

    return $success;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Advertisement</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Add Advertisement</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="image">Image Upload:</label>
            <input type="file" id="image" name="image" class="form-control-file">
        </div>

        <div class="form-group">
            <label for="showTime">Show Time (seconds):</label>
            <input type="number" id="showTime" name="showTime" class="form-control">
        </div>

        <div class="form-group">
            <label for="link">Link:</label>
            <input type="text" id="link" name="link" class="form-control">
        </div>

        <button type="submit" name="addAdvertisement" class="btn btn-primary">Add Advertisement</button>
    </form>
</div>

</body>
</html>
