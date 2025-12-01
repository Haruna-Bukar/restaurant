<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registration page</title>
        <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<?php
include '../includes/db.php';

if(isset($_POST['create'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $confirmpassword = $_POST['confirmPassword'];


    // check if fields are empty

    if(empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($password) || empty($confirmpassword)){
        $message ="<div style='color:red; text-align:center;'> All fields are required</div>";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message ="<div style='color:red; text-align:center;'> Invalid email format</div>";
    }
    elseif($password !== $confirmpassword || strlen($password) < 8){
        $message ="<div style='color:red; text-align:center;'> Passwords do not match</div>";
      

    }elseif((strlen( $phone) > 15)){
        $message ="<div style='color:red; text-align:center;'> Phone number too long</div>";
    }
    else{

        // Check if email already exists
        $stmt_email = $conn->prepare("SELECT email FROM tbl_customers WHERE email = ?");
    $stmt_email->bind_param("s", $email);
    $stmt_email->execute();
    $stmt_email->store_result();
    if($stmt_email->num_rows > 0){
        $message ="<div style='color:red; text-align:center;'> Email already exists. Please use a different email.</div>";
       } else {    
    $stmt = $conn->prepare("INSERT into tbl_customers (firstname, lastname, email, phone, password) values (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstname, $lastname, $email, $phone, $hashed_password);
    $exe_query = $stmt->execute();
    }
}
}

if(isset($exe_query)){
    if($exe_query){
        $message ="<div style='color:green; text-align:center;'> Account created successfully.</div>";}
     }else{
        $message ="<div style='color:red; text-align:center;'> Account creation failed. Please try again.</div>";
    }
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
                    <label for="password">Password</label>
                    <input type="password" name ="password" id="password" class="form-control" placeholder="Create a password">
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="confirmPassword"  id="confirmPassword" class="form-control" placeholder="Confirm your password">
                </div>
                
                
                <input type="submit" class="btn" name = 'create' value = "Create Account">
                
                <div class="login-link">
                    Already have an account? <a href="login.php">Login In</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>