<style>
    .btnExplore {
    border: 0;
    padding: 15px 30px 15px 30px;
    border-radius: 0px 50px 50px 50px;
    background-color: var(--color-accent) !important;
    color: white;
    z-index: 1;
}
.carousel-item {
    justify-content: center;
    align-items: center;
}

.carousel-item img {
    max-height: 600px;
    max-width: 100%;
    object-fit: contain;
}

.custom-carousel-indicators {
    position: relative;
    bottom: 20px;
    right: 70px;
    transform: translateX(-50%);
    list-style: none;
    display: flex;
    padding: 0;
    margin: 0;
    z-index: 1;
}

.custom-carousel-indicator {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin: 0 5px;
    cursor: pointer;
    overflow: hidden;
    transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out;
}

.custom-carousel-indicator.active {
    background-color: var(--color-accent);
    transform: scale(1.1);
}

.custom-carousel-indicator img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.custom-carousel-indicator.active {
    background-color: var(--color-accent);
}

.navbarBottom {
    display: none !important;
}

.activeN {
    border-bottom: 2px solid gold;
}

@media screen and (max-width: 912px) {
    .custom-carousel-indicators {
        top: 15px;
        right: 50%;
    }
}

@media screen and (max-width: 767px) {
    .custom-carousel-indicators {
        top: 80px;
        left: 50%;
    }
}
</style>
<?php
include('../config.php');

// Fetch Hero Section Data
$heroQuery = "SELECT * FROM tbl_hero LIMIT 1";
$heroResult = $conn->query($heroQuery);
$hero = $heroResult->fetch_assoc();

// Fetch Carousel Data
$carouselQuery = "SELECT * FROM tbl_carousel ORDER BY display_order ASC";
$carouselResult = $conn->query($carouselQuery);
$carouselItems = [];
if ($carouselResult->num_rows > 0) {
    while ($row = $carouselResult->fetch_assoc()) {
        $carouselItems[] = $row;
    }
}
?>

<div class="container-fluid mt-2 mb-2 mt-sm-5">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 d-flex align-items-center">
            <div class="ml-3">
                <h1><?= htmlspecialchars($hero['title']); ?></h1>
                <h2 class="font-weight-light text-md"><?= htmlspecialchars($hero['subtitle']); ?></h2>
                <p style="color: var(--color-secondary);"><?= htmlspecialchars($hero['description']); ?></p>
                <button class="btnExplore bg-dark"><?= htmlspecialchars($hero['button_text']); ?></button>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 d-flex justify-content-center align-items-center">
            <div id="carouselExample" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($carouselItems as $index => $item) : ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                            <img src="<?= htmlspecialchars($item['image_url']); ?>" class="d-block w-100"
                                 alt="<?= htmlspecialchars($item['alt_text']); ?>"
                                 onerror="this.onerror=null; this.src='assets/img/placeholder.jpg'">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Carousel Controls -->
                <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>

                <!-- Custom Carousel Indicators -->
                <ul class="custom-carousel-indicators d-flex justify-content-center mb-0">
                    <?php foreach ($carouselItems as $index => $item) : ?>
                        <li data-target="#carouselExample" data-slide-to="<?= $index; ?>" class="custom-carousel-indicator <?= $index === 0 ? 'active' : ''; ?>">
                            <img src="<?= htmlspecialchars($item['image_url']); ?>" 
                                 alt="<?= htmlspecialchars($item['alt_text']); ?>"
                                 onerror="this.onerror=null; this.src='assets/img/placeholder.jpg'">
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Ensure only one image is visible at a time
    $('.carousel-item').each(function(index) {
        if (index !== 0) {
            $(this).removeClass('active'); // Ensure only the first slide has 'active' class
        }
    });

    $('.custom-carousel-indicators li').click(function() {
        var slideIndex = $(this).data('slide-to');
        $('#carouselExample').carousel(slideIndex);
    });

    $('#carouselExample').on('slid.bs.carousel', function(event) {
        var activeIndex = $(event.relatedTarget).index();
        $('.custom-carousel-indicators li').removeClass('active');
        $('.custom-carousel-indicators li[data-slide-to="' + activeIndex + '"]').addClass('active');
    });
});
</script>
