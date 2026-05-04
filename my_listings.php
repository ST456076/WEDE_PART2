<?php
session_start();
include "DBConn.php";
include "bootstrap.php";
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$userId = (int)$_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['listing_id'])) {
    $listingId = (int)$_POST['listing_id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM listings WHERE listing_id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $listingId, $userId);
    mysqli_stmt_execute($stmt);
}
$stmt = mysqli_prepare($conn, "SELECT * FROM listings WHERE user_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$items = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html><html><head><title>My Listings</title><link rel="stylesheet" href="style.css"></head><body class="dashboard-body"><main class="simple-page"><a href="user_dashboard.php">← Dashboard</a><h2>My Listings</h2><a class="primary-btn inline-btn" href="add_listing.php">+ Add listing</a><?php while($item=mysqli_fetch_assoc($items)){ ?><div class="list-row"><div><strong><?php echo htmlspecialchars($item['title']); ?></strong><p><?php echo htmlspecialchars($item['category']); ?> • R<?php echo number_format($item['price'],2); ?></p></div><form method="POST"><input type="hidden" name="listing_id" value="<?php echo (int)$item['listing_id']; ?>"><button class="danger-btn">Delete</button></form></div><?php } ?></main></body></html>
