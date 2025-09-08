<?php
header('Content-Type: application/json');
include 'db.php';  // database connection

$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Base SQL
$sql = "SELECT id, name, price, colors, sizes, category, image_url FROM items WHERE 1=1";
$params = [];
$types = "";

// Category filter
$allowedCategories = ['Mens', 'Womens', 'Kids'];
if ($category && in_array($category, $allowedCategories)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

// Search filter
if ($search) {
    $sql .= " AND (name LIKE ? OR colors LIKE ?)";
    $searchParam = "%" . $search . "%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "ss";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    // Convert comma-separated colors/sizes into arrays
    $row['colors'] = $row['colors'] ? explode(",", $row['colors']) : [];
    $row['sizes'] = $row['sizes'] ? explode(",", $row['sizes']) : [];

    // Ensure image_url is absolute or prepend folder
    if (!preg_match("~^https?://~", $row['image_url'])) {
        $row['image_url'] =  $row['image_url'];
    }

    $products[] = $row;
}

// Return consistent JSON
echo json_encode([
    "success" => true,
    "products" => $products
]);
?>
