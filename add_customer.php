<?php
session_start();
include "DBConn.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['add'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];

    $sql = "INSERT INTO tbluser (full_name, email) VALUES ('$full_name', '$email')";
    mysqli_query($conn, $sql);

    header("Location: customers.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Customer</title>
</head>
<body>

<h2>Add New Customer</h2>

<form method="POST">
    <input type="text" name="full_name" placeholder="Full Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>

    <button type="submit" name="add">Add Customer</button>
</form>

<br>
<a href="customers.php">Back</a>

</body>
</html>