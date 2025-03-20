<?php
include('config.php');

if(isset($_POST['id']) && isset($_POST['table'])) {
    $id = intval($_POST['id']);
    $table = $_POST['table'];
    
    $result = $conn->query("SELECT * FROM tbl_carousel WHERE id = $id");
    
    if($result->num_rows > 0) {
        $item = $result->fetch_assoc();
        echo json_encode($item);
    } else {
        echo json_encode(['error' => 'Item not found']);
    }
}
?>