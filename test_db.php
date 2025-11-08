<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>Testing database connection...</h3>";

include('db.php');

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Successfully connected to the database!";
}
?>
