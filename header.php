<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Mini E-Commerce';
}
if (!isset($metaDescription)) {
    $metaDescription = 'Mini E-Commerce Store with premium responsive storefront.';
}
if (!isset($metaKeywords)) {
    $metaKeywords = 'ecommerce, shopping, cart, admin panel';
}
if (!isset($extraHead)) {
    $extraHead = '';
}

$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $row) {
        if (is_array($row) && isset($row['qty'])) {
            $cartCount += (int) $row['qty'];
        }
    }
}

$scheme = 'https';
$host = 'miniecommerce.ahmershah.dev';
$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$canonicalUrl = $scheme . '://' . $host . strtok($uri, '?');
$appPathSafe = isset($APP_PATH) ? $APP_PATH : '';
$siteName = 'Mini E-Commerce';
$baseUrl = $scheme . '://' . $host . $appPathSafe;
$siteJsonLd = json_encode(array(
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $siteName,
    'url' => $baseUrl
), JSON_UNESCAPED_SLASHES);
$pageJsonLd = json_encode(array(
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => $pageTitle,
    'description' => $metaDescription,
    'url' => $canonicalUrl
), JSON_UNESCAPED_SLASHES);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($metaKeywords); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonicalUrl); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($siteName); ?>">
    <meta property="og:locale" content="en_US">
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonicalUrl); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link href="<?php echo $appPathSafe; ?>/assets/css/style.css" rel="stylesheet">
    <script>
        var APP_BASE = "<?php echo htmlspecialchars($appPathSafe, ENT_QUOTES); ?>";
    </script>
    <script type="application/ld+json">
        <?php echo $siteJsonLd; ?>
    </script>
    <script type="application/ld+json">
        <?php echo $pageJsonLd; ?>
    </script>
    <?php echo $extraHead; ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark top-nav sticky-top">
        <div class="container">
            <a class="navbar-brand brand-mark" href="<?php echo $appPathSafe; ?>/">Mini E-Commerce</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <div class="search-wrap me-lg-3 mb-2 mb-lg-0">
                    <input type="text" id="globalSearch" class="form-control search-input" placeholder="Search products..." autocomplete="off">
                    <div id="searchSuggestionBox" class="suggestion-box d-none"></div>
                </div>
                <ul class="navbar-nav ms-lg-3">
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $appPathSafe; ?>/logout">Logout</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $appPathSafe; ?>/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?php echo $appPathSafe; ?>/signup">Signup</a></li>
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link cart-link" href="<?php echo $appPathSafe; ?>/cart">Cart <span id="cartCountBadge" class="badge rounded-pill text-bg-warning"><?php echo (int) $cartCount; ?></span></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>