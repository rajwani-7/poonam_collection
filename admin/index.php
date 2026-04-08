<?php
require_once '../config/config.php';

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
                <a href="index.php" class="nav-item active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="products.php" class="nav-item">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="orders.php" class="nav-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Orders</span>
                </a>
                <a href="categories.php" class="nav-item">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
                <a href="customers.php" class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Customers</span>
                </a>
                <a href="settings.php" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <header class="top-bar">
                <div class="top-bar-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Dashboard</h1>
                </div>
                
                <div class="top-bar-right">
                    <button class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="badge">3</span>
                    </button>
                    <div class="admin-profile">
                        <img src="../assets/images/admin-avatar.png" alt="Admin">
                        <span>Admin</span>
                    </div>
                </div>
            </header>
            
            <div class="content">
                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['total_products']; ?></h3>
                            <p>Total Products</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['total_orders']; ?></h3>
                            <p>Total Orders</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <div class="stat-info">
                            <h3>₹<?php echo number_format($stats['total_revenue'], 2); ?></h3>
                            <p>Total Revenue</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $stats['pending_orders']; ?></h3>
                            <p>Pending Orders</p>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders -->
                <div class="section-card">
                    <div class="section-header">
                        <h2>Recent Orders</h2>
                        <a href="orders.php" class="btn-view-all">View All</a>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ordersQuery = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 10";
                                $orders = $db->query($ordersQuery)->fetchAll();
                                
                                foreach($orders as $order):
                                ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo $order['customer_name']; ?></td>
                                    <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $order['status']; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <button class="btn-action" onclick="viewOrder(<?php echo $order['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>
