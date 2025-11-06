<?php
session_start();
include('db.php');

// Ensure only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all employees and their details
$sql = "SELECT id, name, email, phone, department, position, bank_name, account_number, address, salary, date_joined 
        FROM employees ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Employee Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.card { border-radius: 12px; box-shadow: 0 3px 15px rgba(0,0,0,0.1); }
.table thead { background-color: #007bff; color: white; }
</style>
</head>
<body class="p-4">

<h3 class="text-center text-primary mb-4">All Employee Personal Details</h3>

<div class="container">
  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Department</th>
            <th>Position</th>
            <th>Bank Name</th>
            <th>Account Number</th>
            <th>Address</th>
            <th>Salary</th>
            <th>Date Joined</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['department'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['position'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['bank_name'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['account_number'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['address'] ?? '-') ?></td>
                <td><?= htmlspecialchars($row['salary']) ?></td>
                <td><?= htmlspecialchars($row['date_joined']) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="11" class="text-center text-muted">No employee records found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
 <a href="admin_dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>

</body>
</html>
