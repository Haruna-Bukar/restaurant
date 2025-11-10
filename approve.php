<?php
include 'db.php';

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = intval($_GET['id']);

// ✅ Generate a unique reservation code
$reservation_code = "PR-" . rand(10000, 99999);

// ✅ Update reservation status & add ticket code
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
