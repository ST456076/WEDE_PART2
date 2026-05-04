<?php
include "DBConn.php";

if (isset($_POST['register'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($conn, "INSERT INTO tbluser (full_name, email, password) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $full_name, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Registration failed: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
<form class="auth-card" method="POST">
    <h2>Create account</h2>
    <p class="muted">Join the peer-to-peer clothing marketplace.</p>
    <?php if (isset($error)) { ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php } ?>
    <input type="text" name="full_name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="register">Register</button>
    <a href="login.php">Already have an account? Login</a>
</form>
</body>
</html>
