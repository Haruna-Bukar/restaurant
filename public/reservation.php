<?php
session_start();
include '../includes/db.php';

// Block access if not logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['PHP_SELF']));
    exit();
}

$customer_id = $_SESSION['customer_id'];
$success = "";
$error = "";

// Fetch table options
$tableQuery = mysqli_query($conn, "SELECT * FROM tbl_tables WHERE capacity > 0");

if(isset($_POST['reserve'])){
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $table_id = $_POST['table_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = trim($_POST['message']);

    if(empty($fullname) || empty($email) || empty($phone) || empty($table_id) || empty($date) || empty($time)){
        $error = "⚠ All required fields must be filled!";
    }else{

        // Check again capacity
        $check = mysqli_query($conn, "SELECT capacity FROM tbl_tables WHERE table_id='$table_id'");
        $row = mysqli_fetch_assoc($check);

        if($row['capacity'] > 0){

            // Insert with STATUS AS PENDING
            $insert = mysqli_query($conn, "INSERT INTO tbl_reservations 
                (customer_id, fullname, email, phone, table_id, date, time, message, status)
                VALUES 
                ('$customer_id','$fullname','$email','$phone','$table_id','$date','$time','$message','Pending')");

            if($insert){
                // Reduce capacity 🔽
                mysqli_query($conn, "UPDATE tbl_tables SET capacity = capacity - 1 WHERE table_id='$table_id'");
                
                $reserve_id = mysqli_insert_id($conn);
                $success = "✅ Reservation submitted! Pending admin approval.<br>
                            <a href='customer/view_reservations.php?id=$reserve_id' style='color:green;font-weight:bold;text-decoration:none'>
                                View Reservation
                            </a>";
            } else {
                $error = "⚠ Something went wrong. Try again!";
            }

        } else {
            $error = "⚠ This table type is fully booked!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reserve a Table</title>
<style>
:root{
  --accent:#ff9800;
  --bg: #0f1724;
  --nav:#0f1724;
}
body{margin:0;font-family:Arial;background:var(--bg);color:#0f1724}
.reservation-container{
  max-width:600px;margin:40px auto;background:#fff;padding:24px 32px;
  border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);
}
h1{text-align:center;margin-bottom:18px;color:var(--nav)}
label{font-weight:600;margin-bottom:6px;display:block}
input, select, textarea{
  width:100%;padding:12px;border-radius:8px;border:1px solid #ccc;
  margin-bottom:14px;font-size:1rem;
}
textarea{resize:none;height:100px}
button{
  background:var(--accent);color:#fff;border:none;padding:12px;
  width:100%;border-radius:8px;font-size:1rem;font-weight:bold;
  cursor:pointer;transition:.25s;
}
button:hover{opacity:.9}
.message{
  padding:12px;border-radius:8px;margin-bottom:18px;text-align:center
}
.success{background:#e6f9e6;color:#2d7a2d}
.error{background:#ffecec;color:#b30000}
</style>
</head>
<body>

<div class="reservation-container">
    <h1>Reserve a Table</h1>

    <?php if($success){ echo "<div class='message success'>$success</div>"; } ?>
    <?php if($error){ echo "<div class='message error'>$error</div>"; } ?>

    <form method="POST">

        <label>Full Name *</label>
        <input type="text" name="fullname" required>

        <label>Email *</label>
        <input type="email" name="email" required>

        <label>Phone Number *</label>
        <input type="text" name="phone" required>

        <label>Select Table *</label>
        <select name="table_id" required>
            <option value="">-- Choose Table --</option>
            <?php while($t = mysqli_fetch_assoc($tableQuery)) { ?>
                <option value="<?= $t['table_id'] ?>">
                    Table for <?= $t['table_type'] ?> (Remaining: <?= $t['capacity'] ?>)
                </option>
            <?php } ?>
        </select>

        <label>Date *</label>
        <input type="date" name="date" required>

        <label>Time *</label>
        <input type="time" name="time" required>

        <label>Special Request (Optional)</label>
        <textarea name="message" placeholder="Any special request?"></textarea>

        <button type="submit" name="reserve">Submit</button>
    </form>
</div>

</body>
</html>
