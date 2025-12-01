<?php
session_start();
include '../includes/db.php';

// ✅ Check if logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// ✅ Fetch info
$sql = "SELECT * FROM tbl_customers WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>

<div class="container">
    <h2>My Profile</h2>

    <div class="info">
        <label>Email:</label>
        <p><?php echo $customer['email']; ?></p>

        <label>First Name:</label>
        <p><?php echo $customer['firstname']; ?></p>

        <label>Last Name:</label>
        <p><?php echo $customer['lastname']; ?></p>

        <label>Phone:</label>
        <p><?php echo $customer['phone']; ?></p>
    </div>

    <a href="editprofile.php">Edit Profile</a>
</div>

</body>
</html>
