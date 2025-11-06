<?php
session_start();
include('db.php');



// Handle approval/rejection
$action_message = "";
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    $status = $action === 'approve' ? 'Approved' : 'Rejected';

    $update = $conn->prepare("UPDATE leave_records SET status = ? WHERE id = ?");
    $update->bind_param("si", $status, $id);
    if ($update->execute()) {
        $action_message = "<div class='alert alert-success text-center'>Leave request has been $status successfully!</div>";
    }
}

// Fetch pending leave count for notification badge
$pending_count = $conn->query("SELECT COUNT(*) AS total FROM leave_records WHERE status = 'Pending'")->fetch_assoc()['total'];

// Fetch all leave requests
$leaves = $conn->query("SELECT leave_records.*, employees.name 
    FROM leave_records 
    JOIN employees ON leave_records.employee_id = employees.id 
    ORDER BY leave_records.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Leave Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.navbar { background: #198754; }
.navbar-brand, .nav-link { color: #fff !important; }
.card { border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
.badge-pending { background-color: #ffc107; }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Admin Dashboard</a>
    <div class="d-flex">
      <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back</a>
        <?php if ($pending_count > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= $pending_count ?>
          </span>
        <?php endif; ?>
      </a>
      
    </div>
  </div>
</nav>

<div class="container mt-4">
    <?= $action_message ?>

    <div class="card">
        <div class="card-body">
            <h3 class="text-center text-success mb-3">Leave Requests</h3>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $leaves->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['leave_type']) ?></td>
                            <td><?= htmlspecialchars($row['from_date']) ?></td>
                            <td><?= htmlspecialchars($row['to_date']) ?></td>
                            <td><?= htmlspecialchars($row['reason']) ?></td>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php elseif ($row['status'] === 'Approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <a href="?action=approve&id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                                    <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
                                <?php else: ?>
                                    <span class="text-muted"><?= htmlspecialchars($row['status']) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if ($leaves->num_rows === 0): ?>
                            <tr><td colspan="7">No leave requests found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
