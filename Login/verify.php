<?php
$conn = new mysqli("localhost","u569550465_bogahawatte","Malshan2003#","u569550465_fol_clothing");
if($conn->connect_error){ die("DB connection failed"); }

$code = $_GET['code'] ?? '';
if(!$code){ die("Invalid verification link"); }

$stmt = $conn->prepare("SELECT * FROM pending_users WHERE verification_code=?");
$stmt->bind_param("s",$code);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows == 0){ die("Invalid or expired link"); }

$user = $result->fetch_assoc();

$stmt2 = $conn->prepare("INSERT INTO users (name,email,phone,address,postal,province,district,password) VALUES (?,?,?,?,?,?,?,?)");
$stmt2->bind_param("ssssssss",$user['name'],$user['email'],$user['phone'],$user['address'],$user['postal'],$user['province'],$user['district'],$user['password']);
$stmt2->execute();

$stmt3 = $conn->prepare("DELETE FROM pending_users WHERE id=?");
$stmt3->bind_param("i",$user['id']);
$stmt3->execute();

echo "<h2 style='text-align:center;margin-top:50px;color:green;'>Your email has been verified! Registration complete.</h2>";
?>
