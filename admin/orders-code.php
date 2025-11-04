<?php
include('../config/function.php');

if (isset($_POST['addItem'])) {
    // Validate inputs
    $productId = isset($_POST['product_id']) && !empty($_POST['product_id']) ? validate($_POST['product_id']) : null;
    $barcode = isset($_POST['barcode']) && !empty($_POST['barcode']) ? validate($_POST['barcode']) : null;
    $quantity = isset($_POST['quantity']) ? validate($_POST['quantity']) : null;

    // Check if quantity is provided
    if (!$quantity || $quantity <= 0) {
        redirect('order-create.php', 'Please enter a valid quantity!');
    }

    // Ensure at least one input (productId or barcode) is provided
    if (empty($productId) && empty($barcode)) {
        redirect('order-create.php', 'Please select a product or scan a barcode!');
    }

    // Fetch product details based on product ID or barcode
    $productQuery = null;
    if ($barcode) {
        $productQuery = "SELECT p.*, c.name AS category_name, sc.name AS subcategory_name 
                         FROM products p
                         LEFT JOIN categories c ON p.category_id = c.id
                         LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
                         WHERE p.barcode='" . mysqli_real_escape_string($conn, $barcode) . "' LIMIT 1";
    } elseif ($productId) {
        $productQuery = "SELECT p.*, c.name AS category_name, sc.name AS subcategory_name 
                        FROM products p
                        LEFT JOIN categories c ON p.category_id = c.id
                        LEFT JOIN sub_categories sc ON p.sub_category_id = sc.id
                        WHERE p.id='" . mysqli_real_escape_string($conn, $productId) . "' LIMIT 1";
    }

    if ($productQuery) {
        $checkProduct = mysqli_query($conn, $productQuery);
    }

    // Verify product availability
    if ($checkProduct && mysqli_num_rows($checkProduct) > 0) {
        $row = mysqli_fetch_assoc($checkProduct);

        // Check stock availability
        if ($row['quantity'] < $quantity) {
            redirect('order-create.php', 'Only ' . $row['quantity'] . ' quantity available!');
        }

        // Prepare product data
        $productData = [
            'product_id' => $row['id'],
            'name' => $row['name'],
            'image' => $row['image'],
            'price' => $row['price'],
            'discount' => $row['discount'],
            'quantity' => $quantity,
            'category_name' => $row['category_name'],
            'subcategory_name' => $row['subcategory_name'],
            'warranty_period' => $row['warranty_period'],
            'imei_code' => $row['imei_code']
        ];

        // Initialize session arrays if they don't exist
        if (!isset($_SESSION['productItemIds'])) {
            $_SESSION['productItemIds'] = [];
        }
        if (!isset($_SESSION['productItems'])) {
            $_SESSION['productItems'] = [];
        }

        // Add or update product in session
        if (!in_array($row['id'], $_SESSION['productItemIds'])) {
            // Add new product
            $_SESSION['productItemIds'][] = $row['id'];
            $_SESSION['productItems'][] = $productData;
        } else {
            // Update quantity for existing product
            foreach ($_SESSION['productItems'] as $key => $productSessionItem) {
                if ($productSessionItem['product_id'] == $row['id']) {
                    $newQuantity = $productSessionItem['quantity'] + $quantity;

                    if ($newQuantity > $row['quantity']) {
                        redirect('order-create.php', 'Only ' . $row['quantity'] . ' quantity available!');
                    }

                    $_SESSION['productItems'][$key]['quantity'] = $newQuantity;
                    break;
                }
            }
        }

        redirect('order-create.php', 'Item added: ' . $row['name']);
    } else {
        redirect('order-create.php', 'No such product found!');
    }
}

if (isset($_POST['productIncDec'])) {
    $productId = validate($_POST['product_id']);
    $quantity = validate($_POST['quantity']);

    $flag = false;
    foreach ($_SESSION['productItems'] as $key => $item) {
        if ($item['product_id'] == $productId) {
            $flag = true;
            $_SESSION['productItems'][$key]['quantity'] = $quantity;
        }
    }

    if ($flag) {
        jsonResponse(200, 'success', 'Quantity Updated');
    } else {
        jsonResponse(500, 'error', 'Something Went Wrong. Please refresh.');
    }
}

