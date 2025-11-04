<?php
include('includes/header.php');

// Check if order was just successfully placed
$orderSuccess = false;
if (isset($_SESSION['order_success']) && $_SESSION['order_success'] === true) {
  $orderSuccess = true;
}

// Check if we have active session data
$hasSessionData = isset($_SESSION['productItems']) && isset($_SESSION['cphone']) && isset($_SESSION['invoice_no']);

// Redirect only if there's no session data and no successful order
if (!$hasSessionData && !$orderSuccess) {
  echo '<script>window.location.href = "order-create.php"</script>';
  exit;
}

// For success case, we need to check if we have the last invoice number
if ($orderSuccess && isset($_SESSION['last_invoice_no'])) {
  $invoiceNo = $_SESSION['last_invoice_no'];
} elseif (isset($_SESSION['invoice_no'])) {
  $invoiceNo = $_SESSION['invoice_no'];
} else {
  $invoiceNo = 'INVOICE';
}
?>

<div class="modal fade" id="orderSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="mb-3 p-4">
          <h5 id="orderPlaceSuccessMessage"></h5>
        </div>
        <a href="orders.php" class="btn btn-secondary" onclick="clearSessionData()">Close</a>
        <button type="button" class="btn btn-danger" onclick="printMyBillingArea()"><i class="fa-solid fa-print"></i> Print</button>
        <button type="button" class="btn btn-warning" onclick="downloadPDF('<?= $invoiceNo ?>')">Download PDF</button>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid px-4 mb-4">
  <div class="row">
    <div class="col-md-12">
      <div class="card mt-4">
        <div class="card-header">
          <h4 class="mb-0">
            <?= $orderSuccess ? 'Order Placed Successfully' : 'Order Summary' ?>
            <a href="order-create.php" class="btn btn-sm btn-primary float-end">Back to create order</a>
          </h4>
        </div>
        <div class="card-body">
          <?php alertMessage(); ?>

          <div id="myBillingArea">
            <?php if ($hasSessionData || $orderSuccess): ?>
              <?php
              $phone = $hasSessionData ? validate($_SESSION['cphone']) : '';
              $invoiceNo = $hasSessionData ? validate($_SESSION['invoice_no']) : (isset($_SESSION['last_invoice_no']) ? validate($_SESSION['last_invoice_no']) : 'INVOICE');
              $payment_mode = $hasSessionData ? (isset($_SESSION['payment_mode']) ? validate($_SESSION['payment_mode']) : '') : '';
              $reference_number = $hasSessionData ? (isset($_SESSION['reference_number']) ? validate($_SESSION['reference_number']) : '') : '';

              if ($hasSessionData && !empty($phone)) {
                // Fetch customer details for active order
                $customerQuery = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
                if ($customerQuery && mysqli_num_rows($customerQuery) > 0) {
                  $cRowData = mysqli_fetch_assoc($customerQuery);
              ?>
                  <!-- Header Section -->
                  <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding: 10px;">
                    <div>
                      <h2 style="margin: 0; font-size: 24px;">INVOICE</h2>
                    </div>
                    <div>
                      <img src="../assets/img/png.png" alt="Logo" style="width: auto; height: 60px;">
                    </div>
                  </div>

                  <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px;">
                    <tbody>
                      <!-- Customer and Invoice Details Row -->
                      <tr>
                        <!-- Customer Details -->
                        <td style="width: 50%; padding: 10px; vertical-align: top;">
                          <h5 style="font-size: 12px; margin: 0;">Customer Details</h5>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Name:</strong> <?= $cRowData['name'] ?></p>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Phone:</strong> <?= $cRowData['phone'] ?></p>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Email:</strong> <?= $cRowData['email'] ?></p>
                        </td>

                        <!-- Invoice Details -->
                        <td style="width: 50%; padding: 10px; vertical-align: top; text-align: right;">
                          <h5 style="font-size: 12px; margin: 0;">Invoice Details</h5>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Invoice No:</strong> <?= $invoiceNo ?></p>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Date:</strong> <?= date('d M Y') ?></p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                <?php
                }
              } elseif ($orderSuccess) {
                // Success message for placed order
                echo '<div class="alert alert-success text-center mb-4">';
                echo '<h4>Order Placed Successfully!</h4>';
                echo '<p>Invoice Number: <strong>' . $invoiceNo . '</strong></p>';
                echo '</div>';

                // Try to get customer info from last session data
                if (isset($_SESSION['last_customer_data'])) {
                  $cRowData = $_SESSION['last_customer_data'];
                ?>
                  <!-- Header Section for Success -->
                  <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding: 10px;">
                    <div>
                      <h2 style="margin: 0; font-size: 24px;">INVOICE</h2>
                    </div>
                    <div>
                      <img src="../assets/img/png.png" alt="Logo" style="width: auto; height: 60px;">
                    </div>
                  </div>

                  <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px;">
                    <tbody>
                      <tr>
                        <td style="width: 50%; padding: 10px; vertical-align: top;">
                          <h5 style="font-size: 12px; margin: 0;">Customer Details</h5>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Name:</strong> <?= $cRowData['name'] ?></p>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Phone:</strong> <?= $cRowData['phone'] ?></p>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Email:</strong> <?= $cRowData['email'] ?></p>
                        </td>
                        <td style="width: 50%; padding: 10px; vertical-align: top; text-align: right;">
                          <h5 style="font-size: 12px; margin: 0;">Invoice Details</h5>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Invoice No:</strong> <?= $invoiceNo ?></p>
                          <p style="margin: 5px 0; font-size: 10px; line-height: 1.5;"><strong>Date:</strong> <?= date('d M Y') ?></p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
              <?php
                }
              }
              ?>

              <!-- Products Table -->
              <?php if ($hasSessionData && isset($_SESSION['productItems'])): ?>
                <?php
                $sessionProducts = $_SESSION['productItems'];
                ?>
                <div class="table-responsive mb-3">
                  <table style="width: 100%; margin-top: 10px; border-collapse: collapse; font-size: 12px;">
                    <thead>
                      <tr style="text-align: left;">
                        <th style="background-color:#e9ecef; border-bottom: 1px solid #e9ecef; padding: 5px;" width="5%">ID</th>
                        <th style="background-color:#e9ecef; border-bottom: 1px solid #e9ecef; padding: 5px;">Product Name</th>
                        <th style="background-color:#e9ecef; border-bottom: 1px solid #e9ecef; padding: 5px;">Price</th>
                        <th style="background-color:#e9ecef; border-bottom: 1px solid #e9ecef; padding: 5px;">Discount</th>
                        <th style="background-color:#e9ecef; border-bottom: 1px solid #e9ecef; padding: 5px;">Warranty</th>
                        <th style="background-color:#e9ecef; border-bottom: 1px solid #e9ecef; padding: 5px;">IMEI Code</th>
                        <th style="background-color:#e9ecef; border-bottom: 1px solid #e9ecef; padding: 5px;">Quantity</th>
                        <th style="background-color:#e9ecef; border-bottom: 1px solid #e9ecef; padding: 5px;">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 1;
                      $grandTotal = 0;
                      foreach ($sessionProducts as $item) {
                        $productId = $item['product_id'];

                        // Fetch product discount from the database
                        $productQuery = "SELECT discount FROM products WHERE id='$productId' LIMIT 1";
                        $productResult = mysqli_query($conn, $productQuery);

                        if ($productResult && mysqli_num_rows($productResult) > 0) {
                          $productData = mysqli_fetch_assoc($productResult);
                          $discount = $productData['discount'];
                        } else {
                          $discount = 0;
                        }

                        $totalPrice = ($item['price'] - $discount) * $item['quantity'];
                        $grandTotal += $totalPrice;
                      ?>
                        <tr>
                          <td style="border-bottom: 1px solid #ccc; padding: 5px;"><?= $i++; ?></td>
                          <td style="border-bottom: 1px solid #ccc; padding: 5px;"><?= $item['name']; ?></td>
                          <td style="border-bottom: 1px solid #ccc; padding: 5px;"><?= number_format($item['price'], 2); ?></td>
                          <td style="border-bottom: 1px solid #ccc; padding: 5px;"><?= number_format($discount, 2); ?></td>
                          <td style="border-bottom: 1px solid #ccc; padding: 5px;"><?= $item['warranty_period'] ?? 'N/A'; ?></td>
                          <td style="border-bottom: 1px solid #ccc; padding: 5px;"><?= $item['imei_code'] ?? 'N/A'; ?></td>
                          <td style="border-bottom: 1px solid #ccc; padding: 5px;"><?= $item['quantity']; ?></td>
                          <td style="border-bottom: 1px solid #ccc; padding: 5px;">
                            <?= number_format($totalPrice, 2); ?>
                          </td>
                        </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="7" align="end" style="text-align: right; padding: 5px; font-weight: bold;">Grand Total:</td>
                        <td colspan="1" style="font-size:18px; color:#e55300; padding: 5px; font-weight: bold;"> <?= number_format($grandTotal, 2); ?></td>
                      </tr>
                      <tr>
                        <td colspan="6" style="font-size: 12px;">
                          Payment Mode: <?= $payment_mode; ?>
                          <?php if (!empty($reference_number)): ?>
                            | Reference No: <?= $reference_number; ?>
                          <?php endif; ?>

                          <?php
                          // Display instalment information if payment mode is Instalment
                          if ($payment_mode == 'Instalment' && isset($_SESSION['down_payment']) && isset($_SESSION['period_months'])) {
                            $down_payment = $_SESSION['down_payment'];
                            $period_months = $_SESSION['period_months'];
                            $remaining_amount = $grandTotal - $down_payment;
                            $monthly_payment = $remaining_amount / $period_months;

                            echo ' | Down Payment: Rs. ' . number_format($down_payment, 2);
                            echo ' | Period: ' . $period_months . ' months';
                            echo ' | Monthly: Rs. ' . number_format($monthly_payment, 2);
                          }
                          ?>

                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              <?php elseif ($orderSuccess): ?>
                <div class="alert alert-info text-center">
                  <p>Order details have been saved to the database.</p>
                </div>
              <?php endif; ?>

              <!-- Terms and Conditions -->
              <div style="padding-top: 20px;">
                <h5 style="font-size: 12px; margin-bottom: 5px;">Terms and Conditions</h5>
                <p style="font-size: 10px; line-height: 16px; margin: 0;">1. භාණ්ඩය විකුණුමෙන් පසු ආපසු ගත හෝ මාරු කළ නොහැක.</p>
                <p style="font-size: 10px; line-height: 16px; margin: 0;">2. වගකීම නිෂ්පාදන දෝෂයන්ට පමණක් අදාළ වේ. එය අධික වෝල්ටීයතාව, දියර දෝෂ, වැටීමෙන් ඇතිවූ හානි, හෝ නිල මුද්‍රාව දැක්වීමෙන් හෝ ඉවත් කිරීමෙන් ඇතිවූ හානි ආවරණය කරන්නේ නැත.</p>
                <p style="font-size: 10px; line-height: 16px; margin: 0;">3. වගකීමක් ඇති ජංගම දුරකථනයක ගැටළුවක් ඇති විට, නව ජංගම දුරකථනයක් ලබාදීමට වහාම සලස්වනු නොලැබේ.</p>
              </div>

              <!-- Signatures Section -->
              <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: center;">
                <!-- Customer Signature -->
                <div style="text-align: center; font-size: 12px; line-height: 1.5; margin: 0;">
                  <p style="margin: 0; font-weight: bold;">_________________________</p>
                  <p style="margin: 5px 0 0; font-size: 10px;">Customer Signature</p>
                </div>
                <!-- Authorized Signature -->
                <div style="text-align: center; font-size: 12px; line-height: 1.5; margin: 0;">
                  <p style="margin: 0; font-weight: bold;">_________________________</p>
                  <p style="margin: 5px 0 0; font-size: 10px;">Authorized Signature</p>
                </div>
              </div>

              <!-- Footer Section -->
              <div style="background-color: #0077b6; color: #fff; padding:10px 15px; margin-top: 50px; font-size: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <!-- Web and Email -->
                  <div style="flex: 1;">
                    <p style="margin: 0; font-size: 12px; font-weight: bold;">Web & Email</p>
                    <p style="margin: 0; font-size: 10px;">www.dcs.lk</p>
                    <p style="margin: 0; font-size: 10px;">info@dcs.lk</p>
                  </div>
                  <!-- Address -->
                  <div style="flex: 1;">
                    <p style="margin: 0; font-size: 12px; font-weight: bold;">Address</p>
                    <p style="margin: 0; font-size: 10px;">319/A, Urubokka Road</p>
                    <p style="margin: 0; font-size: 10px;">Heegoda.</p>
                  </div>
                  <!-- Contact -->
                  <div style="flex: 1;">
                    <p style="margin: 0; font-size: 12px; font-weight: bold;">Contact</p>
                    <p style="margin: 0; font-size: 10px;">070 691 7666</p>
                    <p style="margin: 0; font-size: 10px;">077 791 7666</p>
                    <p style="margin: 0; font-size: 10px;">070 391 7666</p>
                  </div>
                  <!-- QR Code -->
                  <div style="flex: 0.5; text-align: right;">
                    <img src="../assets/img/qr-code.jpeg" alt="QR Code" style="width: 50px; height: auto; padding: 5px; border-radius: 5px;">
                  </div>
                </div>
              </div>
            <?php else: ?>
              <div class="alert alert-warning text-center">
                <h5>No order data found. Please create an order first.</h5>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <?php if ($hasSessionData && !$orderSuccess): ?>
          <div class="mt-4 text-end mb-4">
            <button type="button" class="btn btn-primary px-4 mx-1" id="saveOrder">Save Order</button>
            <button class="btn btn-info px-4 mx-1" onclick="printMyBillingArea()"><i class="fa-solid fa-print"></i> Print</button>
            <button class="btn btn-warning px-4 mx-1" onclick="downloadPDF('<?= $invoiceNo ?>')">Download PDF</button>
          </div>
        <?php elseif ($orderSuccess): ?>
          <div class="mt-4 text-end mb-4">
            <a href="orders.php" class="btn btn-secondary px-4 mx-1" onclick="clearSessionData()">View Orders</a>
            <button class="btn btn-info px-4 mx-1" onclick="printMyBillingArea()"><i class="fa-solid fa-print"></i> Print</button>
            <button class="btn btn-warning px-4 mx-1" onclick="downloadPDF('<?= $invoiceNo ?>')">Download PDF</button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  function clearSessionData() {
    // Clear session data via AJAX when leaving the page
    $.ajax({
      type: "POST",
      url: "clear-session.php",
      async: false
    });
  }
</script>

<?php include('includes/footer.php'); ?>