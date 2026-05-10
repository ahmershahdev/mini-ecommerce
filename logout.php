<?php
require_once 'db.php';

unset($_SESSION['user_id']);
unset($_SESSION['user_name']);

header('Location: ' . $APP_PATH . '/login');
exit;
