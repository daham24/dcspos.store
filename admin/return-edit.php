<?php
include('includes/header.php');
include('../config/dbCon.php');

// Fetch the return item id from the URL
$returnId = isset($_GET['id']) ? $_GET['id'] : 0;
if ($returnId == 0) {
    echo '<p>Invalid return ID.</p>';
    exit;
}

// Fetch return details from the database based on the ID
$query = "SELECT r.*, oi.product_id, oi.quantity AS order_quantity, p.name AS product_name, p.price AS product_price, 
          r.return_date, r.reason, r.status, o.tracking_no 
          FROM returns r 
          JOIN order_items oi ON r.order_item_id = oi.id 
          JOIN products p ON r.product_id = p.id
          JOIN orders o ON o.id = oi.order_id
          WHERE r.id = '$returnId'";

$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    $returnData = mysqli_fetch_assoc($result);
} else {
    echo '<p>Return item not found.</p>';
    exit;
}
?>

<div class="container-fluid px-4 mt-4 mb-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-undo-alt fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Edit Return Item</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="returns.php" class="text-decoration-none">Returns</a></li>
                        <li class="breadcrumb-item active">Edit Return</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="return-items-view.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Returns
        </a>
    </div>

    <!-- Edit Return Card -->
    <div class="card mt-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-edit me-2 text-primary"></i>Edit Return Details
            </h4>
            <span class="badge bg-light text-dark border">
                ID: <?= $returnData['id']; ?>
            </span>
        </div>
        <div class="card-body p-4">

            <!-- Form to edit return item details -->
            <form method="POST" action="code.php" id="editReturnForm">

                <!-- Hidden field to hold the return ID -->
                <input type="hidden" name="return_id" value="<?= $returnData['id'] ?>">

                <div class="row">
                    <!-- Tracking Number -->
                    <div class="col-md-6 mb-3">
                        <label for="tracking_no" class="form-label fw-semibold">Tracking Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-barcode text-muted"></i></span>
                            <input type="text" class="form-control" id="tracking_no" value="<?= $returnData['tracking_no'] ?>" disabled>
                        </div>
                    </div>

                    <!-- Product Name -->
                    <div class="col-md-6 mb-3">
                        <label for="product_name" class="form-label fw-semibold">Product Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-cube text-muted"></i></span>
                            <input type="text" class="form-control" id="product_name" value="<?= $returnData['product_name'] ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Quantity -->
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label fw-semibold">Quantity</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-boxes text-muted"></i></span>
                            <input type="number" class="form-control" id="quantity" value="<?= $returnData['order_quantity'] ?>" disabled>
                        </div>
                    </div>

                    <!-- Return Date -->
                    <div class="col-md-6 mb-3">
                        <label for="return_date" class="form-label fw-semibold">Return Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" class="form-control" id="return_date" name="return_date" value="<?= date('Y-m-d', strtotime($returnData['return_date'])) ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Reason for Return -->
                <div class="mb-3">
                    <label for="reason" class="form-label fw-semibold">Reason for Return <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light align-items-start pt-3"><i class="fas fa-comment text-muted"></i></span>
                        <textarea class="form-control" id="reason" name="reason" rows="4" placeholder="Enter reason for return" required><?= $returnData['reason'] ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <!-- Status -->
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-tasks text-muted"></i></span>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" <?= $returnData['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="completed" <?= $returnData['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="return-items-view.php" class="btn btn-outline-secondary w-100 py-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" name="updateReturn" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-save me-2"></i>Update Return
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Return Information Card -->
    <div class="card mt-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-dark">
                <i class="fas fa-info-circle me-2 text-primary"></i>Current Return Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted mb-2">Product</h6>
                        <p class="fw-bold mb-0"><?= $returnData['product_name']; ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted mb-2">Quantity</h6>
                        <p class="fw-bold text-info mb-0"><?= $returnData['order_quantity']; ?> items</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted mb-2">Current Status</h6>
                        <span class="badge <?= $returnData['status'] == 'pending' ? 'bg-warning' : 'bg-success' ?>">
                            <?= ucfirst($returnData['status']); ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted mb-2">Return Date</h6>
                        <p class="fw-bold mb-0"><?= date('M j, Y', strtotime($returnData['return_date'])); ?></p>
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

    .badge {
        font-weight: 500;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation for edit return
        const editReturnForm = document.getElementById('editReturnForm');
        if (editReturnForm) {
            editReturnForm.addEventListener('submit', function(e) {
                const returnDate = document.getElementById('return_date').value;
                const reason = document.getElementById('reason').value.trim();
                const status = document.getElementById('status').value;

                if (!returnDate || !reason || !status) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Missing Information',
                        text: 'Please fill in all required fields.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    return;
                }

                if (reason.length < 10) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Reason Too Short',
                        text: 'Please provide a detailed reason (at least 10 characters).',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    document.getElementById('reason').focus();
                    return;
                }
            });
        }
    });
</script>