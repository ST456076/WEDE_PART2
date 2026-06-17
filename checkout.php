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
               listings.title,
               listings.price,
               listings.quantity AS stock_quantity
        FROM cart
        INNER JOIN listings
        ON cart.listing_id = listings.listing_id
        WHERE cart.user_id='$user_id'";

$result = mysqli_query($conn, $sql);

$total = 0;
$cart_items = [];

while($row = mysqli_fetch_assoc($result))
{
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;

    $cart_items[] = $row;
}

if(empty($cart_items))
{
    die("Your cart is empty.");
}

$insert_order = "INSERT INTO tblorder(user_id, total_amount, order_status)
                 VALUES('$user_id', '$total', 'Pending')";

mysqli_query($conn, $insert_order);

$order_id = mysqli_insert_id($conn);

foreach($cart_items as $item)
{
    $listing_id = $item['listing_id'];
    $quantity = $item['quantity'];
    $price = $item['price'];

    // Insert into orderLine
    $insert_line = "INSERT INTO orderLine(order_id, listing_id, quantity, price)
                    VALUES('$order_id', '$listing_id', '$quantity', '$price')";

    mysqli_query($conn, $insert_line);

    // Decrease stock quantity
    $update_stock = "UPDATE listings
                     SET quantity = quantity - $quantity
                     WHERE listing_id = '$listing_id'";

    mysqli_query($conn, $update_stock);
}

// Empty cart after checkout
mysqli_query($conn,
"DELETE FROM cart WHERE user_id='$user_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Complete</title>

    <link rel="stylesheet" href="style.css">

    <style>

        body{
            font-family: Arial;
            background:#f5f5f5;
            margin:0;
            padding:0;
        }

        .checkout-container{
            width:500px;
            margin:80px auto;
            background:white;
            padding:40px;
            border-radius:10px;
            text-align:center;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        .success{
            color:green;
            font-size:28px;
            margin-bottom:20px;
        }

        .order-ref{
            background:#f2f2f2;
            padding:15px;
            border-radius:8px;
            margin:20px 0;
            font-size:18px;
        }

        .btn{
            display:inline-block;
            margin:10px;
            padding:12px 20px;
            text-decoration:none;
            background:black;
            color:white;
            border-radius:5px;
        }

    </style>
</head>

<body>

<div class="checkout-container">

    <div class="success">
        Order Successful
    </div>

    <p>
        Thank you for your purchase.
    </p>

    <div class="order-ref">
        Reference Number:
        <strong>#<?php echo $order_id; ?></strong>
    </div>

    <p>
        Your order has been placed successfully and your cart has been cleared.
    </p>

    <a href="my_listings.php" class="btn">
        Continue Shopping
    </a>

    <a href="logout.php" class="btn">
        Return to Login
    </a>

</div>

</body>
</html>