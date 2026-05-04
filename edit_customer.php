<?php
session_start();
include "DBConn.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM tbluser WHERE user_id = '$id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];

    $sql = "UPDATE tbluser SET full_name = '$full_name', email = '$email' WHERE user_id = '$id'";
    mysqli_query($conn, $sql);

    header("Location: customers.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Customer</title>
</head>
<body>

<h2>Edit Customer</h2>

<form method="POST">
    <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required><br><br>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br><br>

    <button type="submit" name="update">Update Customer</button>
</form>

<br>
<a href="customers.php">Back</a>

</body>
</html>