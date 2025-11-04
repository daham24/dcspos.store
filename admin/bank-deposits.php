<?php
include('includes/header.php');
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
            <i class="fas fa-money-bill-wave fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Daily Bank Deposit</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Bank Deposits</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="index.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>

    <!-- Add Bank and Account Modal -->
    <div class="modal fade" id="addBankAccountModal" tabindex="-1" aria-labelledby="addBankAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title text-dark" id="addBankAccountModalLabel">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>Add Bank & Account
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 border-end">
                            <h5 class="text-dark mb-3">
                                <i class="fas fa-university me-2 text-primary"></i>Add Bank
                            </h5>
                            <form method="POST" action="bank-deposit-process.php" id="addBankForm">
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label fw-semibold">Bank Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-building text-muted"></i></span>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Enter bank name" required>
                                    </div>
                                </div>
                                <button type="button" id="addBankBtn" class="btn btn-outline-primary w-100 mt-2 mb-2">
                                    <i class="fas fa-save me-2"></i>Add Bank
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-dark mb-3">
                                <i class="fas fa-credit-card me-2 text-primary"></i>Add Account
                            </h5>
                            <form method="POST" action="bank-deposit-process.php">
                                <div class="mb-3">
                                    <label for="bank_id" class="form-label fw-semibold">Select Bank</label>
                                    <select name="bank_id" class="form-select" required>
                                        <option value="">Select a Bank</option>
                                        <?php
                                        $banks = mysqli_query($conn, "SELECT * FROM banks");
                                        while ($bank = mysqli_fetch_assoc($banks)) {
                                            echo "<option value='{$bank['id']}'>{$bank['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="account_number" class="form-label fw-semibold">Account Number</label>
                                    <input type="text" name="account_number" class="form-control" placeholder="Enter account number" required>
                                </div>
                                <button type="submit" name="add_account" class="btn btn-outline-primary w-100 mt-2">
                                    <i class="fas fa-save me-2"></i>Add Account
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card for Adding Daily Deposit -->
    <div class="card mt-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-piggy-bank me-2 text-primary"></i>Add Daily Deposit
            </h4>
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBankAccountModal">
                <i class="fas fa-plus me-1"></i>Add Bank/Account
            </button>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="bank-deposit-process.php">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bank_id" class="form-label fw-semibold">Select Bank</label> <span class="text-danger">*</span>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-university text-muted"></i></span>
                            <select name="bank_id" class="form-select" id="bank_id" required>
                                <option value="">Select a Bank</option>
                                <?php
                                // Fetch banks
                                $banks = mysqli_query($conn, "SELECT * FROM banks");
                                while ($bank = mysqli_fetch_assoc($banks)) {
                                    echo "<option value='{$bank['id']}'>{$bank['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="account_id" class="form-label fw-semibold">Select Account</label> <span class="text-danger">*</span>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-credit-card text-muted"></i></span>
                            <select name="account_id" class="form-select" id="account_id" required>
                                <option value="">Select a Bank First</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="deposit_amount" class="form-label fw-semibold">Deposit Amount</label> <span class="text-danger">*</span>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-dollar-sign text-muted"></i></span>
                            <input type="number" name="deposit_amount" class="form-control" placeholder="Enter deposit amount" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="deposit_date" class="form-label fw-semibold">Deposit Date</label> <span class="text-danger">*</span>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" name="deposit_date" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" name="add_deposit" class="btn btn-primary py-2">
                        <i class="fas fa-plus-circle me-2"></i>Add Deposit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table to Display Deposits -->
    <div class="card mt-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-list me-2 text-primary"></i>All Deposits
            </h4>
            <span class="badge bg-light text-dark border fs-6">
                <?php
                $countQuery = "SELECT COUNT(*) as total FROM daily_deposits";
                $countResult = mysqli_query($conn, $countQuery);
                $countData = mysqli_fetch_assoc($countResult);
                echo $countData['total'] . " Records";
                ?>
            </span>
        </div>
        <div class="card-body p-4">
            <?php
            // Query to fetch deposits
            $query = "SELECT dd.*, ba.account_number, b.name AS bank_name 
                      FROM daily_deposits dd
                      JOIN bank_accounts ba ON dd.bank_account_id = ba.id
                      JOIN banks b ON ba.bank_id = b.id
                      ORDER BY dd.deposit_date DESC";
            $deposits = mysqli_query($conn, $query);
            ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Bank Name</th>
                            <th class="border-0">Account Number</th>
                            <th class="border-0">Deposit Amount</th>
                            <th class="border-0">Deposit Date</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($deposit = mysqli_fetch_assoc($deposits)) : ?>
                            <tr class="border-bottom">
                                <td class="fw-semibold"><?= $deposit['bank_name']; ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= $deposit['account_number']; ?></span>
                                </td>
                                <td class="fw-bold text-success">$<?= number_format($deposit['deposit_amount'], 2); ?></td>
                                <td>
                                    <span class="text-muted"><?= date('M j, Y', strtotime($deposit['deposit_date'])); ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="bank-deposit-edit.php?id=<?= $deposit['id']; ?>" class="btn btn-outline-dark btn-sm">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                        <a href="bank-deposit-delete.php?id=<?= $deposit['id']; ?>" class="btn btn-outline-danger btn-sm delete-btn" data-id="<?= $deposit['id']; ?>">
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

    .product-info {
        font-size: 0.875rem;
    }

    .return-item-checkbox:checked {
        background-color: #dc3545;
        border-color: #dc3545;
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
        // JavaScript to Load Accounts Based on Selected Bank
        document.getElementById('bank_id').addEventListener('change', function() {
            const bankId = this.value;

            // Get accounts related to selected bank
            if (bankId) {
                fetch('bank-deposit-process.php?bank_id=' + bankId)
                    .then(response => response.json())
                    .then(data => {
                        const accountSelect = document.getElementById('account_id');
                        accountSelect.innerHTML = '<option value="">Select an Account</option>';

                        // Populate account options
                        data.forEach(account => {
                            const option = document.createElement('option');
                            option.value = account.id;
                            option.textContent = account.account_number;
                            accountSelect.appendChild(option);
                        });
                    });
            } else {
                // Clear account options if no bank selected
                document.getElementById('account_id').innerHTML = '<option value="">Select a Bank First</option>';
            }
        });

        // SweetAlert for delete confirmation
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent the default link behavior

                const deleteUrl = this.getAttribute('href'); // Get the URL for the delete action

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
                        window.location.href = deleteUrl; // Redirect to delete URL
                    }
                });
            });
        });

        // Enhanced form validation for deposit form
        const depositForm = document.querySelector('form[action="bank-deposit-process.php"]');
        if (depositForm) {
            depositForm.addEventListener('submit', function(e) {
                const bankId = document.getElementById('bank_id').value;
                const accountId = document.getElementById('account_id').value;
                const depositAmount = document.querySelector('input[name="deposit_amount"]').value;
                const depositDate = document.querySelector('input[name="deposit_date"]').value;

                if (!bankId || !accountId || !depositAmount || !depositDate) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Missing Information',
                        text: 'Please fill in all required fields.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    return;
                }

                if (parseFloat(depositAmount) <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Invalid Amount',
                        text: 'Please enter a valid deposit amount greater than zero.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    document.querySelector('input[name="deposit_amount"]').focus();
                    return;
                }

                // Confirm deposit action
                e.preventDefault();
                Swal.fire({
                    title: 'Add Deposit?',
                    html: `You are about to deposit <strong>$${parseFloat(depositAmount).toFixed(2)}</strong> on <strong>${depositDate}</strong>.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#000',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Add Deposit',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        depositForm.submit();
                    }
                });
            });
        }

        // Add bank form validation
        const bankForms = document.querySelectorAll('form[action="bank-deposit-process.php"]');
        bankForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const bankNameInput = form.querySelector('input[name="bank_name"]');
                if (bankNameInput) {
                    const bankName = bankNameInput.value.trim();
                    if (bankName.length < 2) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Invalid Bank Name',
                            text: 'Please enter a valid bank name (at least 2 characters).',
                            icon: 'warning',
                            confirmButtonColor: '#6c757d'
                        });
                        bankNameInput.focus();
                    }
                }

                const accountNumberInput = form.querySelector('input[name="account_number"]');
                if (accountNumberInput) {
                    const accountNumber = accountNumberInput.value.trim();
                    if (accountNumber.length < 5 || !/^\d+$/.test(accountNumber)) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Invalid Account Number',
                            text: 'Please enter a valid account number (at least 5 digits).',
                            icon: 'warning',
                            confirmButtonColor: '#6c757d'
                        });
                        accountNumberInput.focus();
                    }
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Add Bank with AJAX
        const addBankBtn = document.getElementById('addBankBtn');
        const bankNameInput = document.getElementById('bank_name');
        const accountBankSelect = document.getElementById('account_bank_id');

        if (addBankBtn) {
            addBankBtn.addEventListener('click', function() {
                const bankName = bankNameInput.value.trim();

                // Validate bank name
                if (!bankName || bankName.length < 2) {
                    Swal.fire({
                        title: 'Invalid Bank Name',
                        text: 'Bank name must be at least 2 characters long.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    return;
                }

                // Show loading state
                const originalText = addBankBtn.innerHTML;
                addBankBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
                addBankBtn.disabled = true;

                // Submit via AJAX
                fetch('bank-deposit-process.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'add_bank=true&bank_name=' + encodeURIComponent(bankName)
                    })
                    .then(response => response.text())
                    .then(data => {
                        // Check if successful (you might want to return JSON for better handling)
                        if (data.includes('success') || !data.includes('error')) {
                            // Add the new bank to the dropdown
                            const newOption = document.createElement('option');
                            // We need to get the actual ID from the server
                            // For now, we'll use a temporary approach and reload the page

                            Swal.fire({
                                title: 'Success!',
                                text: 'Bank added successfully!',
                                icon: 'success',
                                confirmButtonColor: '#28a745'
                            }).then((result) => {
                                // Clear the input
                                bankNameInput.value = '';
                                // Reload the page to refresh the bank list
                                location.reload();
                            });
                        } else {
                            throw new Error('Failed to add bank');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to add bank. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    })
                    .finally(() => {
                        // Reset button state
                        addBankBtn.innerHTML = originalText;
                        addBankBtn.disabled = false;
                    });
            });
        }

        // Add Account form submission with confirmation
        const addAccountForm = document.getElementById('addAccountForm');
        if (addAccountForm) {
            addAccountForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const bankId = document.getElementById('account_bank_id').value;
                const accountNumber = document.querySelector('#addAccountForm input[name="account_number"]').value;

                // Validate inputs
                if (!bankId || !accountNumber) {
                    Swal.fire({
                        title: 'Missing Information',
                        text: 'Please fill in all required fields.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    return;
                }

                if (accountNumber.length < 5 || !/^\d+$/.test(accountNumber)) {
                    Swal.fire({
                        title: 'Invalid Account Number',
                        text: 'Account number must be at least 5 digits and contain only numbers.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    return;
                }

                // Show confirmation
                Swal.fire({
                    title: 'Add Account?',
                    html: `You are about to add account number <strong>${accountNumber}</strong>.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Add Account',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form
                        addAccountForm.submit();
                    }
                });
            });
        }
    });
</script>