<?php
$conn = new mysqli("localhost", "root", "", "crud_demo");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$id = $_GET['id'];
$sql = "SELECT * FROM contacts WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Contact</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow">
          <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Edit Contact</h4>
          </div>
          <div class="card-body">
            <form action="update.php" method="POST">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <div class="mb-3">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($row['first_name']); ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($row['last_name']); ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($row['email']); ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
              </div>
              <button type="submit" class="btn btn-primary w-100">Update Contact</button>
            </form>
            <a href="index.php" class="btn btn-link mt-3">Back to List</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
