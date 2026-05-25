<?php
session_start();
include 'DBConn.php';

$sql = "SELECT * FROM listings ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Marketplace Listings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Marketplace Listings</h1>

<div class="product-container">

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="product-card">

    <img src="<?php echo $row['image_url']; ?>">

    <h3><?php echo $row['title']; ?></h3>

    <p><?php echo $row['category']; ?></p>

    <p>Size: <?php echo $row['size']; ?></p>

    <p>Condition: <?php echo $row['condition_status']; ?></p>

    <p>R<?php echo $row['price']; ?></p>

    <a href="cart.php?add=<?php echo $row['listing_id']; ?>">
        Add to Cart
    </a>

    <a href="wishlist.php?add=<?php echo $row['listing_id']; ?>">
        Wishlist
    </a>

</div>

<?php } ?>

</div>

</body>
</html>
