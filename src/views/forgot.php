<?php
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    // In real use, send an email with a reset link. Here we just simulate.
    $msg = "If an account with that email exists, a reset link was sent.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-warning"><h4>Forgot Password</h4></div>
                <div class="card-body">
                    <?php if($msg): ?><div class="alert alert-info"><?php echo $msg; ?></div><?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button class="btn btn-warning w-100" type="submit">Reset Password</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="login.php">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
