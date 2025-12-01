<?php
session_start();
include '../includes/db.php';// database connection

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use POST request check (more reliable than relying on the submit button name which
    // may not be present when the form is submitted via Enter key).
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please enter both email and password.";
        $redir = '';
        if (isset($_GET['redirect'])) { $redir = '?redirect=' . urlencode($_GET['redirect']); }
        header("Location: login.php" . $redir);
        exit();
    }

    // Check user by email and password
    $stmt = ($conn->prepare("SELECT * FROM tbl_customers WHERE email = ? LIMIT 1"));
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
             session_regenerate_id(true); 
             // Prevent session fixation attacks
            $_SESSION['customer_id'] = $user['customer_id'];
            $_SESSION['firstname']  = $user['firstname'];
            $_SESSION['lastname']  = $user['lastname'];
            $_SESSION['email']     = $user['email'];

            // Redirect to intended page after login
            $redirect = $_GET['redirect'] ?? '';
            if ($redirect &&strpos($redirect, 'http') === false) {
                header("Location: " . $redirect);
            } else {
                header("Location: ../customer/customer_dashboard.php");
            }
            exit();
        } else {
        // wrong password
        $_SESSION['error'] = "Email or Password is Incorrect";
        $redir = isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '';
        header("Location: login.php" . $redir);
        exit();
    }
    } else {
        $_SESSION['error'] = "Email or Password is Incorrect";
        // preserve redirect when sending back to login form
        $redir = '';
        if (isset($_GET['redirect'])) { $redir = '?redirect=' . urlencode($_GET['redirect']); }
        header("Location: login.php" . $redir);
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
        <link rel="stylesheet" href="../assets/css/login.css">

        <script>
          setTimeout(() => {
            const message = document.querySelector("p[style*='color: green']");
            if (message) message.style.display = "none";
        }, 4000); // hide after 4 seconds
        </script>


</head>
<body>
<div class="container">
        <div class="header">
            <h1>Log-In</h1>
            
        </div>

        <?php
        
        if (isset($_SESSION['error'])) {
           echo '<p style="text-align: center; color: red; margin: 20px;">' .$_SESSION['error'] . '</p>';
        }
        ?>
        <?php
            if (isset($_GET['logout'])) {
                echo "<p style='text-align:center; color:green;'>You’ve been logged out successfully.</p>";
            }
            if (isset($_GET['timeout'])) {
                echo "<p style='text-align:center; color:orange;'>Session expired due to inactivity. Please log in again.</p>";
            }
        ?>

       
        
        <div class="form-container">
            <form id="loginForm" method="POST">
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name ="email" id="email" class="form-control" placeholder="Enter your email">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name ="password" id="password" class="form-control" placeholder="Create a password">
                </div>
                
                <button type="submit" class="btn" name="login">Login</button>
                
                <div class="signup-link">
                    Don't have an account? <a href="registration.php">Sign Up</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>