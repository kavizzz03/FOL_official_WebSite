<?php
$servername = "localhost";
$username = "u569550465_bogahawatte";
$password = "Malshan2003#";
$database = "u569550465_fol_clothing";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM items ORDER BY id DESC";
$result = $conn->query($sql);

$items = array();

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode($items);
$conn->close();
?>
