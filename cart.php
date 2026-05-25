<?php
session_start();
include 'DBConn.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT cart.cart_id,
               cart.quantity,
               listings.title,
               listings.price,
               listings.image_url
        FROM cart
        INNER JOIN listings
        ON cart.listing_id = listings.listing_id
        WHERE cart.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Your Shopping Cart</h1>

<table border="1">
<tr>
    <th>Image</th>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
    <th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) {

$subtotal = $row['price'] * $row['quantity'];
$total += $subtotal;
?>

<tr>
    <td>
        <img src="image_urls/<?php echo $row['image_url']; ?>" width="100">
    </td>

    <td><?php echo $row['title']; ?></td>

    <td>R<?php echo $row['price']; ?></td>

    <td>
        <form action="update_cart.php" method="POST">
            <input type="hidden" name="cart_id"
            value="<?php echo $row['cart_id']; ?>">

            <input type="number" name="quantity"
            value="<?php echo $row['quantity']; ?>" min="1">

            <button type="submit">Update</button>
        </form>
    </td>

    <td>R<?php echo $subtotal; ?></td>

    <td>
        <a href="remove_cart.php?id=<?php echo $row['cart_id']; ?>">
            Remove
        </a>
    </td>
</tr>

<?php } ?>

</table>

<h2>Total: R<?php echo $total; ?></h2>

<a href="listings.php" class="btn">
    Continue Shopping
</a>

<a href="checkout.php" class="btn">
    Checkout
</a>

</body>
</html>
