<?php
session_start();
$login_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "crud_demo");
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$username'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $login_msg = "Invalid credentials!";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-dark text-white"><h4>Login</h4></div>
                <div class="card-body">
                    <?php if($login_msg): ?><div class="alert alert-danger"><?php echo $login_msg; ?></div><?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label>Username or Email</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Login</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="register.php">Register</a> | <a href="forgot.php">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
