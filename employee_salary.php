<?php
session_start();
include('db.php');

if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];
$name = $_SESSION['employee_name'];

$stmt = $conn->prepare("SELECT salary FROM employees WHERE id = ?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$salary = $row['salary'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Salary</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 text-center">
    <h3 class="text-primary mb-4">Hello, <?= htmlspecialchars($name) ?></h3>
    <div class="card shadow p-4">
        <h4 class="text-success">Your Current Salary</h4>
        <h2 class="fw-bold mt-3">Ksh <?= number_format($salary, 2) ?></h2>
    </div>
    <a href="employee_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</div>
</body>
</html>
