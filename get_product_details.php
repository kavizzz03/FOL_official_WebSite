<?php
$host = "localhost";
$user = "u569550465_bogahawatte";        // Your DB username
$pass = "Malshan2003#";    // Your DB password
$db   = "u569550465_fol_clothing";  // Your DB name

// Get product ID from query string
$productId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Connect to DB
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product details by ID
$sql = "SELECT id, name, price, colors, sizes, category, image_url FROM items WHERE id = $productId";
$result = $conn->query($sql);

$product = [];
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
}

header('Content-Type: application/json');
echo json_encode($product);

$conn->close();
?>
