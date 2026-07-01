<?php

require 'config/function.php';

if (!isset($_SESSION['pendingStaffLogin'])) {
    redirect('login.php', 'Please login first.');
}

if (isset($_POST['markAttendanceBtn'])) {
    $scannedToken = validate($_POST['qr_token']);
    $pendingStaff = $_SESSION['pendingStaffLogin'];

    if ($scannedToken === '') {
        redirect('staff-attendance.php', 'Please scan or enter your QR code.');
    }

    if ($scannedToken !== $pendingStaff['qr_token']) {
        redirect('staff-attendance.php', 'Invalid QR code. Please use your assigned staff QR card.');
    }

    $attendanceResult = markStaffAttendance($pendingStaff['user_id'], 'qr_scan');

    if ($attendanceResult['status'] != 200) {
        redirect('staff-attendance.php', $attendanceResult['message']);
    }

    $userQuery = "SELECT * FROM admins WHERE id='" . validate($pendingStaff['user_id']) . "' LIMIT 1";
    $userResult = mysqli_query($conn, $userQuery);

    if ($userResult && mysqli_num_rows($userResult) === 1) {
        $userRow = mysqli_fetch_assoc($userResult);
        completeStaffLogin($userRow);
        redirect('admin/index.php', 'Attendance marked. Welcome!');
    }

    redirect('login.php', 'Something went wrong. Please login again.');
}

redirect('staff-attendance.php', 'Invalid request.');
