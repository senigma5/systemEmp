<?php
// ✅ Use shared DB connection
include('db.php');

// Handle filters safely
$employee = isset($_GET['employee']) ? $conn->real_escape_string($_GET['employee']) : '';
$sender = isset($_GET['sender']) ? $conn->real_escape_string($_GET['sender']) : '';
$from_date = isset($_GET['from_date']) ? $conn->real_escape_string($_GET['from_date']) : '';
$to_date = isset($_GET['to_date']) ? $conn->real_escape_string($_GET['to_date']) : '';

// Base query
$query = "SELECT notifications.*, employees.name 
          FROM notifications 
          LEFT JOIN employees ON notifications.employee_id = employees.id 
          WHERE 1=1";

// Apply filters
if (!empty($employee)) {
    $query .= " AND employees.name LIKE '%$employee%'";
}
if (!empty($sender)) {
    $query .= " AND notifications.sender LIKE '%$sender%'";
}
if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND DATE(notifications.created_at) BETWEEN '$from_date' AND '$to_date'";
}

$query .= " ORDER BY notifications.created_at DESC";
$result = $conn->query($query);

// Fetch dynamic options
$employees = $conn->query("SELECT DISTINCT name FROM employees WHERE name IS NOT NULL AND name <> ''");
$senders = $conn->query("SELECT DISTINCT sender FROM notifications WHERE sender IS NOT NULL AND sender <> ''");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notification Report</title>
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
    .message-cell {
      max-width: 400px;
      white-space: pre-wrap;
      word-wrap: break-word;
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
    <h2>Notification Report</h2>

    <form method="GET" class="filters">
      <select name="employee">
        <option value="">All Employees</option>
        <?php while ($e = $employees->fetch_assoc()) { ?>
          <option value="<?php echo htmlspecialchars($e['name']); ?>" <?php if ($employee == $e['name']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($e['name']); ?>
          </option>
        <?php } ?>
      </select>

      <select name="sender">
        <option value="">All Senders</option>
        <?php while ($s = $senders->fetch_assoc()) { ?>
          <option value="<?php echo htmlspecialchars($s['sender']); ?>" <?php if ($sender == $s['sender']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($s['sender']); ?>
          </option>
        <?php } ?>
      </select>

      <input type="date" name="from_date" value="<?php echo htmlspecialchars($from_date); ?>">
      <input type="date" name="to_date" value="<?php echo htmlspecialchars($to_date); ?>">
      <button type="submit">Filter</button>
      <a href="notification_report.php" style="color:#007bff;text-decoration:none;">Reset</a>
    </form>

    <a href="#" class="export-btn" onclick="window.print()">Print Report</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Recipient</th>
        <th>Sender</th>
        <th>Message</th>
        <th>Date Sent</th>
      </tr>
      <?php
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $recipient = !empty($row['name']) ? htmlspecialchars($row['name']) : 'All Employees';
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$recipient}</td>
                      <td>" . htmlspecialchars($row['sender']) . "</td>
                      <td class='message-cell'>" . htmlspecialchars($row['message']) . "</td>
                      <td>{$row['created_at']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='5' style='text-align:center;'>No notifications found</td></tr>";
      }
      $conn->close();
      ?>
    </table>

    <div class="footer">
      <a href='reports_dashboard.php' class='btn btn-secondary back-btn'>← Back to Dashboard</a>
      © <?php echo date("Y"); ?> Notification Report
    </div>
  </div>
</body>
</html>
