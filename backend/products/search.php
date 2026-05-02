<?php
require_once __DIR__ . '/../data/db.php';
header('Content-Type: application/json');

$q = "";
if (isset($_GET['q'])) {
    $q = mysqli_real_escape_string($conn, $_GET['q']);
}

if ($q === "") {
    echo json_encode(array('status' => 'ok', 'items' => array()));
    exit;
}

$sql = "SELECT id, name, slug FROM products WHERE name LIKE '%" . $q . "%' OR description LIKE '%" . $q . "%' ORDER BY id ASC LIMIT 6";
$res = mysqli_query($conn, $sql);

$items = array();
while ($row = mysqli_fetch_assoc($res)) {
    $items[] = $row;
}

echo json_encode(array('status' => 'ok', 'items' => $items));

