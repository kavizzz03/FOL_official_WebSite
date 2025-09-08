<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body { font-family: "Segoe UI", sans-serif; }
    .sidebar {
      height: 100vh; background: #1e293b; color: #fff;
      position: fixed; top: 0; left: 0; width: 260px; padding-top: 60px;
    }
    .sidebar a {
      display: block; padding: 12px 20px; color: #cbd5e1;
      text-decoration: none; transition: 0.2s;
    }
    .sidebar a:hover { background: #334155; color: #fff; }
    .content { margin-left: 260px; padding: 30px; }
    .topbar {
      position: fixed; top: 0; left: 260px; right: 0;
      height: 60px; background: #fff; border-bottom: 1px solid #ddd;
      display: flex; justify-content: space-between; align-items: center;
      padding: 0 20px; z-index: 1000;
    }
    .card-custom { border-radius: 15px; transition: 0.3s; }
    .card-custom:hover { transform: translateY(-4px); box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>
<?php include 'topbar.php'; ?>

<!-- Main Content -->
<div class="content">
  <div class="container-fluid">
    <h2 class="mb-4">Dashboard Overview</h2>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3 bg-primary text-white">
          <h5><i class="fa fa-box"></i> Items</h5>
          <p class="mb-0">Manage your products</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3 bg-success text-white">
          <h5><i class="fa fa-shopping-cart"></i> Orders</h5>
          <p class="mb-0">Track & manage orders</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3 bg-warning text-dark">
          <h5><i class="fa fa-users"></i> Users</h5>
          <p class="mb-0">Manage user accounts</p>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card card-custom shadow-sm p-3 bg-danger text-white">
          <h5><i class="fa fa-bullhorn"></i> Announcements</h5>
          <p class="mb-0">Create announcements</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
