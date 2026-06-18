<?php
session_start();
include "DBConn.php";
include "bootstrap.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int)$_SESSION['user_id'];
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $size = trim($_POST['size'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $condition = trim($_POST['condition_status'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $imageUrl = trim($_POST['image_url'] ?? '');

    if ($title === '' || $price <= 0) {
        $error = "Please enter a title and valid price.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO listings (user_id, title, description, size, category, condition_status, price, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "isssssds", $userId, $title, $description, $size, $category, $condition, $price, $imageUrl);
        mysqli_stmt_execute($stmt);
        header("Location: my_listings.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Listingggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

<form class="auth-card wide" method="POST">
    <h2>Add a clothing listing</h2>
    <p class="muted">Upload the details of the item you want to sell.</p>

    <?php if ($error !== '') { ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <input type="text" name="title" placeholder="Item title" required>

    <textarea name="description" placeholder="Description"></textarea>

    <div class="two-cols">
        <input type="text" name="size" placeholder="Size e.g. M">

        <select name="category" required>
    <option value="">Select Category</option>
    <option value="Women">Women</option>
    <option value="Men">Men</option>
    <option value="Kids Clothes">Kids Clothes</option>
    <option value="Accessories">Accessories</option>
    <option value="Bags">Bags</option>
    <option value="Shoes">Shoes</option>
</select>
    </div>

    <div class="two-cols">
        <input type="text" name="condition_status" placeholder="Condition e.g. Like New">
        <input type="number" step="0.01" min="1" name="price" placeholder="Price" required>
    </div>

    <input type="text" name="image_url" placeholder="Image URL e.g. images/dress.jpg">

    <button type="submit">Publish listing</button>

    <a href="user_dashboard.php">Back to dashboard</a>
</form>

</body>
</html>