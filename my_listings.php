<?php
// START SESSION AND CONNECT TO DATABASE
session_start();

// CONNECT TO DATABASE

include 'DBConn.php';


// CHECK IF USER IS LOGGED IN
// If not logged in → block access

if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in.");
}

// Get logged-in user ID
$userId = (int)$_SESSION['user_id'];


//DELETE LISTING (ONLY OWN LISTINGS)

if (isset($_GET['delete'])) {

    // Get listing ID from URL
    $listingId = (int)$_GET['delete'];

    // Use prepared statement for security
    $stmt = $conn->prepare("
        DELETE FROM listings
        WHERE listing_id = ?
        AND user_id = ?
    ");

    $stmt->bind_param("ii", $listingId, $userId);
    $stmt->execute();
    $stmt->close();

    // Redirect back to same page after delete
    header("Location: my_listings.php");
    exit();
}


//FETCH USER LISTINGS

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
<html>
<head>
    <title>My Listings</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard-body">

<div class="simple-page">

    <a href="user_dashboard.php">
        ← Back to Marketplace
    </a>

    <h2>My Clothing Listings</h2>

    <p class="muted">
        Manage all clothing items you have listed for sale.
    </p>

    <div style="margin-bottom:20px;">
        <a href="add_listing.php" class="green-btn">
            + Add New Listing
        </a>
    </div>

    <?php if ($result->num_rows > 0) { ?>

        <?php while ($row = $result->fetch_assoc()) { ?>

            <div class="list-row">

                <div style="display:flex; gap:18px; align-items:center;">

                    <img
                        src="<?php echo htmlspecialchars($row['image_url']); ?>"
                        alt="Listing Image"
                        style="
                            width:120px;
                            height:120px;
                            object-fit:cover;
                            border-radius:12px;
                            border:1px solid #393938;
                        "
                    >

                    <div>

                        <h3 style="margin:0;">
                            <?php echo htmlspecialchars($row['title']); ?>
                        </h3>

                        <p>
                            Category:
                            <?php echo htmlspecialchars($row['category']); ?>
                        </p>

                        <p>
                            Size:
                            <?php echo htmlspecialchars($row['size']); ?>
                        </p>

                        <p>
                            <strong>
                                R<?php echo htmlspecialchars($row['price']); ?>
                            </strong>
                        </p>

                    </div>

                </div>

                <div style="display:flex; gap:10px; flex-wrap:wrap;">

                    <a
                        href="edit_listing.php?id=<?php echo $row['listing_id']; ?>"
                        class="outline-green-btn"
                    >
                        Edit
                    </a>

                    <a
                        href="my_listings.php?delete=<?php echo $row['listing_id']; ?>"
                        class="danger-btn"
                        onclick="return confirm('Are you sure you want to delete this listing?')"
                    >
                        Delete
                    </a>

                </div>

            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="empty-market">

            <h3>No Listings Found</h3>

            <p>
                You have not added any clothing items yet.
            </p>

            <a href="add_listing.php" class="green-btn">
                Add Your First Listing
            </a>

        </div>

    <?php } ?>

</div>

</body>
</html>