<nav class="navbar navbar-expand-lg navbar-light mb-0 shadow-sm fixed-top" style="z-index:1040">
    <div class="container-fluid">
      <button class="sidebar-toggler" id="sidebarToggle"><i class="bi bi-list"></i></button>
      <span class="navbar-brand ms-2">
        <img src="flyhub-logo.png" class="flyhub-logo" alt="Flyhub Logo">
        Flyhub CRM
      </span>
      <div class="d-flex align-items-center">
        <span class="me-3">👋 Hello, <?= htmlspecialchars($_SESSION['user']); ?></span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
      </div>
    </div>
  </nav>