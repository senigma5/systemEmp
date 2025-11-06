<?php
session_start();
include('db.php');

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle salary update
if (isset($_POST['update_salary'])) {
    $employee_id = $_POST['employee_id'];
    $new_salary = $_POST['new_salary'];

    $update = $conn->prepare("UPDATE employees SET salary = ? WHERE id = ?");
    $update->bind_param("di", $new_salary, $employee_id);
    if ($update->execute()) {
        $msg = "<div class='alert alert-success text-center'>Salary updated successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger text-center'>Error updating salary.</div>";
    }
}

// Fetch employees
$result = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Salaries</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3 class="text-center text-success mb-4">Manage Employee Salaries</h3>
    <?= isset($msg) ? $msg : '' ?>

    <table class="table table-bordered text-center">
        <thead class="table-success">
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Current Salary (Ksh)</th>
                <th>New Salary</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= htmlspecialchars($row['salary']) ?></td>
                    <td>
                        <input type="number" step="0.01" name="new_salary" class="form-control" placeholder="Enter amount" required>
                        <input type="hidden" name="employee_id" value="<?= $row['id'] ?>">
                    </td>
                    <td><button type="submit" name="update_salary" class="btn btn-primary btn-sm">Update</button></td>
                </form>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center">
        <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
