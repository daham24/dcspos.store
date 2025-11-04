<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-primary text-white py-3">
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>Order Details</h4>
        <div class="d-flex gap-2">
          <a href="orders-view-print.php?track=<?= $_GET['track'] ?>"
            class="btn btn-warning btn-sm"
            data-bs-toggle="tooltip"
            title="Print Invoice">
            <i class="fas fa-print me-1"></i> Print
          </a>
          <a href="orders.php"
            class="btn btn-light btn-sm"
            data-bs-toggle="tooltip"
            title="Back to Orders">
            <i class="fas fa-arrow-left me-1"></i> Back
          </a>
        </div>
      </div>
    </div>
    <div class="card-body">

      <?php alertMessage(); ?>

      <?php
      if (isset($_GET['track'])) {
        if ($_GET['track'] == '') {
      ?>
          <div class="text-center py-5">
            <div class="mb-4">
              <i class="fas fa-search fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted">No Tracking Number Found!</h4>
            <p class="text-muted mb-4">Please provide a valid tracking number to view order details.</p>
            <a href="orders.php" class="btn btn-primary">
              <i class="fas fa-arrow-left me-1"></i> Back to Orders
            </a>
          </div>
          <?php
          return false;
        }

        $trackingNo = validate($_GET['track']);

        $query = "SELECT o.*, c.* FROM orders o, customers c 
                  WHERE c.id = o.customer_id AND tracking_no='$trackingNo' 
                  ORDER BY o.id DESC";

        $orders = mysqli_query($conn, $query);
        if ($orders) {
          if (mysqli_num_rows($orders) > 0) {

            $orderData = mysqli_fetch_assoc($orders);
            $orderId = $orderData['id'];

            // Check if this is an instalment payment
            $instalmentQuery = "SELECT * FROM instalment_payments WHERE tracking_no='$trackingNo' LIMIT 1";
            $instalmentResult = mysqli_query($conn, $instalmentQuery);
            $hasInstalment = $instalmentResult && mysqli_num_rows($instalmentResult) > 0;
            $instalmentData = $hasInstalment ? mysqli_fetch_assoc($instalmentResult) : null;

            // Status badge color
            $statusClass = '';
            switch ($orderData['order_status']) {
              case 'completed':
                $statusClass = 'bg-success';
                break;
              case 'booked':
                $statusClass = 'bg-primary';
                break;
              case 'pending':
                $statusClass = 'bg-warning';
                break;
              case 'cancelled':
                $statusClass = 'bg-danger';
                break;
              default:
                $statusClass = 'bg-secondary';
            }

            // Payment mode badge color
            $paymentClass = '';
            switch ($orderData['payment_mode']) {
              case 'Cash Payment':
                $paymentClass = 'bg-success';
                break;
              case 'Online Payment':
                $paymentClass = 'bg-info';
                break;
              case 'Instalment':
                $paymentClass = 'bg-warning';
                break;
              default:
                $paymentClass = 'bg-secondary';
            }
          ?>
            <!-- Order Summary Card -->
            <div class="card shadow-sm border-0 mb-4">
              <div class="card-header bg-light py-3">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Order Summary</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label text-muted small mb-1">Tracking Number</label>
                      <div class="fw-bold text-primary fs-5"><?= $orderData['tracking_no']; ?></div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label text-muted small mb-1">Invoice Number</label>
                      <div class="fw-bold"><?= $orderData['invoice_no']; ?></div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label text-muted small mb-1">Order Date</label>
                      <div class="fw-bold"><?= date('F d, Y', strtotime($orderData['order_date'])); ?></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label text-muted small mb-1">Order Status</label>
                      <div>
                        <span class="badge <?= $statusClass ?> fs-6"><?= ucfirst($orderData['order_status']); ?></span>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label class="form-label text-muted small mb-1">Payment Mode</label>
                      <div>
                        <span class="badge <?= $paymentClass ?>"><?= $orderData['payment_mode']; ?></span>
                      </div>
                    </div>
                    <?php if (!empty($orderData['reference_number'])): ?>
                      <div class="mb-3">
                        <label class="form-label text-muted small mb-1">Reference Number</label>
                        <div class="fw-bold text-success"><?= $orderData['reference_number']; ?></div>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

            <!-- Customer Details Card -->
            <div class="card shadow-sm border-0 mb-4">
              <div class="card-header bg-light py-3">
                <h5 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Customer Information</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label class="form-label text-muted small mb-1">Full Name</label>
                      <div class="fw-bold"><?= $orderData['name']; ?></div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label class="form-label text-muted small mb-1">Email Address</label>
                      <div class="fw-bold"><?= $orderData['email']; ?></div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mb-3">
                      <label class="form-label text-muted small mb-1">Phone Number</label>
                      <div class="fw-bold"><?= $orderData['phone']; ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Instalment Details Section -->
            <?php if ($hasInstalment && $instalmentData): ?>
              <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light py-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2 text-warning"></i>Instalment Payment Details</h5>
                    <?php if ($instalmentData['payment_status'] == 'pending' && $instalmentData['remaining_amount'] > 0): ?>
                      <button type="button" class="btn btn-success btn-sm" onclick="markFullPaymentCompleted(<?= $instalmentData['id'] ?>, '<?= $trackingNo ?>')">
                        <i class="fas fa-check-circle me-1"></i> Mark Full Payment Completed
                      </button>
                    <?php elseif ($instalmentData['payment_status'] == 'completed'): ?>
                      <span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i> Full Payment Completed</span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="text-center p-3 border rounded">
                        <div class="text-success fw-bold fs-4">Rs. <?= number_format($instalmentData['down_payment'], 2); ?></div>
                        <small class="text-muted">Down Payment</small>
                        <?php if ($instalmentData['payment_status'] == 'completed'): ?>
                          <div class="mt-1"><span class="badge bg-success">Paid</span></div>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="text-center p-3 border rounded">
                        <div class="fw-bold fs-4 <?= $instalmentData['remaining_amount'] > 0 ? 'text-warning' : 'text-success'; ?>">
                          Rs. <?= number_format($instalmentData['remaining_amount'], 2); ?>
                        </div>
                        <small class="text-muted">Remaining Amount</small>
                        <?php if ($instalmentData['remaining_amount'] == 0): ?>
                          <div class="mt-1"><span class="badge bg-success">Paid</span></div>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="text-center p-3 border rounded">
                        <div class="text-info fw-bold fs-4">Rs. <?= number_format($instalmentData['monthly_payment'], 2); ?></div>
                        <small class="text-muted">Monthly Payment</small>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="text-center p-3 border rounded">
                        <div class="fw-bold fs-4"><?= $instalmentData['period_months']; ?></div>
                        <small class="text-muted">Months</small>
                      </div>
                    </div>
                  </div>

                  <div class="row mt-4">
                    <div class="col-md-6">
                      <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                        <span class="fw-semibold">Payment Status:</span>
                        <span class="badge <?= $instalmentData['payment_status'] == 'completed' ? 'bg-success' : 'bg-warning' ?> fs-6">
                          <?= $instalmentData['payment_status'] == 'completed' ? 'Full Payment Completed' : 'Payment in Progress'; ?>
                        </span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                        <span class="fw-semibold">Last Updated:</span>
                        <span class="text-muted"><?= date('M d, Y h:i A', strtotime($instalmentData['created_at'])); ?></span>
                      </div>
                    </div>
                  </div>

                  <?php if ($instalmentData['remaining_amount'] > 0): ?>
                    <div class="mt-4 p-4 bg-warning bg-opacity-10 rounded border">
                      <h6 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>Payment Schedule</h6>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="d-flex justify-content-between mb-2">
                            <span>Down Payment:</span>
                            <span class="fw-bold text-success">Rs. <?= number_format($instalmentData['down_payment'], 2); ?></span>
                          </div>
                          <div class="d-flex justify-content-between mb-2">
                            <span>Remaining Amount:</span>
                            <span class="fw-bold text-warning">Rs. <?= number_format($instalmentData['remaining_amount'], 2); ?></span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="d-flex justify-content-between mb-2">
                            <span>Monthly Payment:</span>
                            <span class="fw-bold">Rs. <?= number_format($instalmentData['monthly_payment'], 2); ?></span>
                          </div>
                          <div class="d-flex justify-content-between">
                            <span>Total Payable:</span>
                            <span class="fw-bold text-primary">Rs. <?= number_format($instalmentData['down_payment'] + $instalmentData['remaining_amount'], 2); ?></span>
                          </div>
                        </div>
                      </div>
                      <?php if ($instalmentData['payment_status'] == 'pending'): ?>
                        <div class="mt-3 p-3 bg-white rounded border">
                          <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-warning me-2 fs-5"></i>
                            <div>
                              <small class="text-muted">
                                Click "Mark Full Payment Completed" when customer pays the remaining
                                <span class="fw-bold text-danger">Rs. <?= number_format($instalmentData['remaining_amount'], 2); ?></span>
                              </small>
                            </div>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>
                  <?php else: ?>
                    <div class="mt-4 p-4 bg-success bg-opacity-10 rounded border border-success">
                      <div class="text-center">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h5 class="text-success mb-2">Payment Completed Successfully!</h5>
                        <p class="text-muted mb-0">All instalment payments have been received and processed.</p>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>

            <!-- Order Items Card -->
            <?php
            $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, oi.discount as orderItemDiscount, 
                              o.*, p.name, p.image, p.discount as productDiscount, p.warranty_period, p.imei_code
                              FROM orders as o 
                              INNER JOIN order_items as oi ON oi.order_id = o.id
                              INNER JOIN products as p ON p.id = oi.product_id
                              WHERE o.tracking_no = '$trackingNo'";

            $orderItemsRes = mysqli_query($conn, $orderItemQuery);
            if ($orderItemsRes) {
              if (mysqli_num_rows($orderItemsRes) > 0) {
            ?>
                <div class="card shadow-sm border-0">
                  <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-shopping-basket me-2 text-primary"></i>Order Items</h5>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <table class="table table-hover mb-0">
                        <thead class="table-light">
                          <tr>
                            <th width="30%">Product</th>
                            <th width="12%" class="text-center">Price (Rs.)</th>
                            <th width="12%" class="text-center">Discount (Rs.)</th>
                            <th width="12%" class="text-center">Warranty</th>
                            <th width="12%" class="text-center">IMEI No.</th>
                            <th width="10%" class="text-center">Qty</th>
                            <th width="12%" class="text-center">Total (Rs.)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $grandTotal = 0;
                          foreach ($orderItemsRes as $orderItemRow) :
                            $discount = $orderItemRow['orderItemDiscount'] ?: $orderItemRow['productDiscount'];
                            $totalPrice = ($orderItemRow['orderItemPrice'] - $discount) * $orderItemRow['orderItemQuantity'];
                            $grandTotal += $totalPrice;
                          ?>
                            <tr>
                              <td>
                                <div class="d-flex align-items-center">
                                  <img src="<?= $orderItemRow['image'] != '' ? '../' . $orderItemRow['image'] : '../assets/images/no-img.jpg'; ?>"
                                    class="rounded me-3"
                                    style="width: 50px; height: 50px; object-fit: cover;"
                                    alt="<?= $orderItemRow['name']; ?>" />
                                  <div>
                                    <div class="fw-semibold"><?= $orderItemRow['name']; ?></div>
                                  </div>
                                </div>
                              </td>
                              <td class="text-center fw-bold"><?= number_format($orderItemRow['orderItemPrice'], 0); ?></td>
                              <td class="text-center text-danger fw-bold">-<?= number_format($discount, 0); ?></td>
                              <td class="text-center">
                                <span class="badge bg-light text-dark border"><?= $orderItemRow['warranty_period'] ?? 'N/A'; ?></span>
                              </td>
                              <td class="text-center">
                                <span class="badge bg-light text-dark border"><?= $orderItemRow['imei_code'] ?? 'N/A'; ?></span>
                              </td>
                              <td class="text-center fw-bold"><?= $orderItemRow['orderItemQuantity']; ?></td>
                              <td class="text-center fw-bold text-success"><?= number_format($totalPrice, 0); ?></td>
                            </tr>
                          <?php endforeach; ?>
                          <tr class="table-active">
                            <td colspan="6" class="text-end fw-bold fs-5">Grand Total:</td>
                            <td class="text-center fw-bold fs-5 text-success">Rs. <?= number_format($grandTotal, 0); ?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
            <?php
              } else {
                echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>No order items found!</div>';
              }
            } else {
              echo '<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>Error fetching order items!</div>';
            }
            ?>

        <?php
          } else {
            echo '<div class="alert alert-warning text-center py-4">
                    <i class="fas fa-search fa-2x mb-3 text-muted"></i>
                    <h4 class="text-muted">No Order Found</h4>
                    <p class="text-muted">The tracking number provided does not match any orders.</p>
                  </div>';
          }
        } else {
          echo '<div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i>Database query error!</div>';
        }
      } else {
        ?>
        <div class="text-center py-5">
          <div class="mb-4">
            <i class="fas fa-receipt fa-4x text-muted"></i>
          </div>
          <h4 class="text-muted">No Tracking Number Provided</h4>
          <p class="text-muted mb-4">Please select an order to view its details.</p>
          <a href="orders.php" class="btn btn-primary">
            <i class="fas fa-arrow-left me-1"></i> Back to Orders
          </a>
        </div>
      <?php
      }
      ?>

    </div>
  </div>
