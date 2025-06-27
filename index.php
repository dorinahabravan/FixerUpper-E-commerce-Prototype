<?php
session_start();
require_once 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FixerUpper - IT Hardware</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php require_once 'templates/header.php'; ?>
<!-- Hero Section -->
<!-- Hero Section with Background Image -->
<section class="hero-section text-white text-center py-5 mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold">Upgrade Your Workspace</h1>
        <p class="lead mb-4">Get 10% Off Your First Order ⚡</p>
        <a href="#products" class="btn btn-light btn-lg px-4">Shop Now</a>
    </div>
</section>



<div class="container mt-4">
    <h1 class="mb-4">Shop IT Hardware</h1>

    <div class="row" id="products">
        <?php
        $stmt = $pdo->query("SELECT * FROM products ORDER BY date_added DESC");

        while ($product = $stmt->fetch()):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
    <!-- ✅ Corrected Image Line -->
<img src="assets/images/<?= htmlspecialchars($product['image']) ?>" 
     class="card-img-top" 
     alt="<?= htmlspecialchars($product['name']) ?>" 
     style="height: 200px; object-fit: contain; background-color: #f8f9fa;">


    <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
        <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
        <p class="card-text"><strong>£<?= number_format($product['price'], 2) ?></strong></p>

        <form action="cart.php" method="POST" class="mt-auto">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
        </form>

        <!-- ✅ View More button moved outside form (best practice) -->
        <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-outline-secondary mt-2 w-100">View More</a>
    </div>
</div>

        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
