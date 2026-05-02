<?php
require_once __DIR__ . '/backend/data/db.php';

if (!isset($_SESSION['user_id']) || (int)$_SESSION['user_id'] <= 0) {
    header('Location: /mini_ecommerce_project/login?msg=Please+login+first');
    exit;
}

$page_title = "Mini Ecommerce | My Account";
$meta_description = "Manage your account and view recent orders.";
$meta_keywords = "account, orders";

$user_id = (int)$_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE id=" . $user_id . " LIMIT 1";
$user_res = mysqli_query($conn, $user_sql);

if (!$user_res || mysqli_num_rows($user_res) !== 1) {
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    header('Location: /mini_ecommerce_project/login?msg=Please+login+first');
    exit;
}

$user = mysqli_fetch_assoc($user_res);

$order_sql = "SELECT * FROM orders WHERE user_id=" . $user_id . " ORDER BY id DESC";
$order_res = mysqli_query($conn, $order_sql);

include __DIR__ . '/partials/header.php';
?>

<section class="container py-5">
    <div class="card account-card p-4">
        <h1 class="h3">Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        <p class="text-secondary mb-4"><?php echo htmlspecialchars($user['email']); ?></p>

        <h4>Your Orders</h4>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($order_res && mysqli_num_rows($order_res) > 0) { ?>
                        <?php while ($order = mysqli_fetch_assoc($order_res)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_code']); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">No orders yet.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>