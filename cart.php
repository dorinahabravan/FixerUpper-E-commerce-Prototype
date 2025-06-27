<?php
// Show any PHP errors during development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session to store and access the cart
session_start();

// Include the database connection
require_once 'includes/db.php';

// If cart doesn't exist in session, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// -------------------------
// Add to Cart
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int) $_POST['product_id'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    header('Location: cart.php');
    exit;
}

// -------------------------
// Remove item
// -------------------------
if (isset($_GET['remove'])) {
    $remove_id = (int) $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header('Location: cart.php');
    exit;
}

// -------------------------
// Update quantities
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        $_SESSION['cart'][(int)$id] = max(1, (int)$qty);
    }
    header('Location: cart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - FixerUpper</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Include header -->
<?php
require_once 'templates/header.php';

?>

<div class="container mt-5">
  <h2 class="mb-4">Your Shopping Cart</h2>

  <?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info">Your cart is currently empty.</div>
  <?php else: ?>
    <form method="post">
      <div class="row">
        <!-- LEFT: Cart Items -->
        <div class="col-lg-8">
          <?php
          $total = 0;
          foreach ($_SESSION['cart'] as $product_id => $quantity):
              $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
              $stmt->execute([$product_id]);
              $product = $stmt->fetch();

              if ($product):
                  $subtotal = $product['price'] * $quantity;
                  $total += $subtotal;
          ?>
          <div class="card mb-4 shadow-sm">
            <div class="row g-0">
              <div class="col-md-3">
                <img src="assets/images/<?= htmlspecialchars($product['image'] ?? '') ?>" class="img-fluid rounded-start" alt="Product Image">
              </div>
              <div class="col-md-9">
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                  <p class="card-text mb-1">Price: £<?= number_format($product['price'], 2) ?></p>
                  
                  <div class="d-flex justify-content-between align-items-end mt-4">
                    <div class="d-flex align-items-center">
                      <label class="me-2 mb-0">Qty:</label>
                      <input type="number" name="quantities[<?= $product_id ?>]" value="<?= $quantity ?>" min="1" class="form-control form-control-sm me-3" style="width: 70px;">
                      <a href="?remove=<?= $product_id ?>" class="btn btn-outline-danger btn-sm">Remove</a>
                    </div>
                    <div class="text-end">
                      <small class="text-muted">Subtotal:</small><br>
                      <strong>£<?= number_format($subtotal, 2) ?></strong>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif; endforeach; ?>
        </div>

        <!-- RIGHT: Order Summary -->
        <div class="col-lg-4">
          <div class="card bg-light p-4 shadow-sm">
            <h5 class="mb-3">Order Summary</h5>
            <p class="mb-2">Total Items: <strong><?= count($_SESSION['cart']) ?></strong></p>
            <p class="mb-3">Total Amount: <strong>£<?= number_format($total, 2) ?></strong></p>

            <button type="submit" name="update_cart" class="btn btn-warning w-100 mb-2">Update Cart</button>
            <a href="register.php" class="btn btn-success w-100">Proceed to Checkout</a>
          </div>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>




<footer class="text-center text-muted mt-5">
  <p>&copy; <?= date('Y') ?> FixerUpper. All rights reserved.</p>
</footer>

<!-- ✅ Bootstrap Bundle JS with Popper (needed for dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Modal for login/register prompt -->
<div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="loginPromptLabel">Login Required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You need to be logged in to proceed to checkout.
      </div>
      <div class="modal-footer">
        <a href="login.php" class="btn btn-primary">Login</a>
        <a href="register.php" class="btn btn-secondary">Register</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>


