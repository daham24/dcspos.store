<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mt-4 mb-5">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-user-edit fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Edit Admin/Staff</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="admins.php" class="text-decoration-none">Admins & Staff</a></li>
            <li class="breadcrumb-item active">Edit User</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="admins.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Users
    </a>
  </div>

  <!-- Edit Admin/Staff Card -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
      <h4 class="mb-0 text-dark">
        <i class="fas fa-edit me-2 text-primary"></i>Edit User Details
      </h4>
      <span class="badge bg-light text-dark border">
        ID: <?= isset($adminData['data']['id']) ? $adminData['data']['id'] : 'N/A' ?>
      </span>
    </div>
    <div class="card-body p-4">
      <?php alertMessage(); ?>

      <?php
      if (isset($_GET['id'])) {
        if ($_GET['id'] != '') {

          $adminId = $_GET['id'];
        } else {
          echo '<div class="alert alert-danger">No Id Found</div>';
          return false;
        }
      } else {
        echo '<div class="alert alert-danger">No id given in params</div>';
        return false;
      }

      $adminData = getById('admins', $adminId);
      if ($adminData) {
        if ($adminData['status'] == 200) {
      ?>

          <form action="code.php" method="POST" id="editAdminForm">
            <input type="hidden" name="adminId" value="<?= $adminData['data']['id']; ?>">

            <div class="row">
              <!-- Full Name -->
              <div class="col-md-12 mb-3">
                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                  <input type="text" name="name" id="name" required value="<?= $adminData['data']['name']; ?>" class="form-control" placeholder="Enter full name" />
                </div>
              </div>

              <!-- Email -->
              <div class="col-md-6 mb-3">
                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                  <input type="email" name="email" id="email" required value="<?= $adminData['data']['email']; ?>" class="form-control" placeholder="Enter email address" />
                </div>
              </div>

              <!-- Password -->
              <div class="col-md-6 mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                  <input type="password" name="password" id="password" class="form-control" placeholder="Leave blank to keep current password" />
                  <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                    <i class="fas fa-eye"></i>
                  </button>
                </div>
                <small class="text-muted">Leave empty to keep current password</small>
              </div>

              <!-- Phone Number -->
              <div class="col-md-6 mb-3">
                <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                  <input type="number" name="phone" id="phone" required value="<?= $adminData['data']['phone']; ?>" class="form-control" placeholder="Enter phone number" />
                </div>
              </div>

              <!-- Role -->
              <div class="col-md-6 mb-3">
                <label for="role" class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="fas fa-user-tag text-muted"></i></span>
                  <select name="role" id="role" required class="form-select">
                    <option value="">Select Role</option>
                    <option value="admin" <?= $adminData['data']['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="staff" <?= $adminData['data']['role'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                  </select>
                </div>
              </div>

              <!-- Is Ban -->
              <div class="col-md-12 mb-3">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" name="is_ban" id="is_ban" <?= $adminData['data']['is_ban'] == true ? 'checked' : '';  ?> style="width: 3rem; height: 1.5rem;">
                  <label class="form-check-label fw-semibold ms-2" for="is_ban">
                    <i class="fas fa-ban me-1 text-danger"></i>Ban User Account
                  </label>
                </div>
                <small class="text-muted">If enabled, this user will not be able to login</small>
              </div>

              <!-- Action Buttons -->
              <div class="col-md-12 mb-3">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                  <a href="admins.php" class="btn btn-outline-secondary me-md-2 px-4">
                    <i class="fas fa-times me-1"></i>Cancel
                  </a>
                  <button type="submit" name="updateAdmin" class="btn btn-primary px-4">
                    <i class="fas fa-save me-1"></i>Update User
                  </button>
                </div>
              </div>
            </div>
          </form>

      <?php
        } else {
          echo '<div class="alert alert-danger">' . $adminData['message'] . '</div>';
        }
      } else {
        echo '<div class="alert alert-danger">Something Went Wrong</div>';
        return false;
      }
      ?>
    </div>
  </div>

  <!-- Current User Information Card -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom py-3">
      <h5 class="mb-0 text-dark">
        <i class="fas fa-info-circle me-2 text-primary"></i>Current User Information
      </h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <div class="border rounded p-3 text-center">
            <h6 class="text-muted mb-2">Current Role</h6>
            <span class="badge <?= $adminData['data']['role'] == 'admin' ? 'bg-primary' : 'bg-secondary' ?>">
              <i class="fas <?= $adminData['data']['role'] == 'admin' ? 'fa-user-shield' : 'fa-user-check' ?> me-1"></i>
              <?= ucfirst($adminData['data']['role']) ?>
            </span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3 text-center">
            <h6 class="text-muted mb-2">Account Status</h6>
            <?php if ($adminData['data']['is_ban'] == 1): ?>
              <span class="badge bg-danger">
                <i class="fas fa-ban me-1"></i>Banned
              </span>
            <?php else: ?>
              <span class="badge bg-success">
                <i class="fas fa-check-circle me-1"></i>Active
              </span>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3 text-center">
            <h6 class="text-muted mb-2">Last Updated</h6>
            <p class="fw-bold mb-0"><?= date('M j, Y', strtotime($adminData['data']['updated_at'] ?? 'Now')) ?></p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="border rounded p-3 text-center">
            <h6 class="text-muted mb-2">Created Date</h6>
            <p class="fw-bold mb-0"><?= date('M j, Y', strtotime($adminData['data']['created_at'])) ?></p>
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
    background-color: #dc3545;
    border-color: #dc3545;
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
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        const icon = this.querySelector('i');
        if (type === 'password') {
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
          this.setAttribute('title', 'Show Password');
        } else {
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
          this.setAttribute('title', 'Hide Password');
        }
      });
    }

    // Form validation
    const editAdminForm = document.getElementById('editAdminForm');
    if (editAdminForm) {
      editAdminForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const role = document.getElementById('role').value;

        // Basic validation
        if (!name || !email || !phone || !role) {
          e.preventDefault();
          Swal.fire({
            title: 'Missing Information',
            text: 'Please fill in all required fields.',
            icon: 'warning',
            confirmButtonColor: '#6c757d'
          });
          return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          e.preventDefault();
          Swal.fire({
            title: 'Invalid Email',
            text: 'Please enter a valid email address.',
            icon: 'warning',
            confirmButtonColor: '#6c757d'
          });
          document.getElementById('email').focus();
          return;
        }

        // Phone validation
        if (phone.length < 10) {
          e.preventDefault();
          Swal.fire({
            title: 'Invalid Phone Number',
            text: 'Please enter a valid phone number.',
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