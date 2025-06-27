<?php
session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";


require_once 'includes/db.php';

session_start();
require_once 'includes/db.php';

// ✅ HARD BLOCK if user not logged in
if (!isset($_SESSION['user_id'])) {
    echo "<div style='padding:2rem; background:#f8d7da; color:#721c24; font-family:sans-serif;'>
            <h2>You must be logged in to confirm an order.</h2>
            <a href='login.php'>Click here to login</a>
          </div>";
    exit;
}

$cart = $_SESSION['cart'] ?? [];

// Only process if it's a POST request and the cart has items
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cart)) {
    // Get form inputs
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $address = trim($_POST['address']);

    // Attach user ID if logged in, otherwise null
    $userId = $_SESSION['user_id'] ?? null;

    // Get product details for the items in cart
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute(array_keys($cart));
    $products = $stmt->fetchAll();

    // Calculate total cost
    $total = 0;
    foreach ($products as $product) {
        $total += $product['price'] * $cart[$product['id']];
    }

    // Insert into orders table
    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, customer_name, customer_email, address, total)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$userId, $name, $email, $address, $total]);
    $orderId = $pdo->lastInsertId(); // Get the new order ID

    // Insert order items
    $stmtItem = $pdo->prepare("
        INSERT INTO order_items (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");
    foreach ($products as $product) {
        $stmtItem->execute([
            $orderId,
            $product['id'],
            $cart[$product['id']],
            $product['price']
        ]);
    }

    // Empty the cart after order
    unset($_SESSION['cart']);

    // Confirmation flag
    $confirmation = true;
} else {
    // Redirect back to cart if accessed incorrectly
    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require 'templates/header.php'; ?>
<div class="container py-5">
    <?php if (isset($confirmation)): ?>
        <h2 class="mb-4">✅ Thank You for Your Order!</h2>
        <p>Your order has been placed successfully. We will contact you shortly by email.</p>
        <a href="index.php" class="btn btn-primary mt-3">Continue Shopping</a>
    <?php endif; ?>
</div>
<?php require 'templates/footer.php'; ?>
</body>
</html>
