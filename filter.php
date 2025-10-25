<?php
require_once 'includes/db.php';

function getProductCategory($productName) {
  $name = strtolower($productName);
  if (str_contains($name, 'laptop')) return 'Laptops';
  if (str_contains($name, 'monitor')) return 'Monitors';
  return 'Accessories';
}

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products");
$allProducts = $stmt->fetchAll();

// Filter
$filteredProducts = array_filter($allProducts, function ($product) {
  $category = getProductCategory($product['name']);
  if (!empty($_GET['category']) && is_array($_GET['category'])) {
    if (!in_array($category, $_GET['category'])) return false;
  }
  if (!empty($_GET['min_price']) && $product['price'] < $_GET['min_price']) return false;
  if (!empty($_GET['max_price']) && $product['price'] > $_GET['max_price']) return false;
  return true;
});
$filteredProducts = array_values($filteredProducts);

// Sort
$sort = $_GET['sort'] ?? '';
if ($sort === 'price_asc') {
  usort($filteredProducts, fn($a, $b) => $a['price'] <=> $b['price']);
} elseif ($sort === 'price_desc') {
  usort($filteredProducts, fn($a, $b) => $b['price'] <=> $a['price']);
} elseif ($sort === 'name_asc') {
  usort($filteredProducts, fn($a, $b) => strcasecmp($a['name'], $b['name']));
}

// Output cards
if (empty($filteredProducts)): ?>
  <p class="text-center text-muted">No products match your filters.</p>
<?php endif; ?>

<?php foreach ($filteredProducts as $product): ?>
  <div class="col-md-4 mb-4">
    <div class="card h-100">
      <img src="assets/images/<?= htmlspecialchars($product['image']) ?>" 
           class="card-img-top" 
           alt="<?= htmlspecialchars($product['name']) ?>" 
           style="height: 200px; object-fit: contain; background-color: #f8f9fa;">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
        <p class="card-text"><strong>£<?= number_format($product['price'], 2) ?></strong></p>
        <form action="cart.php" method="POST" class="mt-auto">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <button type="submit" name="add_to_cart" class="btn btn-brown w-100 rounded-pill">Add to Cart</button>
        </form>
        <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-brown-outline mt-2 w-100 rounded-pill">View More</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
