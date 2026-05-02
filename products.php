<?php
require_once __DIR__ . '/backend/data/db.php';

$page_title = "Mini Ecommerce | Products";
$meta_description = "Browse Product A to Product E and shop quickly.";
$meta_keywords = "products, ecommerce, online shopping";

$where = "";

if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($conn, $_GET['slug']);
    $where = " WHERE slug='" . $slug . "' ";
}

$product_sql = "SELECT * FROM products" . $where . " ORDER BY id ASC";
$product_result = mysqli_query($conn, $product_sql);

include __DIR__ . '/partials/header.php';
?>

<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Shop Products</h1>
        <a href="/mini_ecommerce_project/cart" class="btn btn-outline-dark">Go to Cart</a>
    </div>

    <div class="row g-4 product-grid product-grid-five">
        <?php if ($product_result && mysqli_num_rows($product_result) > 0) { ?>
            <?php while ($product = mysqli_fetch_assoc($product_result)) { ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="card product-card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body d-flex flex-column">
                            <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="text-secondary flex-grow-1"><?php echo htmlspecialchars($product['description']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag">$<?php echo number_format($product['price'], 2); ?></span>
                                <button class="btn btn-sm btn-primary add-to-cart-btn" data-product-id="<?php echo (int)$product['id']; ?>">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-12">
                <div class="alert alert-warning">No products available right now.</div>
            </div>
        <?php } ?>
    </div>
</section>

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "Mini Ecommerce Products",
        "url": "<?php echo htmlspecialchars($current_url); ?>"
    }
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>