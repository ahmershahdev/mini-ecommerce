<?php
require_once __DIR__ . '/../data/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (!isset($_POST['product_id']) || !isset($_POST['qty'])) {
    header('Location: /mini_ecommerce_project/cart');
    exit;
}

$product_id = (int)$_POST['product_id'];
$qty = (int)$_POST['qty'];

if ($qty < 1) {
    $qty = 1;
}

$_SESSION['cart'][$product_id] = $qty;
header('Location: /mini_ecommerce_project/cart');

