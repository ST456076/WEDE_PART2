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
        $validPassword = (!isset($admin['password']) && $password == 'admin123') || (isset($admin['password']) && ($password == $admin['password'] || password_verify($password, $admin['password'])));

        if ($validPassword) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_full_name'];
            header("Location: customers.php");
            exit();
        } else {
            $error = "Wrong password";
        }
    } else {
        $error = "Admin not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
<form class="auth-card" method="POST">
    <h2>Admin login</h2>
    <p class="muted">Default password for your current SQL admin is <strong>admin123</strong>.</p>
    <?php if (isset($error)) { ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php } ?>
    <input type="email" name="email" placeholder="Admin Email" required>
    <input type="password" name="password" placeholder="Admin Password" required>
    <button type="submit" name="login">Login</button>
    <a href="login.php">Back to user login</a>
</form>
</body>
</html>
