<?php
session_start();
include "DBConn.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM tbluser WHERE user_id = '$id'";
mysqli_query($conn, $sql);

header("Location: customers.php");
exit();
?>