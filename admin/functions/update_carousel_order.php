<?php
include('config.php');

$order = json_decode($_POST['order']);
foreach($order as $index => $id) {
    $displayOrder = $index + 1;
    $conn->query("UPDATE tbl_carousel SET display_order = $displayOrder WHERE id = " . intval($id));
}
echo 'success';
?>