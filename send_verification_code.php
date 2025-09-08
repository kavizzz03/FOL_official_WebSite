<?php
// send_verification_code.php

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$code = $data['code'];

// Set up the email content (HTML version)
$subject = "FOL Clothing - Email Verification";
$message = "
<html>
<head>
  <title>FOL Clothing - Email Verification</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      color: #333;
    }
    .email-container {
      width: 600px;
      margin: auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .email-header {
      text-align: center;
      padding: 10px 0;
    }
    .email-header h1 {
      color: #28a745;
    }
    .email-body {
      font-size: 16px;
      margin-bottom: 20px;
    }
    .code {
      font-size: 22px;
      font-weight: bold;
      color: #007bff;
    }
    .footer {
      font-size: 12px;
      color: #aaa;
      text-align: center;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class='email-container'>
    <div class='email-header'>
      <h1>FOL Clothing</h1>
      <p>Your Email Verification Code</p>
    </div>
    <div class='email-body'>
      <p>Hello,</p>
      <p>Thank you for signing up with FOL Clothing. Please use the following code to verify your email address:</p>
      <p class='code'>$code</p>
      <p>If you did not request this, please ignore this email.</p>
    </div>
    <div class='footer'>
      <p>&copy; 2024 FOL Clothing. All rights reserved.</p>
    </div>
  </div>
</body>
</html>
";

// Set email headers to send HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
$headers .= "From: no-reply@folclothing.com" . "\r\n";

// Send email
if (mail($email, $subject, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
