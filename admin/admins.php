<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mt-4 mb-5">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-users fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Admins & Staff Management</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Admins & Staff</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="admins-create.php" class="btn btn-primary">
      <i class="fas fa-user-plus me-1"></i>Add Admin/Staff
    </a>
  </div>

  <!-- Admins/Staff Card -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
      <h4 class="mb-0 text-dark">
        <i class="fas fa-user-shield me-2 text-primary"></i>User Accounts
      </h4>
      <span class="badge bg-light text-dark border fs-6">
        <?php
        $admins = getAll('admins');
        $totalAdmins = mysqli_num_rows($admins);
        echo $totalAdmins . " Users";
        ?>
      </span>
    </div>
    <div class="card-body p-4">
      <?php alertMessage(); ?>

      <?php
      if (!$admins) {
        echo '<div class="alert alert-danger">Something Went Wrong!</div>';
        return false;
      }

      if (mysqli_num_rows($admins) > 0) {
      ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="bg-light">
              <tr>
                <th class="border-0">User</th>
                <th class="border-0">Contact</th>
                <th class="border-0">Role</th>
                <th class="border-0">Status</th>
                <th class="border-0 text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($admins as $adminItem) : ?>
                <tr class="border-bottom">
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="fas fa-user text-primary"></i>
                      </div>
                      <div>
                        <h6 class="mb-0 fw-bold"><?= $adminItem['name'] ?></h6>
                        <small class="text-muted">ID: <?= $adminItem['id'] ?></small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>
                      <div class="fw-semibold"><?= $adminItem['email'] ?></div>
                      <small class="text-muted"><?= $adminItem['phone'] ?? 'N/A' ?></small>
                    </div>
                  </td>
                  <td>
                    <span class="badge <?= $adminItem['role'] == 'admin' ? 'bg-primary' : 'bg-secondary' ?>">
                      <i class="fas <?= $adminItem['role'] == 'admin' ? 'fa-user-shield' : 'fa-user-check' ?> me-1"></i>
                      <?= ucfirst($adminItem['role']) ?>
                    </span>
                  </td>
                  <td>
                    <?php if ($adminItem['is_ban'] == 1): ?>
                      <span class="badge bg-danger">
                        <i class="fas fa-ban me-1"></i>Inactive
                      </span>
                    <?php else: ?>
                      <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i>Active
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <div class="btn-group" role="group">
                      <a href="admins-edit.php?id=<?= $adminItem['id'] ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                      </a>
                      <a
                        href="admins-delete.php?id=<?= $adminItem['id'] ?>"
                        class="btn btn-outline-danger btn-sm delete-btn"
                        data-delete-url="admins-delete.php?id=<?= $adminItem['id'] ?>"
                        data-user-name="<?= $adminItem['name'] ?>">
                        <i class="fas fa-trash me-1"></i>Delete
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php
      } else {
      ?>
        <div class="text-center py-5">
          <div class="mb-4">
            <i class="fas fa-users fa-4x text-muted"></i>
          </div>
          <h4 class="text-muted mb-3">No Users Found</h4>
          <p class="text-muted mb-4">Get started by adding your first admin or staff member.</p>
          <a href="admins-create.php" class="btn btn-primary">
            <i class="fas fa-user-plus me-1"></i>Add First User
          </a>
        </div>
      <?php
      }
      ?>
    </div>
  </div>

  <!-- Quick Stats Card -->
  <!-- <div class="row mt-4">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow-sm h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Users</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalAdmins ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow-sm h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Active Users</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $activeAdmins = mysqli_num_rows(getAll('admins', ['is_ban' => 0]));
                echo $activeAdmins;
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-check fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-danger shadow-sm h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                Inactive Users</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $inactiveAdmins = mysqli_num_rows(getAll('admins', ['is_ban' => 1]));
                echo $inactiveAdmins;
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-slash fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow-sm h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Admin Users</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $adminUsers = mysqli_num_rows(getAll('admins', ['role' => 'admin']));
                echo $adminUsers;
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user-shield fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->
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

  .btn {
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
  }

  .btn:hover {
    transform: translateY(-1px);
  }

  .card-header {
    border-radius: 0.35rem 0.35rem 0 0 !important;
  }

  .table tbody tr:hover {
    background-color: #f8f9fc;
  }

  .badge {
    font-weight: 500;
    font-size: 0.75rem;
  }

  .border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
  }

  .border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
  }

  .border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
  }

  .border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Enhanced SweetAlert for delete confirmation
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        const deleteUrl = this.getAttribute('data-delete-url');
        const userName = this.getAttribute('data-user-name');

        Swal.fire({
          title: 'Delete User?',
          html: `You are about to delete <strong>${userName}</strong>. This action cannot be undone.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, Delete User',
          cancelButtonText: 'Cancel',
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = deleteUrl;
          }
        });
      });
    });
  });
</script>