<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once '../config/config.php';

$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Admin authentication required']);
        exit;
    }

    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    if (!isset($_FILES['image'])) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        exit;
    }
    
    $file = $_FILES['image'];
    
    // Validate file type
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type']);
        exit;
    }
    
    // Validate file size
    if ($file['size'] > $maxFileSize) {
        echo json_encode(['success' => false, 'message' => 'File too large']);
        exit;
    }
    
    // Create upload directory if not exists
    if (!file_exists(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = UPLOAD_DIR . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $imageUrl = UPLOAD_URL . $filename;
        
        // If product_id is provided, save to database
        if (isset($_POST['product_id'])) {
            try {
                $stmt = $db->prepare("
                    INSERT INTO product_images (product_id, image_url, is_primary, display_order)
                    VALUES (:product_id, :image_url, :is_primary, :display_order)
                ");
                
                $productId = $_POST['product_id'];
                $isPrimary = isset($_POST['is_primary']) ? $_POST['is_primary'] : 0;
                $displayOrder = isset($_POST['display_order']) ? $_POST['display_order'] : 0;
                
                $stmt->bindParam(':product_id', $productId);
                $stmt->bindParam(':image_url', $imageUrl);
                $stmt->bindParam(':is_primary', $isPrimary);
                $stmt->bindParam(':display_order', $displayOrder);
                
                $stmt->execute();
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Image uploaded and saved',
                    'url' => $imageUrl,
                    'id' => $db->lastInsertId()
                ]);
            } catch(PDOException $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode([
                'success' => true, 
                'message' => 'Image uploaded',
                'url' => $imageUrl
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
