<?php
session_start();
include "DBConn.php";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM tbladmin WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);

        if ($password === "admin123") {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_full_name'];

            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Wrong admin password.";
        }
    } else {
        $error = "Admin account not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="login-wrapper">

    <!-- LEFT SIDE IMAGE -->
    <div class="login-left">
        <img src="images/recloset-logo.jpg" alt="Admin Login" class="login-logo">
    </div>

    <!-- RIGHT SIDE FORM -->
    <div class="login-right">

        <form class="auth-card" method="POST">

            <h2>Admin Login</h2>
            <p class="muted">Use your admin account to manage the system.</p>

            <?php if (isset($error)) { ?>
                <div class="error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <input type="email" name="email" placeholder="Admin email" required>
            <input type="password" name="password" placeholder="Admin password" required>

            <button type="submit" name="login">
                Login as Admin
            </button>

            <a href="login.php">← Back to User Login</a>

        </form>

    </div>

</div>

</body>
</html>