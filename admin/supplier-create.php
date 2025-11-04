<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mt-4 mb-5">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-truck-loading fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Add New Supplier</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="suppliers.php" class="text-decoration-none">Suppliers</a></li>
            <li class="breadcrumb-item active">Add New</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="suppliers.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Suppliers
    </a>
  </div>

  <!-- Add Supplier Card -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom py-3">
      <h4 class="mb-0 text-dark">
        <i class="fas fa-plus-circle me-2 text-primary"></i>Supplier Information
      </h4>
    </div>
    <div class="card-body p-4">
      <?php alertMessage(); ?>

      <form action="code.php" method="POST" id="addSupplierForm">
        <div class="row">
          <!-- Supplier Name -->
          <div class="col-md-12 mb-3">
            <label for="name" class="form-label fw-semibold">Supplier Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-building text-muted"></i></span>
              <input type="text" name="name" id="name" required class="form-control" placeholder="Enter supplier company name" />
            </div>
          </div>

          <!-- Email -->
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
              <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" />
            </div>
          </div>

          <!-- Phone -->
          <div class="col-md-6 mb-3">
            <label for="phone" class="form-label fw-semibold">Phone Number</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
              <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter phone number" />
            </div>
          </div>

          <!-- Status -->
          <div class="col-md-12 mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" style="width: 3rem; height: 1.5rem;">
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
              <button type="submit" name="saveSupplier" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i>Save Supplier
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Information Card -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom py-3">
      <h5 class="mb-0 text-dark">
        <i class="fas fa-info-circle me-2 text-primary"></i>Supplier Information
      </h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
              <i class="fas fa-asterisk text-primary fa-lg"></i>
            </div>
            <div>
              <h6 class="mb-1 fw-bold">Required Fields</h6>
              <p class="text-muted mb-0">Supplier name is mandatory for creating a new supplier</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
              <i class="fas fa-eye text-success fa-lg"></i>
            </div>
            <div>
              <h6 class="mb-1 fw-bold">Visibility</h6>
              <p class="text-muted mb-0">Hidden suppliers won't appear in dropdown selections</p>
            </div>
          </div>
        </div>
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
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const addSupplierForm = document.getElementById('addSupplierForm');

    if (addSupplierForm) {
      addSupplierForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();

        // Basic validation
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

        // Email validation (if provided)
        if (email) {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(email)) {
            e.preventDefault();
            Swal.fire({
              title: 'Invalid Email',
              text: 'Please enter a valid email address or leave it empty.',
              icon: 'warning',
              confirmButtonColor: '#6c757d'
            });
            document.getElementById('email').focus();
            return;
          }
        }

        // Phone validation (if provided)
        if (phone && phone.length < 10) {
          e.preventDefault();
          Swal.fire({
            title: 'Invalid Phone Number',
            text: 'Please enter a valid phone number or leave it empty.',
            icon: 'warning',
            confirmButtonColor: '#6c757d'
          });
          document.getElementById('phone').focus();
          return;
        }
      });
    }
  });
</script>