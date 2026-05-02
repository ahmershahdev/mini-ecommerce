<?php
require_once __DIR__ . '/../../backend/data/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: /mini_ecommerce_project/admin?msg=Please+login+first');
    exit;
}

$msg = "";
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
}

if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $image = mysqli_real_escape_string($conn, $_POST['image']);

    $sql = "INSERT INTO products(name, slug, description, price, stock, image) VALUES('" . $name . "','" . $slug . "','" . $description . "'," . $price . "," . $stock . ",'" . $image . "')";
    mysqli_query($conn, $sql);
    header('Location: /mini_ecommerce_project/admin-panel?msg=Product+added');
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $image = mysqli_real_escape_string($conn, $_POST['image']);

    $sql = "UPDATE products SET name='" . $name . "', slug='" . $slug . "', description='" . $description . "', price=" . $price . ", stock=" . $stock . ", image='" . $image . "' WHERE id=" . $id;
    mysqli_query($conn, $sql);
    header('Location: /mini_ecommerce_project/admin-panel?msg=Product+updated');
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)$_POST['id'];
    $sql = "DELETE FROM products WHERE id=" . $id;
    mysqli_query($conn, $sql);
    header('Location: /mini_ecommerce_project/admin-panel?msg=Product+deleted');
    exit;
}

$product_res = mysqli_query($conn, "SELECT * FROM products ORDER BY id ASC");

$page_title = "Mini Ecommerce | Admin Panel";
$meta_description = "Manage product catalog.";
$meta_keywords = "admin panel";
include __DIR__ . '/../../partials/header.php';
?>

<section class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Admin Panel</h1>
        <a class="btn btn-outline-danger btn-sm" href="/mini_ecommerce_project/backend/auth/logout.php">Logout</a>
    </div>

    <?php if ($msg !== "") { ?><div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div><?php } ?>

    <div class="card admin-card p-4 mb-4">
        <h4>Add Product</h4>
        <form method="post" class="row g-2">
            <input type="hidden" name="action" value="add">
            <div class="col-md-4"><input type="text" name="name" class="form-control" placeholder="Product name" required></div>
            <div class="col-md-3"><input type="text" name="slug" class="form-control" placeholder="product-slug" required></div>
            <div class="col-md-5"><input type="text" name="description" class="form-control" placeholder="Description" required></div>
            <div class="col-md-2"><input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required></div>
            <div class="col-md-2"><input type="number" name="stock" class="form-control" placeholder="Stock" required></div>
            <div class="col-md-6"><input type="text" name="image" class="form-control" placeholder="Image URL" required></div>
            <div class="col-md-2"><button class="btn btn-primary w-100" type="submit">Add</button></div>
        </form>
    </div>

    <div class="card admin-card p-4">
        <h4 class="mb-3">Manage Products</h4>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = mysqli_fetch_assoc($product_res)) { ?>
                        <tr>
                            <form method="post">
                                <td>
                                    <?php echo (int)$p['id']; ?>
                                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
                                </td>
                                <td><input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($p['name']); ?>"></td>
                                <td><input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($p['slug']); ?>"></td>
                                <td><input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($p['description']); ?>"></td>
                                <td><input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($p['price']); ?>"></td>
                                <td><input type="number" name="stock" class="form-control" value="<?php echo htmlspecialchars($p['stock']); ?>"></td>
                                <td><input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($p['image']); ?>"></td>
                                <td class="d-flex gap-2">
                                    <button type="submit" name="action" value="update" class="btn btn-sm btn-outline-primary">Save</button>
                                    <button type="submit" name="action" value="delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')">Delete</button>
                                </td>
                            </form>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
