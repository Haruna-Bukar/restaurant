<?php
include 'db.php';

// Check reservation ID
if (!isset($_GET['id'])) {
    die("Invalid reservation");
}

$id = intval($_GET['id']); // Secure conversion to integer

// Fetch reservation with customer info
$sql = "SELECT r.*, CONCAT(c.firstname,' ',c.lastname) AS fullname, 
        c.phone, t.table_type 
        FROM tbl_reservations r 
        JOIN tbl_customers c ON r.customer_id = c.customer_id 
        JOIN tbl_tables t ON r.table_id = t.table_id
        WHERE r.reserve_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Reservation not found!");
}

$res = $result->fetch_assoc();

// Prevent non-approved tickets
if ($res['status'] !== 'Approved') {
    die("This reservation is not approved yet!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reservation Ticket - Pitti Restaurant</title>
<style>
    * { font-family: Arial, sans-serif; }
    body { background: #f7f7f7; padding: 30px; }
    .ticket {
        width: 450px;
        background: #fff;
        margin: auto;
        padding: 20px 25px;
        border: 3px solid #ff9800;
        border-radius: 12px;
        text-align: center;
    }
    .logo {
        width: 80px;
        height: 80px;
        background: #ddd;
        border-radius: 50%;
        margin: auto;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        font-size: 12px;
    }
    .logo img {
        max-width: 70px;
        max-height: 70px;
    }
    h1 {
        color: #0f1724;
        font-size: 22px;
        margin-bottom: 4px;
    }
    .line {
        margin: 10px 0;
        border-bottom: 2px dashed #ccc;
    }
    .label { font-weight: bold; color: #444; }
    .code {
        font-size: 1.2rem;
        font-weight: bold;
        margin-top: 10px;
        color: #ff9800;
    }
    .footer {
        margin-top: 15px;
        font-size: 12px;
        color: #555;
    }
    .print-btn {
        margin-top: 20px;
        background: #ff9800;
        color: #fff;
        padding: 10px 14px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        text-decoration: none;
    }
    @media print {
    body {
        background: #fff !important;
    }
    .print-btn {
        display: none !important;
    }
}

</style>
</head>
<body>

<div class="ticket">
    <div class="logo"><img src="assets/image.png" alt="pitti restaurant logo"></div>

    <h1>Pitti Restaurant</h1>
    <p>Reservation Confirmation</p>

    <div class="line"></div>

    <p><span class="label">Customer:</span> <?= $res['fullname']; ?></p>
    <p><span class="label">Phone:</span> <?= $res['phone']; ?></p>
    <p><span class="label">Table Type:</span> <?= $res['table_type']; ?></p>
    <p><span class="label">Date:</span> <?= $res['date']; ?></p>
    <p><span class="label">Time:</span> <?= $res['time']; ?></p>

    <div class="code">Code: <?= $res['reservation_code']; ?></div>

    <div class="line"></div>

    <div class="footer">
        Show this reservation ticket at the entrance for verification.
        <br><strong>Thank you for choosing us!</strong>
    </div>
    <button class="print-btn" onclick="window.print()">Download / Print as PDF</button>

</div>

</body>
</html>
