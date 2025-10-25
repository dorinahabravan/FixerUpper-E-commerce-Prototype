<?php
session_start();
require_once 'includes/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?message=login_required');
    exit;
}

// Get user's orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require_once 'templates/header.php'; ?>
<div class="container py-5">
    <h2>My Orders</h2>

    <?php if (count($orders) === 0): ?>
        <p>You haven’t placed any orders yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total (£)</th>
                        <th>Shipping Address</th>
                        <th>Placed On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= number_format($order['total'], 2) ?></td>
                            <!--  XSS Protection: escape user input before displaying -->
                            <td><?= htmlspecialchars($order['address']) ?></td>
                            <td><?= $order['created_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require 'templates/footer.php'; ?>
</body>
</html>
