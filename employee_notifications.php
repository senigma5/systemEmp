<?php
session_start();
include('db.php');

if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Fetch notifications meant for this user or for all
$query = $conn->prepare("
    SELECT * FROM notifications 
    WHERE employee_id = ? OR employee_id IS NULL
    ORDER BY created_at DESC
");
$query->bind_param("i", $employee_id);
$query->execute();
$result = $query->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Notifications</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<div class="container mt-5">
    <a href="employee_dashboard.php" class="btn btn-secondary mb-3">← Back</a>
    <h3 class="text-center text-primary mb-4">Notifications</h3>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted"><?= $row['created_at'] ?></h6>
                    <p class="card-text"><?= htmlspecialchars($row['message']) ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info text-center">No notifications found.</div>
    <?php endif; ?>
</div>
</body>
</html>
