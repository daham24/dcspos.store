<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-clipboard-check fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Staff Attendance</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Attendance</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="index.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>

    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>' . $_SESSION['message'] . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
        unset($_SESSION['message']);
    }

    $filterDate = isset($_GET['date']) ? validate($_GET['date']) : date('Y-m-d');
    $filterStaff = isset($_GET['staff_id']) ? validate($_GET['staff_id']) : '';

    $whereClause = "WHERE sa.attendance_date = '$filterDate'";
    if ($filterStaff !== '') {
        $whereClause .= " AND sa.admin_id = '$filterStaff'";
    }

    $attendanceQuery = "SELECT sa.*, a.name, a.email, a.phone, a.qr_token
                        FROM staff_attendance sa
                        INNER JOIN admins a ON a.id = sa.admin_id
                        $whereClause
                        ORDER BY sa.check_in_time DESC";
    $attendanceResult = mysqli_query($conn, $attendanceQuery);

    $staffList = mysqli_query($conn, "SELECT id, name FROM admins WHERE role='staff' ORDER BY name ASC");

    $todayCountQuery = "SELECT COUNT(*) as total FROM staff_attendance WHERE attendance_date = '" . date('Y-m-d') . "'";
    $todayCountResult = mysqli_query($conn, $todayCountQuery);
    $todayCount = mysqli_fetch_assoc($todayCountResult)['total'] ?? 0;

    $totalStaffQuery = "SELECT COUNT(*) as total FROM admins WHERE role='staff' AND is_ban = 0";
    $totalStaffResult = mysqli_query($conn, $totalStaffQuery);
    $totalStaff = mysqli_fetch_assoc($totalStaffResult)['total'] ?? 0;
    ?>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Present Today</h6>
                    <h2 class="mb-0 text-success"><?= $todayCount ?> / <?= $totalStaff ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Selected Date</h6>
                    <h2 class="mb-0"><?= date('M j, Y', strtotime($filterDate)) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Records Shown</h6>
                    <h2 class="mb-0"><?= $attendanceResult ? mysqli_num_rows($attendanceResult) : 0 ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-filter me-2 text-primary"></i>Filter Attendance
            </h4>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Date</label>
                    <input type="date" name="date" class="form-control" value="<?= $filterDate ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Staff Member</label>
                    <select name="staff_id" class="form-select">
                        <option value="">All Staff</option>
                        <?php while ($staff = mysqli_fetch_assoc($staffList)) : ?>
                            <option value="<?= $staff['id'] ?>" <?= $filterStaff == $staff['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($staff['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="attendance.php" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-list me-2 text-primary"></i>Attendance Records
            </h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Staff</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Check-in Time</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($attendanceResult && mysqli_num_rows($attendanceResult) > 0) : ?>
                            <?php while ($record = mysqli_fetch_assoc($attendanceResult)) : ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($record['name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($record['phone']) ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($record['email']) ?></td>
                                    <td><?= date('M j, Y', strtotime($record['attendance_date'])) ?></td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-clock me-1"></i><?= date('h:i A', strtotime($record['check_in_time'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?= $record['check_in_method'] === 'qr_scan' ? 'QR Scan' : 'Manual' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-2x mb-3 d-block"></i>
                                    No attendance records found for the selected filters.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
