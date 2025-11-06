<?php
session_start();
include('db.php');

// Fetch employees for dropdown
$employees = $conn->query("SELECT id, name FROM employees");

// Handle notification submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient = $_POST['recipient'];
    $content = trim($_POST['message']);

    if (!empty($content)) {
        if ($recipient === "all") {
            $stmt = $conn->prepare("INSERT INTO notifications (message) VALUES (?)");
            $stmt->bind_param("s", $content);
        } else {
            $stmt = $conn->prepare("INSERT INTO notifications (employee_id, message) VALUES (?, ?)");
            $stmt->bind_param("is", $recipient, $content);
        }

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success text-center'>Notification sent successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger text-center'>Failed to send notification.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning text-center'>Message cannot be empty!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Send Notification</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.navbar { background: #198754; height: 60px; }
.navbar-brand { color: #fff !important; }
.card { border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
</style>
</head>
<body>

<!-- Minimal Green Bar -->
<nav class="navbar">
  <div class="container-fluid justify-content-center">
    <span class="navbar-brand fw-bold">Send Notification</span>
  </div>
</nav>

<div class="container mt-5">
    <?= $message ?>
    <div class="card p-4">
        <h3 class="text-center text-success mb-4">Send Notification</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Recipient:</label>
                <select name="recipient" class="form-select" required>
                    <option value="all">All Employees</option>
                    <?php while ($row = $employees->fetch_assoc()): ?>
                        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Message:</label>
                <textarea name="message" rows="4" class="form-control" placeholder="Type your message here..." required></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">Send Notification</button>
            </div>
            <div> 
  <a href="admin_dashboard.php" class="btn btn-danger btn-sm">Back</a>
</div>

        </form>
    </div>
</div>

</body>
</html>
