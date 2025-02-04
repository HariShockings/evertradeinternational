<style>
    .property {
        margin-bottom: 30px;
    }

    .property-inner {
        box-shadow: 0 0 10px rgba(0, 0, 0, .2);
        padding: 10px;
    }

    .property img {
        margin-bottom: 10px;
        max-width: 100%;
        height: auto;
    }
</style>

<div class="container mt-5">
    <div class="filterBtn my-2">
        <button id="showFilterBtn" class="btn btn-primary btn-sm">Show Filter</button>
        <button id="hideFilterBtn" class="btn btn-danger btn-sm" style="display: none;">Hide Filter</button>
        <button id="clearFiltersBtn" class="btn btn-secondary btn-sm">Clear Filters</button>
    </div>

    <div id="search">
        <form id="search-form" class="row">
            <div class="form-group col-12">
                <input id="searchInput" class="form-control" type="text" placeholder="Search" />
            </div>
        </form>
    </div>

    <div id="filter" style="display: none;">
        <form class="row">
            <div class="form-group col-sm-4">
                <select id="locationFilter" class="filter form-control">
                    <option value="">Select Location</option>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <select id="typeFilter" class="filter form-control">
                    <option value="">Select Type</option>
                </select>
            </div>
            <div class="form-group col-sm-2">
                <input id="minPriceFilter" class="filter form-control" type="number" placeholder="Min Price" />
            </div>
            <div class="form-group col-sm-2">
                <input id="maxPriceFilter" class="filter form-control" type="number" placeholder="Max Price" />
            </div>
        </form>
    </div>

    <div class="row" id="properties"></div>
    </div>
    <?php
    include('../config.php');

    $query = "SELECT * image FROM tbl_property WHERE IsDeleted = 0";
    $result = mysqli_query($conn, $query);

    $properties = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['image'] = 'advertiser/img/' . $row['image'];
            $properties[] = $row;
        }
    }

    echo json_encode($properties);
    ?>

<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<script>
    $(document).ready(function () {
    function loadProperties() {
        $.ajax({
            url: "fetch_properties.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                let propertiesHtml = "";
                let locations = new Set();
                let types = new Set();

                $.each(data, function (index, property) {
                    propertiesHtml += `<div class='col-sm-4 property' data-id='${property.PropertyID}' 
                        data-location='${property.propertyLocation}' data-type='${property.type}' 
                        data-price='${property.Price}'>
                        <div class='property-inner text-center'>
                            <a href='propertyPage.php?id=${property.PropertyID}'>
                                <img src='${property.image}' alt='${property.propertyLocation}' class='img-fluid'>
                            </a>
                            <br>Location: ${property.propertyLocation}
                            <br>Type: ${property.type}
                            <br>Price: $${property.Price} per month
                            <br>Total Space: ${property.totalSpace} persons
                            <br>Available Space: ${property.AvailableSpace} persons
                        </div>
                    </div>`;

                    locations.add(property.propertyLocation);
                    types.add(property.type);
                });

                $("#properties").html(propertiesHtml);

                $("#locationFilter").html(`<option value="">Select Location</option>` + 
                    [...locations].map(loc => `<option value="${loc}">${loc}</option>`).join(""));

                $("#typeFilter").html(`<option value="">Select Type</option>` + 
                    [...types].map(type => `<option value="${type}">${type}</option>`).join(""));
            }
        });
    }

    loadProperties();

    // Show/Hide Filter
    $("#showFilterBtn").click(function () {
        $("#filter").show();
        $("#showFilterBtn").hide();
        $("#hideFilterBtn").show();
    });

    $("#hideFilterBtn").click(function () {
        $("#filter").hide();
        $("#hideFilterBtn").hide();
        $("#showFilterBtn").show();
    });

    // Search Function
    $("#searchInput").on("keyup", function () {
        let searchText = $(this).val().toLowerCase();
        $(".property").each(function () {
            let location = $(this).data("location").toLowerCase();
            let type = $(this).data("type").toLowerCase();
            let price = $(this).data("price").toString();

            $(this).toggle(location.includes(searchText) || type.includes(searchText) || price.includes(searchText));
        });
    });

    // Filter Function
    $(".filter").on("change", function () {
        let selectedLocation = $("#locationFilter").val();
        let selectedType = $("#typeFilter").val();
        let minPrice = $("#minPriceFilter").val();
        let maxPrice = $("#maxPriceFilter").val();

        $(".property").each(function () {
            let location = $(this).data("location");
            let type = $(this).data("type");
            let price = parseFloat($(this).data("price"));

            let show = (!selectedLocation || location === selectedLocation) &&
                (!selectedType || type === selectedType) &&
                (!minPrice || price >= parseFloat(minPrice)) &&
                (!maxPrice || price <= parseFloat(maxPrice));

            $(this).toggle(show);
        });
    });

    // Clear Filters
    $("#clearFiltersBtn").click(function () {
        $(".filter").val("");
        loadProperties();
    });

});
</script>