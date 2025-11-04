<?php
include('includes/header.php');
include('../config/dbCon.php');

$editData = null;

// Fetch all products for the dropdown
$products = mysqli_query($conn, "SELECT id, name FROM products");

// Fetch product cost data when edit_id is set
if (isset($_GET['edit_id'])) {
  $edit_id = $_GET['edit_id'];
  $editQuery = "SELECT * FROM products_cost WHERE id = '$edit_id'";
  $editResult = mysqli_query($conn, $editQuery);

  if (mysqli_num_rows($editResult) > 0) {
    $editData = mysqli_fetch_assoc($editResult);
  }
}

// Fetch all product cost records with search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "
    SELECT pc.*, p.name AS product_name 
    FROM products_cost pc
    LEFT JOIN products p ON pc.product_id = p.id
    WHERE p.name LIKE '%$search%'
    ORDER BY pc.date DESC
";
$productCosts = mysqli_query($conn, $query);
?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-money-bill-wave fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Product Cost Management</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="products.php" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item active">Product Cost</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="products.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Products
    </a>
  </div>

  <!-- Product Cost Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <h5 class="card-title mb-0">
        <i class="fas fa-plus-circle text-primary me-2"></i>
        <?= $editData ? 'Update Product Cost' : 'Add Product Cost' ?>
      </h5>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <!-- Product Cost Form -->
      <form action="product-cost-code.php" method="POST" id="product-cost-form">
        <input type="hidden" name="edit_id" value="<?= $editData ? $editData['id'] : ''; ?>">
        <div class="row">
          <!-- Product Selection -->
          <div class="col-md-3 mb-4">
            <label class="form-label fw-semibold">
              Select Product <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-cube text-muted"></i>
              </span>
              <select name="product_id" class="form-select" required>
                <option value="">-- Select Product --</option>
                <?php
                while ($product = mysqli_fetch_assoc($products)) {
                  $selected = ($editData && $editData['product_id'] == $product['id']) ? 'selected' : '';
                  echo "<option value='{$product['id']}' $selected>{$product['name']}</option>";
                }
                ?>
              </select>
            </div>
          </div>

          <!-- Quantity -->
          <div class="col-md-2 mb-4">
            <label class="form-label fw-semibold">
              Quantity <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-boxes text-muted"></i>
              </span>
              <input type="number" name="quantity" class="form-control"
                placeholder="Qty"
                value="<?= $editData ? $editData['quantity'] : ''; ?>"
                min="1" required>
            </div>
          </div>

          <!-- Unit Price -->
          <div class="col-md-2 mb-4">
            <label class="form-label fw-semibold">
              Unit Price (Rs.) <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">Rs.</span>
              <input type="number" step="0.01" name="unit_price" class="form-control"
                placeholder="0.00"
                value="<?= $editData ? $editData['unit_price'] : ''; ?>"
                min="0" required>
            </div>
          </div>

          <!-- Total Cost -->
          <div class="col-md-2 mb-4">
            <label class="form-label fw-semibold">Total Cost (Rs.)</label>
            <div class="input-group">
              <span class="input-group-text bg-light">Rs.</span>
              <input type="number" step="0.01" name="total_cost" class="form-control"
                placeholder="0.00" readonly
                value="<?= $editData ? $editData['total_cost'] : ''; ?>">
            </div>
            <div class="form-text text-success fw-semibold" id="costCalculation"></div>
          </div>

          <!-- Date -->
          <div class="col-md-2 mb-4">
            <label class="form-label fw-semibold">Date</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-calendar text-muted"></i>
              </span>
              <input type="date" name="date" class="form-control"
                value="<?= $editData && $editData['date'] ? $editData['date'] : date('Y-m-d'); ?>"
                readonly>
            </div>
          </div>

          <!-- Action Button -->
          <div class="col-md-1 mb-4">
            <label class="form-label">&nbsp;</label>
            <button type="submit" name="<?= $editData ? 'updateCost' : 'saveCost'; ?>"
              class="btn btn-primary w-100">
              <i class="fas fa-save me-1"></i>
              <?= $editData ? 'Update' : 'Save'; ?>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Product Cost Records Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h5 class="card-title mb-0">
            <i class="fas fa-list text-primary me-2"></i>Cost Records
            <?php if (mysqli_num_rows($productCosts) > 0): ?>
              <span class="badge bg-primary ms-2"><?= mysqli_num_rows($productCosts); ?> records</span>
            <?php endif; ?>
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
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-filter me-1"></i>Filter
            </button>
            <?php if (isset($_GET['search'])): ?>
              <a href="product-cost.php" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i>
              </a>
            <?php endif; ?>
          </form>
        </div>
      </div>
    </div>
    <div class="card-body">
      <!-- Table to Display Product Cost Data -->
      <div class="table-responsive">
        <table class="table table-hover table-bordered">
          <thead class="table-light">
            <tr>
              <th width="25%">Product Name</th>
              <th width="15%">Quantity</th>
              <th width="15%">Unit Price (Rs.)</th>
              <th width="15%">Total Cost (Rs.)</th>
              <th width="15%">Date</th>
              <th width="15%">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (mysqli_num_rows($productCosts) > 0) {
              $grandTotal = 0;
              while ($cost = mysqli_fetch_assoc($productCosts)) :
                $grandTotal += $cost['total_cost'];
            ?>
                <tr>
                  <td>
                    <strong><?= $cost['product_name']; ?></strong>
                  </td>
                  <td>
                    <span class="badge bg-dark"><?= $cost['quantity']; ?></span>
                  </td>
                  <td>
                    <strong class="text-success">Rs. <?= number_format($cost['unit_price'], 2); ?></strong>
                  </td>
                  <td>
                    <strong class="text-primary">Rs. <?= number_format($cost['total_cost'], 2); ?></strong>
                  </td>
                  <td>
                    <small class="text-muted">
                      <?= date('M j, Y', strtotime($cost['date'])); ?>
                    </small>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="product-cost.php?edit_id=<?= $cost['id']; ?>"
                        class="btn btn-outline-primary"
                        title="Edit Cost">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="#"
                        class="btn btn-outline-danger delete-btn"
                        data-delete-url="product-cost-code.php?delete_id=<?= $cost['id']; ?>"
                        data-product-name="<?= htmlspecialchars($cost['product_name']); ?>"
                        title="Delete Cost">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
            <?php
              endwhile;
            } else {
              echo '<tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>No Cost Records Found</h5>
                                            <p>Start by adding your first product cost record above.</p>
                                        </div>
                                    </td>
                                  </tr>';
            }
            ?>
          </tbody>
          <?php if (mysqli_num_rows($productCosts) > 0): ?>
            <tfoot class="table-light">
              <tr>
                <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                <td class="fw-bold text-success">Rs. <?= number_format($grandTotal, 2); ?></td>
                <td colspan="2"></td>
              </tr>
            </tfoot>
          <?php endif; ?>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
  .card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
  }

  .table th {
    border-top: none;
    font-weight: 600;
    color: #6e707e;
  }

  .input-group-text {
    border-right: none;
  }

  .form-control:focus+.input-group-text {
    border-color: #86b7fe;
    border-right: 1px solid #86b7fe;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Calculate total cost automatically
    const quantityInput = document.querySelector('input[name="quantity"]');
    const unitPriceInput = document.querySelector('input[name="unit_price"]');
    const totalCostInput = document.querySelector('input[name="total_cost"]');
    const costCalculation = document.getElementById('costCalculation');

    function calculateTotalCost() {
      const quantity = parseFloat(quantityInput.value) || 0;
      const unitPrice = parseFloat(unitPriceInput.value) || 0;
      const totalCost = quantity * unitPrice;

      totalCostInput.value = totalCost.toFixed(2);

      // Update calculation text
      if (quantity > 0 && unitPrice > 0) {
        costCalculation.textContent = `${quantity} × Rs. ${unitPrice.toFixed(2)} = Rs. ${totalCost.toFixed(2)}`;
      } else {
        costCalculation.textContent = '';
      }
    }

    quantityInput.addEventListener('input', calculateTotalCost);
    unitPriceInput.addEventListener('input', calculateTotalCost);

    // Initialize calculation on page load
    calculateTotalCost();

    // Enhanced SweetAlert for delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();

        const deleteUrl = this.getAttribute('data-delete-url');
        const productName = this.getAttribute('data-product-name');

        Swal.fire({
          title: 'Delete Cost Record?',
          html: `You are about to delete the cost record for <strong>"${productName}"</strong>. This action cannot be undone.`,
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

    // Form validation
    const productCostForm = document.getElementById('product-cost-form');
    if (productCostForm) {
      productCostForm.addEventListener('submit', function(e) {
        const quantity = parseFloat(quantityInput.value);
        const unitPrice = parseFloat(unitPriceInput.value);

        if (quantity <= 0) {
          e.preventDefault();
          Swal.fire({
            title: 'Invalid Quantity',
            text: 'Please enter a valid quantity greater than 0.',
            icon: 'warning',
            confirmButtonColor: '#3085d6'
          });
          quantityInput.focus();
          return;
        }

        if (unitPrice <= 0) {
          e.preventDefault();
          Swal.fire({
            title: 'Invalid Unit Price',
            text: 'Please enter a valid unit price greater than 0.',
            icon: 'warning',
            confirmButtonColor: '#3085d6'
          });
          unitPriceInput.focus();
          return;
        }
      });
    }

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