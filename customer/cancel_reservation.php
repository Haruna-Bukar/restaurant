<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: ../public/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = intval($_GET['id']);
$customer_id = $_SESSION['customer_id'];

// ✅ Only allow cancel if still pending
$sql = "UPDATE tbl_reservations 
        SET status='Cancelled' 
        WHERE reserve_id='$id' AND customer_id='$customer_id' AND status='Pending'";

if ($conn->query($sql)) {
    echo "<script>
            alert('Reservation cancelled successfully!');
            window.location.href='my_reservations.php';
          </script>";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
