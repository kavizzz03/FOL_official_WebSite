<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Make sure the email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Use PHP's mail function or a mail API like PHPMailer or SendGrid to send an email
        $to = $email;
        $headers = "From: no-reply@folclothing.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_subject = $subject;
        $email_message = "<html><body>";
        $email_message .= "<h2>$subject</h2>";
        $email_message .= "<p>$message</p>";
        $email_message .= "</body></html>";

        // Send email
        if (mail($to, $email_subject, $email_message, $headers)) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "invalid_email";
    }
}
?>
