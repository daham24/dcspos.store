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
            <li class="breadcrumb-item active">Categories</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="categories-create.php" class="btn btn-primary">
      <i class="fas fa-plus-circle me-1"></i>Add Category
    </a>
  </div>

  <!-- Categories Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h5 class="card-title mb-0">
            <i class="fas fa-folder text-primary me-2"></i>Main Categories
          </h5>
        </div>
        <div class="col-md-6 text-end">
          <span class="badge bg-primary fs-6">
            <?php
            $categories = getAll('categories');
            echo mysqli_num_rows($categories) . ' Categories';
            ?>
          </span>
        </div>
      </div>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <?php
      $categories = getAll('categories');
      if (!$categories) {
        echo '<div class="alert alert-danger">Something Went Wrong!</div>';
      } elseif (mysqli_num_rows($categories) > 0) {
      ?>
        <div class="table-responsive">
          <table class="table table-hover table-bordered">
            <thead class="table-light">
              <tr>
                <th width="40%">Category Name</th>
                <th width="25%">Description</th>
                <th width="15%">Status</th>
                <th width="20%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $item) : ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="category-icon bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-folder"></i>
                      </div>
                      <div>
                        <strong><?= $item['name'] ?></strong>
                        <?php if ($item['description']): ?>
                          <br><small class="text-muted">ID: <?= $item['id'] ?></small>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td>
                    <?php if ($item['description']): ?>
                      <small class="text-muted"><?= $item['description'] ?></small>
                    <?php else: ?>
                      <span class="text-muted fst-italic">No description</span>
                    <?php endif; ?>
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
                    <div class="btn-group" role="group">
                      <a href="categories-edit.php?id=<?= $item['id'] ?>" class="btn btn-outline-primary btn-sm" title="Edit Category">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a
                        href="categories-delete.php?id=<?= $item['id']; ?>"
                        class="btn btn-outline-danger btn-sm delete-btn"
                        data-delete-url="categories-delete.php?id=<?= $item['id']; ?>"
                        data-category-name="<?= htmlspecialchars($item['name']); ?>"
                        title="Delete Category">
                        <i class="fas fa-trash"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php } else { ?>
        <!-- Empty State for Categories -->
        <div class="text-center py-5">
          <div class="empty-state-icon mb-3">
            <i class="fas fa-folder-open fa-4x text-muted"></i>
          </div>
          <h4 class="text-muted">No Categories Found</h4>
          <p class="text-muted mb-4">Get started by creating your first product category.</p>
          <a href="categories-create.php" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i>Create First Category
          </a>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Sub Categories Card -->
  <div class="card mt-4 shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h5 class="card-title mb-0">
            <i class="fas fa-folder-tree text-warning me-2"></i>Sub Categories
          </h5>
        </div>
        <div class="col-md-6 text-end">
          <?php
          $subCategoriesCount = getSubCategoriesWithCategoryName();
          $totalSubCategories = $subCategoriesCount ? mysqli_num_rows($subCategoriesCount) : 0;
          ?>
          <span class="badge bg-warning text-dark fs-6">
            <?= $totalSubCategories ?> Sub Categories
          </span>
        </div>
      </div>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <?php
      function getSubCategoriesWithCategoryName()
      {
        global $conn;
        $query = "SELECT sc.id, sc.name AS subcategory_name, c.name AS category_name, sc.status 
                          FROM sub_categories sc
                          LEFT JOIN categories c ON sc.category_id = c.id
                          ORDER BY c.name, sc.name";
        return mysqli_query($conn, $query);
      }

      $subCategories = getSubCategoriesWithCategoryName();
      if (!$subCategories) {
        echo '<div class="alert alert-danger">Something Went Wrong!</div>';
      } elseif (mysqli_num_rows($subCategories) > 0) {
        // Group subcategories by category
        $groupedSubCategories = [];
        while ($row = mysqli_fetch_assoc($subCategories)) {
          $categoryName = $row['category_name'];
          $groupedSubCategories[$categoryName][] = $row;
        }
      ?>
        <div class="row">
          <?php foreach ($groupedSubCategories as $categoryName => $subCategories) : ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
              <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-light py-3">
                  <h6 class="card-title mb-0 text-primary">
                    <i class="fas fa-folder me-2"></i><?= $categoryName; ?>
                  </h6>
                </div>
                <div class="card-body p-0">
                  <div class="list-group list-group-flush">
                    <?php foreach ($subCategories as $subCategory) : ?>
                      <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                          <i class="fas fa-folder text-warning me-2"></i>
                          <span class="fw-medium"><?= $subCategory['subcategory_name']; ?></span>
                        </div>
                        <div>
                          <?php if ($subCategory['status'] == 1): ?>
                            <span class="badge bg-danger btn-sm">
                              <i class="fas fa-eye-slash"></i>
                            </span>
                          <?php else: ?>
                            <span class="badge bg-success btn-sm">
                              <i class="fas fa-eye"></i>
                            </span>
                          <?php endif; ?>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pt-0">
                  <small class="text-muted">
                    <?= count($subCategories) ?> sub categor<?= count($subCategories) === 1 ? 'y' : 'ies' ?>
                  </small>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php } else { ?>
        <!-- Empty State for Sub Categories -->
        <div class="text-center py-5">
          <div class="empty-state-icon mb-3">
            <i class="fas fa-folder fa-4x text-muted"></i>
          </div>
          <h4 class="text-muted">No Sub Categories Found</h4>
          <p class="text-muted mb-4">Create subcategories to better organize your products under main categories.</p>
          <a href="categories-create.php" class="btn btn-warning">
            <i class="fas fa-plus-circle me-1"></i>Create Sub Category
          </a>
        </div>
      <?php } ?>
    </div>
  </div>

  <!-- Quick Stats -->
  <div class="row mt-4 mb-4">
    <div class="col-md-3">
      <div class="card bg-primary text-white">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <h4 class="mb-0"><?= mysqli_num_rows(getAll('categories')) ?></h4>
              <small>Total Categories</small>
            </div>
            <div class="align-self-center">
              <i class="fas fa-folder fa-2x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-success text-white">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <h4 class="mb-0">
                <?php
                $visibleCategories = getAll('categories');
                $visibleCount = 0;
                if ($visibleCategories) {
                  while ($cat = mysqli_fetch_assoc($visibleCategories)) {
                    if ($cat['status'] == 0) $visibleCount++;
                  }
                }
                echo $visibleCount;
                ?>
              </h4>
              <small>Visible Categories</small>
            </div>
            <div class="align-self-center">
              <i class="fas fa-eye fa-2x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-dark">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <h4 class="mb-0"><?= $totalSubCategories ?></h4>
              <small>Total Sub Categories</small>
            </div>
            <div class="align-self-center">
              <i class="fas fa-folder-tree fa-2x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-info text-white">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div>
              <h4 class="mb-0">
                <?php
                $categoriesWithSub = getAll('categories');
                $withSubCount = 0;
                if ($categoriesWithSub) {
                  while ($cat = mysqli_fetch_assoc($categoriesWithSub)) {
                    $subCheck = mysqli_query($conn, "SELECT COUNT(*) as count FROM sub_categories WHERE category_id = " . $cat['id']);
                    $subCount = mysqli_fetch_assoc($subCheck)['count'];
                    if ($subCount > 0) $withSubCount++;
                  }
                }
                echo $withSubCount;
                ?>
              </h4>
              <small>Categories with Sub</small>
            </div>
            <div class="align-self-center">
              <i class="fas fa-sitemap fa-2x opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
  .category-icon {
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

  .list-group-item {
    border-left: none;
    border-right: none;
  }

  .list-group-item:first-child {
    border-top: none;
  }

  .list-group-item:last-child {
    border-bottom: none;
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
        const categoryName = this.getAttribute('data-category-name');

        Swal.fire({
          title: 'Delete Category?',
          html: `You are about to delete <strong>"${categoryName}"</strong>. This action cannot be undone and will affect all associated subcategories and products.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel',
          reverseButtons: true,
          backdrop: true,
          showLoaderOnConfirm: true,
          preConfirm: () => {
            return new Promise((resolve) => {
              window.location.href = deleteUrl;
              resolve();
            });
          }
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: 'Deleted!',
              text: 'The category has been deleted.',
              icon: 'success',
              timer: 2000,
              showConfirmButton: false
            });
          }
        });
      });
    });

    // Add hover effects to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.transition = 'all 0.2s ease-in-out';
      });
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
      });
    });
  });
</script>