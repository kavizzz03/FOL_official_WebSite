<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars(trim($_POST["name"]));
    $email   = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    $adminEmail = "kavizzn@gmail.com"; // 🔁 Change this to your actual admin email
    $subjectToAdmin = "New Contact Message from $name";
    $messageToAdmin = "
        You have received a new message from FOL Clothing contact form:

        Name: $name
        Email: $email
        Message:
        $message

        Sent on: " . date("Y-m-d H:i:s") . "
    ";

    $headers = "From: FOL Clothing <no-reply@folclothing.com>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send email to admin
    $adminSent = mail($adminEmail, $subjectToAdmin, $messageToAdmin, $headers);

    // Send thank you email to user
    $subjectToUser = "Thank you for contacting FOL Clothing!";
    $messageToUser = "
        Hi $name,

        Thank you for reaching out to FOL Clothing! We've received your message and one of our team members will get back to you shortly.

        Here's a copy of your message:
        \"$message\"

        Best regards,
        The FOL Clothing Team
        www.folclothing.com
    ";

    $headersToUser = "From: FOL Clothing <no-reply@folclothing.com>\r\n";
    $headersToUser .= "Reply-To: $adminEmail\r\n";

    $userSent = mail($email, $subjectToUser, $messageToUser, $headersToUser);

    if ($adminSent && $userSent) {
        echo "<script>alert('Message sent successfully. We will contact you soon.'); window.location.href='contact.html';</script>";
    } else {
        echo "<script>alert('Failed to send message. Please try again later.'); window.location.href='contact.html';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='contact.html';</script>";
}
?>
