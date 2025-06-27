<?php
session_start();
require_once 'includes/db.php';

// Get product ID from URL safely
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// If no product found
if (!$product) {
    echo "<div class='container py-5'><h2>Product not found.</h2></div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?> - FixerUpper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'templates/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6 mb-4">
            <img src="assets/images/<?= htmlspecialchars($product['image']) ?>" class="img-fluid rounded shadow" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="mb-3"><?= htmlspecialchars($product['name']) ?></h1>
            <h4 class="text-success mb-3">£<?= number_format($product['price'], 2) ?></h4>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

            <!-- Add to Cart Form -->
            <form method="POST" action="cart.php">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" name="add_to_cart" class="btn btn-primary mt-3">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
