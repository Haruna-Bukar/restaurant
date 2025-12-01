<?php
session_start();
include '../includes/db.php';

if (!isset($_GET['id'])) {
    die("Reservation ID not found!");
}

$id = intval($_GET['id']);

// Fetch reservation details + table info
$sql = "SELECT r.*, t.table_type 
        FROM tbl_reservations r
        JOIN tbl_tables t ON r.table_id = t.table_id
        WHERE r.reserve_id = '$id'";
$res = $conn->query($sql);

if ($res->num_rows == 0) {
    die("Invalid reservation");
}

$row = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reservation Details</title>
<style>
body { font-family: Arial, sans-serif; background:#f7f7f7; padding:20px; }
.container {
    background:#fff; max-width:600px; margin:30px auto; padding:24px;
    border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.08);
}
h2 { text-align:center; color:#0f1724; margin-bottom:10px; }
.status {
    text-align:center; padding:10px; font-weight:bold; 
    border-radius:6px; margin-bottom:15px;
}
.pending { background:#fff3cd; color:#856404; }
.approved { background:#d4edda; color:#155724; }
.btn {
    display:block; width:100%; text-align:center;
    padding:12px; margin-top:12px; 
    background:#ff9800; color:#fff;
    text-decoration:none; font-weight:bold;
    border-radius:8px;
}
.btn:hover { opacity:0.85; }
</style>
</head>
<body>

<div class="container">
    <h2>Reservation Details</h2>

    <p><strong>Name:</strong> <?= $row['fullname']; ?></p>
    <p><strong>Phone:</strong> <?= $row['phone']; ?></p>
    <p><strong>Email:</strong> <?= $row['email']; ?></p>
    <p><strong>Table Type:</strong> Table for <?= $row['table_type']; ?></p>
    <p><strong>Reservation_code:</strong> <?= $row['reservation_code']; ?></p>
    <p><strong>Date:</strong> <?= $row['date']; ?></p>
    <p><strong>Time:</strong> <?= $row['time']; ?></p>

    <!-- STATUS -->
    <div class="status <?= strtolower($row['status']); ?>">
        <?= $row['status']; ?>
        <meta http-equiv="refresh" content="10">
    </div>

    <?php if ($row['status'] == "Approved") { ?>
        <a href="../misc/memo.php?id=<?= $row['reserve_id']; ?>" class="btn">
            Download Reservation Ticket
        </a>
    <?php } else { ?>
        <p style="text-align:center;color:#555;">⏳ Waiting for admin approval.</p>
    <?php } ?>

</div>

</body>
</html>
