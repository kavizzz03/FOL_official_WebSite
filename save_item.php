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
    $name = $_POST['name'];
    $price = $_POST['price'];
    $colors = $_POST['colors'];
    $sizes = $_POST['sizes'];
    $category = $_POST['category'];
    $image = $_POST['image'];

    if (empty($name) || empty($price) || empty($colors) || empty($sizes) || empty($image)) {
        echo "All fields are required!";
        exit();
    }

    $imageData = base64_decode($image);
    $imageName = "IMG_" . time() . ".jpg";
    $imagePath = "uploads/" . $imageName;

    if (file_put_contents($imagePath, $imageData)) {
        $sql = "INSERT INTO items (name, price, colors, sizes, category, image_url) 
                VALUES ('$name', '$price', '$colors', '$sizes', '$category', '$imagePath')";

        if ($conn->query($sql) === TRUE) {
            echo "Item saved successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error uploading image.";
    }
}

$conn->close();
?>
