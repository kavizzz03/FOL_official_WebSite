<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$conn = new mysqli(
    "localhost",
    "u569550465_bogahawatte",
    "Malshan2003#",
    "u569550465_fol_clothing"
);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
} // 🔗 include connection

// Fetch products marked as top sellers
$sql = "SELECT id, name, price, image, colors, sizes 
        FROM products 
        WHERE is_top_seller = 1 
        ORDER BY id DESC";

$result = $conn->query($sql);

$topSellers = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['colors'] = !empty($row['colors']) ? explode(",", $row['colors']) : [];
        $row['sizes']  = !empty($row['sizes'])  ? explode(",", $row['sizes']) : [];
        $topSellers[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "products" => $topSellers
]);

$conn->close();
?>
