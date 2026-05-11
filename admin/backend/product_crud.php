<?php
require_once __DIR__ . '/../../db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . $APP_PATH . '/admin-login');
    exit;
}

if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $slug = mysqli_real_escape_string($conn, trim($_POST['slug']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price = (float) $_POST['price'];
    $image = mysqli_real_escape_string($conn, trim($_POST['image_url']));
    $active = isset($_POST['is_active']) ? (int) $_POST['is_active'] : 1;

    $sql = "INSERT INTO products (name, slug, description, price, image_url, is_active) VALUES ('$name', '$slug', '$description', $price, '$image', $active)";
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $APP_PATH . '/admin-panel?msg=Product added successfully');
        exit;
    }
    header('Location: ' . $APP_PATH . '/admin-panel?msg=Failed to add product');
    exit;
}

if (isset($_POST['update_product']) && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $slug = mysqli_real_escape_string($conn, trim($_POST['slug']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price = (float) $_POST['price'];
    $image = mysqli_real_escape_string($conn, trim($_POST['image_url']));
    $active = isset($_POST['is_active']) ? (int) $_POST['is_active'] : 1;

    $sql = "UPDATE products SET name='$name', slug='$slug', description='$description', price=$price, image_url='$image', is_active=$active WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $APP_PATH . '/admin-panel?msg=Product updated successfully');
        exit;
    }
    header('Location: ' . $APP_PATH . '/admin-panel?msg=Failed to update product');
    exit;
}

if (isset($_POST['delete_product']) && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $sql = "DELETE FROM products WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header('Location: ' . $APP_PATH . '/admin-panel?msg=Product deleted successfully');
        exit;
    }
    header('Location: ' . $APP_PATH . '/admin-panel?msg=Failed to delete product');
    exit;
}

header('Location: ' . $APP_PATH . '/admin-panel');
exit;
