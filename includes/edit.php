<?php 
include '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    header("location: view.php");
    exit;
}

$message = ""; // 🔹 Message will be stored here

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $special = isset($_POST['special']) ? 1 : 0; // 🔹 New special field
    $photoname = $_FILES['image']['name'];
    $tmpname = $_FILES['image']['tmp_name'];

    // 🔹 Update query
    $sql = "UPDATE tbl_menu 
            SET name='$name', description='$description', image='$photoname', cag_id='$category', price='$price', special='$special'
            WHERE menu_id=$id";

    $exe_query = $conn->query($sql);

    if ($exe_query) {
        if (!empty($photoname)) {
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
            $message = "<div class='message success'>✅ Menu updated successfully!</div>";
        }
    } else {
        $message = "<div class='message error'>❌ Database update failed.</div>";
    }
}

// 🔹 Fetch menu item for form
$sql = "SELECT `menu_id`, `name`, `image`, `description`, `cag_id`, `price`, `special` FROM `tbl_menu` WHERE menu_id=$id";
$res = $conn->query($sql);
$row = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .message {
            text-align: center;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            width: 80%;
            margin: 10px auto;
        }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Update Menu</h1>
    </div>
    <div class="form-container">

        <?php echo $message; ?>

        <?php if ($row): ?>
        <form id="menuForm" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" placeholder="Name of food">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($row['description']); ?>" placeholder="Description">
                </div>
            </div>

            <div class="form-group">
                <label for="special">Today’s Special</label>
                <input type="checkbox" name="special" value="1" <?php echo ($row['special'] == 1) ? "checked" : ""; ?>>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" name="category">
                    <option>--Choose--</option>
                    <?php 
                    $sql2 = "SELECT * FROM tbl_category;";
                    $result2 = $conn->query($sql2);
                    if ($result2->num_rows > 0) {
                        while ($cat = $result2->fetch_assoc()) {
                            $selected = ($cat['cag_id'] == $row['cag_id']) ? "selected" : "";
                            echo "<option value='".$cat['cag_id']."' $selected>".$cat['name']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                <br>
                <img src="photos/<?php echo htmlspecialchars($row['image']); ?>" alt="" width="100">
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" class="form-control" value="<?php echo htmlspecialchars($row['price']); ?>">
            </div>

            <input type="submit" class="btn" name="update" value="Update">
        </form>
        <?php else: ?>
            <p class="message error">Menu item not found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
