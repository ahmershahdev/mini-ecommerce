<?php
require_once __DIR__ . '/backend/data/db.php';

$page_title = "Mini Ecommerce | Login";
$meta_description = "Login to your Mini Ecommerce account.";
$meta_keywords = "login, ecommerce account";
$message = "";

if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
}

include __DIR__ . '/partials/header.php';
?>

<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card auth-card p-4">
                <h1 class="h3 mb-3">Login</h1>
                <?php if ($message !== "") { ?>
                    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
                <?php } ?>
                <form action="/mini_ecommerce_project/backend/auth/login.php" method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="mt-3 mb-0">No account? <a href="/mini_ecommerce_project/signup">Signup</a></p>
                <p class="small text-secondary mt-2 mb-0">Admin? <a href="/mini_ecommerce_project/admin">Go to admin login</a></p>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
