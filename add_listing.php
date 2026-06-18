<?php
session_start();
include "DBConn.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int)$_SESSION['user_id'];
$error = "";
$title = '';
$description = '';
$size = '';
$category = '';
$condition = '';
$price = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $size = trim($_POST['size'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $condition = trim($_POST['condition_status'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $imagePath = "";
    $allowedCategories = ['Women', 'Men', 'Kids Clothes', 'Accessories', 'Bags', 'Shoes'];
    $maxFileSize = 5 * 1024 * 1024; // 5 MB
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if ($title === '' || $price <= 0) {
        $error = "Please enter a title and valid price.";
    } elseif ($category === '' || !in_array($category, $allowedCategories, true)) {
        $error = "Please select a valid category.";
    } elseif (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = "Please select an image.";
    } elseif ($_FILES['image']['size'] > $maxFileSize) {
        $error = "Image must be 5MB or smaller.";
    } else {
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);

        if ($imageInfo === false || !in_array($imageInfo['mime'], $allowedMimeTypes, true)) {
            $error = "Only JPG, PNG, and GIF images are allowed.";
        } else {
            $uploadDir = __DIR__ . '/uploads/';

            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                $error = "Unable to create upload directory.";
            } else {
                $fileName = basename($_FILES['image']['name']);
                $fileName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $fileName);
                $relativePath = 'uploads/' . time() . '_' . $fileName;
                $targetFile = $uploadDir . basename($relativePath);

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $error = "Failed to upload image.";
                } else {
                    $imagePath = $relativePath;

                    $stmt = mysqli_prepare(
                        $conn,
                        "INSERT INTO listings 
                (user_id, title, description, size, category, condition_status, price, image_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
                    );

                    if ($stmt) {
                        mysqli_stmt_bind_param(
                            $stmt,
                            "isssssds",
                            $userId,
                            $title,
                            $description,
                            $size,
                            $category,
                            $condition,
                            $price,
                            $imagePath
                        );

                        if (mysqli_stmt_execute($stmt)) {
                            header("Location: my_listings.php");
                            exit();
                        } else {
                            $error = "Database error: " . mysqli_stmt_error($stmt);
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        $error = "Database error: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
}
// Delete seller listing
if (isset($_GET['delete'])) {

    // Store listing ID from URL
    $listingId = (int)$_GET['delete'];

    // Delete ONLY seller's own listing
    $delete = mysqli_prepare(
        $conn,
        "DELETE FROM listings
         WHERE listing_id = ?
         AND user_id = ?"
    );

    mysqli_stmt_bind_param(
        $delete,
        "ii",
        $listingId,
        $userId
    );

    mysqli_stmt_execute($delete);

}
// Get current seller listings
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM listings
     WHERE user_id = ?
     ORDER BY listing_id DESC"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $userId
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Listing</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

<form class="auth-card wide" method="POST" enctype="multipart/form-data">    <h2>Add a clothing listing</h2>
    <p class="muted">Upload the details of the item you want to sell.</p>

    <?php if ($error !== '') { ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <input type="text" name="title" placeholder="Item title" required value="<?php echo htmlspecialchars($title); ?>">

    <textarea name="description" placeholder="Description"><?php echo htmlspecialchars($description); ?></textarea>

    <div class="two-cols">
        <input type="text" name="size" placeholder="Size e.g. M" value="<?php echo htmlspecialchars($size); ?>">

        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Women" <?php echo $category === 'Women' ? 'selected' : ''; ?>>Women</option>
            <option value="Men" <?php echo $category === 'Men' ? 'selected' : ''; ?>>Men</option>
            <option value="Kids Clothes" <?php echo $category === 'Kids Clothes' ? 'selected' : ''; ?>>Kids Clothes</option>
            <option value="Accessories" <?php echo $category === 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
            <option value="Bags" <?php echo $category === 'Bags' ? 'selected' : ''; ?>>Bags</option>
            <option value="Shoes" <?php echo $category === 'Shoes' ? 'selected' : ''; ?>>Shoes</option>
        </select>
    </div>

    <div class="two-cols">
        <input type="text" name="condition_status" placeholder="Condition e.g. Like New" value="<?php echo htmlspecialchars($condition); ?>">
        <input type="number" step="0.01" min="1" name="price" placeholder="Price" required value="<?php echo $price > 0 ? htmlspecialchars((string)$price) : ''; ?>">
    </div>

    <div class="file-upload">
        <label for="image">Upload product image</label>
        <div class="file-input-wrapper">
            <input id="image" type="file" name="image" accept="image/jpeg, image/png, image/gif" required>
            <span class="file-name">No file selected</span>
        </div>
        
    </div>
    <button type="submit">Publish listing</button>

    <a href="user_dashboard.php">Back to dashboard</a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const fileName = document.querySelector('.file-input-wrapper .file-name');

    if (fileInput && fileName) {
        fileInput.addEventListener('change', function() {
            const selected = fileInput.files.length ? fileInput.files[0].name : 'No file selected';
            fileName.textContent = selected;
        });
    }
});
</script>

</body>
</html>