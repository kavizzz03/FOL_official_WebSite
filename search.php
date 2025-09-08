<?php
$host = "localhost";
$user = "u569550465_bogahawatte";
$pass = "Malshan2003#";
$db   = "u569550465_fol_clothing";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

$search = $_GET['query'] ?? '';

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql = "SELECT id, name, price, image_url FROM items 
            WHERE name LIKE '%$search%' OR category LIKE '%$search%' LIMIT 6";
    $result = $conn->query($sql);

    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    echo json_encode($items);
}
$conn->close();
?>
