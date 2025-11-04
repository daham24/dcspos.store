<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-cubes fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Product Management</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Products</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="products-create.php" class="btn btn-primary">
      <i class="fas fa-plus-circle me-1"></i>Add Product
    </a>
  </div>

  <!-- Products Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h5 class="card-title mb-0">
            <i class="fas fa-list text-primary me-2"></i>Product List
          </h5>
        </div>
        <div class="col-md-6">
          <form class="d-flex gap-2" method="GET" action="">
            <div class="input-group">
              <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search products..."
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
              <span class="input-group-text bg-light">
                <i class="fas fa-search text-muted"></i>
              </span>
            </div>
            <select name="category" class="form-select" style="min-width: 180px;">
              <option value="">All Categories</option>
              <?php
              $categories = getAll('categories');
              foreach ($categories as $category) {
                $selected = isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'selected' : '';
                echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
              }
              ?>
            </select>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-filter me-1"></i>Filter
            </button>
            <?php if (isset($_GET['search']) || isset($_GET['category'])): ?>
              <a href="products.php" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i>
              </a>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <?php
      // Fetch products based on search and category filters
      $searchQuery = isset($_GET['search']) ? validate($_GET['search']) : '';
      $categoryFilter = isset($_GET['category']) ? validate($_GET['category']) : '';

      $query = "SELECT p.*, c.name AS category_name, sc.name AS subcategory_name 
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.id
                      LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
                      WHERE 1=1";

      if ($searchQuery) {
        $query .= " AND (p.name LIKE '%$searchQuery%' OR p.description LIKE '%$searchQuery%' OR p.barcode LIKE '%$searchQuery%')";
      }

      if ($categoryFilter) {
        $query .= " AND p.category_id = '$categoryFilter'";
      }

      $query .= " ORDER BY p.created_at DESC";

      $products = mysqli_query($conn, $query);

      if (!$products) {
        echo '<div class="alert alert-danger">Something Went Wrong!</div>';
      } elseif (mysqli_num_rows($products) > 0) {
      ?>
        <div class="table-responsive">
          <table class="table table-hover table-bordered">
            <thead class="table-light">
              <tr>
                <th width="80px">Image</th>
                <th width="200px">Product</th>
                <th width="120px">Category</th>
                <th width="120px">Subcategory</th>
                <th width="100px">Pricing</th>
                <th width="80px">Stock</th>
                <th width="100px">Identification</th>
                <th width="80px">Status</th>
                <th width="100px">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $item) : ?>
                <tr>
                  <td>
                    <div class="product-image-container">
                      <?php if (!empty($item['image'])): ?>
                        <img src="../<?= $item['image']; ?>" class="img-thumbnail" alt="<?= htmlspecialchars($item['name']); ?>">
                      <?php else: ?>
                        <div class="no-image bg-light d-flex align-items-center justify-content-center">
                          <i class="fas fa-cube text-muted"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <div class="product-info">
                      <strong class="d-block"><?= $item['name']; ?></strong>
                      <?php if (!empty($item['description'])): ?>
                        <small class="text-muted d-block text-truncate" style="max-width: 180px;" title="<?= htmlspecialchars($item['description']); ?>">
                          <?= htmlspecialchars($item['description']); ?>
                        </small>
                      <?php endif; ?>
                      <?php if (!empty($item['warranty_period'])): ?>
                        <small class="text-info d-block">
                          <i class="fas fa-shield-alt me-1"></i><?= $item['warranty_period']; ?>
                        </small>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-primary"><?= $item['category_name'] ?? 'N/A'; ?></span>
                  </td>
                  <td>
                    <span class="badge bg-secondary"><?= $item['subcategory_name'] ?? 'N/A'; ?></span>
                  </td>
                  <td>
                    <div class="pricing-info">
                      <div class="d-flex justify-content-between">
                        <small class="text-muted">Actual:</small>
                        <strong class="text-success">Rs. <?= number_format($item['price'], 2); ?></strong>
                      </div>
                      <div class="d-flex justify-content-between">
                        <small class="text-muted">Selling:</small>
                        <strong class="text-primary">Rs. <?= number_format($item['sell_price'], 2); ?></strong>
                      </div>
                      <?php if ($item['discount'] > 0): ?>
                        <div class="d-flex justify-content-between">
                          <small class="text-muted">Discount:</small>
                          <strong class="text-danger">Rs. <?= number_format($item['discount'], 2); ?></strong>
                        </div>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <span class="badge <?= $item['quantity'] > 10 ? 'bg-success' : ($item['quantity'] > 0 ? 'bg-warning' : 'bg-danger'); ?>">
                      <?= $item['quantity']; ?> in stock
                    </span>
                  </td>
                  <td>
                    <div class="identification-info">
                      <?php if (!empty($item['barcode'])): ?>
                        <small class="d-block">
                          <i class="fas fa-barcode me-1"></i>
                          <span title="<?= $item['barcode']; ?>"><?= substr($item['barcode'], 0, 8) . '...'; ?></span>
                        </small>
                      <?php endif; ?>
                      <?php if (!empty($item['imei_code'])): ?>
                        <small class="d-block">
                          <i class="fas fa-mobile me-1"></i>
                          <span title="<?= $item['imei_code']; ?>"><?= substr($item['imei_code'], 0, 6) . '...'; ?></span>
                        </small>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <?php if ($item['status'] == 1): ?>
                      <span class="badge bg-danger">
                        <i class="fas fa-eye-slash me-1"></i>Hidden
                      </span>
                    <?php else: ?>
                      <span class="badge bg-success">
                        <i class="fas fa-eye me-1"></i>Visible
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="products-edit.php?id=<?= $item['id']; ?>"
                        class="btn btn-outline-primary <?= ($_SESSION['role'] == 'staff') ? 'disabled' : ''; ?>"
                        title="Edit Product">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="products-delete.php?id=<?= $item['id']; ?>"
                        class="btn btn-outline-danger delete-btn <?= ($_SESSION['role'] == 'staff') ? 'disabled' : ''; ?>"
                        data-delete-url="products-delete.php?id=<?= $item['id']; ?>"
                        data-product-name="<?= htmlspecialchars($item['name']); ?>"
                        title="Delete Product">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Product Statistics -->
        <div class="row mt-4">
          <div class="col-md-3">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h4 class="mb-0"><?= mysqli_num_rows($products); ?></h4>
                    <small>Total Products</small>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-cube fa-2x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
          // Count low stock products (less than 5)
          $lowStockQuery = "SELECT COUNT(*) as count FROM products WHERE quantity < 5 AND quantity > 0";
          $lowStockResult = mysqli_query($conn, $lowStockQuery);
          $lowStockCount = mysqli_fetch_assoc($lowStockResult)['count'];
          ?>
          <div class="col-md-3">
            <div class="card bg-warning text-dark">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h4 class="mb-0"><?= $lowStockCount; ?></h4>
                    <small>Low Stock</small>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
          // Count out of stock products
          $outOfStockQuery = "SELECT COUNT(*) as count FROM products WHERE quantity = 0";
          $outOfStockResult = mysqli_query($conn, $outOfStockQuery);
          $outOfStockCount = mysqli_fetch_assoc($outOfStockResult)['count'];
          ?>
          <div class="col-md-3">
            <div class="card bg-danger text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h4 class="mb-0"><?= $outOfStockCount; ?></h4>
                    <small>Out of Stock</small>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-times-circle fa-2x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
          // Count hidden products
          $hiddenQuery = "SELECT COUNT(*) as count FROM products WHERE status = 1";
          $hiddenResult = mysqli_query($conn, $hiddenQuery);
          $hiddenCount = mysqli_fetch_assoc($hiddenResult)['count'];
          ?>
          <div class="col-md-3">
            <div class="card bg-secondary text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h4 class="mb-0"><?= $hiddenCount; ?></h4>
                    <small>Hidden Products</small>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-eye-slash fa-2x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      <?php } else { ?>
        <!-- Empty State -->
        <div class="text-center py-5">
          <div class="empty-state-icon mb-3">
            <i class="fas fa-cube fa-4x text-muted"></i>
          </div>
          <h4 class="text-muted">No Products Found</h4>
          <p class="text-muted mb-4">
            <?php if ($searchQuery || $categoryFilter): ?>
              No products match your search criteria. Try adjusting your filters.
            <?php else: ?>
              Get started by adding your first product to the inventory.
            <?php endif; ?>
          </p>
          <?php if (!$searchQuery && !$categoryFilter): ?>
            <a href="products-create.php" class="btn btn-primary">
              <i class="fas fa-plus-circle me-1"></i>Add First Product
            </a>
          <?php else: ?>
            <a href="products.php" class="btn btn-outline-primary">
              <i class="fas fa-times me-1"></i>Clear Filters
            </a>
          <?php endif; ?>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
  .product-image-container {
    width: 60px;
    height: 60px;
  }

  .product-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0.375rem;
  }

  .no-image {
    width: 100%;
    height: 100%;
    border-radius: 0.375rem;
    font-size: 1.5rem;
  }

  .card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
  }

  .table th {
    border-top: none;
    font-weight: 600;
    color: #6e707e;
  }

  .empty-state-icon {
    opacity: 0.5;
  }

  .pricing-info {
    font-size: 0.85rem;
  }

  .identification-info {
    font-size: 0.8rem;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Enhanced SweetAlert for delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();

        // Check if the button is disabled (for staff role)
        if (this.classList.contains('disabled')) {
          return;
        }

        const deleteUrl = this.getAttribute('data-delete-url');
        const productName = this.getAttribute('data-product-name');

        Swal.fire({
          title: 'Delete Product?',
          html: `You are about to delete <strong>"${productName}"</strong>. This action cannot be undone and will remove this product from the system.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel',
          reverseButtons: true,
          backdrop: true
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = deleteUrl;
          }
        });
      });
    });

    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
      row.addEventListener('mouseenter', function() {
        this.style.backgroundColor = '#f8f9fa';
        this.style.transition = 'background-color 0.2s ease-in-out';
      });
      row.addEventListener('mouseleave', function() {
        this.style.backgroundColor = '';
      });
    });
  });
</script>