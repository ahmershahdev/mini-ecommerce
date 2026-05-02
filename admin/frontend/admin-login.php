<?php
require_once __DIR__ . '/../../backend/data/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: /mini_ecommerce_project/admin-panel');
    exit;
}

$msg = "";
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin_users WHERE email='" . $email . "' LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) === 1) {
        $admin = mysqli_fetch_assoc($res);
        if ($password === $admin['password']) {
            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: /mini_ecommerce_project/admin-panel');
            exit;
        } else {
            $msg = "Invalid password";
        }
    } else {
        $msg = "Admin account not found";
    }
}

$page_title = "Mini Ecommerce | Admin Login";
$meta_description = "Admin panel login.";
$meta_keywords = "admin login";
include __DIR__ . '/../../partials/header.php';
?>

<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card admin-card p-4">
                <h1 class="h3 mb-3">Admin Login</h1>
                <?php if ($msg !== "") { ?><div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div><?php } ?>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login to Admin</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
