function paymentGateWay() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = () => {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            alert(xhttp.responseText);
            // Payment completed. It can be a successful failure.
            payhere.onCompleted = function onCompleted(orderId) {
                console.log("Payment completed. OrderID:" + orderId);
                // Note: validate the payment and show success or failure page to the customer
            };

            // Payment window closed
            payhere.onDismissed = function onDismissed() {
                // Note: Prompt user to pay again or show an error page
                console.log("Payment dismissed");
            };

            // Error occurred
            payhere.onError = function onError(error) {
                // Note: show an error page
                console.log("Error:" + error);
            };
            var paymentData = JSON.parse(xhttp.responseText);
            if (paymentData) {
                var propertyID = paymentData.propertyID;
                var price = paymentData.price;
                var userDetails = paymentData.userDetails;

                // Modify payment object using fetched data
                var payment = {
                    "sandbox": true,
                    "merchant_id": "1226673", // Replace your Merchant ID
                    "return_url": "http://localhost/work/findBodima/pages/propertyPage.php?id=8&propertyID=" + paymentData.propertyID,
                    "cancel_url": "http://localhost/work/findBodima/pages/propertyPage.php?id=8&propertyID=" + paymentData.propertyID,
                    "notify_url": "http://sample.com/notify",
                    "order_id": paymentData.order_id,
                    "items": "Door bell wireless",
                    "amount": paymentData.price, // Use the fetched price here
                    "currency": "LKR",
                    "hash": paymentData.merchant_secret, // *Replace with generated hash retrieved from backend
                    "first_name": paymentData.userDetails.FirstName,
                    "last_name": paymentData.userDetails.LastName,
                    "email": paymentData.userDetails.Email,
                    "phone": "0771234567",
                    "address": "No.1, Galle Road",
                    "city": paymentData.userDetails.HomeCity,
                    "country": "Sri Lanka",
                    "delivery_address": "No. 46, Galle road, Ka.lutara South",
                    "delivery_city": "Kalutara",
                    "delivery_country": "Sri Lanka",
                    "custom_1": "",
                    "custom_2": ""
                };
            

                // Start payment with modified payment object
                payhere.startPayment(payment);
            } else {
                console.log("Error: Failed to fetch payment data.");
            }
        }
    }
    xhttp.open("GET", "../functions/save_booking.php", true);
    xhttp.send();
}