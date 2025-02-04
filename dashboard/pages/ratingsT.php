<?php
// Start the session
session_start();

if (isset($_SESSION['userType'])) {
    $userType = intval($_SESSION['userType']);
}

// Check if the user is logged in and their userType is admin (userType = 1) or advertiser (userType = 2)
if (isset($_SESSION['userID']) && ($_SESSION['userType'] == 2 || $_SESSION['userType'] == 1)) {
    // Include your database configuration file
    include '../../config.php';

    // Check if the connection is successful
    if (!$conn) {
        echo "Database connection error.";
    } else {
        // Query to fetch property ratings with PropertyID, rounded average rating, and count
        $ratingsQuery = "SELECT PropertyID, 
                                ROUND(AVG(Rating), 1) AS AverageRating, 
                                COUNT(Rating) AS RatingCount, 
                                IsDeleted 
                         FROM tbl_propertyrating 
                         WHERE IsDeleted = 0 
                         GROUP BY PropertyID";

        // Execute the query
        $ratingsResult = mysqli_query($conn, $ratingsQuery);

        if (!$ratingsResult) {
            echo "Error executing query: " . mysqli_error($conn);
        } elseif (mysqli_num_rows($ratingsResult) > 0) {
            // Array to hold rating data for the graph with PropertyID as keys
            $ratingsData = [];

            while ($ratingRow = mysqli_fetch_assoc($ratingsResult)) {
                // Add rating data to the array with PropertyID as keys
                $ratingsData[$ratingRow['PropertyID']] = [
                    'AverageRating' => $ratingRow['AverageRating'],
                    'RatingCount' => $ratingRow['RatingCount']
                ];
            }

            // Close the database connection
            mysqli_close($conn);

            // Convert the ratingsData array to JSON for use in JavaScript
            $ratingsDataJSON = json_encode($ratingsData);
        } else {
            echo "No ratings found.";
        }
    }
} else {
    echo "You are not authorized to view this page.";
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div style="width: 80%; margin: auto; display: flex; align-items: center;">
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search..." style="flex: 1;">
    <div class="mb-3">
        <button id="showAsTableBtn" class="btn btn-primary mx-4">Show as Table</button>
        <button id="showInGraphBtn" class="btn btn-primary">Show in Graph</button>
    </div>
</div>


<div id="tableContainer" style="display: block;">
    <?php if (isset($ratingsDataJSON)) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="ratingsTable">
                <thead class="thead-dark">
                    <tr>
                        <th>Property ID</th>
                        <th>Average Rating</th>
                        <th>Rating Count</th>
                        <?php if ($userType == 1) { // Only show Actions column for admin users ?>
                            <th class="actions">Actions</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ratingsData as $propertyID => $data) { ?>
                        <tr>
                            <td><?php echo $propertyID; ?></td>
                            <td><?php echo $data['AverageRating']; ?></td>
                            <td><?php echo $data['RatingCount']; ?></td>
                            <?php if ($userType == 1) { // Only show Actions for admin users ?>
                                <td class="d-flex actionBtn">
                                    <a href="functions/update_rating.php?id=<?php echo $propertyID; ?>" class="btn btn-primary"><i class="fas fa-pen"></i></a>
                                    <a href="functions/delete_rating.php?id=<?php echo $propertyID; ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else {
        echo "Error: Ratings data not available.";
    } ?>
</div>

<div id="graphContainer" style="display: none;">
    <canvas id="ratingsChart"></canvas>
</div>

<script>
    $(document).ready(function () {
        // Show as Table button click event
        $('#showAsTableBtn').click(function () {
            $('#tableContainer').show();
            $('#graphContainer').hide();
        });

        // Show in Graph button click event
        $('#showInGraphBtn').click(function () {
            $('#tableContainer').hide();
            $('#graphContainer').show();

            // Generate graph using Chart.js
            var ctx = document.getElementById('ratingsChart').getContext('2d');
            var ratingsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_keys($ratingsData)); ?>,
                    datasets: [{
                        label: 'Average Rating',
                        data: <?php echo json_encode(array_column($ratingsData, 'AverageRating')); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        // Table filter on search
        $('#searchInput').on('keyup', function () {
            var searchText = $(this).val().toLowerCase();
            $('#ratingsTable tbody tr').each(function () {
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
