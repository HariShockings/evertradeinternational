<!-- CDN for html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<!-- CDN for SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['userID']) && isset($_SESSION['userType'])) {
    $userType = intval($_SESSION['userType']);
} else {
    header("Location: unauthorized.php");
    exit();
}

// Include the database connection
include '../../config.php';

// Fetch third-party advertisements based on showTime intervals
$sql = "SELECT * FROM tbl_third_party_advertisement WHERE IsDeleted = 0 ORDER BY showTime";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo '<div class="container mt-4">';
    echo '<div class="row mb-4">';
    echo '<div class="col-md-6">';
    echo '<input type="text" id="searchInput" class="form-control" placeholder="Search...">';
    echo '</div>';
    echo '<div class="col-md-6 text-left">';
    echo '<a href="functions/add_advertisement.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Advertisement</a>';
    echo '<button id="downloadPdfBtn" class="btn btn-danger ml-2"><i class="fas fa-download"></i> PDF</button>';
    echo '<button id="downloadExcelBtn" class="btn btn-success ml-2"><i class="fas fa-download"></i> Excel</button>';
    echo '</div>';
    echo '</div>';

    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-striped" id="advertisementContainer">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>User</th>';
    echo '<th>Description</th>';
    echo '<th>Image</th>';
    echo '<th>Show Time</th>';
    echo '<th>Link</th>';
    if ($userType == 1) { // Only show Actions column for admin users
        echo '<th class="actions">Actions</th>';
    }
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['ThirdPartyAdvertisementID'] . '</td>';
        echo '<td>' . $row['UserID'] . '</td>';
        echo '<td style="max-width: 200px; word-wrap: break-word;">';   
        $description = $row['Description'];
        $description = wordwrap($description, 50, "<br/>", true);
        echo $description;
        echo '</td>';
        echo '<td style="max-width: 200px; word-wrap: break-word;">';
        $imagePath = $row['Image'];
        $imageName = basename($imagePath);
        echo $imageName;
        echo '</td>';
        echo '<td>' . $row['showTime'] . '</td>';
        echo '<td><a href="' . $row['Link'] . '" target="_blank">' . $row['Link'] . '</a></td>';
        if ($userType == 1) { // Actions for admin users
            echo '<td class="actionBtn">';
            echo '<a href="functions/edit_advertisement.php?id=' . $row['ThirdPartyAdvertisementID'] . '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
            echo '<a href="functions/delete_advertisement.php?id=' . $row['ThirdPartyAdvertisementID'] . '" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>';
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
} else {
    echo '<div class="container mt-4">';
    echo '<div class="alert alert-warning" role="alert">No advertisements found.</div>';
    echo '</div>';
}

mysqli_close($conn);
?>

<script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            $('#advertisementContainer tr').each(function() {
                var lineText = $(this).text().toLowerCase();
                if (lineText.indexOf(searchText) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });

        $('#downloadPdfBtn').click(function () {
            generatePDF('#advertisementContainer', 'all_advertisements.pdf');
            setTimeout(function () {
            location.reload();
        }, 1000);
        });

        $('#downloadExcelBtn').click(function () {
            generateExcel();
        });

        function generatePDF(selector, fileName) {
            const element = document.querySelector(selector);

            const actionsColumns = element.querySelectorAll('.actions, .actionBtn');
        if (actionsColumns) {
            actionsColumns.forEach(column => {
                column.style.display = 'none';
            });
        }

            const options = {
                margin:       [20, 20, 20, 20],
                filename:     fileName,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'pt', format: 'a4', orientation: 'portrait' }
            };
            
            html2pdf().set(options).from(element).save();
        }

        function generateExcel() {
            // Create a new workbook
            var wb = XLSX.utils.book_new();
            // Convert table to worksheet
            var ws = XLSX.utils.table_to_sheet(document.getElementById('advertisementContainer'));

            // Apply date format to the DateTime column
            var dateColIdx = 1; // Assuming DateTime column is the second column (index 1)
            var dateFormat = 'dd/mm/yyyy HH:mm:ss'; // Modify the date format as needed
            ws['!cols'] = [{ width: 20 }, { width: 25 }, { width: 15 }, { width: 15 }, { width: 15 }, { width: 15 }]; // Adjust column widths as needed

            // Iterate through rows to apply date format
            for (var i = 1; i < ws.length; i++) {
                var cellRef = XLSX.utils.encode_cell({ r: i, c: dateColIdx });
                if (ws[cellRef]) {
                    ws[cellRef].z = dateFormat;
                }
            }

            // Add table headings to Excel
            const headings = Array.from(document.querySelectorAll('#advertisementContainer th')).map(th => th.textContent.trim());
            XLSX.utils.sheet_add_aoa(ws, [headings], { origin: 'A1' });

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Advertisements');
            // Save workbook as Excel file
            XLSX.writeFile(wb, 'advertisements.xlsx');
        }
    });
</script>