</div>

<script>
  // Initialize Bootstrap tooltips
  document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });
  });

  function markFullPaymentCompleted(instalmentId, trackingNo) {
    Swal.fire({
      title: 'Mark Full Payment as Completed?',
      text: 'This will mark the entire remaining amount as paid and close the instalment. This action cannot be undone.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Mark as Fully Paid',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#198754'
    }).then((result) => {
      if (result.isConfirmed) {
        // Show loading state
        Swal.fire({
          title: 'Updating...',
          text: 'Please wait',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        // Make AJAX request
        $.ajax({
          type: "POST",
          url: "update-payment-status.php",
          data: {
            instalment_id: instalmentId,
            tracking_no: trackingNo,
            mark_full_paid: true
          },
          success: function(response) {
            try {
              var res = JSON.parse(response);
              if (res.status == 200) {
                Swal.fire({
                  title: 'Success!',
                  text: res.message,
                  icon: 'success',
                  confirmButtonText: 'OK'
                }).then(() => {
                  // Reload the page to show updated status
                  location.reload();
                });
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: res.message,
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            } catch (e) {
              Swal.fire({
                title: 'Error!',
                text: 'Invalid response from server',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          },
          error: function() {
            Swal.fire({
              title: 'Error!',
              text: 'Failed to update payment status',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        });
      }
    });
  }
</script>

<?php include('includes/footer.php'); ?>