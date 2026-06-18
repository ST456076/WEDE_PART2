
<?php
session_start();
include "DBConn.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get customer ID safely
$id = intval($_GET['id']);

// Fetch customer data
$sql = "SELECT * FROM tbluser WHERE user_id = '$id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// If customer doesn't exist
if (!$user) {
    header("Location: customers.php");
    exit();
}

// Update customer
if (isset($_POST['update'])) {

    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "
        UPDATE tbluser
        SET
            full_name = '$full_name',
            email = '$email'
        WHERE user_id = '$id'
    ";

    mysqli_query($conn, $sql);

    header("Location: customers.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>

    <!-- Link your stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

<div class="auth-card">

    <h2>Edit Customer</h2>

    <p class="muted">
        Update customer account information.
    </p>

    <form method="POST">

        <input
            type="text"
            name="full_name"
            value="<?php echo htmlspecialchars($user['full_name']); ?>"
            placeholder="Full Name"
            required
        >

        <input
            type="email"
            name="email"
            value="<?php echo htmlspecialchars($user['email']); ?>"
            placeholder="Email Address"
            required
        >

        <button type="submit" name="update">
            Update Customer
        </button>

    </form>

    <a href="customers.php">
        ← Back to Customers
    </a>

</div>

</body>
</html>

