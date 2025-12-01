<?php 
session_start();
include '../includes/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$sql = "SELECT r.*, t.table_type 
        FROM tbl_reservations r
        JOIN tbl_tables t ON r.table_id = t.table_id
        WHERE r.customer_id = '$customer_id'
        ORDER BY r.reserve_id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Reservations</title>

<style>
body { font-family: Arial, sans-serif; background: #f4f4f9; padding: 20px; }
h2 { color: #111; }

.card {
    background: #fff; border-radius: 8px; padding: 15px;
    margin-bottom: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.status {
    padding: 6px 10px; color: #fff; border-radius: 4px;
    font-weight: bold; font-size: 13px;
}

.pending { background: #ffc107; } /* Yellow */
.approved { background: #28a745; } /* Green */
.cancelled { background: #dc3545; } /* Red */

.btn {
    display: inline-block; border: none;
    background: #0f1724; color: #fff;
    padding: 8px 14px; border-radius: 4px;
    text-decoration: none; margin-top: 10px;
}
</style>
</head>

<body>

<h2>My Reservations</h2>

<?php 
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status_class = strtolower($row['status']);
?>
<div class="card">
    <p><strong>Reservation ID:</strong> <?= $row['reserve_id'] ?></p>
    <p><strong>Table:</strong> Table for <?= $row['table_type'] ?></p>
    <p><strong>Date:</strong> <?= $row['date'] ?></p>
    <p><strong>Time:</strong> <?= $row['time'] ?></p>
    <p><strong>Status:</strong> 
        <span class="status <?= $status_class ?>"><?= $row['status'] ?></span>
    </p>

    <?php if ($row['status'] == "Approved") { ?>

    <a class="btn" href="view_reservations.php?id=<?= $row['reserve_id'] ?>">View Ticket</a>

<?php } elseif ($row['status'] == "Pending") { ?>

    <a class="btn btn-cancel" 
       href="cancel_reservation.php?id=<?= $row['reserve_id'] ?>" 
       onclick="return confirm('Are you sure you want to cancel this reservation?');">
       Cancel Reservation
    </a>

<?php } ?>

</div>

<?php  
    }
} else {
    echo "<p>No reservations found.</p>";
}
?>

</body>
</html>
