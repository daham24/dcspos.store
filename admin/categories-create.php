<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-tags fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Category Management</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="categories.php" class="text-decoration-none">Categories</a></li>
            <li class="breadcrumb-item active">Add Category</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="categories.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Categories
    </a>
  </div>

  <!-- Add Main Category Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <h5 class="card-title mb-0">
        <i class="fas fa-folder-plus text-primary me-2"></i>Add Main Category
      </h5>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <form action="code.php" method="POST">
        <div class="row">
          <!-- Category Name -->
          <div class="col-md-8 mb-4">
            <label class="form-label fw-semibold">
              Category Name <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-tag text-muted"></i>
              </span>
              <input
                type="text"
                name="name"
                required
                class="form-control"
                placeholder="Enter category name"
                maxlength="100" />
            </div>
            <div class="form-text">Choose a descriptive name for your product category</div>
          </div>

          <!-- Status Toggle -->
          <div class="col-md-4 mb-4">
            <label class="form-label fw-semibold">Category Status</label>
            <div class="form-check form-switch">
              <input
                class="form-check-input"
                type="checkbox"
                name="status"
                id="categoryStatus"
                style="width: 3rem; height: 1.5rem;">
              <label class="form-check-label fw-semibold" for="categoryStatus">
                <span id="categoryStatusText" class="text-success">Visible</span>
              </label>
            </div>
            <div class="form-text">Hidden categories won't appear in product lists</div>
          </div>

          <!-- Description -->
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">Description</label>
            <div class="input-group">
              <span class="input-group-text bg-light align-items-start pt-2">
                <i class="fas fa-align-left text-muted"></i>
              </span>
              <textarea
                name="description"
                class="form-control"
                rows="3"
                placeholder="Enter category description (optional)"
                maxlength="255"></textarea>
            </div>
            <div class="form-text">Brief description of what products belong in this category</div>
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
                <button type="submit" name="saveCategory" class="btn btn-primary">
                  <i class="fas fa-save me-1"></i>Save Category
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Add Sub Category Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <h5 class="card-title mb-0">
        <i class="fas fa-folder text-warning me-2"></i>Add Sub Category
      </h5>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <form action="code.php" method="POST">
        <div class="row">
          <!-- Parent Category Selection -->
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">
              Main Category <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-sitemap text-muted"></i>
              </span>
              <select name="categoryId" class="form-select" required>
                <option value="">Select Main Category</option>
                <?php
                $categories = getAll('categories');
                if ($categories) {
                  foreach ($categories as $cateItem) {
                    echo '<option value="' . $cateItem['id'] . '">' . $cateItem['name'] . '</option>';
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-text">Choose the parent category for this subcategory</div>
          </div>

          <!-- Sub Category Name -->
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">
              Sub Category Name <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-tags text-muted"></i>
              </span>
              <input
                type="text"
                name="name"
                required
                class="form-control"
                placeholder="Enter sub category name"
                maxlength="100" />
            </div>
            <div class="form-text">Name for the subcategory under the main category</div>
          </div>

          <!-- Status Toggle -->
          <div class="col-md-12 mb-4">
            <div class="card border-0 bg-light">
              <div class="card-body py-3">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <label class="form-label fw-semibold mb-0">Sub Category Status</label>
                    <p class="text-muted mb-0 small">
                      Control whether this subcategory is visible in the system
                    </p>
                  </div>
                  <div class="col-md-4 text-end">
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        name="status"
                        id="subCategoryStatus"
                        style="width: 3rem; height: 1.5rem;">
                      <label class="form-check-label fw-semibold" for="subCategoryStatus">
                        <span id="subCategoryStatusText" class="text-success">Visible</span>
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
                  All fields are required for subcategories
                </small>
              </div>
              <div class="d-flex gap-2">
                <button type="reset" class="btn btn-outline-secondary">
                  <i class="fas fa-redo me-1"></i>Reset
                </button>
                <button type="submit" name="saveSubCategory" class="btn btn-warning">
                  <i class="fas fa-save me-1"></i>Save Sub Category
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
              <i class="fas fa-layer-group text-primary fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="card-title mb-1">Category Structure</h6>
              <p class="card-text small text-muted mb-0">
                Organize products hierarchically with main categories and subcategories for better navigation.
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
              <i class="fas fa-eye text-primary fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="card-title mb-1">Visibility Control</h6>
              <p class="card-text small text-muted mb-0">
                Hide categories temporarily while preserving product relationships and data.
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
              <i class="fas fa-project-diagram text-primary fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="card-title mb-1">Organization Tips</h6>
              <p class="card-text small text-muted mb-0">
                Use clear, descriptive names to make it easy for customers to find products.
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

  .form-check-input:checked+.form-check-label #categoryStatusText,
  .form-check-input:checked+.form-check-label #subCategoryStatusText {
    color: #dc3545 !important;
  }

  .card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
  }

  .input-group-text {
    border-right: none;
  }

  .form-control:focus+.input-group-text {
    border-color: #86b7fe;
    border-right: 1px solid #86b7fe;
  }

  .btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
  }

  .btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    color: #000;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Main Category Status Toggle
    const categoryStatus = document.getElementById('categoryStatus');
    const categoryStatusText = document.getElementById('categoryStatusText');

    if (categoryStatus) {
      categoryStatus.addEventListener('change', function() {
        categoryStatusText.textContent = this.checked ? 'Hidden' : 'Visible';
        categoryStatusText.className = this.checked ? 'text-danger' : 'text-success';
      });
    }

    // Sub Category Status Toggle
    const subCategoryStatus = document.getElementById('subCategoryStatus');
    const subCategoryStatusText = document.getElementById('subCategoryStatusText');

    if (subCategoryStatus) {
      subCategoryStatus.addEventListener('change', function() {
        subCategoryStatusText.textContent = this.checked ? 'Hidden' : 'Visible';
        subCategoryStatusText.className = this.checked ? 'text-danger' : 'text-success';
      });
    }

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const nameInput = this.querySelector('input[name="name"]');

        // Validate name length
        if (nameInput && nameInput.value.trim().length < 2) {
          e.preventDefault();
          showAlert('Please enter a valid name (at least 2 characters)', 'error');
          nameInput.focus();
          return;
        }

        // Validate category selection for subcategory form
        const categorySelect = this.querySelector('select[name="categoryId"]');
        if (categorySelect && !categorySelect.value) {
          e.preventDefault();
          showAlert('Please select a main category', 'error');
          categorySelect.focus();
          return;
        }
      });
    });

    // Helper function for alerts
    function showAlert(message, type = 'success') {
      // You can integrate with your existing alert system
      console.log(`${type}: ${message}`);
    }

    // Auto-focus first input
    const firstInput = document.querySelector('input[name="name"]');
    if (firstInput) {
      firstInput.focus();
    }
  });
</script>