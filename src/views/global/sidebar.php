<div class="sidebar" id="sidebarNav">
    <ul class="nav flex-column pt-2">
      <li class="nav-item">
        <a class="nav-link" href="index.php?action=list"><i class="bi bi-house-door"></i> Dashboard</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="index.php?action=list"><i class="bi bi-person-lines-fill"></i> Contacts</a>
      </li>
      <li class="nav-item">
        <a
          class="nav-link <?= ($_GET['action'] ?? '') === 'listCompanies' ? 'active' : '' ?>"
          href="index.php?action=listCompanies"
        >
          <i class="bi bi-building"></i>
          <span class="sidebar-label">Companies</span>
        </a>
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