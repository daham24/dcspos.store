<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mt-4 mb-5">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-user-plus fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Add Admin/Staff</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="admins.php" class="text-decoration-none">Admins & Staff</a></li>
            <li class="breadcrumb-item active">Add New</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="admins.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back
    </a>
  </div>

  <!-- Add Admin/Staff Card -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom py-3">
      <h4 class="mb-0 text-dark">
        <i class="fas fa-user-shield me-2 text-primary"></i>Create New User Account
      </h4>
    </div>
    <div class="card-body p-4">
      <?php alertMessage(); ?>

      <form action="code.php" method="POST" id="addAdminForm">
        <div class="row">
          <!-- Full Name -->
          <div class="col-md-12 mb-3">
            <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
              <input type="text" name="name" id="name" required class="form-control" placeholder="Enter full name" />
            </div>
          </div>

          <!-- Email -->
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
              <input type="email" name="email" id="email" required class="form-control" placeholder="Enter email address" />
            </div>
          </div>

          <!-- Password -->
          <div class="col-md-6 mb-3">
            <label for="password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
              <input type="password" name="password" id="password" required class="form-control" placeholder="Enter password" />
              <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <small class="text-muted">Password must be at least 6 characters long</small>
          </div>

          <!-- Phone Number -->
          <div class="col-md-6 mb-3">
            <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
              <input type="number" name="phone" id="phone" required class="form-control" placeholder="Enter phone number" />
            </div>
          </div>

          <!-- Role -->
          <div class="col-md-6 mb-3">
            <label for="role" class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fas fa-user-tag text-muted"></i></span>
              <select name="role" id="role" required class="form-select">
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
              </select>
            </div>
          </div>

          <!-- Is Ban -->
          <div class="col-md-12 mb-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" role="switch" name="is_ban" id="is_ban" style="width: 3rem; height: 1.5rem;">
              <label class="form-check-label fw-semibold ms-2" for="is_ban">
                <i class="fas fa-ban me-1 text-danger"></i>Ban User Account
              </label>
            </div>
            <small class="text-muted">If enabled, this user will not be able to login</small>
          </div>

          <!-- Action Buttons -->
          <div class="col-md-12 mb-3">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <a href="admins-create.php" class="btn btn-outline-secondary me-md-2 px-4">
                <i class="fas fa-times me-1"></i>Cancel
              </a>
              <button type="submit" name="saveAdmin" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i>Create User
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
        <i class="fas fa-info-circle me-2 text-primary"></i>Role Information
      </h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
              <i class="fas fa-user-shield text-primary fa-lg"></i>
            </div>
            <div>
              <h6 class="mb-1 fw-bold">Admin Role</h6>
              <p class="text-muted mb-0">Full access to all system features and settings</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-success bg-opacity-10 rounded p-2 me-3">
              <i class="fas fa-user-check text-success fa-lg"></i>
            </div>
            <div>
              <h6 class="mb-1 fw-bold">Staff Role</h6>
              <p class="text-muted mb-0">Limited access to specific modules and functions</p>
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

  #togglePassword {
    border-left: 0;
    transition: all 0.3s ease;
  }

  #togglePassword:hover {
    background-color: #e9ecef;
    border-color: #6c757d;
  }

  .input-group .form-control:not(:last-child) {
    border-right: 0;
  }

  .input-group .btn {
    border-left: 1px solid #e3e6f0;
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
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const addAdminForm = document.getElementById('addAdminForm');

    if (addAdminForm) {
      addAdminForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const phone = document.getElementById('phone').value.trim();
        const role = document.getElementById('role').value;

        // Basic validation
        if (!name || !email || !password || !phone || !role) {
          e.preventDefault();
          Swal.fire({
            title: 'Missing Information',
            text: 'Please fill in all required fields.',
            icon: 'warning',
            confirmButtonColor: '#6c757d'
          });
          return;
        }

        if (password.length < 6) {
          e.preventDefault();
          Swal.fire({
            title: 'Weak Password',
            text: 'Password must be at least 6 characters long.',
            icon: 'warning',
            confirmButtonColor: '#6c757d'
          });
          document.getElementById('password').focus();
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

        // Confirmation dialog
        e.preventDefault();
        const roleText = role === 'admin' ? 'Administrator' : 'Staff';
        Swal.fire({
          title: 'Create User Account?',
          html: `You are about to create a new <strong>${roleText}</strong> account for <strong>${name}</strong>.`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#28a745',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, Create Account',
          cancelButtonText: 'Cancel'
        }).then((result) => {
          if (result.isConfirmed) {
            addAdminForm.submit();
          }
        });
      });
    }
  });

  document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
      togglePassword.addEventListener('click', function() {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle the eye icon
        const icon = this.querySelector('i');
        if (type === 'password') {
          icon.classList.remove('fa-eye-slash');
          icon.classList.add('fa-eye');
        } else {
          icon.classList.remove('fa-eye');
          icon.classList.add('fa-eye-slash');
        }
      });
    }
  });
</script>