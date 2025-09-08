<?php
include 'db.php';
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $token = $_POST['token'];

    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token=? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $email = $row['email'];
        $stmt = $conn->prepare("UPDATE admin SET password=? WHERE email=?");
        $stmt->bind_param("ss", $password, $email);
        $stmt->execute();

        $conn->query("DELETE FROM password_resets WHERE email='$email'");
        echo "Password reset successful. <a href='login.php'>Login</a>";
    } else {
        echo "Invalid or expired token.";
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow p-4">
        <h4 class="text-center">Create New Password</h4>
        <form method="POST">
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
          <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Reset Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
