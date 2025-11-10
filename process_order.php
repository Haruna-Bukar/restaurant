<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id']) || !isset($_SESSION['cart_items'])) {
    die("Invalid order session.");
}

$customer_id = $_SESSION['customer_id'];
$order_type = $_SESSION['order_type'];
$cart_items = json_decode($_SESSION['cart_items'], true);

// Calculate Total
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['qty'];
}

// Ticket Code
$ticket_code = strtoupper(substr(md5(uniqid()), 0, 6));

// INSERT ORDER
$query = "INSERT INTO tbl_order (customer_id, ticket_code, order_type, status, total_price, order_date)
          VALUES (?, ?, ?, 'pending', ?, NOW())";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "issi", $customer_id, $ticket_code, $order_type, $total_price);
mysqli_stmt_execute($stmt);

$order_id = mysqli_insert_id($conn);

// ORDER ITEMS INSERT
$item_query = "INSERT INTO order_items (order_id, menu_id, quantity) VALUES (?, ?, ?)";
$item_stmt = mysqli_prepare($conn, $item_query);

foreach ($cart_items as $i) {
    mysqli_stmt_bind_param($item_stmt, "iii", $order_id, $i['id'], $i['qty']);
    mysqli_stmt_execute($item_stmt);
}

// DELIVERY INSERT ✅ ONLY if delivery
if ($order_type === "delivery") {
    $name = $_SESSION['delivery_name'];
    $phone = $_SESSION['delivery_phone'];
    $address = $_SESSION['delivery_address'];

    mysqli_query($conn,
    "INSERT INTO tbl_delivery (order_id, fullname, phone, address)
     VALUES ('$order_id', '$name', '$phone', '$address')");
}

// ✅ Clear cart + session
unset($_SESSION['cart_items']);
unset($_SESSION['order_type']);

echo "
<script>
    localStorage.removeItem('cart');
    alert('✅ Order Placed! Ticket: $ticket_code');
    window.location.href = 'my_orders.php';
</script>
";
