<?php
require_once __DIR__ . '/../data/db.php';

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    header('Location: /mini_ecommerce_project/login?msg=Please+fill+all+fields');
    exit;
}

$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$sql = "SELECT * FROM users WHERE email='" . $email . "' LIMIT 1";
$res = mysqli_query($conn, $sql);

if ($res && mysqli_num_rows($res) === 1) {
    $user = mysqli_fetch_assoc($res);
    if ($password === $user['password']) {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        header('Location: /mini_ecommerce_project/account');
        exit;
    }
}

header('Location: /mini_ecommerce_project/login?msg=Invalid+email+or+password');

