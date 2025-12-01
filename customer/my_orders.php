<?php
session_start();
include '../includes/db.php';

// ✅ Redirect if not logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// ✅ Get all user's orders
$query = "SELECT * FROM tbl_order WHERE customer_id = '$customer_id' ORDER BY order_id DESC";
$result = mysqli_query($conn, $query);

// ✅ Mark notifications as seen once page opens
mysqli_query($conn, "UPDATE tbl_order SET is_notified = 1 WHERE customer_id = '$customer_id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Orders</title>
<link rel="stylesheet" href="../assets/css/harun.css">
<style>
.order-card {
    background: #fff;
    color:  #ff9800;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
    border-left: 6px solid var(--accent);
}
.order-status {
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 6px;
    display: inline-block;
}
.status-pending { background: #ffc107; }
.status-confirmed { background: #17a2b8; color:#fff; }
.status-completed { background: #28a745; color:#fff; }
.status-canceled { background: #dc3545; color:#fff; }
.download-btn {
    margin-top: 10px;
    display: inline-block;
    background: var(--accent);
    color: white;
    padding: 7px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
}
.download-btn:hover {
    background: #fff;
    color: var(--accent);
    border: 2px solid var(--accent);
}
</style>
</head>
<body>

<h2 style="text-align:center;">My Orders</h2>

<div class="order-container">
<?php
if (mysqli_num_rows($result) == 0) {
    echo "<p style='text-align:center;color:gray;'>No orders yet.</p>";
}

while ($row = mysqli_fetch_assoc($result)) {
    $ticket = $row['ticket_code'];
    $status = strtolower($row['status']);
    $status_class = "status-$status";

    echo "
    <div class='order-card'>
        <h3>Order #{$row['order_id']}</h3>
        <p>Total Price: ₦{$row['total_price']}</p>
        <p>Order Type: {$row['order_type']}</p>
        <p>Ticket Code: <b>$ticket</b></p>
        <span class='order-status $status_class'>" . ucfirst($row['status']) . "</span><br><br>
    ";

    if ($row['status'] == "confirmed") {
        echo "<a class='download-btn' href='download_ticket.php?ticket=$ticket'>Download Ticket</a>";
    }

    // ✅ Cancel button only when pending
    if ($row['status'] == "pending") {
        echo "
        <a class='download-btn' style='background:#dc3545' 
           href='cancel_order.php?id={$row['order_id']}'
           onclick='return confirm(\"Are you sure you want to cancel this order?\");'>
           Cancel Order
        </a>";
    }

    echo "</div>";
}
?>
</div>

</body>
</html>
