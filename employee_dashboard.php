<?php
session_start();
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

include('db.php');

$employee_id = $_SESSION['employee_id'];
$name = $_SESSION['employee_name'];
$role = $_SESSION['employee_role'];
$today = date('Y-m-d');
$message = "";

/* ---------- ATTENDANCE SUBMISSION ---------- */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status'])) {
    $status = $_POST['status'];

    $check = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? AND date = ?");
    $check->bind_param("is", $employee_id, $today);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE attendance SET status = ? WHERE employee_id = ? AND date = ?");
        $update->bind_param("sis", $status, $employee_id, $today);
        $update->execute();
        $message = "<div class='alert alert-info text-center'>Attendance updated successfully!</div>";
    } else {
        $insert = $conn->prepare("INSERT INTO attendance (employee_id, status, date) VALUES (?, ?, ?)");
        $insert->bind_param("iss", $employee_id, $status, $today);
        $insert->execute();
        $message = "<div class='alert alert-success text-center'>Attendance submitted successfully!</div>";
    }
}

/* ---------- LEAVE APPLICATION ---------- */
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['leave_type'])) {
    $leave_type = $_POST['leave_type'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $reason = $_POST['reason'];

    $insert_leave = $conn->prepare("INSERT INTO leave_records (employee_id, leave_type, from_date, to_date, reason, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $insert_leave->bind_param("issss", $employee_id, $leave_type, $from_date, $to_date, $reason);
    if ($insert_leave->execute()) {
        $message .= "<div class='alert alert-success text-center'>Leave request submitted successfully!</div>";
    } else {
        $message .= "<div class='alert alert-danger text-center'>Error submitting leave request.</div>";
    }
}

/* ---------- FETCH RECORDS ---------- */
$attendance_records = $conn->prepare("SELECT * FROM attendance WHERE employee_id = ? ORDER BY date DESC");
$attendance_records->bind_param("i", $employee_id);
$attendance_records->execute();
$attendance_result = $attendance_records->get_result();

$leave_records = $conn->prepare("SELECT * FROM leave_records WHERE employee_id = ? ORDER BY id DESC");
$leave_records->bind_param("i", $employee_id);
$leave_records->execute();
$leave_result = $leave_records->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.navbar { background: #20c997; }
.navbar-brand, .nav-link, .navbar-text { color: #fff !important; }
.card { border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">Employee Dashboard</a>
    <div class="d-flex align-items-center">
  <span class="navbar-text me-3">
    Welcome, <?= htmlspecialchars($name) ?> (<?= htmlspecialchars($role) ?>)
  </span>
  <a href="employee_notifications.php" class="btn btn-warning btn-sm me-2">Notifications</a>
   <a href="personal_details.php" class="btn btn-outline-success btn-sm me-2">Enter Personal Details</a>
  <a href="employee_salary.php" class="btn btn-outline-success btn-sm me-2">View Salary</a>
  <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
</div>

  </div>
</nav>

<div class="container mt-4">
    <?= $message ?>

    <!-- Attendance -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="text-center text-success mb-3">Submit Today's Attendance</h4>
            <form method="POST" class="text-center">
                <select name="status" class="form-select w-50 d-inline-block" required>
                    <option value="">Select Status</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="Leave">Leave</option>
                </select>
                <button type="submit" class="btn btn-success ms-2">Submit</button>
            </form>
        </div>
    </div>

    <!-- Leave Application -->
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="text-center text-primary mb-3">Apply for Leave</h4>
            <form method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <label>Leave Type</label>
                        <select name="leave_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Annual Leave">Annual Leave</option>
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Maternity Leave">Maternity Leave</option>
                            <option value="Paternity Leave">Paternity Leave</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>From</label>
                        <input type="date" name="from_date" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>To</label>
                        <input type="date" name="to_date" class="form-control" required>
                    </div>
                </div>
                <div class="mt-3">
                    <label>Reason</label>
                    <textarea name="reason" class="form-control" required></textarea>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary">Submit Leave</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leave History -->
    <div class="card">
        <div class="card-body">
            <h4 class="text-center text-success mb-3">Your Leave History</h4>
            <table class="table table-bordered text-center">
                <thead class="table-success">
                    <tr>
                        <th>Type</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $leave_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['leave_type']) ?></td>
                            <td><?= htmlspecialchars($row['from_date']) ?></td>
                            <td><?= htmlspecialchars($row['to_date']) ?></td>
                            <td><?= htmlspecialchars($row['reason']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($leave_result->num_rows === 0): ?>
                        <tr><td colspan="5">No leave records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
