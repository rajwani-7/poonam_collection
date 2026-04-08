<?php
// Database Configuration
if (!function_exists('env_value')) {
    function env_value($key, $default = null) {
        $value = getenv($key);

        if ($value === false && isset($_ENV[$key])) {
            $value = $_ENV[$key];
        }

        if ($value === false && isset($_SERVER[$key])) {
            $value = $_SERVER[$key];
        }

        return $value === false ? $default : $value;
    }
}

define('DB_HOST', env_value('DB_HOST', 'localhost'));
define('DB_PORT', env_value('DB_PORT', ''));
define('DB_USER', env_value('DB_USER', 'root'));
define('DB_PASS', env_value('DB_PASS', ''));
define('DB_NAME', env_value('DB_NAME', 'poonam_collection'));
define('DB_CHARSET', env_value('DB_CHARSET', 'utf8mb4'));
define('DB_AUTO_CREATE', filter_var(env_value('DB_AUTO_CREATE', 'false'), FILTER_VALIDATE_BOOLEAN));

class Database {
    private $host = DB_HOST;
    private $port = DB_PORT;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $charset = DB_CHARSET;
    private $autoCreate = DB_AUTO_CREATE;
    private $conn;

    private function buildDsn($includeDatabase = true) {
        $dsn = 'mysql:host=' . $this->host;

        if ($this->port !== '') {
            $dsn .= ';port=' . $this->port;
        }

        if ($includeDatabase) {
            $dsn .= ';dbname=' . $this->dbname;
        }

        if ($this->charset !== '') {
            $dsn .= ';charset=' . $this->charset;
        }

        return $dsn;
    }
    
    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO($this->buildDsn(), $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            if ($this->autoCreate && ((int)$e->getCode() === 1049 || stripos($e->getMessage(), 'Unknown database') !== false)) {
                try {
                    $bootstrap = new PDO($this->buildDsn(false), $this->user, $this->pass);
                    $bootstrap->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $bootstrap->exec('CREATE DATABASE IF NOT EXISTS `' . $this->dbname . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

                    $this->conn = new PDO($this->buildDsn(), $this->user, $this->pass);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                } catch (PDOException $inner) {
                    $this->conn = null;
                }
            }
        }

        if ($this->conn) {
            $this->initializeCoreSchema();
        }
        
        return $this->conn;
    }

    private function initializeCoreSchema() {
        try {
            $this->conn->exec(
                "CREATE TABLE IF NOT EXISTS admin_users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(50) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    email VARCHAR(100) UNIQUE NOT NULL,
                    full_name VARCHAR(100),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )"
            );

            $this->conn->exec(
                "CREATE TABLE IF NOT EXISTS products (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    category VARCHAR(100) NOT NULL,
                    price DECIMAL(10, 2) NOT NULL,
                    description TEXT,
                    stock INT DEFAULT 0,
                    featured BOOLEAN DEFAULT FALSE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )"
            );

            $this->conn->exec(
                "CREATE TABLE IF NOT EXISTS customers (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    full_name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) UNIQUE NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    phone VARCHAR(20) DEFAULT NULL,
                    address TEXT DEFAULT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )"
            );

            $this->conn->exec(
                "CREATE TABLE IF NOT EXISTS orders (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    customer_name VARCHAR(100) NOT NULL,
                    customer_email VARCHAR(100) NOT NULL,
                    customer_phone VARCHAR(20) NOT NULL,
                    shipping_address TEXT NOT NULL,
                    total_amount DECIMAL(10, 2) NOT NULL,
                    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )"
            );

            $this->conn->exec(
                "CREATE TABLE IF NOT EXISTS order_items (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    order_id INT NOT NULL,
                    product_id INT NOT NULL,
                    quantity INT NOT NULL,
                    price DECIMAL(10, 2) NOT NULL,
                    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
                    FOREIGN KEY (product_id) REFERENCES products(id)
                )"
            );

            $this->conn->exec(
                "CREATE TABLE IF NOT EXISTS product_images (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    product_id INT NOT NULL,
                    image_url VARCHAR(500) NOT NULL,
                    is_primary BOOLEAN DEFAULT FALSE,
                    display_order INT DEFAULT 0,
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
                )"
            );

            $adminHash = '$2y$10$DfLR17HGb0SQ2BBj98ka6.8ud5v44GLa3GTIu0W4ZKXVIFaZ1dxRe';
            $stmt = $this->conn->prepare(
                'INSERT INTO admin_users (username, password, email, full_name)
                 VALUES (:username, :password, :email, :full_name)
                 ON DUPLICATE KEY UPDATE password = VALUES(password), full_name = VALUES(full_name)'
            );
            $username = 'admin';
            $email = 'admin@poonamcollection.com';
            $fullName = 'Admin User';
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $adminHash);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':full_name', $fullName);
            $stmt->execute();
        } catch (PDOException $e) {
            // Keep connect resilient; callers will handle unavailable schema if needed.
        }
    }
}
?>
