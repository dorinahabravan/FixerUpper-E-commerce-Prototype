<!-- login.php -->
<?php
session_start();
require_once 'includes/db.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     // Sanitise user inputs
    $email = trim($_POST['email']);
    $password = $_POST['password'];

      // This code is for preventing SQL Injection
    // Using prepared statements with placeholders
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

       // The code below is for preventing Password Theft
    // Verifying password using password_verify
    if ($user && password_verify($password, $user['password_hash'])) {

           //This code is for preventing Session Hijacking
        // Regenerating session ID on login to prevent session fixation attacks
        session_regenerate_id(true);

          //Securely storing user info in session
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
        <!-- Show login/register warning -->
        <div class="alert alert-warning">
            <strong>Note:</strong> You are not logged in.
            <br>Please <a href="login.php">log in</a> or <a href="register.php">create an account</a> to save your order under your profile.
        </div>
        <h2 class="mb-4 text-center">Login</h2>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endforeach; ?>

        <form method="POST" style="max-width: 800px; margin: 20px auto;  padding: 30px; border-radius: 10px">
            <div class="mb-3">
                <input type="email" name="email" class="form-control rounded-pill" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control rounded-pill" placeholder="Password" required>
            </div>
            <button type="submit" style="background-color:#b5883f;" class="btn btn-primary w-100 rounded-pill">Login</button>
            <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
</div>
<?php require 'templates/footer.php'; ?>
</body>
</html>
