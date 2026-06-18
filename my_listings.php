

<?php

session_start();
include 'DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// DELETE LISTING
if (isset($_GET['delete'])) {
    $listingId = (int)$_GET['delete'];

    $stmt = $conn->prepare("
        DELETE FROM listings
        WHERE listing_id = ?
        AND user_id = ?
    ");
    $stmt->bind_param("ii", $listingId, $userId);
    $stmt->execute();
    $stmt->close();

    header("Location: my_listings.php");
    exit();
}

// FETCH LISTINGS
$stmt = $conn->prepare("
    SELECT *
    FROM listings
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Listings</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="market-body">

<!-- HEADER LINK BACK -->
<header class="market-header">
    <a class="market-logo" href="user_dashboard.php">Recloset</a>

    <div class="market-icons">
        <a href="user_dashboard.php">Browse</a>
        <a href="add_listing.php">Sell</a>
        <a href="logout.php">Logout</a>
    </div>
</header>

<!-- PAGE TITLE -->
<section style="padding: 30px 52px;">
    <h1 style="color: var(--green-dk); margin-bottom: 10px;">
        My Listings
    </h1>

    <p class="muted">
        Manage all clothing items you have listed for sale.
    </p>

    <a href="add_listing.php" class="green-btn" style="display:inline-block; margin-top:15px;">
        + Add New Listing
    </a>
</section>

<!-- GRID -->
<section class="product-grid">

<?php if ($result->num_rows > 0): ?>

    <?php while ($row = $result->fetch_assoc()): ?>

        <div class="product-card">

            <div class="product-image">
                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                     alt="Listing Image">
            </div>

            <div class="product-info">

                <h3><?php echo htmlspecialchars($row['title']); ?></h3>

                <p class="brand-name">
                    Category: <?php echo htmlspecialchars($row['category']); ?>
                </p>

                <p class="brand-name">
                    Size: <?php echo htmlspecialchars($row['size']); ?>
                </p>

                <div class="price-row">
                    R<?php echo number_format($row['price'], 2); ?>
                </div>

                <div class="product-actions">

                    <a href="edit_listing.php?id=<?php echo $row['listing_id']; ?>"
                       class="green-btn">
                        Edit
                    </a>

                    <a href="my_listings.php?delete=<?php echo $row['listing_id']; ?>"
                       class="save-button"
                       onclick="return confirm('Are you sure you want to delete this listing?')">
                        Delete
                    </a>

                </div>

            </div>
        </div>

    <?php endwhile; ?>

<?php else: ?>

    <div class="empty-market" style="grid-column:1/-1;">
        <h3>No Listings Found</h3>
        <p>You have not added any clothing items yet.</p>
        <a href="add_listing.php" class="green-btn">Add Your First Listing</a>
    </div>

<?php endif; ?>

</section>

</body>
</html>