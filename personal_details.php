<?php
session_start();
include('db.php');

// Redirect if not logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: employee_login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];
$message = "";

// Fetch current employee info
$query = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$query->bind_param("i", $employee_id);
$query->execute();
$result = $query->get_result();
$employee = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $department = $_POST['department'] ?? '';
    $position = $_POST['position'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $account_number = $_POST['account_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $progress = $_POST['progress'] ?? '';

    $stmt = $conn->prepare("UPDATE employees 
        SET email=?, phone=?, department=?, position=?, bank_name=?, account_number=?, address=?, progress=? 
        WHERE id=?");
    $stmt->bind_param("ssssssssi", $email, $phone, $department, $position, $bank_name, $account_number, $address, $progress, $employee_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>Profile updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Error updating profile.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Profile Update</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.card { border-radius: 12px; box-shadow: 0 3px 15px rgba(0,0,0,0.1); }
h3 { color: #0d6efd; text-align: center; margin-bottom: 20px; }
.back-btn { margin-top: 10px; display: inline-block; }
</style>
</head>
<body class="p-4">

<div class="container">
  <h3>Update Your Profile</h3>
  <?= $message ?>
  <div class="card p-4 mx-auto" style="max-width: 700px;">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($employee['name']) ?>" disabled>
        </div>
        <div class="col-md-6">
          <label class="form-label">Username</label>
          <input type="text" class="form-control" value="<?= htmlspecialchars($employee['username']) ?>" disabled>
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($employee['email'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($employee['phone'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Department</label>
          <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($employee['department'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Position</label>
          <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($employee['position'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Bank Name</label>
          <input type="text" name="bank_name" class="form-control" value="<?= htmlspecialchars($employee['bank_name'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Account Number</label>
          <input type="text" name="account_number" class="form-control" value="<?= htmlspecialchars($employee['account_number'] ?? '') ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($employee['address'] ?? '') ?>">
        </div>
        <div class="col-md-12">
          <label class="form-label">Progress Notes</label>
          <textarea name="progress" class="form-control" rows="3"><?= htmlspecialchars($employee['progress'] ?? '') ?></textarea>
        </div>
      </div>
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-success px-4">Save Changes</button>
        <a href="employee_dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
