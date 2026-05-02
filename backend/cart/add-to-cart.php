<?php
require_once __DIR__ . '/../data/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (!isset($_POST['product_id'])) {
    echo json_encode(array('status' => 'error', 'message' => 'product_id missing'));
    exit;
}

$product_id = (int)$_POST['product_id'];
$qty = 1;
if (isset($_POST['qty'])) {
    $qty = (int)$_POST['qty'];
    if ($qty < 1) {
        $qty = 1;
    }
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $qty;
} else {
    $_SESSION['cart'][$product_id] = $qty;
}

$cart_count = 0;
foreach ($_SESSION['cart'] as $q) {
    $cart_count += (int)$q;
}

echo json_encode(array('status' => 'ok', 'cart_count' => $cart_count));

