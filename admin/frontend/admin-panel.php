<?php
require_once 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . $APP_PATH . '/admin-login');
    exit;
}

$editProduct = null;
if (isset($_GET['edit'])) {
    $editId = (int) $_GET['edit'];
    $resEdit = mysqli_query($conn, "SELECT id, name, slug, description, price, image_url, is_active FROM products WHERE id = $editId LIMIT 1");
    if ($resEdit && mysqli_num_rows($resEdit) > 0) {
        $editProduct = mysqli_fetch_assoc($resEdit);
    }
}

$products = array();
$res = mysqli_query($conn, "SELECT id, name, slug, description, price, image_url, is_active FROM products ORDER BY id ASC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $products[] = $row;
    }
}

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Mini E-Commerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $APP_PATH; ?>/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Admin Product Panel</h2>
            <div>
                <a class="btn btn-outline-dark" href="<?php echo $APP_PATH; ?>/">Store</a>
                <a class="btn btn-danger" href="<?php echo $APP_PATH; ?>/admin/backend/admin-logout.php">Logout</a>
            </div>
        </div>

        <?php if ($msg !== '') { ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
        <?php } ?>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="form-box">
                    <h4 class="mb-3"><?php echo $editProduct ? 'Edit Product' : 'Add Product'; ?></h4>
                    <form method="post" action="<?php echo $APP_PATH; ?>/admin/backend/product_crud.php">
                        <?php if ($editProduct) { ?>
                            <input type="hidden" name="id" value="<?php echo (int) $editProduct['id']; ?>">
                        <?php } ?>

                        <div class="mb-2">
                            <label class="form-label">Name</label>
                            <input class="form-control" name="name" required value="<?php echo $editProduct ? htmlspecialchars($editProduct['name']) : ''; ?>">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Slug</label>
                            <input class="form-control" name="slug" required value="<?php echo $editProduct ? htmlspecialchars($editProduct['slug']) : ''; ?>">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo $editProduct ? htmlspecialchars($editProduct['description']) : ''; ?></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Price</label>
                            <input class="form-control" type="number" step="0.01" name="price" required value="<?php echo $editProduct ? htmlspecialchars($editProduct['price']) : ''; ?>">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Image URL</label>
                            <input class="form-control" name="image_url" required value="<?php echo $editProduct ? htmlspecialchars($editProduct['image_url']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Active</label>
                            <select name="is_active" class="form-select">
                                <option value="1" <?php echo ($editProduct && (int) $editProduct['is_active'] === 1) ? 'selected' : ''; ?>>Yes</option>
                                <option value="0" <?php echo ($editProduct && (int) $editProduct['is_active'] === 0) ? 'selected' : ''; ?>>No</option>
                            </select>
                        </div>

                        <?php if ($editProduct) { ?>
                            <button type="submit" name="update_product" class="btn btn-warning">Update Product</button>
                            <a href="<?php echo $APP_PATH; ?>/admin-panel" class="btn btn-outline-secondary">Cancel</a>
                        <?php } else { ?>
                            <button type="submit" name="add_product" class="btn btn-brand">Add Product</button>
                        <?php } ?>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="form-box">
                    <h4 class="mb-3">All Products</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($products) > 0) { ?>
                                    <?php foreach ($products as $p) { ?>
                                        <tr>
                                            <td><?php echo (int) $p['id']; ?></td>
                                            <td><img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;"></td>
                                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                                            <td>$<?php echo number_format((float) $p['price'], 2); ?></td>
                                            <td><?php echo ((int) $p['is_active'] === 1) ? 'Active' : 'Inactive'; ?></td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary" href="<?php echo $APP_PATH; ?>/admin-panel?edit=<?php echo (int) $p['id']; ?>">Edit</a>
                                                <form method="post" action="<?php echo $APP_PATH; ?>/admin/backend/product_crud.php" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo (int) $p['id']; ?>">
                                                    <button class="btn btn-sm btn-outline-danger" type="submit" name="delete_product" onclick="return confirm('Delete this product?');">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6">No products found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>