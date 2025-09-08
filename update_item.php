<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the data sent from the app
      $item_id = $_POST['item_id'];
$item_name = $_POST['item_name'];
$item_price = $_POST['item_price'];
$item_colors = $_POST['item_colors'];
$item_sizes = $_POST['item_sizes'];
$item_category = $_POST['item_category'];
$item_image_url = $_POST['item_image_url'];
        
    

        // Database connection
        $conn = new mysqli("localhost", "u569550465_bogahawatte", "Malshan2003#", "u569550465_fol_clothing");

    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST request


// SQL query to update the item in the database
$sql = "UPDATE items SET name='$item_name', price='$item_price', colors='$item_colors', sizes='$item_sizes', category='$item_category', image_url='$item_image_url' WHERE id=$item_id";

if ($conn->query($sql) === TRUE) {
    echo "Item updated successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}

$conn->close();
?>
