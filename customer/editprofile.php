<?php 
session_start();
include '../includes/db.php'; // database connection

if (!isset($_SESSION['customer_id'])) {
    header("location: ../public/login.php");
    exit();
}
$customer_id = $_SESSION['customer_id'];
$sql = "SELECT * FROM tbl_customers WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (isset($_POST['update_profile'])) {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // Basic validation
    if (empty($firstname) || empty($lastname)  || empty($phone)) {
        $_SESSION['error'] = "First name, Last name and phone cannot be empty.";
    } else {
        $update_sql = "UPDATE tbl_customers SET firstname = ?, lastname = ?, phone = ? WHERE customer_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssi", $firstname, $lastname, $phone, $customer_id);
        if ($update_stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully.";
            header("Location: profile.php");
            exit();
            // Refresh customer data
            $stmt->execute();
            $result = $stmt->get_result();
            $customer = $result->fetch_assoc();
        } else {
            $_SESSION['error'] = "Failed to update profile. Please try again.";
        }
    }
}

if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "All password fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New password and confirm password do not match.";
    } else {
        // Verify current password
        if (password_verify($current_password, $customer['password'])) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $password_sql = "UPDATE tbl_customers SET password = ? WHERE customer_id = ?";
            $password_stmt = $conn->prepare($password_sql);
            $password_stmt->bind_param("si", $hashed_new_password, $customer_id);
            if ($password_stmt->execute()) {
                $_SESSION['success'] = "Password changed successfully.";
                header("Location: profile.php");
                exit();
            } else {
                $_SESSION['error'] = "Failed to change password. Please try again.";
            }
        } else {
            $_SESSION['error'] = "Current password is incorrect.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:#1c2942;
            padding: 20px;
        }
        h2, h3 {
            text-align: center;
        }
        .container {
            max-width: 400px;
            background: white;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        input[type=text], input[type=email], input[type=password] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>My Profile</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red; text-align: center;">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    } elseif (isset($_SESSION['success'])) {
        echo '<p style="color: green; text-align: center;">' . $_SESSION['success'] . '</p>';
        unset($_SESSION['success']);   
    }
    ?>
    <form method="POST">
        <label>Email (Read Only)</label>
        <input type="email" value="<?php echo $customer['email']; ?>" readonly>

        <label>First Name</label>
        <input type="text" name="firstname" value="<?php echo $customer['firstname']; ?>">

        <label>Last Name</label>
        <input type="text" name="lastname" value="<?php echo $customer['lastname']; ?>">

        <label>Phone Number</label>
        <input type="text" name="phone" value="<?php echo $customer['phone']; ?>">

        <button type="submit" name="update_profile">Update Profile</button>
    </form>
    <hr style="margin:20px 0;">

<h3>Change Password</h3>

<form method="POST">
    <label>Current Password</label>
    <input type="password" name="current_password" required>

    <label>New Password</label>
    <input type="password" name="new_password" required>

    <label>Confirm New Password</label>
    <input type="password" name="confirm_password" required>

    <button type="submit" name="change_password" style="background-color:#f39c12;">Change Password</button>
</form>

</div>

</body>
</html>
