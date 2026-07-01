<?php
include('includes/header.php'); // or include your database connection

$customers = getAll('customers');
if ($customers) {
  foreach ($customers as $customer) {
    $display = $customer['name'] . ' - ' . $customer['phone'];
    if (!empty($customer['email'])) {
      $display .= ' - ' . $customer['email'];
    }
    echo "<option value='{$customer['id']}' data-phone='{$customer['phone']}'>{$display}</option>";
  }
} else {
  echo '<option value="" disabled>No customers found</option>';
}
