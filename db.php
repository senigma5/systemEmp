<?php
$servername = getenv("MYSQLHOST") ?: getenv("MYSQL_HOST");
$username = getenv("MYSQLUSER") ?: getenv("MYSQL_USER");
$password = getenv("MYSQLPASSWORD") ?: getenv("MYSQL_PASSWORD");
$database = getenv("MYSQLDATABASE") ?: getenv("MYSQL_DATABASE");
$port = getenv("MYSQLPORT") ?: getenv("MYSQL_PORT");

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
echo "✅ Successfully connected to the database!";
?>
