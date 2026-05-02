<?php
require_once __DIR__ . '/../data/db.php';

if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password'])) {
    header('Location: /mini_ecommerce_project/signup?msg=Please+fill+all+fields');
    exit;
}

$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$check_sql = "SELECT id FROM users WHERE email='" . $email . "' LIMIT 1";
$check_res = mysqli_query($conn, $check_sql);

if ($check_res && mysqli_num_rows($check_res) > 0) {
    header('Location: /mini_ecommerce_project/signup?msg=Email+already+exists');
    exit;
}

$insert_sql = "INSERT INTO users(name, email, password) VALUES('" . $name . "','" . $email . "','" . $password . "')";
$ok = mysqli_query($conn, $insert_sql);

if ($ok) {
    header('Location: /mini_ecommerce_project/login?msg=Signup+successful,+please+login');
    exit;
}

header('Location: /mini_ecommerce_project/signup?msg=Signup+failed');

