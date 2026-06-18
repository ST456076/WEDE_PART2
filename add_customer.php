
<?php
session_start();
include "DBConn.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// When admin submits the form
if (isset($_POST['add'])) {

    // Get form values
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if email already exists
    $check = mysqli_query(
        $conn,
        "SELECT * FROM tbluser WHERE email='$email'"
    );

    if (mysqli_num_rows($check) > 0) {

        $message = "Email already exists.";

    } else {

        // Insert customer
        $sql = "
        INSERT INTO tbluser
        (
            full_name,
            email,
            password
        )
        VALUES
        (
            '$full_name',
            '$email',
            '$password'
        )
        ";

        if (mysqli_query($conn, $sql)) {

            header("Location: customers.php");
            exit();

        } else {

            $message = "Error adding customer.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Customer</title>

    <!-- Link to your existing stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

<div class="auth-card">

    <h2>Add New Customer</h2>

    <p class="muted">
        Create a customer account for the marketplace.
    </p>

    <?php if (!empty($message)) { ?>
        <div class="error">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <input
            type="text"
            name="full_name"
            placeholder="Full Name"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="Email Address"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit" name="add">
            Add Customer
        </button>

    </form>

    <a href="customers.php">
        ← Back to Customers
    </a>

</div>

</body>
</html>

