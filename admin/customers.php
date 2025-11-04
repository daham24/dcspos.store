<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-users fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Customer Management</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Customers</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="customers-create.php" class="btn btn-primary">
      <i class="fas fa-plus-circle me-1"></i>Add Customer
    </a>
  </div>

  <!-- Customers Card -->
  <div class="card mt-4 shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h5 class="card-title mb-0">
            <i class="fas fa-list me-2 text-primary"></i>Customer List
          </h5>
        </div>
        <div class="col-md-6">
          <form class="d-flex" method="GET" action="">
            <div class="input-group">
              <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by phone number..."
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
              </button>
              <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                <a href="customers.php" class="btn btn-outline-secondary">
                  <i class="fas fa-times"></i>
                </a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <?php
      // Fetch customers based on search
      $searchQuery = isset($_GET['search']) ? validate($_GET['search']) : '';

      $query = "SELECT * FROM customers WHERE 1=1";

      if ($searchQuery) {
        $query .= " AND phone LIKE '%$searchQuery%'";
      }

      $query .= " ORDER BY created_at DESC";

      $customers = mysqli_query($conn, $query);

      if (!$customers) {
        echo '<div class="alert alert-danger">Something Went Wrong!</div>';
        return false;
      }

      if (mysqli_num_rows($customers) > 0):
      ?>
        <div class="table-responsive">
          <table class="table table-hover table-bordered">
            <thead class="table-light">
              <tr>
                <th width="20%">Customer</th>
                <th width="20%">Contact Info</th>
                <th width="10%">Status</th>
                <th width="15%">Orders</th>
                <th width="15%">Last Updated</th>
                <th width="20%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($item = mysqli_fetch_assoc($customers)): ?>
                <?php
                // Query to count orders for each customer
                $customerId = $item['id'];
                $orderQuery = "SELECT COUNT(*) AS order_count FROM orders WHERE customer_id = '$customerId'";
                $orderResult = mysqli_query($conn, $orderQuery);
                $orderCount = mysqli_fetch_assoc($orderResult)['order_count'];
                ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar-circle bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <?= strtoupper(substr($item['name'], 0, 1)) ?>
                      </div>
                      <div>
                        <strong><?= $item['name'] ?></strong>
                        <?php if ($item['email']): ?>
                          <br><small class="text-muted">ID: <?= $item['id'] ?></small>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td>
                    <?php if ($item['email']): ?>
                      <div class="mb-1">
                        <i class="fas fa-envelope text-muted me-1"></i>
                        <small><?= $item['email'] ?></small>
                      </div>
                    <?php endif; ?>
                    <div>
                      <i class="fas fa-phone text-muted me-1"></i>
                      <strong><?= $item['phone'] ?></strong>
                    </div>
                  </td>
                  <td>
                    <?php if ($item['status'] == 1): ?>
                      <span class="badge bg-danger">
                        <i class="fas fa-eye-slash me-1"></i>Hidden
                      </span>
                    <?php else: ?>
                      <span class="badge bg-success">
                        <i class="fas fa-eye me-1"></i>Visible
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      <span class="badge bg-info me-2">
                        <?= $orderCount ?>
                      </span>
                      <?php if ($orderCount > 0): ?>
                        <a href="customer-orders.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-outline-primary">
                          View
                        </a>
                      <?php else: ?>
                        <span class="text-muted small">No orders</span>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <small class="text-muted">
                      <?= date('M j, Y', strtotime($item['updated_at'] ?? $item['created_at'])) ?>
                    </small>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="customers-edit.php?id=<?= $item['id'] ?>" class="btn btn-outline-primary btn-sm" title="Edit Customer">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a
                        href="customers-delete.php?id=<?= $item['id'] ?>"
                        class="btn btn-outline-danger btn-sm delete-btn"
                        data-delete-url="customers-delete.php?id=<?= $item['id'] ?>"
                        title="Delete Customer">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <!-- Customer Stats -->
        <div class="row mt-4">
          <div class="col-md-3">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h4 class="mb-0"><?= mysqli_num_rows($customers) ?></h4>
                    <small>Total Customers</small>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-users fa-2x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
          // Count visible customers
          $visibleQuery = "SELECT COUNT(*) as count FROM customers WHERE status = 0";
          $visibleResult = mysqli_query($conn, $visibleQuery);
          $visibleCount = mysqli_fetch_assoc($visibleResult)['count'];
          ?>
          <div class="col-md-3">
            <div class="card bg-success text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h4 class="mb-0"><?= $visibleCount ?></h4>
                    <small>Active Customers</small>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-eye fa-2x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
          // Count hidden customers
          $hiddenQuery = "SELECT COUNT(*) as count FROM customers WHERE status = 1";
          $hiddenResult = mysqli_query($conn, $hiddenQuery);
          $hiddenCount = mysqli_fetch_assoc($hiddenResult)['count'];
          ?>
          <div class="col-md-3">
            <div class="card bg-warning text-dark">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h4 class="mb-0"><?= $hiddenCount ?></h4>
                    <small>Hidden Customers</small>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-eye-slash fa-2x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php
          // Count customers with orders
          $withOrdersQuery = "SELECT COUNT(DISTINCT customer_id) as count FROM orders";
          $withOrdersResult = mysqli_query($conn, $withOrdersQuery);
          $withOrdersCount = mysqli_fetch_assoc($withOrdersResult)['count'];
          ?>
          <div class="col-md-3">
            <div class="card bg-info text-white">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div>
                    <h4 class="mb-0"><?= $withOrdersCount ?></h4>
                    <small>With Orders</small>
                  </div>
                  <div class="align-self-center">
                    <i class="fas fa-shopping-cart fa-2x opacity-50"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-5">
          <div class="empty-state-icon mb-3">
            <i class="fas fa-users fa-4x text-muted"></i>
          </div>
          <h4 class="text-muted">No Customers Found</h4>
          <p class="text-muted mb-4">
            <?php if ($searchQuery): ?>
              No customers found matching your search criteria.
            <?php else: ?>
              Get started by adding your first customer.
            <?php endif; ?>
          </p>
          <?php if (!$searchQuery): ?>
            <a href="customers-create.php" class="btn btn-primary">
              <i class="fas fa-plus-circle me-1"></i>Add First Customer
            </a>
          <?php else: ?>
            <a href="customers.php" class="btn btn-outline-primary">
              <i class="fas fa-times me-1"></i>Clear Search
            </a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
  .avatar-circle {
    font-weight: bold;
    font-size: 14px;
  }

  .empty-state-icon {
    opacity: 0.5;
  }

  .card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
  }

  .table th {
    border-top: none;
    font-weight: 600;
    color: #6e707e;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();

        const customerName = this.closest('tr').querySelector('strong').textContent;
        const deleteUrl = this.getAttribute('data-delete-url');

        Swal.fire({
          title: 'Delete Customer?',
          html: `You are about to delete <strong>${customerName}</strong>. This action cannot be undone.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!',
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