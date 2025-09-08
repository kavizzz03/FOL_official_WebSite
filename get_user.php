<?php
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';

if(!$email){
    echo json_encode(['success'=>false,'message'=>'Email required']);
    exit;
}

$conn = new mysqli("localhost","u569550465_bogahawatte","Malshan2003#","u569550465_fol_clothing");
if($conn->connect_error){
    echo json_encode(['success'=>false,'message'=>'DB connection failed']);
    exit;
}


// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);
$email = isset($data['email']) ? $conn->real_escape_string($data['email']) : '';

if(empty($email)){
    echo json_encode(['success'=>false, 'error'=>'Email is required']);
    exit;
}

// Query user
$sql = "SELECT email, phone, address, postal, province, district FROM users WHERE email='$email' LIMIT 1";
$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    $user = $result->fetch_assoc();
    echo json_encode(['success'=>true, 'user'=>$user]);
} else {
    echo json_encode(['success'=>false, 'error'=>'User not found']);
}

$conn->close();