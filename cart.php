<?php
require_once 'config/db.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

foreach ($_SESSION['cart'] as $key => $item) {
    if (!is_array($item) || !isset($item['id'])) {
        unset($_SESSION['cart'][$key]);
    }
}

if (isset($_POST['update_cart'])) {
    if (isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $pid => $qty) {
            $pidInt = (int) $pid;
            $qtyInt = (int) $qty;
            if (isset($_SESSION['cart'][$pidInt])) {
                if ($qtyInt <= 0) {
                    unset($_SESSION['cart'][$pidInt]);
                } else {
                    $_SESSION['cart'][$pidInt]['qty'] = $qtyInt;
                }
            }
        }
    }
}

if (isset($_GET['remove'])) {
    $removeId = (int) $_GET['remove'];
    if (isset($_SESSION['cart'][$removeId])) {
        unset($_SESSION['cart'][$removeId]);
    }
}

$cartPath = (isset($APP_PATH) ? $APP_PATH : '') . '/cart';

$pageTitle = 'Your Cart | Commerza';
$metaDescription = 'Manage your cart and checkout quickly at Commerza.';
$metaKeywords = 'cart, checkout, ecommerce';

require_once 'includes/header.php';

$total = 0;
?>

<main class="container py-4">
    <h1 class="mb-4">Shopping Cart</h1>

    <?php if (count($_SESSION['cart']) === 0) { ?>
        <div class="alert alert-info">Your cart is empty.</div>
        <a href="<?php echo $APP_PATH; ?>/" class="btn btn-brand">Continue Shopping</a>
    <?php } else { ?>
        <form method="post" action="<?php echo $cartPath; ?>">
            <div class="table-responsive form-box">
                <table class="table align-middle cart-table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th width="120">Qty</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $item) {
                            if (!is_array($item)) {
                                continue;
                            }
                            $subtotal = ((float) $item['price']) * ((int) $item['qty']);
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        <div><?php echo htmlspecialchars($item['name']); ?></div>
                                    </div>
                                </td>
                                <td>$<?php echo number_format((float) $item['price'], 2); ?></td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="qty[<?php echo (int) $item['id']; ?>]" value="<?php echo (int) $item['qty']; ?>">
                                </td>
                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                                <td><a class="btn btn-sm btn-outline-danger" href="<?php echo $cartPath; ?>?remove=<?php echo (int) $item['id']; ?>">Remove</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                <button type="submit" name="update_cart" class="btn btn-outline-dark">Update Cart</button>
                <h4 class="mb-0">Total: <span class="product-price">$<?php echo number_format($total, 2); ?></span></h4>
            </div>
        </form>

        <div class="mt-4 d-flex flex-wrap gap-2">
            <a href="<?php echo $APP_PATH; ?>/checkout.php" class="btn btn-buy">Proceed to Buy</a>
            <a href="<?php echo $APP_PATH; ?>/" class="btn btn-brand">Add More Items</a>
        </div>
    <?php } ?>
</main>

<?php require_once 'includes/footer.php'; ?>