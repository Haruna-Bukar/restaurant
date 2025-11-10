<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registration page</title>
        <link rel="stylesheet" href="style.css">

</head>
<body>

<?php


include 'db.php';
$message = "";

if (isset($_POST['create'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']);
    $password = trim($_POST['password']);
    $confirmpassword = trim($_POST['confirmPassword']);

    // ✅ Basic validation
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($role) || empty($password) || empty($confirmpassword)) {
        $message = "<div style='color:red; text-align:center;'>All fields are required!</div>";
    } elseif ($password != $confirmpassword) {
        $message = "<div style='color:red; text-align:center;'>Passwords do not match!</div>";
    } elseif (strlen($password) < 8) {
        $message = "<div style='color:red; text-align:center;'>Password must be at least 8 characters!</div>";
    } else {
        // ✅ Check if email already exists
        $check_email = $conn->prepare("SELECT email FROM tbl_user WHERE email=?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();

        if ($check_email->num_rows > 0) {
            $message = "<div style='color:red; text-align:center;'>Email already registered!</div>";
        } else {
            // ✅ Hash password before saving
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // ✅ Insert securely
            $insert = $conn->prepare("INSERT INTO tbl_user (firstname, lastname, email, phone, role, password) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->bind_param("ssssss", $firstname, $lastname, $email, $phone, $role, $hashedPassword);

            if ($insert->execute()) {
                $message = "<div style='color:green; text-align:center;'>Account created successfully!</div>";
            } else {
                $message = "<div style='color:red; text-align:center;'>Registration failed!</div>";
            }

            $insert->close();
        }

        $check_email->close();
    }
}
?>


?>

<div class="container">
        <div class="header">
            <h1>Create Account</h1>
        </div>
        <?php 
        if(isset($message)){
            echo $message;
        }
        ?>
        
        <div class="form-container">
            <form id="registrationForm" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" name ="firstname" id="firstName" class="form-control" placeholder="Enter your first name">
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" name ="lastname" id="lastName" class="form-control" placeholder="Enter your last name">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name ="email" id="email" class="form-control" placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name ="phone" id="phone" class="form-control" placeholder="Enter your phone number">
                </div>

                <div class="form-group">
                    <label for="role">role</label>
                    <input type="role" name ="role" id="password" class="form-control" placeholder="What is your role">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name ="password" id="password" class="form-control" placeholder="Create a password">
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="confirmPassword"  id="confirmPassword" class="form-control" placeholder="Confirm your password">
                </div>
                
                
                <input type="submit" class="btn" name = 'create' value = "Create Account">
                
                <div class="login-link">
                    Already have an account? <a href="adminlogin.php">Login In</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>