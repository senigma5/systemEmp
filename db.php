<?php
$servername = "localhost";
$username = "root"; // default XAMPP user
$password = ""; // leave empty
$dbname = "admin_db"; // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
