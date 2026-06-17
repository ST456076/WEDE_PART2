<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch wishlist items
$sql = "SELECT w.wishlist_id, l.listing_id, l.title, l.price, l.image_url, l.size, l.condition_status 
        FROM wishlist w 
        JOIN listings l ON w.listing_id = l.listing_id 
        WHERE w.user_id = ? 
        ORDER BY w.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - Recloset</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Page-specific styles for Wishlist */
        .wishlist-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 52px;
        }

        .wishlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--sand);
            padding-bottom: 20px;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .wishlist-card {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .wishlist-card:hover {
            transform: translateY(-8px);
        }

        .wishlist-card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
        }

        .wishlist-info {
            padding: 18px;
        }

        .wishlist-info h3 {
            margin: 0 0 8px;
            font-size: 18px;
        }

        .price {
            font-size: 20px;
            font-weight: 800;
            color: var(--green-dk);
            margin: 8px 0;
        }

        .remove-btn {
            background: var(--error);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: var(--radius);
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            margin-top: 12px;
        }

        .remove-btn:hover {
            background: #a13d32;
        }

        .empty-state {
            text-align: center;
            padding: 100px 20px;
            color: #6f6b63;
        }
    </style>
</head>
<body class="market-body">

    <!-- Announcement -->
    <div class="announcement">
        Sustainable Fashion • Extend the Life of Clothing • Shop Consciously
    </div>

    <!-- Header -->
    <header class="market-header">
        <a class="market-logo" href="user_dashboard.php">Recloset</a>
        
        <div class="market-search">
            <input type="text" placeholder="Search wishlist..." id="searchInput">
        </div>

        <div class="market-icons">
            <a href="user_dashboard.php">Browse</a>
            <a href="cart.php">Cart</a>
            <a href="my_listings.php">My Listings</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="wishlist-container">
        <div class="wishlist-header">
            <h1>My Wishlist</h1>
            <p><?= mysqli_num_rows($result) ?> items saved</p>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="wishlist-grid">
                <?php while ($item = mysqli_fetch_assoc($result)): ?>
                    <div class="wishlist-card">
                        <?php if (!empty($item['image_url'])): ?>
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x260/e5e0d8/3A5944?text=No+Image" alt="No Image">
                        <?php endif; ?>

                        <div class="wishlist-info">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p>Size: <?= htmlspecialchars($item['size'] ?? 'N/A') ?> • <?= htmlspecialchars($item['condition_status'] ?? 'Good') ?></p>
                            <div class="price">R<?= number_format($item['price'], 2) ?></div>
                            
                            <form action="add_to_cart.php" method="GET" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $item['listing_id'] ?>">
                                <button type="submit" class="green-btn">Add to Cart</button>
                            </form>

                            <form action="remove_wishlist.php" method="POST" style="display:inline;" onsubmit="return confirm('Remove from wishlist?');">
                                <input type="hidden" name="wishlist_id" value="<?= $item['wishlist_id'] ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h2>Your wishlist is empty</h2>
                <p>Start saving items you love </p>
                <a href="user_dashboard.php" class="green-btn">Browse Items</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>