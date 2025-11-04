<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-user-plus fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Add New Customer</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="customers.php" class="text-decoration-none">Customers</a></li>
            <li class="breadcrumb-item active">Add Customer</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="customers.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Customers
    </a>
  </div>

  <!-- Add Customer Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <h5 class="card-title mb-0">
        <i class="fas fa-user-circle text-primary me-2"></i>Customer Information
      </h5>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <form action="code.php" method="POST">
        <div class="row">
          <!-- Name Field -->
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">
              Full Name <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-user text-muted"></i>
              </span>
              <input
                type="text"
                name="name"
                required
                class="form-control"
                placeholder="Enter customer full name"
                maxlength="100" />
            </div>
            <div class="form-text">Enter the customer's full name as it should appear in records</div>
          </div>

          <!-- Email Field -->
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Email Address</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-envelope text-muted"></i>
              </span>
              <input
                type="email"
                name="email"
                class="form-control"
                placeholder="customer@example.com"
                maxlength="100" />
            </div>
            <div class="form-text">Optional - for sending order updates</div>
          </div>

          <!-- Phone Field -->
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">
              Phone Number <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-phone text-muted"></i>
              </span>
              <input
                type="tel"
                name="phone"
                required
                class="form-control"
                placeholder="Enter phone number"
                pattern="[0-9]{10,15}"
                title="Please enter a valid phone number (10-15 digits)" />
            </div>
            <div class="form-text">Required for order tracking and notifications</div>
          </div>

          <!-- Status Toggle -->
          <div class="col-md-12 mb-4">
            <div class="card border-0 bg-light">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-md-8">
                    <label class="form-label fw-semibold mb-0">Customer Visibility</label>
                    <p class="text-muted mb-0 small">
                      Control whether this customer is visible in the system
                    </p>
                  </div>
                  <div class="col-md-4 text-end">
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        name="status"
                        id="statusSwitch"
                        style="width: 3.5rem; height: 1.5rem;">
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
                <a href="customers.php" class="btn btn-outline-secondary">
                  <i class="fas fa-times me-1"></i>Cancel
                </a>
                <button type="submit" name="saveCustomer" class="btn btn-primary">
                  <i class="fas fa-save me-1"></i>Save Customer
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
      <div class="card border-0 bg-light">
        <div class="card-body">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <i class="fas fa-user-check text-primary fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="card-title mb-1">Customer Verification</h6>
              <p class="card-text small text-muted mb-0">
                Always verify customer phone numbers to ensure accurate order tracking.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 bg-light">
        <div class="card-body">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <i class="fas fa-bell text-primary fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="card-title mb-1">Notifications</h6>
              <p class="card-text small text-muted mb-0">
                Email is optional but enables order confirmations and updates.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card border-0 bg-light">
        <div class="card-body">
          <div class="d-flex">
            <div class="flex-shrink-0">
              <i class="fas fa-eye-slash text-primary fa-lg"></i>
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="card-title mb-1">Visibility Control</h6>
              <p class="card-text small text-muted mb-0">
                Hide customers from lists while preserving their order history.
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
    const statusSwitch = document.getElementById('statusSwitch');
    const statusText = document.getElementById('statusText');

    // Update status text based on switch state
    statusSwitch.addEventListener('change', function() {
      if (this.checked) {
        statusText.textContent = 'Hidden';
        statusText.className = 'text-danger';
      } else {
        statusText.textContent = 'Visible';
        statusText.className = 'text-success';
      }
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
      const phoneInput = document.querySelector('input[name="phone"]');
      const nameInput = document.querySelector('input[name="name"]');

      // Validate phone number (basic validation)
      if (phoneInput.value && !/^\d{10,15}$/.test(phoneInput.value)) {
        e.preventDefault();
        showAlert('Please enter a valid phone number (10-15 digits)', 'error');
        phoneInput.focus();
        return;
      }

      // Validate name length
      if (nameInput.value.trim().length < 2) {
        e.preventDefault();
        showAlert('Please enter a valid name (at least 2 characters)', 'error');
        nameInput.focus();
        return;
      }
    });

    // Helper function for alerts (you might have this in your includes)
    function showAlert(message, type = 'success') {
      // You can integrate with your existing alert system
      // For now, using a simple alert
      const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
      // This would typically integrate with your existing alert system
      console.log(`${type}: ${message}`);
    }
  });
</script>