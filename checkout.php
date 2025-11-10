<?php
session_start();
include 'db.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['PHP_SELF']));
    exit();
}

$customer_id = $_SESSION['customer_id'];
$success_message = '';
$error_message = '';

// ✅ Handle continue button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_order'])) {
    if (!isset($_POST['order_type']) || empty($_POST['order_type'])) {
        $error_message = "⚠ Please select an order type.";
    } elseif (!isset($_POST['cart_items']) || empty($_POST['cart_items'])) {
        $error_message = "⚠ Your cart is empty.";
    } else {
        $_SESSION['order_type'] = $_POST['order_type'];
        $_SESSION['cart_items'] = $_POST['cart_items'];

        if ($_POST['order_type'] != "delivery") {
            header("Location: process_order.php");
            exit();
        } else {
            header("Location: delivery_form.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout - Pitti Restaurant</title>

<style>
    :root {
        --accent: #d61c1f;
        --dark: #111;
        --light: #fff;
         --bg: #0f1724;
          --card: #0b1320;
          --accent: #ff9800;
          --muted: #9aa6b2;
    }

    body {
        font-family: "Poppins", sans-serif;
        margin: 0;
        background: #0f1724;
    }

    header {
        background: var(--dark);
        padding: 15px 30px;
        color: var(--light);
    }

    .checkout-container {
        max-width: 550px;
        margin: 3rem auto;
        background: grey;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        animation: fadeIn .4s ease-in-out;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    h1, h2 {
        text-align: center;
        color: var(--accent);
        margin-bottom: 1.2rem;
    }

    .order-summary {
        background: #fff3f3;
        border-left: 6px solid var(--accent);
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 6px;
    }

    .order-total {
        font-size: 1.2rem;
        font-weight: bold;
        text-align: right;
        margin-top: 10px;
    }

    select {
        width: 100%;
        padding: .9rem;
        font-size: 1rem;
        margin: 1rem 0;
        border-radius: 6px;
        border: 1px solid #ccc;
        background: #fff;
    }

    .btn {
        width: 100%;
        padding: .9rem;
        font-size: 1.1rem;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        margin-top: .5rem;
        transition: .3s;
        background: var(--accent);
        color: white;
    }

    .btn:hover {
        background: #b2171a;
    }

    .btn-outline {
        background: transparent;
        color: var(--accent);
        border: 2px solid var(--accent);
    }

    .btn-outline:hover {
        background: var(--accent);
        color: white;
    }

    .error-message {
        background: #ffe0e0;
        border-left: 6px solid #c40000;
        padding: 10px;
        margin-bottom: 1rem;
        border-radius: 5px;
        color: #840000;
        font-weight: bold;
    }

</style>

</head>

<body>

<div class="checkout-container">
    <h1>Checkout</h1>

    <?php if ($error_message): ?>
        <div class="error-message"><?= $error_message ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="cart_items" id="cart-items-input">

        <div class="order-summary">
            <h2>Order Summary</h2>
            <div id="order-items"></div>
            <div class="order-total">Total: <span id="order-total">₦0</span></div>
        </div>

        <select name="order_type" required>
            <option value="">-- Choose Order Type --</option>
            <option value="dinein">Dine In</option>
            <option value="takeaway">Take Away</option>
            <option value="delivery">Delivery</option>
        </select>

        <button type="submit" name="submit_order" class="btn">Continue</button>
        <a href="menu.php" class="btn btn-outline">Back to Menu</a>
    </form>
</div>

<script>
function getCart() {
    return JSON.parse(localStorage.getItem('cart') || '[]');
}

function renderOrderSummary() {
    const cart = getCart();
    let total = 0;
    const container = document.getElementById('order-items');
    const input = document.getElementById('cart-items-input');
    const totalLabel = document.getElementById('order-total');

    container.innerHTML = '';
    cart.forEach(item => {
        total += item.price * item.qty;
        container.innerHTML += `
            <p><strong>${item.name}</strong> × ${item.qty} — ₦${(item.price * item.qty).toFixed(2)}</p>
        `;
    });

    totalLabel.textContent = `₦${total.toFixed(2)}`;
    input.value = JSON.stringify(cart);
}

document.addEventListener('DOMContentLoaded', renderOrderSummary);
</script>

</body>
</html>
