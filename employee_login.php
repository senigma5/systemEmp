<?php
session_start();
include('db.php'); // Make sure db.php is in the same directory

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Secure query using prepared statements
        $stmt = $conn->prepare("SELECT * FROM employees WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $employee = $result->fetch_assoc();

            // For now passwords are plain text (later you can use password_hash)
            if ($password === $employee['password']) {
                $_SESSION['employee_id'] = $employee['id'];
                $_SESSION['employee_name'] = $employee['name'];
                $_SESSION['employee_role'] = $employee['role'];

                header("Location: employee_dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No employee found with that username.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, rgba(13,110,253,0.85), rgba(108,117,125,0.85)),
                    url('img2.PNG');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        backdrop-filter: blur(3px);
    }

    .login-container {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.3);
        width: 100%;
        max-width: 400px;
        padding: 2.5rem;
    }

    .logo {
        display: block;
        margin: 0 auto 1rem auto;
        width: 100%;
        max-width: 300px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .login-container h3 {
        text-align: center;
        font-weight: 700;
        color: #0d6efd;
        margin-bottom: 1.5rem;
    }

    .form-control {
        border-radius: 10px;
        padding: 0.75rem;
    }

    .btn-primary {
        border-radius: 10px;
        padding: 0.7rem;
        font-weight: 600;
        background: linear-gradient(90deg, #0d6efd, #6c757d);
        border: none;
    }

    .btn-primary:hover {
        opacity: 0.9;
    }

    .error-message {
        color: #dc3545;
        text-align: center;
        font-weight: 500;
    }

    .footer-links {
        text-align: center;
        margin-top: 1rem;
    }

    .footer-links a {
        color: #ffc107;
        text-decoration: none;
        font-weight: 500;
    }

    .footer-links a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="login-container">
    <!-- Logo -->
    <img src="img3.png" alt="Convenient Courier Services Limited Logo" class="logo">

    <!-- Title -->
    <h3>Employee Login</h3>

    <!-- Error Message -->
    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label text-dark">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label text-dark">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <!-- Footer -->
    <div class="footer-links">
        <p class="text-muted mb-1">© <?= date("Y"); ?> Employee Portal</p>
        <a href="admin_login.php">Admin Login</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
