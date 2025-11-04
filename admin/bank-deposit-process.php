<?php
session_start();
include('../config/dbCon.php');

// Function to set session messages
function setMessage($type, $message)
{
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Function to redirect with messages
function redirect($location)
{
    header('Location: ' . $location);
    exit();
}

// Handle adding a new bank via AJAX
if (isset($_POST['add_bank'])) {
    $bankName = mysqli_real_escape_string($conn, $_POST['bank_name']);

    // Validate bank name
    if (empty($bankName) || strlen($bankName) < 2) {
        // For AJAX requests, return JSON
        if (is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Bank name must be at least 2 characters long.']);
            exit();
        } else {
            setMessage('error', 'Bank name must be at least 2 characters long.');
            redirect('bank-deposits.php');
        }
    }

    // Check if bank already exists
    $checkQuery = "SELECT id FROM banks WHERE name = '$bankName'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        if (is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Bank already exists!']);
            exit();
        } else {
            setMessage('error', 'Bank already exists!');
            redirect('bank-deposits.php');
        }
    }

    $query = "INSERT INTO banks (name, created_at) VALUES ('$bankName', NOW())";
    if (mysqli_query($conn, $query)) {
        $newBankId = mysqli_insert_id($conn);
        if (is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Bank added successfully!',
                'bank_id' => $newBankId,
                'bank_name' => $bankName
            ]);
            exit();
        } else {
            setMessage('success', 'Bank added successfully!');
        }
    } else {
        if (is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Failed to add bank. Please try again.']);
            exit();
        } else {
            setMessage('error', 'Failed to add bank. Please try again.');
        }
    }

    if (!is_ajax_request()) {
        redirect('bank-deposits.php');
    }
    exit();
}

// Handle adding a new account
if (isset($_POST['add_account'])) {
    error_log("=== ADD ACCOUNT TRIGGERED ===");
    error_log("POST Data: " . print_r($_POST, true));

    $bankId = mysqli_real_escape_string($conn, $_POST['bank_id']);
    $accountNumber = mysqli_real_escape_string($conn, $_POST['account_number']);

    error_log("Bank ID: $bankId, Account Number: $accountNumber");

    // Validate inputs
    if (empty($bankId) || empty($accountNumber)) {
        $errorMsg = 'Please fill in all required fields.';
        error_log("Validation Error: $errorMsg");
        setMessage('error', $errorMsg);
        redirect('bank-deposits.php');
    }

    if (strlen($accountNumber) < 5 || !preg_match('/^\d+$/', $accountNumber)) {
        $errorMsg = 'Account number must be at least 5 digits and contain only numbers.';
        error_log("Validation Error: $errorMsg");
        setMessage('error', $errorMsg);
        redirect('bank-deposits.php');
    }

    // Check if account already exists for this bank
    $checkQuery = "SELECT id FROM bank_accounts WHERE bank_id = '$bankId' AND account_number = '$accountNumber'";
    error_log("Duplicate Check Query: $checkQuery");
    $checkResult = mysqli_query($conn, $checkQuery);

    if (!$checkResult) {
        $errorMsg = 'Database error during duplicate check: ' . mysqli_error($conn);
        error_log("Database Error: $errorMsg");
        setMessage('error', 'Database error. Please try again.');
        redirect('bank-deposits.php');
    }

    if (mysqli_num_rows($checkResult) > 0) {
        $errorMsg = 'Account number already exists for this bank!';
        error_log("Duplicate Found: $errorMsg");
        setMessage('error', $errorMsg);
        redirect('bank-deposits.php');
    }

    $query = "INSERT INTO bank_accounts (bank_id, account_number, created_at) VALUES ('$bankId', '$accountNumber', NOW())";
    error_log("Insert Query: $query");

    if (mysqli_query($conn, $query)) {
        $newAccountId = mysqli_insert_id($conn);
        $successMsg = 'Account added successfully! ID: ' . $newAccountId;
        error_log("Success: $successMsg");
        setMessage('success', 'Account added successfully!');
    } else {
        $errorMsg = 'Database Error: ' . mysqli_error($conn);
        error_log("Insert Failed: $errorMsg");
        setMessage('error', 'Failed to add account. Please try again.');
    }

    error_log("Redirecting to bank-deposits.php");
    redirect('bank-deposits.php');
}

// Helper function to check if it's an AJAX request
function is_ajax_request()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// Handle adding a new deposit
if (isset($_POST['add_deposit'])) {
    $accountId = mysqli_real_escape_string($conn, $_POST['account_id']);
    $depositAmount = mysqli_real_escape_string($conn, $_POST['deposit_amount']);
    $depositDate = mysqli_real_escape_string($conn, $_POST['deposit_date']);

    // Validate inputs
    if (empty($accountId) || empty($depositAmount) || empty($depositDate)) {
        setMessage('error', 'Please fill in all required fields.');
        redirect('bank-deposits.php');
    }

    if (!is_numeric($depositAmount) || $depositAmount <= 0) {
        setMessage('error', 'Please enter a valid deposit amount greater than zero.');
        redirect('bank-deposits.php');
    }

    // Validate date
    $currentDate = date('Y-m-d');
    if ($depositDate > $currentDate) {
        setMessage('error', 'Deposit date cannot be in the future.');
        redirect('bank-deposits.php');
    }

    $query = "INSERT INTO daily_deposits (bank_account_id, deposit_amount, deposit_date, created_at) 
              VALUES ('$accountId', '$depositAmount', '$depositDate', NOW())";

    if (mysqli_query($conn, $query)) {
        setMessage('success', 'Deposit added successfully!');
    } else {
        setMessage('error', 'Failed to add deposit. Please try again.');
    }
    redirect('bank-deposits.php');
}

