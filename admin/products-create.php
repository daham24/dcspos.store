<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-cube fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Add New Product</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="products.php" class="text-decoration-none">Products</a></li>
            <li class="breadcrumb-item active">Add Product</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="products.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Products
    </a>
  </div>

  <!-- Add Product Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <h5 class="card-title mb-0">
        <i class="fas fa-plus-circle text-primary me-2"></i>Product Information
      </h5>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <form action="code.php" method="POST" enctype="multipart/form-data" id="productForm">
        <div class="row">
          <!-- Category Selection -->
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">
              Main Category <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-folder text-muted"></i>
              </span>
              <select name="category_id" id="category_id" class="form-select" required onchange="fetchSubcategories(this.value)">
                <option value="">Select Category</option>
                <?php
                $categories = getAll('categories');
                if ($categories) {
                  if (mysqli_num_rows($categories) > 0) {
                    foreach ($categories as $cateItem) {
                      echo '<option value="' . $cateItem['id'] . '">' . $cateItem['name'] . '</option>';
                    }
                  } else {
                    echo '<option value="">No Category Found!</option>';
                  }
                } else {
                  echo '<option value="">Something Went Wrong!</option>';
                }
                ?>
              </select>
            </div>
          </div>

          <!-- Sub Category Selection -->
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Sub Category</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-folder-tree text-muted"></i>
              </span>
              <select name="sub_category_id" id="sub_category_id" class="form-select">
                <option value="">Select Sub Category</option>
              </select>
            </div>
            <div class="form-text">Optional - select a subcategory for better organization</div>
          </div>

          <!-- Product Name -->
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">
              Product Name <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-tag text-muted"></i>
              </span>
              <input type="text" name="name" required class="form-control" placeholder="Enter product name" maxlength="255" />
            </div>
          </div>

          <!-- Description -->
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">Description</label>
            <div class="input-group">
              <span class="input-group-text bg-light align-items-start pt-2">
                <i class="fas fa-align-left text-muted"></i>
              </span>
              <textarea name="description" class="form-control" rows="3" placeholder="Enter product description (optional)" maxlength="500"></textarea>
            </div>
            <div class="form-text">Max 500 characters</div>
          </div>

          <!-- Pricing Section -->
          <div class="col-md-12 mb-4">
            <div class="card border-0 bg-light">
              <div class="card-body">
                <h6 class="card-title mb-3">
                  <i class="fas fa-money-bill-wave text-primary me-2"></i>Pricing Information
                </h6>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">
                      Actual Price (Rs.) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-white">Rs.</span>
                      <input type="number" name="price" required id="actual_price" class="form-control" placeholder="0.00" step="0.01" min="0" oninput="calculateDiscount()" />
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">
                      Selling Price (Rs.) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                      <span class="input-group-text bg-white">Rs.</span>
                      <input type="number" name="sell_price" required id="selling_price" class="form-control" placeholder="0.00" step="0.01" min="0" oninput="calculateDiscount()" />
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Discount</label>
                    <div class="input-group">
                      <span class="input-group-text bg-white">Rs.</span>
                      <input type="number" step="0.01" name="discount" id="discount" class="form-control" readonly>
                    </div>
                    <div id="discountPercentage" class="form-text text-success fw-semibold"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Product Identification -->
          <div class="col-md-12 mb-4">
            <div class="card border-0 bg-light">
              <div class="card-body">
                <h6 class="card-title mb-3">
                  <i class="fas fa-fingerprint text-primary me-2"></i>Product Identification
                </h6>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Barcode</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light">
                        <i class="fas fa-barcode text-muted"></i>
                      </span>
                      <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Enter barcode" maxlength="50">
                    </div>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">IMEI Number</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light">
                        <i class="fas fa-mobile-alt text-muted"></i>
                      </span>
                      <input type="text" name="imei_code" id="imei_code" class="form-control" placeholder="Enter IMEI number" maxlength="20">
                    </div>
                  </div>
                  <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">
                      Quantity <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="quantity" required class="form-control" placeholder="0" min="0" />
                  </div>
                  <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">Warranty Period</label>
                    <input type="text" name="warranty_period" class="form-control" placeholder="e.g., 1 year" maxlength="20" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Image Upload -->
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">Product Image</label>
            <div class="card border-dashed">
              <div class="card-body text-center">
                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Upload Product Image</h6>
                <p class="text-muted small mb-3">Supported formats: JPG, PNG, GIF</p>
                <input type="file" name="image" class="form-control" accept="image/*" />
              </div>
            </div>
          </div>

          <!-- Status Toggle -->
          <div class="col-md-12 mb-4">
            <div class="card border-0 bg-light">
              <div class="card-body py-3">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <label class="form-label fw-semibold mb-0">Product Visibility</label>
                    <p class="text-muted mb-0 small">
                      Control whether this product is visible to customers
                    </p>
                  </div>
                  <div class="col-md-4 text-end">
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" style="width: 3.5rem; height: 1.5rem;">
                      <label class="form-check-label fw-semibold" for="statusSwitch">
                        <span id="statusText" class="text-success">Visible</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center border-top pt-4">
              <div>
                <small class="text-muted">
                  <i class="fas fa-info-circle me-1"></i>
                  Fields marked with <span class="text-danger">*</span> are required
                </small>
              </div>
              <div class="d-flex gap-2">
                <button type="reset" class="btn btn-outline-secondary">
                  <i class="fas fa-redo me-1"></i>Reset
                </button>
                <button type="submit" name="saveProduct" class="btn btn-primary">
                  <i class="fas fa-save me-1"></i>Save Product
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Quick Tips Section -->
  <div class="row mt-4 mb-4">
    <div class="col-md-4">
      <div class="card border-0 bg-light h-100">
        <div class="card-body">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <i class="fas fa-calculator text-primary fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="card-title mb-1">Pricing Strategy</h6>
              <p class="card-text small text-muted mb-0">
                Set competitive prices. Discount is automatically calculated between actual and selling price.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 bg-light h-100">
        <div class="card-body">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <i class="fas fa-sitemap text-primary fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="card-title mb-1">Category Organization</h6>
              <p class="card-text small text-muted mb-0">
                Proper categorization helps customers find products easily and improves inventory management.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 bg-light h-100">
        <div class="card-body">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <i class="fas fa-qrcode text-primary fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="card-title mb-1">Product Identification</h6>
              <p class="card-text small text-muted mb-0">
                Barcode and IMEI numbers help in inventory tracking and quick product identification.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
  .form-check-input:checked {
    background-color: #dc3545;
    border-color: #dc3545;
  }

  .form-check-input:checked+.form-check-label #statusText {
    color: #dc3545 !important;
  }

  .card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
  }

  .border-dashed {
    border: 2px dashed #dee2e6 !important;
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
    // Status toggle functionality
    const statusSwitch = document.getElementById('statusSwitch');
    const statusText = document.getElementById('statusText');

    if (statusSwitch) {
      statusSwitch.addEventListener('change', function() {
        statusText.textContent = this.checked ? 'Hidden' : 'Visible';
        statusText.className = this.checked ? 'text-danger' : 'text-success';
      });
    }

    // Auto-generate barcode if empty
    const barcodeInput = document.getElementById('barcode');
    if (barcodeInput) {
      barcodeInput.addEventListener('blur', function() {
        if (!this.value.trim()) {
          // Generate a simple barcode (you can enhance this)
          const randomBarcode = 'BC' + Date.now().toString().slice(-8);
          this.value = randomBarcode;
        }
      });
    }
  });

  // Function to calculate the discount
  function calculateDiscount() {
    const actualPrice = parseFloat(document.getElementById('actual_price').value) || 0;
    const sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
    const discountField = document.getElementById('discount');
    const discountPercentage = document.getElementById('discountPercentage');

    if (actualPrice > 0 && sellingPrice > 0) {
      const discount = actualPrice - sellingPrice;
      const percentage = ((discount / actualPrice) * 100).toFixed(1);

      discountField.value = discount.toFixed(2);

      if (discount > 0) {
        discountPercentage.textContent = `${percentage}% discount`;
        discountPercentage.className = 'form-text text-success fw-semibold';
      } else if (discount < 0) {
        discountPercentage.textContent = `${Math.abs(percentage)}% premium`;
        discountPercentage.className = 'form-text text-danger fw-semibold';
      } else {
        discountPercentage.textContent = 'No discount';
        discountPercentage.className = 'form-text text-muted';
      }
    } else {
      discountField.value = '';
      discountPercentage.textContent = '';
    }
  }

  // Function to fetch subcategories
  function fetchSubcategories(categoryId) {
    if (categoryId) {
      // Show loading state
      const subcategorySelect = document.getElementById('sub_category_id');
      subcategorySelect.innerHTML = '<option value="">Loading subcategories...</option>';
      subcategorySelect.disabled = true;

      // Send AJAX request to fetch subcategories
      fetch('fetch_subcategories.php?category_id=' + categoryId)
        .then(response => response.json())
        .then(data => {
          subcategorySelect.disabled = false;
          subcategorySelect.innerHTML = '<option value="">Select Sub Category</option>';

          if (data.length > 0) {
            data.forEach(subcategory => {
              const option = document.createElement('option');
              option.value = subcategory.id;
              option.textContent = subcategory.name;
              subcategorySelect.appendChild(option);
            });
          } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No Subcategories Available';
            subcategorySelect.appendChild(option);
          }
        })
        .catch(error => {
          console.error('Error fetching subcategories:', error);
          subcategorySelect.disabled = false;
          subcategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
        });
    } else {
      // Clear subcategories if no category is selected
      const subcategorySelect = document.getElementById('sub_category_id');
      subcategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
    }
  }

  // Form validation
  document.getElementById('productForm').addEventListener('submit', function(e) {
    const actualPrice = parseFloat(document.getElementById('actual_price').value);
    const sellingPrice = parseFloat(document.getElementById('selling_price').value);

    if (actualPrice < 0 || sellingPrice < 0) {
      e.preventDefault();
      alert('Prices cannot be negative');
      return;
    }

    if (sellingPrice > actualPrice) {
      e.preventDefault();
      alert('Selling price cannot be higher than actual price');
      return;
    }
  });
</script>