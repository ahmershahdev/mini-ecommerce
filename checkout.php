<?php
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $APP_PATH . '/login');
    exit;
}

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header('Location: ' . $APP_PATH . '/cart');
    exit;
}

$message = '';
$orderCode = '';

if (isset($_POST['place_order'])) {
    $userId = (int) $_SESSION['user_id'];
    $total = 0;

    foreach ($_SESSION['cart'] as $item) {
        $total += ((float) $item['price']) * ((int) $item['qty']);
    }

    $orderCode = 'ORD-' . strtoupper(substr(md5((string) time()), 0, 10));
    $sqlOrder = "INSERT INTO orders (order_code, user_id, total_amount, status) VALUES ('$orderCode', $userId, $total, 'Placed')";
    $okOrder = mysqli_query($conn, $sqlOrder);

    if ($okOrder) {
        $orderId = mysqli_insert_id($conn);

        foreach ($_SESSION['cart'] as $item) {
            $pid = (int) $item['id'];
            $qty = (int) $item['qty'];
            $price = (float) $item['price'];
            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($orderId, $pid, $qty, $price)";
            mysqli_query($conn, $sqlItem);
        }

        $_SESSION['cart'] = array();
        $message = 'Order placed successfully. Your order code: ' . $orderCode;
    } else {
        $message = 'Order failed. Please try again.';
    }
}

$pageTitle = 'Checkout | Mini E-Commerce';
$metaDescription = 'Place your order quickly from Mini E-Commerce checkout.';
$metaKeywords = 'checkout, order, buy';

require_once 'includes/header.php';
?>

<main class="container py-4">
    <div class="form-box">
        <h2 class="mb-3">Checkout</h2>
        <p class="text-muted">You are signed in as user ID: <?php echo (int) $_SESSION['user_id']; ?></p>

        <?php if ($message !== '') { ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
            <a class="btn btn-brand" href="<?php echo $APP_PATH; ?>/">Back to Shop</a>
        <?php } else { ?>
            <form method="post" action="<?php echo $APP_PATH; ?>/checkout.php">
                <button type="submit" name="place_order" class="btn btn-buy">Confirm Purchase</button>
            </form>
        <?php } ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>