<?php
session_start();
include 'DBConn.php';

$sql = "SELECT * FROM listings ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Listings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!--Navigation Bar-->
<!-- Announcement Bar -->
<div class="announcement">
    Sustainable Fashion • Extend the Life of Clothing • Shop Consciously
</div>

<!-- Main Header -->
<header class="market-header">
    <div class="market-logo">Recloset</div>
    
    <div class="market-search">
        <input type="text" placeholder="Search for items, brands, or categories...">
    </div>

    <div class="market-icons">
        <a href="user_dashboard.php">Browse</a>
        <a href="wishlist.php">Wishlist</a>
        <a href="cart.php" style="position: relative;">
            Cart
            <?php if(isset($_SESSION['cart_count']) && $_SESSION['cart_count'] > 0): ?>
                <sup><?= $_SESSION['cart_count'] ?></sup>
            <?php endif; ?>
        </a>
        <a href="my_listings.php">My Listings</a>
        <a href="logout.php">Logout</a>
    </div>
</header>


<h1>My Listings</h1>

<div class="my-listings-grid">

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="my-listing-card">

    <img src="<?php echo $row['image_url']; ?>" class="listing-image" alt="Listing Image">

    <div class="listing-info">

        <h3><?php echo $row['title']; ?></h3>

        <p>Category: <?php echo $row['category']; ?></p>

        <p>Size: <?php echo $row['size']; ?></p>

        <p>Condition: <?php echo $row['condition_status']; ?></p>

        <p><strong>R<?php echo $row['price']; ?></strong></p>

 <div class="listing-actions">

    <a href="cart.php?add=<?php echo $row['listing_id']; ?>" class="listing-btn cart-btn">
        Add to Cart
    </a>

    <a href="wishlist.php?add=<?php echo $row['listing_id']; ?>" class="listing-btn wishlist-btn">
        Wishlist
    </a>

</div>
    </div>

</div>



<?php } ?>

</div>

<div style="margin-top: 30px;">
    <a href="user_dashboard.php" class="back-dashboard">
        ← Back to Dashboard
    </a>
</div>