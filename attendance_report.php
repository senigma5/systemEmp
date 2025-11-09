<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "db.php");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle filters
$employee = isset($_GET['employee']) ? $_GET['employee'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Base query
$query = "SELECT attendance.*, employees.name 
          FROM attendance 
          INNER JOIN employees ON attendance.employee_id = employees.id 
          WHERE 1=1";

// Apply filters
if (!empty($employee)) {
    $query .= " AND employees.name LIKE '%$employee%'";
}
if (!empty($status)) {
    $query .= " AND attendance.status = '$status'";
}
if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND attendance.date BETWEEN '$from_date' AND '$to_date'";
}

$query .= " ORDER BY attendance.date DESC";
$result = $conn->query($query);

// Fetch employees for dropdown
$employees = $conn->query("SELECT DISTINCT name FROM employees");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Attendance Report</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f8;
      margin: 0;
      padding: 20px;
    }
    .container {
      background: white;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #333;
    }
    .filters {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 15px;
    }
    .filters input, .filters select {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .filters button {
      padding: 8px 16px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .filters button:hover {
      background: #0056b3;
    }
    .export-btn {
      display: inline-block;
      padding: 8px 16px;
      background: #28a745;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      margin-bottom: 10px;
    }
    .export-btn:hover {
      background: #218838;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .footer {
      text-align: center;
      margin-top: 20px;
      color: #555;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Attendance Report</h2>

    <form method="GET" class="filters">
      <select name="employee">
        <option value="">All Employees</option>
        <?php while ($e = $employees->fetch_assoc()) { ?>
          <option value="<?php echo $e['name']; ?>" <?php if ($employee == $e['name']) echo 'selected'; ?>>
            <?php echo $e['name']; ?>
          </option>
        <?php } ?>
      </select>

      <select name="status">
        <option value="">All Statuses</option>
        <option value="Present" <?php if ($status == 'Present') echo 'selected'; ?>>Present</option>
        <option value="Absent" <?php if ($status == 'Absent') echo 'selected'; ?>>Absent</option>
        <option value="Late" <?php if ($status == 'Late') echo 'selected'; ?>>Late</option>
        <option value="Leave" <?php if ($status == 'Leave') echo 'selected'; ?>>Leave</option>
      </select>

      <input type="date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>">
      <input type="date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>">
      <button type="submit">Filter</button>
      <a href="attendance_report.php" style="color:#007bff;text-decoration:none;">Reset</a>
    </form>

    <a href="#" class="export-btn" onclick="window.print()">Print Report</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Employee Name</th>
        <th>Status</th>
        <th>Date</th>
        <th>Recorded On</th>
      </tr>
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['name']}</td>
                      <td>{$row['status']}</td>
                      <td>{$row['date']}</td>
                      <td>{$row['created_at']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='5' style='text-align:center;'>No attendance records found</td></tr>";
      }
      $conn->close();
      ?>
    </table>

    <div class="footer">
        <a href="reports_dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>
      © <?php echo date("Y"); ?> Attendance Report 
    </div>
  </div>
</body>
</html>
