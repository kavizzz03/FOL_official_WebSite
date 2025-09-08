<?php
include '../db.php';

// ✅ Handle Add Subscriber
if (isset($_POST['add_subscriber'])) {
    $email = trim($_POST['email']);
    if (!empty($email)) {
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            echo "<script>
                Swal.fire('Added!', 'Subscriber added successfully.', 'success').then(()=>{
                    window.location='subscribers.php';
                });
            </script>";
        } else {
            echo "<script>Swal.fire('Error!', 'Could not add subscriber.', 'error');</script>";
        }
        $stmt->close();
    }
}

// ✅ Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM subscribers WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>
        Swal.fire('Deleted!', 'Subscriber removed.', 'success').then(()=>{
            window.location='subscribers.php';
        });
    </script>";
    exit;
}

// ✅ Fetch Subscribers
$result = $conn->query("SELECT * FROM subscribers ORDER BY subscribed_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Subscribers Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap + SweetAlert -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

  <style>
    body { margin-left:260px; background:#f8fff8; }
   
    .card { border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08); }
    .table th { background:#198754; color:#fff; }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="container mt-4">
  <h3 class="mb-4"><i class="bi bi-people-fill"></i> Subscribers Management</h3>

  <!-- Add Subscriber Form -->
  <div class="card p-3 mb-4">
    <form method="POST" class="row g-3">
      <div class="col-md-8">
        <input type="email" name="email" class="form-control" placeholder="Enter subscriber email" required>
      </div>
      <div class="col-md-4">
        <button type="submit" name="add_subscriber" class="btn btn-success w-100">
          <i class="bi bi-plus-circle"></i> Add Subscriber
        </button>
      </div>
    </form>
  </div>

  <!-- Subscribers Table -->
  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Email</th>
            <th>Subscribed At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['subscribed_at'] ?></td>
            <td>
              <button class="btn btn-sm btn-danger" onclick="deleteSubscriber(<?= $row['id'] ?>)">
                <i class="bi bi-trash"></i>
              </button>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
// ✅ Delete Subscriber with SweetAlert
function deleteSubscriber(id){
  Swal.fire({
    title: "Delete this subscriber?",
    text: "This action cannot be undone!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    confirmButtonText: "Yes, delete"
  }).then((result)=>{
    if(result.isConfirmed){
      window.location.href = 'subscribers.php?delete='+id;
    }
  });
}
</script>

</body>
</html>
