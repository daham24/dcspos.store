<?php
session_start();

// Clear order-related session data
unset($_SESSION['productItemIds']);
unset($_SESSION['productItems']);
unset($_SESSION['cphone']);
unset($_SESSION['payment_mode']);
unset($_SESSION['reference_number']);
unset($_SESSION['invoice_no']);
unset($_SESSION['last_invoice_no']);
unset($_SESSION['order_success']);
unset($_SESSION['last_customer_data']);

echo "Session cleared";
