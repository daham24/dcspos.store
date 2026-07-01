<?php include("includes/header.php");

if (isset($_SESSION['loggedIn'])) {
?>
    <script>window.location.href = 'admin/index.php';</script>
<?php
    exit;
}

if (!isset($_SESSION['pendingStaffLogin'])) {
?>
    <script>window.location.href = 'login.php';</script>
<?php
    exit;
}

$pendingStaff = $_SESSION['pendingStaffLogin'];
?>

<div class="login-container">
    <div class="login-wrapper">
        <div class="form-column">
            <div class="login-content">
                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                        <h6>' . $_SESSION['message'] . '</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    unset($_SESSION['message']);
                }
                ?>

                <h1 class="login-title">Mark Attendance</h1>
                <p class="text-muted mb-4">Welcome, <strong><?= htmlspecialchars($pendingStaff['name']) ?></strong>. Scan your staff QR code to mark attendance and continue.</p>

                <div class="alert alert-light border mb-4">
                    <small class="text-muted d-block mb-2">Don't have your QR card yet?</small>
                    <small>Ask your admin to print it from <strong>Admins & Staff → Edit → Print QR Card</strong>, or enter your staff code manually below.</small>
                </div>

                <form class="login-form" action="staff-attendance-code.php" method="POST" id="attendanceForm">
                    <div class="input-group mb-3">
                        <label for="qr_token" class="input-label">Staff QR Code</label>
                        <div>
                            <input type="text" name="qr_token" id="qr_token" class="input-value input-field" placeholder="Scan QR code or enter code manually" required autofocus autocomplete="off" />
                        </div>
                        <small class="text-muted">Use your assigned staff QR card or barcode scanner</small>
                    </div>
                    <div class="submit-button">
                        <button type="submit" name="markAttendanceBtn" class="login-button">Mark Attendance & Login</button>
                    </div>
                </form>

                <div class="website-link mt-3">
                    <a href="logout.php">Cancel and return to login</a>
                </div>
            </div>
        </div>
        <div class="image-column">
            <img src="assets/img/loginBanner.jpg" alt="" class="login-image" />
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrInput = document.getElementById('qr_token');
    const form = document.getElementById('attendanceForm');

    if (qrInput) {
        qrInput.focus();

        qrInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                form.submit();
            }
        });
    }
});
</script>

<?php include("includes/footer.php"); ?>
