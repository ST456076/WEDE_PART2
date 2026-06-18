<?php
$conn = mysqli_connect("localhost", "root", "", "clothingstore");

if (!$conn) {
    die("DB Connection failed: " . mysqli_connect_error());
}
?>