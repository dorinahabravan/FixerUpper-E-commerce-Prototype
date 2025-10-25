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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="style.css">
</head>
<body class="home-page">

<?php require_once 'templates/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero-section text-white text-center py-5 mb-5">
  <div class="container">
    <h1 class="display-4 fw-bold">Upgrade Your Workspace</h1>
    <p class="lead mb-4">Get 10% Off Your First Order ⚡</p>
    <a href="#products" class="btn btn-light btn-lg px-4 rounded-pill">Shop Now</a>
  </div>
</section>

<!-- WHY SHOP WITH US -->
<section class="bg-light py-5">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-4 mb-4 mb-md-0">
        <div class="mb-2">
          <i class="bi bi-box-seam display-5 text-warning me-2"></i>
          <i class="bi bi-truck display-5 text-warning"></i>
        </div>
        <h5 class="fw-bold mt-2">Free Delivery</h5>
        <p class="text-muted small mb-0">On orders over £100</p>
      </div>
      <div class="col-md-4 mb-4 mb-md-0">
        <div class="mb-2">
          <i class="bi bi-patch-check display-5 text-warning me-2"></i>
          <i class="bi bi-shield-lock display-5 text-warning"></i>
        </div>
        <h5 class="fw-bold mt-2">12-Month Warranty</h5>
        <p class="text-muted small mb-0">Peace of mind on all items</p>
      </div>
      <div class="col-md-4">
        <div class="mb-2">
          <i class="bi bi-headset display-5 text-warning me-2"></i>
          <i class="bi bi-chat-dots display-5 text-warning"></i>
        </div>
        <h5 class="fw-bold mt-2">Expert Support</h5>
        <p class="text-muted small mb-0">Friendly help when you need it</p>
      </div>
    </div>
  </div>
</section>

<!-- PRODUCTS & SIDEBAR SECTION -->
<div class="container mt-4">
  <div class="row">
    <!-- SIDEBAR (Left Column) -->
    <div class="col-md-3 mb-4">
 <div class="card sidebar-card p-3 shadow-sm">
           <h5 class="mb-3 text-center">Sort & Filter</h5>

        <!-- Real Sort Form (GET) -->
        <form method="GET" id="filterForm">
  <div class="mb-3">
    <label class="form-label">Sort By</label>
    <select name="sort" class="form-select rounded-pill">
      <option value="" <?= empty($_GET['sort']) ? 'selected' : '' ?>>Default</option>
      <option value="price_asc" <?= ($_GET['sort'] ?? '') == 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
      <option value="price_desc" <?= ($_GET['sort'] ?? '') == 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
      <option value="name_asc" <?= ($_GET['sort'] ?? '') == 'name_asc' ? 'selected' : '' ?>>Name A-Z</option>
    </select>
  </div>

  <div class="mb-3">
    <label class="form-label">Category</label>
    <?php
    $availableCategories = ['Laptops', 'Monitors', 'Accessories'];
    foreach ($availableCategories as $cat):
      $checked = (isset($_GET['category']) && in_array($cat, $_GET['category'])) ? 'checked' : '';
    ?>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="category[]" value="<?= $cat ?>" id="<?= $cat ?>" <?= $checked ?>>
        <label class="form-check-label" for="<?= $cat ?>"><?= $cat ?></label>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="mb-3">
    <label class="form-label">Min Price (£)</label>
    <!-- This code is for preventing Cross-Site Scripting (XSS) -->
    <input type="number" name="min_price" class="form-control rounded-pill" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
  </div>

  <div class="mb-3">
    <label class="form-label">Max Price (£)</label>
    <!-- This code is for preventing Cross-Site Scripting (XSS) -->
    <input type="number" name="max_price" class="form-control rounded-pill" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
  </div>

  <button type="submit" class="btn btn-brown w-100 rounded-pill">Apply Filters</button>
</form>

      </div>
    </div>

    <!-- PRODUCTS GRID (Right Column) -->
    <div class="col-md-9">
      <h1 class="mb-4">Shop IT Hardware</h1>
      <div class="row" id="products">
        
        <?php
        // Determine sort order from GET
        $sort = $_GET['sort'] ?? '';
        $orderBy = 'date_added DESC';

        if ($sort === 'price_asc') {
          $orderBy = 'price ASC';
        } elseif ($sort === 'price_desc') {
          $orderBy = 'price DESC';
        } elseif ($sort === 'name_asc') {
          $orderBy = 'name ASC';
        }

        // Fetch products with sort
        $stmt = $pdo->query("SELECT * FROM products ORDER BY $orderBy");
        while ($product = $stmt->fetch()):
        ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100">
            <!-- This code is for preventing Cross-Site Scripting (XSS) -->
            <img src="assets/images/<?= htmlspecialchars($product['image']) ?>" 
                 class="card-img-top" 
                 alt= "<?= htmlspecialchars($product['name']) ?>" 
                 style="height: 200px; object-fit: contain; background-color: #f8f9fa;">
            <div class="card-body d-flex flex-column">
              <!-- This code is for preventing Cross-Site Scripting (XSS) -->
              <h5 class="card-title "><?= htmlspecialchars($product['name']) ?></h5>
              
              <p class="card-text"><strong>£<?= number_format($product['price'], 2) ?></strong></p>
              <form action="cart.php" method="POST" class="mt-auto">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <button type="submit" name="add_to_cart" class="btn btn-brown w-100 rounded-pill">Add to Cart</button>
              </form>
              <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-brown-outline mt-2 w-100 rounded-pill">View More</a>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <div id="loading" class="text-center my-3" style="display:none;">
  <div class="spinner-border text-warning"></div>
</div>

    </div>
  </div>
</div>

<?php require_once 'templates/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('filterForm');
  const productsDiv = document.getElementById('products');
  const loading = document.getElementById('loading');

  form.addEventListener('change', function (e) {
    e.preventDefault();
    applyFilters();
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    applyFilters();
  });

  function applyFilters() {
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);

    loading.style.display = 'block';

    fetch('filter.php?' + params.toString())
      .then(res => res.text())
      .then(html => {
        productsDiv.innerHTML = html;
        loading.style.display = 'none';
      })
      .catch(() => {
        loading.style.display = 'none';
        productsDiv.innerHTML = '<div class="alert alert-danger">Error loading products.</div>';
      });
  }
});
</script>

</body>
</html>
