<?php
header('Content-Type: application/json');

require_once '../config/config.php';

$database = new Database();
$db = $database->connect();

ensureCustomersTable($db);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($method === 'GET' && $action === 'status') {
    echo json_encode([
        'success' => true,
        'logged_in' => isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] === true,
        'customer' => isset($_SESSION['customer_logged_in']) ? [
            'id' => $_SESSION['customer_id'],
            'name' => $_SESSION['customer_name'],
            'email' => $_SESSION['customer_email']
        ] : null
    ]);
    exit;
}

if ($method !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}

switch ($action) {
    case 'register':
        registerCustomer($db, $input);
        break;
    case 'login':
        loginCustomer($db, $input);
        break;
    case 'logout':
        logoutCustomer();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
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

function registerCustomer($db, $input) {
    $name = trim((string)($input['name'] ?? ''));
    $email = strtolower(trim((string)($input['email'] ?? '')));
    $password = (string)($input['password'] ?? '');
    $phone = trim((string)($input['phone'] ?? ''));

    if ($name === '' || $email === '' || $password === '') {
        echo json_encode(['success' => false, 'message' => 'Name, email, and password are required']);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
        return;
    }

    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
        return;
    }

    $existing = $db->prepare('SELECT id FROM customers WHERE email = :email LIMIT 1');
    $existing->bindParam(':email', $email);
    $existing->execute();

    if ($existing->fetch()) {
        echo json_encode(['success' => false, 'message' => 'An account with this email already exists']);
        return;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare(
        'INSERT INTO customers (full_name, email, password, phone) VALUES (:full_name, :email, :password, :phone)'
    );
    $stmt->bindParam(':full_name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':phone', $phone);

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Registration failed']);
        return;
    }

    $customerId = (int)$db->lastInsertId();
    establishCustomerSession($customerId, $name, $email);

    echo json_encode(['success' => true, 'message' => 'Account created successfully']);
}

function loginCustomer($db, $input) {
    $email = strtolower(trim((string)($input['email'] ?? '')));
    $password = (string)($input['password'] ?? '');

    if ($email === '' || $password === '') {
        echo json_encode(['success' => false, 'message' => 'Email and password are required']);
        return;
    }

    $stmt = $db->prepare('SELECT id, full_name, email, password FROM customers WHERE email = :email LIMIT 1');
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $customer = $stmt->fetch();

    if (!$customer || !password_verify($password, $customer['password'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
        return;
    }

    establishCustomerSession((int)$customer['id'], $customer['full_name'], $customer['email']);
    echo json_encode(['success' => true, 'message' => 'Login successful']);
}

function logoutCustomer() {
    unset($_SESSION['customer_logged_in']);
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_name']);
    unset($_SESSION['customer_email']);

    echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
}

function establishCustomerSession($id, $name, $email) {
    session_regenerate_id(true);
    $_SESSION['customer_logged_in'] = true;
    $_SESSION['customer_id'] = $id;
    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;
}
?>