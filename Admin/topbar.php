<div class="topbar">
  <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
  <button class="btn btn-outline-danger btn-sm" onclick="window.location='logout.php'">
    <i class="fa fa-sign-out-alt"></i> Logout
  </button>
</div>
