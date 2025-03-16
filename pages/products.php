<?php
include('../config.php');

// Fetch categories with at least one product
$category_query = "SELECT DISTINCT c.id, c.name FROM tbl_category c 
                   JOIN tbl_hardware h ON c.id = h.category_id WHERE h.stock > 0";
$category_result = $conn->query($category_query);

// Fetch products dynamically
$product_query = "SELECT h.id, h.name, h.images, h.stock, h.page_slug, c.name AS category 
                  FROM tbl_hardware h
                  LEFT JOIN tbl_category c ON h.category_id = c.id";
$product_result = $conn->query($product_query);
?>

<div class="container">
    <input type="text" class="form-control w-100" id="searchHardware" placeholder="Search Hardware...">
    <div class="text-center my-4">
        <a class="hardware-filter-link" href="#" data-filter="all">All</a>
        <?php while ($category = $category_result->fetch_assoc()): ?>
            <a class="hardware-filter-link" href="#" data-filter="hardware-<?= strtolower(str_replace(' ', '-', $category['name'])); ?>">
                <?= htmlspecialchars($category['name']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="row g-4">
    <?php while ($product = $product_result->fetch_assoc()): 
        $images = json_decode($product['images'], true);
        $category_class = "hardware-" . strtolower(str_replace(' ', '-', $product['category']));
        $image_src = !empty($images[0]) ? "uploads/" . htmlspecialchars($images[0]) : "assets/img/placeholder.jpg";
        $out_of_stock = $product['stock'] <= 0;
    ?>
    <div class="col-md-4 col-sm-6 hardware-item <?= $category_class; ?>" 
         data-id="<?= $product['id']; ?>" 
         data-product="<?= urlencode($product['page_slug']); ?>">
        <a href="productData.php?product=<?= urlencode($product['page_slug']); ?>"> <!-- SEO-friendly URL -->
        <div class="hardware-img-wrapper">
            <img src="<?= $image_src; ?>" alt="<?= htmlspecialchars($product['name']); ?>" 
                 onerror="this.onerror=null; this.src='assets/img/placeholder.jpg'">
            <div class="hardware-trigger" style="<?= $out_of_stock ? 'background: red; color: white;' : '' ?>">
                <?= $out_of_stock ? 'Out of Stock & View More Details' : 'See More' ?>
            </div>
            <div class="hardware-name"> <?= htmlspecialchars($product['name']); ?> </div>
        </div>
        </a>
    </div>
    <?php endwhile; ?>
</div>
</div>

<script>
$(document).ready(function() {
    // Category filter
    $(".hardware-filter-link").click(function(e) {
        e.preventDefault();
        var filter = $(this).data("filter");
        $(".hardware-item").hide();
        if (filter == "all") {
            $(".hardware-item").fadeIn();
        } else {
            $("." + filter).fadeIn();
        }
    });

    // Search filter
    $("#searchHardware").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".hardware-item").filter(function() {
            $(this).toggle($(this).find(".hardware-name").text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>

<?php $conn->close(); ?>

