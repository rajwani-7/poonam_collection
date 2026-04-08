<?php
// General Configuration
session_start();

if (!function_exists('env_value')) {
	function env_value($key, $default = null) {
		$value = getenv($key);

		if ($value === false && isset($_ENV[$key])) {
			$value = $_ENV[$key];
		}

		if ($value === false && isset($_SERVER[$key])) {
			$value = $_SERVER[$key];
		}

		return $value === false ? $default : $value;
	}
}

if (!function_exists('normalize_base_url')) {
	function normalize_base_url($url) {
		$url = trim((string)$url);

		if ($url === '') {
			return '';
		}

		return rtrim($url, '/') . '/';
	}
}

if (!function_exists('detect_base_url')) {
	function detect_base_url() {
		$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? null) == 443);
		$scheme = $isHttps ? 'https://' : 'http://';
		$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
		$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
		$scriptDir = trim(dirname($scriptName), '/');

		if ($scriptDir === '' || $scriptDir === '.') {
			return $scheme . $host . '/';
		}

		$segments = explode('/', $scriptDir);
		$lastSegment = end($segments);

		if (in_array($lastSegment, ['admin', 'api', 'config'], true)) {
			array_pop($segments);
		}

		$path = trim(implode('/', $segments), '/');

		return $scheme . $host . '/' . ($path !== '' ? $path . '/' : '');
	}
}

// Base URL
define('BASE_URL', normalize_base_url(env_value('APP_BASE_URL', detect_base_url())));
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
