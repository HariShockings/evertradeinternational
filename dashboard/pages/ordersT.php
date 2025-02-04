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

// Fetch orders based on user type
if ($userType == 2) {
    // Fetch orders for the logged-in advertiser
    $userID = $_SESSION['userID'];
    $orderQuery = "SELECT * FROM tbl_order WHERE UserID = '$userID'";
} elseif ($userType == 1) {
    // Display all orders for admin users
    $orderQuery = "SELECT * FROM tbl_order";
} else {
    // Redirect or handle unauthorized access
    header("Location: unauthorized.php");
    exit();
}

$orderResult = mysqli_query($conn, $orderQuery);

if ($orderResult && mysqli_num_rows($orderResult) > 0) {
    echo '<div class="container mt-4">';
   
    
    $totalOrders = mysqli_num_rows($orderResult);
    echo '<p>Total Orders: ' . $totalOrders . '</p>';
    
    echo '<div class="row mb-4">';
    echo '<div class="col-md-6">';
    echo '<input type="text" id="searchInput" class="form-control" placeholder="Search...">';
    echo '</div>';
    echo '<div class="col-md-6 text-right">';
    if ($userType == 1) {
        // Add Order button for admin users
       echo '<a href="functions/add_order.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add Order</a>';
        echo '<button id="downloadPdfBtn" class="btn btn-danger ml-2"><i class="fas fa-download"></i> Download PDF</button>';
        echo '<button id="downloadExcelBtn" class="btn btn-success ml-2"><i class="fas fa-download"></i> Download Excel</button>';
    }
    echo '</div>';
    echo '</div>';

    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<div class="table-responsive">';
    echo '<table id="orderContainer" class="table table-bordered table-striped">';
    echo '<thead class="thead-dark">';
    echo '<tr>';
    echo '<th>Order ID</th>';
    echo '<th>Date Time</th>';
    echo '<th>User ID</th>';
    echo '<th>Property ID</th>';
    echo '<th>Space Required</th>';
    echo '<th>Status</th>';
    echo '<th id="actions">Actions</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    while ($orderRow = mysqli_fetch_assoc($orderResult)) {
        echo '<tr>';
        echo '<td>' . $orderRow['OrderID'] . '</td>';
        echo '<td>' . $orderRow['DateTime'] . '</td>';
        echo '<td>' . $orderRow['UserID'] . '</td>';
        echo '<td>' . $orderRow['PropertyID'] . '</td>';
        echo '<td>' . $orderRow['spaceReq'] . '</td>';
        echo '<td>' . $orderRow['Stts'] . '</td>';
        echo '<td class="actionBtn">';
        // Action buttons for update and delete
        echo '<a href="functions/update_order.php?id=' . $orderRow['OrderID'] . '" class="btn btn-primary"><i class="fas fa-pen"></i></a>';
        echo '<a href="functions/delete_order.php?id=' . $orderRow['OrderID'] . '" class="btn btn-danger ml-1"><i class="fas fa-trash-alt"></i></a>';
        if ($orderRow['Stts'] == 'pending') {
            echo '<button class="btn btn-success changeStatusBtn ml-1" data-id="' . $orderRow['OrderID'] . '">✓</button>';
        } elseif ($orderRow['Stts'] == 'done') {
            echo '<button class="btn btn-warning changeStatusBtn ml-1" data-id="' . $orderRow['OrderID'] . '">✗</button>';
        }
        echo '</td>';
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
    echo '<div class="alert alert-warning" role="alert">No orders found.</div>';
    echo '</div>';
}

mysqli_close($conn);
?>

<script>
    $(document).ready(function () {

        function generatePDF(selector, fileName) {
            const element = document.querySelector(selector);

            const actionsColumns = element.querySelectorAll('#actions, .actionBtn');
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

        // Download PDF button click event
        $('#downloadPdfBtn').click(function () {
            generatePDF('#orderContainer', 'all_orders.pdf');
            setTimeout(function () {
            location.reload();
        }, 1000);
        });

        function generateExcel() {
            // Create a new workbook
            var wb = XLSX.utils.book_new();
            // Convert table to worksheet
            var ws = XLSX.utils.table_to_sheet(document.getElementById('orderContainer'));

            // Apply date format to the DateTime column
            var dateColIdx = 1; // Assuming DateTime column is the second column (index 1)
            var dateFormat = 'dd/mm/yyyy HH:mm:ss'; // Modify the date format as needed
            ws['!cols'] = [{ width: 20 }, { width: 25 }, { width: 15 }, { width: 15 }, { width: 15 }, { width: 15 }, { width: 15 }]; // Adjust column widths as needed

            // Iterate through rows to apply date format
            for (var i = 1; i < ws.length; i++) {
                var cellRef = XLSX.utils.encode_cell({ r: i, c: dateColIdx });
                if (ws[cellRef]) {
                    ws[cellRef].z = dateFormat;
                }
            }

            // Add table headings to Excel
            const headings = Array.from(document.querySelectorAll('#orderContainer th')).map(th => th.textContent.trim());
            XLSX.utils.sheet_add_aoa(ws, [headings], { origin: 'A1' });

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Orders');
            // Save workbook as Excel file
            XLSX.writeFile(wb, 'orders.xlsx');
        }

        // Download Excel button click event
        $('#downloadExcelBtn').click(function () {
            generateExcel();
        });

        $('#searchInput').on('keyup', function () {
            var searchText = $(this).val().toLowerCase();
            $('#orderContainer tr').each(function () {
                var lineText = $(this).text().toLowerCase();
                if (lineText.indexOf(searchText) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
    function changeOrderStatus(orderID, newStatus) {
    $.ajax({
        url: 'functions/change_order_status.php',
        method: 'POST',
        data: { orderID: orderID, newStatus: newStatus },
        success: function (response) {
            if (response === 'success') {
                location.reload(); // Refresh the page on successful status change
            } else {
                alert('Failed to update status. Please try again.');
            }
        },
        error: function () {
            alert('Error updating status. Please try again later.');
        }
    });
}

// Change status button click event
$(document).on('click', '.changeStatusBtn', function () {
    var orderID = $(this).data('id');
    var currentStatus = $(this).text().trim();

    // Determine the new status based on the current status
    var newStatus = currentStatus === 'Mark as Done' ? 'done' : 'pending';

    // Confirm before changing status
    if (confirm('Are you sure you want to change the status to ' + newStatus + '?')) {
        changeOrderStatus(orderID, newStatus);
    }
});
    });
</script>