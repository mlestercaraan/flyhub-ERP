<?php
// src/views/dashboard.php
require __DIR__ . '/../../vendor/autoload.php';
include __DIR__ . '/../middleware/auth.php';

// Get dashboard data
$stats = $result ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dashboard – Flyhub CRM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/flyhub-ERP/public/assets/css/index.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">

  <?php include __DIR__ . '/global/topbar.php'; ?>
  <?php include __DIR__ . '/global/sidebar.php'; ?>

  <div class="content">
    <div class="container-fluid py-4">
      <h1 class="mb-4">Dashboard</h1>
      
      <!-- Stats Cards -->
      <div class="row mb-4">
        <div class="col-md-3">
          <div class="card bg-primary text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h4><?= $stats['total_contacts'] ?? 0 ?></h4>
                  <p class="mb-0">Total Contacts</p>
                </div>
                <div class="align-self-center">
                  <i class="bi bi-person-lines-fill fs-1"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="card bg-success text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h4><?= $stats['total_companies'] ?? 0 ?></h4>
                  <p class="mb-0">Total Companies</p>
                </div>
                <div class="align-self-center">
                  <i class="bi bi-building fs-1"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="card bg-warning text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h4>0</h4>
                  <p class="mb-0">Active Deals</p>
                </div>
                <div class="align-self-center">
                  <i class="bi bi-briefcase fs-1"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-3">
          <div class="card bg-info text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between">
                <div>
                  <h4>0</h4>
                  <p class="mb-0">Pending Tasks</p>
                </div>
                <div class="align-self-center">
                  <i class="bi bi-check2-square fs-1"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-dark text-white">
              <h5 class="mb-0">Recent Contacts</h5>
            </div>
            <div class="card-body">
              <?php if (!empty($stats['recent_contacts'])): ?>
                <div class="list-group list-group-flush">
                  <?php foreach ($stats['recent_contacts'] as $contact): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                      <div>
                        <strong><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></strong>
                        <br>
                        <small class="text-muted"><?= htmlspecialchars($contact['email']) ?></small>
                      </div>
                      <a href="index.php?action=edit&id=<?= $contact['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <p class="text-muted">No contacts found.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-dark text-white">
              <h5 class="mb-0">Recent Companies</h5>
            </div>
            <div class="card-body">
              <?php if (!empty($stats['recent_companies'])): ?>
                <div class="list-group list-group-flush">
                  <?php foreach ($stats['recent_companies'] as $company): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                      <div>
                        <strong><?= htmlspecialchars($company['company_name']) ?></strong>
                        <br>
                        <small class="text-muted"><?= htmlspecialchars($company['city'] . ', ' . $company['country']) ?></small>
                      </div>
                      <a href="index.php?action=viewCompany&id=<?= $company['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <p class="text-muted">No companies found.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/global/footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/flyhub-ERP/public/assets/js/index.js"></script>
</body>
</html>