<?php
header('Content-Type: application/json');

require_once '../config/config.php';

if (!isset($_SESSION['customer_logged_in']) || $_SESSION['customer_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login to continue']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$database = new Database();
$db = $database->connect();

ensureCustomersTable($db);

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$items = $input['items'] ?? [];
$customerPhone = trim((string)($input['phone'] ?? ''));
$shippingAddress = trim((string)($input['shipping_address'] ?? ''));

if (empty($items) || !is_array($items)) {
    echo json_encode(['success' => false, 'message' => 'Cart is empty']);
    exit;
}

if ($shippingAddress === '') {
    echo json_encode(['success' => false, 'message' => 'Shipping address is required']);
    exit;
}

$totalAmount = 0;
$normalizedItems = [];

foreach ($items as $item) {
    $productId = (int)($item['id'] ?? 0);
    $quantity = max(1, (int)($item['quantity'] ?? 1));
    $price = (float)($item['price'] ?? 0);

    if ($productId <= 0 || $price <= 0) {
        continue;
    }

    $totalAmount += $price * $quantity;
    $normalizedItems[] = [
        'product_id' => $productId,
        'quantity' => $quantity,
        'price' => $price
    ];
}

if (count($normalizedItems) === 0) {
    echo json_encode(['success' => false, 'message' => 'No valid items to checkout']);
    exit;
}

$customerName = $_SESSION['customer_name'];
$customerEmail = $_SESSION['customer_email'];
$customerId = (int)$_SESSION['customer_id'];

try {
    $db->beginTransaction();

    $updateCustomer = $db->prepare('UPDATE customers SET phone = :phone, address = :address WHERE id = :id');
    $updateCustomer->bindParam(':phone', $customerPhone);
    $updateCustomer->bindParam(':address', $shippingAddress);
    $updateCustomer->bindParam(':id', $customerId);
    $updateCustomer->execute();

    $orderStmt = $db->prepare(
        'INSERT INTO orders (customer_name, customer_email, customer_phone, shipping_address, total_amount, status)
         VALUES (:customer_name, :customer_email, :customer_phone, :shipping_address, :total_amount, :status)'
    );

    $status = 'pending';
    $orderStmt->bindParam(':customer_name', $customerName);
    $orderStmt->bindParam(':customer_email', $customerEmail);
    $orderStmt->bindParam(':customer_phone', $customerPhone);
    $orderStmt->bindParam(':shipping_address', $shippingAddress);
    $orderStmt->bindParam(':total_amount', $totalAmount);
    $orderStmt->bindParam(':status', $status);
    $orderStmt->execute();

    $orderId = (int)$db->lastInsertId();

    $itemStmt = $db->prepare(
        'INSERT INTO order_items (order_id, product_id, quantity, price)
         VALUES (:order_id, :product_id, :quantity, :price)'
    );

    foreach ($normalizedItems as $item) {
        $itemStmt->bindParam(':order_id', $orderId);
        $itemStmt->bindParam(':product_id', $item['product_id']);
        $itemStmt->bindParam(':quantity', $item['quantity']);
        $itemStmt->bindParam(':price', $item['price']);
        $itemStmt->execute();
    }

    $db->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully',
        'order_id' => $orderId
    ]);
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo json_encode(['success' => false, 'message' => 'Failed to place order']);
}

function ensureCustomersTable($db) {
    $db->exec(
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
}
?>