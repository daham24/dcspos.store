<?php
include('../config/function.php');

if (isset($_POST['mark_full_paid'])) {
  $instalment_id = validate($_POST['instalment_id']);
  $tracking_no = validate($_POST['tracking_no']);

  // Check if instalment exists
  $instalmentQuery = "SELECT * FROM instalment_payments WHERE id='$instalment_id' AND tracking_no='$tracking_no' LIMIT 1";
  $instalmentResult = mysqli_query($conn, $instalmentQuery);

  if (!$instalmentResult || mysqli_num_rows($instalmentResult) == 0) {
    jsonResponse(404, 'error', 'Instalment record not found!');
    exit;
  }

  $instalmentData = mysqli_fetch_assoc($instalmentResult);

  // Update payment status to completed and set remaining amount to 0
  $updateData = [
    'payment_status' => 'completed',
    'remaining_amount' => 0,
    'monthly_payment' => 0
  ];

  $updateResult = update('instalment_payments', $instalment_id, $updateData);

  if ($updateResult) {
    // Also update the main order status to completed
    $orderUpdate = update('orders', $instalmentData['order_id'], ['order_status' => 'completed']);

    jsonResponse(200, 'success', 'Full payment marked as completed successfully! Remaining amount cleared.');
  } else {
    jsonResponse(500, 'error', 'Failed to update payment status!');
  }
  exit;
}

jsonResponse(400, 'error', 'Invalid request');
