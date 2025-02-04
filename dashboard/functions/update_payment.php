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

    // Fetch the payment details based on the PaymentID
    $paymentQuery = "SELECT * FROM tbl_payment WHERE PaymentID = $paymentID";
    $paymentResult = mysqli_query($conn, $paymentQuery);

    if (mysqli_num_rows($paymentResult) > 0) {
        $paymentRow = mysqli_fetch_assoc($paymentResult);

        // Handle the form submission for updating the payment
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $dateTime = $_POST['dateTime'];
            $orderID = $_POST['orderID'];
            $userID = $_POST['userID'];
            $amount = $_POST['amount'];

            // Update the payment in the database
            $updateQuery = "UPDATE tbl_payment SET DateTime = '$dateTime', OrderID = '$orderID', UserID = '$userID', Amount = '$amount' WHERE PaymentID = $paymentID";
            if (mysqli_query($conn, $updateQuery)) {
                echo "Payment updated successfully.";
                // Redirect to a success page or another page after update
                // header("Location: success.php");
                // exit();
            } else {
                echo "Error updating payment: " . mysqli_error($conn);
            }
        }

        mysqli_close($conn);
    } else {
        echo "Payment not found.";
    }
} else {
    echo "Payment ID not provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Payment</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Payment</h1>
        <form action="update_payment.php?id=<?php echo $paymentRow['PaymentID']; ?>" method="POST">
            <div class="mb-3">
                <label for="dateTime" class="form-label">Date Time:</label>
                <input type="text" id="dateTime" name="dateTime" value="<?php echo $paymentRow['DateTime']; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="orderID" class="form-label">Order ID:</label>
                <input type="text" id="orderID" name="orderID" value="<?php echo $paymentRow['OrderID']; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="userID" class="form-label">User ID:</label>
                <input type="text" id="userID" name="userID" value="<?php echo $paymentRow['UserID']; ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount:</label>
                <input type="text" id="amount" name="amount" value="<?php echo $paymentRow['Amount']; ?>" class="form-control">
            </div>
            <button type="submit" name="updatePayment" class="btn btn-primary">Update Payment</button>
        </form>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