if (isset($_POST['proceedToPlaceBtn'])) {
    $phone = validate($_POST['cphone']);
    $payment_mode = validate($_POST['payment_mode']);
    $reference_number = isset($_POST['reference_number']) ? validate($_POST['reference_number']) : '';

    // Validate payment mode
    if (empty($payment_mode)) {
        jsonResponse(422, 'warning', 'Please select payment mode');
        exit;
    }

    // Validate phone number
    if (empty($phone) || !is_numeric($phone)) {
        jsonResponse(422, 'warning', 'Please enter valid phone number');
        exit;
    }

    // For online payment, reference number is required
    if ($payment_mode == 'Online Payment' && empty($reference_number)) {
        jsonResponse(422, 'warning', 'Reference number is required for online payments');
        exit;
    }

    // Store in session
    $_SESSION['cphone'] = $phone;
    $_SESSION['payment_mode'] = $payment_mode;
    $_SESSION['reference_number'] = $reference_number;

    // Store instalment data if payment mode is Instalment
    if ($payment_mode == 'Instalment') {
        $_SESSION['down_payment'] = validate($_POST['down_payment']);
        $_SESSION['period_months'] = validate($_POST['period_months']);
    }

    // Generate invoice number for display
    $yearMonth = date('Y-m');
    $prefix = 'INV';
    $query = "SELECT invoice_no FROM orders WHERE invoice_no LIKE '$prefix-$yearMonth-%' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastNumber = intval(substr($row['invoice_no'], strrpos($row['invoice_no'], '-') + 1));
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $newNumber = '001';
    }
    $_SESSION['invoice_no'] = "$prefix-$yearMonth-$newNumber";

    // Check if customer exists
    $checkingCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");
    if ($checkingCustomer) {
        if (mysqli_num_rows($checkingCustomer) > 0) {
            jsonResponse(200, 'success', 'Customer Found');
        } else {
            jsonResponse(404, 'warning', 'Customer Not Found! Please save customer details.');
        }
    } else {
        jsonResponse(500, 'error', 'Something Went Wrong');
    }
    exit;
}

if (isset($_POST['saveCustomerBtn'])) {
    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = validate($_POST['email']);

    if ($name != '' && $phone != '') {

        // First check if customer with this phone already exists
        $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");

        if (mysqli_num_rows($checkCustomer) > 0) {
            // Customer already exists, just use the existing one
            jsonResponse(200, 'success', 'Customer Created Succesfully!');
        } else {
            // Insert new customer
            $data = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
            ];
            $result = insert('customers', $data);

            if ($result) {
                jsonResponse(200, 'success', 'Customer Created Successfully');
            } else {
                jsonResponse(500, 'error', 'Something Went Wrong');
            }
        }
    } else {
        jsonResponse(422, 'warning', 'Please fill required fields');
    }
    exit;
}

