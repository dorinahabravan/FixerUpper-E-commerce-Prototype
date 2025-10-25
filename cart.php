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

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['saved'])) {
    $_SESSION['saved'] = [];
}

// Save for Later Functionality
// This code is for preventing SQL Injection
// We cast IDs to integers to avoid malicious input via GET
if (isset($_GET['save'])) {
    $save_id = (int) $_GET['save'];
    if (isset($_SESSION['cart'][$save_id])) {
        $_SESSION['saved'][$save_id] = $_SESSION['cart'][$save_id];
        unset($_SESSION['cart'][$save_id]);
    }
    header('Location: cart.php');
    exit;
}


// Move to Cart
if (isset($_GET['move_to_cart'])) {
    $move_id = (int) $_GET['move_to_cart'];
    if (isset($_SESSION['saved'][$move_id])) {
        $_SESSION['cart'][$move_id] = $_SESSION['saved'][$move_id];
        unset($_SESSION['saved'][$move_id]);
    }
    header('Location: cart.php');
    exit;
}


// Add to Cart
// This code is for preventing SQL Injection
// Cast POSTed product ID to integer
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


// Remove item
//Cast remove ID to integer to prevent malicious input
if (isset($_GET['remove'])) {
    $remove_id = (int) $_GET['remove'];
    unset($_SESSION['cart'][$remove_id]);
    header('Location: cart.php');
    exit;
}


// Update quantities
// Sanitise quantities to ensure they're integers >= 1
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
  

 <?php if (empty($_SESSION['cart'])): ?>
  <div class="text-center py-5 container content mt-5">
    <h3 class="mb-3">Your cart is currently empty 🛒</h3>
    <p class="text-muted mb-4">Looks like you haven't added anything yet.</p>
    <a href="index.php#products" class="btn btn-warning btn-lg px-4 rounded-pill" style="background-color: #b5883f; color: white;">Start Shopping</a>
  </div>
<?php else: ?>
  ...

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
                  <!-- This code is for preventing Cross-Site Scripting (XSS) -->
                <img src="assets/images/<?= htmlspecialchars($product['image'] ?? '') ?>" class="img-fluid rounded-start" alt="Product Image">
              </div>
              <div class="col-md-9">
                <div class="card-body">
                  <!-- This code is for preventing Cross-Site Scripting (XSS) -->
                  <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                  <p class="card-text mb-1">Price: £<?= number_format($product['price'], 2) ?></p>
                  
                  <div class="d-flex justify-content-between align-items-end mt-4">
                    <div class="d-flex align-items-center">
                      <label class="me-2 mb-0">Qty:</label>
                      <input type="number" name="quantities[<?= $product_id ?>]" value="<?= $quantity ?>" min="1" class="form-control form-control-sm me-3" style="width: 70px;">
                      <a href="?remove=<?= $product_id ?>" class="btn btn-outline-danger btn-sm rounded-pill">Remove</a>
                      <a href="?save=<?= $product_id ?>" class="btn btn-outline-secondary btn-sm rounded-pill ms-2">Save for Later</a>
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

            <button type="submit" name="update_cart" class="btn btn-warning w-100 mb-2 rounded-pill " style="background-color: #b5883f; color: white;">Update Cart</button>
           <?php if (isset($_SESSION['user_id'])): ?>
  <a href="checkout.php" class="btn btn-success rounded-pill" >Proceed to Checkout</a>
<?php else: ?>
  <a href="register.php" class="btn btn-success rounded-pill">Proceed to Checkout</a>
<?php endif; ?>
          </div>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>

<!-- Saved for Later Section -->
<?php if (!empty($_SESSION['saved'])): ?>
  <hr class="my-5">
  <div class="container mt-5">
    <h4 class="mb-4">Saved for Later</h4>
    <div class="row">
      <div class="col-lg-8">
        <?php foreach ($_SESSION['saved'] as $product_id => $quantity): ?>
          <?php
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();
            if (!$product) continue;
          ?>
          <div class="card mb-4 shadow-sm" >
            <div class="row g-0">
              <div class="col-md-3">
                  <!-- This code is for preventing Cross-Site Scripting (XSS) -->
                <img src="assets/images/<?= htmlspecialchars($product['image'] ?? '') ?>" class="img-fluid rounded-start" alt="Product Image">
              </div>
              <div class="col-md-9">
                <div class="card-body d-flex justify-content-between align-items-center">
                  
                  <div>
                    <h5 class="card-title mb-1"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text mb-1">Price: £<?= number_format($product['price'], 2) ?></p>
                  </div>
                  <div>
<a href="?move_to_cart=<?= $product_id ?>" class="btn btn-outline-secondary btn-sm rounded-pill ms-2" style="background-color:#b5883f; color: white;">Move to Cart</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>







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
<?php require_once 'templates/footer.php'; ?>
</body>
</html>


