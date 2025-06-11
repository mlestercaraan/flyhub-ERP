<?php
// 1) Autoload Composer dependencies
require __DIR__ . '/../../vendor/autoload.php';

// 2) Protect the page
include __DIR__ . '/../middleware/auth.php';

// 3) Import your controller
use App\Controllers\ContactController;

// 4) Capture filter & sort inputs
$searchTerm = $_GET['search'] ?? '';
$order_by   = $_GET['sort']   ?? 'first_name';
$order_dir  = (($_GET['dir'] ?? 'asc') === 'desc') ? 'desc' : 'asc';

// 5) Fetch contacts via the controller
$contactController = new ContactController();
$contacts = $contactController->listContacts($searchTerm, $order_by, $order_dir);

// 6) Helper to build sort links
function sort_link($col, $label, $order_by, $order_dir, $searchTerm) {
    $dir   = ($order_by === $col && $order_dir === 'asc') ? 'desc' : 'asc';
    $arrow = '';
    if ($order_by === $col) {
        $arrow = $order_dir === 'asc' ? ' ▲' : ' ▼';
    }
    $q = $searchTerm ? '&search=' . urlencode($searchTerm) : '';
    return "<a href='index.php?action=list&sort={$col}&dir={$dir}{$q}' class='text-decoration-none' style='color:var(--fly-blue)'>{$label}{$arrow}</a>";
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Flyhub CRM</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Your custom CSS -->
  <link href="/flyhub-ERP/public/assets/css/index.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">

  <!-- Top Navbar -->
  <?php include __DIR__ . '/global/topbar.php'; ?>

  <!-- Sidebar -->
  <?php include __DIR__ . '/global/sidebar.php'; ?>

  <!-- Content Area -->
  <div class="content">

    <!-- Add New Contact Button -->
    <div class="container-fluid mb-0 add-contact-bar">
      <div class="row">
        <div class="col text-end">
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addContactModal">
            Add New Contact
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
              <span class="fw-bold fs-5">All Contacts</span>
              <div class="d-flex align-items-center">
                <form class="search-bar me-3" method="get" action="index.php">
                  <input type="hidden" name="action" value="list">
                  <div class="input-group input-group-sm">
                    <input
                      type="text"
                      name="search"
                      value="<?= htmlspecialchars($searchTerm); ?>"
                      class="form-control"
                      placeholder="Search contacts..."
                    >
                    <button class="btn btn-outline-light" type="submit">
                      <i class="bi bi-search"></i>
                    </button>
                  </div>
                </form>
                <form method="post" action="export.php" class="d-inline">
                  <button class="btn btn-success btn-sm me-2" type="submit">Export to Excel</button>
                </form>
                <button id="bulkDeleteBtn" class="btn btn-danger btn-sm" disabled>Bulk Delete</button>
              </div>
            </div>

            <div class="card-body p-0">
              <form id="bulkActionForm" method="post" action="index.php?action=bulk_delete">
                <div class="table-responsive" style="overflow-x:auto;">
                  <table class="table table-hover align-middle mb-0">
                    <thead>
                      <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th><?= sort_link('first_name','Name',$order_by,$order_dir,$searchTerm) ?></th>
                        <th><?= sort_link('email','Email',$order_by,$order_dir,$searchTerm) ?></th>
                        <th><?= sort_link('phone','Phone',$order_by,$order_dir,$searchTerm) ?></th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (count($contacts) > 0): ?>
                        <?php foreach ($contacts as $row):
                          $id       = $row['id'];
                          $fullName = htmlspecialchars(trim($row['first_name'] . ' ' . $row['last_name']));
                          $email    = htmlspecialchars($row['email']);
                          $phone    = htmlspecialchars($row['phone']);
                        ?>
                          <tr data-id="<?= $id ?>">
                            <td>
                              <input type="checkbox" name="selected_ids[]" value="<?= $id ?>" class="selectbox">
                            </td>
                            <td>
                              <a href="profile.php?id=<?= $id ?>" class="fw-bold text-decoration-none text-primary">
                                <?= $fullName ?>
                              </a>
                            </td>
                            <td>
                              <a href="profile.php?id=<?= $id ?>" class="text-decoration-none text-primary">
                                <?= $email ?>
                              </a>
                            </td>
                            <td contenteditable="true" class="editable" data-field="phone">
                              <?= $phone ?>
                            </td>
                            <td>
                              <a href="index.php?action=edit&id=<?= $id ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                              <a href="index.php?action=delete&id=<?= $id ?>"
                                 class="btn btn-sm btn-danger"
                                 onclick="return confirm('Delete this contact?');">
                                Delete
                              </a>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="6" class="text-center">No contacts found.</td>
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

    <!-- Add Contact Modal -->
    <div class="modal fade" id="addContactModal" tabindex="-1" aria-labelledby="addContactModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="index.php?action=create" method="POST">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="addContactModalLabel">Add New Contact</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">Add Contact</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div> <!-- /.content -->

  <!-- Footer -->
  <?php include __DIR__ . '/global/footer.php'; ?>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/flyhub-ERP/public/assets/js/index.js"></script>
</body>
</html>
