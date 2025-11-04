<?php
include('includes/header.php');
include('../config/dbCon.php');

// Check if 'id' is passed as a parameter
if (isset($_GET['id'])) {
    $billId = $_GET['id'];

    // Fetch the bill record for editing
    $query = "SELECT * FROM utility_bills WHERE id = '$billId'";
    $result = mysqli_query($conn, $query);
    $bill = mysqli_fetch_assoc($result);

    // If bill not found
    if (!$bill) {
        echo "Bill not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}
?>

<div class="container-fluid px-4 mt-4 mb-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-edit fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Edit Utility Bill</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="utility-bills.php" class="text-decoration-none">Utility Bills</a></li>
                        <li class="breadcrumb-item active">Edit Bill</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="utility-bills.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Bills
        </a>
    </div>

    <!-- Edit Bill Card -->
    <div class="card mt-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-edit me-2 text-primary"></i>Edit Bill Details
            </h4>
            <span class="badge bg-light text-dark border">
                ID: <?= $bill['id']; ?>
            </span>
        </div>
        <div class="card-body p-4">

            <?php alertMessage(); ?> <!-- Display the alert message if exists -->

            <form method="POST" action="utility-bill-process.php" id="editBillForm">
                <input type="hidden" name="bill_id" value="<?= $bill['id']; ?>">

                <div class="row">
                    <!-- Bill Type -->
                    <div class="col-md-6 mb-3">
                        <label for="bill_type" class="form-label fw-semibold">Bill Type <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-receipt text-muted"></i></span>
                            <input type="text" name="bill_type" class="form-control"
                                value="<?= $bill['bill_type']; ?>"
                                placeholder="Enter bill type" required>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-dollar-sign text-muted"></i></span>
                            <input type="number" name="amount" class="form-control"
                                value="<?= $bill['amount']; ?>"
                                placeholder="0.00" step="0.01" min="0.01" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Bill Date -->
                    <div class="col-md-6 mb-3">
                        <label for="bill_date" class="form-label fw-semibold">Bill Date <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="bill_date" class="form-control"
                                value="<?= $bill['bill_date']; ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="utility-bills.php" class="btn btn-outline-secondary w-100 py-2">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" name="update_bill" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-save me-2"></i>Update Bill
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Bill Info Card -->
    <div class="card mt-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-dark">
                <i class="fas fa-info-circle me-2 text-primary"></i>Current Bill Information
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted mb-2">Bill Type</h6>
                        <p class="fw-bold mb-0"><?= $bill['bill_type']; ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted mb-2">Current Amount</h6>
                        <p class="fw-bold text-success mb-0">$<?= number_format($bill['amount'], 2); ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted mb-2">Bill Date</h6>
                        <p class="fw-bold mb-0"><?= date('M j, Y', strtotime($bill['bill_date'])); ?></p>
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
</style>