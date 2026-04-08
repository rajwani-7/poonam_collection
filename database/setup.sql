-- Database Setup for Poonam Collection E-Commerce
-- Created: March 10, 2026

CREATE DATABASE IF NOT EXISTS poonam_collection;
USE poonam_collection;

-- Products Table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT,
    stock INT DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Product Images Table (Multiple images per product)
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Customers Table
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders Table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    shipping_address TEXT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Order Items Table
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert Default Admin User (password: admin123)
INSERT INTO admin_users (username, password, email, full_name)
VALUES ('admin', '$2y$10$DfLR17HGb0SQ2BBj98ka6.8ud5v44GLa3GTIu0W4ZKXVIFaZ1dxRe', 'admin@poonamcollection.com', 'Admin User')
ON DUPLICATE KEY UPDATE
    password = VALUES(password),
    full_name = VALUES(full_name);

-- Insert Sample Products (Men's Ethnic Wear)
INSERT INTO products (name, category, price, description, stock, featured) VALUES
('Royal Silk Kurta', 'Kurtas', 2499.00, 'Premium silk kurta with traditional embroidery', 20, TRUE),
('Designer Sherwani', 'Sherwanis', 12999.00, 'Elegant wedding sherwani with intricate zari work', 8, TRUE),
('Indo-Western Jacket Set', 'Indo-Western', 5499.00, 'Modern fusion jacket with kurta and churidar', 15, TRUE),
('Cotton Kurta Pajama', 'Kurtas', 1299.00, 'Comfortable cotton kurta pajama for daily wear', 35, FALSE),
('Nehru Jacket', 'Jackets', 2999.00, 'Classic Nehru jacket with velvet finish', 18, FALSE),
('Pathani Suit', 'Kurtas', 1799.00, 'Traditional Pathani suit in pure cotton', 25, FALSE),
('Bandhgala Suit', 'Indo-Western', 8999.00, 'Premium bandhgala suit for formal occasions', 10, TRUE),
('Embroidered Sherwani', 'Sherwanis', 15999.00, 'Luxury sherwani with stone work for weddings', 5, TRUE);

-- Insert Sample Images for Products
INSERT INTO product_images (product_id, image_url, is_primary, display_order) VALUES
(1, 'uploads/kurta1-1.jpg', TRUE, 1),
(1, 'uploads/kurta1-2.jpg', FALSE, 2),
(1, 'uploads/kurta1-3.jpg', FALSE, 3),
(2, 'uploads/sherwani1-1.jpg', TRUE, 1),
(2, 'uploads/sherwani1-2.jpg', FALSE, 2),
(2, 'uploads/sherwani1-3.jpg', FALSE, 3),
(3, 'uploads/indo-western1-1.jpg', TRUE, 1),
(3, 'uploads/indo-western1-2.jpg', FALSE, 2),
(4, 'uploads/kurta2-1.jpg', TRUE, 1),
(4, 'uploads/kurta2-2.jpg', FALSE, 2),
(5, 'uploads/jacket1-1.jpg', TRUE, 1),
(5, 'uploads/jacket1-2.jpg', FALSE, 2),
(6, 'uploads/kurta3-1.jpg', TRUE, 1),
(7, 'uploads/bandhgala1-1.jpg', TRUE, 1),
(7, 'uploads/bandhgala1-2.jpg', FALSE, 2),
(8, 'uploads/sherwani2-1.jpg', TRUE, 1),
(8, 'uploads/sherwani2-2.jpg', FALSE, 2);
