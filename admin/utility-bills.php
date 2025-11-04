<?php
include('includes/header.php');
include('../config/dbCon.php');
?>

<div class="container-fluid px-4 mt-4 mb-5">
    <?php
    // Display message if exists
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>' . $_SESSION['message'] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        unset($_SESSION['message']);
    }
    ?>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-lightbulb fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Utility Bills Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Utility Bills</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="index.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>

    <!-- Add Bill Type Modal -->
    <div class="modal fade" id="addBillTypeModal" tabindex="-1" aria-labelledby="addBillTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title text-dark" id="addBillTypeModalLabel">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>Add Bill Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="utility-bill-process.php">
                        <div class="mb-3">
                            <label for="bill_type" class="form-label fw-semibold">Bill Type <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-receipt text-muted"></i></span>
                                <input type="text" name="bill_type" class="form-control" placeholder="Enter bill type (e.g., Electricity, Water)" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="add_bill_type" class="btn btn-primary py-2">
                                <i class="fas fa-save me-2"></i>Add Bill Type
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for Adding Bills -->
    <div class="card mt-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-plus-circle me-2 text-primary"></i>Add Utility Bill
            </h4>
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBillTypeModal">
                <i class="fas fa-plus me-1"></i>Add Bill Type
            </button>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="utility-bill-process.php">
                <div class="row">
                    <!-- Bill Type Selection -->
                    <div class="col-md-4 mb-3">
                        <label for="bill_type" class="form-label fw-semibold">Select Bill Type <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-list text-muted"></i></span>
                            <select name="bill_type" class="form-select" required>
                                <option value="">Select a Bill Type</option>
                                <?php
                                // Fetch bill types
                                $billTypes = mysqli_query($conn, "SELECT DISTINCT bill_type FROM utility_bills");
                                while ($billType = mysqli_fetch_assoc($billTypes)) {
                                    echo "<option value='{$billType['bill_type']}'>{$billType['bill_type']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="col-md-4 mb-3">
                        <label for="amount" class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-dollar-sign text-muted"></i></span>
                            <input type="number" name="amount" class="form-control" placeholder="0.00" step="0.01" min="0.01" required>
                        </div>
                    </div>

                    <!-- Bill Date -->
                    <div class="col-md-4 mb-3">
                        <label for="bill_date" class="form-label fw-semibold">Bill Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="bill_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" name="add_bill" class="btn btn-primary py-2">
                        <i class="fas fa-plus-circle me-2"></i>Add Bill
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table to Display Bills -->
    <div class="card mt-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-list me-2 text-primary"></i>All Utility Bills
            </h4>
            <span class="badge bg-light text-dark border fs-6">
                <?php
                $countQuery = "SELECT COUNT(*) as total FROM utility_bills WHERE amount > 0";
                $countResult = mysqli_query($conn, $countQuery);
                $countData = mysqli_fetch_assoc($countResult);
                echo $countData['total'] . " Records";
                ?>
            </span>
        </div>
        <div class="card-body p-4">
            <?php
            // Query to fetch bills
            $query = "SELECT * FROM utility_bills WHERE amount > 0 ORDER BY bill_date DESC";
            $bills = mysqli_query($conn, $query);
            ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Bill Type</th>
                            <th class="border-0">Amount</th>
                            <th class="border-0">Bill Date</th>
                            <th class="border-0">Created At</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($bill = mysqli_fetch_assoc($bills)) : ?>
                            <tr class="border-bottom">
                                <td class="fw-semibold">
                                    <i class="fas fa-receipt text-primary me-2"></i><?= $bill['bill_type']; ?>
                                </td>
                                <td class="fw-bold text-success">$<?= number_format($bill['amount'], 2); ?></td>
                                <td>
                                    <span class="text-muted"><?= date('M j, Y', strtotime($bill['bill_date'])); ?></span>
                                </td>
                                <td>
                                    <span class="text-muted"><?= date('M j, Y g:i A', strtotime($bill['created_at'])); ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="utility-bill-edit.php?id=<?= $bill['id']; ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <a href="utility-bill-delete.php?id=<?= $bill['id']; ?>" class="btn btn-outline-danger btn-sm delete-btn">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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

    .table tbody tr:hover {
        background-color: #f8f9fc;
    }

    .badge {
        font-weight: 500;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert for delete confirmation
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const deleteUrl = this.getAttribute('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    });
</script>