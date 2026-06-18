<?php
session_start();
include "DBConn.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT * FROM tbluser";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="market-body">

<!-- HEADER -->
<header class="market-header">
    <a class="market-logo" href="admin_dashboard.php">Recloset Admin</a>

    <nav class="market-icons">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<!-- HERO -->
<section class="market-hero">
    <div class="hero-copy">
        <p class="small-label">Customer Management</p>
        <h1>Users Overview</h1>
        <p>Manage all registered customers in your store.</p>
    </div>
</section>

<main class="market-main">

<!-- BUTTON -->
<div style="margin-bottom:20px;">
    <a class="green-btn" href="add_customer.php">
        + Add New Customer
    </a>
</div>

<!-- TABLE -->
<div class="admin-table-box">

<table class="admin-table">

    <tr>
        <th>User ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>

            <td>
                <a class="outline-green-btn"
                   href="edit_customer.php?id=<?php echo $row['user_id']; ?>">
                   Edit
                </a>

                <a class="danger-link"
                   href="delete_customer.php?id=<?php echo $row['user_id']; ?>"
                   onclick="return confirm('Delete this user?')">
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