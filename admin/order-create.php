<?php include('includes/header.php'); ?>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addCustomerModalLabel">
          <i class="fas fa-user-plus me-2"></i>Add New Customer
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="c_name" placeholder="Enter full name" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="c_phone" placeholder="Enter phone number" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Email Address</label>
          <input type="email" class="form-control" id="c_email" placeholder="Enter email (optional)" />
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Cancel
        </button>
        <button type="button" class="btn btn-primary saveCustomer">
          <i class="fas fa-save me-1"></i>Save Customer
        </button>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid px-4">

  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-shopping-cart fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Create New Order</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="orders.php" class="text-decoration-none">Orders</a></li>
            <li class="breadcrumb-item active">Create Order</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="orders.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Orders
    </a>
  </div>

  <!-- Add Products Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <h5 class="card-title mb-0">
        <i class="fas fa-plus-circle text-primary me-2"></i>Add Products to Order
      </h5>
    </div>
    <div class="card-body">
      <?php alertMessage(); ?>

      <form action="orders-code.php" method="POST">
        <div class="row g-3 align-items-end">

          <!-- Barcode Scanner -->
          <div class="col-md-4">
            <label class="form-label fw-semibold">Scan Barcode</label>
            <div class="input-group">
              <span class="input-group-text bg-light">
                <i class="fas fa-barcode text-muted"></i>
              </span>
              <input type="text" id="barcode" name="barcode" class="form-control" placeholder="Scan or enter barcode" autofocus>
            </div>
          </div>

          <!-- Product Selection -->
          <div class="col-md-4">
            <label class="form-label fw-semibold">Select Product</label>
            <select name="product_id" class="form-select mySelect2">
              <option value="">-- Choose Product --</option>
              <?php
              $products = getAll('products');
              if ($products) {
                if (mysqli_num_rows($products) > 0) {
                  foreach ($products as $prodItem) {
              ?>
                    <option value="<?= $prodItem['id']; ?>" data-barcode="<?= $prodItem['barcode']; ?>">
                      <?= $prodItem['name']; ?>
                    </option>
              <?php
                  }
                } else {
                  echo '<option value="">No products available</option>';
                }
              } else {
                echo '<option value="">Database error</option>';
              }
              ?>
            </select>
          </div>

          <!-- Quantity -->
          <div class="col-md-2">
            <label class="form-label fw-semibold">Quantity</label>
            <input type="number" name="quantity" value="1" class="form-control" min="1" />
          </div>

          <!-- Add Button -->
          <div class="col-md-2">
            <button type="submit" name="addItem" class="btn btn-primary w-100 h-auto" style="min-height: 38px;">
              <i class="fas fa-cart-plus me-1"></i>Add Item
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Order Items Card -->
  <div class="card mt-4 shadow-sm border-0">
    <div class="card-header bg-white py-3">
      <h5 class="card-title mb-0">
        <i class="fas fa-list-alt text-primary me-2"></i>Order Items
        <?php if (isset($_SESSION['productItems']) && !empty($_SESSION['productItems'])): ?>
          <span class="badge bg-primary ms-2"><?= count($_SESSION['productItems']); ?> items</span>
        <?php endif; ?>
      </h5>
    </div>
    <div class="card-body" id="productArea">
      <?php
      if (isset($_SESSION['productItems'])) {
        $sessionProducts = $_SESSION['productItems'];

        if (empty($sessionProducts)) {
          unset($_SESSION['productItems']);
          unset($_SESSION['productItemIds']);
        }
      ?>
        <div class="table-responsive" id="productContent">
          <table class="table table-hover table-bordered">
            <thead class="table-light">
              <tr>
                <th width="5%">#</th>
                <th width="25%">Product Name</th>
                <th width="15%">Category</th>
                <th width="15%">Subcategory</th>
                <th width="10%">Price</th>
                <th width="15%">Quantity</th>
                <th width="10%">Total</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
            <tbody id="itemTableBody">
              <?php
              $i = 1;
              $grandTotal = 0;
              foreach ($sessionProducts as $key => $item) :
                $itemTotal = $item['price'] * $item['quantity'];
                $grandTotal += $itemTotal;
              ?>
                <tr>
                  <td class="fw-semibold"><?= $i++; ?></td>
                  <td><?= $item['name']; ?></td>
                  <td><span class="badge bg-secondary"><?= $item['category_name'] ?? 'N/A'; ?></span></td>
                  <td><span class="badge bg-light text-dark"><?= $item['subcategory_name'] ?? 'N/A'; ?></span></td>
                  <td class="fw-semibold">Rs. <?= number_format($item['price'], 2); ?></td>
                  <td>
                    <div class="input-group input-group-sm qtyBox" style="max-width: 120px;">
                      <input type="hidden" value="<?= $item['product_id']; ?>" class="prodId" />
                      <button class="btn btn-outline-secondary decrement" type="button">-</button>
                      <input type="text" value="<?= $item['quantity']; ?>" class="form-control text-center qty quantityInput" />
                      <button class="btn btn-outline-secondary increment" type="button">+</button>
                    </div>
                  </td>
                  <td class="fw-bold text-primary">Rs. <?= number_format($itemTotal, 2); ?></td>
                  <td>
                    <a href="order-item-delete.php?index=<?= $key; ?>" class="btn btn-sm btn-outline-danger" title="Remove item">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
              <tr>
                <td colspan="6" class="text-end fw-bold">Grand Total:</td>
                <td class="fw-bold text-success">Rs. <?= number_format($grandTotal, 2); ?></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Payment & Customer Section -->
        <div class="mt-4 p-4 border rounded bg-light">
          <h6 class="mb-3 fw-semibold">
            <i class="fas fa-credit-card me-2"></i>Payment & Customer Details
          </h6>

          <div class="row g-3 align-items-end">
            <!-- Payment Mode -->
            <div class="col-md-3">
              <label class="form-label fw-semibold">Payment Mode <span class="text-danger">*</span></label>
              <select id="payment_mode" name="payment_mode" class="form-select">
                <option value="">-- Select Payment Method --</option>
                <option value="Cash Payment">Cash Payment</option>
                <option value="Online Payment">Online Payment</option>
                <option value="Instalment">Instalment</option>
              </select>
            </div>

            <!-- Reference Number Field -->
            <div class="col-md-3" id="reference_number_field" style="display: none;">
              <label class="form-label fw-semibold">Reference Number</label>
              <input type="text" id="reference_number" name="reference_number" class="form-control" placeholder="Enter transaction reference" />
            </div>

            <!-- Customer Phone - Default -->
            <div class="col-md-3" id="default_cphone_field">
              <label class="form-label fw-semibold">Customer Phone</label>
              <div class="input-group">
                <input type="number" id="cphone" name="cphone" class="form-control" placeholder="Enter phone number" />
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal" title="Add new customer">
                  <i class="fas fa-user-plus"></i>
                </button>
              </div>
            </div>

            <!-- Proceed Button -->
            <div class="col-md-3">
              <button type="button" class="btn btn-success w-100 proceedToPlace" style="min-height: 38px;">
                <i class="fas fa-check-circle me-2"></i>Place Order
              </button>
            </div>

            <!-- Instalment Section -->
            <div class="col-12 mt-3" id="instalment_fields" style="display: none;">
              <div class="border p-3 rounded bg-white">
                <h6 class="fw-semibold mb-3">Instalment Details</h6>
                <div class="row g-3 align-items-end">
                  <div class="col-md-3">
                    <label class="form-label fw-semibold">Down Payment (Rs.)</label>
                    <input type="number" id="down_payment" name="down_payment" class="form-control" placeholder="Enter amount" step="0.01" min="0">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label fw-semibold">Period (Months)</label>
                    <input type="number" id="period_months" name="period_months" class="form-control" placeholder="Months" min="1" max="60">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-semibold">Customer Phone</label>
                    <input type="number" id="cphone_instalment" name="cphone" class="form-control" placeholder="Required for instalment" />
                  </div>
                </div>
                <div class="mt-2" id="instalment_calculation" style="display: none;">
                  <div class="alert alert-info py-2">
                    <small class="fw-semibold" id="calculation_text"></small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php
      } else {
        echo '
        <div class="text-center py-5">
          <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
          <h5 class="text-muted">No Items Added Yet</h5>
          <p class="text-muted">Start by adding products to your order using the form above.</p>
        </div>';
      }
      ?>
    </div>
  </div>

</div>


<?php include('includes/footer.php'); ?>