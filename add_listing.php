<?php
session_start();
include 'DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

unset($_SESSION['admin_id']);

// When form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form values
    $title = $_POST['title'];
    $description = $_POST['description'];
    $size = $_POST['size'];
    $category = $_POST['category'];
    $condition = $_POST['condition_status'];
    $price = $_POST['price'];

    // Default image path
    $imagePath = "";

    // Check if image uploaded
    if (!empty($_FILES['image']['name'])) {

        // Create uploads folder if needed
        if (!is_dir("uploads")) {
            mkdir("uploads");
        }

        // Create unique filename
        $fileName = time() . "_" . basename($_FILES['image']['name']);

        $targetFile = "uploads/" . $fileName;

        // Move image
        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $targetFile
        );

        $imagePath = $targetFile;
    }

    // Insert listing
    $sql = "
    INSERT INTO listings
    (
        title,
        description,
        size,
        category,
        condition_status,
        price,
        image_url
    )
    VALUES
    (
        '$title',
        '$description',
        '$size',
        '$category',
        '$condition',
        '$price',
        '$imagePath'
    )
    ";

    mysqli_query($conn, $sql);

    header("Location: user_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Listing</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Add Marketplace Listing</h2>

<form method="POST" enctype="multipart/form-data">

    <input
        type="text"
        name="title"
        placeholder="Title"
        required
    >

    <br><br>

    <textarea
        name="description"
        placeholder="Description"
        required
    ></textarea>

    <br><br>

    <input
        type="text"
        name="size"
        placeholder="Size"
        required
    >

    <br><br>

    <input
        type="text"
        name="category"
        placeholder="Category"
        required
    >

    <br><br>

    <input
        type="text"
        name="condition_status"
        placeholder="Condition"
        required
    >

    <br><br>

    <input
        type="number"
        step="0.01"
        name="price"
        placeholder="Price"
        required
    >

    <br><br>

    <input
        type="file"
        name="image"
        accept="image/*"
    >

    <br><br>

    <button type="submit">
        Add Listing
    </button>

</form>

<br>

<a href="user_dashboard.php">
    Back to Dashboard
</a>

</body>
</html>
