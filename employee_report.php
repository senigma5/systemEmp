<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "admin_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search and filters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';
$role = isset($_GET['role']) ? $_GET['role'] : '';

$query = "SELECT * FROM employees WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR position LIKE '%$search%')";
}
if (!empty($department)) {
    $query .= " AND department = '$department'";
}
if (!empty($role)) {
    $query .= " AND role = '$role'";
}

$query .= " ORDER BY id ASC";
$result = $conn->query($query);

// Fetch filter options dynamically
$departments = $conn->query("SELECT DISTINCT department FROM employees WHERE department IS NOT NULL AND department <> ''");
$roles = $conn->query("SELECT DISTINCT role FROM employees WHERE role IS NOT NULL AND role <> ''");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Report</title>
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
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 15px;
      gap: 10px;
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
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
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
    <h2>Employee Report</h2>

    <form method="GET" class="filters">
      <input type="text" name="search" placeholder="Search by name, email or position" value="<?php echo htmlspecialchars($search); ?>">

      <select name="department">
        <option value="">All Departments</option>
        <?php while ($d = $departments->fetch_assoc()) { ?>
          <option value="<?php echo $d['department']; ?>" <?php if ($department == $d['department']) echo 'selected'; ?>>
            <?php echo $d['department']; ?>
          </option>
        <?php } ?>
      </select>

      <select name="role">
        <option value="">All Roles</option>
        <?php while ($r = $roles->fetch_assoc()) { ?>
          <option value="<?php echo $r['role']; ?>" <?php if ($role == $r['role']) echo 'selected'; ?>>
            <?php echo $r['role']; ?>
          </option>
        <?php } ?>
      </select>

      <button type="submit">Filter</button>
      <a href="employee_report.php" style="color:#007bff;text-decoration:none;">Reset</a>
    </form>

    <a href="#" class="export-btn" onclick="window.print()">Print Report</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Department</th>
        <th>Position</th>
        <th>Role</th>
        <th>Salary (Ksh)</th>
        <th>Date Joined</th>
        <th>Progress</th>
      </tr>
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>{$row['name']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['department']}</td>
                      <td>{$row['position']}</td>
                      <td>{$row['role']}</td>
                      <td>{$row['salary']}</td>
                      <td>{$row['date_joined']}</td>
                      <td>{$row['progress']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='9' style='text-align:center;'>No employees found</td></tr>";
      }
      $conn->close();
      ?>
    </table>

    <div class="footer">
         <a href="reports_dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>
      © <?php echo date("Y"); ?> Employee Report
    </div>
  </div>
</body>
</html>
