<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wishlist_id'])) {
    $wishlist_id = (int)$_POST['wishlist_id'];

    // Delete only if it belongs to the current user (security)
    $sql = "DELETE FROM wishlist WHERE wishlist_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $wishlist_id, $user_id);
    mysqli_stmt_execute($stmt);
}

// Always redirect back to wishlist.php (even if it becomes empty)
header("Location: wishlist.php");
exit();
?>