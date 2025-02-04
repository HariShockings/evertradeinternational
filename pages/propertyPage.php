<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Owl Carousel CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/product.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://www.payhere.lk/lib/payhere.js"></script>


    <style>
        .star-rating,
        .booking {
            line-height: 32px;
            font-size: 1.25em;
        }

        .star-rating .fa-star {
            color: yellow;
        }

        .booking .fa-user {
            color: green;
        }
    </style>
</head>

<body>
    <?php if(!isset($_SESSION['userID'])){
        echo "Login to Book your Desired Property!";
    } ?>

    <?php
// Include the database configuration file
include('../config.php');

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Sanitize and validate the ID parameter
    $propertyID = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "SELECT PropertyID, UserID, propertyLocation, type, Price, totalSpace, availableSpace, image, image2, image3, image4, Description, AdvertiserID, DateTime FROM tbl_property WHERE PropertyID = '$propertyID' AND IsDeleted = 0";
    $result = mysqli_query($conn, $query);

        if ($result) {
            // Check if a row is returned
            if (mysqli_num_rows($result) == 1) {
                // Fetch the property details
                $propertyData = mysqli_fetch_assoc($result);

                // Fetch additional data (e.g., advertiser name)
                $advertiserID = $propertyData['AdvertiserID'];
                $advertiserQuery = "SELECT FirstName,Email FROM tbl_user WHERE UserID = '$advertiserID'";
                $advertiserResult = mysqli_query($conn, $advertiserQuery);

                if ($advertiserResult && mysqli_num_rows($advertiserResult) == 1) {
                    $advertiserData = mysqli_fetch_assoc($advertiserResult);
                    $advertiserName = $advertiserData['FirstName'];
                } else {
                    $advertiserName = "Unknown";
                }
        // yudhee
                $propertyID = $propertyData['PropertyID'];

                // Get the average rating and count of ratings in one query
                $ratingsQuery = "SELECT AVG(Rating) AS AverageRating, COUNT(*) AS NumRatings FROM tbl_propertyrating WHERE PropertyID = '$propertyID'";
                $ratingsResult = mysqli_query($conn, $ratingsQuery);
            
                if ($ratingsResult && mysqli_num_rows($ratingsResult) == 1) {
                    $ratingsData = mysqli_fetch_assoc($ratingsResult);
                    if ($ratingsData['AverageRating'] !== null) {
                        $averageRating = floatval($ratingsData['AverageRating']); // Convert to float
                    } else {
                        $averageRating = 0; // Default to 0 if average rating is null
                    }
                    $numRatings = intval($ratingsData['NumRatings']); // Convert to integer
                } else {
                    $averageRating = 0; // Default to 0 if no ratings are found
                    $numRatings = 0;
                }
                
            }
        }

