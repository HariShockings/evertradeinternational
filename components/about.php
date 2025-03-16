<?php
// about-us-component.php

function displayAboutUsComponent($show = ['abt-data-1' => true, 'abt-data-2' => true, 'abt-data-3' => true, 'abt-data-4' => true, 'abt-data-5' => true, 'abt-data-6' => true]) {
    include('../config.php');

    // Fetch About Us Text
    $aboutUsQuery = "SELECT * FROM tbl_pages where pages = 'about_us' ";
    $aboutUsResult = $conn->query($aboutUsQuery);
    $aboutUs = $aboutUsResult->fetch_assoc();

    // Fetch Icons Data
    $iconsQuery = "SELECT * FROM tbl_icons";
    $iconsResult = $conn->query($iconsQuery);

    // Fetch MissionVision Data
    $missionVisionQuery = "SELECT * FROM tbl_mission_vision";
    $missionVisionResult = $conn->query($missionVisionQuery);

    // Fetch Achievements Data
    $achievementsQuery = "SELECT * FROM tbl_achievements";
    $achievementsResult = $conn->query($achievementsQuery);

    // Fetch Leadership Data
    $leadershipQuery = "SELECT * FROM tbl_leadership";
    $leadershipResult = $conn->query($leadershipQuery);
    ?>

    <?php if ($show['abt-data-1']) : ?>
    <div class="container-fluid p-0 m-0 abt-data-1">
        <img src="<?php echo 'uploads/' . $aboutUs['img_url']; ?>" alt="<?php echo $aboutUs['alt_text']; ?>" class="full-image" id="scrollImage">
    </div>
    <?php endif; ?>

    <?php if ($show['abt-data-2']) : ?>
    <div class="container my-5">
        <div class="row abt-data-2">
            <div class="col-12 text-center">
                <h2 class="fw-bold heading"><?php echo $aboutUs['heading']; ?></h2>
                <p><?php echo $aboutUs['description']; ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($show['abt-data-3']) : ?>
        <div class="row text-center mt-4 abt-data-3">
            <?php while ($icon = $iconsResult->fetch_assoc()) { ?>
                <div class="col-12 col-md-4 mb-4">
                    <div class="about-box shadow bg-light p-4">
                        <i class="fas <?php echo $icon['icon_name']; ?> <?php echo $icon['icon_color']; ?> fa-3x"></i>
                        <h4><?php echo $icon['title']; ?></h4>
                        <p><?php echo $icon['description']; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php endif; ?>

    <?php if ($show['abt-data-4']) : ?>
        <div class="row text-center mt-4 abt-data-4">
            <?php while ($missionVision = $missionVisionResult->fetch_assoc()) { ?>
                <div class="col-12 col-md-6 mb-4">
                    <div class="about-box shadow bg-light p-4">
                        <i class="fas <?php echo $missionVision['icon_name']; ?> <?php echo $missionVision['icon_color']; ?> fa-3x"></i>
                        <h4><?php echo $missionVision['heading']; ?></h4>
                        <p><?php echo $missionVision['description']; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php endif; ?>

    <?php if ($show['abt-data-5']) : ?>
        <div class="row text-center mt-5 abt-data-5">
            <div class="col-12">
                <h3 class="fw-bold mb-4 text-uppercase heading">Our Achievements</h3>
            </div>
            <?php while ($achievement = $achievementsResult->fetch_assoc()) { ?>
                <div class="col-md-3">
                    <div class="about-box shadow bg-light p-4">
                        <h2 class="fw-bold <?php echo $achievement['color']; ?>"><?php echo $achievement['count']; ?>+</h2>
                        <p><?php echo $achievement['description']; ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php endif; ?>

    <?php if ($show['abt-data-6']) : ?>
        <div class="row text-center mt-5 abt-data-6">
            <div class="col-12">
                <h3 class="fw-bold text-center mb-4 text-uppercase heading">Meet Our Leadership</h3>
            </div>
            <div id="teamCarousel" class="owl-carousel owl-theme">
                <?php while ($leader = $leadershipResult->fetch_assoc()) { ?>
                    <div class="item">
                        <div class="about-box shadow bg-light p-4">
                            <img src="<?php echo 'uploads/' . $leader['image_url']; ?>" alt="<?php echo $leader['position']; ?>" class="rounded-circle mb-3" width="100" onerror="this.onerror=null;this.src='assets/img/placeholder.jpg';">
                            <h4><?php echo $leader['name']; ?></h4>
                            <p class="text-muted"><?php echo $leader['position']; ?></p>
                            <p class="mb-1">“<?php echo $leader['quote']; ?>”</p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php endif; ?>

</div>
<?php
}
?>
<script>
    $(document).ready(function(){
        $("#teamCarousel").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 3000,
            responsive: {
                0: { items: 1 },
                600: { items: 2 },
                1000: { items: 3 }
            }
        });
    });
</script>
