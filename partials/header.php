<?php
if (!isset($page_title)) {
    $page_title = "Mini Ecommerce";
}
if (!isset($meta_description)) {
    $meta_description = "Buy quality products online at Mini Ecommerce.";
}
if (!isset($meta_keywords)) {
    $meta_keywords = "ecommerce, shopping, products";
}
if (!isset($current_url)) {
    $current_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $current_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    $current_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $current_url = $current_scheme . '://' . $current_host . $current_uri;
}
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += (int)$qty;
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($current_url); ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_description); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($current_url); ?>">

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/mini_ecommerce_project/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark commerza-nav sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/mini_ecommerce_project/home">
                <span id="brandTicker">Mini Ecommerce</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/mini_ecommerce_project/home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/mini_ecommerce_project/products">Shop</a></li>
                    <li class="nav-item"><a class="nav-link" href="/mini_ecommerce_project/cart">Cart</a></li>
                </ul>

                <div class="commerza-search-wrap me-lg-3">
                    <input type="text" class="form-control" id="productSearchInput" placeholder="Search products..." autocomplete="off">
                    <div id="searchSuggestionBox" class="search-suggestion-box d-none"></div>
                </div>

                <ul class="navbar-nav align-items-lg-center gap-lg-2">
                    <?php if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0) { ?>
                        <li class="nav-item"><a class="nav-link" href="/mini_ecommerce_project/account">My Account</a></li>
                        <li class="nav-item"><a class="nav-link" href="/mini_ecommerce_project/backend/auth/logout.php">Logout</a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a class="nav-link" href="/mini_ecommerce_project/login">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="/mini_ecommerce_project/signup">Signup</a></li>
                    <?php } ?>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-sm btn-light position-relative" href="/mini_ecommerce_project/cart" id="cartAnchor">
                            <i class="bi bi-bag-check"></i> Cart
                            <span class="badge text-bg-danger rounded-pill" id="cartBadge"><?php echo (int)$cart_count; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="page-main">
