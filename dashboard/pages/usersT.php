<!-- CDN for html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<!-- CDN for SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<?php
// Start the session
session_start();

if (!isset($_SESSION['userType'])) {
    header("Location: unauthorized.php");
    exit();
}

$userType = intval($_SESSION['userType']);

// Connect to your MySQL database
include '../../config.php';

// Fetch user details based on user type
if ($userType == 1) {
    $userQuery = "SELECT * FROM tbl_user";
} elseif ($userType == 2) {
    // Fetch properties belonging to the logged-in advertiser
    $userID = $_SESSION['userID'];
    $propertyQuery = "SELECT UserID FROM tbl_property WHERE AdvertiserID = '$userID'";
    $propertyResult = mysqli_query($conn, $propertyQuery);

    $userIDs = [];
    while ($propertyRow = mysqli_fetch_assoc($propertyResult)) {
        $decodedIDs = json_decode($propertyRow['UserID'], true);
        if (!empty($decodedIDs)) {
            $userIDs = array_merge($userIDs, $decodedIDs);
        }
    }

    if (!empty($userIDs)) {
        $flattenedUserIDs = array_unique($userIDs);
        $userIDsString = implode(',', $flattenedUserIDs);
        $userQuery = "SELECT * FROM tbl_user WHERE UserID IN ($userIDsString)";
    } else {
        $userQuery = "SELECT * FROM tbl_user WHERE 1=0"; // Empty query if no user IDs found
    }
} else {
    header("Location: unauthorized.php");
    exit();
}

$userResult = mysqli_query($conn, $userQuery);



// if ($userType == 1) {
//     $userQuery = "SELECT * FROM tbl_user";
// } elseif ($userType == 2) {
//     // Fetch properties belonging to the logged-in advertiser
//     $userID = $_SESSION['userID'];
//     $propertyQuery = "SELECT UserID FROM tbl_property WHERE AdvertiserID = '$userID'";
//     $propertyResult = mysqli_query($conn, $propertyQuery);

//     $userIDs = [];
//     while ($propertyRow = mysqli_fetch_assoc($propertyResult)) {
//         $userIDs[] = json_decode($propertyRow['UserID'], true);
//     }

//     if (!empty($userIDs)) {
//         $flattenedUserIDs = array_unique(array_merge(...$userIDs));
//         $userIDsString = implode(',', $flattenedUserIDs);
//         $userQuery = "SELECT * FROM tbl_user WHERE UserID IN ($userIDsString)";
//     } else {
//         $userQuery = "SELECT * FROM tbl_user WHERE 1=0"; // Empty query if no user IDs found
//     }
// } else {
//     header("Location: unauthorized.php");
//     exit();
// }

$userResult = mysqli_query($conn, $userQuery);

if ($userResult && mysqli_num_rows($userResult) > 0) {
    echo '<div class="container">';
    echo '<div class="row mb-4">';
    echo '<div class="col-md-6">';
    echo '<input type="text" id="searchInput" class="form-control" placeholder="Search...">';
    echo '</div>';
    echo '<div class="col-md-6 text-left">';
    if ($userType == 1) { // Only show buttons for userType = 1
        echo '<a href="functions/add_user.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add User</a>';
        echo '<button id="downloadPdfBtn" class="btn btn-danger ml-2"><i class="fas fa-download"></i> PDF</button>';
        echo '<button id="downloadExcelBtn" class="btn btn-success ml-2"><i class="fas fa-download"></i> Excel</button>';
    }
    echo '</div>';
    echo '</div>';

    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-striped" id="userContainer">';
    echo '<thead class="thead-dark">';
    echo '<tr>';
    echo '<th>UID</th>';
    echo '<th>Age</th>';
    echo '<th>IsActive</th>';
    echo '<th>Gender</th>';
    echo '<th>City</th>';
    echo '<th>Username</th>';
    echo '<th>Email</th>';
    echo '<th>Type</th>';
    if ($userType == 1) { // Only show Actions for userType = 1
        echo '<th class="actions">Actions</th>';
    }
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($userRow = mysqli_fetch_assoc($userResult)) {
        echo '<tr>';
        echo '<td>' . $userRow['UserID'] . '</td>';
        echo '<td>' . $userRow['Age'] . '</td>';
        echo '<td>' . $userRow['IsActive'] . '</td>';
        echo '<td>' . $userRow['Gender'] . '</td>';
        echo '<td>' . $userRow['HomeCity'] . '</td>';
        echo '<td>' . $userRow['Username'] . '</td>';
        echo '<td>' . $userRow['Email'] . '</td>';
        echo '<td>' . $userRow['UserType'] . '</td>';
        if ($userType == 1) { // Only show Actions for userType = 1
            echo '<td class="d-flex actionBtn">';
            echo '<a href="functions/update_user.php?id=' . $userRow['UserID'] . '" class="btn btn-primary"><i class="fas fa-pen"></i></a>';
            echo '<a href="functions/delete_user.php?id=' . $userRow['UserID'] . '" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>';
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
    echo "No users found.";
}

mysqli_close($conn);
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            $('#userContainer tr').each(function() {
                var lineText = $(this).text().toLowerCase();
                if (lineText.indexOf(searchText) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        });

        $('#downloadPdfBtn').click(function() {
            generatePDF('#userContainer', 'all_users.pdf');
            setTimeout(function() {
                location.reload();
            }, 1000);
        });

        $('#downloadExcelBtn').click(function() {
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
                margin: [20, 20, 20, 20],
                filename: fileName,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'pt', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().set(options).from(element).save();
        }

        function generateExcel() {
            // Create a new workbook
            var wb = XLSX.utils.book_new();
            // Convert table to worksheet
            var ws = XLSX.utils.table_to_sheet(document.getElementById('userContainer'));

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Users');
            // Save workbook as Excel file
            XLSX.writeFile(wb, 'users.xlsx');
        }
    });
</script>
