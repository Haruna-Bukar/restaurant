<?php 
include 'db.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM tbl_menu WHERE menu_id = $id";

    $delete_user = $conn->query($sql);
    if ($delete_user) {
        header("Location: ../admin/viewpage.php?message=deleted");
    } else {
        header("Location: ../admin/viewpage.php?message=failed");
    }
}  