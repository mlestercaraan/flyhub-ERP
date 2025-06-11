<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: companies.php");
    exit();
}
$id = intval($_GET['id']);
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$sql = "SELECT * FROM companies WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    $conn->close();
    header("Location: companies.php");
    exit();
}
$company = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Company Profile - Flyhub CRM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    :root {
      --fly-orange: #F95C39;
      --fly-blue: #4591E4;
      --fly-black: #23272b;
      --fly-grey: #f5f6fa;
    }
    body {
      min-height: 100vh;
      background: var(--fly-grey);
      padding-bottom: 80px;
    }
    .navbar {
      background: #fff;
      border-bottom: 2px solid var(--fly-blue);
      min-height: 58px;
    }
    .navbar-brand {
      font-weight: bold;
      letter-spacing: 1px;
      font-size: 1.3rem;
      color: var(--fly-black);
      display: flex;
      align-items: center;
    }
    .flyhub-logo {
      height: 30px;
      margin-right: 8px;
    }
    .sidebar {
      min-width: 220px;
      max-width: 220px;
      background: var(--fly-black);
      color: #fff;
      min-height: 100vh;
      transition: all 0.2s;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1030;
      padding-top: 58px;
      border-right: 1px solid #e1e3e8;
    }
    .sidebar.collapsed {
      min-width: 60px !important;
      max-width: 60px !important;
      overflow-x: hidden;
    }
    .sidebar .nav-link {
      color: #fff;
      font-weight: 500;
      transition: background 0.15s, color 0.15s;
      border-radius: 0 30px 30px 0;
      margin-bottom: 3px;
      padding: 10px 20px;
      display: flex;
      align-items: center;
    }
    .sidebar .nav-link.active, .sidebar .nav-link:hover {
      background: var(--fly-blue);
      color: #fff;
    }
    .sidebar .nav-link .bi {
      margin-right: 14px;
      font-size: 1.3em;
      flex-shrink: 0;
    }
    .sidebar.collapsed .sidebar-label {
      display: none !important;
    }
    .sidebar.collapsed .nav-link {
      justify-content: center;
      padding: 10px 8px;
    }
    .content {
      margin-left: 220px;
      transition: margin-left 0.2s;
      padding: 24px 18px 0 18px;
    }
    .sidebar.collapsed + .content {
      margin-left: 60px;
    }
    @media (max-width: 991px) {
      .sidebar {
        left: -220px;
        transition: left 0.2s;
      }
      .sidebar.show {
        left: 0;
      }
      .content {
        margin-left: 0;
      }
    }
    .sidebar-toggler {
      border: none;
      background: none;
      color: var(--fly-black);
      font-size: 1.5em;
      margin-right: 16px;
    }
    .footer {
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100vw;
      padding: 12px 0;
      background: var(--fly-black);
      color: #fff;
      text-align: center;
      font-size: 1rem;
      letter-spacing: 0.1em;
      z-index: 1100;
    }
  </style>
</head>
<body class="bg-light">

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light mb-0 shadow-sm fixed-top" style="z-index: 1040;">
  <div class="container-fluid">
    <button class="sidebar-toggler" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <span class="navbar-brand ms-2">
      <img src="flyhub-logo.webp" class="flyhub-logo" alt="Flyhub Logo">
      Flyhub CRM
    </span>
    <div class="d-flex align-items-center">
      <span class="me-3">👋 Hello, <?php echo htmlspecialchars($_SESSION['user']); ?></span>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Sidebar -->
<div class="sidebar" id="sidebarNav">
  <ul class="nav flex-column pt-2">
    <li class="nav-item">
      <a class="nav-link" href="#"><i class="bi bi-house-door"></i> <span class="sidebar-label">Dashboard</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="index.php"><i class="bi bi-person-lines-fill"></i> <span class="sidebar-label">Contacts</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="companies.php"><i class="bi bi-building"></i> <span class="sidebar-label">Companies</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#"><i class="bi bi-briefcase"></i> <span class="sidebar-label">Deals</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#"><i class="bi bi-check2-square"></i> <span class="sidebar-label">Tasks</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#"><i class="bi bi-gear"></i> <span class="sidebar-label">Settings</span></a>
    </li>
  </ul>
</div>

<!-- Content Area -->
<div class="content">
  <div class="container-fluid" style="margin-top:70px;">
    <div class="row">
      <!-- Profile Column -->
      <div class="col-md-4">
        <div class="card shadow mb-4">
          <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Company Profile</h5>
          </div>
          <div class="card-body">
            <form action="company_profile_update.php" method="POST">
              <input type="hidden" name="id" value="<?php echo $company['id']; ?>">
              <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" class="form-control" value="<?php echo htmlspecialchars($company['company_name']); ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($company['city']); ?>">
              </div>
              <div class="mb-3">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control" value="<?php echo htmlspecialchars($company['country']); ?>">
              </div>
              <div class="mb-3">
                <label class="form-label">Website URL</label>
                <input type="url" name="website_url" class="form-control" value="<?php echo htmlspecialchars($company['website_url']); ?>">
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="companies.php" class="btn btn-secondary">Back</a>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Placeholder Columns -->
      <div class="col-md-4">
        <!-- Placeholder for future content (column 2) -->
      </div>
      <div class="col-md-4">
        <!-- Placeholder for future content (column 3) -->
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  &copy; <?php echo date('Y'); ?> Flyhub CRM
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function() {
    $('#sidebarToggle').on('click', function() {
      $('.sidebar').toggleClass('collapsed');
      $('.sidebar').toggleClass('show');
      if(window.innerWidth <= 991){
        $('.sidebar').toggleClass('show');
      }
    });
    $(window).on('resize', function() {
      if(window.innerWidth > 991){
        $('.sidebar').removeClass('show');
      }
    });
});
</script>
</body>
</html>
