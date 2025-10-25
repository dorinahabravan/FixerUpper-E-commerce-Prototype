<?php


$cartItemCount = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cartItemCount += $qty;
    }
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FixerUpper</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--  Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">FixerUpper</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-center">
        <?php if (isset($_SESSION['user_id'])): ?>
          <!--  Logged-in dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" onclick="return false;"  id="userDropdown" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              <?= htmlspecialchars($_SESSION['user_email']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
              <li><a class="dropdown-item" href="profile.php">Account Settings</a></li>
              <?php if ($cartItemCount > 0 && $currentPage !== 'index.php'): ?>
                <li><a class="dropdown-item" href="cart.php">🛒 Cart (<?= $cartItemCount ?>)</a></li>
              <?php endif; ?>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
            
          </li>
        <?php else: ?>
          <?php if ($currentPage === 'index.php'): ?>
           
            <a href="login.php" class="nav-link me-3">
  <i class="bi bi-person-circle fs-4"></i>
</a>

<a href="cart.php" class="nav-link position-relative">
  <i class="bi bi-cart fs-4"></i>
  <?php if ($cartItemCount > 0): ?>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
      <?= $cartItemCount ?>
    </span>
  <?php endif; ?>
</a>
         
          <?php endif; ?>

          <?php if ($cartItemCount > 0 && $currentPage !== 'index.php'): ?>
            <li class="nav-item">
              <a class="nav-link position-relative" href="cart.php">
                  <i class="bi bi-cart fs-4"></i>

                🛒 Cart
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                  <?= $cartItemCount ?>
                </span>
              </a>
            </li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
