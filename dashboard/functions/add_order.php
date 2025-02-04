<?php
session_start();

// Check if the user is logged in and their user type is authorized
if (!isset($_SESSION['userID'])) {
    header("Location: unauthorized.php");
    exit();
}

// Include your database configuration file
include '../../config.php';

/**
 * Generates a unique OrderID for new orders.
 *
 * @param $conn The database connection object.
 * @return int A unique OrderID.
 */
function generateRandomOrderID($conn) {
    $randomID = rand(100, 999); // Generate a random number between 100 and 999

    // Check if the generated ID already exists in the database
    $checkQuery = "SELECT OrderID FROM tbl_order WHERE OrderID = '$randomID'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // If the ID already exists, generate ID + 1 until a unique ID is found
        while (mysqli_num_rows($checkResult) > 0) {
            $randomID++;
            $checkQuery = "SELECT OrderID FROM tbl_order WHERE OrderID = '$randomID'";
            $checkResult = mysqli_query($conn, $checkQuery);
        }
    }

    return $randomID;
}


// Fetch property options from tbl_property
$propertyQuery = "SELECT PropertyID, PropertyLocation FROM tbl_property";
$propertyResult = mysqli_query($conn, $propertyQuery);

// Fetch user options from tbl_user
$userQuery = "SELECT UserID, UserName FROM tbl_user";
$userResult = mysqli_query($conn, $userQuery);

// Check if the add form is submitted
if (isset($_POST['addOrder'])) {
    // Retrieve form data
    $orderID = generateRandomOrderID($conn);
    $dateTime = date('Y-m-d H:i:s'); // Current date and time
    $userIDsArray = $_POST['userIDs'];
    $propertyID = $_POST['propertyID'];
    $spaceReq = $_POST['spaceReq'];
    $status = 'pending'; // Initial status

    // Insert new order into the database
    $insertQuery = "INSERT INTO tbl_order (OrderID, DateTime, UserID, PropertyID, spaceReq, Stts) VALUES ";

    // Loop through each selected user ID
    foreach ($userIDsArray as $userID) {
        $userID = trim($userID); // Remove any extra spaces
        // Add values to the insert query
        $insertQuery .= "('$orderID', '$dateTime', '$userID', '$propertyID', '$spaceReq', '$status'),";
    }

    // Remove the trailing comma from the insert query
    $insertQuery = rtrim($insertQuery, ',');

    // Execute the insert query
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
        echo "<code>Order added successfully.</code>";
    } else {
        echo "<code>Error adding order: " . mysqli_error($conn) . "</code>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Add Order</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="propertyID">Property:</label>
            <select id="propertyID" name="propertyID" class="form-control" required>
                <option value="">Select Property</option>
                <?php
                if ($propertyResult && mysqli_num_rows($propertyResult) > 0) {
                    while ($row = mysqli_fetch_assoc($propertyResult)) {
                        echo '<option value="' . $row['PropertyID'] . '">' . $row['PropertyLocation'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="userIDs">User IDs (select multiple):</label>
            <select id="userIDs" name="userIDs[]" class="form-control" required>
                <?php
                if ($userResult && mysqli_num_rows($userResult) > 0) {
                    while ($row = mysqli_fetch_assoc($userResult)) {
                        echo '<option value="' . $row['UserID'] . '">' . $row['UserName'] . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="spaceReq">Space Required:</label>
            <input type="number" id="spaceReq" name="spaceReq" class="form-control" required>
        </div>

        <button type="submit" name="addOrder" class="btn btn-primary">Add Order</button>
    </form>
</div>

</body>
</html>
