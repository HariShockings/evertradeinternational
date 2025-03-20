<style>
    .pd-product-image {
        max-height: 400px;
        object-fit: cover;
    }
    .pd-image-indicator {
        cursor: pointer;
        background: transparent;
        transition: all 0.2s ease;
    }
    .pd-image-indicator.active {
        background: #1e02a5;
    }
    
    /* CSS Tabs */
    .pd-tabs {
        display: flex;
        border-bottom: 2px solid #ddd;
    }
    .pd-tabs label {
        padding: 10px 20px;
        cursor: pointer;
        font-weight: bold;
        color: #555;
        transition: 0.3s;
    }
    .pd-tabs label:hover {
        color: #1e02a5;
    }
    input[name="pd-tab-control"] {
        display: none;
    }
    input[name="pd-tab-control"]:checked + label {
        color: #1e02a5;
        border-bottom: 2px solid #1e02a5;
    }
    .pd-tab-container label{
        margin: 10px 15px;
        cursor: pointer;
    }
    .pd-tab-content {
        display: none;
        padding: 15px;
        background: rgba(223, 223, 223, 0.5);
        border-radius: 20px;
    }
    #pd-desc-tab:checked ~ .pd-content #pd-desc,
    #pd-usecases-tab:checked ~ .pd-content #pd-usecases {
        display: block;
    }

    .product-card {
        perspective: 1000px;
    }

    .product-details {
        position: relative;
        width: 100%;
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }

    .flipped {
        transform: rotateY(180deg);
    }
    .product-back {
        display: none;
    }
    .product-front, .product-back {
        width: 100%;
        backface-visibility: hidden;
    }

    .product-back {
        transform: rotateY(180deg);
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }

    .pd-review-icon, .pd-review-back {
        font-size: 28px;
        color: #1e02a5;
        cursor: pointer;
        transition: all 0.2s ease-in;
    }

    .pd-review-icon:hover, .pd-review-back:hover {
        opacity: 0.6;
    }

    .rating-stars i {
        font-size: 24px;
        cursor: pointer;
        transition: color 0.3s ease-in-out, transform 0.2s;
    }
    .rating-stars i:hover, .rating-stars i.fas {
        color: #ffc107;
        transform: scale(1.2);
    }
    .review-form{
        background: rgba(223, 223, 223, 0.5);
    }
</style>

<?php
include('../config.php');

