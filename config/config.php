<?php
// General Configuration
session_start();

// Base URL
define('BASE_URL', 'http://localhost/pc/');
define('ADMIN_URL', BASE_URL . 'admin/');

// Upload Directory
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Site Settings
define('SITE_NAME', 'Poonam Collection');
define('SITE_EMAIL', 'info@poonamcollection.com');
define('SITE_PHONE', '+91 9876543210');

// Pagination
define('ITEMS_PER_PAGE', 12);

// Error Reporting (Set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Include Database
require_once __DIR__ . '/database.php';
?>
