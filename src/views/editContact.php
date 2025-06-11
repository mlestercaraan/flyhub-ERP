<?php
// editContact.php
/** @var array $contact */
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit <?= htmlspecialchars($contact['first_name'].' '.$contact['last_name']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h1>Edit Contact</h1>
  <form action="index.php?action=update" method="POST">
    <input type="hidden" name="id" value="<?= $contact['id'] ?>">
    <div class="mb-3">
      <label>First Name</label>
      <input name="first_name" class="form-control" value="<?= htmlspecialchars($contact['first_name']) ?>">
    </div>
    <div class="mb-3">
      <label>Last Name</label>
      <input name="last_name" class="form-control" value="<?= htmlspecialchars($contact['last_name']) ?>">
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input name="email" class="form-control" value="<?= htmlspecialchars($contact['email']) ?>">
    </div>
    <div class="mb-3">
      <label>Phone</label>
      <input name="phone" class="form-control" value="<?= htmlspecialchars($contact['phone']) ?>">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</body>
</html>
