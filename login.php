<!-- login.php -->
<?php
session_start();
require_once 'includes/db.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        header("Location: cart.php"); // redirect to cart after login
        exit;
    } else {
        $errors[] = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require 'templates/header.php'; ?>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    
    <div class="card p-4 shadow" style="min-width: 400px;">
        <!-- ❗ Show login/register warning -->
        <div class="alert alert-warning">
            <strong>Note:</strong> You are not logged in.
            <br>Please <a href="login.php">log in</a> or <a href="register.php">create an account</a> to save your order under your profile.
        </div>
        <h2 class="mb-4 text-center">Login</h2>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endforeach; ?>

        <form method="post">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
</div>
<?php require 'templates/footer.php'; ?>
</body>
</html>
