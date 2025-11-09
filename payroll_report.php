<?php
// ✅ Use shared database connection
include('db.php');

// Handle filters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';
$role = isset($_GET['role']) ? $_GET['role'] : '';

$query = "SELECT * FROM employees WHERE 1=1";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR position LIKE '%$search%')";
}
if (!empty($department)) {
    $department = $conn->real_escape_string($department);
    $query .= " AND department = '$department'";
}
if (!empty($role)) {
    $role = $conn->real_escape_string($role);
    $query .= " AND role = '$role'";
}

$query .= " ORDER BY id ASC";
$result = $conn->query($query);

// Fetch filter options
$departments = $conn->query("SELECT DISTINCT department FROM employees WHERE department IS NOT NULL AND department <> ''");
$roles = $conn->query("SELECT DISTINCT role FROM employees WHERE role IS NOT NULL AND role <> ''");

// Calculate total salary
$total_salary = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payroll Report</title>
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
    <h2>Payroll Report</h2>

    <form method="GET" class="filters">
      <input type="text" name="search" placeholder="Search by name, email or position" value="<?php echo htmlspecialchars($search); ?>">

      <select name="department">
        <option value="">All Departments</option>
        <?php while ($d = $departments->fetch_assoc()) { ?>
          <option value="<?php echo htmlspecialchars($d['department']); ?>" <?php if ($department == $d['department']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($d['department']); ?>
          </option>
        <?php } ?>
      </select>

      <select name="role">
        <option value="">All Roles</option>
        <?php while ($r = $roles->fetch_assoc()) { ?>
          <option value="<?php echo htmlspecialchars($r['role']); ?>" <?php if ($role == $r['role']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($r['role']); ?>
          </option>
        <?php } ?>
      </select>

      <button type="submit">Filter</button>
      <a href="payroll_report.php" style="color:#007bff;text-decoration:none;">Reset</a>
    </form>

    <a href="#" class="export-btn" onclick="window.print()">Print Report</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Employee Name</th>
        <th>Department</th>
        <th>Position</th>
        <th>Role</th>
        <th>Salary (Ksh)</th>
        <th>Date Joined</th>
      </tr>
      <?php
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $total_salary += $row['salary'];
              echo "<tr>
                      <td>{$row['id']}</td>
                      <td>" . htmlspecialchars($row['name']) . "</td>
                      <td>" . htmlspecialchars($row['department']) . "</td>
                      <td>" . htmlspecialchars($row['position']) . "</td>
                      <td>" . htmlspecialchars($row['role']) . "</td>
                      <td>" . number_format($row['salary'], 2) . "</td>
                      <td>{$row['date_joined']}</td>
                    </tr>";
          }
      } else {
          echo "<tr><td colspan='7' style='text-align:center;'>No payroll data found</td></tr>";
      }
      ?>
      <tfoot>
        <tr>
          <td colspan="5">Total Salary</td>
          <td colspan="2">Ksh <?php echo number_format($total_salary, 2); ?></td>
        </tr>
      </tfoot>
    </table>

    <div class="footer">
      <a href="reports_dashboard.php" class="btn btn-secondary back-btn">← Back to Dashboard</a>
      © <?php echo date("Y"); ?> Payroll Report | Generated by Admin System
    </div>
  </div>
</body>
</html>
