<?php
require_once 'config/db.php';

$slug = '';
if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($conn, $_GET['slug']);
}

$product = null;
if ($slug !== '') {
    $sql = "SELECT id, name, slug, description, price, image_url FROM products WHERE slug = '$slug' AND is_active = 1 LIMIT 1";
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $product = mysqli_fetch_assoc($res);
    }
}

$related = array();
if ($product) {
    $pid = (int) $product['id'];
    $relRes = mysqli_query($conn, "SELECT id, name, slug, price, image_url FROM products WHERE is_active = 1 AND id != $pid ORDER BY id ASC LIMIT 3");
    if ($relRes) {
        while ($row = mysqli_fetch_assoc($relRes)) {
            $related[] = $row;
        }
    }
}

$pageTitle = 'Product Details | Commerza';
$metaDescription = 'View product details and buy from Commerza.';
$metaKeywords = 'product, details, ecommerce';

if ($product) {
    $pageTitle = $product['name'] . ' | Commerza';
    $metaDescription = $product['description'];
}

require_once 'includes/header.php';
?>

<main class="container py-4">
    <?php if ($product) { ?>
        <div class="row g-4 align-items-center">
            <div class="col-md-6">
                <div class="product-card premium-card product-showcase">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-box product-info-card">
                    <h2 class="mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p class="text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                    <div class="h4 product-price mb-3">$<?php echo number_format((float) $product['price'], 2); ?></div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-brand add-to-cart-btn" data-id="<?php echo (int) $product['id']; ?>">Add to Cart</button>
                        <button class="btn btn-buy buy-now-btn" data-id="<?php echo (int) $product['id']; ?>">Buy Now</button>
                        <a href="<?php echo $APP_PATH; ?>/" class="btn btn-outline-dark">Back to Shop</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (count($related) > 0) { ?>
            <section class="related-section mt-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">You May Also Like</h4>
                    <span class="text-muted small">Curated picks for you</span>
                </div>
                <div class="row g-4">
                    <?php foreach ($related as $r) { ?>
                        <div class="col-sm-6 col-lg-4">
                            <div class="product-card premium-card mini-card h-100">
                                <img src="<?php echo htmlspecialchars($r['image_url']); ?>" alt="<?php echo htmlspecialchars($r['name']); ?>">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="product-title mb-2"><?php echo htmlspecialchars($r['name']); ?></h6>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span class="product-price">$<?php echo number_format((float) $r['price'], 2); ?></span>
                                        <a href="<?php echo $APP_PATH; ?>/product/<?php echo urlencode($r['slug']); ?>" class="btn btn-sm btn-outline-dark">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
        <?php } ?>
    <?php } else { ?>
        <div class="alert alert-warning">Product not found.</div>
        <a href="<?php echo $APP_PATH; ?>/" class="btn btn-brand">Go to Shop</a>
    <?php } ?>
</main>

<?php require_once 'includes/footer.php'; ?>