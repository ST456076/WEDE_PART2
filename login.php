<?php
session_start();
include "DBConn.php";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM tbluser WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password']) || $password == $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
<form class="auth-card" method="POST">
    <h2>Welcome back</h2>
    <p class="muted">Login to your ReCloset account.</p>
    <?php if (isset($error)) { ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php } ?>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
    <a href="register.php">Create new account</a><br>
    <a href="admin_login.php">Admin login</a>
</form>
</body>
</html>
