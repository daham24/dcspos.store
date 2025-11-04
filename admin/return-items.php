<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-undo fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Return Management</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Return Items</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Return Items Card -->
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-search text-primary me-2"></i>Find Customer Orders
            </h5>
        </div>
        <div class="card-body">
            <!-- Customer Search Form -->
            <form method="POST" action="return-items.php" id="customerSearchForm">
                <div class="row align-items-end">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">
                            Customer Phone Number <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-phone text-muted"></i>
                            </span>
                            <input type="text" class="form-control" name="phone" id="phone"
                                placeholder="Enter customer phone number"
                                value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>"
                                required>
                        </div>
                        <div class="form-text">Enter the customer's registered phone number to fetch their orders</div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" name="fetchOrders" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>Fetch Orders
                        </button>
                    </div>
                </div>
            </form>

            <?php
            // Check if the phone number is entered
            if (isset($_POST['fetchOrders'])) {
                $phone = trim($_POST['phone']);

                if (empty($phone)) {
                    echo '<div class="alert alert-warning mt-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Please enter a phone number to fetch orders.
                          </div>';
                } else {
                    // Fetch orders for the customer
                    $query = "SELECT o.id as order_id, o.tracking_no, o.order_date, oi.product_id, oi.quantity, 
                                     p.name, p.price, p.barcode, c.name as customer_name
                              FROM orders o 
                              JOIN order_items oi ON oi.order_id = o.id 
                              JOIN products p ON oi.product_id = p.id 
                              JOIN customers c ON c.id = o.customer_id 
                              WHERE c.phone = '$phone' AND o.order_status = 'completed'";

                    $result = mysqli_query($conn, $query);
                    if ($result && mysqli_num_rows($result) > 0) {
                        $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
                        $_SESSION['orders'] = $orders;
                        $customerName = $orders[0]['customer_name'] ?? 'Customer';
            ?>
                        <!-- Customer Info Card -->
                        <div class="card mt-4 border-0 bg-light">
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-1 text-primary">
                                            <i class="fas fa-user me-2"></i><?= htmlspecialchars($customerName) ?>
                                        </h6>
                                        <small class="text-muted">
                                            Phone: <?= htmlspecialchars($phone) ?> |
                                            Orders Found: <?= count($orders) ?>
                                        </small>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="badge bg-primary">Active Customer</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Return Items Form -->
                        <form method="POST" action="code.php" id="returnForm">
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3">
                                    <i class="fas fa-shopping-cart me-2 text-primary"></i>Select Items to Return
                                </h6>

                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50px" class="text-center">
                                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                                </th>
                                                <th width="200px">Product Information</th>
                                                <th width="120px">Order Details</th>
                                                <th width="100px">Price</th>
                                                <th width="100px">Quantity</th>
                                                <th width="120px">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $totalValue = 0;
                                            foreach ($orders as $index => $row):
                                                $itemTotal = $row['price'] * $row['quantity'];
                                                $totalValue += $itemTotal;
                                            ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="items[]"
                                                            value="<?= $row['order_id'] . '-' . $row['product_id'] ?>"
                                                            class="form-check-input return-item-checkbox"
                                                            data-price="<?= $row['price'] ?>"
                                                            data-quantity="<?= $row['quantity'] ?>">
                                                    </td>
                                                    <td>
                                                        <div class="product-info">
                                                            <strong class="d-block"><?= htmlspecialchars($row['name']) ?></strong>
                                                            <?php if (!empty($row['barcode'])): ?>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-barcode me-1"></i><?= $row['barcode'] ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted d-block">
                                                            <strong>Order:</strong> <?= $row['tracking_no'] ?>
                                                        </small>
                                                        <small class="text-muted">
                                                            <?= date('M j, Y', strtotime($row['order_date'])) ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <strong class="text-success">Rs. <?= number_format($row['price'], 2) ?></strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-dark"><?= $row['quantity'] ?></span>
                                                    </td>
                                                    <td>
                                                        <strong class="text-primary">Rs. <?= number_format($itemTotal, 2) ?></strong>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="5" class="text-end fw-bold">Total Order Value:</td>
                                                <td class="fw-bold text-success">Rs. <?= number_format($totalValue, 2) ?></td>
                                            </tr>
                                            <tr id="returnTotalRow" style="display: none;">
                                                <td colspan="5" class="text-end fw-bold text-danger">Selected for Return:</td>
                                                <td class="fw-bold text-danger" id="returnTotal">Rs. 0.00</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Return Details -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title mb-3">
                                                    <i class="fas fa-clipboard-list text-primary me-2"></i>Return Details
                                                </h6>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">
                                                        Reason for Return <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light align-items-start pt-2">
                                                            <i class="fas fa-comment-dots text-muted"></i>
                                                        </span>
                                                        <textarea name="reason" id="reason" class="form-control" rows="4"
                                                            placeholder="Please provide detailed reason for the return..."
                                                            required maxlength="500"></textarea>
                                                    </div>
                                                    <div class="form-text">Be specific about the issue to help us improve our service</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-4">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Select items to process return and provide reason
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class="fas fa-redo me-1"></i>Reset
                                        </button>
                                        <button type="submit" name="returnItems" class="btn btn-danger" id="returnButton" disabled>
                                            <i class="fas fa-undo me-1"></i>Process Return
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
            <?php
                    } else {
                        echo '<div class="alert alert-warning mt-4">
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Orders Found</h5>
                                    <p class="text-muted mb-0">No completed orders found for this phone number.</p>
                                </div>
                              </div>';
                    }
                }
            } else {
                // Initial state message
                echo '<div class="text-center py-5">
                        <i class="fas fa-undo fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Ready to Process Returns</h5>
                        <p class="text-muted mb-0">Enter a customer phone number above to find their orders and process returns.</p>
                      </div>';
            }
            ?>
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.return-item-checkbox');
        const returnButton = document.getElementById('returnButton');
        const returnTotalRow = document.getElementById('returnTotalRow');
        const returnTotalElement = document.getElementById('returnTotal');

        // Select all checkboxes
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateReturnButton();
                calculateReturnTotal();
            });
        }

        // Update return button state and calculate total
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateReturnButton();
                calculateReturnTotal();

                // Update select all checkbox state
                if (selectAllCheckbox) {
                    const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                }
            });
        });

        function updateReturnButton() {
            const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
            returnButton.disabled = !anyChecked;
        }

        function calculateReturnTotal() {
            let total = 0;
            itemCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const price = parseFloat(checkbox.getAttribute('data-price'));
                    const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                    total += price * quantity;
                }
            });

            if (total > 0) {
                returnTotalRow.style.display = '';
                returnTotalElement.textContent = 'Rs. ' + total.toFixed(2);
            } else {
                returnTotalRow.style.display = 'none';
            }
        }

        // Form validation
        const returnForm = document.getElementById('returnForm');
        if (returnForm) {
            returnForm.addEventListener('submit', function(e) {
                const checkedItems = document.querySelectorAll('.return-item-checkbox:checked');
                const reason = document.getElementById('reason').value.trim();

                if (checkedItems.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'No Items Selected',
                        text: 'Please select at least one item to return.',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                if (reason.length < 10) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Reason Required',
                        text: 'Please provide a detailed reason for the return (at least 10 characters).',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6'
                    });
                    document.getElementById('reason').focus();
                    return;
                }
            });
        }
    });
</script>