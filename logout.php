<?php
require_once 'config/config.php';

unset($_SESSION['customer_logged_in']);
unset($_SESSION['customer_id']);
unset($_SESSION['customer_name']);
unset($_SESSION['customer_email']);

header('Location: index.html');
exit;
?>