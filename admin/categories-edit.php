<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-edit fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Edit Category</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="categories.php" class="text-decoration-none">Categories</a></li>
            <li class="breadcrumb-item active">Edit Category</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="categories.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Categories
    </a>
  </div>

  <?php
  $paramValue = checkParamId('id');

  if (!is_numeric($paramValue)) {
    echo '<div class="alert alert-danger mt-4">' . $paramValue . '</div>';
  } else {
    $category = getById('categories', $paramValue);
    if ($category['status'] != 200) {
      echo '<div class="alert alert-danger mt-4">' . $category['message'] . '</div>';
    } else {
      $categoryData = $category['data'];
  ?>

      <!-- Edit Main Category Card -->
      <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
          <h5 class="card-title mb-0">
            <i class="fas fa-folder text-primary me-2"></i>Edit Main Category
          </h5>
        </div>
        <div class="card-body">
          <?php alertMessage(); ?>

          <form action="code.php" method="POST">
            <input type="hidden" name="categoryId" value="<?= $categoryData['id']; ?>">

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
                    value="<?= htmlspecialchars($categoryData['name']); ?>"
                    required
                    class="form-control"
                    placeholder="Enter category name"
                    maxlength="100" />
                </div>
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
                    <?= $categoryData['status'] == 1 ? 'checked' : ''; ?>
                    style="width: 3rem; height: 1.5rem;">
                  <label class="form-check-label fw-semibold" for="categoryStatus">
                    <span id="categoryStatusText" class="<?= $categoryData['status'] == 1 ? 'text-danger' : 'text-success'; ?>">
                      <?= $categoryData['status'] == 1 ? 'Hidden' : 'Visible'; ?>
                    </span>
                  </label>
                </div>
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
                    placeholder="Enter category description"
                    maxlength="255"><?= htmlspecialchars($categoryData['description']); ?></textarea>
                </div>
                <div class="form-text">Brief description of what products belong in this category</div>
              </div>

              <!-- Action Buttons -->
              <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center border-top pt-4">
                  <div class="d-flex gap-2">
                    <a href="categories.php" class="btn btn-outline-secondary">
                      <i class="fas fa-times me-1"></i>Cancel
                    </a>
                    <button type="submit" name="updateCategory" class="btn btn-primary">
                      <i class="fas fa-save me-1"></i>Update Category
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Sub Categories Management Card -->
      <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
          <h5 class="card-title mb-0">
            <i class="fas fa-folder-tree text-warning me-2"></i>Manage Sub Categories
            <span class="badge bg-warning text-dark ms-2" id="subCategoryCount">0</span>
          </h5>
        </div>
        <div class="card-body">
          <?php alertMessage(); ?>

          <?php
          function getSubCategoriesByCategoryId($categoryId)
          {
            global $conn;
            $query = "SELECT * FROM sub_categories WHERE category_id = $categoryId ORDER BY name ASC";
            $result = mysqli_query($conn, $query);
            return $result;
          }

          $subCategories = getSubCategoriesByCategoryId($paramValue);
          $hasSubCategories = $subCategories && mysqli_num_rows($subCategories) > 0;
          ?>

          <!-- Existing Sub Categories -->
          <?php if ($hasSubCategories): ?>
            <div class="mb-4">
              <h6 class="fw-semibold mb-3 text-muted">Existing Sub Categories</h6>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead class="table-light">
                    <tr>
                      <th width="40%">Sub Category Name</th>
                      <th width="20%">Status</th>
                      <th width="20%">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($subCategory = mysqli_fetch_assoc($subCategories)): ?>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <i class="fas fa-folder text-warning me-2"></i>
                            <strong><?= htmlspecialchars($subCategory['name']); ?></strong>
                          </div>
                        </td>
                        <td>
                          <?php if ($subCategory['status'] == 1): ?>
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
                          <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary edit-subcategory"
                              data-id="<?= $subCategory['id']; ?>"
                              data-name="<?= htmlspecialchars($subCategory['name']); ?>"
                              data-status="<?= $subCategory['status']; ?>">
                              <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger delete-subcategory"
                              data-id="<?= $subCategory['id']; ?>"
                              data-name="<?= htmlspecialchars($subCategory['name']); ?>">
                              <i class="fas fa-trash"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php else: ?>
            <div class="text-center py-4">
              <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
              <h6 class="text-muted">No Sub Categories Found</h6>
              <p class="text-muted mb-0">Add your first subcategory below to get started.</p>
            </div>
          <?php endif; ?>

          <!-- Add New Sub Category -->
          <div class="border-top pt-4">
            <h6 class="fw-semibold mb-3 text-muted">
              <i class="fas fa-plus-circle me-2"></i>Add New Sub Category
            </h6>
            <form action="code.php" method="POST" id="addSubCategoryForm">
              <input type="hidden" name="categoryId" value="<?= $paramValue; ?>">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-semibold">Sub Category Name <span class="text-danger">*</span></label>
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
                </div>
                <div class="col-md-4 mb-3">
                  <label class="form-label fw-semibold">Status</label>
                  <div class="form-check form-switch mt-2">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="status"
                      id="newSubCategoryStatus"
                      style="width: 3rem; height: 1.5rem;">
                    <label class="form-check-label fw-semibold" for="newSubCategoryStatus">
                      <span id="newSubCategoryStatusText" class="text-success">Visible</span>
                    </label>
                  </div>
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">&nbsp;</label>
                  <button type="submit" name="saveSubCategory" class="btn btn-warning w-100">
                    <i class="fas fa-plus me-1"></i>Add
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

  <?php
    }
  }
  ?>
