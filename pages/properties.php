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
        <form id="search-form" action="" method="POST" enctype="multipart/form-data" class="row">
            <div class="form-group col-12">
                <input class="form-control" type="text" placeholder="Search" />
            </div>
        </form>
    </div>

    <div id="filter" style="display: none;">
        <form class="row">
            <div class="form-group col-sm-4 col-xs-6">
                <select data-filter="propertyLocation" class="filter-location filter form-control">
                    <option value="" selected disabled>Location</option>
                    <option value="">Show All Location</option>
                </select>
            </div>
            <div class="form-group col-sm-4 col-xs-6">
                <select data-filter="type" class="filter-type filter form-control">
                    <option value="" selected disabled>Type</option>
                    <option value="">Show All Type</option>
                </select>
            </div>
            <div class="form-group col-sm-2 col-md-2">
                <input id="minPriceFilter" data-filter="minPrice" class="filter-min-price filter form-control"
                    type="text" placeholder="Min Price" />
            </div>
            <div class="form-group col-sm-2 col-md-2">
                <input id="maxPriceFilter" data-filter="maxPrice" class="filter-max-price filter form-control"
                    type="text" placeholder="Max Price" />
            </div>
            <div class="form-group col-sm-2 col-md-2">
                <input data-filter="totalSpace" class="filter-totalSpace filter form-control" type="number"
                    placeholder="Total Space" />
            </div>
            <div class="form-group col-sm-2 col-md-2">
                <input data-filter="availableSpace" class="filter-availableSpace filter form-control" type="number"
                    placeholder="Available Space" />
            </div>
        </form>
    </div>

    <div class="row" id="properties"></div>

</div>
<?php
// Include the conn file
include('../config.php');

// Fetch selected fields from rows where stock is equal to 1
$query = "SELECT PropertyID, propertyLocation, type, Price, totalSpace, AvailableSpace, image FROM tbl_property WHERE IsDeleted = 0";
$result = mysqli_query($conn, $query);

