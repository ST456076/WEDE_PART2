<?php
session_start();
include 'DBConn.php';

if (isset($_GET['id'])) {
    $cart_id = (int)$_GET['id'];
    mysqli_query($conn, "DELETE FROM cart WHERE cart_id = $cart_id");
}

header("Location: cart.php");
exit();
?>