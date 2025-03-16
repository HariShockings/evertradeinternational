<?php 
    // $servername = "sql108.infinityfree.com";
    // $username = "if0_38201364";
    // $password = "iX6MCsTWRcE";
    // $dbname = "if0_38201364_evertrade";
    

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ever_trade";
    
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
?>