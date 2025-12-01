<?php
session_start();
include '../includes/db.php';

// ✅ Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: adminlogin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch info
$sql = "SELECT * FROM tbl_user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
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
        <p><?php echo $user['email']; ?></p>

        <label>First Name:</label>
        <p><?php echo $user['firstname']; ?></p>

        <label>Last Name:</label>
        <p><?php echo $user['lastname']; ?></p>

        <label>Phone:</label>
        <p><?php echo $user['phone']; ?></p>
    </div>

    <a href="editprofile.php">Edit Profile</a>
</div>

</body>
</html>
