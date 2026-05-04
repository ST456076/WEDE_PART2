<?php
session_start();
include "DBConn.php";
include "bootstrap.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$userId = (int)$_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'])) {
    $cartId = (int)$_POST['cart_id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $cartId, $userId);
    mysqli_stmt_execute($stmt);
}
$stmt = mysqli_prepare($conn, "SELECT c.cart_id, c.quantity, l.title, l.price FROM cart c INNER JOIN listings l ON c.listing_id = l.listing_id WHERE c.user_id = ? ORDER BY c.created_at DESC");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$items = mysqli_stmt_get_result($stmt);
$total = 0;
?>
<!DOCTYPE html><html><head><title>Cart</title><link rel="stylesheet" href="style.css"></head><body class="dashboard-body"><main class="simple-page"><a href="user_dashboard.php">← Dashboard</a><h2>Your Cart</h2><?php while($item=mysqli_fetch_assoc($items)){ $total += $item['price']*$item['quantity']; ?><div class="list-row"><div><strong><?php echo htmlspecialchars($item['title']); ?></strong><p>Qty <?php echo (int)$item['quantity']; ?> • R<?php echo number_format($item['price'],2); ?></p></div><form method="POST"><input type="hidden" name="cart_id" value="<?php echo (int)$item['cart_id']; ?>"><button class="danger-btn">Remove</button></form></div><?php } ?><h3>Total: R<?php echo number_format($total,2); ?></h3></main></body></html>
