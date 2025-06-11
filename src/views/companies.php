<?php
// src/views/companies.php

// 1) Autoload Composer dependencies
require __DIR__ . '/../../vendor/autoload.php';

// 2) Protect the page
include __DIR__ . '/../middleware/auth.php';

// 3) Import your controller
use App\Controllers\CompanyController;

// 4) Capture filter & sort inputs
$searchTerm = $_GET['search'] ?? '';
$order_by   = $_GET['sort']   ?? 'company_name';
$order_dir  = (($_GET['dir'] ?? 'asc') === 'desc') ? 'desc' : 'asc';

// 5) Fetch companies via the controller
$companyController = new CompanyController();
$companies = $companyController->listCompanies($searchTerm, $order_by, $order_dir);

// 6) Helper to build sort links
function sort_link($col, $label, $order_by, $order_dir, $searchTerm) {
    $dir   = ($order_by === $col && $order_dir === 'asc') ? 'desc' : 'asc';
    $arrow = '';
    if ($order_by === $col) {
        $arrow = $order_dir === 'asc' ? ' ▲' : ' ▼';
    }
    $q = $searchTerm ? '&search=' . urlencode($searchTerm) : '';
    return "<a href='index.php?action=listCompanies&sort={$col}&dir={$dir}{$q}' class='text-decoration-none' style='color:var(--fly-blue)'>{$label}{$arrow}</a>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Companies - Flyhub CRM</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Your custom CSS -->
  <link href="/flyhub-ERP/public/assets/css/index.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    /* ... keep your existing CSS for sidebar, cards, etc. ... */
  </style>
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
        <span class="me-3">👋 Hello, <?= htmlspecialchars($_SESSION['user']); ?></span>
        <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebarNav">
    <ul class="nav flex-column pt-2">
      <li class="nav-item">
        <a class="nav-link" href="index.php?action=list"><i class="bi bi-house-door"></i> Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="index.php?action=list"><i class="bi bi-person-lines-fill"></i> Contacts</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="index.php?action=listCompanies"><i class="bi bi-building"></i> Companies</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="bi bi-briefcase"></i> Deals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="bi bi-check2-square"></i> Tasks</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><i class="bi bi-gear"></i> Settings</a>
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

    <!-- Table with Search & Sort -->
    <div class="container-fluid table-wrapper">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <!-- Card Header -->
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
              <span class="fw-bold fs-5">All Companies</span>
              <div class="d-flex align-items-center">
                <form class="search-bar me-3" method="get" action="index.php">
                  <input type="hidden" name="action" value="listCompanies">
                  <div class="input-group input-group-sm">
                    <input
                      type="text"
                      name="search"
                      value="<?= htmlspecialchars($searchTerm); ?>"
                      class="form-control"
                      placeholder="Search companies..."
                    >
                    <button class="btn btn-outline-light" type="submit">
                      <i class="bi bi-search"></i>
                    </button>
                  </div>
                </form>
                <form method="post" action="index.php?action=exportCompanies" class="d-inline">
                  <button class="btn btn-success btn-sm me-2" type="submit">Export to Excel</button>
                </form>
                <button id="bulkDeleteBtn" class="btn btn-danger btn-sm" disabled>Bulk Delete</button>
              </div>
            </div>

            <div class="card-body p-0">
              <form id="bulkActionForm" method="post" action="index.php?action=bulkDeleteCompanies">
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead>
                      <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th><?= sort_link('company_name','Company Name',$order_by,$order_dir,$searchTerm) ?></th>
                        <th><?= sort_link('city','City',$order_by,$order_dir,$searchTerm) ?></th>
                        <th><?= sort_link('country','Country',$order_by,$order_dir,$searchTerm) ?></th>
                        <th><?= sort_link('website_url','Website URL',$order_by,$order_dir,$searchTerm) ?></th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($companies) > 0): ?>
                        <?php foreach ($companies as $row): 
                          $id           = $row['id'];
                          $companyName  = htmlspecialchars($row['company_name']);
                          $city         = htmlspecialchars($row['city']);
                          $country      = htmlspecialchars($row['country']);
                          $websiteUrl   = htmlspecialchars($row['website_url']);
                        ?>
                          <tr data-id="<?= $id ?>">
                            <td>
                              <input type="checkbox" name="selected_ids[]" value="<?= $id ?>" class="selectbox">
                            </td>
                            <td>
                              <a href="index.php?action=viewCompany&id=<?= $id ?>" class="fw-bold text-decoration-none text-primary">
                                <?= $companyName ?>
                              </a>
                            </td>
                            <td><?= $city ?></td>
                            <td><?= $country ?></td>
                            <td><?= $websiteUrl ?></td>
                            <td>
                              <a href="index.php?action=editCompany&id=<?= $id ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                              <a href="index.php?action=deleteCompany&id=<?= $id ?>"
                                 class="btn btn-sm btn-danger"
                                 onclick="return confirm('Delete this company?');">
                                Delete
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="6" class="text-center">No companies found.</td>
                        </tr>
                      <?php endif; ?>
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
          <form action="index.php?action=createCompany" method="POST">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="addCompanyModalLabel">Add New Company</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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

  </div> <!-- /.content -->

  <!-- Footer -->
  <div class="footer text-center py-2">
    &copy; <?= date('Y') ?> Flyhub CRM
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  $(function() {
    // Sidebar toggle
    $('#sidebarToggle').on('click', function() {
      $('.sidebar').toggleClass('collapsed show');
    });

    // Select all
    $('#selectAll').click(function() {
      $('.selectbox').prop('checked', this.checked);
      $('#bulkDeleteBtn').prop('disabled', !this.checked);
    });
    $('.selectbox').change(function() {
      $('#bulkDeleteBtn').prop('disabled', $('.selectbox:checked').length === 0);
    });

    // Bulk delete
    $('#bulkDeleteBtn').click(function(){
      if(confirm("Delete selected companies?")) {
        $('#bulkActionForm').submit();
      }
    });

    // Inline editing
    $('.editable').on('blur', function(){
      let td    = $(this),
          value = td.text().trim(),
          field = td.data('field'),
          id    = td.closest('tr').data('id');
      $.post('index.php?action=inlineEditCompany', { id, field, value }, function(resp){
        if(resp !== 'OK') alert(resp);
      });
    }).on('keydown', function(e){
      if(e.key === 'Enter') {
        e.preventDefault();
        $(this).blur();
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
