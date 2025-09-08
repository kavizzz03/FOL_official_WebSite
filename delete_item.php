<?php
$servername = "localhost";
$username = "u569550465_bogahawatte";
$password = "Malshan2003#";
$database = "u569550465_fol_clothing";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    // Delete the item
    $sql = "DELETE FROM items WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Item deleted successfully!";
    } else {
        echo "Error deleting item: " . $conn->error;
    }
}

$conn->close();
?>
