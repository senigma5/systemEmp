<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}
include('db.php');

$emp_id = $_SESSION['employee_id'];
$today = date('Y-m-d');

// Check if already submitted
$check = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? AND date = ?");
$check->bind_param("is", $emp_id, $today);
$check->execute();
$result = $check->get_result();
$submitted = $result->num_rows > 0;

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$submitted) {
    $status = $_POST['status'];
    $insert = $conn->prepare("INSERT INTO attendance (employee_id, status, date) VALUES (?, ?, ?)");
    $insert->bind_param("iss", $emp_id, $status, $today);
    if ($insert->execute()) {
        $message = "<div class='alert alert-success text-center'>Attendance submitted successfully!</div>";
        $submitted = true;
    } else {
        $message = "<div class='alert alert-danger text-center'>Error submitting attendance.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Submit Attendance</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
    <a href="employee_dashboard.php" class="btn btn-secondary mb-3">← Back</a>
    <h3 class="mb-4 text-primary">Submit Today’s Attendance</h3>

    <?= $message ?>

    <?php if (!$submitted): ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="">-- Select --</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Leave">Leave</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Submit Attendance</button>
    </form>
    <?php else: ?>
        <div class="alert alert-info text-center">
            You have already submitted attendance for today (<?= $today ?>).
        </div>
    <?php endif; ?>
</div>
</body>
</html>