// Handle updating an existing bank
if (isset($_POST['update_bank'])) {
    $bankId = mysqli_real_escape_string($conn, $_POST['bank_id']);
    $bankName = mysqli_real_escape_string($conn, $_POST['bank_name']);

    if (empty($bankName) || strlen($bankName) < 2) {
        setMessage('error', 'Bank name must be at least 2 characters long.');
        redirect('bank-deposits.php');
    }

    $query = "UPDATE banks SET name = '$bankName', updated_at = NOW() WHERE id = '$bankId'";
    if (mysqli_query($conn, $query)) {
        setMessage('success', 'Bank updated successfully!');
    } else {
        setMessage('error', 'Failed to update bank. Please try again.');
    }
    redirect('bank-deposits.php');
}

// Handle updating an existing account
if (isset($_POST['update_account'])) {
    $accountId = mysqli_real_escape_string($conn, $_POST['account_id']);
    $bankId = mysqli_real_escape_string($conn, $_POST['bank_id']);
    $accountNumber = mysqli_real_escape_string($conn, $_POST['account_number']);

    if (empty($bankId) || empty($accountNumber)) {
        setMessage('error', 'Please fill in all required fields.');
        redirect('bank-deposits.php');
    }

    if (strlen($accountNumber) < 5 || !preg_match('/^\d+$/', $accountNumber)) {
        setMessage('error', 'Account number must be at least 5 digits and contain only numbers.');
        redirect('bank-deposits.php');
    }

    $query = "UPDATE bank_accounts SET bank_id = '$bankId', account_number = '$accountNumber', updated_at = NOW() WHERE id = '$accountId'";
    if (mysqli_query($conn, $query)) {
        setMessage('success', 'Account updated successfully!');
    } else {
        setMessage('error', 'Failed to update account. Please try again.');
    }
    redirect('bank-deposits.php');
}

// Handle updating an existing deposit - UPDATED TO INCLUDE BANK NAME
if (isset($_POST['update_deposit'])) {
    $depositId = mysqli_real_escape_string($conn, $_POST['deposit_id']);
    $bankId = mysqli_real_escape_string($conn, $_POST['bank_id']); // Add this line
    $accountId = mysqli_real_escape_string($conn, $_POST['account_id']);
    $depositAmount = mysqli_real_escape_string($conn, $_POST['deposit_amount']);
    $depositDate = mysqli_real_escape_string($conn, $_POST['deposit_date']);

    // Validate inputs
    if (empty($bankId) || empty($accountId) || empty($depositAmount) || empty($depositDate)) {
        setMessage('error', 'Please fill in all required fields.');
        redirect('bank-deposit-edit.php?id=' . $depositId);
    }

    if (!is_numeric($depositAmount) || $depositAmount <= 0) {
        setMessage('error', 'Please enter a valid deposit amount greater than zero.');
        redirect('bank-deposit-edit.php?id=' . $depositId);
    }

    // Validate date
    $currentDate = date('Y-m-d');
    if ($depositDate > $currentDate) {
        setMessage('error', 'Deposit date cannot be in the future.');
        redirect('bank-deposit-edit.php?id=' . $depositId);
    }

    // First, verify that the selected account belongs to the selected bank
    $verifyQuery = "SELECT id FROM bank_accounts WHERE id = '$accountId' AND bank_id = '$bankId'";
    $verifyResult = mysqli_query($conn, $verifyQuery);

    if (mysqli_num_rows($verifyResult) == 0) {
        setMessage('error', 'The selected account does not belong to the selected bank.');
        redirect('bank-deposit-edit.php?id=' . $depositId);
    }

    // Update the deposit record - only update bank_account_id which links to the account
    $query = "UPDATE daily_deposits 
              SET bank_account_id = '$accountId', 
                  deposit_amount = '$depositAmount', 
                  deposit_date = '$depositDate'
              WHERE id = '$depositId'";

    if (mysqli_query($conn, $query)) {
        setMessage('success', 'Deposit updated successfully!');
    } else {
        setMessage('error', 'Failed to update deposit. Please try again.');
    }
    redirect('bank-deposits.php');
}

// Handle deleting a deposit
if (isset($_GET['delete_deposit'])) {
    $depositId = mysqli_real_escape_string($conn, $_GET['delete_deposit']);

    $query = "DELETE FROM daily_deposits WHERE id = '$depositId'";
    if (mysqli_query($conn, $query)) {
        setMessage('success', 'Deposit deleted successfully!');
    } else {
        setMessage('error', 'Failed to delete deposit. Please try again.');
    }
    redirect('bank-deposits.php');
}

// Get bank id from the query string (for account selection)
if (isset($_GET['bank_id'])) {
    $bankId = mysqli_real_escape_string($conn, $_GET['bank_id']);

    if ($bankId) {
        // Fetch accounts for the selected bank
        $query = "SELECT * FROM bank_accounts WHERE bank_id = '$bankId' ORDER BY account_number";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            // Return empty array if query fails
            echo json_encode([]);
            exit();
        }

        // Return the result as a JSON response
        $accounts = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $accounts[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($accounts);
    } else {
        echo json_encode([]);
    }
    exit();
}

// If no specific action is matched, redirect to main page
redirect('bank-deposits.php');
