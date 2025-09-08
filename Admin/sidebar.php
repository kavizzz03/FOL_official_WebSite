<style>
    /* Sidebar container */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  width: 250px;
  height: 100%;
  background: #1e1e2f;
  padding: 20px 0;
  box-shadow: 2px 0 10px rgba(0,0,0,0.2);
  overflow-y: auto;
  transition: all 0.3s ease;
  z-index: 1000;
}

/* Sidebar title */
.sidebar h4 {
  color: #fff;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 16px;
  letter-spacing: 1px;
}

/* Sidebar links */
.sidebar a {
  display: block;
  padding: 12px 20px;
  color: #bdbdbd;
  text-decoration: none;
  font-size: 14px;
  transition: all 0.2s ease;
  border-left: 3px solid transparent;
}

/* Sidebar links hover */
.sidebar a:hover {
  background: #2d2d44;
  color: #fff;
  border-left: 3px solid #4cafef;
}

/* Active link */
.sidebar a.active {
  background: #2d2d44;
  color: #fff;
  border-left: 3px solid #4cafef;
}

/* Icons inside sidebar */
.sidebar a i {
  width: 20px;
  text-align: center;
  margin-right: 8px;
}

/* Responsive: sidebar collapse */
@media (max-width: 768px) {
  .sidebar {
    width: 200px;
  }
  .sidebar a {
    font-size: 13px;
    padding: 10px 15px;
  }
}

@media (max-width: 576px) {
  .sidebar {
    position: relative;
    width: 100%;
    height: auto;
    box-shadow: none;
  }
}

</style>
<div class="sidebar">
  <h4 class="text-center mb-4">Admin Panel</h4>
  <a href="admin_dashboard.php"><i class="fa fa-home me-2"></i> Dashboard</a>
  <a href="Items/items.php"><i class="fa fa-box me-2"></i> Item Management</a>
  <a href="Orders/orders.php"><i class="fa fa-shopping-cart me-2"></i> Orders Management</a>
  <a href="admins.php"><i class="fa fa-user-shield me-2"></i> Admin Management</a>
  <a href="Users/users.php"><i class="fa fa-users me-2"></i> Users Management</a>
  <a href="announcements.php"><i class="fa fa-bullhorn me-2"></i> Make Announcement</a>
  <a href="sidebar_mgmt.php"><i class="fa fa-bars me-2"></i> Sidebar Management</a>
  <a href="owner_details.php"><i class="fa fa-id-card me-2"></i> Owner Details</a>
  <a href="subscribers.php"><i class="fa fa-envelope-open-text me-2"></i> Subscribers</a>
  <a href="logout.php" class="text-danger"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
</div>
