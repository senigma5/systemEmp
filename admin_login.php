<?php
session_start();
include('db.php'); 

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No admin found with that username.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Convenient Courier EMS Admin Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, rgba(13,110,253,0.85), rgba(108,117,125,0.85)),
                    url('img1.PNG');
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
        margin-bottom: 1rem;
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
    <h3>Convenient Courier EMS Admin Login</h3>

    <!-- Error Message -->
    <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label text-dark">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Enter admin username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label text-dark">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
    </form>

    <!-- Footer -->
    <div class="footer-links">
        <p class="text-muted mb-1">© <?= date("Y"); ?> Admin Portal</p>
        <a href="employee_login.php">Employee Login</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


