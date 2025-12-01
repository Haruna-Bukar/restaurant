<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['order_type']) || $_SESSION['order_type'] !== "delivery") {
    header("Location: ../public/checkout.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['delivery_name'] = $_POST['fullname'];
    $_SESSION['delivery_phone'] = $_POST['phone'];
    $_SESSION['delivery_address'] = $_POST['address'];
    $_SESSION['delivery_distance_km'] = $_POST['distance_km'] ?? 0;
    

    // fixed delivery fee
    $_SESSION['delivery_fee'] = 500; // Flat rate for delivery

    header("Location: process_order.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Delivery Information</title>
<style>
    body { font-family: Arial; padding: 20px; background: #f7f7f7; }
    h2 { color: #0f1724; }
    form { background: #fff; padding: 20px; border-radius: 5px; }
    input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
    .btn {
        background: #0f1724; color: #fff; padding: 10px 20px; 
        border: none; border-radius: 5px; cursor: pointer;
    }
    .btn:hover {
        background: #1e293b;
    }
</style>   
</head> 
<body>
<h2>Delivery Information</h2>
<form method="POST">
    <input type="text" name="fullname" placeholder="Full Name" required><br><br>
    <input type="text" name="phone" placeholder="Phone" required><br><br>
    <textarea name="address" placeholder="Delivery Address" required></textarea><br><br>
    <button type="submit" class="btn">Place Order</button>
</form>
</body>
</html>
