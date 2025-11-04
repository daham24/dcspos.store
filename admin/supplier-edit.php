<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mt-4 mb-5">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-edit fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Edit Supplier</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="suppliers.php" class="text-decoration-none">Suppliers</a></li>
            <li class="breadcrumb-item active">Edit Supplier</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="suppliers.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Suppliers
    </a>
  </div>

  <?php
  $supplierId = validate($_GET['id']);
  $supplier = getById('suppliers', $supplierId);
  if ($supplier['status'] != 200) {
    redirect('suppliers.php', $supplier['message']);
  }
  ?>

  <!-- Edit Supplier Information Card -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
      <h4 class="mb-0 text-dark">
        <i class="fas fa-building me-2 text-primary"></i>Supplier Information
      </h4>
      <span class="badge bg-light text-dark border">
        ID: <?= $supplier['data']['id']; ?>
      </span>
    </div>
    <div class="card-body p-4">
      <?php alertMessage(); ?>

      <form action="code.php" method="POST" id="editSupplierForm">
        <input type="hidden" name="supplierId" value="<?= $supplier['data']['id']; ?>">

        <div class="row">
          <!-- Supplier Name -->
          <div class="col-md-12 mb-3">
            <label for="name" class="form-label fw-semibold">Supplier Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-building text-muted"></i></span>
              <input type="text" name="name" id="name" value="<?= $supplier['data']['name']; ?>" required class="form-control" placeholder="Enter supplier name" />
            </div>
          </div>

          <!-- Email -->
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
              <input type="email" name="email" id="email" value="<?= $supplier['data']['email']; ?>" class="form-control" placeholder="Enter email address" />
            </div>
          </div>

          <!-- Phone -->
          <div class="col-md-6 mb-3">
            <label for="phone" class="form-label fw-semibold">Phone Number</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
              <input type="text" name="phone" id="phone" value="<?= $supplier['data']['phone']; ?>" class="form-control" placeholder="Enter phone number" />
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-12 mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" <?= $supplier['data']['status'] == 1 ? 'checked' : ''; ?> style="width: 3rem; height: 1.5rem;">
              <label class="form-check-label fw-semibold ms-2" for="status">
                <i class="fas fa-eye-slash me-1 text-warning"></i>Hide Supplier
              </label>
            </div>
            <small class="text-muted">When checked, this supplier will be hidden from selection lists</small>
          </div>

          <!-- Action Buttons -->
          <div class="col-md-12 mb-3">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <a href="suppliers.php" class="btn btn-outline-secondary me-md-2 px-4">
                <i class="fas fa-times me-1"></i>Cancel
              </a>
              <button type="submit" name="updateSupplier" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i>Update Supplier
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Current Supplier Information -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom py-3">
      <h5 class="mb-0 text-dark">
        <i class="fas fa-info-circle me-2 text-primary"></i>Current Supplier Details
      </h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <div class="border rounded p-3 text-center">
            <h6 class="text-muted mb-2">Current Status</h6>
            <?php if ($supplier['data']['status'] == 1): ?>
              <span class="badge bg-danger">
                <i class="fas fa-eye-slash me-1"></i>Hidden
              </span>
            <?php else: ?>
              <span class="badge bg-success">
                <i class="fas fa-eye me-1"></i>Visible
              </span>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3 text-center">
            <h6 class="text-muted mb-2">Email</h6>
            <p class="fw-bold mb-0"><?= !empty($supplier['data']['email']) ? $supplier['data']['email'] : 'Not set' ?></p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3 text-center">
            <h6 class="text-muted mb-2">Phone</h6>
            <p class="fw-bold mb-0"><?= !empty($supplier['data']['phone']) ? $supplier['data']['phone'] : 'Not set' ?></p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3 text-center">
            <h6 class="text-muted mb-2">Last Updated</h6>
            <p class="fw-bold mb-0"><?= date('M j, Y', strtotime($supplier['data']['updated_at'] ?? 'Now')) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Products Supplied by Supplier -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom py-3">
      <h4 class="mb-0 text-dark">
        <i class="fas fa-edit me-2 text-primary"></i>Manage Supplier Products
      </h4>
    </div>
    <div class="card-body p-4">
      <?php alertMessage(); ?>

      <?php
      // Fetch products supplied by this supplier
      $supplierProducts = mysqli_query($conn, "
                SELECT sp.id AS supplier_product_id, p.id AS product_id, p.name AS product_name 
                FROM supplier_products sp
                LEFT JOIN products p ON sp.product_id = p.id
                WHERE sp.supplier_id = '$supplierId'
            ");

      if ($supplierProducts && mysqli_num_rows($supplierProducts) > 0) {
      ?>
        <div class="row">
          <?php while ($supplierProduct = mysqli_fetch_assoc($supplierProducts)) : ?>
            <div class="col-md-6 mb-4">
              <div class="card border-0 shadow-sm">
                <div class="card-body">
                  <form action="code.php" method="POST" class="edit-product-form">
                    <input type="hidden" name="supplierProductId" value="<?= $supplierProduct['supplier_product_id']; ?>">
                    <input type="hidden" name="supplierId" value="<?= $supplierId; ?>">

                    <div class="row align-items-center">
                      <div class="col-md-8">
                        <label class="form-label fw-semibold">Product</label>
                        <div class="input-group">
                          <span class="input-group-text bg-light"><i class="fas fa-cube text-muted"></i></span>
                          <select name="productId" class="form-select">
                            <?php
                            $products = mysqli_query($conn, "SELECT id, name FROM products");
                            if ($products && mysqli_num_rows($products) > 0) {
                              while ($product = mysqli_fetch_assoc($products)) {
                                $selected = ($product['id'] == $supplierProduct['product_id']) ? 'selected' : '';
                                echo "<option value='{$product['id']}' $selected>{$product['name']}</option>";
                              }
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="d-grid gap-2">
                          <button type="submit" name="updateSupplierProduct" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sync-alt me-1"></i>Update
                          </button>
                          <button type="button" class="btn btn-outline-danger btn-sm delete-product-btn"
                            data-product-id="<?= $supplierProduct['supplier_product_id']; ?>"
                            data-product-name="<?= $supplierProduct['product_name']; ?>">
                            <i class="fas fa-trash me-1"></i>Remove
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php
      } else {
      ?>
        <div class="text-center py-4">
          <div class="mb-3">
            <i class="fas fa-box-open fa-3x text-muted"></i>
          </div>
          <h5 class="text-muted mb-2">No Products Assigned</h5>
          <p class="text-muted">This supplier doesn't have any products assigned yet.</p>
        </div>
      <?php
      }
      ?>
    </div>
  </div>

  <!-- Add New Products Supplied by Supplier -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom py-3">
      <h4 class="mb-0 text-dark">
        <i class="fas fa-plus-circle me-2 text-primary"></i>Add New Product
      </h4>
    </div>
    <div class="card-body p-4">
      <form action="code.php" method="POST" id="addProductForm">
        <input type="hidden" name="supplierId" value="<?= $supplierId; ?>">

        <div class="row align-items-end">
          <div class="col-md-8 mb-3">
            <label for="newProductId" class="form-label fw-semibold">Select Product <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-cube text-muted"></i></span>
              <select name="productId" id="newProductId" class="form-select" required>
                <option value="">Choose a product...</option>
                <?php
                $products = mysqli_query($conn, "SELECT id, name FROM products");
                if ($products && mysqli_num_rows($products) > 0) {
                  while ($product = mysqli_fetch_assoc($products)) {
                    echo "<option value='{$product['id']}'>{$product['name']}</option>";
                  }
                }
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <div class="d-grid">
              <button type="submit" name="saveSupplierProduct" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Add Product
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
  .card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
  }

  .btn {
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
  }

  .btn:hover {
    transform: translateY(-1px);
  }

  .form-control,
  .form-select {
    border-radius: 0.375rem;
    border: 1px solid #e3e6f0;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #6c757d;
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.1);
  }

  .card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
  }

  .input-group-text {
    background-color: #f8f9fc;
    border: 1px solid #e3e6f0;
  }

  .form-check-input:checked {
    background-color: #ffc107;
    border-color: #ffc107;
  }

  .form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
  }

  .badge {
    font-weight: 500;
  }

  .edit-product-form .card {
    border: 1px solid #e3e6f0 !important;
    box-shadow: none !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Supplier form validation
    const editSupplierForm = document.getElementById('editSupplierForm');
    if (editSupplierForm) {
      editSupplierForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();

        if (!name) {
          e.preventDefault();
          Swal.fire({
            title: 'Missing Information',
            text: 'Supplier name is required.',
            icon: 'warning',
            confirmButtonColor: '#6c757d'
          });
          document.getElementById('name').focus();
          return;
        }

        e.preventDefault();
        Swal.fire({
          title: 'Update Supplier?',
          text: 'Are you sure you want to update this supplier?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, Update',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            editSupplierForm.submit();
          }
        });
      });
    }

    // Add product form validation
    const addProductForm = document.getElementById('addProductForm');
    if (addProductForm) {
      addProductForm.addEventListener('submit', function(e) {
        const productId = document.getElementById('newProductId').value;

        if (!productId) {
          e.preventDefault();
          Swal.fire({
            title: 'Missing Information',
            text: 'Please select a product to add.',
            icon: 'warning',
            confirmButtonColor: '#6c757d'
          });
          return;
        }
      });
    }

    // Delete product buttons
    const deleteButtons = document.querySelectorAll('.delete-product-btn');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        const productName = this.getAttribute('data-product-name');

        Swal.fire({
          title: 'Remove Product?',
          html: `Are you sure you want to remove <strong>${productName}</strong> from this supplier?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, Remove',
          cancelButtonText: 'Cancel',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'code.php';

            const supplierProductIdInput = document.createElement('input');
            supplierProductIdInput.type = 'hidden';
            supplierProductIdInput.name = 'supplierProductId';
            supplierProductIdInput.value = productId;

            const supplierIdInput = document.createElement('input');
            supplierIdInput.type = 'hidden';
            supplierIdInput.name = 'supplierId';
            supplierIdInput.value = <?= $supplierId; ?>;

            const deleteActionInput = document.createElement('input');
            deleteActionInput.type = 'hidden';
            deleteActionInput.name = 'deleteSupplierProduct';
            deleteActionInput.value = '1';

            form.appendChild(supplierProductIdInput);
            form.appendChild(supplierIdInput);
            form.appendChild(deleteActionInput);

            document.body.appendChild(form);
            form.submit();
          }
        });
      });
    });
  });
</script>