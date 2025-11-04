<?php
include('includes/header.php');
include('../config/dbCon.php');

// Check if 'id' is passed as a parameter
if (isset($_GET['id'])) {
    $depositId = $_GET['id'];

    $query = "SELECT dd.*, ba.account_number, ba.bank_id, b.name AS bank_name 
          FROM daily_deposits dd
          JOIN bank_accounts ba ON dd.bank_account_id = ba.id
          JOIN banks b ON ba.bank_id = b.id
          WHERE dd.id = '$depositId'";

    $result = mysqli_query($conn, $query);
    $deposit = mysqli_fetch_assoc($result);

    // If deposit not found
    if (!$deposit) {
        echo "Deposit not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

// Fetch all banks and accounts for dropdowns
$banks = mysqli_query($conn, "SELECT * FROM banks");
$accounts = mysqli_query($conn, "SELECT * FROM bank_accounts");

?>

<div class="container-fluid px-4 mt-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-edit fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-1 text-gray-800">Edit Bank Deposit</h1>
                <p class="text-muted mb-0">Update deposit information</p>
            </div>
        </div>
        <a href="bank-deposits.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Deposits
        </a>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <!-- Edit Deposit Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Edit Deposit Details
                    </h5>
                </div>
                <div class="card-body">

                    <?php alertMessage(); ?> <!-- Display the alert message if exists -->

                    <form method="POST" action="bank-deposit-process.php">
                        <input type="hidden" name="deposit_id" value="<?= $deposit['id']; ?>">

                        <div class="row">
                            <!-- Select Bank -->
                            <div class="col-md-6 mb-3">
                                <label for="bank_id" class="form-label fw-semibold">Select Bank <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-university text-muted"></i></span>
                                    <select name="bank_id" class="form-select" required>
                                        <option value="">Select a Bank</option>
                                        <?php while ($bank = mysqli_fetch_assoc($banks)) : ?>
                                            <option value="<?= $bank['id']; ?>"
                                                <?= $bank['id'] == $deposit['bank_id'] ? 'selected' : ''; ?>>
                                                <?= $bank['name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Select Account -->
                            <div class="col-md-6 mb-3">
                                <label for="account_id" class="form-label fw-semibold">Select Account <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-credit-card text-muted"></i></span>
                                    <select name="account_id" class="form-select border-start-0" required>
                                        <option value="<?= $deposit['bank_account_id']; ?>" selected><?= $deposit['account_number']; ?></option>
                                        <?php
                                        mysqli_data_seek($accounts, 0);
                                        while ($account = mysqli_fetch_assoc($accounts)) : ?>
                                            <option value="<?= $account['id']; ?>"><?= $account['account_number']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Deposit Amount -->
                            <div class="col-md-6 mb-3">
                                <label for="deposit_amount" class="form-label fw-semibold">Deposit Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-dollar-sign text-muted"></i></span>
                                    <input type="number" name="deposit_amount" class="form-control border-start-0"
                                        value="<?= $deposit['deposit_amount']; ?>"
                                        placeholder="0.00" step="0.01" min="0.01" required>
                                </div>
                            </div>

                            <!-- Deposit Date -->
                            <div class="col-md-6 mb-3">
                                <label for="deposit_date" class="form-label fw-semibold">Deposit Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                                    <input type="date" name="deposit_date" class="form-control border-start-0"
                                        value="<?= $deposit['deposit_date']; ?>" required>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-6 mb-2">
                                <a href="bank-deposit.php" class="btn btn-outline-secondary w-100 py-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                            <div class="col-md-6 mb-2">
                                <button type="submit" name="update_deposit" class="btn btn-primary w-100 py-2">
                                    <i class="fas fa-save me-2"></i>Update Deposit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- Current Deposit Info Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Deposit Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Deposit ID</small>
                        <div class="fw-bold text-dark">#<?= $deposit['id']; ?></div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Bank Name</small>
                        <div class="fw-bold text-dark"><?= $deposit['bank_name']; ?></div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Account Number</small>
                        <div class="fw-bold text-dark"><?= $deposit['account_number']; ?></div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Current Amount</small>
                        <div class="fw-bold text-success fs-5">$<?= number_format($deposit['deposit_amount'], 2); ?></div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Deposit Date</small>
                        <div class="fw-bold text-dark"><?= date('F j, Y', strtotime($deposit['deposit_date'])); ?></div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="bank-deposits.php" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-list me-1"></i>View All Deposits
                        </a>
                        <a href="bank-deposits.php" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-plus me-1"></i>Add New Deposit
                        </a>
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
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
        background-color: #f8f9fc;
    }

    .form-control,
    .form-select {
        border-radius: 0.375rem;
        border: 1px solid #d1d3e2;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #bac8f3;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .input-group-text {
        background-color: #f8f9fc;
        border: 1px solid #d1d3e2;
    }

    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }
</style>