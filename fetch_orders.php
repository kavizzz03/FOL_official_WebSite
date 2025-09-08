<?php
header('Content-Type: application/json');

$host = 'localhost';
$db = 'u569550465_fol_clothing';
$user = 'u569550465_bogahawatte';
$pass = 'Malshan2003#';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$keyword = $_POST['keyword'] ?? '';

$sql = "SELECT * FROM orders WHERE email = ? OR contact = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
  $items = json_decode($row['items'], true) ?? [];
  $grandTotal = 0;

  // Calculate total per item and grand total
  foreach ($items as &$item) {
    if (!isset($item['quantity'])) $item['quantity'] = 1; // default 1
    $item['total'] = $item['price'] * $item['quantity'];
    $grandTotal += $item['total'];
  }

  $row['items'] = $items;
  $row['total'] = $grandTotal;
  $orders[] = $row;
}

echo json_encode(['success' => true, 'orders' => $orders]);

$conn->close();
?>
