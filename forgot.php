<?php
$conn = new mysqli("localhost", "u569550465_bogahawatte", "Malshan2003#", "u569550465_fol_clothing");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->fetch()) {
        $token = bin2hex(random_bytes(16));
        $expiry = date('Y-m-d H:i:s', time() + 3600);
        $stmt = $conn->prepare("UPDATE admins SET reset_token = ?, token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();
        $link = "https://modaloku.cpsharetxt.com//reset.php?token=$token";
        mail($email, "Password Reset", "Click to reset: $link");
        $msg = "Check your email for reset link.";
    } else {
        $error = "Email not found.";
    }
}
?>
<form method="POST">
  <input name="email" required placeholder="Email">
  <button type="submit">Send Reset Link</button>
</form>
<?php if (isset($msg)) echo "<p>$msg</p>"; ?>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
