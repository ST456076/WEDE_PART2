<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ONLY run when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['action'])) {

    $cart_id = (int)$_POST['cart_id'];
    $action = $_POST['action'];

    if ($action === 'increase') {
        mysqli_query($conn,
            "UPDATE cart SET quantity = quantity + 1 WHERE cart_id = $cart_id"
        );
    }

    if ($action === 'decrease') {
        mysqli_query($conn,
            "UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE cart_id = $cart_id"
        );
    }

    // redirect ONLY after valid update
    header("Location: cart.php");
    exit();
}

// if someone opens file directly → go back safely
header("Location: cart.php");
exit();