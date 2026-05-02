<?php
require_once __DIR__ . '/backend/data/db.php';
$page_title = "Mini Ecommerce | Page Not Found";
$meta_description = "The page you requested could not be found.";
$meta_keywords = "404, not found";
include __DIR__ . '/partials/header.php';
?>
<section class="container py-5 text-center">
    <div class="card auth-card p-5">
        <h1 class="display-5">404</h1>
        <p class="lead">Broken link or missing page.</p>
        <a href="/mini_ecommerce_project/home" class="btn btn-primary">Back to Home</a>
    </div>
</section>
<?php include __DIR__ . '/partials/footer.php'; ?>
