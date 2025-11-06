<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "admin_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filters
$employee = isset($_GET['employee']) ? $_GET['employee'] : '';
$leave_type = isset($_GET['leave_type']) ? $_GET['leave_type'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
$to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

// Base query with join
$query = "SELECT leave_records.*, employees.name 
          FROM leave_records 
          INNER JOIN employees ON leave_records.employee_id = employees.id 
          WHERE 1=1";

// Apply filters
if (!empty($employee)) {
    $query .= " AND employees.name LIKE '%$employee%'";
}
if (!empty($leave_type)) {
    $query .= " AND leave_records.leave_type = '$leave_type'";
}
if (!empty($status)) {
    $query .= " AND leave_records.status = '$status'";
}
if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND leave_records.from_date BETWEEN '$from_date' AND '$to_date'";
}

$query .= " ORDER BY leave_records.from_date DESC";
$result = $conn->query($query);

// Fetch dynamic options
$employees = $conn->query("SELECT DISTINCT name FROM employees");
$leave_types = $conn->query("SELECT DISTINCT leave_type FROM leave_records");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Leave Report</title>
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
      border: none;
      background: #007bff;
      color: white;
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
    .status-approved {
      color: green;
      font-weight: bold;
    }
    .status-pending {
      color: orange;
      font-weight: bold;
    }
    .status-rejected {
      color: red;
      font-weight: bold;
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
    <h2>Employee Leave Report</h2>

    <form method="GET" class="filters">
      <select name="employee">
        <option value="">All Employees</option>
        <?php while ($e = $employees->fetch_assoc()) { ?>
          <option value="<?php echo $e['name']; ?>" <?php if ($employee == $e['name']) echo 'selected'; ?>>
            <?php echo $e['name']; ?>
          </option>
        <?php } ?>
      </select>

      <select name="leave_type">
        <option value="">All Leave Types</option>
        <?php while ($l = $leave_types->fetch_assoc()) { ?>
          <option value="<?php echo $l['leave_type']; ?>" <?php if ($leave_type == $l['leave_type']) echo 'selected'; ?>>
            <?php echo $l['leave_type']; ?>
          </option>
        <?php } ?>
      </select>

      <select name="status">
        <option value="">All Status</option>
        <option value="Approved" <?php if ($status == 'Approved') echo 'selected'; ?>>Approved</option>
        <option value="Pending" <?php if ($status == 'Pending') echo 'selected'; ?>>Pending</option>
        <option value="Rejected" <?php if ($status == 'Rejected') echo 'selected'; ?>>Rejected</option>
      </select>

      <input type="date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>">
      <input type="date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>">

      <button type="submit">Filter</button>
      <a href="leave_report.php" style="color:#007bff;text-decoration:none;">Reset</a>
    </form>

    <a href="#" class="export-btn" onclick="window.print()">Print Report</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Employee Name</th>
        <th>Leave Type</th>
        <th>From</th>
        <th>To</th>
        <th>Reason</th>
        <th>Status</th>
      </tr>
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $status_class = strtolower($row['status']) == 'approved' ? 'status-approved' :
                              (strtolower($row['status']) == 'pending' ? 'status-pending' : 'status-rejected');
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['name']}</td>
                      <td>{$row['leave_type']}</td>
                      <td>{$row['from_date']}</td>
                      <td>{$row['to_date']}</td>
                      <td>{$row['reason']}</td>
                      <td class='$status_class'>{$row['status']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='7' style='text-align:center;'>No leave records found</td></tr>";
      }
      $conn->close();
      ?>
    </table>

    <div class="footer">
         <a href="reports_dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>
      © <?php echo date("Y"); ?> Leave Report 
    </div>
  </div>
</body>
</html>
