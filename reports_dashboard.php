<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reports Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f4f6f8;
      margin: 0;
      padding: 0;
    }

    header {
      background: #007bff;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      background: white;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
    }

    .report-links {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      transition: 0.3s;
      border: 1px solid #ddd;
    }

    .card:hover {
      background: #007bff;
      color: white;
      transform: translateY(-3px);
      box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }

    .card a {
      text-decoration: none;
      color: inherit;
      font-size: 18px;
      font-weight: 600;
      display: block;
    }

    footer {
      text-align: center;
      margin-top: 30px;
      color: #555;
      font-size: 14px;
      padding-bottom: 10px;
    }
  </style>
</head>
<body>

  <header>
    <h1>Reports Dashboard</h1>
  </header>

  <div class="container">
    <h2>Select a Report to View</h2>

    <div class="report-links">
      <div class="card">
        <a href="employee_report.php">Employee Report</a>
      </div>

      <div class="card">
        <a href="attendance_report.php">Attendance Report</a>
      </div>

      <div class="card">
        <a href="leave_report.php">Leave Report</a>
      </div>

      <div class="card">
        <a href="payroll_report.php">Payroll Report</a>
      </div>

      <div class="card">
        <a href="notification_report.php">Notification Report</a>
      </div>

      <div class="card">
        <a href="admin_report.php"> Admin Report</a>
      </div>
    </div>
  </div>

  <footer>
     <a href="admin_dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>
    © <?php echo date("Y"); ?> Reports Dashboard | All Rights Reserved
  </footer>

</body>
</html>
