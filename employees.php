<?php
include('db.php');

// DELETE employee
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM employees WHERE id=$id");
    echo "<script>alert('Employee deleted successfully!'); window.location='employees.php';</script>";
    exit;
}

// UPDATE employee
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $department = $_POST['department'] ?? '';
    $position = $_POST['position'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $account_number = $_POST['account_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $role = $_POST['role'] ?? '';
    $date_joined = $_POST['date_joined'] ?? date('Y-m-d');
    $progress = $_POST['progress'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $salary = $_POST['salary'] ?? 0;

    $sql = "UPDATE employees SET 
            name='$name', email='$email', phone='$phone', department='$department',
            position='$position', bank_name='$bank_name', account_number='$account_number',
            address='$address', role='$role', date_joined='$date_joined', progress='$progress',
            username='$username', password='$password', salary='$salary'
            WHERE id=$id";

    if ($conn->query($sql)) {
        echo "<script>alert('Employee updated successfully!'); window.location='employees.php';</script>";
    } else {
        echo "<script>alert('Error updating employee.');</script>";
    }
}

// ADD employee
if (isset($_POST['add'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $department = $_POST['department'] ?? '';
    $position = $_POST['position'] ?? '';
    $bank_name = $_POST['bank_name'] ?? '';
    $account_number = $_POST['account_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $role = $_POST['role'] ?? '';
    $date_joined = $_POST['date_joined'] ?? date('Y-m-d');
    $progress = $_POST['progress'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $salary = $_POST['salary'] ?? 0;

    if (!empty($username)) {
        $check = $conn->query("SELECT * FROM employees WHERE username='$username'");
        if ($check->num_rows > 0) {
            echo "<script>alert('Username already exists!');</script>";
        } else {
            $sql = "INSERT INTO employees 
                    (name, email, phone, department, position, bank_name, account_number, address, role, date_joined, progress, username, password, salary)
                    VALUES 
                    ('$name','$email','$phone','$department','$position','$bank_name','$account_number','$address','$role','$date_joined','$progress','$username','$password','$salary')";
            if ($conn->query($sql)) {
                echo "<script>alert('Employee added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding employee.');</script>";
            }
        }
    } else {
        echo "<script>alert('Username cannot be empty!');</script>";
    }
}

// FETCH all employees
$result = $conn->query("SELECT * FROM employees");

// FETCH single employee for editing
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = $conn->query("SELECT * FROM employees WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Management</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, rgba(13,110,253,0.85), rgba(108,117,125,0.85)), url('imag2.png');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 20px;
    }
    form, table {
        background: rgba(0,0,0,0.5);
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        margin: 0 auto 20px;
    }
    input, textarea {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        border-radius: 5px;
        border: none;
    }
    button {
        background-color: #0d6efd;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background-color: #0b5ed7;
    }
    table {
        width: 90%;
        margin: auto;
        border-collapse: collapse;
        background-color: rgba(255,255,255,0.1);
    }
    table th, table td {
        padding: 10px;
        border-bottom: 1px solid white;
        text-align: left;
    }
    .back {
        display: inline-block;
        margin: 20px auto;
        background: #6c757d;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
    }
    .back:hover {
        background: #5a6268;
    }
</style>
</head>
<body>

<h2>Employee Management</h2>

<form method="POST">
    <h3><?= $editData ? 'Update Employee' : 'Add New Employee' ?></h3>

    <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

    <input type="text" name="name" placeholder="Full Name" value="<?= $editData['name'] ?? '' ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?= $editData['email'] ?? '' ?>">
    <input type="text" name="phone" placeholder="Phone" value="<?= $editData['phone'] ?? '' ?>">
    <input type="text" name="department" placeholder="Department" value="<?= $editData['department'] ?? '' ?>">
    <input type="text" name="position" placeholder="Position" value="<?= $editData['position'] ?? '' ?>">
    <input type="text" name="bank_name" placeholder="Bank Name" value="<?= $editData['bank_name'] ?? '' ?>">
    <input type="text" name="account_number" placeholder="Account Number" value="<?= $editData['account_number'] ?? '' ?>">
    <input type="text" name="address" placeholder="Address" value="<?= $editData['address'] ?? '' ?>">
    <input type="text" name="role" placeholder="Role" value="<?= $editData['role'] ?? '' ?>">
    <input type="date" name="date_joined" value="<?= $editData['date_joined'] ?? '' ?>">
    <textarea name="progress" placeholder="Progress Notes"><?= $editData['progress'] ?? '' ?></textarea>
    <input type="text" name="username" placeholder="Username" value="<?= $editData['username'] ?? '' ?>" required>
    <input type="text" name="password" placeholder="Password" value="<?= $editData['password'] ?? '' ?>" required>
    <input type="number" step="0.01" name="salary" placeholder="Salary" value="<?= $editData['salary'] ?? '' ?>">

    <button type="submit" name="<?= $editData ? 'update' : 'add' ?>">
        <?= $editData ? 'Update Employee' : 'Add Employee' ?>
    </button>
</form>

<table>
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Phone</th>
        <th>Department</th><th>Position</th><th>Salary</th><th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?= htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?= htmlspecialchars($row['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?= htmlspecialchars($row['department'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
<td><?= htmlspecialchars($row['position'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>

            <td><?= $row['salary']; ?></td>
            <td>
                <a href="?edit=<?= $row['id']; ?>" style="color:yellow;">Edit</a> |
                <a href="?delete=<?= $row['id']; ?>" style="color:red;" onclick="return confirm('Delete this employee?');">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="admin_dashboard.php" class="back">&larr; Back</a>

</body>
</html>
