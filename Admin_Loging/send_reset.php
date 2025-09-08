<?php
include 'db.php';

$email = $_POST['email'];
$stmt = $conn->prepare("SELECT * FROM admin WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $token = bin2hex(random_bytes(50));
    $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

    $conn->query("INSERT INTO password_resets (email, token, expires_at) VALUES ('$email', '$token', '$expires')");

    $reset_link = "https://modaloku.cpsharetxt.com/reset_password.php?token=$token";
    $subject = "Password Reset Request";
    $message = "Click the link below to reset your password:<br><a href='$reset_link'>$reset_link</a>";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: no-reply@yourdomain.com\r\n";

    mail($email, $subject, $message, $headers);
    echo "Reset link sent to your email.";
} else {
    echo "Email not found.";
}
?>
