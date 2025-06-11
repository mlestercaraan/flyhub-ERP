<?php
// editCompany.php
/** @var array $company */
$company = $result ?? [];
if (!$company) {
    header('Location: index.php?action=listCompanies');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit <?= htmlspecialchars($company['company_name']) ?> - Flyhub CRM</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/flyhub-ERP/public/assets/css/index.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light">

  <?php include __DIR__ . '/global/topbar.php'; ?>
  <?php include __DIR__ . '/global/sidebar.php'; ?>

  <div class="content">
    <div class="container py-4">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header bg-dark text-white">
              <h4 class="mb-0">Edit Company</h4>
            </div>
            <div class="card-body">
              <form action="index.php?action=updateCompany" method="POST">
                <input type="hidden" name="id" value="<?= $company['id'] ?>">
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Company Name *</label>
                      <input type="text" name="company_name" class="form-control" 
                             value="<?= htmlspecialchars($company['company_name']) ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Industry</label>
                      <input type="text" name="industry" class="form-control" 
                             value="<?= htmlspecialchars($company['industry'] ?? '') ?>">
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Website URL</label>
                      <input type="url" name="website_url" class="form-control" 
                             value="<?= htmlspecialchars($company['website_url'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Phone</label>
                      <input type="text" name="phone" class="form-control" 
                             value="<?= htmlspecialchars($company['phone'] ?? '') ?>">
                    </div>
                  </div>
                </div>
                
                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" 
                         value="<?= htmlspecialchars($company['email'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                  <label class="form-label">Address</label>
                  <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($company['address'] ?? '') ?></textarea>
                </div>
                
                <div class="row">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label class="form-label">City</label>
                      <input type="text" name="city" class="form-control" 
                             value="<?= htmlspecialchars($company['city'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label class="form-label">State</label>
                      <input type="text" name="state" class="form-control" 
                             value="<?= htmlspecialchars($company['state'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label class="form-label">Country</label>
                      <input type="text" name="country" class="form-control" 
                             value="<?= htmlspecialchars($company['country'] ?? '') ?>">
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Postal Code</label>
                      <input type="text" name="postal_code" class="form-control" 
                             value="<?= htmlspecialchars($company['postal_code'] ?? '') ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Status</label>
                      <select name="status" class="form-control">
                        <option value="active" <?= ($company['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($company['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="prospect" <?= ($company['status'] ?? '') === 'prospect' ? 'selected' : '' ?>>Prospect</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="mb-3">
                  <label class="form-label">Notes</label>
                  <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($company['notes'] ?? '') ?></textarea>
                </div>
                
                <div class="text-end">
                  <button type="submit" class="btn btn-success">Save Changes</button>
                  <a href="index.php?action=listCompanies" class="btn btn-secondary">Cancel</a>
                </div>
              </form>
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