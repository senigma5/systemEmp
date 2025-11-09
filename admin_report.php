<?php
// Include the shared database connection file
include('db.php');

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query for admin records
$query = "SELECT * FROM admins WHERE 1=1";
if (!empty($search)) {
    $query .= " AND username LIKE '%$search%'";
}
$query .= " ORDER BY created_at DESC";
$result = $conn->query($query);

// Count total admins
$count_query = $conn->query("SELECT COUNT(*) AS total_admins FROM admins");
$count = $count_query->fetch_assoc()['total_admins'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Activity Report</title>
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
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 15px;
    }
    .filters input {
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
    tfoot td {
      font-weight: bold;
      background-color: #e9ecef;
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
    <h2>Admin Activity Report</h2>

    <form method="GET" class="filters">
      <input type="text" name="search" placeholder="Search admin username" value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit">Filter</button>
      <a href="admin_report.php" style="color:#007bff;text-decoration:none;">Reset</a>
    </form>

    <a href="#" class="export-btn" onclick="window.print()">Print Report</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Password</th>
        <th>Date Created</th>
      </tr>
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['username']}</td>
                      <td>{$row['password']}</td>
                      <td>{$row['created_at']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='4' style='text-align:center;'>No admin records found</td></tr>";
      }
      ?>
      <tfoot>
        <tr>
          <td colspan="3">Total Admins</td>
          <td><?php echo $count; ?></td>
        </tr>
      </tfoot>
    </table>

    <div class="footer">
         <a href="reports_dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>
      © <?php echo date("Y"); ?> Admin Report
    </div>
  </div>
</body>
</html>
