<?php
include('config.php');

$id = $_POST['id'] ?? 0;
$query = "SELECT * FROM tbl_leadership WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode([]);
}
?>
