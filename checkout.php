<?php
session_start();
include 'DBConn.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT cart.quantity,
               listings.price
        FROM cart
        INNER JOIN listings
        ON cart.listing_id = listings.listing_id
        WHERE cart.user_id='$user_id'";

$result = mysqli_query($conn, $sql);

$total = 0;

while($row = mysqli_fetch_assoc($result))
{
    $total += ($row['price'] * $row['quantity']);
}

$insert_order = "INSERT INTO orders(user_id, total_amount)
                 VALUES('$user_id','$total')";

mysqli_query($conn, $insert_order);

mysqli_query($conn,
"DELETE FROM cart WHERE user_id='$user_id'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>

<h1>Order Successful</h1>

<p>Your order has been placed successfully.</p>

<a href="listings.php">Continue Shopping</a>

</body>
</html>