if ($result) {
    // Check if any rows are returned
    if (mysqli_num_rows($result) > 0) {
        // Fetch all rows and store them in an array
        $productsData = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $row['image'] = 'advertiser/img/' . $row['image'];
            $productsData[] = $row;
        }
    } else {
        // No products found with stock equal to 1
        echo "No products found with stock equal to 1.";
        exit();
    }
} else {
    // Query execution error
    echo "Error: " . mysqli_error($conn);
    exit();
}
?>
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
<script>
    $(document).ready(function () {
        // Show filter section and hide show button
        $('#showFilterBtn').on('click', function () {
            $('#filter').show();
            $('#showFilterBtn').hide();
            $('#hideFilterBtn').show();
        });
        // Hide filter section and show show button
        $('#hideFilterBtn').on('click', function () {
            $('#filter').hide();
            $('#hideFilterBtn').hide();
            $('#showFilterBtn').show();
        });

        var data = <?php echo json_encode($productsData); ?>;

        var properties = "",
            locations = "",
            types = "";
        for (var i = 0; i < data.length; i++) {
            var PropertyID = data[i].PropertyID,
                propertyLocation = data[i].propertyLocation,
                type = data[i].type,
                price = data[i].Price,
                rawPrice = price.replace("$", ""),
                rawPrice = parseInt(rawPrice.replace(",", "")),
                totalSpace = data[i].totalSpace,
                availableSpace = data[i].AvailableSpace,
                image = data[i].image;
            // Create property cards
            properties += "<div class='col-sm-4 property'  data-id='" + PropertyID + "' data-propertyLocation='" + propertyLocation +
    "' data-type='" +
    type + "' data-price='" + rawPrice + "' data-totalSpace='" + totalSpace +
    "' data-availableSpace='" +
    availableSpace + "'><div class='property-inner text-center'><a href='pages/propertyPage.php?id=" + PropertyID + "'>";
properties += "<img src='" + image + "' alt='" +
    propertyLocation + "' class='img-fluid'></a><br />Location: " + propertyLocation + "<br />Type: " + type +
    "<br />Price: " +
    price + " $ per month<br />Total Space: " + totalSpace + " person<br />Available Space: " + availableSpace +
    " person</div></div>";

    $(document).on("click", ".property", function() {
    var propertyID = $(this).data("id");

    // Redirect to the property page with the updated propertyID
    window.location.href = 'pages/propertyPage.php?id=' + propertyID;
});

            // Create dropdown of locations
            if (locations.indexOf("<option value='" + propertyLocation + "'>" + propertyLocation +
                "</option>") == -1) {
                locations += "<option value='" + propertyLocation + "'>" + propertyLocation + "</option>";
            }
            // Create dropdown of types
            if (types.indexOf("<option value='" + type + "'>" + type + "</option>") == -1) {
                types += "<option value='" + type + "'>" + type + "</option>";
            }
        }
        $("#properties").html(properties);
        $(".filter-location").append(locations);
        $(".filter-type").append(types);
        var filtersObject = {};
        // On filter change
        $(".filter").on("keyup change", function () {
            var filterName = $(this).data("filter"),
                filterVal = $(this).val();
            if (filterVal == "") {
                delete filtersObject[filterName];
            } else {
                filtersObject[filterName] = filterVal;
            }
            var filters = "";
            for (var key in filtersObject) {
                if (filtersObject.hasOwnProperty(key)) {
                    // if (key === 'price') {
                    //     filters += "[data-"+key+"='" + parseInt(filtersObject[key].replace("$","").replace(",","")) + "']";
                    // } else {
                    //     filters += "[data-"+key+"='" + filtersObject[key] + "']";
                    // }
                    filters += "[data-" + key + "='" + filtersObject[key] + "']";
                }
            }
            if (filters == "") {
                $(".property").show();
            } else {
                $(".property").hide().filter(filters).show();
            }
        });

        // Default min and max prices
        var minPrice = 0;
        var maxPrice = 9999999;
        // On min price filter change
        $("#minPriceFilter").on("keyup", function () {
            minPrice = $(this).val() === '' ? 0 : parseInt($(this).val().replace("$", "").replace(",",
                ""));
            filterProperties();
        });
        // On max price filter change
        $("#maxPriceFilter").on("keyup", function () {
            maxPrice = $(this).val() === '' ? 9999999 : parseInt($(this).val().replace("$", "").replace(
                ",", ""));
            filterProperties();
        });
        // Function to filter properties based on min and max prices
        function filterProperties() {
            $(".property").each(function () {
                var propertyPrice = parseInt($(this).data("price"));
                if (propertyPrice >= minPrice && propertyPrice <= maxPrice) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        $("#clearFiltersBtn").on("click", function() {
        $(".filter").val(''); // Clear all filter inputs
        filtersObject = {}; // Clear the filters object
        minPrice = 0; // Reset min price
        maxPrice = 9999999; // Reset max price
        filterProperties(); // Apply filters (to reset visibility)
    });

        // Function to filter property cards based on search input
        $("#search-form input").on("keyup", function () {
            var searchText = $(this).val()
                .toLowerCase(); // Get the search text and convert to lowercase
            $(".property").each(function () {
                var location = $(this).data("propertylocation")
                    .toLowerCase(); // Get property location and convert to lowercase
                var type = $(this).data("type")
                    .toLowerCase(); // Get property type and convert to lowercase
                var price = $(this).data("price")
                    .toString(); // Get property price as string
                // Check if search text matches any property details
                if (location.includes(searchText) || type.includes(searchText) ||
                    price
                    .includes(searchText)) {
                    $(this)
                        .show(); // Show the property card if it matches the search text
                } else {
                    $(this)
                        .hide(); // Hide the property card if it doesn't match the search text
                }
            });
        });
    });
</script>