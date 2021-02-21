   <?php

$servername = "mydbhost";
$username = "root";
$password = "demo1234";
$databaseName = "db_mystore";

$connect = new mysqli($servername,$username,$password,$databaseName);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
} 
echo "Connected successfully";

?>