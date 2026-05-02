<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "syedahmershah";
$pass = "ahmarKH@N2006";
$dbname = "mini_ecommerce";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$site_name = "Mini Ecommerce";
$site_description = "Mini Ecommerce demo project.";

if (isset($_SESSION['user_id'])) {
    $session_user_id = (int)$_SESSION['user_id'];
    if ($session_user_id <= 0) {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
    } else {
        $session_user_sql = "SELECT id FROM users WHERE id=" . $session_user_id . " LIMIT 1";
        $session_user_res = mysqli_query($conn, $session_user_sql);
        if (!$session_user_res || mysqli_num_rows($session_user_res) !== 1) {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
        }
    }
}

$current_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$current_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$current_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$current_url = $current_scheme . '://' . $current_host . $current_uri;
