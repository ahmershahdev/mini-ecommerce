<?php
require_once __DIR__ . '/../data/db.php';

if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
}

if (isset($_SESSION['admin_id'])) {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_name']);
}

header('Location: /mini_ecommerce_project/home');

