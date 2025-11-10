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

if(isset($_POST['create'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmPassword'];


    if($password != $confirmpassword || strlen($password) < 8){
      $message ="<div style='color:red; text-align:center;'> Password must match Confirm Password and must be 8 characters long</div>";
      

    }else{
    
    $sql = " INSERT INTO `tbl_customers`( `firstname`, `lastname`, `email`, `phone`, `password`) VALUES ('$firstname','$lastname','$email','$phone','$password')";
    $exe_query = $conn->query($sql);
    if($exe_query == false){
        echo "query failed...";
    }
    }
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