<!-- register.php -->
<?php
session_start();
require_once 'includes/db.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = "Email already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_email'] = $email;
            header("Location: cart.php"); // go to cart after registering
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
        <h2 class="mb-4 text-center">Register</h2>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endforeach; ?>

        <form method="post">
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Register</button>
            <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</div>
<?php require 'templates/footer.php'; ?>
</body>
</html>