?>

    <div class="card-wrapper mt-5">
        <div class="card">
            <!-- card left -->
            <div class="product-imgs">
                <div class="img-display">
                    <div class="img-showcase">
                        <?php echo "<img src='../advertiser/img/" . $propertyData['image'] . "' alt='Property Image'>"?>
                        <?php echo "<img src='../advertiser/img/" . $propertyData['image2'] . "' alt='Property Image'>"?>
                        <?php echo "<img src='../advertiser/img/" . $propertyData['image3'] . "' alt='Property Image'>"?>
                        <?php echo "<img src='../advertiser/img/" . $propertyData['image4'] . "' alt='Property Image'>"?>
                    </div>
                </div>
                <div class="img-select">
                    <div class="img-item">
                        <a href="#" data-id="1">
                            <?php echo "<img src='../advertiser/img/" . $propertyData['image'] . "' alt='Property Image'>"?>
                        </a>
                    </div>
                    <div class="img-item">
                        <a href="#" data-id="2">
                            <?php echo "<img src='../advertiser/img/" . $propertyData['image2'] . "' alt='Property Image'>"?>
                        </a>
                    </div>
                    <div class="img-item">
                        <a href="#" data-id="3">
                            <?php echo "<img src='../advertiser/img/" . $propertyData['image3'] . "' alt='Property Image'>"?>
                        </a>
                    </div>
                    <div class="img-item">
                        <a href="#" data-id="4">
                            <?php echo "<img src='../advertiser/img/" . $propertyData['image4'] . "' alt='Property Image'>"?>
                        </a>
                    </div>
                </div>
            </div>
            <!-- card right -->
            <div class="product-content">
                <h2 class="product-title">
                    <?php echo "" . $propertyData['type'] . " at <br>" . $propertyData['propertyLocation'] . "" ?></h2>
                <a href="../" class="product-link">visit to Website Findbodima</a>
                <div class="product-rating" id="productRatingContainer">

                    <!-- yudhee -->
                    <?php
                        // Calculate the number of full stars, half star, and empty stars
                        $fullStars = floor($averageRating);
                        $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

                        // Construct the HTML for the star ratings
                        $ratingHTML = '<div class="product-rating">';
                        for ($i = 0; $i < $fullStars; $i++) {
                            $ratingHTML .= '<i class="fas fa-star"></i>';
                        }
                        if ($hasHalfStar) {
                            $ratingHTML .= '<i class="fas fa-star-half-alt"></i>';
                        }
                        for ($i = 0; $i < $emptyStars; $i++) {
                            $ratingHTML .= '<i class="far fa-star"></i>';
                        }
                        $ratingHTML .= '<span>' . number_format($averageRating, 1) . ' (' . $numRatings . ')</span>';
                        $ratingHTML .= '</div>';
                        ?>

                    <!-- Display the star ratings HTML -->
                    <?php echo $ratingHTML; ?>

                    <!-- yudhee -->
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#ratingModal">Rate
                        Now</button>
                    <!-- yudhee -->
                </div>

                <div class="product-price">
                    <p class="new-price">Price : <span><?php echo "" . $propertyData['Price'] . " $ per month" ?></span>
                    </p>
                </div>

                <div class="product-detail">
                    <h2>about this Property: </h2>
                    <p><?php echo $propertyData['Description']; ?></p>
                    <ul>
                        <li>Advertiser Name: <span><?php echo "$advertiserName" ?></span></li>
                        <li>Type: <span><?php echo $propertyData['type']; ?></span></li>
                        <li>Total Space: <span><?php echo $propertyData['totalSpace']; ?> <i
                                    class="fa fa-users"></i></span></li>
                        <li>Available Space: <span><?php echo $propertyData['availableSpace']; ?> <i
                                    class="fa fa-users"></i></span></li>
                        <li>Users Sharing Space: <span><?php echo $propertyData['UserID']; ?></span></li>
                    </ul>
                </div>

                <div class="purchase-info">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookingModal">Book Now</button>
                    <button class="btn btn-danger">Inquiry</button>
                    <p class="text-secondary"><small>Contact <?php echo "$advertiserName" ?>:
                            <?php echo $advertiserData['Email']; ?></small></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal  yudhee-->
    <div class="modal fade" id="ratingModal" tabindex="-1" role="dialog" aria-labelledby="ratingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Rate Now</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <!-- Center the content horizontally -->
                        <div class="col-lg-6">
                            <!-- Adjust the column size as needed -->
                            <div class="star-rating d-flex justify-content-center">
                                <!-- Center the stars within the column -->
                                <span class="fa fa-star text-secondary pl-0 pr-2" data-rating="1"></span>
                                <span class="fa fa-star text-secondary px-2" data-rating="2"></span>
                                <span class="fa fa-star text-secondary px-2" data-rating="3"></span>
                                <span class="fa fa-star text-secondary px-2" data-rating="4"></span>
                                <span class="fa fa-star text-secondary pl-2 pr-0" data-rating="5"></span>
                                <input type="hidden" name="rating" class="rating-value" value="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="resetRatingBtn">Reset</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveRatingBtn">Save Rating</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal  chamodi-->
    <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Book Now</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <form id="bookingForm">
                            <p>Total Space: <span><?php echo $propertyData['totalSpace']; ?> <i
                                    class="fa fa-users"></i></span></p>
                    <p>Available Space: <span><?php echo $propertyData['availableSpace']; ?> <i
                                    class="fa fa-users"></i></span></p>
                    <p>Users Sharing Space: <span><?php echo $propertyData['UserID']; ?></span></p>
                                <div class="booking d-flex justify-content-start">
                                    <label for="bookingSpaces" class="text-danger">Select Spaces:</label>
                                    <input type="number" id="bookingSpaces" name="bookingSpaces" min="1"
                                        max="<?php echo $propertyData['availableSpace']; ?>" required>
                                    <input type="hidden" name="propertyID" value="<?php echo $propertyID; ?>">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                   
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBookingBtn" onclick="paymentGateWay();">Book Spaces</button>
                </div>
            </div>
        </div>

        <?php 
    } else {
    echo "Invalid request. Property ID is missing.";
}
?>


