<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
  <div class="card mt-4 shadow-sm">
    <div class="card-header bg-primary text-white">
      <div class="row align-items-center">
        <div class="col-md-4">
          <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Orders Management</h4>
        </div>
        <div class="col-md-8">
          <form action="" method="GET">
            <div class="row g-2">
              <div class="col-md-3">
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-search text-muted"></i>
                  </span>
                  <input type="text"
                    name="search_tracking"
                    class="form-control border-start-0"
                    placeholder="Tracking No."
                    value="<?= isset($_GET['search_tracking']) ? htmlspecialchars($_GET['search_tracking']) : ''; ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-calendar text-muted"></i>
                  </span>
                  <input type="date"
                    name="date"
                    class="form-control border-start-0"
                    value="<?= isset($_GET['date']) ? $_GET['date'] : ''; ?>" />
                </div>
              </div>
              <div class="col-md-3">
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-credit-card text-muted"></i>
                  </span>
                  <select name="payment_status" class="form-select border-start-0">
                    <option value="">All Payments</option>
                    <option value="Cash Payment" <?= isset($_GET['payment_status']) && $_GET['payment_status'] == 'Cash Payment' ? 'selected' : ''; ?>>Cash Payment</option>
                    <option value="Online Payment" <?= isset($_GET['payment_status']) && $_GET['payment_status'] == 'Online Payment' ? 'selected' : ''; ?>>Online Payment</option>
                    <option value="Instalment" <?= isset($_GET['payment_status']) && $_GET['payment_status'] == 'Instalment' ? 'selected' : ''; ?>>Instalment</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-success flex-fill">
                    <i class="fas fa-filter me-1"></i> Filter
                  </button>
                  <a href="orders.php" class="btn btn-outline-danger">
                    <i class="fas fa-times me-1"></i> Reset
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="card-body">

      <?php
      // Get filter values
      $searchTracking = isset($_GET['search_tracking']) ? validate($_GET['search_tracking']) : '';
      $orderDate = isset($_GET['date']) ? validate($_GET['date']) : '';
      $paymentStatus = isset($_GET['payment_status']) ? validate($_GET['payment_status']) : '';

      // Build the query with conditions
      $query = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id";

      if ($searchTracking != '') {
        $query .= " AND o.tracking_no LIKE '%$searchTracking%'";
      }
      if ($orderDate != '') {
        $query .= " AND o.order_date = '$orderDate'";
      }
      if ($paymentStatus != '') {
        $query .= " AND o.payment_mode = '$paymentStatus'";
      }

      $query .= " ORDER BY o.id DESC";  // Sort the orders by ID descending

      // Execute the query
      $orders = mysqli_query($conn, $query);
      if ($orders) {
        if (mysqli_num_rows($orders) > 0) {
      ?>
          <div class="table-responsive">
            <table class="table table-hover table-striped table-bordered">
              <thead class="table-dark">
                <tr>
                  <th width="12%">Tracking No.</th>
                  <th width="15%">Customer Name</th>
                  <th width="12%">Phone</th>
                  <th width="12%">Order Date</th>
                  <th width="12%">Status</th>
                  <th width="15%">Payment Mode</th>
                  <th width="22%">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $orderItem) :
                  // Status badge color
                  $statusClass = '';
                  switch ($orderItem['order_status']) {
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
                  switch ($orderItem['payment_mode']) {
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
                  <tr>
                    <td>
                      <div class="fw-bold text-primary"><?= $orderItem['tracking_no']; ?></div>
                      <small class="text-muted"><?= $orderItem['invoice_no']; ?></small>
                    </td>
                    <td>
                      <div class="fw-semibold"><?= $orderItem['name']; ?></div>
                    </td>
                    <td>
                      <span class="text-nowrap"><?= $orderItem['phone']; ?></span>
                    </td>
                    <td>
                      <span class="text-nowrap"><?= date('d M, Y', strtotime($orderItem['order_date'])); ?></span>
                    </td>
                    <td>
                      <span class="badge <?= $statusClass ?>"><?= ucfirst($orderItem['order_status']); ?></span>
                    </td>
                    <td>
                      <span class="badge <?= $paymentClass ?>"><?= $orderItem['payment_mode']; ?></span>
                    </td>
                    <td>
                      <div class="d-flex gap-1 flex-wrap">
                        <a href="orders-view.php?track=<?= $orderItem['tracking_no']; ?>"
                          class="btn btn-outline-info btn-sm"
                          data-bs-toggle="tooltip"
                          title="View Order Details">
                          <i class="fas fa-eye me-1"></i> View
                        </a>
                        <a href="orders-view-print.php?track=<?= $orderItem['tracking_no']; ?>"
                          class="btn btn-outline-primary btn-sm"
                          data-bs-toggle="tooltip"
                          title="Print Invoice">
                          <i class="fas fa-print me-1"></i> Print
                        </a>
                        <?php if ($orderItem['payment_mode'] == 'Instalment'): ?>
                          <a href="instalment-payments.php?track=<?= $orderItem['tracking_no']; ?>"
                            class="btn btn-outline-warning btn-sm disabled"
                            data-bs-toggle="tooltip"
                            title="Instalment Details">
                            <i class="fas fa-credit-card me-1"></i> Instalment
                          </a>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        <?php
        } else {
        ?>
          <div class="text-center py-5">
            <div class="mb-4">
              <i class="fas fa-shopping-cart fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted">No Orders Found</h4>
            <p class="text-muted mb-4">There are no orders matching your criteria.</p>
            <a href="orders.php" class="btn btn-primary">
              <i class="fas fa-refresh me-1"></i> Reset Filters
            </a>
          </div>
        <?php
        }
      } else {
        ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <strong>Error:</strong> Something went wrong while fetching orders.
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
</script>

<?php include('includes/footer.php'); ?>