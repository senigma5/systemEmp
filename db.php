<?php
$servername = "shinkansen.proxy.rlwy.net";
$username = "root";
$password = "NkVODZTTcBvPqlhsyiEOYiZsIhiWqBBq";
$database = "railway";
$port = 53658;

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Database connection successful!";
}
?>
