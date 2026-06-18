<?php
// Start session
session_start();

// Connect database
include "DBConn.php";

// Handle login
if (isset($_POST['login'])) {

$_SESSION['user_id'] = $user['id'];
unset($_SESSION['admin_id']);

    // Get input values
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Get user by email (safe query)
    $stmt = mysqli_prepare($conn, "SELECT * FROM tbluser WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if user exists
    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        // Check password (supports hashed + plain for testing)
        if (password_verify($password, $user['password']) || $password == $user['password']) {

            // Save session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];

            // Redirect
            header("Location: user_dashboard.php");
            exit();

        } else {
            $error = "Wrong password";
        }

    } else {
        $error = "User not found. Please register first.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

<div class="login-wrapper">

    <!-- LEFT SIDE -->
    <div class="login-left">
        <img src="images/recloset-logo.jpg" class="login-logo" alt="Logo">
    </div>

    <!-- RIGHT SIDE -->
    <div class="login-right">

        <form class="auth-card" method="POST">

            <h2>Welcome back</h2>
            <p class="muted">Login to your ReCloset account.</p>

            <!-- ERROR MESSAGE -->
            <?php if (isset($error)) { ?>
                <div class="error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <!-- INPUTS -->
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <!-- BUTTON -->
            <button type="submit" name="login">Login</button>

            <!-- LINKS -->
            <a href="register.php">Create new account</a><br>
            <a href="admin_login.php">Admin login</a>

        </form>

    </div>

</div>

</body>
</html>