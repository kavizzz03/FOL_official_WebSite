<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "u569550465_bogahawatte", "Malshan2003#", "u569550465_fol_clothing");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

$sql = "SELECT * FROM slideshow LIMIT 4";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
