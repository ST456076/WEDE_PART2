<?php
// Start session and include necessary files
session_start();
include "DBConn.php";
include "bootstrap.php";

// Security: Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int)$_SESSION['user_id'];
$userName = $_SESSION['user_name'] ?? 'Customer';

// Get search and category filters from URL
$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

// ==================== DATABASE QUERY FOR PRODUCTS ====================
// Build SQL query with optional search and category filters
$sql = "SELECT l.*, u.full_name AS seller_name FROM listings l 
        LEFT JOIN tbluser u ON l.user_id = u.user_id WHERE 1=1";
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
if ($params) { 
    mysqli_stmt_bind_param($stmt, $types, ...$params); 
}
mysqli_stmt_execute($stmt);
$listings = mysqli_stmt_get_result($stmt);

// Get categories for filter dropdown
$categories = mysqli_query($conn, "SELECT DISTINCT category FROM listings 
                WHERE category IS NOT NULL AND category <> '' ORDER BY category");

// Get counts for header badges
$cartCount = get_cart_count($conn, $userId);
$wishlistCount = get_wishlist_count($conn, $userId);
$myListingCount = get_listing_count($conn, $userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recloset - Shop Sustainable Fashion</title>
    <link rel="stylesheet" href="style.css">
</head>


<body class="market-body">


<!-- Announcement Bar -->
<div class="announcement">
    Sustainable Fashion • Extend the Life of Clothing • Shop Consciously
</div>


<!-- Main Header -->
<header class="market-header">

    <a class="market-logo" href="user_dashboard.php">
        Recloset
    </a>


    <form class="market-search" method="GET" action="user_dashboard.php">

        <input type="text" 
        name="search" 
        placeholder="Search for pre-loved items..."
        value="<?php echo htmlspecialchars($search); ?>">

    </form>



    <div class="market-icons">

        <a href="user_dashboard.php">
            Browse
        </a>


        <a href="wishlist.php">
            Wishlist 
            <sup><?php echo $wishlistCount; ?></sup>
        </a>


        <a href="cart.php">
            Cart 
            <sup><?php echo $cartCount; ?></sup>
        </a>


        <a href="my_listings.php">
            My Listings 
            <sup><?php echo $myListingCount; ?></sup>
        </a>


        <a href="logout.php">
            Logout
        </a>

    </div>

</header>



<!-- Category Navigation -->

<nav class="category-nav">

<a href="user_dashboard.php">All</a>

<a href="user_dashboard.php?category=Women">Women</a>

<a href="user_dashboard.php?category=Men">Men</a>

<a href="user_dashboard.php?category=Kids Clothes">Kids Clothes</a>

<a href="user_dashboard.php?category=Accessories">Accessories</a>

<a href="user_dashboard.php?category=Bags">Bags</a>

<a href="user_dashboard.php?category=Shoes">Shoes</a>


<a class="selling-link" href="add_listing.php">
Start Selling
</a>


</nav>



<!-- Hero Section -->

<section class="market-hero">


<div class="hero-copy">


<p class="small-label">
Welcome, <?php echo htmlspecialchars($userName); ?>
</p>


<h1>
Buy and sell pre-loved fashion beautifully.
</h1>


<p>
Give clothing a second life. Browse sustainable pieces...
</p>


<div class="hero-buttons">


<a class="green-btn" href="#products">
Shop Now →
</a>


<a class="outline-green-btn" href="add_listing.php">
Start Selling
</a>
</div>
</div>
</section>

    <!-- CATEGORY COLLECTION SECTION -->

<section class="collection-section">

    <h2>
        Shop By Collection
    </h2>

    <p>
        Find your next favourite pre-loved piece
    </p>


    <div class="collection-grid">


        <div class="collection-card women">

            <h3>Women</h3>

            <p>
                Dresses, tops & more
            </p>

            <a href="user_dashboard.php?category=Women">
                Shop Women
            </a>

        </div>



        <div class="collection-card men">

            <h3>Men</h3>

            <p>
                Jackets, shirts & more
            </p>

            <a href="user_dashboard.php?category=Men">
                Shop Men
            </a>

        </div>




        <div class="collection-card kids">

            <h3>Kids Clothes</h3>

            <p>
                Cute finds for little ones
            </p>
            <a href="user_dashboard.php?category=Kids Clothes">
                Shop Kids
            </a>
        </div>
        <div class="collection-card accessories">
            <h3>Accessories</h3>
            <p>
                Accessories & timeless pieces
            </p>
            <a href="user_dashboard.php?category=Accessories">
                Shop Accessories
            </a>
        </div>
    </div>

</section>

    <!-- MAIN PRODUCTS SECTION -->
    <section id="products" style="padding: 20px 52px 60px;">
        <div style="text-align:center; margin-bottom:50px;">
            <h1 class="section-title">Fresh From The Closet</h1>
            <h2>Pre-loved pieces with stories to tell</h2>
        </div>

        <div class="product-grid">
            <?php if (mysqli_num_rows($listings) === 0): ?>
                <div class="empty-market" style="grid-column: 1 / -1; text-align:center;">
                    <h3>No products found</h3>
                    <a class="green-btn" href="add_listing.php">Create Listing</a>
                </div>
            <?php else: ?>
                <?php while ($item = mysqli_fetch_assoc($listings)): ?>
                    <article class="product-card">
                        <div class="product-image">
                            <span class="condition-badge">
                                <?php echo htmlspecialchars($item['condition_status'] ?? 'Good'); ?>
                            </span>
                            
                            <?php if (!empty($item['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <?php endif; ?>
                        </div>

                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p class="brand-name">
                                <?php echo htmlspecialchars($item['seller_name'] ?? 'Recloset Seller'); ?>
                            </p>
                            <div class="price-row">
                                R<?php echo number_format($item['price'], 2); ?>
                            </div>

                            <div class="product-actions">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="listing_id" value="<?php echo $item['listing_id']; ?>">
                                    <input type="hidden" name="action" value="add_wishlist">
                                    <button type="submit" class="save-button">♡ Save</button>
                                </form>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="listing_id" value="<?php echo $item['listing_id']; ?>">
                                    <input type="hidden" name="action" value="add_cart">
                                    <button type="submit" class="green-btn">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="market-footer">


<div class="footer-brand">

<h2>
Recloset
</h2>

<p>
Sustainable peer-to-peer clothing marketplace.
<br>
Making fashion circular.
</p>

</div>
<div class="footer-section">

<h3>
Shop
</h3>
<a href="user_dashboard.php">
All Products
</a>
<a href="wishlist.php">
Wishlist
</a>
<a href="cart.php">
Cart
</a>
</div>
<div class="footer-section">
<h3>
Sell
</h3>
<a href="add_listing.php">
Start Selling
</a>
<a href="my_listings.php">
My Listings
</a>
</div>
<div class="footer-section">
<h3>
Support
</h3>
<a href="#">
Contact Us
</a>
<a href="#">
Shipping
</a>
<a href="#">
Returns
</a>
</div>
<div class="footer-section">
<h3>
Legal
</h3>
<a href="#">
Privacy Policy
</a>
<a href="#">
Terms of Service
</a>
</div>
</footer>
</body>
</html>