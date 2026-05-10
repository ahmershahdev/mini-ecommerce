<?php
include 'db.php';
header('Content-Type: application/json');

if (!isset($_POST['product_id'])) {
    echo json_encode(array('status' => 'error', 'message' => 'Missing product id'));
    exit;
}

$productId = (int) $_POST['product_id'];
$qty = 1;
if (isset($_POST['qty'])) {
    $qty = (int) $_POST['qty'];
    if ($qty < 1) {
        $qty = 1;
    }
}

$sql = "SELECT id, name, slug, price, image_url FROM products WHERE id = $productId AND is_active = 1 LIMIT 1";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    echo json_encode(array('status' => 'error', 'message' => 'Product not found'));
    exit;
}
$product = mysqli_fetch_assoc($res);

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

foreach ($_SESSION['cart'] as $key => $item) {
    if (!is_array($item)) {
        unset($_SESSION['cart'][$key]);
    }
}

if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['qty'] = $_SESSION['cart'][$productId]['qty'] + $qty;
} else {
    $_SESSION['cart'][$productId] = array(
        'id' => (int) $product['id'],
        'name' => $product['name'],
        'slug' => $product['slug'],
        'price' => (float) $product['price'],
        'image_url' => $product['image_url'],
        'qty' => $qty
    );
}

$cartCount = 0;
foreach ($_SESSION['cart'] as $item) {
    if (is_array($item) && isset($item['qty'])) {
        $cartCount += (int) $item['qty'];
    }
}

echo json_encode(array(
    'status' => 'ok',
    'cart_count' => $cartCount
));
exit;
