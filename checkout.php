<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT cart.cart_id,
               cart.quantity,
               listings.listing_id,
               listings.price
        FROM cart
        INNER JOIN listings
        ON cart.listing_id = listings.listing_id
        WHERE cart.user_id='$user_id'";

$result = mysqli_query($conn, $sql);

$cart_items = [];
$total = 0;

while($row = mysqli_fetch_assoc($result)){
    $total += $row['price'] * $row['quantity'];
    $cart_items[] = $row;
}

if(empty($cart_items)){
    echo "Cart empty";
    exit();
}

// create order
mysqli_query($conn,
"INSERT INTO tblorder(user_id,total_amount,order_status)
VALUES('$user_id','$total','Pending')");

$order_id = mysqli_insert_id($conn);

// order lines + stock update
foreach($cart_items as $item){
    mysqli_query($conn,
    "INSERT INTO orderLine(order_id,listing_id,quantity,price)
    VALUES('$order_id',
           '{$item['listing_id']}',
           '{$item['quantity']}',
           '{$item['price']}')");

    mysqli_query($conn,
    "UPDATE listings
     SET quantity = quantity - {$item['quantity']}
     WHERE listing_id='{$item['listing_id']}'");
}

// clear cart
mysqli_query($conn,"DELETE FROM cart WHERE user_id='$user_id'");

// save order id
$_SESSION['last_order_id'] = $order_id;

// redirect ONLY
header("Location: order_success.php");
exit();
?>

//checkout html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Complete</title>
    <link rel="stylesheet" href="code.css">
</head>

<body>

<div class="checkout-container">

    <div class="checkout-success-card">

        <div class="success-icon">
            ✓
        </div>

        <h1>Order Successful!</h1>

        <p class="success-text">
            Thank you for shopping with us.
            Your order has been placed successfully.
        </p>

        <div class="order-box">
            <span>Reference Number</span>
            <h2>#<?php echo $order_id; ?></h2>
        </div>

        <p class="small-text">
            Your payment has been received and your cart has been cleared.
            You can continue browsing more products below.
        </p>

        <div class="checkout-buttons">
            <a href="my_listings.php" class="btn">
                Continue Shopping
            </a>

            <a href="logout.php" class="btn secondary-btn">
                Return to Login
            </a>
        </div>

    </div>

</div>

</body>
</html>