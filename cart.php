<?php
require_once __DIR__ . '/backend/data/db.php';

$page_title = "Mini Ecommerce | Cart";
$meta_description = "View your cart and complete your purchase.";
$meta_keywords = "cart, checkout, ecommerce";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$total = 0;
$items = array();

if (count($_SESSION['cart']) > 0) {
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

    if ($id_list !== "") {
        $sql = "SELECT * FROM products WHERE id IN (" . $id_list . ")";
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $pid = (int)$row['id'];
            $row['qty'] = (int)$_SESSION['cart'][$pid];
            $row['line_total'] = ((float)$row['price']) * $row['qty'];
            $total += $row['line_total'];
            $items[] = $row;
        }
    }
}

include __DIR__ . '/partials/header.php';
?>

<section class="container py-5">
    <h1 class="mb-4">Shopping Cart</h1>
    <div class="card cart-card p-3 p-md-4">
        <?php if (count($items) === 0) { ?>
            <div class="alert alert-info mb-0">Your cart is empty. <a href="/mini_ecommerce_project/products">Start shopping</a>.</div>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) { ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" width="56" height="56" style="object-fit:cover;border-radius:8px;" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <form action="/mini_ecommerce_project/backend/cart/update-cart.php" method="post" class="d-flex gap-2">
                                        <input type="hidden" name="product_id" value="<?php echo (int)$item['id']; ?>">
                                        <input type="number" min="1" name="qty" value="<?php echo (int)$item['qty']; ?>" class="form-control" style="width:90px;">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                    </form>
                                </td>
                                <td>$<?php echo number_format($item['line_total'], 2); ?></td>
                                <td>
                                    <form action="/mini_ecommerce_project/backend/cart/remove-from-cart.php" method="post">
                                        <input type="hidden" name="product_id" value="<?php echo (int)$item['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3 gap-3">
                <h4 class="mb-0">Grand Total: <span class="text-primary">$<?php echo number_format($total, 2); ?></span></h4>
                <form action="/mini_ecommerce_project/backend/cart/checkout.php" method="post" class="mb-0">
                    <button type="submit" class="btn btn-success btn-lg">Buy Now</button>
                </form>
            </div>
        <?php } ?>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
