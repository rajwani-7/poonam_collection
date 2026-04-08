<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once '../config/config.php';

$database = new Database();
$db = $database->connect();

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        getProducts($db);
        break;
    case 'POST':
        requireAdminSession();
        createProduct($db);
        break;
    case 'PUT':
        requireAdminSession();
        updateProduct($db);
        break;
    case 'DELETE':
        requireAdminSession();
        deleteProduct($db);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

function requireAdminSession() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Admin authentication required']);
        exit;
    }
}

function getProducts($db) {
    try {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $featured = isset($_GET['featured']) ? $_GET['featured'] : null;
        $limit = isset($_GET['limit']) ? $_GET['limit'] : 50;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : 0;
        
        if ($id) {
            // Get single product with images
            $stmt = $db->prepare("
                SELECT p.*, 
                       GROUP_CONCAT(pi.image_url ORDER BY pi.display_order) as images
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id
                WHERE p.id = :id
                GROUP BY p.id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $product = $stmt->fetch();
            
            if ($product) {
                $product['images'] = $product['images'] ? explode(',', $product['images']) : [];
                echo json_encode(['success' => true, 'product' => $product]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found']);
            }
        } else {
            // Get multiple products
            $query = "
                SELECT p.*, 
                       pi.image_url as image
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
                WHERE 1=1
            ";
            
            if ($category) {
                $query .= " AND p.category = :category";
            }
            
            if ($featured !== null) {
                $query .= " AND p.featured = :featured";
            }
            
            $query .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
            
            $stmt = $db->prepare($query);
            
            if ($category) {
                $stmt->bindParam(':category', $category);
            }
            
            if ($featured !== null) {
                $stmt->bindParam(':featured', $featured);
            }
            
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $products = $stmt->fetchAll();
            
            echo json_encode(['success' => true, 'products' => $products]);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function createProduct($db) {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        
        $stmt = $db->prepare("
            INSERT INTO products (name, category, price, description, stock, featured)
            VALUES (:name, :category, :price, :description, :stock, :featured)
        ");
        
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':featured', $data['featured']);
        
        if ($stmt->execute()) {
            $productId = $db->lastInsertId();
            
            // Insert images if provided
            if (isset($data['images']) && is_array($data['images'])) {
                $imageStmt = $db->prepare("
                    INSERT INTO product_images (product_id, image_url, is_primary, display_order)
                    VALUES (:product_id, :image_url, :is_primary, :display_order)
                ");
                
                foreach ($data['images'] as $index => $imageUrl) {
                    $imageStmt->bindParam(':product_id', $productId);
                    $imageStmt->bindParam(':image_url', $imageUrl);
                    $isPrimary = ($index === 0) ? 1 : 0;
                    $imageStmt->bindParam(':is_primary', $isPrimary);
                    $imageStmt->bindParam(':display_order', $index);
                    $imageStmt->execute();
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Product created', 'id' => $productId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create product']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function updateProduct($db) {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        
        $stmt = $db->prepare("
            UPDATE products 
            SET name = :name, 
                category = :category, 
                price = :price, 
                description = :description, 
                stock = :stock, 
                featured = :featured
            WHERE id = :id
        ");
        
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':stock', $data['stock']);
        $stmt->bindParam(':featured', $data['featured']);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update product']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function deleteProduct($db) {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $data['id']);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
