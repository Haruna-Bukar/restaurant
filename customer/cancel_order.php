<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['customer_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: ../public/login.php");
    exit();
}

// Determine role
$role = isset($_SESSION['admin_id']) ? 'admin' : 'customer';

// Get order ID
if (!isset($_GET['id'])) {
    die("Invalid request!");
}
$order_id = intval($_GET['id']);

// Build query depending on role
if ($role === 'customer') {
    $user_id = $_SESSION['customer_id'];
    // Cancel only if belongs to customer and pending
    $sql = "UPDATE tbl_order 
            SET status='Cancelled'
            WHERE order_id='$order_id' 
            AND customer_id='$user_id'
            AND status='Pending'";
} else {
    // Admin can cancel any order regardless of customer
    $sql = "UPDATE tbl_order 
            SET status='Cancelled'
            WHERE order_id='$order_id'
            AND status='Pending'";
}

// Execute query
if ($conn->query($sql)) {
    $redirect = $role === 'user' ? '../admin/admin_orders.php' : 'customer_dashboard.php';
    echo "<script>
            alert('Order has been cancelled successfully!');
            window.location.href='$redirect';
          </script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