if (isset($_POST['saveOrder'])) {
    // Check if session data exists
    if (!isset($_SESSION['cphone']) || !isset($_SESSION['payment_mode'])) {
        jsonResponse(400, 'error', 'Session data missing. Please restart the order process.');
        exit;
    }

    $phone = validate($_SESSION['cphone']);
    $payment_mode = validate($_SESSION['payment_mode']);
    $reference_number = isset($_SESSION['reference_number']) ? validate($_SESSION['reference_number']) : '';

    // Use the invoice number already generated in proceedToPlaceBtn
    $invoice_no = isset($_SESSION['invoice_no']) ? validate($_SESSION['invoice_no']) : 'INV-' . date('Y-m') . '-001';
    $order_placed_by_id = $_SESSION['loggedInUser']['user_id'];

    $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE phone='$phone' LIMIT 1");

    if (!$checkCustomer) {
        jsonResponse(500, 'error', 'Something Went Wrong!');
        exit;
    }

    if (mysqli_num_rows($checkCustomer) > 0) {
        $customerData = mysqli_fetch_assoc($checkCustomer);

        if (!isset($_SESSION['productItems']) || empty($_SESSION['productItems'])) {
            jsonResponse(404, 'warning', 'No Items to place order!');
            exit;
        }

        $sessionProducts = $_SESSION['productItems'];
        $totalAmount = 0;
        foreach ($sessionProducts as $amtItem) {
            $totalAmount += $amtItem['price'] * $amtItem['quantity'];
        }

        // Generate tracking number
        $tracking_no = rand(11111, 99999);

        // Insert into orders table with reference_number
        $data = [
            'customer_id' => $customerData['id'],
            'tracking_no' => $tracking_no,
            'invoice_no' => $invoice_no,
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d'),
            'order_status' => 'completed',
            'payment_mode' => $payment_mode,
            'reference_number' => $reference_number,
            'order_placed_by_id' => $order_placed_by_id,
        ];

        $result = insert('orders', $data);
        if (!$result) {
            jsonResponse(500, 'error', 'Failed to place order!');
            exit;
        }

        $lastOrderId = mysqli_insert_id($conn);

        // Handle Instalment Payment
        if ($payment_mode == 'Instalment') {
            $down_payment = isset($_SESSION['down_payment']) ? validate($_SESSION['down_payment']) : 0;
            $period_months = isset($_SESSION['period_months']) ? validate($_SESSION['period_months']) : 0;

            // Validate instalment data
            if (empty($down_payment) || $down_payment <= 0) {
                jsonResponse(422, 'warning', 'Please enter valid down payment amount');
                exit;
            }

            if (empty($period_months) || $period_months <= 0) {
                jsonResponse(422, 'warning', 'Please enter valid period months');
                exit;
            }

            // Calculate instalment details
            $remaining_amount = $totalAmount - $down_payment;
            $monthly_payment = $remaining_amount / $period_months;

            // Insert into instalment_payments table
            $instalmentData = [
                'order_id' => $lastOrderId,
                'tracking_no' => $tracking_no,
                'down_payment' => $down_payment,
                'period_months' => $period_months,
                'monthly_payment' => round($monthly_payment, 2),
                'remaining_amount' => round($remaining_amount, 2)
            ];

            $instalmentResult = insert('instalment_payments', $instalmentData);
            if (!$instalmentResult) {
                jsonResponse(500, 'error', 'Order placed but failed to save instalment details!');
                exit;
            }
        }

        // Insert order items into order_items table
        foreach ($sessionProducts as $prodItem) {
            $productId = $prodItem['product_id'];
            $price = $prodItem['price'];
            $discount = $prodItem['discount'];
            $quantity = $prodItem['quantity'];

            // Insert order items
            $dataOrderItem = [
                'order_id' => $lastOrderId,
                'product_id' => $productId,
                'price' => $price,
                'discount' => $discount,
                'quantity' => $quantity,
            ];
            $orderItemQuery = insert('order_items', $dataOrderItem);

            // Check and update product stock
            $checkProductQuantityQuery = mysqli_query($conn, "SELECT * FROM products WHERE id='$productId'");
            if (!$checkProductQuantityQuery) {
                jsonResponse(500, 'error', 'Failed to fetch product data!');
                exit;
            }

            $productQtyData = mysqli_fetch_assoc($checkProductQuantityQuery);
            $totalProductQuantity = $productQtyData['quantity'] - $quantity;

            // Ensure quantity is not negative
            if ($totalProductQuantity < 0) {
                jsonResponse(400, 'warning', 'Insufficient stock for product: ' . $prodItem['name']);
                exit;
            }

            $dataUpdate = ['quantity' => $totalProductQuantity];
            $updateProductQty = update('products', $productId, $dataUpdate);

            if (!$updateProductQty) {
                jsonResponse(500, 'error', 'Failed to update product stock!');
                exit;
            }
        }

        // Store the final invoice number for success display
        $_SESSION['last_invoice_no'] = $invoice_no;
        $_SESSION['order_success'] = true;

        jsonResponse(200, 'success', 'Order Placed Successfully');
        exit;
    } else {
        jsonResponse(404, 'warning', 'No Customer Found!');
        exit;
    }
}

// If no valid POST action found
jsonResponse(400, 'error', 'Invalid Request');
