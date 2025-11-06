<?php
session_start();
include('db.php');

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Ensure only admin can access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// Handle salary disbursement form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];

    // Fetch employee info
    $stmt = $conn->prepare("SELECT name, email, bank_name, account_number, salary FROM employees WHERE id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    if ($employee && !empty($employee['email'])) {
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'josephkimani0101@gmail.com'; // your email
            $mail->Password = 'pfla pvdu ifem gzsz'; // Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('josephkimani0101@gmail.com', 'Convenient Courier HR Department');
            $mail->addAddress($employee['email'], $employee['name']);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Salary Disbursement - Convenient Courier Employee Management System';
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color:#f4f4f4; padding:20px;'>
              <div style='max-width:600px; margin:auto; background-color:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 0 10px rgba(0,0,0,0.1);'>
                
                <div style='background-color:#0d6efd; color:#fff; padding:20px; text-align:center;'>
                  <h2 style='margin:0;'>Convenient Courier EMS</h2>
                  <p style='margin:5px 0 0;'>Salary Disbursement Notification</p>
                </div>

                <div style='padding:25px; color:#333;'>
                  <p>Dear <strong>{$employee['name']}</strong>,</p>
                  <p>We are delighted to inform you that your monthly salary has been successfully processed and disbursed.</p>

                  <table style='width:100%; border-collapse:collapse; margin:20px 0;'>
                    <tr>
                      <td style='padding:8px; border-bottom:1px solid #ddd;'>Amount:</td>
                      <td style='padding:8px; border-bottom:1px solid #ddd;'><strong>KES {$employee['salary']}</strong></td>
                    </tr>
                    <tr>
                      <td style='padding:8px; border-bottom:1px solid #ddd;'>Bank:</td>
                      <td style='padding:8px; border-bottom:1px solid #ddd;'><strong>{$employee['bank_name']}</strong></td>
                    </tr>
                    <tr>
                      <td style='padding:8px;'>Account Number:</td>
                      <td style='padding:8px;'><strong>{$employee['account_number']}</strong></td>
                    </tr>
                  </table>

                  <p>Thank you for your continued hard work and dedication to Convenient Courier.</p>
                  <p>For any inquiries regarding this transaction, kindly contact the Finance Department.</p>

                  <p style='margin-top:30px;'>Warm regards,<br>
                  <strong>HR & Finance Department</strong><br>
                  <em>Convenient Courier Employee Management System</em></p>
                </div>

                <div style='background-color:#f0f0f0; text-align:center; padding:15px; font-size:13px; color:#555;'>
                  <p>This is an automated message. Please do not reply directly to this email.</p>
                </div>
              </div>
            </div>";

            $mail->send();
            $message = "<div class='alert alert-success text-center'>Salary notification successfully sent to {$employee['name']} ({$employee['email']}).</div>";
        } catch (Exception $e) {
            $message = "<div class='alert alert-danger text-center'>Email could not be sent. Error: {$mail->ErrorInfo}</div>";
        }
    } else {
        $message = "<div class='alert alert-warning text-center'>Employee email not found!</div>";
    }
}

// Fetch employees
$employees = $conn->query("SELECT id, name, email FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Disburse Salary via Email</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background: #f8f9fa; }
.card { border-radius: 12px; box-shadow: 0 3px 15px rgba(0,0,0,0.1); }
</style>
</head>
<body class="p-4">

<div class="container">
  <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
  <h3 class="text-center text-primary mb-4">Salary Disbursement Notification</h3>
  <?= $message ?>

  <div class="card p-4 mx-auto" style="max-width: 600px;">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Select Employee:</label>
        <select name="employee_id" class="form-select" required>
          <option value="">-- Select Employee --</option>
          <?php while ($row = $employees->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['email']) ?>)</option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="text-center">
        <button type="submit" class="btn btn-success px-4">Send Notification</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