if (isset($_GET['product'])) {
    $pageSlug = urldecode($_GET['product']);
    $productSlug = $conn->real_escape_string($pageSlug);

    $product_query = "SELECT h.id, h.name, h.images, h.description, h.use_cases, h.price, h.stock, c.name AS category
                      FROM tbl_hardware h
                      LEFT JOIN tbl_category c ON h.category_id = c.id
                      WHERE h.page_slug = '$productSlug'";

    $product_result = $conn->query($product_query);

    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
        $images = json_decode($product['images'], true);

        function formatTextWithPoints($text) {
            $lines = explode("\n", $text);
            $formattedText = '';
            foreach ($lines as $line) {
                $trimmedLine = trim($line);
                if (!empty($trimmedLine)) {
                    $formattedText .= "✅ " . htmlspecialchars($trimmedLine) . "<br>";
                }
            }
            return $formattedText;
        }

        $formattedDescription = formatTextWithPoints($product['description']);
        $formattedUseCases = formatTextWithPoints($product['use_cases']);

        $ownerResult = $conn->query("SELECT contact FROM tbl_owner LIMIT 1");
        $owner = $ownerResult->fetch_assoc();

        ?>
        
<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <div class="owl-carousel owl-theme">
                <?php
                if (!empty($images)) {
                    foreach ($images as $image) {
                        echo '<div class="item">
                                <img src="uploads/' . htmlspecialchars($image) . '" onerror="this.src=\'assets/img/placeholder.jpg\'" class="img-fluid pd-product-image">
                            </div>';
                    }
                } else {
                    echo '<div class="item">
                            <img src="assets/img/placeholder.jpg" class="img-fluid pd-product-image">
                        </div>';
                }
                ?>
            </div>
            <div class="d-flex justify-content-center mt-3">
            <?php
                if (!empty($images)) {
                    foreach ($images as $index => $image) {
                        echo '<img src="uploads/' . htmlspecialchars($image) . '" class="img-thumbnail pd-image-indicator mx-1" style="width: 60px; height: 60px;" data-index="' . $index . '" onerror="this.src=\'assets/img/placeholder.jpg\'">';
                    }
                }
            ?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="product-card mt-sm-4 mt-md-0 mt-lg-0">
                <div class="product-details">
                    <!-- Front Side (Product Details) -->
                    <div class="product-front p-4 bg-light rounded">
                        <div class="d-flex align-items-center justify-content-between">
                            <h2 class="mt-2"><?= htmlspecialchars($product['name']); ?></h2>
                            <a class="hardware-filter-link d-flex align-items-center"><?= htmlspecialchars($product['category']); ?></a>
                            <i class="fas fa-comments pd-review-icon"></i>
                        </div>
                        <p class="price mb-2"><strong>Price:</strong> $<?= number_format($product['price'], 2); ?></p>
                        <p class="<?= ($product['stock'] > 0) ? 'stock' : 'out-of-stock'; ?> mb-2">
                            <strong>Stock:</strong> <?= ($product['stock'] > 0) ? $product['stock'] . ' Available' : 'Out of Stock'; ?>
                        </p>

                        <div class="pd-tab-container">
                            <input type="radio" id="pd-desc-tab" name="pd-tab-control" checked>
                            <label for="pd-desc-tab">Product Description</label>

                            <input type="radio" id="pd-usecases-tab" name="pd-tab-control">
                            <label for="pd-usecases-tab">Use Cases</label>

                            <div class="pd-content">
                                <div class="pd-tab-content" id="pd-desc">
                                    <p><?= $formattedDescription; ?></p>
                                </div>
                                <div class="pd-tab-content" id="pd-usecases">
                                    <p><?= $formattedUseCases; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4">
                        <a class="btn btn-danger" href="tel:<?php echo htmlspecialchars($owner['contact']); ?>">
                            <i class="fas fa-phone mr-2" style="transform: rotate(90deg);"></i> Inquire Now
                        </a>
                            <button class="btn btn-def"><i class="fas fa-robot mr-2"></i> Ask Assitant</button>
                        </div>
                    </div>

                    <!-- Back Side (Reviews) -->
                    <div class="product-back">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <?php
                            $review_query = "SELECT COUNT(*) as total_reviews, AVG(rating) as avg_rating FROM tbl_review WHERE hardware_id = " . $product['id'];
                            $review_result = $conn->query($review_query);
                            $review_data = $review_result->fetch_assoc();

                            $totalReviews = $review_data['total_reviews'] ?? 0;
                            $avgRating = $totalReviews > 0 ? round($review_data['avg_rating'], 1) : 0;
                            $filledStars = floor($avgRating);
                            $halfStar = ($avgRating - $filledStars) >= 0.5 ? 1 : 0;
                            $emptyStars = 5 - ($filledStars + $halfStar);
                            ?>
                            <h3 class="mb-0">Customer Reviews</h3>
                            <i class="fas fa-arrow-left pd-review-back"></i>
                        </div>

                        <!-- Average Rating Section -->
                        <div class="d-flex align-items-center justify-content-center my-3">
                            <small class="mx-2 my-0">Rating <span class="text-muted">(AVG)</span> :</small>
                            <div class="text-warning">
                                <?php
                                echo str_repeat('<i class="fas fa-star"></i>', $filledStars);
                                echo $halfStar ? '<i class="fas fa-star-half-alt"></i>' : '';
                                echo str_repeat('<i class="far fa-star"></i>', $emptyStars);
                                ?>
                            </div>
                            <p class="mx-2 my-0"><?= $avgRating ?> out of 5 <small class="text-muted mx-1">(<?= $totalReviews ?> reviews)</small></p>
                        </div>

                        <div class="owl-carousel owl-theme review-carousel">
                            <?php
                            $review_query = "SELECT reviewer_name, rating, description FROM tbl_review WHERE hardware_id = " . $product['id'];
                            $review_result = $conn->query($review_query);

                            if ($review_result->num_rows > 0) {
                                while ($review = $review_result->fetch_assoc()) {
                                    echo '<div class="item p-3 bg-white rounded">
                                            <h5 class="mb-1">' . htmlspecialchars($review['reviewer_name']) . '</h5>
                                            <p class="text-warning mb-1">' . str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) . '</p>
                                            <p class="text-muted">' . htmlspecialchars($review['description']) . '</p>
                                        </div>';
                                }
                            } else {
                                echo '<div class="item p-3 bg-white rounded shadow-sm text-center">No reviews available.</div>';
                            }
                            ?>
                        </div>

                        <!-- Review Submission Form -->
                        <div class="review-form mt-4 p-3 border rounded">
                            <h5 class="mb-3">Leave Your Review</h5>
                            <form action="functions/submit_review.php" method="POST">
                                <input type="hidden" name="hardware_id" value="<?= $product['id'] ?>">

                                <!-- Name & Star Rating in Same Line -->
                                <div class="row d-flex align-items-center mb-3">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                    <input type="text" name="reviewer_name" class="form-control" placeholder="Your Name" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 rating-stars d-flex mt-sm-3 mt-md-0 mt-lg-0">                                        <input type="hidden" name="rating" id="ratingValue" value="5">
                                        <i class="fas fa-star fs-4 text-secondary mx-1" data-value="1"></i>
                                        <i class="fas fa-star fs-4 text-secondary mx-1" data-value="2"></i>
                                        <i class="fas fa-star fs-4 text-secondary mx-1" data-value="3"></i>
                                        <i class="fas fa-star fs-4 text-secondary mx-1" data-value="4"></i>
                                        <i class="fas fa-star fs-4 text-secondary mx-1" data-value="5"></i>
                                    </div>       
                                </div>

                                <div class="mb-3">
                                    <textarea name="description" class="form-control" rows="3" placeholder="Your Review" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-def w-100">Submit Review</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <?php
    } else {
        echo "<p class='text-center text-danger'>Product not found.</p>";
    }
} else {
    echo "<p class='text-center text-danger'>Invalid request.</p>";
}

