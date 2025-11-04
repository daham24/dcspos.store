<?php
include('includes/header.php');

// Get customer ID from URL
$customerId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($customerId == 0) {
    echo '<div class="alert alert-danger mt-4">Invalid Customer ID</div>';
    include('includes/footer.php');
    exit;
}

// Fetch customer details
$customerQuery = "SELECT * FROM customers WHERE id = '$customerId'";
$customerResult = mysqli_query($conn, $customerQuery);
$customer = mysqli_fetch_assoc($customerResult);

// Fetch customer orders with more details
$query = "SELECT o.id AS order_id, o.tracking_no, o.total_amount, o.order_date, o.order_status, 
                 o.payment_mode, COUNT(oi.id) as item_count
          FROM orders o 
          LEFT JOIN order_items oi ON o.id = oi.order_id
          WHERE o.customer_id = '$customerId'
          GROUP BY o.id
          ORDER BY o.order_date DESC";

$orders = mysqli_query($conn, $query);

?>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-shopping-bag fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Customer Orders</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="customers.php" class="text-decoration-none">Customers</a></li>
                        <li class="breadcrumb-item active">Customer Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="customers.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Customers
        </a>
    </div>

    <!-- Customer Information Card -->
    <?php if ($customer): ?>
        <div class="card mt-4 shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-circle text-primary me-2"></i>Customer Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <div class="customer-avatar bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-user fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-1"><?= htmlspecialchars($customer['name']) ?></h6>
                                <small class="text-muted">Customer ID: <?= $customer['id'] ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="customer-contact">
                            <small class="text-muted d-block">Phone</small>
                            <strong><?= htmlspecialchars($customer['phone']) ?></strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="customer-contact">
                            <small class="text-muted d-block">Email</small>
                            <strong><?= !empty($customer['email']) ? htmlspecialchars($customer['email']) : 'N/A' ?></strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="customer-stats">
                            <small class="text-muted d-block">Total Orders</small>
                            <strong class="text-primary"><?= mysqli_num_rows($orders) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Orders Card -->
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt text-primary me-2"></i>Order History
                        <?php if (mysqli_num_rows($orders) > 0): ?>
                            <span class="badge bg-primary ms-2"><?= mysqli_num_rows($orders) ?> orders</span>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <?php if (mysqli_num_rows($orders) > 0): ?>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Print Report
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (mysqli_num_rows($orders) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="120px">Tracking No.</th>
                                <th width="100px">Items</th>
                                <th width="120px">Total Amount</th>
                                <th width="120px">Order Date</th>
                                <th width="100px">Payment Mode</th>
                                <th width="120px">Status</th>
                                <th width="80px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalSpent = 0;
                            while ($order = mysqli_fetch_assoc($orders)) {
                                $totalSpent += $order['total_amount'];
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-barcode text-muted me-2"></i>
                                            <code><?= $order['tracking_no'] ?></code>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= $order['item_count'] ?> item<?= $order['item_count'] != 1 ? 's' : '' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">Rs. <?= number_format($order['total_amount'], 2) ?></strong>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span><?= date('d M, Y', strtotime($order['order_date'])) ?></span>
                                            <small class="text-muted"><?= date('h:i A', strtotime($order['order_date'])) ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= ucfirst($order['payment_mode']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        switch (strtolower($order['order_status'])) {
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
                                            <?= ucfirst($order['order_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="order-details.php?id=<?= $order['order_id'] ?>" class="btn btn-outline-primary btn-sm" title="View Order Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Total Spent:</td>
                                <td class="fw-bold text-success">Rs. <?= number_format($totalSpent, 2) ?></td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Order Statistics -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0"><?= mysqli_num_rows($orders) ?></h4>
                                        <small>Total Orders</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-shopping-bag fa-2x opacity-50"></i>
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
                                        <h4 class="mb-0">Rs. <?= number_format($totalSpent, 2) ?></h4>
                                        <small>Total Value</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
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
                                        <h4 class="mb-0">Rs. <?= number_format($totalSpent / max(mysqli_num_rows($orders), 1), 2) ?></h4>
                                        <small>Average Order</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calculator fa-2x opacity-50"></i>
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
                                        <h4 class="mb-0">
                                            <?php
                                            // Count recent orders (last 30 days)
                                            $recentQuery = "SELECT COUNT(*) as count FROM orders WHERE customer_id = '$customerId' AND order_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                                            $recentResult = mysqli_query($conn, $recentQuery);
                                            $recentCount = mysqli_fetch_assoc($recentResult)['count'];
                                            echo $recentCount;
                                            ?>
                                        </h4>
                                        <small>Last 30 Days</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x opacity-50"></i>
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
                        <i class="fas fa-shopping-bag fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No Orders Found</h4>
                    <p class="text-muted mb-4">This customer hasn't placed any orders yet.</p>
                    <a href="customers.php" class="btn btn-outline-primary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Customers
                    </a>
                    <a href="orders-create.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create New Order
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
    .customer-avatar {
        font-size: 1.5rem;
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

    .empty-state-icon {
        opacity: 0.5;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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