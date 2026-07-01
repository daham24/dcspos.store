<?php

require 'config/function.php';

if (isset($_POST['loginBtn'])) {
    $email = validate($_POST['email']);
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($email != '' && $password != '') {
        $query = "SELECT * FROM admins WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $hashedPassword = $row['password'];

                if (!password_verify($password, $hashedPassword)) {
                    redirect('login.php', 'Invalid Password!');
                }

                if ($row['is_ban'] == 1) {
                    redirect('login.php', 'Your account has been banned! Please Contact your Admin.');
                }

                if ($row['role'] === 'staff' && isAttendanceFeatureReady()) {
                    $row['qr_token'] = ensureStaffQrToken($row['id'], $row['qr_token'] ?? null);

                    if (empty($row['qr_token'])) {
                        completeStaffLogin($row);
                        redirect('admin/index.php', 'Logged In Successfully!');
                    }

                    if (hasMarkedAttendanceToday($row['id'])) {
                        completeStaffLogin($row);
                        redirect('admin/index.php', 'Logged In Successfully!');
                    }

                    $_SESSION['pendingStaffLogin'] = [
                        'user_id' => $row['id'],
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'role' => $row['role'],
                        'qr_token' => $row['qr_token'],
                    ];

                    redirect('staff-attendance.php', 'Please scan your QR code to mark attendance.');
                }

                if ($row['role'] === 'staff') {
                    completeStaffLogin($row);
                    redirect('admin/index.php', 'Logged In Successfully!');
                }

                $_SESSION['loggedIn'] = true;
                $_SESSION['loggedInUser'] = [
                    'user_id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                ];

                redirect('admin/index.php', 'Logged In Successfully!');
            } else {
                redirect('login.php', 'Invalid Email Address!');
            }
        } else {
            redirect('login.php', 'Something Went Wrong!');
        }
    } else {
        redirect('login.php', 'All Fields are Mandetory!');
    }
}
