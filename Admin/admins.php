<?php
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

include '../db.php';

// Function to send email using PHP mail()
function sendAdminEmail($to, $subject, $body){
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@yourdomain.com" . "\r\n"; // Replace with your email
    mail($to, $subject, $body, $headers);
}

// Function to log admin action
function logAdminAction($conn, $admin_username, $action){
    $ip = $_SERVER['REMOTE_ADDR'];
    $device = $_SERVER['HTTP_USER_AGENT'];
    date_default_timezone_set('Asia/Colombo');
    $time = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO admin_logs (admin_username, action, ip_address, device, created_at) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sssss",$admin_username,$action,$ip,$device,$time);
    $stmt->execute();
    $stmt->close();
}

// Handle Add Admin
if(isset($_POST['add_admin'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admin (username, email, password) VALUES (?,?,?)");
    $stmt->bind_param("sss",$username,$email,$password);
    $stmt->execute();
    $stmt->close();

    // Email to owner
    $owner_email = $conn->query("SELECT owner_email FROM owner_details LIMIT 1")->fetch_assoc()['owner_email'];
    sendAdminEmail($owner_email,"New Admin Added","Admin <b>$username</b> has been added.");

    // Log action
    logAdminAction($conn,$username,"Added Admin");

    echo "<script>Swal.fire('Success','Admin added successfully','success');</script>";
}

// Handle Update Admin
if(isset($_POST['update_admin'])){
    $id = $_POST['admin_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($password!=""){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admin SET username=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi",$username,$email,$password,$id);
    } else {
        $stmt = $conn->prepare("UPDATE admin SET username=?, email=? WHERE id=?");
        $stmt->bind_param("ssi",$username,$email,$id);
    }
    $stmt->execute();
    $stmt->close();

    $owner_email = $conn->query("SELECT owner_email FROM owner_details LIMIT 1")->fetch_assoc()['owner_email'];
    sendAdminEmail($owner_email,"Admin Updated","Admin <b>$username</b> has been updated.");

    logAdminAction($conn,$username,"Updated Admin");

    echo "<script>Swal.fire('Success','Admin updated successfully','success');</script>";
}

// Handle Delete Admin
if(isset($_GET['delete_id'])){
    $id = $_GET['delete_id'];
    $admin = $conn->query("SELECT username,email FROM admin WHERE id=$id")->fetch_assoc();
    $conn->query("DELETE FROM admin WHERE id=$id");

    $owner_email = $conn->query("SELECT owner_email FROM owner_details LIMIT 1")->fetch_assoc()['owner_email'];
    sendAdminEmail($owner_email,"Admin Deleted","Admin <b>{$admin['username']}</b> has been deleted.");

    logAdminAction($conn,$admin['username'],"Deleted Admin");

    echo "<script>Swal.fire('Deleted','Admin deleted successfully','success').then(()=>{window.location='admins.php'})</script>";
}

// Fetch Admins
$admins = $conn->query("SELECT * FROM admin ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Management</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="../sidebar.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<style>
body{margin-left:260px; background:#f4f6fa;}
.table td, .table th{vertical-align: middle;}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Admin Management</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAdminModal"><i class="fa fa-plus me-1"></i> Add Admin</button>
    </div>

    <table class="table table-striped table-hover shadow-sm bg-white rounded">
        <thead class="table-dark">
            <tr>
                <th>ID</th><th>Username</th><th>Email</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row=$admins->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAdminModal<?= $row['id'] ?>"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteAdmin(<?= $row['id'] ?>)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </td>
            </tr>

            <!-- Edit Admin Modal -->
            <div class="modal fade" id="editAdminModal<?= $row['id'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form method="POST">
                  <input type="hidden" name="admin_id" value="<?= $row['id'] ?>">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                      <div class="mb-3">
                          <label>Username</label>
                          <input type="text" name="username" class="form-control" value="<?= $row['username'] ?>" required>
                      </div>
                      <div class="mb-3">
                          <label>Email</label>
                          <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
                      </div>
                      <div class="mb-3">
                          <label>Password (Leave blank to keep current)</label>
                          <input type="password" name="password" class="form-control">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="update_admin" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>

        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
      <div class="modal-header">
        <h5 class="modal-title">Add New Admin</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
              <label>Username</label>
              <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_admin" class="btn btn-success">Add Admin</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteAdmin(id){
    Swal.fire({
        title:'Are you sure?',
        text:'This admin will be deleted permanently!',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Yes, delete it!'
    }).then((res)=>{
        if(res.isConfirmed){
            window.location.href='?delete_id='+id;
        }
    });
}
</script>
</body>
</html>
