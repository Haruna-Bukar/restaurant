<?php
session_start();

// ✅ Only logged-in admin can access
if (!isset($_SESSION['user_id'])) {
    header("Location: adminlogin.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$firstname = $_SESSION['firstname'] ?? '';
$lastname = $_SESSION['lastname'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Pitti Restaurant</title>
<style>
    body {
        margin: 0;
        padding: 0;
        background: #0d1117;
        font-family: Arial, sans-serif;
        color: #fff;
        display: flex;
    }

    /* ✅ Sidebar */
    .sidebar {
        width: 250px;
        background: #111827;
        height: 100vh;
        padding: 20px 0;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        border-right: 2px solid #1f2937;
    }

    .sidebar h2 {
        color: #ff9800;
        text-align: center;
    }

    .sidebar a {
        display: block;
        padding: 15px 25px;
        color: #d1d5db;
        text-decoration: none;
        font-size: 17px;
        transition: 0.3s;
    }

    .sidebar a:hover {
        background: #ff9800;
        color: #111;
        font-weight: bold;
        border-radius: 8px;
    }

    .logout {
        background: #c0392b;
        margin-top: auto;
    }
    .logout:hover {
        background: #a5281a;
    }

    /* ✅ Content */
    .content {
        margin-left: 260px;
        padding: 40px;
        width: calc(100% - 260px);
    }

    h1 {
        font-size: 32px;
        margin-bottom: 10px;
        color: #ff9800;
    }

    .card-box {
        margin-top: 30px;
        background: #1f2937;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #374151;
    }

    .card-box p {
        font-size: 18px;
        color: #cbd5e1;
    }
</style>
</head>
<body>

<!-- ✅ Sidebar -->
<div class="sidebar">
    <h2>Pitti Admin</h2>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_reservations.php">Manage Reservations</a>
    <a href="viewpage.php">Manage Menu</a>
    <a href="order.php">Manage Order</a>
    <a href="tables.php">Manage Tables</a>
    <a href="menuform.php">Update Menu</a>
    <a href="customers.php">View Customers</a>
    <a href="admin_orders.php">View Orders</a>
    <a href="logout.php" class="logout">Logout</a>
</div>

<!-- ✅ Page Content -->
<div class="content">
    <h1>Welcome Admin 👋</h1>
    <p>Manage restaurant operations easily!</p>

    <div class="card-box">
        <p>Use the sidebar to navigate through management sections.</p>
    </div>
</div>

</body>
</html>
