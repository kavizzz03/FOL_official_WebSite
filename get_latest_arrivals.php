<?php
$host = "localhost";
$user = "u569550465_bogahawatte";        // Your DB username
$pass = "Malshan2003#";    // Your DB password
$db   = "u569550465_fol_clothing";  // Your DB name

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, price, image_url, colors, sizes FROM items ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($sql);

$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['colors'] = $row['colors'] ? explode(",", $row['colors']) : [];
        $row['sizes']  = $row['sizes'] ? explode(",", $row['sizes']) : [];
        $products[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "products" => $products
]);
$conn->close();
?>