<?php
// Start the session
session_start();

if (isset($_SESSION['userType'])) {
    $userType = intval($_SESSION['userType']);
}

if (!isset($_SESSION['userID']) && $_SESSION['userType'] != 1) {
    header("Location: unauthorized.php");
    exit();
}

// Check if OrderID is provided in the URL
if (isset($_GET['id'])) {
    $orderID = $_GET['id'];
    $userID = $_SESSION['userID'];

    // Include your database configuration file
    include '../../config.php';

    // Check if the logged-in user is authorized to update the order
    $checkOwnershipQuery = "SELECT * FROM tbl_order WHERE OrderID = '$orderID' AND (UserID = '$userID' OR '$userType' = 1)";
    $checkOwnershipResult = mysqli_query($conn, $checkOwnershipQuery);

    if (mysqli_num_rows($checkOwnershipResult) > 0) {
        // Fetch order details for the update form
        $fetchOrderQuery = "SELECT * FROM tbl_order WHERE OrderID = '$orderID'";
        $fetchOrderResult = mysqli_query($conn, $fetchOrderQuery);

        if (mysqli_num_rows($fetchOrderResult) > 0) {
            $orderData = mysqli_fetch_assoc($fetchOrderResult);

            // Check if the update form is submitted
            if (isset($_POST['updateOrder'])) {
                // Retrieve form data
                $dateTime = $_POST['dateTime'];
                $propertyID = $_POST['propertyID'];
                $spaceReq = $_POST['spaceReq'];
                $isDeleted = $_POST['isDeleted'];
                $status = $_POST['status'];

                // Update the order in the database
                $updateQuery = "UPDATE tbl_order SET DateTime = '$dateTime', PropertyID = '$propertyID', spaceReq = '$spaceReq', IsDeleted = '$isDeleted', Stts = '$status' WHERE OrderID = '$orderID'";
                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    echo "<code>Order updated successfully.</code>";
                } else {
                    echo " <code>Error updating order: " . mysqli_error($conn) . "</code>";
                }
            }
        } else {
            echo "Order not found.";
        }
    } else {
        echo "You are not authorized to update this order.";
    }

    mysqli_close($conn);
} else {
    echo "OrderID not provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php if (isset($orderData)) : ?>
    <div class="container mt-5">
        <h1 class="mb-4">Update Order</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $orderID; ?>" method="POST">
            <div class="form-group">
                <label for="dateTime">Date Time:</label>
                <input type="text" id="dateTime" name="dateTime"pattern="\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}"  class="form-control" value="<?php echo $orderData['DateTime']; ?>" Required>
            </div>

            <div class="form-group">
                <label for="propertyID">Property ID:</label>
                <input type="text" id="propertyID" name="propertyID" class="form-control" value="<?php echo $orderData['PropertyID']; ?>"Required>
            </div>

            <div class="form-group">
                <label for="spaceReq">Space Required:</label>
                <input type="text" id="spaceReq" name="spaceReq" class="form-control" value="<?php echo $orderData['spaceReq']; ?>"Required>
            </div>

            <div class="form-group">
                <label for="isDeleted">Is Deleted:</label>
                <input type="text" id="isDeleted" name="isDeleted" class="form-control" value="<?php echo $orderData['IsDeleted']; ?>"Required>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <input type="text" id="status" name="status" class="form-control" value="<?php echo $orderData['Stts']; ?>"Required>
            </div>

            <button type="submit" name="updateOrder" class="btn btn-primary">Update Order</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
