<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mt-4 mb-5">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-truck-loading fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Suppliers Management</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active">Suppliers</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="supplier-create.php" class="btn btn-primary">
      <i class="fas fa-plus me-1"></i>Add Supplier
    </a>
  </div>

  <!-- Suppliers Card -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
      <h4 class="mb-0 text-dark">
        <i class="fas fa-list me-2 text-primary"></i>All Suppliers
      </h4>
      <span class="badge bg-light text-dark border fs-6">
        <?php
        $suppliers = getAll('suppliers');
        $totalSuppliers = mysqli_num_rows($suppliers);
        echo $totalSuppliers . " Suppliers";
        ?>
      </span>
    </div>
    <div class="card-body p-4">
      <?php alertMessage(); ?>

      <?php
      if (!$suppliers) {
        echo '<div class="alert alert-danger">Something Went Wrong!</div>';
        return false;
      }

      if (mysqli_num_rows($suppliers) > 0) {
      ?>
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="bg-light">
              <tr>
                <th class="border-0">Supplier</th>
                <th class="border-0">Contact Info</th>
                <th class="border-0">Status</th>
                <th class="border-0 text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($suppliers as $item) : ?>
                <tr class="border-bottom">
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                        <i class="fas fa-building text-primary"></i>
                      </div>
                      <div>
                        <h6 class="mb-0 fw-bold"><?= $item['name']; ?></h6>
                        <small class="text-muted">ID: <?= $item['id']; ?></small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div>
                      <?php if (!empty($item['email'])): ?>
                        <div class="fw-semibold">
                          <i class="fas fa-envelope text-muted me-1"></i><?= $item['email']; ?>
                        </div>
                      <?php endif; ?>
                      <?php if (!empty($item['phone'])): ?>
                        <small class="text-muted">
                          <i class="fas fa-phone text-muted me-1"></i><?= $item['phone']; ?>
                        </small>
                      <?php endif; ?>
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
                  <td class="text-center">
                    <div class="btn-group" role="group">
                      <a href="supplier-edit.php?id=<?= $item['id']; ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                      </a>
                      <a
                        href="supplier-delete.php?id=<?= $item['id']; ?>"
                        class="btn btn-outline-danger btn-sm delete-btn"
                        data-delete-url="supplier-delete.php?id=<?= $item['id']; ?>"
                        data-supplier-name="<?= $item['name']; ?>">
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
            <i class="fas fa-truck-loading fa-4x text-muted"></i>
          </div>
          <h4 class="text-muted mb-3">No Suppliers Found</h4>
          <p class="text-muted mb-4">Get started by adding your first supplier.</p>
          <a href="supplier-create.php" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add First Supplier
          </a>
        </div>
      <?php
      }
      ?>
    </div>
  </div>

  <!-- Quick Stats -->
  <div class="row mt-4">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow-sm h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Suppliers</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalSuppliers ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-truck fa-2x text-gray-300"></i>
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
                Active Suppliers</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $activeSuppliers = mysqli_num_rows(getAll('suppliers', ['status' => 0]));
                echo $activeSuppliers;
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-eye fa-2x text-gray-300"></i>
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
                Hidden Suppliers</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $hiddenSuppliers = mysqli_num_rows(getAll('suppliers', ['status' => 1]));
                echo $hiddenSuppliers;
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-eye-slash fa-2x text-gray-300"></i>
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
                With Products</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $suppliersWithProducts = mysqli_num_rows(getAll('supplier_products', '', 'DISTINCT supplier_id'));
                echo $suppliersWithProducts;
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-boxes fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Products Supplied by Suppliers Section -->
  <div class="card mt-4">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
      <h4 class="mb-0 text-dark">
        <i class="fas fa-boxes me-2 text-primary"></i>Products by Suppliers
      </h4>
      <span class="badge bg-light text-dark border fs-6">
        Product Distribution
      </span>
    </div>
    <div class="card-body p-4">
      <?php
      // Function to fetch suppliers with their supplied products
      function getSuppliersWithProducts()
      {
        global $conn;

        $query = "SELECT s.id AS supplier_id, s.name AS supplier_name, p.id AS product_id, p.name AS product_name 
                          FROM suppliers s
                          LEFT JOIN supplier_products sp ON s.id = sp.supplier_id
                          LEFT JOIN products p ON sp.product_id = p.id
                          ORDER BY s.name, p.name";
        $result = mysqli_query($conn, $query);

        return $result;
      }

      $suppliersWithProducts = getSuppliersWithProducts();
      if (!$suppliersWithProducts) {
        echo '<div class="alert alert-danger">Something Went Wrong!</div>';
        return false;
      }

      if (mysqli_num_rows($suppliersWithProducts) > 0) {
        // Group products by supplier
        $groupedSuppliers = [];
        while ($row = mysqli_fetch_assoc($suppliersWithProducts)) {
          $supplierName = $row['supplier_name'];
          if ($row['product_name']) {
            $groupedSuppliers[$supplierName][] = $row;
          }
        }
      ?>
        <div class="row">
          <?php foreach ($groupedSuppliers as $supplierName => $products) : ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
              <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                  <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                      <i class="fas fa-building text-primary"></i>
                    </div>
                    <h6 class="mb-0 fw-bold"><?= $supplierName; ?></h6>
                  </div>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <span class="badge bg-primary">
                      <i class="fas fa-box me-1"></i><?= count($products); ?> Products
                    </span>
                  </div>
                  <div class="product-list">
                    <?php foreach ($products as $product) : ?>
                      <div class="d-flex align-items-center mb-2 p-2 rounded bg-light">
                        <i class="fas fa-cube text-muted me-2"></i>
                        <span class="small"><?= $product['product_name']; ?></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php
      } else {
      ?>
        <div class="text-center py-4">
          <div class="mb-3">
            <i class="fas fa-box-open fa-3x text-muted"></i>
          </div>
          <h5 class="text-muted mb-2">No Products Assigned</h5>
          <p class="text-muted">No products have been assigned to suppliers yet.</p>
        </div>
      <?php
      }
      ?>
    </div>
  </div>
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

  .product-list {
    max-height: 200px;
    overflow-y: auto;
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
        const supplierName = this.getAttribute('data-supplier-name');

        Swal.fire({
          title: 'Delete Supplier?',
          html: `You are about to delete <strong>${supplierName}</strong>. This action cannot be undone.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, Delete Supplier',
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