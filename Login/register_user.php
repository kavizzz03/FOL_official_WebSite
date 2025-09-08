<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Colombo');

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data['name']);
$email = trim($data['email']);
$phone = trim($data['phone']);
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$otp = rand(100000, 999999);
$created_at = date('Y-m-d H:i:s');

$conn = new mysqli("localhost","u569550465_bogahawatte","Malshan2003#","u569550465_fol_clothing");
if ($conn->connect_error) { die(json_encode(['success'=>false,'message'=>'DB Connection Error'])); }

// Check if email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success'=>false,'message'=>'Email already registered']);
    exit;
}
$stmt->close();

// Insert user as unverified
$stmt = $conn->prepare("INSERT INTO users (name,email,phone,password,otp_verified,otp_code,created_at) VALUES (?,?,?,?,0,?,?)");
$stmt->bind_param("ssssss",$name,$email,$phone,$password,$otp,$created_at);
if($stmt->execute()){
    // Send OTP Email (user-friendly)
    $subject = "Welcome to FOL Clothing! Verify Your Email";
    $message = "
    <html>
    <head>
      <title>Email Verification</title>
    </head>
    <body style='font-family: Poppins,sans-serif; color:#1f2937;'>
      <div style='max-width:600px;margin:auto;padding:30px;background:#f1f5f9;border-radius:15px;text-align:center;'>
        <h2 style='color:#16a34a;'>Hello, $name!</h2>
        <p>Thanks for registering at <strong>FOL Clothing</strong>.</p>
        <p>Your OTP code is:</p>
        <h1 style='color:#16a34a;'>$otp</h1>
        <p>Or click the button below to verify your email:</p>
        <a href='http://yourdomain.com/verify_email.php?email=$email&otp=$otp' 
           style='display:inline-block;margin-top:20px;padding:15px 25px;background:#16a34a;color:#fff;text-decoration:none;border-radius:10px;font-weight:600;'>Verify Email</a>
        <p style='margin-top:20px;color:#555;'>If you did not register, please ignore this email.</p>
      </div>
    </body>
    </html>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@folclothing.com" . "\r\n";

    mail($email,$subject,$message,$headers);

    echo json_encode(['success'=>true,'message'=>'Registration successful! OTP sent to your email.']);
}else{
    echo json_encode(['success'=>false,'message'=>'Registration failed']);
}
$stmt->close();
$conn->close();
?>
