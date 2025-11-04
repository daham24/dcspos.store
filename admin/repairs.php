<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-tools fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Repair Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Repairs</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="repairs-create.php" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i>Add Repair
        </a>
    </div>

    <!-- Repairs Card -->
    <div class="card mt-4 shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-primary me-2"></i>Repair Items
                    </h5>
                </div>
                <div class="col-md-6">
                    <form class="d-flex gap-2" method="GET" action="">
                        <div class="input-group">
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Search by item name..."
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                        <select name="status" class="form-select" style="min-width: 140px;">
                            <option value="">All Status</option>
                            <option value="0" <?= isset($_GET['status']) && $_GET['status'] === '0' ? 'selected' : '' ?>>Pending</option>
                            <option value="1" <?= isset($_GET['status']) && $_GET['status'] === '1' ? 'selected' : '' ?>>Completed</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <?php if (isset($_GET['search']) || isset($_GET['status'])): ?>
                            <a href="repairs.php" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php
            // Search and Filter Logic
            $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
            $statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';

            $query = "
                SELECT repairs.*, customers.name AS customer_name, customers.email, customers.phone 
                FROM repairs 
                INNER JOIN customers ON repairs.customer_id = customers.id
                WHERE 1
            ";

            if ($searchQuery !== '') {
                $query .= " AND repairs.item_name LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%'";
            }

            if ($statusFilter !== '') {
                $query .= " AND repairs.status = " . intval($statusFilter);
            }

            $query .= " ORDER BY repairs.created_at DESC";

            $repairs = mysqli_query($conn, $query);

            if ($repairs && mysqli_num_rows($repairs) > 0) {
            ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="150px">Device Info</th>
                                <th width="150px">Customer</th>
                                <th width="120px">Conditions</th>
                                <th width="120px">Items Received</th>
                                <th width="100px">Status</th>
                                <th width="120px">Date</th>
                                <th width="80px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($repair = mysqli_fetch_assoc($repairs)) { ?>
                                <tr>
                                    <td>
                                        <div class="device-info">
                                            <strong class="d-block"><?= htmlspecialchars($repair['item_name']); ?></strong>
                                            <small class="text-muted">
                                                ID: <?= $repair['id']; ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="customer-info">
                                            <strong class="d-block"><?= htmlspecialchars($repair['customer_name']); ?></strong>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-phone me-1"></i><?= $repair['phone']; ?>
                                            </small>
                                            <?php if (!empty($repair['email'])): ?>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-envelope me-1"></i><?= $repair['email']; ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="conditions-info">
                                            <?php
                                            $conditions = !empty($repair['physical_condition'])
                                                ? explode(', ', $repair['physical_condition'])
                                                : [];
                                            if (!empty($conditions)):
                                                foreach (array_slice($conditions, 0, 2) as $condition):
                                            ?>
                                                    <span class="badge bg-light text-dark mb-1 d-block"><?= $condition ?></span>
                                                <?php
                                                endforeach;
                                                if (count($conditions) > 2):
                                                ?>
                                                    <small class="text-primary">+<?= count($conditions) - 2 ?> more</small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="items-info">
                                            <?php
                                            $items = !empty($repair['received_items'])
                                                ? explode(', ', $repair['received_items'])
                                                : [];
                                            if (!empty($items)):
                                                foreach (array_slice($items, 0, 2) as $item):
                                            ?>
                                                    <span class="badge bg-info text-white mb-1 d-block"><?= $item ?></span>
                                                <?php
                                                endforeach;
                                                if (count($items) > 2):
                                                ?>
                                                    <small class="text-primary">+<?= count($items) - 2 ?> more</small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($repair['status'] == 1): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Completed
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted d-block">
                                            <?= date('M j, Y', strtotime($repair['created_at'])) ?>
                                        </small>
                                        <small class="text-muted">
                                            <?= date('g:i A', strtotime($repair['created_at'])) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="repairs-view.php?id=<?= $repair['id']; ?>"
                                                class="btn btn-outline-primary"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                                <a href="repairs-edit.php?id=<?= $repair['id']; ?>"
                                                    class="btn btn-outline-warning"
                                                    title="Edit Repair">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="#"
                                                    class="btn btn-outline-danger delete-btn"
                                                    data-delete-url="repairs-delete.php?id=<?= $repair['id']; ?>"
                                                    data-repair-item="<?= htmlspecialchars($repair['item_name']); ?>"
                                                    title="Delete Repair">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Repair Statistics -->
                <div class="row mt-4">
                    <?php
                    // Count pending repairs
                    $pendingQuery = "SELECT COUNT(*) as count FROM repairs WHERE status = 0";
                    $pendingResult = mysqli_query($conn, $pendingQuery);
                    $pendingCount = mysqli_fetch_assoc($pendingResult)['count'];

                    // Count completed repairs
                    $completedQuery = "SELECT COUNT(*) as count FROM repairs WHERE status = 1";
                    $completedResult = mysqli_query($conn, $completedQuery);
                    $completedCount = mysqli_fetch_assoc($completedResult)['count'];

                    // Count today's repairs
                    $todayQuery = "SELECT COUNT(*) as count FROM repairs WHERE DATE(created_at) = CURDATE()";
                    $todayResult = mysqli_query($conn, $todayQuery);
                    $todayCount = mysqli_fetch_assoc($todayResult)['count'];
                    ?>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0"><?= mysqli_num_rows($repairs) ?></h4>
                                        <small>Total Repairs</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-tools fa-2x opacity-50"></i>
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
                                        <small>Pending Repairs</small>
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
                                        <small>Today's Repairs</small>
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
                        <i class="fas fa-tools fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No Repair Items Found</h4>
                    <p class="text-muted mb-4">
                        <?php if ($searchQuery || $statusFilter): ?>
                            No repairs match your search criteria. Try adjusting your filters.
                        <?php else: ?>
                            Get started by adding your first repair item to the system.
                        <?php endif; ?>
                    </p>
                    <?php if (!$searchQuery && !$statusFilter): ?>
                        <a href="repairs-create.php" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i>Add First Repair
                        </a>
                    <?php else: ?>
                        <a href="repairs.php" class="btn btn-outline-primary">
                            <i class="fas fa-times me-1"></i>Clear Filters
                        </a>
                    <?php endif; ?>
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

    .device-info,
    .customer-info,
    .conditions-info,
    .items-info {
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
                const repairItem = this.getAttribute('data-repair-item');

                Swal.fire({
                    title: 'Delete Repair?',
                    html: `You are about to delete the repair for <strong>"${repairItem}"</strong>. This action cannot be undone.`,
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