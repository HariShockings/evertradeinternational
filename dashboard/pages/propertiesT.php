<!-- CDN for html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<!-- CDN for SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<?php
// Assuming you have started the session
session_start();

// Check if the user is logged in and their userType is 2 (advertiser)
if (isset($_SESSION['userType'])) {
    $userType = intval($_SESSION['userType']);
}

if (isset($_SESSION['userID']) && ($_SESSION['userType'] == 2 || $_SESSION['userType'] == 1)) {
    // Connect to your MySQL database
    include '../../config.php';

    if ($userType == 2) {
        // Display only properties belonging to the logged-in advertiser
        $userID = $_SESSION['userID'];
        $sql = "SELECT * FROM tbl_property WHERE AdvertiserID = '$userID' AND IsDeleted = 0";
    } elseif ($userType == 1) {
        // Display all properties for admin users
        $sql = "SELECT * FROM tbl_property WHERE IsDeleted = 0";
    } else {
        // Redirect or handle unauthorized access
        header("Location: unauthorized.php");
        exit();
    }

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="container">';
        echo '<div class="row mb-4">';
        echo '<div class="col-md-6">';
        echo '<input type="text" id="searchInput" class="form-control" placeholder="Search...">';
        echo '</div>';
        echo '<div class="col-md-6 text-left">';
        if ($userType == 1) { // Only show buttons for userType = 1
            echo '<a href="functions/add_property.php" class="btn btn-primary ml-2"><i class="fas fa-plus"></i> Add Property</a>';
            echo '<button id="downloadPdfBtn" class="btn btn-danger ml-2"><i class="fas fa-download"></i> PDF</button>';
            echo '<button id="downloadExcelBtn" class="btn btn-success ml-2"><i class="fas fa-download"></i> Excel</button>';
        }
        echo '</div>';
        echo '</div>';

        echo '<div class="row" id="propertiesContainer">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-lg-4 col-md-6 mb-4 propertyItem">';
            echo '<div class="card">';
            echo '<img src="../advertiser/img/' . $row['image'] . '" class="card-img-top" alt="Property Image" width="300px" height="225px">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $row['propertyLocation'] . ' : ( ' . $row['PropertyID'] . ' )</h5>';
            echo '<p class="card-text">' . $row['Description'] . '</p>';
            echo '</div>';
            echo '<ul class="list-group list-group-flush">';
            echo '<li class="list-group-item">Price: ' . $row['price'] . '</li>';
            echo '<li class="list-group-item">Type: ' . $row['type'] . '</li>';
            echo '<li class="list-group-item">Available Space: ' . $row['availableSpace'] . '</li>';
            echo '</ul>';
            echo '<div class="card-body">';
            echo '<a href="functions/update_property.php?id=' . $row['PropertyID'] . '" class="btn btn-primary"><i class="fas fa-pen"></i> Update</a>';
            echo '<a href="functions/delete_property.php?id=' . $row['PropertyID'] . '" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    } else {
        echo "No properties found.";
    }

    mysqli_close($conn);
} else {
    echo "You are not authorized to view this page.";
}
?>

<script>
$(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        $('.propertyItem').each(function() { // Change the selector to target the property items
            var lineText = $(this).text().toLowerCase();
            if (lineText.indexOf(searchText) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    $('#downloadPdfBtn').click(function() {
        var propertiesTable = convertToTable('#propertiesContainer');
        generatePDF(propertiesTable, 'all_properties.pdf');
    });

    $('#downloadExcelBtn').click(function() {
        var propertiesTable = convertToTable('#propertiesContainer');
        generateExcel(propertiesTable);
    });

    function convertToTable(selector) {
        const propertiesContainer = document.querySelector(selector);
        const propertiesTable = document.createElement('table');
        propertiesTable.classList.add('table', 'table-bordered');

        const headersRow = propertiesTable.insertRow();
        headersRow.innerHTML = '<th>Property ID & Location</th><th>Description</th><th>Price</th><th>Type</th><th>Available Space</th>';

        propertiesContainer.querySelectorAll('.propertyItem').forEach(propertyItem => {
            const rowData = propertyItem.querySelectorAll('.card-title, .card-text, .list-group-item');
            const row = propertiesTable.insertRow();
            rowData.forEach(data => {
                const cell = row.insertCell();
                cell.textContent = data.textContent.trim();
            });
        });

        return propertiesTable;
    }

    function generatePDF(table, fileName) {
        const options = {
            margin: [20, 20, 20, 20],
            filename: fileName,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'pt', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(options).from(table).save();
    }

    function generateExcel(table) {
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.table_to_sheet(table);

        XLSX.utils.book_append_sheet(wb, ws, 'Properties');
        XLSX.writeFile(wb, 'properties.xlsx');
    }
});

</script>

