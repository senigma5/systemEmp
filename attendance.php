<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include('db.php');

// Handle admin attendance saving
if (isset($_POST['status']) && is_array($_POST['status'])) {
    $today = date('Y-m-d');

    foreach ($_POST['status'] as $id => $value) {
        $check = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? AND date = ?");
        $check->bind_param("is", $id, $today);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $update = $conn->prepare("UPDATE attendance SET status = ? WHERE employee_id = ? AND date = ?");
            $update->bind_param("sis", $value, $id, $today);
            $update->execute();
        } else {
            $insert = $conn->prepare("INSERT INTO attendance (employee_id, status, date) VALUES (?, ?, ?)");
            $insert->bind_param("iss", $id, $value, $today);
            $insert->execute();
        }
    }

    echo "<div class='alert alert-success text-center'>Attendance saved successfully!</div>";
}

// Fetch all employees
$employees = $conn->query("SELECT * FROM employees");

// Get today's attendance records
$today = date('Y-m-d');
$attendanceData = [];
$result = $conn->query("SELECT employee_id, status FROM attendance WHERE date = '$today'");
while ($row = $result->fetch_assoc()) {
    $attendanceData[$row['employee_id']] = $row['status'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daily Attendance</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.badge-submitted { background-color: #28a745; color: white; }
.badge-pending { background-color: #ffc107; color: black; }
</style>
</head>
<body class="p-4">

<a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back</a>
<h3 class="mb-4 text-primary">Daily Attendance - <?= date('l, F j, Y') ?></h3>

<form method="POST">
<table class="table table-bordered table-striped">
<thead class="table-primary text-center">
<tr>
  <th>#</th>
  <th>Name</th>
  <th>Role</th>
  <th>Status</th>
  <th>Submitted?</th>
</tr>
</thead>
<tbody>
<?php 
$counter = 1;
while($emp = $employees->fetch_assoc()): 
    $empId = $emp['id'];
    $submitted = isset($attendanceData[$empId]);
    $statusValue = $submitted ? $attendanceData[$empId] : '';
?>
<tr class="text-center align-middle">
<td><?= $counter++ ?></td>
<td><?= htmlspecialchars($emp['name']) ?></td>
<td><?= htmlspecialchars($emp['role']) ?></td>
<td>
<?php if ($submitted): ?>
    <input type="text" readonly class="form-control-plaintext text-center" value="<?= htmlspecialchars($statusValue) ?>">
<?php else: ?>
    <select name="status[<?= $empId ?>]" class="form-select text-center">
        <option value="">Select</option>
        <option value="Present">Present</option>
        <option value="Absent">Absent</option>
        <option value="Leave">Leave</option>
    </select>
<?php endif; ?>
</td>
<td>
<?php if ($submitted): ?>
    <span class="badge badge-submitted">Submitted</span>
<?php else: ?>
    <span class="badge badge-pending">Pending</span>
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<div class="text-center">
  <button type="submit" class="btn btn-success px-4"> Save Attendance</button>
</div>
</form>

</body>
</html>
