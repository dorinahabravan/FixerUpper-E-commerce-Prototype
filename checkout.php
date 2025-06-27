<?php
// Only logged-in users can access this page
session_start();


?><?php

require_once 'includes/db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require 'templates/header.php'; ?>

<div class="container py-5">
    <h2>Checkout</h2>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <!-- ❗ Show login/register warning -->
        <div class="alert alert-warning">
            <strong>Note:</strong> You are not logged in.
            <br>Please <a href="login.php">log in</a> or <a href="register.php">create an account</a> to save your order under your profile.
        </div>
    <?php else: ?>
        <!-- ✅ Show logged-in message -->
        <div class="alert alert-success">
            Welcome, <strong><?= htmlspecialchars($_SESSION['user_email']) ?></strong>. You’re ready to complete your order.
        </div>
    <?php endif; ?>

    <!-- 🛒 Checkout form (always visible, even if not logged in) -->
    <form action="confirm_order.php" method="POST">
        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required value="<?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '' ?>">
        </div>

        <div class="mb-3">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Shipping Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Place Order</button>
    </form>
</div>

<?php require 'templates/footer.php'; ?>
</body>
</html>

