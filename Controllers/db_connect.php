<?php
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
}
?>
