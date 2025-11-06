<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include('db.php');

// Fetch total absences per employee
$absent = $conn->query("
    SELECT e.id, e.name, e.role, COUNT(a.id) AS total_absences
    FROM employees e
    LEFT JOIN attendance a ON e.id = a.employee_id AND a.status='Absent'
    GROUP BY e.id
");

// Calculate summary statistics
$totalEmployees = $conn->query("SELECT COUNT(*) AS total FROM employees")->fetch_assoc()['total'];
$totalAbsentToday = $conn->query("
    SELECT COUNT(*) AS total 
    FROM attendance 
    WHERE date = CURDATE() AND status = 'Absent'
")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Absentee Tracking</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.badge-high { background-color: #dc3545; }  /* red */
.badge-medium { background-color: #ffc107; color: #000; }  /* yellow */
.badge-low { background-color: #198754; }  /* green */
</style>
</head>
<body class="p-4 bg-light">
<a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back</a>

<h3 class="text-primary mb-4"> Absentee Tracking</h3>

<!-- Summary Section -->
<div class="mb-4">
  <div class="alert alert-info">
    <strong>Summary:</strong> <?= $totalAbsentToday ?> employees are absent today.  
    Total registered employees: <?= $totalEmployees ?>.
  </div>
</div>

<!-- Absence Table -->
<table class="table table-bordered table-striped text-center align-middle">
<thead class="table-danger">
<tr>
  <th>#</th>
  <th>Employee</th>
  <th>Role</th>
  <th>Total Absences</th>
  <th>Risk Level</th>
</tr>
</thead>
<tbody>
<?php 
$counter = 1;
while($row = $absent->fetch_assoc()):
  $riskBadge = '';
  if ($row['total_absences'] >= 10) {
      $riskBadge = "<span class='badge badge-high'> High</span>";
  } elseif ($row['total_absences'] >= 5) {
      $riskBadge = "<span class='badge badge-medium'>Moderate</span>";
  } else {
      $riskBadge = "<span class='badge badge-low'>Low</span>";
  }
?>
<tr>
  <td><?= $counter++ ?></td>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= htmlspecialchars($row['role']) ?></td>
  <td><?= $row['total_absences'] ?></td>
  <td><?= $riskBadge ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</body>
</html>
