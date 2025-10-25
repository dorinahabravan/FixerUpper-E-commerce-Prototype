
<?php


session_start();
require_once 'includes/db.php';
// Start session securely to track user state
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       //Sanitising user inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
 // Validating required fields
    if (empty($name) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    }
// Validating required email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address.";
}


    if (empty($errors)) {
         // This code is for preventing SQL Injection
        // Using prepared statements with placeholders
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = "Email already registered.";
        } else {
                 //This code is for preventing Password Theft
            // Hashing the password securely before storing
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

              // Again, using prepared statements to prevent SQL Injection
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashedPassword]);
   
            header("Location: login.php?registered=1"); // prompt  to log in after registering
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
    <link href="assets/css/custom.css" rel="stylesheet">
</head>
<body>
<?php require 'templates/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow" style="min-width: 400px;">
        <!--  Show login/register warning -->
        <div class="alert alert-warning">
            <strong>Note:</strong> You are not logged in.
            <br>Please <a href="login.php">log in</a> or <a href="register.php">create an account</a> to save your order under your profile.
        </div>
        <h2 class="mb-4 text-center">Register</h2>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endforeach; ?>

        <form method="POST" style="max-width: 800px; margin: 20px auto;  padding: 30px; border-radius: 10px;">
            <div class="mb-3">
                <input type="text" name="name" class="form-control rounded-pill" placeholder="Name" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control rounded-pill" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control rounded-pill" placeholder="Password" required>
            </div>
            <button type="submit" style="background-color:#b5883f;" class="btn btn-success w-100 rounded-pill">Register</button>
            <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</div>
<?php require 'templates/footer.php'; ?>
</body>
</html>



