<?php
session_start();

if(!isset($_SESSION['last_order_id'])){
    header("Location: user_dashboard.php");
    exit();
}

$order_id = $_SESSION['last_order_id'];
unset($_SESSION['last_order_id']);
?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Successful</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="market-body">

<div class="checkout-container">

    <div class="checkout-success-card">

        <div class="success-icon">✓</div>

        <h1>Order Successful!</h1>

        <p class="success-text">
            Thank you for your purchase. Your order has been placed successfully.
        </p>

        <div class="order-box">
            <span>Reference Number</span>
            <h2>#<?php echo $order_id; ?></h2>
        </div>

        <a href="user_dashboard.php" class="green-btn">
            Continue Shopping
        </a>

    </div>

</div>

</body>
</html>