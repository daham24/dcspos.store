<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mt-4 mb-5">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-user-edit fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Edit Customer</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="customers.php" class="text-decoration-none">Customers</a></li>
            <li class="breadcrumb-item active">Edit Customer</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="customers.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Customers
    </a>
  </div>

  <?php
  $paramValue = checkParamId('id');
  if (!is_numeric($paramValue)) {
    echo '<div class="alert alert-danger">' . $paramValue . '</div>';
    return false;
  }

  $customer = getById('customers', $paramValue);
  if ($customer['status'] == 200) {
  ?>

    <!-- Edit Customer Card -->
    <div class="card mt-4">
      <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
        <h4 class="mb-0 text-dark">
          <i class="fas fa-edit me-2 text-primary"></i>Edit Customer Details
        </h4>
        <span class="badge bg-light text-dark border">
          ID: <?= $customer['data']['id']; ?>
        </span>
      </div>
      <div class="card-body p-4">
        <?php alertMessage(); ?>

        <form action="code.php" method="POST" id="editCustomerForm">
          <input type="hidden" name="customerId" value="<?= $customer['data']['id']; ?>" />

          <div class="row">
            <!-- Customer Name -->
            <div class="col-md-12 mb-3">
              <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                <input type="text" name="name" id="name" required value="<?= $customer['data']['name']; ?>" class="form-control" placeholder="Enter customer name" />
              </div>
            </div>

            <!-- Email -->
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label fw-semibold">Email Address</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                <input type="email" name="email" id="email" value="<?= $customer['data']['email']; ?>" class="form-control" placeholder="Enter email address" />
              </div>
            </div>

            <!-- Phone -->
            <div class="col-md-6 mb-3">
              <label for="phone" class="form-label fw-semibold">Phone Number</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                <input type="number" name="phone" id="phone" value="<?= $customer['data']['phone']; ?>" class="form-control" placeholder="Enter phone number" />
              </div>
            </div>

            <!-- Status -->
            <div class="col-md-12 mb-3">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="status" id="status" <?= $customer['data']['status'] == true ? 'checked' : ''; ?> style="width: 3rem; height: 1.5rem;">
                <label class="form-check-label fw-semibold ms-2" for="status">
                  <i class="fas fa-eye-slash me-1 text-warning"></i>Hide Customer
                </label>
              </div>
              <small class="text-muted">When checked, this customer will be hidden from selection lists</small>
            </div>

            <!-- Action Buttons -->
            <div class="col-md-12 mb-3">
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="customers.php" class="btn btn-outline-secondary me-md-2 px-4">
                  <i class="fas fa-times me-1"></i>Cancel
                </a>
                <button type="submit" name="updateCustomer" class="btn btn-primary px-4">
                  <i class="fas fa-save me-1"></i>Update Customer
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Current Customer Information -->
    <div class="card mt-4">
      <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-dark">
          <i class="fas fa-info-circle me-2 text-primary"></i>Current Customer Information
        </h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <div class="border rounded p-3 text-center">
              <h6 class="text-muted mb-2">Current Status</h6>
              <?php if ($customer['data']['status'] == true): ?>
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
              <p class="fw-bold mb-0"><?= !empty($customer['data']['email']) ? $customer['data']['email'] : 'Not set' ?></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="border rounded p-3 text-center">
              <h6 class="text-muted mb-2">Phone</h6>
              <p class="fw-bold mb-0"><?= !empty($customer['data']['phone']) ? $customer['data']['phone'] : 'Not set' ?></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="border rounded p-3 text-center">
              <h6 class="text-muted mb-2">Last Updated</h6>
              <p class="fw-bold mb-0"><?= date('M j, Y', strtotime($customer['data']['updated_at'] ?? 'Now')) ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php
  } else {
    echo '<div class="alert alert-danger mt-4">' . $customer['message'] . '</div>';
    return false;
  }
  ?>
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
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const editCustomerForm = document.getElementById('editCustomerForm');

    if (editCustomerForm) {
      editCustomerForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();

        // Basic validation
        if (!name) {
          e.preventDefault();
          Swal.fire({
            title: 'Missing Information',
            text: 'Customer name is required.',
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