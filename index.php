<?php
require_once __DIR__ . '/backend/data/db.php';

$page_title = "Mini Ecommerce | Home";
$meta_description = "Shop premium picks from Product A to Product E at Mini Ecommerce.";
$meta_keywords = "mini ecommerce, buy products, product a, product b";

$product_sql = "SELECT * FROM products ORDER BY id ASC LIMIT 5";
$product_result = mysqli_query($conn, $product_sql);

include __DIR__ . '/partials/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <h1 class="display-5">Premium Everyday Essentials</h1>
                <p class="lead text-secondary">Discover our handpicked collection from Product A to Product E. Simple checkout, fast delivery, great value.</p>
                <a href="/mini_ecommerce_project/products" class="btn btn-primary btn-lg">Start Shopping</a>
            </div>
            <div class="col-lg-6">
                <div class="card hero-card p-4">
                    <h3 class="mb-3">Why Mini Ecommerce</h3>
                    <ul class="mb-0">
                        <li>Curated 5-product collection</li>
                        <li>Secure login and checkout flow</li>
                        <li>Fast search with live AJAX suggestion</li>
                        <li>Responsive premium storefront</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Featured Products</h2>
        <a href="/mini_ecommerce_project/products" class="btn btn-outline-primary">View All</a>
    </div>

    <div class="row g-4 product-grid product-grid-five">
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
    </div>
</section>

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Store",
        "name": "Mini Ecommerce",
        "url": "<?php echo htmlspecialchars($current_scheme . '://' . $current_host . '/mini_ecommerce_project/'); ?>",
        "description": "Mini ecommerce demo store with premium responsive layout."
    }
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>