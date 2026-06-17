<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Add to Cart (if coming from somewhere)
if (isset($_GET['add'])) {
    $listing_id = (int)$_GET['add'];

    $check = "SELECT * FROM cart WHERE user_id='$user_id' AND listing_id='$listing_id'";
    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {
        mysqli_query($conn, "UPDATE cart SET quantity = quantity + 1 
                            WHERE user_id='$user_id' AND listing_id='$listing_id'");
    } else {
        mysqli_query($conn, "INSERT INTO cart(user_id, listing_id, quantity) 
                            VALUES('$user_id', '$listing_id', 1)");
    }
    header("Location: cart.php");
    exit();
}

// Fetch Cart Items with Images
$sql = "SELECT cart.cart_id, cart.quantity, 
               listings.listing_id, listings.title, listings.price, 
               listings.image_url, listings.size, listings.condition_status
        FROM cart
        INNER JOIN listings ON cart.listing_id = listings.listing_id
        WHERE cart.user_id = ?
        ORDER BY cart.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$total = 0;
$item_count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart - Recloset</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="market-body">

    <!-- Announcement Bar -->
    <div class="announcement">
        Sustainable Fashion • Extend the Life of Clothing • Shop Consciously
    </div>

    <!-- Main Header -->
    <header class="market-header">
        <a class="market-logo" href="user_dashboard.php">Recloset</a>
        
        <div class="market-search">
            <input type="text" placeholder="Search for items...">
        </div>

        <div class="market-icons">
            <a href="user_dashboard.php">Browse</a>
            <a href="wishlist.php">Wishlist</a>
            <a href="cart.php" style="position: relative;">
                Cart
                <?php if($item_count > 0): ?>
                    <sup><?= $item_count ?></sup>
                <?php endif; ?>
            </a>
            <a href="my_listings.php">My Listings</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="cart-container">
        <div class="cart-header">
            <h1>Your Shopping Cart</h1>
            <p><?= $item_count ?> item(s)</p>
        </div>

        <?php if ($item_count > 0): ?>
            <div class="cart-items">
                <?php 
                $total = 0;
                while ($row = mysqli_fetch_assoc($result)): 
                    $price = floatval($row['price'] ?? 0);
                    $quantity = intval($row['quantity'] ?? 1);
                    $subtotal = $price * $quantity;
                    $total += $subtotal;
                ?>
                <div class="cart-item">
                    <!-- Product Image -->
                    <div>
                        <?php if (!empty($row['image_url'])): ?>
                            <img src="<?= htmlspecialchars($row['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($row['title']) ?>"
                                 style="width: 100%; height: 140px; object-fit: cover; border-radius: var(--radius);">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/140x140/e5e0d8/3A5944?text=No+Image" 
                                 alt="No Image"
                                 style="width: 100%; height: 140px; object-fit: cover; border-radius: var(--radius);">
                        <?php endif; ?>
                    </div>

                    <!-- Product Info -->
                    <div class="cart-item-info">
                        <h4><?= htmlspecialchars($row['title'] ?? 'Untitled Product') ?></h4>
                        <p>
                            Size: <?= htmlspecialchars($row['size'] ?? 'N/A') ?> 
                            <?= !empty($row['condition_status']) ? ' • ' . htmlspecialchars($row['condition_status']) : '' ?>
                        </p>
                    </div>

                    <!-- Price -->
                    <div style="text-align: center; font-weight: 700; font-size: 17px;">
                        R<?= number_format($price, 2) ?>
                    </div>

                    <!-- Quantity Control -->
                    <div class="quantity-control">
                        <form action="update_cart.php" method="POST" style="display: flex; align-items: center; gap: 8px;">
                            <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
                            <button type="submit" name="action" value="decrease" 
                                    style="width: 36px; height: 36px; border-radius: 50%; background: var(--sand); font-size: 18px; border: none;">−</button>
                            <span style="min-width: 30px; text-align: center; font-weight: 600; font-size: 16px;">
                                <?= $quantity ?>
                            </span>
                            <button type="submit" name="action" value="increase" 
                                    style="width: 36px; height: 36px; border-radius: 50%; background: var(--sand); font-size: 18px; border: none;">+</button>
                        </form>
                    </div>

                    <!-- Subtotal -->
                    <div style="text-align: right; font-weight: 700; font-size: 17px;">
                        R<?= number_format($subtotal, 2) ?>
                    </div>

                    <!-- Remove -->
                    <a href="remove_cart.php?id=<?= $row['cart_id'] ?>" 
                       style="color: var(--error); font-weight: 700;"
                       onclick="return confirm('Remove this item from cart?')">
                        Remove
                    </a>
                </div>
                <?php endwhile; ?>
            </div>

            <!-- Order Summary -->
          <div class="cart-summary">
                    <h2>Order Summary</h2>
                    <div class="cart-total">
                        <span>Total</span>
                        <span>R<?= number_format($total, 2) ?></span>
                    </div>

                    <a href="checkout.php" class="green-btn" style="display: block; text-align: center; padding: 16px; font-size: 18px; margin-bottom: 12px;">
                        Proceed to Checkout
                    </a>
                    
                    <a href="user_dashboard.php" class="secondary-btn" style="display: block; text-align: center;">
                        Continue Shopping
                    </a>
                </div>

        <?php else: ?>
            <div style="text-align: center; padding: 120px 20px;">
                <h2>Your cart is empty</h2>
                <p style="color: #6f6b63; margin: 20px 0 40px;">Time to fill it with beautiful pre-loved pieces 🌿</p>
                <a href="user_dashboard.php" class="green-btn">Start Shopping</a>
            </div>
        <?php endif; ?>

        <a href="user_dashboard.php" class="back-dashboard" style="margin-top: 40px; display: inline-block;">
            ← Back to Dashboard
        </a>
    </div>

</body>
</html>