<?php
if (isset($_POST['saveCustomerBtn'])) {
    // Prevent any session redirects in included files
    define('AJAX_REQUEST', true);
}


include('../config/dbCon.php');
include('../config/function.php');

// Handle AJAX request to save customer
if (isset($_POST['saveCustomerBtn'])) {
    $name = validate($_POST['name']);
    $phone = validate($_POST['phone']);
    $email = isset($_POST['email']) ? validate($_POST['email']) : '';

    if ($name != '' && $phone != '') {
        // Check if customer exists by phone
        $check = mysqli_query($conn, "SELECT id FROM customers WHERE phone = '$phone'");
        if (mysqli_num_rows($check) > 0) {
            jsonResponse(200, 'success', 'Customer already exists');
        } else {
            $data = ['name' => $name, 'phone' => $phone];
            if (!empty($email)) {
                $data['email'] = $email;
            }
            $result = insert('customers', $data);
            if ($result) {
                jsonResponse(200, 'success', 'Customer created successfully');
            } else {
                jsonResponse(500, 'error', 'Database error');
            }
        }
    } else {
        jsonResponse(422, 'warning', 'Name and phone are required');
    }
    exit;
}



if (isset($_POST['saveRepair'])) {
    // Validate and sanitize inputs
    $item_name = validate($_POST['item_name']);
    $customer_id = validate($_POST['customer_id']);
    $description = validate($_POST['description']);
    $physical_condition = isset($_POST['physical_condition']) ? implode(', ', $_POST['physical_condition']) : '';
    $received_items = isset($_POST['received_items']) ? implode(', ', $_POST['received_items']) : '';

    // Check required fields
    if ($item_name != '' && $customer_id != '' && $description != '') {
        // Prepare data for insertion
        $data = [
            'item_name' => $item_name,
            'customer_id' => $customer_id,
            'description' => $description,
            'physical_condition' => $physical_condition,
            'received_items' => $received_items
        ];

        // Insert data into repairs table
        $result = insert('repairs', $data);

        // Check result and redirect
        if ($result) {
            redirect('repairs.php', 'Repair Item Created Successfully!');
        } else {
            redirect('repair-create.php', 'Something Went Wrong. Please try again.');
        }
    } else {
        redirect('repair-create.php', 'Please fill all required fields.');
    }
} else {
    redirect('repair-create.php', 'Invalid Request.');
}
