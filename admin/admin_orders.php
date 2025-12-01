<?php
session_start();
include '../includes/db.php'; // ✅ path to DB (if admin folder is inside root)

// ✅ Check admin login (optional – if you already use admin login)
if (!isset($_SESSION['user_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// ✅ Update order status when admin submits change
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];

    // ✅ Update DB
    $update = "UPDATE tbl_order SET status='$new_status' WHERE order_id='$order_id'";
    mysqli_query($conn, $update);

    // ✅ If confirmed → show notification to customer
    if ($new_status == "confirmed") {
        mysqli_query($conn, "UPDATE tbl_order SET is_notified=0 WHERE order_id='$order_id'");
    }

    echo "<script>alert('Order updated successfully');</script>";
}

// ✅ Get all orders
$orders = mysqli_query($conn, "SELECT * FROM tbl_order ORDER BY order_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <style>
        table { width:100%; border-collapse:collapse; }
        th,td { padding:10px; border:1px solid #ccc; text-align:center; }
        th { background:#333; color:#fff; }
        .btn { padding:5px 10px; background:#28a745; color:#fff; border:none; cursor:pointer; border-radius:6px; }
    </style>
</head>
<body>

<h2>Manage Orders</h2>

<table>
<tr>
    <th>Order ID</th>
    <th>Customer</th>
    <th>Total</th>
    <th>Type</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($orders)) { ?>
<tr>
    <form method="POST">
    <td><?php echo $row['order_id']; ?></td>
    <td><?php echo $row['customer_id']; ?></td>
    <td>₦<?php echo $row['total_price']; ?></td>
    <td><?php echo $row['order_type']; ?></td>
    <td>
        <select name="status">
            <option value="pending" <?php if($row['status']=="pending") echo "selected"; ?>>Pending</option>
            <option value="confirmed" <?php if($row['status']=="confirmed") echo "selected"; ?>>Confirmed</option>
            <option value="completed" <?php if($row['status']=="completed") echo "selected"; ?>>Completed</option>
            <option value="canceled" <?php if($row['status']=="canceled") echo "selected"; ?>>Canceled</option>
        </select>
    </td>

    <td>
        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
        <button type="submit" class="btn" name="update_status">Update</button>
    </td>
    </form>
</tr>
<?php } ?>

</table>

</body>
</html>
