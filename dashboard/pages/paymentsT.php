<!-- CDN for html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<!-- CDN for SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<?php
// Start the session
session_start();

// Check if the user is logged in and their userType is 1 or 2
if (isset($_SESSION['userID']) && isset($_SESSION['userType'])) {
    $userType = intval($_SESSION['userType']);
} else {
    header("Location: unauthorized.php");
    exit();
}

// Connect to the MySQL database
include '../../config.php';

if ($userType == 1) {
    // Display all payments for admin users
    $paymentQuery = "SELECT * FROM tbl_payment";
} elseif ($userType == 2) {
    // Display payments belonging to the logged-in advertiser
    $userID = $_SESSION['userID'];
    $paymentQuery = "SELECT * FROM tbl_payment WHERE UserID = '$userID'";
} else {
    // Redirect or handle unauthorized access
    header("Location: unauthorized.php");
    exit();
}
//fetching the tables
$paymentResult = mysqli_query($conn, $paymentQuery);

if (mysqli_num_rows($paymentResult) > 0) {
    echo '<div class="container mt-4">';
    echo '<div class="row mb-4">';
    echo '<div class="col-md-6">';
    echo '<input type="text" id="searchInput" class="form-control" placeholder="Search...">';
    echo '</div>';
    echo '<div class="col-md-6 text-left">';
    echo '<button id="downloadPdfBtn" class="btn btn-danger ml-2"><i class="fas fa-download"></i> PDF</button>';
    echo '<button id="downloadExcelBtn" class="btn btn-success ml-2"><i class="fas fa-download"></i> Excel</button>';
    echo '</div>';
    echo '</div>';

    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-striped" id="paymentContainer">';
    echo '<thead class="thead-dark">';
    echo '<tr>';
    echo '<th>Payment ID</th>';
    echo '<th>Date Time</th>';
    echo '<th>Order ID</th>';
    echo '<th>User ID</th>';
    echo '<th>Amount</th>';
    echo '<th class="actions">Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($paymentRow = mysqli_fetch_assoc($paymentResult)) {
        echo '<tr>';
        echo '<td>' . $paymentRow['PaymentID'] . '</td>';
        echo '<td>' . $paymentRow['DateTime'] . '</td>';
        echo '<td>' . $paymentRow['OrderID'] . '</td>';
        echo '<td>' . $paymentRow['UserID'] . '</td>';
        echo '<td>' . $paymentRow['Amount'] . '</td>';
        echo '<td class="actionBtn">'; // Actions column
        echo '<a href="functions/update_payment.php?id=' . $paymentRow['PaymentID'] . '" class="btn btn-primary"><i class="fas fa-pen"></i></a>';
        echo '<a href="functions/delete_payment.php?id=' . $paymentRow['PaymentID'] . '" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    echo '</div>';
     
} else {
    echo '<div class="container mt-4">';
    echo '<div class="alert alert-warning" role="alert">No payments found.</div>';
    echo '</div>';
}

mysqli_close($conn);
?>

<script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            $('#paymentContainer tr').each(function() {
                var lineText = $(this).text().toLowerCase();
                if (lineText.indexOf(searchText) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });

        $('#downloadPdfBtn').click(function () {
            generatePDF('#paymentContainer', 'all_payments.pdf');
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
            var ws = XLSX.utils.table_to_sheet(document.getElementById('paymentContainer'));

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
            const headings = Array.from(document.querySelectorAll('#paymentContainer th')).map(th => th.textContent.trim());
            XLSX.utils.sheet_add_aoa(ws, [headings], { origin: 'A1' });

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Payments');
            // Save workbook as Excel file
            XLSX.writeFile(wb, 'payments.xlsx');
        }
    });
</script>
