<?php
session_start();
include 'db.php';

// ✅ Only Logged Admin Allowed
if (!isset($_SESSION['user_id'])) {
    header("Location: adminlogin.php");
    exit();
}

$sql = "SELECT * FROM tbl_customers ORDER BY customer_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Customers</title>
<style>
body { font-family: Arial; padding: 20px; background: #f7f7f7; }
h2 { color: #0f1724; }
table { width: 100%; border-collapse: collapse; background: #fff; }
th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
th { background: #0f1724; color: #fff; }
.back-btn {
    background: #ff9800; padding: 10px 20px; 
    color: #fff; text-decoration: none; border-radius: 5px;
}
</style>
</head>
<body>

<a href="admin_dashboard.php" class="back-btn">⬅ Back</a>
<h2>Registered Customers</h2>

<table>
<tr>
    <th>#</th>
    <th>Full Name</th>
    <th>Email</th>
    <th>Phone</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['customer_id']; ?></td>
    <td><?= $row['firstname'] . " " . $row['lastname']; ?></td>
    <td><?= $row['email']; ?></td>
    <td><?= $row['phone']; ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>
