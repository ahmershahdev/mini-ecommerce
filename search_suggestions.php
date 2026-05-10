<?php
include 'db.php';
header('Content-Type: application/json');

if (!isset($conn)) {
    echo json_encode(array());
    exit;
}

$q = '';
if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
}

if ($q === '') {
    echo json_encode(array());
    exit;
}

$qEsc = mysqli_real_escape_string($conn, $q);
$sql = "SELECT slug, name, price FROM products WHERE is_active = 1 AND (name LIKE '%$qEsc%' OR description LIKE '%$qEsc%') ORDER BY name ASC LIMIT 8";
$res = mysqli_query($conn, $sql);

$list = array();
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $list[] = array(
            'slug' => $row['slug'],
            'name' => $row['name'],
            'price' => number_format((float) $row['price'], 2)
        );
    }
}

echo json_encode($list);
exit;
