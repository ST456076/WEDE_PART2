<?php
session_start();
include "DBConn.php";

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Generate reference number
$orderReference = "ORD" . time();
$sessionId = session_id();

// Get all cart items
$cartQuery = mysqli_query($conn, "
SELECT cart.*, listings.price
FROM cart
JOIN listings ON cart.listing_id = listings.listing_id
WHERE cart.user_id = '$user_id'
");

if (mysqli_num_rows($cartQuery) == 0) {
    die("Your cart is empty.");
}

$totalAmount = 0;
$cartItems = [];

while ($item = mysqli_fetch_assoc($cartQuery)) {
    $item["subtotal"] = $item["price"] * $item["quantity"];
    $totalAmount += $item["subtotal"];
    $cartItems[] = $item;
}

// Create order
mysqli_query($conn, "
INSERT INTO tblorder
(user_id, total_amount, order_status)
VALUES
('$user_id', '$totalAmount', 'Completed')
");

$order_id = mysqli_insert_id($conn);

// Save each item into orderline and reduce stock
foreach ($cartItems as $item) {

    $listing_id = $item['listing_id'];
    $qty = $item['quantity'];
    $price = $item['price'];

    // Insert into orderline
    mysqli_query($conn, "
    INSERT INTO orderline
    (order_id, listing_id, quantity, price)
    VALUES
    ('$order_id', '$listing_id', '$qty', '$price')
    ");

    // Reduce stock
    mysqli_query($conn, "
    UPDATE listings
    SET quantity = quantity - $qty
    WHERE listing_id = '$listing_id'
    ");
}

// Empty cart
mysqli_query($conn, "
DELETE FROM cart
WHERE user_id = '$user_id'
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Successful</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="checkout-container">

    <div class="success-icon">✓</div>

    <h1>Order Confirmed!</h1>

    <p class="thank-you">
        Thank you for shopping with <strong>ReCloset</strong> 
        <br>
        Your order has been placed successfully.
    </p>

    <div class="order-details">

        <div class="detail">
            <span>Reference Number</span>
            <strong><?php echo $orderReference; ?></strong>
        </div>

        <div class="detail">
            <span>Session ID</span>
            <strong><?php echo session_id(); ?></strong>
        </div>

    </div>

    <div class="checkout-buttons">

        <a href="user_dashboard.php" class="checkout-btn">
            Continue Shopping
        </a>

        <a href="logout.php" class="checkout-btn secondary">
            Logout
        </a>

    </div>

</div>

</body>
</html>