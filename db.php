<?php
$servername = "mysql.railway.internal";
$username = "root";
$password = "NkVODZTTcBvPqlhsyiEOYiZsIhiWqBBq";
$dbname = "railway";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
?>
