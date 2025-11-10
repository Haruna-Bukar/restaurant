<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .back-btn {
            background: #ff9800; padding: 10px 20px; 
            color: #fff; text-decoration: none; border-radius: 5px;
        }
    </style>    
</head>
<body>

<a href="admin_dashboard.php" class="back-btn">⬅ Back</a>

<?php
include 'db.php';

$message = ""; // 🔹 will hold success or error message

if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
     $special = $_POST['special'];
    $photoname = $_FILES['image']['name'];
    $tmpname = $_FILES['image']['tmp_name'];

    // Insert data into database
    $sql = "INSERT INTO `tbl_menu`(`cag_id`, `image`, `name`, `description`, `price`, `special`) 
            VALUES ($category,'$photoname','$name','$description','$price', '$special')";
    $exe_query = $conn->query($sql);

    if ($exe_query) {
        // Check file type using MIME
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = mime_content_type($tmpname);

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($tmpname, "photos/" . $photoname)) {
                $message = "<div class='message success'>✅ Photo Uploaded Successfully!</div>";
            } else {
                $message = "<div class='message error'>⚠️ Error uploading photo.</div>";
            }
        } else {
            $message = "<div class='message error'>❌ Invalid file type. Only JPG, JPEG, and PNG are allowed.</div>";
        }
    } else {
        $message = "<div class='message error'>❌ Database insertion failed.</div>";
    }
}
?>

<div class="container">
    <div class="header">
        <h1>Menu</h1>
    </div>
    <div class="form-container">

        <!-- ✅ Message shows here -->
        <?php echo $message; ?>

        <form id="menuForm" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Name of food" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" id="description" class="form-control" placeholder="Description" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" name="category" required>
                    <option value="">--Choose--</option>
                    <?php 
                    $sql = "SELECT * FROM tbl_category;";
                    $result = mysqli_query($conn, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='".$row['cag_id']."'>".$row['name']."</option>";
                        }
                    }                        
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
            </div>

             <div class="form-group">
                <label for="image">special</label>
                <input type="text" name="special" id="special" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control" placeholder="Enter price" required>
            </div>

            <input type="submit" class="btn" name="create" value="Submit">
        </form>
    </div>
</div>

</body>
</html>
