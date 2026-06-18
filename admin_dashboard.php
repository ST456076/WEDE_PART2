<?php
session_start();
include "DBConn.php";
// Mark all unread messages as read
$totalUnreadMessages = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM messages
         WHERE status = 'Unread'"
    )
)['total'];
include "bootstrap.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

if (isset($_GET['delete_user'])) {
    $userId = (int)$_GET['delete_user'];

    mysqli_query($conn, "DELETE FROM wishlist WHERE user_id = $userId");
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $userId");
    mysqli_query($conn, "DELETE FROM listings WHERE user_id = $userId");
    mysqli_query($conn, "DELETE FROM tbluser WHERE user_id = $userId");

    $message = "User deleted successfully.";
}

if (isset($_GET['delete_listing'])) {
    $listingId = (int)$_GET['delete_listing'];

    mysqli_query($conn, "DELETE FROM wishlist WHERE listing_id = $listingId");
    mysqli_query($conn, "DELETE FROM cart WHERE listing_id = $listingId");
    mysqli_query($conn, "DELETE FROM listings WHERE listing_id = $listingId");

    $message = "Listing deleted successfully.";
}

$users = mysqli_query($conn, "SELECT * FROM tbluser ORDER BY user_id DESC");
$listings = mysqli_query($conn, "
    SELECT listings.*, tbluser.full_name 
    FROM listings 
    LEFT JOIN tbluser ON listings.user_id = tbluser.user_id
    ORDER BY listings.listing_id DESC
");

$totalUsers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbluser"))['total'];
$totalListings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM listings"))['total'];
$totalCart = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM cart"))['total'];
$totalWishlist = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM wishlist"))['total'];
// Count unread messages
$totalUnreadMessages = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM messages
         WHERE status = 'Unread'"
    )
)['total']; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="market-body">

<div class="announcement">Admin Panel • Recloset Marketplace Management</div>

<header class="market-header">
    <a class="market-logo" href="admin_dashboard.php">Recloset Admin</a>

    <nav class="market-icons">
        <a href="user_dashboard.php">View Store</a>
        <a href="logout.php">Logout</a>
        <a class="green-btn" href="admin_messages.php">
    View Messages
</a>
    </nav>
</header>

<section class="market-hero">
    <div class="hero-copy">
        <p class="small-label">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
        <h1>Manage your clothing marketplace.</h1>
        <p>View customers, monitor listings, and remove users or products when needed.</p>
    </div>
</section>

<?php if ($message !== "") { ?>
    <div class="toast-message"><?php echo htmlspecialchars($message); ?></div>
<?php } ?>

<main class="market-main">

    <section class="product-grid">
        <div class="product-card">
            <div class="product-info">
                <p class="small-label">Users</p>
                <h2><?php echo $totalUsers; ?></h2>
            </div>
        </div>

        <div class="product-card">
            <div class="product-info">
                <p class="small-label">Listings</p>
                <h2><?php echo $totalListings; ?></h2>
            </div>
        </div>

        <div class="product-card">
            <div class="product-info">
                <p class="small-label">Cart Items</p>
                <h2><?php echo $totalCart; ?></h2>
            </div>
        </div>

        <div class="product-card">
            <div class="product-info">
                <p class="small-label">Wishlist Items</p>
                <h2><?php echo $totalWishlist; ?></h2>
            </div>
        </div>
            <div class="product-card">
        <div class="product-info">
            <p class="small-label">Unread Messages</p>
            <h2><?php echo $totalUnreadMessages; ?></h2>
        </div>
    </div>

</section>
    </section>

    <br><br>

    <div class="section-heading">
        <div>
            <p class="small-label">Customer Management</p>
            <h2>Registered Users</h2>
        </div>
        <a class="green-btn" href="add_customer.php">Add Customer</a>
    </div>

    <div class="admin-table-box">
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>

            <?php while ($user = mysqli_fetch_assoc($users)) { ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td>
                        <a class="outline-green-btn" href="edit_customer.php?id=<?php echo $user['user_id']; ?>">Edit</a>
                        <a class="danger-link" href="admin_dashboard.php?delete_user=<?php echo $user['user_id']; ?>" onclick="return confirm('Delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <br><br>

    <div class="section-heading">
        <div>
            <p class="small-label">Listing Management</p>
            <h2>Marketplace Listings</h2>
        </div>
    </div>
    <!-- Add Listing Button -->
     <a class="green-btn" href="admin_add_listing.php">
    Add Listing
</a>

    <div class="admin-table-box">
        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Seller</th>
                <th>Category</th>
                <th>Size</th>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php while ($item = mysqli_fetch_assoc($listings)) { ?>
                <tr>
                    <td><?php echo $item['listing_id']; ?></td>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo htmlspecialchars($item['full_name'] ?? 'No seller'); ?></td>
                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                    <td><?php echo htmlspecialchars($item['size']); ?></td>
                    <td>R<?php echo number_format((float)$item['price'], 2); ?></td>
                   <td>

    <!-- Edit Listing -->
    <a
        class="outline-green-btn"
        href="admin_edit_listing.php?id=<?php echo $item['listing_id']; ?>">
        Edit
    </a>

    <!-- Delete Listing -->
    <a
        class="danger-link"
        href="admin_dashboard.php?delete_listing=<?php echo $item['listing_id']; ?>"
        onclick="return confirm('Delete this listing?')">
        Delete
    </a>

</td>
                </tr>
            <?php } ?>
        </table>
    </div>

</main>


</body>
</html>