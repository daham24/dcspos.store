<?php include('includes/header.php'); ?>

<?php
if (!isset($_GET['id']) || $_GET['id'] === '') {
    echo '<div class="container mt-5"><div class="alert alert-danger">No staff ID provided.</div></div>';
    include('includes/footer.php');
    exit;
}

$adminId = validate($_GET['id']);
$adminData = getById('admins', $adminId);

if ($adminData['status'] != 200 || $adminData['data']['role'] !== 'staff') {
    echo '<div class="container mt-5"><div class="alert alert-danger">Invalid staff member.</div></div>';
    include('includes/footer.php');
    exit;
}

$staff = $adminData['data'];

if (empty($staff['qr_token'])) {
    $newToken = generateUniqueQrToken();
    update('admins', $adminId, ['qr_token' => $newToken]);
    $staff['qr_token'] = $newToken;
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow" id="qrPrintCard">
                <div class="card-body text-center p-5">
                    <h4 class="mb-1">DCS.lk Staff ID</h4>
                    <p class="text-muted mb-4">Scan to mark attendance at login</p>

                    <div class="mb-3">
                        <canvas id="staffQrCode"></canvas>
                    </div>

                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($staff['name']) ?></h5>
                    <p class="text-muted mb-1"><?= htmlspecialchars($staff['email']) ?></p>
                    <p class="text-muted mb-4"><?= htmlspecialchars($staff['phone']) ?></p>

                    <div class="border rounded p-2 bg-light">
                        <small class="text-muted d-block">Staff Code</small>
                        <code><?= htmlspecialchars($staff['qr_token']) ?></code>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-2 mt-4">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-1"></i>Print QR Card
                </button>
                <a href="admins-edit.php?id=<?= $staff['id'] ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const token = <?= json_encode($staff['qr_token']) ?>;
    const canvas = document.getElementById('staffQrCode');

    QRCode.toCanvas(canvas, token, {
        width: 220,
        margin: 2,
        color: {
            dark: '#000000',
            light: '#ffffff'
        }
    });
});
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #qrPrintCard, #qrPrintCard * {
        visibility: visible;
    }
    #qrPrintCard {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
    }
    .btn, nav, footer, .sb-sidenav, .sb-topnav {
        display: none !important;
    }
}
</style>

<?php include('includes/footer.php'); ?>
