<?php
session_start();
require_once 'includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?message=login_required');
    exit;
}

$success = '';
$errors = [];

// Get current user data
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']);
    $email       = trim($_POST['email']);
    $currentPass = $_POST['current_password'] ?? '';
    $newPass     = $_POST['new_password'] ?? '';

    // Validate fields
    if (empty($name) || empty($email)) {
        $errors[] = "Name and Email cannot be empty.";
    }

    // Check if email was changed and is already used by another user
    if ($email !== $user['email']) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $errors[] = "This email is already registered by another user.";
        }
    }

    // If no validation errors, proceed to update
    if (empty($errors)) {
        // Update name and email
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $_SESSION['user_id']]);
        $success = "Profile updated successfully.";
        $_SESSION['user_email'] = $email; // Update session value
        $user['name'] = $name;
        $user['email'] = $email;
    }

    // If changing password
    if (!empty($currentPass) && !empty($newPass)) {
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $row = $stmt->fetch();

        if ($row && password_verify($currentPass, $row['password_hash'])) {
            $newHash = password_hash($newPass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->execute([$newHash, $_SESSION['user_id']]);
            $success .= "<br>Password changed successfully.";
        } else {
            $errors[] = "Current password is incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require 'templates/header.php'; ?>

<div class="container py-5">
    <h2>Account Settings</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php foreach ($errors as $error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endforeach; ?>

    <form method="POST" style="max-width: 800px; margin: 20px auto;  padding: 30px; border-radius: 10px;">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control rounded-pill" required>
        </div>

        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control rounded-pill" required>
        </div>

        <hr>
        <h5>Change Password</h5>
        <div class="mb-3">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control rounded-pill" placeholder="Enter current password">
        </div>
        <div class="mb-3">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control rounded-pill" placeholder="Enter new password">
        </div>

       <div class="text-center mt-3">
  <button type="submit" class="btn btn-primary rounded-pill px-4" style="background-color:#b5883f;">Update Account</button>
</div>
    </form>
</div>

<?php require 'templates/footer.php'; ?>
</body>
</html>
