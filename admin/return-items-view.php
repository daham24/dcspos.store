<?php
include('includes/header.php');
include('../config/dbCon.php');
?>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-undo fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Returned Items</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Returned Items</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Print Report
            </button>
            <a href="return-items.php" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>New Return
            </a>
        </div>
    </div>

    <!-- Returned Items Card -->
    <div class="card mt-4 shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-primary me-2"></i>Return History
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <?php
                    $totalReturnsQuery = "SELECT COUNT(*) as total FROM returns";
                    $totalReturnsResult = mysqli_query($conn, $totalReturnsQuery);
                    $totalReturns = mysqli_fetch_assoc($totalReturnsResult)['total'];
                    ?>
                    <span class="badge bg-primary fs-6"><?= $totalReturns ?> Returns</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php
            $query = "SELECT r.*, oi.product_id, oi.quantity AS order_quantity, p.name AS product_name, 
                             p.price AS product_price, p.barcode, r.return_date, r.reason, r.status, 
                             o.tracking_no, c.name AS customer_name, c.phone AS customer_phone
                      FROM returns r 
                      JOIN order_items oi ON r.order_item_id = oi.id 
                      JOIN products p ON r.product_id = p.id
                      JOIN orders o ON o.id = oi.order_id
                      JOIN customers c ON o.customer_id = c.id
                      ORDER BY r.return_date DESC";

            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
            ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="120px">Tracking Info</th>
                                <th width="150px">Product Details</th>
                                <th width="120px">Customer</th>
                                <th width="100px">Pricing</th>
                                <th width="80px">Qty</th>
                                <th width="120px">Return Date</th>
                                <th width="150px">Reason</th>
                                <th width="100px">Status</th>
                                <th width="100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalReturnValue = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $returnValue = $row['product_price'] * $row['quantity'];
                                $totalReturnValue += $returnValue;
                            ?>
                                <tr>
                                    <td>
                                        <div class="tracking-info">
                                            <code class="d-block"><?= $row['tracking_no'] ?></code>
                                            <small class="text-muted">
                                                <?= date('M j, Y', strtotime($row['return_date'])) ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="product-info">
                                            <strong class="d-block"><?= htmlspecialchars($row['product_name']) ?></strong>
                                            <?php if (!empty($row['barcode'])): ?>
                                                <small class="text-muted">
                                                    <i class="fas fa-barcode me-1"></i><?= $row['barcode'] ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <strong class="d-block"><?= htmlspecialchars($row['customer_name']) ?></strong>
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i><?= $row['customer_phone'] ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="pricing-info">
                                            <small class="text-muted d-block">Price:</small>
                                            <strong class="text-success">Rs. <?= number_format($row['product_price'], 2) ?></strong>
                                            <small class="text-muted d-block">Total:</small>
                                            <strong class="text-primary">Rs. <?= number_format($returnValue, 2) ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-dark"><?= $row['quantity'] ?></span>
                                    </td>
                                    <td>
                                        <div class="date-info">
                                            <small class="text-muted d-block">
                                                <?= date('M j, Y', strtotime($row['return_date'])) ?>
                                            </small>
                                            <small class="text-muted">
                                                <?= date('g:i A', strtotime($row['return_date'])) ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="reason-info">
                                            <small class="text-truncate d-block" style="max-width: 150px;"
                                                title="<?= htmlspecialchars($row['reason']) ?>">
                                                <?= htmlspecialchars($row['reason']) ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        switch (strtolower($row['status'])) {
                                            case 'completed':
                                                $statusClass = 'bg-success';
                                                break;
                                            case 'processing':
                                                $statusClass = 'bg-warning text-dark';
                                                break;
                                            case 'pending':
                                                $statusClass = 'bg-info';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'bg-danger';
                                                break;
                                            default:
                                                $statusClass = 'bg-secondary';
                                        }
                                        ?>
                                        <span class="badge <?= $statusClass ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="return-edit.php?id=<?= $row['id'] ?>"
                                                class="btn btn-outline-primary"
                                                title="Edit Return">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="return-delete.php?id=<?= $row['id'] ?>"
                                                class="btn btn-outline-danger delete-btn"
                                                data-delete-url="return-delete.php?id=<?= $row['id'] ?>"
                                                data-product-name="<?= htmlspecialchars($row['product_name']) ?>"
                                                title="Delete Return">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Return Value:</td>
                                <td class="fw-bold text-danger">Rs. <?= number_format($totalReturnValue, 2) ?></td>
                                <td colspan="5"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Return Statistics -->
                <div class="row mt-4">
                    <?php
                    // Count returns by status
                    $pendingQuery = "SELECT COUNT(*) as count FROM returns WHERE status = 'pending'";
                    $pendingResult = mysqli_query($conn, $pendingQuery);
                    $pendingCount = mysqli_fetch_assoc($pendingResult)['count'];

                    $completedQuery = "SELECT COUNT(*) as count FROM returns WHERE status = 'completed'";
                    $completedResult = mysqli_query($conn, $completedQuery);
                    $completedCount = mysqli_fetch_assoc($completedResult)['count'];

                    $processingQuery = "SELECT COUNT(*) as count FROM returns WHERE status = 'processing'";
                    $processingResult = mysqli_query($conn, $processingQuery);
                    $processingCount = mysqli_fetch_assoc($processingResult)['count'];

                    // Today's returns
                    $todayQuery = "SELECT COUNT(*) as count FROM returns WHERE DATE(return_date) = CURDATE()";
                    $todayResult = mysqli_query($conn, $todayQuery);
                    $todayCount = mysqli_fetch_assoc($todayResult)['count'];
                    ?>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0"><?= $totalReturns ?></h4>
                                        <small>Total Returns</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-undo fa-2x opacity-50"></i>
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
                                        <h4 class="mb-0"><?= $pendingCount ?></h4>
                                        <small>Pending</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x opacity-50"></i>
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
                                        <h4 class="mb-0"><?= $completedCount ?></h4>
                                        <small>Completed</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
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
                                        <h4 class="mb-0"><?= $todayCount ?></h4>
                                        <small>Today's Returns</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar-day fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php } else { ?>
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="empty-state-icon mb-3">
                        <i class="fas fa-undo fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No Returned Items Found</h4>
                    <p class="text-muted mb-4">No return records have been created yet.</p>
                    <a href="return-items.php" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>Process First Return
                    </a>
                </div>
            <?php } ?>
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

    .empty-state-icon {
        opacity: 0.5;
    }

    .tracking-info,
    .product-info,
    .customer-info,
    .pricing-info,
    .date-info,
    .reason-info {
        font-size: 0.875rem;
    }

    .badge {
        font-size: 0.75rem;
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
                const productName = this.getAttribute('data-product-name');

                Swal.fire({
                    title: 'Delete Return Record?',
                    html: `You are about to delete the return record for <strong>"${productName}"</strong>. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    backdrop: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });

        // Add hover effects to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
                this.style.transition = 'background-color 0.2s ease-in-out';
            });
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });
</script>