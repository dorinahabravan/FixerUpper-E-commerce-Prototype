<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bootstrap Dropdown Test</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ✅ Dropdown Button Test -->
<div class="container mt-5">
  <h2>Dropdown Test</h2>
  <div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
      Open Menu
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="#">Item 1</a></li>
      <li><a class="dropdown-item" href="#">Item 2</a></li>
    </ul>
  </div>
</div>

<!-- ✅ Bootstrap Bundle JS (Popper + Dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
