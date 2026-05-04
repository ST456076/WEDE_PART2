<?php
// Shared setup for the marketplace pages.
// It keeps the dashboard working even if the wishlist/cart/listings tables have not been imported yet.
if (!isset($conn)) {
    include __DIR__ . "/DBConn.php";
}

function table_exists($conn, $tableName) {
    $stmt = mysqli_prepare($conn, "SHOW TABLES LIKE ?");
    mysqli_stmt_bind_param($stmt, "s", $tableName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

function setup_marketplace_tables($conn) {
    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS listings (
        listing_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        title VARCHAR(120) NOT NULL,
        description TEXT NULL,
        size VARCHAR(30) NULL,
        category VARCHAR(60) NULL,
        condition_status VARCHAR(60) NULL,
        price DECIMAL(10,2) NOT NULL DEFAULT 0,
        image_url VARCHAR(255) NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS cart (
        cart_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        listing_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_cart_item (user_id, listing_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    mysqli_query($conn, "CREATE TABLE IF NOT EXISTS wishlist (
        wishlist_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        listing_id INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_wishlist_item (user_id, listing_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM listings");
    $countRow = $countResult ? mysqli_fetch_assoc($countResult) : ['total' => 0];

    if ((int)$countRow['total'] === 0) {
        $seedSql = "INSERT INTO listings (user_id, title, description, size, category, condition_status, price, image_url) VALUES
            (NULL, 'Vintage Denim Jacket', 'Classic blue denim jacket for everyday outfits.', 'M', 'Jackets', 'Good', 280.00, ''),
            (NULL, 'White Summer Dress', 'Lightweight dress, perfect for casual days.', 'S', 'Dresses', 'Like New', 220.00, ''),
            (NULL, 'Streetwear Hoodie', 'Comfortable oversized hoodie.', 'L', 'Hoodies', 'Good', 180.00, ''),
            (NULL, 'Black Cargo Pants', 'Trendy cargo pants with side pockets.', 'M', 'Pants', 'New', 250.00, '')";
        mysqli_query($conn, $seedSql);
    }
}

function get_listing_count($conn, $userId) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM listings WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return (int)$row['total'];
}

function get_cart_count($conn, $userId) {
    $stmt = mysqli_prepare($conn, "SELECT COALESCE(SUM(quantity), 0) AS total FROM cart WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return (int)$row['total'];
}

function get_wishlist_count($conn, $userId) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS total FROM wishlist WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return (int)$row['total'];
}

setup_marketplace_tables($conn);
?>
