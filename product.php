<?php
session_start();
require_once 'includes/db.php';

// Get ID from query
if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}


//Prepared statements with placeholders ro prevent SQL Inkection attacks
$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
  echo "<div class='container my-5'><div class='alert alert-danger'>Product not found.</div></div>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- This code is for preventing Cross-Site Scripting (XSS) -->
  <title><?= htmlspecialchars($product['name']) ?> - FixerUpper</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require_once 'templates/header.php'; ?>

<div class="container my-5">
  <div class="row g-5">
    <!-- Product Image -->
    <div class="col-md-6 text-center">
      <!-- This code is for preventing Cross-Site Scripting (XSS) -->
      <img src="assets/images/<?= htmlspecialchars($product['image']) ?>" class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>

    <!-- Product Info -->
    <div class="col-md-6">
      <!-- This code is for preventing Cross-Site Scripting (XSS) -->
      <h2 class="mb-3"><?= htmlspecialchars($product['name']) ?></h2>
      <h4 class="text-dark mb-3">£<?= number_format($product['price'], 2) ?></h4>
      

     <form action="cart.php" method="POST" class="mb-4">
  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

 <div class="d-flex align-items-center gap-2">
  <label class="mb-0">Qty:</label>
  <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm rounded-pill" style="width: 70px;">
  
  <button type="submit" name="add_to_cart" class="btn btn-brown rounded-pill flex-grow-1">
    Add to Cart
  </button>
</div>

</form>


      <div class="product-short-description mb-4">
        <h5 class="fw-bold mb-2">Key Features:</h5>
        <ul class="list-unstyled">
          <li>✅ High quality refurbished hardware</li>
          <li>✅ Professionally tested and cleaned</li>
          <li>✅ Fast delivery across UK</li>
          <li>✅ 12-month warranty included</li>
        </ul>
      </div>

      <div class="product-description">
        <h5 class="fw-bold mb-2">Technical Specification</h5>
        <!-- This code is for preventing Cross-Site Scripting (XSS) -->
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      </div>

      <div class="mt-4">
        <h6 class="fw-bold">Delivery & Returns</h6>
        <p class="small text-muted">
          Free delivery on orders over £100. Easy returns within 30 days.
        </p>
      </div>

      <div class="mt-3">
        <h6 class="fw-bold">Warranty</h6>
        <p class="small text-muted">
          12-month warranty on all products for peace of mind.
        </p>
      </div>
    </div>
  </div>
</div>

<?php require_once 'templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
