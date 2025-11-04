<?php include('includes/header.php'); ?>

<?php
// Set the trial period expiry date
$expiryDate = '2026-03-03'; // Update this date as needed
$currentDate = date('Y-m-d');

if ($currentDate > $expiryDate) {
    // Redirect to an expired page or stop the application
    echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Trial Expired',
                text: 'Your trial period has ended. Please contact support to activate the application.',
                footer: '<a href=\"mailto:support@yourdomain.com\">Contact Support</a>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Close'
            }).then(() => {
                window.location.href = '../index.php'; // Optional: Redirect to your website or info page
            });
        </script>
    ";
    exit; // Stop further execution
}
?>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-tachometer-alt fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0 fw-bold">Dashboard Overview</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card bg-dark text-white px-3 py-2">
            <div class="d-flex align-items-center">
                <i class="fas fa-clock me-2"></i>
                <div id="live-clock" class="fw-bold fs-5">
                    <!-- JavaScript will update this -->
                </div>
            </div>
        </div>
    </div>

    <?php alertMessage(); ?>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <!-- Inventory Summary -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Categories</h6>
                            <h3 class="fw-bold text-primary mb-0"><?= getCount('categories'); ?></h3>
                        </div>
                        <div class="icon-circle bg-primary text-white">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Product categories in system</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Products</h6>
                            <h3 class="fw-bold text-success mb-0"><?= getCount('products'); ?></h3>
                        </div>
                        <div class="icon-circle bg-success text-white">
                            <i class="fas fa-list-check"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Products in inventory</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Admins</h6>
                            <h3 class="fw-bold text-warning mb-0"><?= getCount('admins'); ?></h3>
                        </div>
                        <div class="icon-circle bg-warning text-white">
                            <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">System administrators</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Customers</h6>
                            <h3 class="fw-bold text-info mb-0"><?= getCount('customers'); ?></h3>
                        </div>
                        <div class="icon-circle bg-info text-white">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Registered customers</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders & Repairs Section -->
    <div class="row mt-2">
        <div class="col-md-12 mb-3">
            <h5 class="fw-semibold text-primary">
                <i class="fas fa-chart-line me-2"></i>Business Overview
            </h5>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Today's Orders</h6>
                            <h3 class="fw-bold text-danger mb-0">
                                <?php
                                $todayDate = date('Y-m-d');
                                $todayOrders = mysqli_query($conn, "SELECT * FROM orders WHERE order_date='$todayDate'");
                                if ($todayOrders) {
                                    echo mysqli_num_rows($todayOrders) > 0 ? mysqli_num_rows($todayOrders) : "0";
                                } else {
                                    echo '0';
                                }
                                ?>
                            </h3>
                        </div>
                        <div class="icon-circle bg-danger text-white">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Orders placed today</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Orders</h6>
                            <h3 class="fw-bold text-secondary mb-0"><?= getCount('orders'); ?></h3>
                        </div>
                        <div class="icon-circle bg-secondary text-white">
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">All-time orders</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Pending Repairs</h6>
                            <h3 class="fw-bold text-warning mb-0">
                                <?php
                                $pendingRepairs = mysqli_query($conn, "SELECT * FROM repairs WHERE status = 0");
                                if ($pendingRepairs) {
                                    echo mysqli_num_rows($pendingRepairs);
                                } else {
                                    echo "0";
                                }
                                ?>
                            </h3>
                        </div>
                        <div class="icon-circle bg-warning text-white">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">Awaiting service</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Repairs</h6>
                            <h3 class="fw-bold text-success mb-0"><?= getCount('repairs'); ?></h3>
                        </div>
                        <div class="icon-circle bg-success text-white">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">All repair jobs</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unavailable Items Section -->
    <?php
    $queryUnavailableItems = "
            SELECT p.id, p.name AS product_name, c.name AS category_name, p.price, p.barcode
            FROM products p 
            INNER JOIN categories c ON p.category_id = c.id 
            WHERE p.quantity = 0";
    $resultUnavailableItems = mysqli_query($conn, $queryUnavailableItems);
    ?>

    <?php if ($resultUnavailableItems && mysqli_num_rows($resultUnavailableItems) > 0): ?>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>Out of Stock Items
                            <span class="badge bg-danger ms-2"><?= mysqli_num_rows($resultUnavailableItems); ?></span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30%">Product Name</th>
                                        <th width="20%">Category</th>
                                        <th width="15%">Barcode</th>
                                        <th width="15%">Price</th>
                                        <th width="20%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($resultUnavailableItems)): ?>
                                        <tr>
                                            <td>
                                                <strong><?= $row['product_name']; ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary"><?= $row['category_name']; ?></span>
                                            </td>
                                            <td>
                                                <code><?= !empty($row['barcode']) ? $row['barcode'] : 'N/A'; ?></code>
                                            </td>
                                            <td>
                                                <strong class="text-success">Rs. <?= number_format($row['price'], 2); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle me-1"></i>Out of Stock
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Welcome Section -->
    <div class="row mt-5 mb-4">
        <div class="col-md-12">
            <div class="welcome-banner-container position-relative rounded shadow-lg overflow-hidden">
                <div class="banner-overlay position-absolute w-100 h-100"></div>
                <img
                    loading="lazy"
                    src="../assets/img/WelcomeBannerN.png"
                    alt="Dimuthu Cellular Service welcome image"
                    class="banner-image w-100 h-100" />
                <div class="banner-content position-absolute top-50 start-50 translate-middle text-center text-white w-100">
                    <h1 class="display-5 fw-bold mb-3">Welcome <?= $_SESSION['loggedInUser']['name']; ?>! 👋</h1>
                    <h4 class="fw-light mb-4">Have A Wonderful Day Ahead</h4>
                    <div class="banner-info mt-4">
                        <p class="mb-2">
                            <i class="fas fa-calendar me-2"></i>
                            <?= date('l, F j, Y'); ?>
                        </p>
                        <p class="mb-0 opacity-90">
                            <i class="fas fa-clock me-2"></i>
                            Last login: <?= date('g:i A'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .welcome-banner-container {
            height: 400px;
            /* Same as your original banner height */
            position: relative;
        }

        .banner-image {
            object-fit: cover;
            object-position: center;
        }

        .banner-overlay {
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .banner-content {
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .banner-info {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px 25px;
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Optional: Add some animation */
        .banner-content {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate(-50%, -40%);
            }

            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #6e707e;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }
    </style>

    <!-- JavaScript for Live Clock -->
    <script>
        function updateClock() {
            const clockElement = document.getElementById('live-clock');
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            clockElement.innerHTML = `${hours}:${minutes}:${seconds}`;
        }

        // Update clock every second
        setInterval(updateClock, 1000);

        // Initialize clock
        updateClock();

        // Add hover effects to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>


    <?php include('includes/footer.php'); ?>