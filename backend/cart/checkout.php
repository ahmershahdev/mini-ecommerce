<?php
require_once __DIR__ . '/../data/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /mini_ecommerce_project/login?msg=Please+login+to+checkout');
    exit;
}

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header('Location: /mini_ecommerce_project/cart');
    exit;
}

$id_list = "";
foreach ($_SESSION['cart'] as $pid => $qty) {
    $pid = (int)$pid;
    if ($pid > 0) {
        if ($id_list === "") {
            $id_list = (string)$pid;
        } else {
            $id_list .= "," . $pid;
        }
    }
}

if ($id_list === "") {
    header('Location: /mini_ecommerce_project/cart');
    exit;
}

$total = 0;
$items = array();
$product_sql = "SELECT * FROM products WHERE id IN (" . $id_list . ")";
$product_res = mysqli_query($conn, $product_sql);
while ($p = mysqli_fetch_assoc($product_res)) {
    $pid = (int)$p['id'];
    $qty = (int)$_SESSION['cart'][$pid];
    $line_total = ((float)$p['price']) * $qty;
    $total += $line_total;
    $p['qty'] = $qty;
    $p['line_total'] = $line_total;
    $items[] = $p;
}

$order_code = 'ORD-' . strtoupper(substr(md5((string)time() . (string)rand(1000, 9999)), 0, 10));
$user_id = (int)$_SESSION['user_id'];

$order_sql = "INSERT INTO orders(user_id, order_code, total_amount, status) VALUES(" . $user_id . ", '" . $order_code . "', " . $total . ", 'Paid')";
$order_ok = mysqli_query($conn, $order_sql);

if ($order_ok) {
    $order_id = mysqli_insert_id($conn);

    foreach ($items as $item) {
        $pid = (int)$item['id'];
        $qty = (int)$item['qty'];
        $price = (float)$item['price'];

        $item_sql = "INSERT INTO order_items(order_id, product_id, qty, price) VALUES(" . $order_id . ", " . $pid . ", " . $qty . ", " . $price . ")";
        mysqli_query($conn, $item_sql);

        $stock_sql = "UPDATE products SET stock = CASE WHEN stock >= " . $qty . " THEN stock - " . $qty . " ELSE stock END WHERE id=" . $pid;
        mysqli_query($conn, $stock_sql);
    }

    $_SESSION['cart'] = array();
    header('Location: /mini_ecommerce_project/account?msg=Order+placed:+' . urlencode($order_code));
    exit;
}

header('Location: /mini_ecommerce_project/cart');

