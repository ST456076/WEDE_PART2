<?php
session_start();
include 'DBConn.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT wishlist.wishlist_id,
               listings.title,
               listings.price,
               listings.image_url
        FROM wishlist
        INNER JOIN listings
        ON wishlist.listing_id = listings.listing_id
        WHERE wishlist.user_id='$user_id'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wishlist</title>
</head>
<body>

<h1>My Wishlist</h1>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div>
    <img src="image_urls/<?php echo $row['image_url']; ?>" width="120">

    <h3><?php echo $row['title']; ?></h3>

    <p>R<?php echo $row['price']; ?></p>

    <a href="add_to_cart.php?id=<?php echo $row['wishlist_id']; ?>">
        Add to Cart
    </a>
</div>

<?php } ?>

</body>
</html>
