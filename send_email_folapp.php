<?php

// Replace with your email server details
$to = "kavindubogahawatte@gmail.com";  // Admin email to receive the login alert
$subject = "FOL Clothing App Login Alert";

// Get data from the Android app (sent via POST)
$username = $_POST['username'];
$device = $_POST['device'];
$ip_address = $_POST['ip_address'];
$login_time = $_POST['login_time'];

// Create the email message
$message = "Login Details:\n\n";
$message .= "Username: $username\n";
$message .= "Device: $device\n";
$message .= "IP Address: $ip_address\n";
$message .= "Login Time: $login_time\n";

// Send the email
$headers = "From: no-reply@folclothing.com";

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully";
} else {
    echo "Email sending failed";
}
?>
