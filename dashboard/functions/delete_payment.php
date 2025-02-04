<?php
// Start the session
session_start();

// Check if the user is logged in and their userType is 1 or 2
if (isset($_SESSION['userID']) && isset($_SESSION['userType'])) {
    $userType = intval($_SESSION['userType']);
} else {
    header("Location: unauthorized.php");
    exit();
}

// Check if the payment ID is provided in the URL
if (isset($_GET['id'])) {
    $paymentID = intval($_GET['id']);

    // Connect to the MySQL database
    include '../../config.php';

    // Check if the user is authorized to delete this payment
    if ($userType == 1 || ($userType == 2 && canDeletePayment($paymentID, $_SESSION['userID'], $conn))) {
        // Perform the deletion query
        $deleteQuery = "DELETE FROM tbl_payment WHERE PaymentID = $paymentID";
        if (mysqli_query($conn, $deleteQuery)) {
            echo "Payment deleted successfully.";
            header("Location: ../");
        } else {
            echo "Error deleting payment: " . mysqli_error($conn);
        }
    } else {
        echo "You are not authorized to delete this payment.";
    }

    mysqli_close($conn);
} else {
    echo "Payment ID not provided.";
}

// Function to check if the user is authorized to delete the payment
function canDeletePayment($paymentID, $userID, $conn) {
    $checkQuery = "SELECT * FROM tbl_payment WHERE PaymentID = $paymentID AND UserID = '$userID'";
    $checkResult = mysqli_query($conn, $checkQuery);

    return (mysqli_num_rows($checkResult) > 0);
}
?>
