<?php
include 'DBConn.php';

$cart_id = $_POST['cart_id'];
$quantity = $_POST['quantity'];

$sql = "UPDATE cart SET quantity='$quantity'
        WHERE cart_id='$cart_id'";

mysqli_query($conn, $sql);

header("Location: cart.php");
?>
