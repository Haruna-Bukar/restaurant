<?php
session_start();
include 'db.php'; // database connection

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use POST request check (more reliable than relying on the submit button name which
    // may not be present when the form is submitted via Enter key).
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
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
    $sql = "SELECT * FROM tbl_customers WHERE email='$email' AND password ='$password' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Store login session
        // NOTE: adjust the key here if your customers table uses a different PK name (e.g. customer_id)
        $_SESSION['customer_id']   = isset($user['id']) ? $user['id'] : ($user['customer_id'] ?? null);
        $_SESSION['firstname']  = $user['firstname'] ?? '';
        $_SESSION['lastname']  = $user['lastname'] ?? '';
        $_SESSION['email']     = $user['email'] ?? '';

        // Respect optional redirect parameter (only allow relative paths)
        $redirect = '';
        if (isset($_GET['redirect'])) {
            $redirect = trim($_GET['redirect']);
        }
        if ($redirect && strpos($redirect, 'http') === false) {
            header("Location: " . $redirect);
            exit();
        }

        header("Location: customer_dashboard.php");
        exit();
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
        <link rel="stylesheet" href="login.css">

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