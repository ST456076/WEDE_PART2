-- Import this if your database does not already have these tables.
-- The PHP files also create these tables automatically when the dashboard opens.

CREATE TABLE IF NOT EXISTS listings (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cart (
    cart_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    listing_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_cart_item (user_id, listing_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS wishlist (
    wishlist_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    listing_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_wishlist_item (user_id, listing_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
