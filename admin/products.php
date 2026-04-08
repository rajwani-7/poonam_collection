<?php
require_once '../config/config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$database = new Database();
$db = $database->connect();

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header('Location: products.php');
    exit;
}

// Get all products
$productsQuery = "
    SELECT p.*, 
           COUNT(pi.id) as image_count,
           (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN product_images pi ON p.id = pi.product_id
    GROUP BY p.id
    ORDER BY p.created_at DESC
";
$products = $db->query($productsQuery)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - Admin</title>
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
                <a href="index.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="products.php" class="nav-item active">
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
                    <h1>Products Management</h1>
                </div>
                
                <div class="top-bar-right">
                    <button class="btn btn-primary" onclick="openAddProductModal()">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                </div>
            </header>
            
            <div class="content">
                <div class="section-card">
                    <div class="section-header">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Search products..." id="searchInput">
                        </div>
                        
                        <select class="filter-select" id="categoryFilter">
                            <option value="">All Categories</option>
                            <option value="Kurtas">Kurtas</option>
                            <option value="Sherwanis">Sherwanis</option>
                            <option value="Indo-Western">Indo-Western</option>
                            <option value="Jackets">Jackets</option>
                        </select>
                    </div>
                    
                    <div class="products-grid-admin">
                        <?php foreach($products as $product): ?>
                        <div class="product-card-admin" data-category="<?php echo $product['category']; ?>">
                            <div class="product-image-admin">
                                <?php if($product['primary_image']): ?>
                                    <img src="<?php echo $product['primary_image']; ?>" alt="<?php echo $product['name']; ?>">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                        <p>No Image</p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if($product['featured']): ?>
                                <span class="featured-badge">Featured</span>
                                <?php endif; ?>
                                
                                <div class="product-overlay">
                                    <button class="btn-icon" onclick="editProduct(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon" onclick="manageImages(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-images"></i>
                                    </button>
                                    <button class="btn-icon btn-delete" onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="product-info-admin">
                                <h3><?php echo $product['name']; ?></h3>
                                <p class="category-tag"><?php echo $product['category']; ?></p>
                                <div class="product-details">
                                    <span class="price">₹<?php echo number_format($product['price'], 2); ?></span>
                                    <span class="stock">Stock: <?php echo $product['stock']; ?></span>
                                </div>
                                <div class="product-meta">
                                    <span><i class="fas fa-images"></i> <?php echo $product['image_count']; ?> images</span>
                                    <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($product['created_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Add/Edit Product Modal -->
    <div class="modal" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Product</h2>
                <button class="close-modal" onclick="closeProductModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="productForm" enctype="multipart/form-data">
                <input type="hidden" id="productId" name="id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input type="text" name="name" id="productName" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Category *</label>
                        <select name="category" id="productCategory" required>
                            <option value="">Select Category</option>
                            <option value="Kurtas">Kurtas</option>
                            <option value="Sherwanis">Sherwanis</option>
                            <option value="Indo-Western">Indo-Western</option>
                            <option value="Jackets">Jackets</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price (₹) *</label>
                        <input type="number" name="price" id="productPrice" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Stock *</label>
                        <input type="number" name="stock" id="productStock" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="productDescription" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="featured" id="productFeatured">
                        <span>Mark as Featured Product</span>
                    </label>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeProductModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Image Management Modal -->
    <div class="modal" id="imageModal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h2>Manage Product Images</h2>
                <button class="close-modal" onclick="closeImageModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="upload-area">
                    <form id="imageUploadForm" enctype="multipart/form-data">
                        <input type="hidden" id="imageProductId" name="product_id">
                        <div class="upload-box">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <h3>Upload Images</h3>
                            <p>Drag and drop or click to select</p>
                            <input type="file" name="images[]" id="imageInput" multiple accept="image/*">
                        </div>
                    </form>
                </div>
                
                <div class="images-grid" id="productImages">
                    <!-- Images will be loaded here -->
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
    <script src="../assets/js/products.js"></script>
</body>
</html>
