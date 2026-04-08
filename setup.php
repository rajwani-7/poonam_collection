<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup - Poonam Collection</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .setup-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #d4af37;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .step {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #d4af37;
        }
        .step h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .step p {
            color: #666;
            line-height: 1.6;
            font-size: 14px;
        }
        .status {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .status.success {
            background: #d4edda;
            color: #155724;
        }
        .status.error {
            background: #f8d7da;
            color: #721c24;
        }
        .status.warning {
            background: #fff3cd;
            color: #856404;
        }
        .icon {
            font-size: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #d4af37;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #b8941f;
            transform: translateY(-2px);
        }
        code {
            background: #2c3e50;
            color: #d4af37;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 13px;
        }
        .credentials {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .credentials strong {
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <h1>🎉 Poonam Collection Setup</h1>
        <p class="subtitle">E-Commerce Website Installation Guide</p>
        
        <div class="step">
            <h3>Step 1: Check Requirements</h3>
            <p>Ensure you have XAMPP/WAMP installed with Apache and MySQL running.</p>
            <?php
            if (function_exists('mysqli_connect')) {
                echo '<div class="status success"><span class="icon">✓</span> PHP MySQL extension is installed</div>';
            } else {
                echo '<div class="status error"><span class="icon">✗</span> PHP MySQL extension is NOT installed</div>';
            }
            
            if (version_compare(PHP_VERSION, '7.4.0') >= 0) {
                echo '<div class="status success"><span class="icon">✓</span> PHP version ' . PHP_VERSION . ' (Compatible)</div>';
            } else {
                echo '<div class="status warning"><span class="icon">⚠</span> PHP version ' . PHP_VERSION . ' (Upgrade recommended)</div>';
            }
            ?>
        </div>
        
        <div class="step">
            <h3>Step 2: Create Database</h3>
            <p>1. Open phpMyAdmin: <code>http://localhost/phpmyadmin</code></p>
            <p>2. Create database: <code>poonam_collection</code></p>
            <p>3. Import: <code>database/setup.sql</code></p>
            
            <?php
            $db_host = 'localhost';
            $db_user = 'root';
            $db_pass = '';
            $db_name = 'poonam_collection';
            
            $conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
            
            if ($conn) {
                echo '<div class="status success"><span class="icon">✓</span> Database connection successful!</div>';
                mysqli_close($conn);
            } else {
                echo '<div class="status error"><span class="icon">✗</span> Cannot connect to database. Please create it first.</div>';
            }
            ?>
        </div>
        
        <div class="step">
            <h3>Step 3: Check Upload Directory</h3>
            <?php
            $upload_dir = __DIR__ . '/uploads';
            if (!file_exists($upload_dir)) {
                if (mkdir($upload_dir, 0777, true)) {
                    echo '<div class="status success"><span class="icon">✓</span> Upload directory created successfully</div>';
                } else {
                    echo '<div class="status error"><span class="icon">✗</span> Failed to create upload directory</div>';
                }
            } else {
                echo '<div class="status success"><span class="icon">✓</span> Upload directory exists</div>';
            }
            
            if (is_writable($upload_dir)) {
                echo '<div class="status success"><span class="icon">✓</span> Upload directory is writable</div>';
            } else {
                echo '<div class="status warning"><span class="icon">⚠</span> Upload directory may not be writable</div>';
            }
            ?>
        </div>
        
        <div class="step">
            <h3>Step 4: Configure Settings</h3>
            <p>Database configuration is in: <code>config/database.php</code></p>
            <p>General settings in: <code>config/config.php</code></p>
        </div>
        
        <div class="credentials">
            <h3>🔐 Admin Login Credentials</h3>
            <p><strong>URL:</strong> <code>http://localhost/pc/admin/</code></p>
            <p><strong>Username:</strong> <code>admin</code></p>
            <p><strong>Password:</strong> <code>admin123</code></p>
        </div>
        
        <div style="text-align: center;">
            <a href="index.html" class="btn">🚀 Launch Website</a>
            <a href="admin/" class="btn" style="background: #2c3e50;">🔧 Go to Admin Panel</a>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e1e1e1; text-align: center; color: #666; font-size: 12px;">
            <p>For detailed instructions, see <strong>README.md</strong></p>
            <p>© 2026 Poonam Collection. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
