<?php
session_start();
include "DBConn.php";
include "bootstrap.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$userId = (int)$_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wishlist_id'])) {
    $wishlistId = (int)$_POST['wishlist_id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM wishlist WHERE wishlist_id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $wishlistId, $userId);
    mysqli_stmt_execute($stmt);
}
$stmt = mysqli_prepare($conn, "SELECT w.wishlist_id, l.title, l.price, l.category FROM wishlist w INNER JOIN listings l ON w.listing_id = l.listing_id WHERE w.user_id = ? ORDER BY w.created_at DESC");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$items = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html><html><head><title>Wishlist</title><link rel="stylesheet" href="style.css"></head><body class="dashboard-body"><main class="simple-page"><a href="user_dashboard.php">← Dashboard</a><h2>Your Wishlist</h2><?php while($item=mysqli_fetch_assoc($items)){ ?><div class="list-row"><div><strong><?php echo htmlspecialchars($item['title']); ?></strong><p><?php echo htmlspecialchars($item['category']); ?> • R<?php echo number_format($item['price'],2); ?></p></div><form method="POST"><input type="hidden" name="wishlist_id" value="<?php echo (int)$item['wishlist_id']; ?>"><button class="danger-btn">Remove</button></form></div><?php } ?></main></body></html>
