<?php
session_start();
include '../includes/db.php';

// ✅ Check DB connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ✅ Check session
if (!isset($_SESSION['customer_id']) || !isset($_SESSION['cart_items'])) {
    die("Invalid order session.");
}

$customer_id = $_SESSION['customer_id'];
$order_type = $_SESSION['order_type'];
$cart_items = json_decode($_SESSION['cart_items'], true);
$delivery_fee = $_SESSION['delivery_fee'] ?? 0;

// ✅ Calculate subtotal
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['qty'];
}

// ✅ Total including delivery fee
$total_price = $subtotal + $delivery_fee;

// ✅ Generate ticket code
$ticket_code = strtoupper(substr(md5(uniqid()), 0, 6));

// ✅ Insert order into tbl_order
$query = "INSERT INTO tbl_order (customer_id, ticket_code, order_type, status, total_price, order_date)
          VALUES (?, ?, ?, 'pending', ?, NOW())";

$stmt = mysqli_prepare($conn, $query);
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "issd", $customer_id, $ticket_code, $order_type, $total_price);

if (!mysqli_stmt_execute($stmt)) {
    die("Execute failed: " . mysqli_stmt_error($stmt));
}

$order_id = mysqli_insert_id($conn);

// ✅ Insert order items
$item_query = "INSERT INTO order_items (order_id, menu_id, quantity) VALUES (?, ?, ?)";
$item_stmt = mysqli_prepare($conn, $item_query);
if (!$item_stmt) {
    die("Prepare failed (order items): " . mysqli_error($conn));
}

foreach ($cart_items as $i) {
    mysqli_stmt_bind_param($item_stmt, "iii", $order_id, $i['id'], $i['qty']);
    if (!mysqli_stmt_execute($item_stmt)) {
        die("Execute failed (order items): " . mysqli_stmt_error($item_stmt));
    }
}

// ✅ Insert delivery info if delivery
if ($order_type === "delivery") {
    $name = $_SESSION['delivery_name'];
    $phone = $_SESSION['delivery_phone'];
    $address = $_SESSION['delivery_address'];

    // Make sure tbl_delivery has a delivery_fee column
    $delivery_query = "INSERT INTO tbl_delivery (order_id, fullname, phone, address, delivery_fee)
                       VALUES (?, ?, ?, ?, ?)";
    $delivery_stmt = mysqli_prepare($conn, $delivery_query);
    if (!$delivery_stmt) {
        die("Prepare failed (delivery): " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($delivery_stmt, "isssi", $order_id, $name, $phone, $address, $delivery_fee);
    if (!mysqli_stmt_execute($delivery_stmt)) {
        die("Execute failed (delivery): " . mysqli_stmt_error($delivery_stmt));
    }
}

// ✅ Clear session + cart
unset($_SESSION['cart_items']);
unset($_SESSION['order_type']);
unset($_SESSION['delivery_name']);
unset($_SESSION['delivery_phone']);
unset($_SESSION['delivery_address']);
unset($_SESSION['delivery_fee']);

echo "
<script>
    localStorage.removeItem('cart');
    alert('✅ Order Placed! Ticket: $ticket_code');
    window.location.href = 'my_orders.php';
</script>";
?>