</div>

<?php include('includes/footer.php'); ?>

<!-- Edit Sub Category Modal -->
<div class="modal fade" id="editSubCategoryModal" tabindex="-1" aria-labelledby="editSubCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSubCategoryModalLabel">Edit Sub Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="code.php" method="POST" id="editSubCategoryForm">
        <div class="modal-body">
          <input type="hidden" name="subCategoryId" id="editSubCategoryId">
          <input type="hidden" name="categoryId" value="<?= $paramValue; ?>">

          <div class="mb-3">
            <label class="form-label fw-semibold">Sub Category Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="editSubCategoryName" required class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="status" id="editSubCategoryStatus">
              <label class="form-check-label fw-semibold" for="editSubCategoryStatus">
                <span id="editSubCategoryStatusText">Visible</span>
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="updateSubCategory" class="btn btn-primary">Update Sub Category</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete the subcategory "<strong id="deleteSubCategoryName"></strong>"?</p>
        <p class="text-danger small mb-0">This action cannot be undone and will remove this subcategory from the system.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmDeleteButton" class="btn btn-danger">Delete Sub Category</button>
      </div>
    </div>
  </div>
</div>

<style>
  .form-check-input:checked {
    background-color: #dc3545;
    border-color: #dc3545;
  }

  .card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
  }

  .btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Update subcategory count
    const subCategoryCount = document.querySelectorAll('tbody tr').length;
    document.getElementById('subCategoryCount').textContent = subCategoryCount;

    // Main category status toggle
    const categoryStatus = document.getElementById('categoryStatus');
    const categoryStatusText = document.getElementById('categoryStatusText');

    if (categoryStatus) {
      categoryStatus.addEventListener('change', function() {
        categoryStatusText.textContent = this.checked ? 'Hidden' : 'Visible';
        categoryStatusText.className = this.checked ? 'text-danger' : 'text-success';
      });
    }

    // New subcategory status toggle
    const newSubCategoryStatus = document.getElementById('newSubCategoryStatus');
    const newSubCategoryStatusText = document.getElementById('newSubCategoryStatusText');

    if (newSubCategoryStatus) {
      newSubCategoryStatus.addEventListener('change', function() {
        newSubCategoryStatusText.textContent = this.checked ? 'Hidden' : 'Visible';
        newSubCategoryStatusText.className = this.checked ? 'text-danger' : 'text-success';
      });
    }

    // Edit subcategory functionality
    const editButtons = document.querySelectorAll('.edit-subcategory');
    const editModal = new bootstrap.Modal(document.getElementById('editSubCategoryModal'));
    const editSubCategoryStatus = document.getElementById('editSubCategoryStatus');
    const editSubCategoryStatusText = document.getElementById('editSubCategoryStatusText');

    editButtons.forEach(button => {
      button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const status = this.getAttribute('data-status') === '1';

        document.getElementById('editSubCategoryId').value = id;
        document.getElementById('editSubCategoryName').value = name;
        editSubCategoryStatus.checked = status;
        editSubCategoryStatusText.textContent = status ? 'Hidden' : 'Visible';
        editSubCategoryStatusText.className = status ? 'text-danger' : 'text-success';

        editModal.show();
      });
    });

    // Edit modal status toggle
    if (editSubCategoryStatus) {
      editSubCategoryStatus.addEventListener('change', function() {
        editSubCategoryStatusText.textContent = this.checked ? 'Hidden' : 'Visible';
        editSubCategoryStatusText.className = this.checked ? 'text-danger' : 'text-success';
      });
    }

    // Delete subcategory functionality
    let deleteSubCategoryId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));

    document.querySelectorAll('.delete-subcategory').forEach(button => {
      button.addEventListener('click', function() {
        deleteSubCategoryId = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        document.getElementById('deleteSubCategoryName').textContent = name;
        deleteModal.show();
      });
    });

    // Handle delete confirmation
    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
      if (deleteSubCategoryId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'code.php';

        const subCategoryIdInput = document.createElement('input');
        subCategoryIdInput.type = 'hidden';
        subCategoryIdInput.name = 'subCategoryId';
        subCategoryIdInput.value = deleteSubCategoryId;

        const categoryIdInput = document.createElement('input');
        categoryIdInput.type = 'hidden';
        categoryIdInput.name = 'categoryId';
        categoryIdInput.value = <?= $paramValue; ?>;

        const deleteActionInput = document.createElement('input');
        deleteActionInput.type = 'hidden';
        deleteActionInput.name = 'deleteSubCategory';
        deleteActionInput.value = '1';

        form.appendChild(subCategoryIdInput);
        form.appendChild(categoryIdInput);
        form.appendChild(deleteActionInput);

        document.body.appendChild(form);
        form.submit();
      }
    });
  });
</script>