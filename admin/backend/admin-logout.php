<?php
require_once '../../config/db.php';

unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

header('Location: ' . $APP_PATH . '/admin-login');
exit;