$conn->close();
?>

<script>
    $(document).ready(function(){
        var owl = $('.owl-carousel');
        owl.owlCarousel({
            loop: true,
            margin: 10,
            nav: false,
            dots: false,
            autoplay: true,
            items: 1
        });

        $('.pd-image-indicator').click(function() {
            var index = $(this).data('index');
            owl.trigger('to.owl.carousel', [index, 300]);
            updateActiveIndicator(index);
        });

        owl.on('changed.owl.carousel', function(event) {
            var currentIndex = event.item.index % $('.pd-image-indicator').length;
            updateActiveIndicator(currentIndex);
        });

        function updateActiveIndicator(index) {
            $('.pd-image-indicator').removeClass('active');
            $('.pd-image-indicator').eq(index).addClass('active');
        }

        updateActiveIndicator(0);

        $('.pd-review-icon').click(function() {
            $('.product-details').addClass('flipped');
            $('.product-front').css('display', 'none');
            $('.product-back').css('display', 'block');
        });

        $('.pd-review-back').click(function() {
            $('.product-details').removeClass('flipped');
            $('.product-front').css('display', 'block');
            $('.product-back').css('display', 'none');

        });


        $('.review-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            items: 1
        });

        document.querySelectorAll('.rating-stars i').forEach(star => {
        star.addEventListener('mouseover', function() {
            let value = this.getAttribute('data-value');
            document.querySelectorAll('.rating-stars i').forEach(s => {
                s.classList.remove('text-warning');
                s.classList.add('text-secondary');
            });
            for (let i = 0; i < value; i++) {
                document.querySelectorAll('.rating-stars i')[i].classList.add('text-warning');
            }
        });

        star.addEventListener('click', function() {
            let value = this.getAttribute('data-value');
            document.getElementById('ratingValue').value = value;
        });

        document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
            let selectedRating = document.getElementById('ratingValue').value;
            document.querySelectorAll('.rating-stars i').forEach(s => {
                s.classList.remove('text-warning');
                s.classList.add('text-secondary');
            });
            for (let i = 0; i < selectedRating; i++) {
                document.querySelectorAll('.rating-stars i')[i].classList.add('text-warning');
            }
        });
    });
    });
</script>
