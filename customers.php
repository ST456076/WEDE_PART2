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
<body>

<h2>Customer Management Page</h2>

<p>Welcome, <?php echo $_SESSION['admin_name']; ?></p>

<a href="add_customer.php">Add New Customer</a><br><br>
<a href="logout.php">Logout</a><br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>User ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
                <a href="edit_customer.php?id=<?php echo $row['user_id']; ?>">Edit</a>
                |
                <a href="delete_customer.php?id=<?php echo $row['user_id']; ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>