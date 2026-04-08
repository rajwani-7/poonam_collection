<?php
require_once '../config/config.php';

unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);

header('Location: login.php');
exit;
?>
