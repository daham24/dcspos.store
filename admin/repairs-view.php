<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mt-4 mb-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-tools fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Repair Details</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="repairs.php" class="text-decoration-none">Repairs</a></li>
                        <li class="breadcrumb-item active">Repair Details</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="repairs.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Repairs
        </a>
    </div>

    <?php
    if (isset($_GET['id'])) {
        $repairId = intval($_GET['id']);

        if ($repairId == 0) {
            echo "<div class='alert alert-danger'>Invalid Repair ID.</div>";
            exit;
        }

        // Fetch repair details
        $repairQuery = "
            SELECT r.*, c.name AS customer_name, c.email AS customer_email, c.phone AS customer_phone
            FROM repairs r
            LEFT JOIN customers c ON r.customer_id = c.id
            WHERE r.id = $repairId
        ";
        $repairResult = mysqli_query($conn, $repairQuery);

        if ($repairResult && mysqli_num_rows($repairResult) > 0) {
            $repairData = mysqli_fetch_assoc($repairResult); // FIXED: Changed $repairData to $repairResult

            // Check if repair cost is added
            $costQuery = "
                SELECT invoice_number, repair_cost, advanced_payment 
                FROM repair_orders 
                WHERE repair_id = $repairId
            ";
            $costResult = mysqli_query($conn, $costQuery);

            // Display success message if redirected after saving cost
            if (isset($_GET['success']) && $_GET['success'] == 'cost_added') {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>Repair cost added successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
    ?>

            <!-- Repair Details Card -->
            <div class="card mt-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 text-dark">
                        <i class="fas fa-info-circle me-2 text-primary"></i>Repair Information
                    </h4>
                    <span class="badge bg-light text-dark border">
                        ID: <?= htmlspecialchars($repairData['id']); ?>
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Customer Details
                            </h5>
                            <div class="border rounded p-3 bg-light">
                                <div class="mb-3">
                                    <label class="fw-semibold text-muted">Customer Name</label>
                                    <div class="fw-bold fs-5"><?= htmlspecialchars($repairData['customer_name']); ?></div>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold text-muted">Email</label>
                                    <div class="fw-bold">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        <?= htmlspecialchars($repairData['customer_email']); ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold text-muted">Phone</label>
                                    <div class="fw-bold">
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        <?= htmlspecialchars($repairData['customer_phone']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Repair Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-tools me-2"></i>Repair Details
                            </h5>
                            <div class="border rounded p-3 bg-light">
                                <div class="mb-3">
                                    <label class="fw-semibold text-muted">Repair Date</label>
                                    <div class="fw-bold">
                                        <i class="fas fa-calendar text-muted me-2"></i>
                                        <?= date('d M Y', strtotime($repairData['created_at'])); ?>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold text-muted">Status</label>
                                    <div>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>In Progress
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="fw-semibold text-muted">Description</label>
                                    <div class="fw-bold text-dark mt-1">
                                        <?= htmlspecialchars($repairData['description']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Repair Description Full Width -->
                    <div class="row">
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h6 class="text-muted mb-2">Repair Description</h6>
                                <p class="mb-0"><?= htmlspecialchars($repairData['description']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($costResult && mysqli_num_rows($costResult) > 0) {
                $costData = mysqli_fetch_assoc($costResult);
            ?>
                <!-- Invoice Details Card -->
                <div class="card mt-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h4 class="mb-0 text-dark">
                            <i class="fas fa-file-invoice me-2 text-primary"></i>Invoice Details
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded bg-light">
                                    <h6 class="text-muted mb-2">Invoice Number</h6>
                                    <h4 class="text-primary fw-bold"><?= htmlspecialchars($costData['invoice_number']); ?></h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded bg-light">
                                    <h6 class="text-muted mb-2">Repair Cost</h6>
                                    <h4 class="text-success fw-bold">Rs. <?= number_format($costData['repair_cost'], 2); ?></h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded bg-light">
                                    <h6 class="text-muted mb-2">Advanced Payment</h6>
                                    <h4 class="text-info fw-bold">
                                        <?= isset($costData['advanced_payment']) ? 'Rs. ' . number_format($costData['advanced_payment'], 2) : 'Not Provided'; ?>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <?php
                        $balance = $costData['repair_cost'] - ($costData['advanced_payment'] ?? 0);
                        if ($balance > 0) {
                        ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="text-center p-3 border rounded bg-warning bg-opacity-10">
                                        <h6 class="text-warning mb-1">Outstanding Balance</h6>
                                        <h4 class="text-warning fw-bold">Rs. <?= number_format($balance, 2); ?></h4>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php
            } else {
                // If cost is not added, show the form
            ?>
                <!-- Add Repair Cost Card -->
                <div class="card mt-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h4 class="mb-0 text-dark">
                            <i class="fas fa-calculator me-2 text-primary"></i>Add Repair Cost
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No repair cost added for this repair. Please add the cost and advanced payment below.
                        </div>

                        <form action="add-repair-cost.php" method="POST" id="repairCostForm">
                            <input type="hidden" name="repair_id" value="<?= $repairId ?>">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="repair_cost" class="form-label fw-semibold">Repair Cost (Rs.) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Rs.</span>
                                        <input type="number" step="0.01" name="repair_cost" id="repair_cost" class="form-control" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="advanced_payment" class="form-label fw-semibold">Advanced Payment (Rs.)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Rs.</span>
                                        <input type="number" step="0.01" name="advanced_payment" id="advanced_payment" class="form-control" placeholder="0.00">
                                    </div>
                                    <small class="text-muted">Leave empty if no advanced payment received</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fas fa-save me-1"></i>Save Cost
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php
            }
            ?>

            <!-- Action Buttons -->
            <div class="card mt-4">
                <div class="card-body text-center">
                    <div class="btn-group" role="group">
                        <a href="repairs-view-print.php?id=<?= $repairData['id']; ?>" class="btn btn-info px-4">
                            <i class="fas fa-print me-1"></i>Print
                        </a>
                        <button class="btn btn-primary px-4" onclick="downloadPDF('<?= $repairData['id']; ?>')">
                            <i class="fas fa-download me-1"></i>Download PDF
                        </button>
                        <?php if (!($costResult && mysqli_num_rows($costResult) > 0)) { ?>
                            <button type="button" class="btn btn-warning px-4" onclick="document.getElementById('repairCostForm').scrollIntoView()">
                                <i class="fas fa-plus me-1"></i>Add Cost
                            </button>
                        <?php } ?>
                    </div>
                </div>
            </div>

    <?php
        } else {
            echo '<div class="alert alert-danger mt-4">
                    <i class="fas fa-exclamation-circle me-2"></i>No repair details found.
                  </div>';
        }
    } else {
        echo '<div class="alert alert-danger mt-4">
                <i class="fas fa-exclamation-circle me-2"></i>No Repair ID provided.
              </div>';
    }
    ?>
</div>

<?php include('includes/footer.php'); ?>

<style>
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .btn {
        border-radius: 0.375rem;
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .form-control {
        border-radius: 0.375rem;
        border: 1px solid #e3e6f0;
    }

    .form-control:focus {
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

    .bg-light {
        background-color: #f8f9fc !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation for repair cost
        const repairCostForm = document.getElementById('repairCostForm');
        if (repairCostForm) {
            repairCostForm.addEventListener('submit', function(e) {
                const repairCost = document.getElementById('repair_cost').value;
                const advancedPayment = document.getElementById('advanced_payment').value;

                if (!repairCost || parseFloat(repairCost) <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Invalid Repair Cost',
                        text: 'Please enter a valid repair cost greater than zero.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    document.getElementById('repair_cost').focus();
                    return;
                }

                if (advancedPayment && parseFloat(advancedPayment) > parseFloat(repairCost)) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Invalid Advanced Payment',
                        text: 'Advanced payment cannot be greater than repair cost.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    document.getElementById('advanced_payment').focus();
                    return;
                }

                e.preventDefault();
                Swal.fire({
                    title: 'Save Repair Cost?',
                    html: `Repair Cost: <strong>Rs. ${parseFloat(repairCost).toFixed(2)}</strong><br>
                           ${advancedPayment ? `Advanced Payment: <strong>Rs. ${parseFloat(advancedPayment).toFixed(2)}</strong>` : 'No advanced payment'}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Save Cost',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        repairCostForm.submit();
                    }
                });
            });
        }
    });

    function downloadPDF(repairId) {
        Swal.fire({
            title: 'Download PDF',
            text: 'Preparing your repair details PDF...',
            icon: 'info',
            showConfirmButton: false,
            timer: 2000
        });
        // Add your PDF download logic here
        // window.location.href = 'download-repair-pdf.php?id=' + repairId;
    }
</script>