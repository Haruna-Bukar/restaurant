<?php
include '../includes/db.php';

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = intval($_GET['id']);

// ✅ Check if reservation code already exists
$check = $conn->query("SELECT reservation_code FROM tbl_reservations WHERE reserve_id='$id'");
$row = $check->fetch_assoc();

if ($row['reservation_code'] == "" || $row['reservation_code'] == NULL) {
    // ✅ Generate code only once
    $reservation_code = "PR-" . rand(10000, 99999);
} else {
    $reservation_code = $row['reservation_code'];
}

// ✅ Update only status + keep old code
$sql = "UPDATE tbl_reservations 
        SET status='Approved', reservation_code='$reservation_code'
        WHERE reserve_id='$id'";

if ($conn->query($sql)) {
    echo "<script>
        alert('✅ Reservation Approved Successfully!');
        window.location.href='admin_reservations.php';
    </script>";
} else {
    echo "❌ Error: " . $conn->error;
}

$conn->close();
?>
