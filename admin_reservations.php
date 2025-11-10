<?php
include 'db.php';

// Fetch all reservations with table type
$sql = "SELECT r.*, t.table_type 
        FROM tbl_reservations r
        JOIN tbl_tables t ON r.table_id = t.table_id
        ORDER BY r.reserve_id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Reservations</title>
<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f7f7f7; }
    table { width: 100%; border-collapse: collapse; background: #fff; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
    th { background: #0f1724; color: #fff; }
    .btn {
        padding: 6px 10px; text-decoration: none; border-radius: 4px;
        border: none; cursor: pointer;
    }
    .approve { background: #28a745; color: #fff; }
    .print { background: #ff9800; color: #fff; }
    .back-btn {
    background: #ff9800; padding: 10px 20px; 
    color: #fff; text-decoration: none; border-radius: 5px;
}
</style>
</head>
<body>

<a href="admin_dashboard.php" class="back-btn">⬅ Back</a>

<h2>Reservations Management</h2>

<table>
<tr>
    <th>#</th>
    <th>Customer</th>
    <th>Phone</th>
    <th>Table Type</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['reserve_id']; ?></td>
    <td><?= $row['fullname']; ?></td>
    <td><?= $row['phone']; ?></td>
    <td>Table for <?= $row['table_type']; ?></td>
    <td><?= $row['date']; ?></td>
    <td><?= $row['time']; ?></td>
    <td><?= $row['status']; ?></td>
    
    <td>
        <?php if ($row['status'] == "Pending") { ?>
            <a href="approve.php?id=<?= $row['reserve_id']; ?>" class="btn approve">Approve</a>
        <?php } ?>

        <?php if ($row['status'] == "Approved") { ?>
            <a href="view_reservations.php?id=<?= $row['reserve_id']; ?>" class="btn print">Print Memo</a>
        <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
