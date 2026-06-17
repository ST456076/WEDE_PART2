<?php
session_start();
include "DBConn.php";
include "bootstrap.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int)$_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? 'Customer';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $listingId = isset($_POST['listing_id']) ? (int)$_POST['listing_id'] : 0;

    if ($action === 'add_cart' && $listingId > 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO cart (user_id, listing_id, quantity) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1");
        mysqli_stmt_bind_param($stmt, "ii", $userId, $listingId);
        mysqli_stmt_execute($stmt);
        $message = "Item added to cart.";
    }

    if ($action === 'add_wishlist' && $listingId > 0) {
        $stmt = mysqli_prepare($conn, "INSERT IGNORE INTO wishlist (user_id, listing_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, "ii", $userId, $listingId);
        mysqli_stmt_execute($stmt);
        $message = "Item saved to wishlist.";
    }
}

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

$sql = "SELECT l.*, u.full_name AS seller_name FROM listings l LEFT JOIN tbluser u ON l.user_id = u.user_id WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND (l.title LIKE ? OR l.description LIKE ? OR l.category LIKE ? OR l.brand LIKE ?)";
    $like = "%" . $search . "%";
    $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like;
    $types .= "ssss";
}

if ($category !== '') {
    $sql .= " AND l.category = ?";
    $params[] = $category;
    $types .= "s";
}

$sql .= " ORDER BY l.created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
if ($params) { mysqli_stmt_bind_param($stmt, $types, ...$params); }
mysqli_stmt_execute($stmt);
$listings = mysqli_stmt_get_result($stmt);

$categories = mysqli_query($conn, "SELECT DISTINCT category FROM listings WHERE category IS NOT NULL AND category <> '' ORDER BY category");
$cartCount = get_cart_count($conn, $userId);
$wishlistCount = get_wishlist_count($conn, $userId);
$myListingCount = get_listing_count($conn, $userId);
$totalItems = mysqli_num_rows($listings);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recloset Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="market-body">

    <!-- Announcement + Header + Category Nav (same as before) -->
    <div class="announcement">Sustainable Fashion • Extend the Life of Clothing • Shop Consciously</div>

    <header class="market-header">
        <a class="market-logo" href="user_dashboard.php">Recloset</a>
        <form class="market-search" method="GET" action="user_dashboard.php">
            <span>⌕</span>
            <input type="text" name="search" placeholder="Search for items..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
        </form>
        <nav class="market-icons">
            <a href="my_listings.php">Browse</a>
            <a href="wishlist.php">Wishlist <sup><?php echo $wishlistCount; ?></sup></a>
            <a href="cart.php">Cart <sup><?php echo $cartCount; ?></sup></a>
            <a href="my_listings.php">My Listings <sup><?php echo $myListingCount; ?></sup></a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <nav class="category-nav">
        <a href="user_dashboard.php">All</a>
        <a href="user_dashboard.php?category=Women">Women</a>
        <a href="user_dashboard.php?category=Men">Men</a>
        <a href="user_dashboard.php?category=Kids Clothes">Kids Clothes</a>
        <a href="user_dashboard.php?category=Accessories">Accessories</a>
        <a href="user_dashboard.php?category=Bags">Bags</a>
        <a href="user_dashboard.php?category=Shoes">Shoes</a>
        <a class="selling-link" href="add_listing.php">Start Selling</a>
    </nav>

    <!-- Hero Section -->
    <section class="market-hero">
        <div class="hero-copy">
            <p class="small-label">Welcome, <?php echo htmlspecialchars($userName); ?></p>
            <h1>Buy and sell pre-loved fashion beautifully.</h1>
            <p>Give clothing a second life. Browse sustainable pieces...</p>
            <div class="hero-buttons">
                <a class="green-btn" href="#products">Shop Now →</a>
                <a class="outline-green-btn" href="add_listing.php">Start Selling</a>
            </div>
        </div>
    </section>

    <main class="market-main" id="products">

    <!-- Featured Section -->
    <section class="featured-section">
        <div class="section-heading">
            <div>
                <p class="small-label">Featured finds</p>
                <h2>Fresh from the closet</h2>
            </div>
            
            <!-- Category Filter -->
            <form class="category-filter" method="GET">
                <select name="category" onchange="this.form.submit()">
                    <option value="">All Products</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                        <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                <?php echo $category === $cat['category'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category']); ?>
                        </option>
                    <?php } ?>
                </select>
            </form>
        </div>

        <!-- Product Grid -->
        <section class="product-grid">
            <?php if (mysqli_num_rows($listings) === 0) { ?>
                <div class="empty-market">
                    <h3>No products yet</h3>
                    <p>Add your first listing so the dashboard has items to display.</p>
                    <a class="green-btn" href="add_listing.php">Create Listing</a>
                </div>
            <?php } else { ?>
                <?php while ($item = mysqli_fetch_assoc($listings)) { ?>
                    <article class="product-card">
                        <div class="product-image">
                            <span class="condition-badge">
                                <?php echo htmlspecialchars($item['condition_status'] ?? 'Good'); ?>
                            </span>
                            
                            <?php if (!empty($item['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <?php else: ?>
                                <div class="placeholder-image">
                                    <?= strtoupper(substr($item['title'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="product-info">
                            <div class="product-title-row">
                                <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                                <span><?php echo htmlspecialchars($item['size'] ?? ''); ?></span>
                            </div>
                            
                            <p class="brand-name">
                                <?php echo htmlspecialchars($item['seller_name'] ?? 'Recloset Seller'); ?>
                            </p>
                            
                            <div class="price-row">
                                R<?php echo number_format($item['price'], 2); ?>
                            </div>

                            <div class="product-actions">
                                <form method="POST">
                                    <input type="hidden" name="listing_id" value="<?php echo $item['listing_id']; ?>">
                                    <input type="hidden" name="action" value="add_wishlist">
                                    <button type="submit" class="save-button">♡ Save</button>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="listing_id" value="<?php echo $item['listing_id']; ?>">
                                    <input type="hidden" name="action" value="add_cart">
                                    <button type="submit" class="green-btn">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php } ?>
            <?php } ?>
        </section>
    </section>

</main>

    <!-- NEW FOOTER -->
    <footer class="market-footer">
        <div>
            <h3>Recloset</h3>
            <p>Sustainable peer-to-peer clothing marketplace.<br>Making fashion circular.</p>
        </div>
        <div>
            <h4>Shop</h4>
            <a href="user_dashboard.php">All Products</a>
            <a href="wishlist.php">Wishlist</a>
            <a href="cart.php">Cart</a>
        </div>
        <div>
            <h4>Sell</h4>
            <a href="add_listing.php">Start Selling</a>
            <a href="my_listings.php">My Listings</a>
        </div>
        <div>
            <h4>Support</h4>
            <a href="#">Contact Us</a>
            <a href="#">Shipping</a>
            <a href="#">Returns</a>
        </div>
        <div>
            <h4>Legal</h4>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </footer>

</body>
</html>