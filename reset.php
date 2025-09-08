<?php
$conn = new mysqli("localhost", "u569550465_bogahawatte", "Malshan2003#", "u569550465_fol_clothing");
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $conn->prepare("SELECT id FROM admins WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($id);
    if ($stmt->fetch()) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newpass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admins SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
            $stmt->bind_param("si", $newpass, $id);
            $stmt->execute();
            $success = "Password reset successful. <a href='login.php'>Login</a>";
        }
    } else {
        $error = "Invalid or expired token.";
    }
}
?>
<?php if (!isset($success) && !isset($error)): ?>
<form method="POST">
  <input name="password" type="password" required placeholder="New Password">
  <button type="submit">Change Password</button>
</form>
<?php endif; ?>
<?php if (isset($success)) echo "<p>$success</p>"; ?>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
