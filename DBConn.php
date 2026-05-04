<?php
$conn = mysqli_connect("localhost:3306", "root", "", "clothingstore");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>