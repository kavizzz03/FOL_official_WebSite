<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// DB Connection
$conn = new mysqli("localhost", "u569550465_bogahawatte", "Malshan2003#", "u569550465_fol_clothing");
if ($conn->connect_error) {
    die(json_encode(["success"=>false,"message"=>"DB Connection failed: ".$conn->connect_error]));
}

// Fetch top sellers
$sql = "SELECT id, name, price, image_url, colors, sizes 
        FROM items 
        WHERE is_top_seller=1 
        ORDER BY id DESC";

$result = $conn->query($sql);
$topSellers = [];

if($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $row['colors'] = $row['colors'] ? explode(",", $row['colors']) : [];
        $row['sizes'] = $row['sizes'] ? explode(",", $row['sizes']) : [];
        $topSellers[] = $row;
    }
}

echo json_encode(["success"=>true,"products"=>$topSellers]);
$conn->close();
?>
