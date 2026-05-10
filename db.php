<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : '';
$projectRoot = realpath(__DIR__ . '/..');
$APP_PATH = '';

if ($docRoot && $projectRoot && strpos($projectRoot, $docRoot) === 0) {
    $APP_PATH = str_replace('\\', '/', substr($projectRoot, strlen($docRoot)));
}

if ($APP_PATH === '/' || $APP_PATH === '\\') {
    $APP_PATH = '';
}

$APP_PATH = rtrim($APP_PATH, '/');

$APP_SCHEME = 'http';
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
    $APP_SCHEME = 'https';
}
$APP_HOST = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$APP_URL = $APP_SCHEME . '://' . $APP_HOST . $APP_PATH;

$host = 'localhost';
$user = 'syedahmershah';
$pass = 'ahmarKH@N2006';
$dbname = 'mini_ecommerce';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

$colCheck = mysqli_query($conn, "SHOW COLUMNS FROM products LIKE 'image_url'");
if ($colCheck && mysqli_num_rows($colCheck) === 0) {
    mysqli_query($conn, "ALTER TABLE products ADD COLUMN image_url VARCHAR(300) NOT NULL DEFAULT ''");
}
