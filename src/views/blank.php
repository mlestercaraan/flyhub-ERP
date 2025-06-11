<?php
// src/views/blank.php

// 1) Autoload Composer dependencies
require __DIR__ . '/../../vendor/autoload.php';

// 2) Protect the page
include __DIR__ . '/../middleware/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Blank Page – Flyhub CRM</title>
  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <!-- Your custom CSS -->
  <link href="/flyhub-ERP/public/assets/css/index.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
  >
</head>
<body class="bg-light">

  <!-- Top Navbar -->
  <?php include __DIR__ . '/global/topbar.php'; ?>

  <!-- Sidebar -->
  <?php include __DIR__ . '/global/sidebar.php'; ?>

  <!-- Content Area -->
  <div class="content">
    <div class="container py-4">
      <h1>Blank Page</h1>
      <p>This is your new blank page. Start adding your content here.</p>
    </div>
  </div> <!-- /.content -->

  <!-- Footer -->
  <?php include __DIR__ . '/global/footer.php'; ?>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
  ></script>
  <script src="/flyhub-ERP/public/assets/js/index.js"></script>
</body>
</html>
