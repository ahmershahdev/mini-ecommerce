<?php
require_once 'db.php';

$pageTitle = 'Mini E-Commerce | Premium Shopping';
$metaDescription = 'Buy Product A to Product E from mini ecommerce storefront.';
$metaKeywords = 'product a, product b, product c, product d, product e, online store';

$products = array();
$sql = "SELECT id, name, slug, description, price, image_url FROM products WHERE is_active = 1 ORDER BY id ASC";
$res = mysqli_query($conn, $sql);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $products[] = $row;
    }
}

$firstRow = array_slice($products, 0, 3);
$secondRow = array_slice($products, 3);

$baseUrl = isset($APP_URL) ? $APP_URL : '';

$itemList = array();
$position = 1;
foreach ($products as $p) {
    $itemList[] = array(
        '@type' => 'ListItem',
        'position' => $position,
        'url' => $baseUrl . '/product/' . $p['slug'],
        'name' => $p['name']
    );
    $position++;
}

$extraHead = '<script type="application/ld+json">' . json_encode(array(
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'Mini E-Commerce',
    'url' => $baseUrl,
    'potentialAction' => array(
        '@type' => 'SearchAction',
        'target' => $baseUrl . '/?q={search_term_string}',
        'query-input' => 'required name=search_term_string'
    )
), JSON_UNESCAPED_SLASHES) . '</script>';

$extraHead .= '<script type="application/ld+json">' . json_encode(array(
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'itemListElement' => $itemList
), JSON_UNESCAPED_SLASHES) . '</script>';

require_once 'includes/header.php';
?>

<main class="container pb-5">
    <section class="hero">
        <h1>Elegant Shopping, Simplified</h1>
        <p class="mb-4">Explore our curated lineup from Product A to Product E. Fast cart flow, smooth animations, responsive premium design and easy checkout.</p>
        <a href="#productGrid" class="btn btn-brand px-4">Start Shopping</a>
    </section>

    <section id="productGrid" class="product-grid">
        <?php if (count($products) > 0) { ?>
            <div class="row g-4">
                <?php foreach ($firstRow as $product) { ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="product-card premium-card h-100">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="small text-muted mb-2"><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="mt-auto d-flex justify-content-between align-items-center gap-2">
                                    <span class="product-price">PKR<?php echo number_format((float) $product['price'], 2); ?></span>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo $APP_PATH; ?>/product/<?php echo urlencode($product['slug']); ?>" class="btn btn-sm btn-outline-dark">View</a>
                                        <button class="btn btn-sm btn-brand add-to-cart-btn" data-id="<?php echo (int) $product['id']; ?>">Add to Cart</button>
                                        <button class="btn btn-sm btn-buy buy-now-btn" data-id="<?php echo (int) $product['id']; ?>">Buy</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <?php if (count($secondRow) > 0) { ?>
                <div class="row g-4 justify-content-center mt-1">
                    <?php foreach ($secondRow as $product) { ?>
                        <div class="col-sm-6 col-lg-4">
                            <div class="product-card premium-card h-100">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="small text-muted mb-2"><?php echo htmlspecialchars($product['description']); ?></p>
                                    <div class="mt-auto d-flex justify-content-between align-items-center gap-2">
                                        <span class="product-price">PKR<?php echo number_format((float) $product['price'], 2); ?></span>
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo $APP_PATH; ?>/product/<?php echo urlencode($product['slug']); ?>" class="btn btn-sm btn-outline-dark">View</a>
                                            <button class="btn btn-sm btn-brand add-to-cart-btn" data-id="<?php echo (int) $product['id']; ?>">Add to Cart</button>
                                            <button class="btn btn-sm btn-buy buy-now-btn" data-id="<?php echo (int) $product['id']; ?>">Buy</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-12">
                <div class="alert alert-warning">No products found. Please import the SQL file first.</div>
            </div>
        <?php } ?>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>