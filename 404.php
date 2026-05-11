<?php
require_once 'db.php';

$pageTitle = '404 Not Found | Mini E-Commerce';
$metaDescription = 'The page you requested was not found.';
$metaKeywords = '404, not found';

http_response_code(404);
require_once 'header.php';
?>
<main class="container py-5">
    <div class="form-box text-center">
        <h1 class="display-5">404</h1>
        <p class="text-muted">This page does not exist or the link is broken.</p>
        <a class="btn btn-brand" href="<?php echo $APP_PATH; ?>/">Back to Homepage</a>
    </div>
</main>
<?php require_once 'footer.php'; ?>