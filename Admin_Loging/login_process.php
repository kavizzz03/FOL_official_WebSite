<?php
session_start();
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM admin WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // Detect IP & Device
        $ip = $_SERVER['REMOTE_ADDR'];
        $device = $_SERVER['HTTP_USER_AGENT'];
        date_default_timezone_set("Asia/Colombo");
        $time = date("Y-m-d H:i:s");

        // Send login email
        $to = $row['email'];
        $subject = "New Admin Login Detected";
        $message = "Hello {$row['username']},<br><br>"
                 . "A login was detected on your admin account.<br>"
                 . "IP Address: $ip <br>"
                 . "Device: $device <br>"
                 . "Time: $time (Colombo, Sri Lanka)<br><br>"
                 . "If this wasn’t you, please reset your password immediately.";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8\r\n";
        $headers .= "From: no-reply@yourdomain.com\r\n";

        mail($to, $subject, $message, $headers);

        header("Location:../Admin/admin_dashboard.php");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}
?>
