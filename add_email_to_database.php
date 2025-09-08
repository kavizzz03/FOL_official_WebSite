
<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];

$conn = new mysqli("localhost", "u569550465_bogahawatte", "Malshan2003#","u569550465_fol_clothing");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Connection failed"]);
    exit;
}

// Save email to the database
$stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    // Send welcome email
    $subject = "Welcome to FOL Clothing!";
    
    // HTML content for the welcome email
    $message = "
    <html>
    <head>
        <title>Welcome to FOL Clothing</title>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; }
            .container { max-width: 600px; margin: auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
            h1 { color: #28a745; }
            p { font-size: 16px; line-height: 1.6; }
            .footer { font-size: 14px; text-align: center; margin-top: 20px; color: #6c757d; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Welcome to FOL Clothing!</h1>
            <p>Thank you for subscribing to our newsletter. We're thrilled to have you with us!</p>
            <p>Stay updated with the latest trends and exclusive offers. You can always visit our <a href='https://www.folclothing.lk' target='_blank'>official website</a> for more.</p>
            <p>We're committed to providing you with the best shopping experience.</p>
            <div class='footer'>
                <p>&copy; 2025 FOL Clothing. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@folclothing.lk" . "\r\n";

    // Send email
    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error sending welcome email"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Error saving email"]);
}

$stmt->close();
$conn->close();
?>

