<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Companies - Flyhub CRM</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --fly-orange: #F95C39;
      --fly-blue: #4591E4;
      --fly-black: #23272b;
      --fly-grey: #f5f6fa;
      --fly-sidebar-hover: #3a4250;
    }
    body {
      min-height: 100vh;
      background: var(--fly-grey);
      position: relative;
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
    .sidebar .nav-link .sidebar-label {
      transition: opacity 0.2s;
      opacity: 1;
    }
    .sidebar.collapsed .nav-link .sidebar-label {
      opacity: 0;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
      background: var(--fly-blue);
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
    .add-company-bar {
      margin-top: 70px;
    }
    .table-wrapper {
      margin-top: 40px;
      width: 100%;
      max-width: 100vw;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 8px 24px 0 rgba(44, 62, 80, 0.08);
      border: none;
    }
    .card-header {
      background: var(--fly-black) !important;
      color: #fff !important;
      border-radius: 10px 10px 0 0;
      border-bottom: none;
    }
    .card-header .fw-bold {
      color: var(--fly-orange);
    }
    .card-body {
      background: #fff;
      border-radius: 0 0 10px 10px;
      padding: 0;
    }
    .table {
      margin-bottom: 0;
      width: 100% !important;
      min-width: 1000px;
    }
    .table th,
    .table td {
      vertical-align: middle;
      border-color: #e6e9ef;
      font-size: 1rem;
    }
    .table th {
      background: var(--fly-grey);
      font-weight: bold;
      color: var(--fly-blue);
    }
    .table-hover tbody tr:hover {
      background: #f8fafc;
    }
    .btn-success {
      background: var(--fly-blue) !important;
      border-color: var(--fly-blue) !important;
      color: #fff !important;
      font-weight: 500;
    }
    .btn-success:hover,
    .btn-success:focus {
      background: #3878ba !important;
      border-color: #3878ba !important;
    }
    .btn-warning {
      background: var(--fly-orange) !important;
      border-color: var(--fly-orange) !important;
      color: #fff !important;
      font-weight: 500;
    }
    .btn-warning:hover,
    .btn-warning:focus {
      background: #e24d27 !important;
      border-color: #e24d27 !important;
    }
    .btn-danger {
      background: #f75a5a !important;
      border-color: #f75a5a !important;
      color: #fff !important;
      font-weight: 500;
    }
    .btn-outline-danger {
      border-color: #f75a5a !important;
      color: #f75a5a !important;
    }
    .btn-outline-danger:hover,
    .btn-outline-danger:focus {
      background: #f75a5a !important;
      color: #fff !important;
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
    .search-bar {
      max-width: 260px;
      margin-left: auto;
    }
  </style>
  <!-- Bootstrap Icons CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light mb-0 shadow-sm fixed-top" style="z-index: 1040;">
  <div class="container-fluid">
    <button class="sidebar-toggler" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <span class="navbar-brand ms-2">
      <img src="flyhub-logo.png" class="flyhub-logo" alt="Flyhub Logo">
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

  <!-- Add New Company Button -->
  <div class="container-fluid mb-0 add-company-bar">
    <div class="row">
      <div class="col text-end">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
          Add New Company
        </button>
      </div>
    </div>
  </div>

  <!-- Table with Search -->
  <div class="container-fluid table-wrapper">
    <div class="row">
      <div class="col">
        <div class="card shadow">
          <!-- Card Header -->
          <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-5">All Companies</span>
            <div class="d-flex align-items-center">
              <form class="search-bar me-3" method="get" action="">
                <div class="input-group input-group-sm">
                  <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" class="form-control" placeholder="Search companies...">
                  <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
                </div>
              </form>
              <form method="post" action="companies_export.php" class="d-inline">
                <button class="btn btn-success btn-sm me-2" type="submit">Export to Excel</button>
              </form>
              <button id="bulkDeleteBtn" class="btn btn-danger btn-sm" disabled>Bulk Delete</button>
            </div>
          </div>
          <div class="card-body p-0">
            <?php
              $conn = new mysqli("localhost", "root", "", "crud_demo");
              if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
              $order_by = $_GET['sort'] ?? 'company_name';
              $order_dir = ($_GET['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
              $allowed_cols = ['company_name', 'city', 'country', 'website_url'];
              if (!in_array($order_by, $allowed_cols)) $order_by = 'company_name';
              $where = '';
              if ($searchTerm !== '') {
                $s = $conn->real_escape_string($searchTerm);
                $where = "WHERE company_name LIKE '%$s%' OR city LIKE '%$s%' OR country LIKE '%$s%' OR website_url LIKE '%$s%'";
              }
              $sql = "SELECT * FROM companies $where ORDER BY $order_by $order_dir";
              $result = $conn->query($sql);

              function sort_link($col, $label, $order_by, $order_dir, $searchTerm) {
                  $dir = ($order_by === $col && $order_dir === 'asc') ? 'desc' : 'asc';
                  $arrow = '';
                  if ($order_by === $col) $arrow = $order_dir === 'asc' ? ' ▲' : ' ▼';
                  $q = $searchTerm ? '&search=' . urlencode($searchTerm) : '';
                  return "<a href='?sort=$col&dir=$dir$q' class='text-decoration-none' style='color:var(--fly-blue)'>$label$arrow</a>";
              }
            ?>
            <form id="bulkActionForm" method="post" action="companies_bulk_delete.php">
              <div class="table-responsive" style="overflow-x:auto;">
                <table class="table table-hover align-middle">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="selectAll"></th>
                      <th><?=sort_link('company_name','Company Name',$order_by,$order_dir,$searchTerm)?></th>
                      <th><?=sort_link('city','City',$order_by,$order_dir,$searchTerm)?></th>
                      <th><?=sort_link('country','Country',$order_by,$order_dir,$searchTerm)?></th>
                      <th><?=sort_link('website_url','Website URL',$order_by,$order_dir,$searchTerm)?></th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          $company_id = $row['id'];
                          $company_name = htmlspecialchars($row['company_name']);
                          $city = htmlspecialchars($row['city']);
                          $country = htmlspecialchars($row['country']);
                          $website_url = htmlspecialchars($row['website_url']);
                          echo "<tr data-id='{$company_id}'>
                            <td><input type='checkbox' name='selected_ids[]' value='{$company_id}' class='selectbox'></td>
                            <td>
                              <a href='company_profile.php?id={$company_id}' class='fw-bold text-decoration-none text-primary'>{$company_name}</a>
                            </td>
                            <td>{$city}</td>
                            <td>{$country}</td>
                            <td>{$website_url}</td>
                            <td>
                              <a href='companies_edit.php?id={$company_id}' class='btn btn-sm btn-warning me-1'>Edit</a>
                              <a href='companies_delete.php?id={$company_id}' class='btn btn-sm btn-danger'
                                 onclick=\"return confirm('Delete this company?');\">Delete</a>
                            </td>
                          </tr>";
                        }
                      } else {
                        echo "<tr><td colspan='6' class='text-center'>No companies found.</td></tr>";
                      }
                    ?>
                    </tbody>

                </table>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Company Modal -->
  <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="companies_insert.php" method="POST">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="addCompanyModalLabel">Add New Company</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Company Name</label>
              <input type="text" name="company_name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">City</label>
              <input type="text" name="city" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Country</label>
              <input type="text" name="country" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Website URL</label>
              <input type="url" name="website_url" class="form-control">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success">Add Company</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  &copy; <?php echo date('Y'); ?> Flyhub CRM
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function() {
    // Sidebar toggle (collapses/expands sidebar)
    $('#sidebarToggle').on('click', function() {
      $('.sidebar').toggleClass('collapsed');
      $('.sidebar').toggleClass('show');
      if(window.innerWidth <= 991){
        $('.sidebar').toggleClass('show');
      }
    });

    // Select all
    $('#selectAll').click(function() {
        $('.selectbox').prop('checked', this.checked);
        toggleBulkBtns();
    });
    $('.selectbox').change(toggleBulkBtns);

    function toggleBulkBtns() {
        let any = $('.selectbox:checked').length > 0;
        $('#bulkDeleteBtn').prop('disabled', !any);
    }

    // Bulk delete
    $('#bulkDeleteBtn').click(function(e){
        if(confirm("Delete selected companies?")) {
            $('#bulkActionForm').submit();
        }
    });

    // Inline editing
    $('.editable').on('blur', function(){
        let td = $(this);
        let value = td.text().trim();
        let field = td.data('field');
        let id = td.closest('tr').data('id');
        $.post('companies_inline_edit.php', {id, field, value}, function(resp){
            if(resp !== 'OK') alert(resp);
        });
    });
    // Handle Enter key for inline editing
    $('.editable').on('keydown', function(e){
        if(e.key === 'Enter') {
            e.preventDefault();
            $(this).blur();
        }
    });

    // Responsive sidebar toggle on window resize
    $(window).on('resize', function() {
      if(window.innerWidth > 991){
        $('.sidebar').removeClass('show');
      }
    });
});
</script>
</body>
</html>