<!-- payhere -->
<script src="../assets/js/payhere.js"></script>
<script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>


        <script>
            const imgs = document.querySelectorAll('.img-select a');
            const imgBtns = [...imgs];
            let imgId = 1;
            imgBtns.forEach((imgItem) => {
                imgItem.addEventListener('click', (event) => {
                    event.preventDefault();
                    imgId = imgItem.dataset.id;
                    slideImage();
                });
            });

            function slideImage() {
                const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;
                document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
            }
            window.addEventListener('resize', slideImage);
        </script>

        <!-- yudhee -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating span');
    const ratingInput = document.querySelector('.rating-value');

    stars.forEach((star) => {
        star.addEventListener('click', () => {
            const ratingValue = star.getAttribute('data-rating');
            ratingInput.value = ratingValue;
            // Highlight selected stars
            stars.forEach((s) => {
                const sRating = s.getAttribute('data-rating');           
                s.classList.toggle('text-secondary', sRating > ratingValue);
            });
        });
    });

    // Reset button functionality
    const resetRatingBtn = document.getElementById('resetRatingBtn');
    resetRatingBtn.addEventListener('click', () => {
        ratingInput.value = ''; // Clear the rating input
        stars.forEach((s) => {
            s.classList.add('text-secondary');
        });
    });

    // Save rating button functionality
    const saveRatingBtn = document.getElementById('saveRatingBtn');
    saveRatingBtn.addEventListener('click', () => {
        const ratingValue = ratingInput.value;
        const propertyID = '<?php echo $propertyID; ?>'; // Assuming $propertyID is available in PHP
        // Send AJAX request to save the rating
        $.ajax({
            url: '../functions/save_rating.php',
            type: 'POST',
            data: {
                propertyID: propertyID,
                rating: ratingValue
            },
            success: function(response) {
                alert('Rating Saved: ' + ratingValue);
                // Optionally, you can handle the response from the server here
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Error occurred while saving rating.');
            }
        });
    });

    const bookingInput = document.getElementById('bookingSpaces');
const saveBookingBtn = document.getElementById('saveBookingBtn');
const totalSpacesElem = document.querySelector('.total-spaces');
const availableSpacesElem = document.querySelector('.available-spaces');

saveBookingBtn.addEventListener('click', () => {
    const bookingSpaces = parseInt(bookingInput.value);
    const propertyID = '<?php echo $propertyID; ?>';
    const userID = '<?php echo isset($_SESSION['userID']) ? $_SESSION['userID'] : ''; ?>'; // Get the user ID from the session

    // Check if userID is not empty before sending the AJAX request
    if (userID.trim() !== '') {
        // Check if propertyID is valid (not 0)
        if (propertyID !== '0') {
            // Send AJAX request to save the booking
            fetch('../functions/save_booking.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    bookingSpaces: bookingSpaces,
                    userID: userID,
                    propertyID: propertyID
                }),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if (data.status === 'success') {
                    alert('Booking Saved: ' + bookingSpaces);
                    updateSpacesDisplay(bookingSpaces);
                    // Call the payment gateway function
                    paymentGateWay(data);
                } else {
                    alert('Error occurred while saving booking: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error occurred while saving booking.');
            });
        } else {
            alert('Invalid property ID.');
        }
    } else {
        alert('User ID not available. Please log in.');
    }
});

function paymentGateWay() {
    // Your payment gateway code here
    console.log('Payment gateway function called.');
}



function updateSpacesDisplay(bookedSpaces) {
    const totalSpaces = parseInt(totalSpacesElem.textContent); // Parse the text content to integer
    const availableSpaces = parseInt(availableSpacesElem.textContent); // Parse the text content to integer
    const newTotalSpaces = totalSpaces - bookedSpaces;
    const newAvailableSpaces = availableSpaces - bookedSpaces;
    // Update the display
    totalSpacesElem.textContent = newTotalSpaces;
    availableSpacesElem.textContent = newAvailableSpaces;
}

});

        </script>

</body>

</html>