<?php
session_start();
include('../config.php');

// Function to generate a random order ID integer
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

if (isset($_SESSION['userID'])) {
    $userID = mysqli_real_escape_string($conn, $_SESSION['userID']);
    $propertyID = isset($_POST['propertyID']) ? $_POST['propertyID'] : 1; // Default propertyID as 1 if not provided
    $bookingSpaces = isset($_POST['bookingSpaces']) ? intval($_POST['bookingSpaces']) : 0;

    if ($propertyID !== '0') { // Check if propertyID is not 0
        // Generate a random order ID integer
        $orderID = generateRandomOrderID($conn);

        // Get the price from tbl_property
        $priceQuery = "SELECT price FROM tbl_property WHERE PropertyID = '$propertyID'";
        $priceResult = mysqli_query($conn, $priceQuery);
        $priceRow = mysqli_fetch_assoc($priceResult);
        $price = isset($priceRow['price']) ? $priceRow['price'] : 0;

        // Insert into tbl_order and tbl_payment in a single transaction
        $conn->begin_transaction(); // Start transaction
        $insertQuery = "INSERT INTO tbl_order (OrderID, DateTime, UserID, PropertyID, spaceReq, IsDeleted, Stts) 
                        VALUES ('$orderID', NOW(), '$userID', '$propertyID', $bookingSpaces, 0, 'pending')";
        $resultOrder = mysqli_query($conn, $insertQuery);

        $insertPaymentQuery = "INSERT INTO tbl_payment (DateTime, OrderID, UserID, Amount) 
                               VALUES (NOW(), '$orderID', '$userID', $price)";
        $resultPayment = mysqli_query($conn, $insertPaymentQuery);

        if ($resultOrder && $resultPayment) {
            $conn->commit(); // Commit the transaction
            $userQuery = "SELECT UserID, FirstName, LastName, HomeCity, Email FROM tbl_user WHERE UserID = '$userID'";
            $userResult = mysqli_query($conn, $userQuery);
            $userDetails = mysqli_fetch_assoc($userResult);

            $merchant_id = "1226673";
            $merchant_secret = "NjIyODg1NjU2MTExODk1ODMyMjIxMzExMDI5NjYzMzQyMzczODQ1";
            $currency = "LKR";
            $hash = strtoupper(
                md5(
                    $merchant_id . 
                    $orderID . 
                    number_format($price, 2, '.', '') . 
                    $currency .  
                    strtoupper(md5($merchant_secret)) 
                ) 
            );

            // Prepare array with price and user details
            $paymentData = [
                'status' => 'success',
                'propertyID' => $propertyID,
                'price' => $price,
                'userDetails' => $userDetails,
                'merchant_secret' => $hash,
                'order_id' => $orderID,
            ];

            // Convert array to JSON and echo
            echo json_encode($paymentData);
        } else {
            $conn->rollback(); // Rollback if any query fails
            echo json_encode(['status' => 'error', 'message' => 'Error occurred while saving booking or payment: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid property ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User is not logged in or user ID is missing.']);
}
?>
