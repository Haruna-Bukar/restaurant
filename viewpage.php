<?php include 'db.php'?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>admin view page</title>
  <!-- <link rel="stylesheet" href="menu.css"> -->
   <style>
  body { font-family: Arial, sans-serif; padding: 20px; background: #f7f7f7; }
    table { width: 100%; border-collapse: collapse; background: #fff; }
    th, td { padding: 12px; border: 1px solid #ccc; text-align: center; }
    th { background: #0f1724; color: #fff; }
    .btn {
        padding: 6px 10px; text-decoration: none; border-radius: 4px;
        border: none; cursor: pointer;
    }
    .back-btn {
    background: #ff9800; padding: 10px 20px; 
    color: #fff; text-decoration: none; border-radius: 5px;
}
   </style>
</head>

<body>

<a href="admin_dashboard.php" class="back-btn">⬅ Back</a>

    <h1 style="text-align: center;">View Page</h1>
    <table>
      <tr>
        <th>id</th>
        <th>Name</th>
        <th>Image</th>
        <th>Price</th>
        <th>Action</th>
      </tr>
    
       <?php
        $sql = "SELECT `menu_id`, `name`, `image`, `price` FROM `tbl_menu`";
        $res = $conn->query($sql);

        if($res->num_rows>0){

            while($row = $res->fetch_assoc()){
                ?>

      <tr>
        <td><?php echo $row['menu_id']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td> <img width="150" height="80" src="photos/<?php echo $row['image']; ?>" alt=""></td>
         <td>₦<?php echo$row['price']; ?></td>
         <td> <a href="view.php?id=<?php echo $row['menu_id']; ?>">View</a> | <a href="edit.php?id=<?php echo $row['menu_id']; ?>">edit</a> | <a href="delete.php?id=<?php echo $row['menu_id'] ?>">delete</a></td>
      </tr>

        <?php
                
            }
        }else{
            echo "No record found";
        }
        ?>
    </table>