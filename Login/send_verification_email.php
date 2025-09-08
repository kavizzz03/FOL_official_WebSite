<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
if(!$data || !isset($data['name'], $data['email'], $data['password'])) {
    echo json_encode(['success'=>false,'message'=>'Invalid input']);
    exit;
}

// Assign variables
$name = $data['name'];
$email = $data['email'];
$phone = $data['phone'] ?? '';
$address = $data['address'] ?? '';
$postal = $data['postal'] ?? '';
$province = $data['province'] ?? '';
$district = $data['district'] ?? '';
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$code = bin2hex(random_bytes(16));

// Connect to database
$conn = new mysqli("localhost","u569550465_bogahawatte","Malshan2003#","u569550465_fol_clothing");
if($conn->connect_error){
    echo json_encode(['success'=>false,'message'=>'DB connection failed: '.$conn->connect_error]);
    exit;
}

// Prepare statement to insert into pending_users
$stmt = $conn->prepare("INSERT INTO pending_users (name,email,phone,address,postal,province,district,password,verification_code) VALUES (?,?,?,?,?,?,?,?,?)");
if(!$stmt){
    echo json_encode(['success'=>false,'message'=>'Prepare failed: '.$conn->error]);
    exit;
}
$stmt->bind_param("sssssssss",$name,$email,$phone,$address,$postal,$province,$district,$password,$code);

if(!$stmt->execute()){
    echo json_encode(['success'=>false,'message'=>'Failed to save user: '.$stmt->error]);
    exit;
}

// Prepare email
$subject = "Verify your email - FOL Clothing";
$verificationLink = "https://modaloku.cpsharetxt.com/Login/verify.php?code=$code";
$message = "
<html>
<head><title>Verify Email</title></head>
<body>
<h2>Hello $name,</h2>
<p>Thanks for registering at <b>FOL Clothing</b>!</p>
<p>Please click the button below to verify your email and complete your registration:</p>
<a href='$verificationLink' style='display:inline-block;padding:10px 20px;background:green;color:#fff;border-radius:5px;text-decoration:none;'>Verify Email</a>
<p>If you did not register, please ignore this email.</p>
</body>
</html>
";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: FOL Clothing <noreply@modaloku.cpsharetxt.com>\r\n";

// Send email using Hostinger mail
if(mail($email, $subject, $message, $headers, "-fnoreply@modaloku.cpsharetxt.com")){
    echo json_encode(['success'=>true,'message'=>'Verification email sent']);
} else {
    echo json_encode(['success'=>false,'message'=>'Failed to send verification email. Make sure noreply@modaloku.cpsharetxt.com exists on your Hostinger account']);
}
?>
