<?php
require_once '../config/config.php';

// Start session (IMPORTANT)
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->connect();

// Get statistics
$statsQuery = "
    SELECT 
        (SELECT COUNT(*) FROM products) as total_products,
        (SELECT COUNT(*) FROM orders) as total_orders,
        (SELECT SUM(total_amount) FROM orders WHERE status = 'delivered') as total_revenue,
        (SELECT COUNT(*) FROM orders WHERE status = 'pending') as pending_orders
";
$stats = $db->query($statsQuery)->fetch();

// ✅ FIX: Handle NULL values safely
$totalRevenue = (float)($stats['total_revenue'] ?? 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Poonam Collection</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Poonam Collection</h2>
                <p>Admin Panel</p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item active"><i class="fas fa-home"></i><span>Dashboard</span></a>
                <a href="products.php" class="nav-item"><i class="fas fa-box"></i><span>Products</span></a>
                <a href="orders.php" class="nav-item"><i class="fas fa-shopping-cart"></i><span>Orders</span></a>
                <a href="categories.php" class="nav-item"><i class="fas fa-tags"></i><span>Categories</span></a>
                <a href="customers.php" class="nav-item"><i class="fas fa-users"></i><span>Customers</span></a>
                <a href="settings.php" class="nav-item"><i class="fas fa-cog"></i><span>Settings</span></a>
                <a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            
            <header class="top-bar">
                <h1>Dashboard</h1>
            </header>
            
            <div class="content">
                
                <!-- Stats -->
                <div class="stats-grid">
                    
                    <div class="stat-card">
                        <h3><?php echo $stats['total_products'] ?? 0; ?></h3>
                        <p>Total Products</p>
                    </div>
                    
                    <div class="stat-card">
                        <h3><?php echo $stats['total_orders'] ?? 0; ?></h3>
                        <p>Total Orders</p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>₹<?php echo number_format($totalRevenue, 2); ?></h3>
                        <p>Total Revenue</p>
                    </div>
                    
                    <div class="stat-card">
                        <h3><?php echo $stats['pending_orders'] ?? 0; ?></h3>
                        <p>Pending Orders</p>
                    </div>
                    
                </div>
                
                <!-- Recent Orders -->
                <div class="section-card">
                    <h2>Recent Orders</h2>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                            $orders = $db->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10")->fetchAll();
                            
                            foreach ($orders as $order):
                                $amount = (float)($order['total_amount'] ?? 0); // ✅ FIX
                            ?>
                            
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo $order['customer_name']; ?></td>
                                <td>₹<?php echo number_format($amount, 2); ?></td>
                                <td><?php echo ucfirst($order['status']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            </tr>
                            
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                </div>
                
            </div>
        </main>
    </div>
</body>
</html>