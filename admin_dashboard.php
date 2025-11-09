<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
  background-color: #f4f6f9;
  font-family: 'Segoe UI', sans-serif;
}

/* Sidebar Styling */
.sidebar {
  height: 100vh;
  background: linear-gradient(180deg, #0d6efd, #003cba);
  color: white;
  padding-top: 20px;
  position: fixed;
  left: 0;
  top: 0;
  width: 240px;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-right: 3px solid #003cba; /* Vertical divider */
}

.sidebar h4 {
  font-weight: 600;
  text-transform: uppercase;
  margin-bottom: 30px;
  text-align: center;
}

.sidebar a {
  color: #e9ecef;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 20px;
  margin: 5px 10px;
  border-radius: 8px;
  transition: background 0.2s, color 0.2s;
  font-size: 15px;
}

.sidebar a:hover {
  background: rgba(255,255,255,0.15);
  color: #fff;
}

.sidebar a.active {
  background: rgba(255,255,255,0.3);
  font-weight: 600;
}

/* Sidebar Buttons */
.sidebar .btn {
  width: 85%;
  font-weight: 500;
  padding: 10px;
  transition: all 0.3s ease;
  border: none;
}

.sidebar .btn-primary {
  background-color: #0d6efd;
}

.sidebar .btn-primary:hover {
  background-color: #0b5ed7;
  transform: scale(1.05);
}

.sidebar .btn-danger {
  background-color: #dc3545;
}

.sidebar .btn-danger:hover {
  background-color: #bb2d3b;
  transform: scale(1.05);
}

/* Main Content Area */
.main-content {
  margin-left: 240px;
  padding: 30px;
  transition: all 0.3s ease;
}

/* Navbar */
.navbar {
  background: #fff;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  padding: 10px 20px;
  border-radius: 8px;
}

/* Card Design */
.card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

/* Small Responsive Adjustments */
@media (max-width: 768px) {
  .sidebar {
    width: 200px;
    border-right: 2px solid #003cba;
  }
  .main-content {
    margin-left: 200px;
  }
  .sidebar a {
    font-size: 14px;
    padding: 10px 15px;
  }
}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div>
    <h4><i class="bi bi-person-gear"></i> Admin Panel</h4>

    <a href="employees.php"><i class="bi bi-people"></i> Manage Employees</a>
    <a href="view_employee_details.php"><i class="bi bi-people"></i> Employees Details</a>
    <a href="attendance.php"><i class="bi bi-calendar-check"></i> Attendance</a>
    <a href="admin_leave_approval.php"><i class="bi bi-envelope-paper"></i> Leave Requests</a>
    <a href="absentee.php"><i class="bi bi-person-dash"></i> Absentee Tracking</a>
    <a href="admin_send_notification.php"><i class="bi bi-bell"></i> Notifications</a>
    <a href="manage_salary.php"><i class="bi bi-cash-coin"></i> Manage Salaries</a>
    
  </div>

  <div class="mt-auto mb-3 text-center">
    <a href="reports_dashboard.php" class="btn btn-primary d-block mx-auto mb-2 rounded">
      <i class="bi bi-file-earmark-text"></i> REPORTS
    </a>
    <a href="logout.php" class="btn btn-danger d-block mx-auto rounded">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </div>
</div>

<!-- Main Content -->
<div class="main-content">
  <nav class="navbar mb-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <h4 class="mb-0 text-primary fw-semibold">Admin Dashboard</h4>
      <span class="text-muted">Welcome, <strong><?= htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
    </div>
  </nav>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card p-4 text-center text-primary">
        <i class="bi bi-people fs-1 mb-2"></i>
        <h5>Employees</h5>
        <p>Manage and view employee details</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-4 text-center text-success">
        <i class="bi bi-calendar-check fs-1 mb-2"></i>
        <h5>Attendance</h5>
        <p>Track daily employee attendance</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-4 text-center text-warning">
        <i class="bi bi-cash-coin fs-1 mb-2"></i>
        <h5>Salaries</h5>
        <p>Manage salary levels and payments</p>
      </div>
    </div>
  </div>

  <div class="alert alert-info mt-4 shadow-sm">
    <i class="bi bi-info-circle"></i> Use the sidebar to manage employees, attendance, leaves, and notifications.
  </div>
</div>

</body>
</html>
