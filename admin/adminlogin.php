<?php
session_start();
include '../includes/db.php';// ✅ adjust depending on your folder structure

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // ✅ Check admin table
        $sql = "SELECT user_id, firstname, lastname, email, password FROM tbl_user WHERE email=? LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin['password'])) {
                // ✅ Login successful
                $_SESSION['user_id'] = $admin['user_id'];
              $_SESSION['user_name'] = $admin['firstname'] . " " . $admin['lastname'];


                header("Location: admin_dashboard.php");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "No admin found with this email!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>
<link rel="stylesheet" href="../assets/css/login.css">
<style>
.error {
    background: #ffe5e5;
    color: #b30000;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 12px;
    text-align: center;
}
</style>

<script>
setTimeout(() => {
    const msg = document.querySelector("p[style*='color: green']");
    if (msg) msg.style.display = "none";
}, 4000); // hide after 4 seconds
</script>


</head>
<body>

<div class="container">
    <div class="header">
        <h1>Admin Login</h1>
    </div>

    <div class="form-container">
        <?php if ($error) echo "<div class='error'>$error</div>"; ?>

         <?php
            if (isset($_GET['logout'])) {
                echo "<p style='text-align:center; color:green;'>You’ve been logged out successfully.</p>";
            }
            if (isset($_GET['timeout'])) {
                echo "<p style='text-align:center; color:orange;'>Session expired due to inactivity. Please log in again.</p>";
            }
        ?>


        <form method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class ="form-control"placeholder="Enter email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn" name="login">Login</button>

            <div class="signup-link">
                Don't have an account? <a href="adminregistration.php">Sign Up</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
