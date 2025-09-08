<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Colombo');

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email']);
$password = trim($data['password']);

$conn = new mysqli("localhost","u569550465_bogahawatte","Malshan2003#","u569550465_fol_clothing");
if ($conn->connect_error) { die(json_encode(['success'=>false,'message'=>'DB Connection Error'])); }

$stmt = $conn->prepare("SELECT id,password,otp_verified FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $hash, $otp_verified);
if($stmt->num_rows == 0){
    echo json_encode(['success'=>false,'message'=>'Email not registered']);
    exit;
}
$stmt->fetch();
$stmt->close();

if(!$otp_verified){
    echo json_encode(['success'=>false,'message'=>'Email not verified. Please check your email.']);
    exit;
}

if(password_verify($password,$hash)){
    session_start();
    $_SESSION['user_id'] = $id;
    $_SESSION['email'] = $email;
    echo json_encode(['success'=>true,'message'=>'Login successful!']);
}else{
    echo json_encode(['success'=>false,'message'=>'Incorrect password']);
}
$conn->close();
?>
