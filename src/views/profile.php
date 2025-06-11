<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = intval($_GET['id']);

// DB connection
$conn = new mysqli("localhost", "root", "", "flyhub_erp");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$sql = "SELECT * FROM contacts WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    $conn->close();
    header("Location: index.php");
    exit();
}
$contact = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Contact Profile - Flyhub CRM</title>
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
      height: 32px;
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
    /* Profile Card */
    .profile-actions {
      display: flex;
      gap: 10px;
      align-items: center;
      margin-bottom: 16px;
    }
    .profile-actions .btn {
      min-width: 110px;
    }
    .profile-title {
      font-weight: 700;
      font-size: 1.3rem;
      background: #22292f;
      color: #fff;
      padding: 13px 22px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      margin-bottom: 0;
    }
    .profile-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(44,62,80,.08);
      padding: 0 24px 24px 24px;
      margin-bottom: 28px;
      margin-top: 0;
    }
    .profile-list .profile-label {
      font-size: 1em;
      font-weight: 600;
      color: var(--fly-blue);
      margin-bottom: 2px;
    }
    .profile-list .profile-value, .profile-list input, .profile-list textarea {
      font-size: 1.08em;
      padding: 3px 2px 6px 0;
      margin-bottom: 15px;
      min-height: 32px;
      display: block;
      background: none;
      border: none;
      border-bottom: 1.5px solid #eaeaea;
      color: #222;
      outline: none;
      width: 100%;
      transition: border 0.15s;
      cursor: pointer;
    }
    .profile-list .profile-value.editing {
      background: #f4f9ff;
      border-bottom: 2px solid var(--fly-blue);
      cursor: text;
    }
    .profile-list input, .profile-list textarea {
      border-bottom: 2px solid var(--fly-blue);
      background: #f4f9ff;
      color: #222;
      cursor: text;
      border-radius: 4px;
      box-shadow: none;
    }
    .profile-list input:focus, .profile-list textarea:focus {
      outline: none;
      border-color: var(--fly-orange);
      background: #eaf3fa;
    }
    .profile-list textarea {
      resize: vertical;
      min-height: 38px;
      max-height: 80px;
    }
  </style>
</head>
<body class="bg-light">

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light mb-0 shadow-sm fixed-top" style="z-index: 1040;">
  <div class="container-fluid">
    <button class="sidebar-toggler" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <span class="navbar-brand ms-2">
      <img src="flyhu_logo.webp" class="flyhub-logo" alt="Flyhub Logo">
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
      <a class="nav-link active" href="index.php"><i class="bi bi-person-lines-fill"></i> <span class="sidebar-label">Contacts</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="companies.php"><i class="bi bi-building"></i> <span class="sidebar-label">Companies</span></a>
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
        <div class="profile-actions">
          <form id="profileForm" action="profile_update.php" method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
            <button id="saveBtn" type="submit" class="btn btn-success btn-sm" style="display:none;">
              <i class="bi bi-save me-1"></i> Save Changes
            </button>
          </form>
          <a href="index.php" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back
          </a>
        </div>
        <div class="profile-card">
          <div class="profile-title">Contact Profile</div>
          <div class="profile-list pt-3">
            <!-- Each property: -->
            <div>
              <div class="profile-label">First Name</div>
              <span class="profile-value" id="first_name_value" data-field="first_name"><?php echo htmlspecialchars($contact['first_name']); ?></span>
            </div>
            <div>
              <div class="profile-label">Last Name</div>
              <span class="profile-value" id="last_name_value" data-field="last_name"><?php echo htmlspecialchars($contact['last_name']); ?></span>
            </div>
            <div>
              <div class="profile-label">Email</div>
              <span class="profile-value" id="email_value" data-field="email"><?php echo htmlspecialchars($contact['email']); ?></span>
            </div>
            <div>
              <div class="profile-label">Phone</div>
              <span class="profile-value" id="phone_value" data-field="phone"><?php echo htmlspecialchars($contact['phone']); ?></span>
            </div>
          </div>
        </div>
      </div>
      <!-- Timeline/Activity Column (placeholder) -->
      <div class="col-md-4"></div>
      <!-- 3rd Column Placeholder -->
      <div class="col-md-4"></div>
    </div>
  </div>
</div>

<div class="footer">
  &copy; <?php echo date('Y'); ?> Flyhub CRM
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function() {
    // Sidebar toggle
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

    // Inline editing logic
    let editingField = null;
    let originalValue = "";
    let changed = false;

    $('.profile-value').on('click', function() {
      if (editingField) return; // Only one at a time
      editingField = $(this);
      originalValue = editingField.text();
      let field = editingField.data('field');
      let type = (field === 'email') ? 'email' : (field === 'phone' ? 'text' : 'text');
      let input = $('<input>')
          .addClass('form-control form-control-sm')
          .val(originalValue)
          .attr('name', field)
          .attr('type', type)
          .css('display','inline-block');
      input.width(editingField.width() + 60);
      editingField.hide().after(input);
      input.focus();

      input.on('keydown', function(e) {
        if (e.key === 'Enter') {
          input.blur();
        } else if (e.key === 'Escape') {
          input.val(originalValue);
          input.blur();
        }
      });
      input.on('blur', function() {
        let val = input.val();
        if (val !== originalValue) {
          editingField.text(val);
          editingField.addClass('editing');
          changed = true;
          $('#saveBtn').show();
        }
        editingField.show();
        input.remove();
        editingField = null;
      });
    });

    // Before submitting, add hidden fields for changed values
    $('#profileForm').on('submit', function() {
      $('.profile-value.editing').each(function() {
        let name = $(this).data('field');
        let val = $(this).text();
        $('<input>').attr('type', 'hidden').attr('name', name).val(val).appendTo('#profileForm');
      });
    });

    // If you want to hide Save when not changed, use this:
    // If Save is clicked, reset state
    $('#saveBtn').on('click', function() {
      $('.profile-value').removeClass('editing');
      $('#saveBtn').hide();
      changed = false;
    });
});
</script>
</body>
</html>
