<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $legacyAdminHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
    
    $database = new Database();
    $db = $database->connect();

    if (!$db) {
        $error = 'Database connection failed. Please start MySQL and run setup at /pc/setup.php';
    } else {
        try {
            $stmt = $db->prepare("SELECT * FROM admin_users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            $admin = $stmt->fetch();
            
            $isValidPassword = $admin && password_verify($password, $admin['password']);
    
            // Backward compatibility for older seeded databases with an incorrect hash.
            if (
                !$isValidPassword &&
                $admin &&
                $admin['username'] === 'admin' &&
                $password === 'admin123' &&
                hash_equals($legacyAdminHash, $admin['password'])
            ) {
                $newHash = password_hash('admin123', PASSWORD_DEFAULT);
                $update = $db->prepare('UPDATE admin_users SET password = :password WHERE id = :id');
                $update->bindParam(':password', $newHash);
                $update->bindParam(':id', $admin['id']);
                $update->execute();
                $isValidPassword = true;
            }

            if ($isValidPassword) {
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['full_name'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid username or password';
            }
        } catch (PDOException $e) {
            $error = 'Database is not initialized. Please run setup at /pc/setup.php';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Poonam Collection</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo-circle">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h1>Poonam Collection</h1>
                <p>Admin Panel Login</p>
            </div>
            
            <?php if($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form">
                <div class="form-group">
                    <label>
                        <i class="fas fa-user"></i>
                        Username
                    </label>
                    <input type="text" name="username" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i>
                    Login to Dashboard
                </button>
            </form>
            
            <div class="login-footer">
                <p><a href="../login.php">Customer Login</a> | <a href="../index.html">Back to Shop</a></p>
                <p>Default credentials: admin / admin123</p>
                <p>&copy; 2026 Poonam Collection. All rights reserved.</p>
            </div>
        </div>
        
        <div class="login-bg">
            <div class="bg-pattern"></div>
        </div>
    </div>
</body>
</html>
