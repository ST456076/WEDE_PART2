<?php
session_start();
include 'DBConn.php';

$user_id = $_SESSION['user_id'];
$listing_id = $_GET['id'];

$check = "SELECT * FROM cart WHERE user_id='$user_id' AND listing_id='$listing_id'";
$result = mysqli_query($conn, $check);

if(mysqli_num_rows($result) > 0)
{
    $update = "UPDATE cart SET quantity = quantity + 1
               WHERE user_id='$user_id'
               AND listing_id='$listing_id'";

    mysqli_query($conn, $update);
}
else
{
    $insert = "INSERT INTO cart(user_id, listing_id, quantity)
               VALUES('$user_id','$listing_id',1)";

    mysqli_query($conn, $insert);
}

header("Location: cart.php");
?>
