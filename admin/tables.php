<?php
session_start();
include '../includes/db.php';

// ✅ Only logged-in admin
if (!isset($_SESSION['user_id'])) {
    header("Location: adminlogin.php");
    exit();
}

// ✅ Add Table
if (isset($_POST['add_table'])) {
    $table_type = trim($_POST['table_type']);
    $capacity = intval($_POST['capacity']);

    if (!empty($table_type) && !empty($capacity)) {
        $sql = "INSERT INTO tbl_tables (table_type, capacity) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $table_type, $capacity);
        $stmt->execute();
    }
    header("Location: tables.php");
    exit();
}

// ✅ Edit Table (update logic)
if (isset($_POST['update_table'])) {
    $id = intval($_POST['table_id']);
    $table_type = trim($_POST['table_type']);
    $capacity = intval($_POST['capacity']);

    $sql = "UPDATE tbl_tables SET table_type=?, capacity=? WHERE table_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $table_type, $capacity, $id);
    $stmt->execute();

    header("Location: tables.php");
    exit();
}

// ✅ Get one table for edit form
$edit_row = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $sql = "SELECT * FROM tbl_tables WHERE table_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_row = $stmt->get_result()->fetch_assoc();
}

// ✅ Delete Table
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM tbl_tables WHERE table_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: tables.php");
    exit();
}

// ✅ Fetch all tables
$result = $conn->query("SELECT * FROM tbl_tables ORDER BY table_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Tables</title>
<style>
    body { font-family: Arial; background: #f4f4f4; padding: 20px; }
    h2 { color: #0f1724; }
    .form-box {
        background: #fff; padding: 15px; width: 350px;
        border-radius: 8px; margin-bottom: 20px;
    }
    input, select {
        padding: 10px; width: 100%; border: 1px solid #ccc; border-radius: 5px;
        margin-bottom: 10px;
    }
    .btn {
        padding: 10px 18px; background: #ff9800; color: #fff;
        border: none; border-radius: 5px; cursor: pointer; font-weight: bold;
    }
    table {
        width: 100%; background: #fff; border-collapse: collapse;
    }
    th, td {
        border: 1px solid #ccc; padding: 10px; text-align: center;
    }
    th { background: #0f1724; color: #fff; }
    .edit { background: #2196f3; }
    .delete { background: #e74c3c; }
    .back-btn {
    background: #ff9800; padding: 10px 20px; 
    color: #fff; text-decoration: none; border-radius: 5px;
}
</style>
</head>
<body>
    <a href="admin_dashboard.php" class="back-btn">⬅ Back</a>

<h2>Manage Tables</h2>

<div class="form-box">
    <h3><?= $edit_row ? "Edit Table" : "Add New Table" ?></h3>

    <form method="POST">
        <?php if ($edit_row) { ?>
            <input type="hidden" name="table_id" value="<?= $edit_row['table_id']; ?>">
        <?php } ?>

        <label>Table Type</label>
        <input type="text" name="table_type" required
               value="<?= $edit_row['table_type'] ?? ''; ?>">

        <label>Capacity</label>
        <input type="number" name="capacity" required
               value="<?= $edit_row['capacity'] ?? ''; ?>">

        <button class="btn" name="<?= $edit_row ? 'update_table' : 'add_table'; ?>">
            <?= $edit_row ? 'Update Table' : 'Add Table'; ?>
        </button>
    </form>
</div>

<table>
<tr>
    <th>#</th>
    <th>Table Type</th>
    <th>Capacity</th>
    <th>Actions</th>
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['table_id']; ?></td>
    <td><?= $row['table_type']; ?></td>
    <td><?= $row['capacity']; ?></td>
    <td>
        <a href="tables.php?edit=<?= $row['table_id']; ?>" class="btn edit">Edit</a>
        <a href="tables.php?delete=<?= $row['table_id']; ?>" class="btn delete" onclick="return confirm('Delete this table?')">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>
