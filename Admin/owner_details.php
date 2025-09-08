<?php
include '../db.php';

// Handle Add Owner
if(isset($_POST['add_owner'])){
    $name = $_POST['owner_name'];
    $email = $_POST['owner_email'];
    $contact = $_POST['owner_contact'];
    $address = $_POST['owner_address'];
    
    $stmt = $conn->prepare("INSERT INTO owner_details (owner_name, owner_email, owner_contact, owner_address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $contact, $address);
    $stmt->execute();
    $stmt->close();

    echo "<script>
    Swal.fire('Success','Owner Added Successfully','success');
    </script>";
}

// Handle Update Owner
if(isset($_POST['update_owner'])){
    $id = $_POST['id'];
    $name = $_POST['owner_name'];
    $email = $_POST['owner_email'];
    $contact = $_POST['owner_contact'];
    $address = $_POST['owner_address'];

    $stmt = $conn->prepare("UPDATE owner_details SET owner_name=?, owner_email=?, owner_contact=?, owner_address=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $contact, $address, $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>
    Swal.fire('Updated','Owner details updated successfully','success');
    </script>";
}

// Handle Delete Owner
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM owner_details WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>
    Swal.fire('Deleted','Owner Deleted Successfully','success').then(()=>{window.location='owner_details.php'});
    </script>";
}

// Fetch all owners
$result = $conn->query("SELECT * FROM owner_details ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Owner Management</title>

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet"/>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<style>
body{margin-left:260px; background:#f4f6fa;}
.table td, .table th{vertical-align: middle;}
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<!-- Main Container -->
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Owner Management</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOwnerModal"><i class="bi bi-plus-circle me-1"></i>Add Owner</button>
  </div>

  <table class="table table-striped table-hover bg-white rounded shadow-sm">
    <thead class="table-success">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Address</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row=$result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['owner_name'] ?></td>
        <td><?= $row['owner_email'] ?></td>
        <td><?= $row['owner_contact'] ?></td>
        <td><?= $row['owner_address'] ?></td>
        <td>
          <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editOwnerModal<?= $row['id'] ?>"><i class="bi bi-pencil-square"></i></button>
          <button onclick="deleteOwner(<?= $row['id'] ?>)" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
        </td>
      </tr>

      <!-- Edit Modal -->
      <div class="modal fade" id="editOwnerModal<?= $row['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <form method="POST" action="">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Edit Owner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <div class="mb-3">
                  <label>Name</label>
                  <input type="text" name="owner_name" class="form-control" value="<?= $row['owner_name'] ?>" required>
                </div>
                <div class="mb-3">
                  <label>Email</label>
                  <input type="email" name="owner_email" class="form-control" value="<?= $row['owner_email'] ?>" required>
                </div>
                <div class="mb-3">
                  <label>Contact</label>
                  <input type="text" name="owner_contact" class="form-control" value="<?= $row['owner_contact'] ?>" required>
                </div>
                <div class="mb-3">
                  <label>Address</label>
                  <input type="text" name="owner_address" class="form-control" value="<?= $row['owner_address'] ?>" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" name="update_owner" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Add Owner Modal -->
<div class="modal fade" id="addOwnerModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add New Owner</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Name</label>
            <input type="text" name="owner_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="owner_email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Contact</label>
            <input type="text" name="owner_contact" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Address</label>
            <input type="text" name="owner_address" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_owner" class="btn btn-success">Add Owner</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function deleteOwner(id){
    Swal.fire({
        title: 'Are you sure?',
        text: "Owner will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if(result.isConfirmed){
            window.location.href='owner_details.php?delete='+id;
        }
    });
}
</script>
</body>
</html>
