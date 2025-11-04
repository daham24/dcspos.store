<?php
$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
?>

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <!-- Sidebar Header -->
        <div class="sb-sidenav-header text-center py-4">
            <div class="sidebar-brand">
                <div class="brand-icon mb-2">
                    <i class="fas fa-mobile-alt fa-2x text-primary"></i>
                </div>
                <h5 class="brand-text text-white mb-0">DCS.lk</h5>
                <small class="text-muted">Admin Panel</small>
            </div>
        </div>

        <div class="sb-sidenav-menu">
            <div class="nav">
                <!-- Core Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-header">Core</div>

                    <a class="nav-link <?= $page == 'index.php' ? 'active' : ''; ?>" href="index.php">
                        <div class="nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        <span class="nav-link-text">Dashboard</span>
                        <?php if ($page == 'index.php'): ?>
                            <span class="active-indicator"></span>
                        <?php endif; ?>
                    </a>

                    <a class="nav-link <?= $page == 'order-create.php' ? 'active' : ''; ?>" href="order-create.php">
                        <div class="nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                        <span class="nav-link-text">Create Order</span>
                        <?php if ($page == 'order-create.php'): ?>
                            <span class="active-indicator"></span>
                        <?php endif; ?>
                    </a>

                    <a class="nav-link <?= $page == 'orders.php' ? 'active' : ''; ?>" href="orders.php">
                        <div class="nav-link-icon"><i class="fas fa-list-alt"></i></div>
                        <span class="nav-link-text">Orders</span>
                        <?php if ($page == 'orders.php'): ?>
                            <span class="active-indicator"></span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Interface Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-header">Interface</div>

                    <!-- Categories -->
                    <div class="nav-item <?= ($page == 'categories-create.php') || ($page == 'categories.php') ? 'active' : ''; ?>">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCategory"
                            <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                            <div class="nav-link-icon"><i class="fas fa-layer-group"></i></div>
                            <span class="nav-link-text">Categories</span>
                            <div class="nav-link-arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <div class="collapse <?= ($page == 'categories-create.php') || ($page == 'categories.php') ? 'show' : ''; ?>" id="collapseCategory">
                            <div class="submenu">
                                <a class="submenu-item <?= $page == 'categories-create.php' ? 'active' : ''; ?>" href="categories-create.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                                    <i class="fas fa-plus-circle me-2"></i>Create Category
                                </a>
                                <a class="submenu-item <?= $page == 'categories.php' ? 'active' : ''; ?>" href="categories.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                                    <i class="fas fa-eye me-2"></i>View Categories
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Products -->
                    <div class="nav-item <?= ($page == 'products-create.php') || ($page == 'products.php') ? 'active' : ''; ?>">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProduct">
                            <div class="nav-link-icon"><i class="fas fa-cube"></i></div>
                            <span class="nav-link-text">Products</span>
                            <div class="nav-link-arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <div class="collapse <?= ($page == 'products-create.php') || ($page == 'products.php') ? 'show' : ''; ?>" id="collapseProduct">
                            <div class="submenu">
                                <a class="submenu-item <?= $page == 'products-create.php' ? 'active' : ''; ?>" href="products-create.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                                    <i class="fas fa-plus-circle me-2"></i>Create Product
                                </a>
                                <a class="submenu-item <?= $page == 'products.php' ? 'active' : ''; ?>" href="products.php">
                                    <i class="fas fa-eye me-2"></i>View Products
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Repairs -->
                    <div class="nav-item <?= ($page == 'repairs-create.php') || ($page == 'repairs.php') ? 'active' : ''; ?>">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseRepair">
                            <div class="nav-link-icon"><i class="fas fa-tools"></i></div>
                            <span class="nav-link-text">Repairs</span>
                            <div class="nav-link-arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <div class="collapse <?= ($page == 'repairs-create.php') || ($page == 'repairs.php') ? 'show' : ''; ?>" id="collapseRepair">
                            <div class="submenu">
                                <a class="submenu-item <?= $page == 'repairs-create.php' ? 'active' : ''; ?>" href="repairs-create.php">
                                    <i class="fas fa-plus-circle me-2"></i>Create Repair
                                </a>
                                <a class="submenu-item <?= $page == 'repairs.php' ? 'active' : ''; ?>" href="repairs.php">
                                    <i class="fas fa-eye me-2"></i>View Repairs
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Returns -->
                    <div class="nav-item <?= ($page == 'return-items.php') || ($page == 'return-items-view.php') ? 'active' : ''; ?>">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseReturn">
                            <div class="nav-link-icon"><i class="fas fa-undo-alt"></i></div>
                            <span class="nav-link-text">Returns</span>
                            <div class="nav-link-arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <div class="collapse <?= ($page == 'return-items.php') || ($page == 'return-items-view.php') ? 'show' : ''; ?>" id="collapseReturn">
                            <div class="submenu">
                                <a class="submenu-item <?= $page == 'return-items.php' ? 'active' : ''; ?>" href="return-items.php">
                                    <i class="fas fa-plus-circle me-2"></i>Create Return
                                </a>
                                <a class="submenu-item <?= $page == 'return-items-view.php' ? 'active' : ''; ?>" href="return-items-view.php">
                                    <i class="fas fa-eye me-2"></i>View Returns
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-header">Analytics</div>

                    <a class="nav-link  <?= $page == 'order-summery-view.php' ? 'active' : ''; ?>" href="order-summery-view.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                        <div class="nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                        <span class="nav-link-text">Summary</span>
                        <?php if ($page == 'order-summery-view.php'): ?>
                            <span class="active-indicator"></span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Expenses Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-header">Expenses</div>

                    <a class="nav-link <?= $page == 'product-cost.php' ? 'active' : ''; ?>" href="product-cost.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                        <div class="nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                        <span class="nav-link-text">Product Cost</span>
                        <?php if ($page == 'product-cost.php'): ?>
                            <span class="active-indicator"></span>
                        <?php endif; ?>
                    </a>

                    <a class="nav-link <?= $page == 'bank-deposits.php' ? 'active' : ''; ?>" href="bank-deposits.php">
                        <div class="nav-link-icon"><i class="fas fa-university"></i></div>
                        <span class="nav-link-text">Bank Deposits</span>
                        <?php if ($page == 'bank-deposits.php'): ?>
                            <span class="active-indicator"></span>
                        <?php endif; ?>
                    </a>

                    <a class="nav-link <?= $page == 'utility-bills.php' ? 'active' : ''; ?>" href="utility-bills.php">
                        <div class="nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <span class="nav-link-text">Utility Bills</span>
                        <?php if ($page == 'utility-bills.php'): ?>
                            <span class="active-indicator"></span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Manage Users Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-header">Manage Users</div>

                    <!-- Admins/Staff -->
                    <div class="nav-item <?= ($page == 'admins-create.php') || ($page == 'admins.php') ? 'active' : ''; ?>">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAdmins"
                            <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                            <div class="nav-link-icon"><i class="fas fa-user-shield"></i></div>
                            <span class="nav-link-text">Admins/Staff</span>
                            <div class="nav-link-arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <div class="collapse <?= ($page == 'admins-create.php') || ($page == 'admins.php') ? 'show' : ''; ?>" id="collapseAdmins">
                            <div class="submenu">
                                <a class="submenu-item <?= $page == 'admins-create.php' ? 'active' : ''; ?>" href="admins-create.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                                    <i class="fas fa-user-plus me-2"></i>Add Admin
                                </a>
                                <a class="submenu-item <?= $page == 'admins.php' ? 'active' : ''; ?>" href="admins.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                                    <i class="fas fa-users me-2"></i>View Admins
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Customers -->
                    <div class="nav-item <?= ($page == 'customers-create.php') || ($page == 'customers.php') ? 'active' : ''; ?>">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCustomers"
                            <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                            <div class="nav-link-icon"><i class="fas fa-users"></i></div>
                            <span class="nav-link-text">Customers</span>
                            <div class="nav-link-arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <div class="collapse <?= ($page == 'customers-create.php') || ($page == 'customers.php') ? 'show' : ''; ?>" id="collapseCustomers">
                            <div class="submenu">
                                <a class="submenu-item <?= $page == 'customers-create.php' ? 'active' : ''; ?>" href="customers-create.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                                    <i class="fas fa-user-plus me-2"></i>Add Customer
                                </a>
                                <a class="submenu-item <?= $page == 'customers.php' ? 'active' : ''; ?>" href="customers.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                                    <i class="fas fa-address-book me-2"></i>View Customers
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Suppliers -->
                    <div class="nav-item <?= ($page == 'supplier-create.php') || ($page == 'suppliers.php') ? 'active' : ''; ?>">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSuppliers"
                            <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                            <div class="nav-link-icon"><i class="fas fa-truck-loading"></i></div>
                            <span class="nav-link-text">Suppliers</span>
                            <div class="nav-link-arrow"><i class="fas fa-chevron-down"></i></div>
                        </a>
                        <div class="collapse <?= ($page == 'supplier-create.php') || ($page == 'suppliers.php') ? 'show' : ''; ?>" id="collapseSuppliers">
                            <div class="submenu">
                                <a class="submenu-item <?= $page == 'supplier-create.php' ? 'active' : ''; ?>" href="supplier-create.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                                    <i class="fas fa-plus-circle me-2"></i>Add Supplier
                                </a>
                                <a class="submenu-item <?= $page == 'suppliers.php' ? 'active' : ''; ?>" href="suppliers.php" <?= $_SESSION['role'] == 'staff' ? 'style="pointer-events: none; opacity: 0.6;"' : ''; ?>>
                                    <i class="fas fa-warehouse me-2"></i>View Suppliers
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Footer -->
        <div class="sb-sidenav-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= $_SESSION['loggedInUser']['name'] ?? 'User'; ?></div>
                    <div class="user-role text-muted"><?= ucfirst($_SESSION['role'] ?? 'User'); ?></div>
                </div>
            </div>
        </div>
    </nav>
</div>

<style>
    /* Sidebar Brand */
    .sb-sidenav-header {
        border-bottom: 1px solid #4a5568;
        margin-bottom: 1rem;
    }

    .brand-icon {
        color: #4299e1;
    }

    .brand-text {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Section Headers */
    .sidebar-section {
        margin-bottom: 1.5rem;
    }

    .sidebar-section-header {
        color: #a0aec0;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.5rem 1.5rem;
        margin-bottom: 0.5rem;
    }

    /* Navigation Links */
    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: #cbd5e0;
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
        position: relative;
    }

    .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
        border-left-color: #4299e1;
    }

    .nav-link.active {
        color: #fff;
        background: linear-gradient(90deg, rgba(66, 153, 225, 0.2) 0%, transparent 100%);
        border-left-color: #4299e1;
    }

    .nav-link-icon {
        width: 20px;
        text-align: center;
        margin-right: 0.75rem;
        font-size: 1rem;
    }

    .nav-link-text {
        flex: 1;
        font-weight: 500;
    }

    .nav-link-arrow {
        transition: transform 0.3s ease;
        font-size: 0.8rem;
    }

    .nav-link.collapsed .nav-link-arrow {
        transform: rotate(0deg);
    }

    .nav-link:not(.collapsed) .nav-link-arrow {
        transform: rotate(180deg);
    }

    /* Active Indicator */
    .active-indicator {
        width: 6px;
        height: 6px;
        background: #48bb78;
        border-radius: 50%;
        margin-left: 0.5rem;
    }

    /* Submenu */
    .submenu {
        padding-left: 2.5rem;
        background: rgba(0, 0, 0, 0.2);
    }

    .submenu-item {
        display: flex;
        align-items: center;
        padding: 0.6rem 1rem;
        color: #a0aec0;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border-left: 2px solid transparent;
    }

    .submenu-item:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
        border-left-color: #4299e1;
    }

    .submenu-item.active {
        color: #fff;
        background: rgba(66, 153, 225, 0.1);
        border-left-color: #4299e1;
    }

    /* Sidebar Footer */
    .sb-sidenav-footer {
        border-top: 1px solid #4a5568;
        padding: 1rem 1.5rem;
    }

    .user-info {
        display: flex;
        align-items: center;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: rgba(66, 153, 225, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 1.25rem;
        color: #4299e1;
    }

    .user-details {
        flex: 1;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: #fff;
    }

    .user-role {
        font-size: 0.75rem;
    }

    /* Smooth transitions */
    .collapse {
        transition: all 0.3s ease;
    }

    .nav-item {
        position: relative;
    }

    /* Scrollbar Styling */
    .sb-sidenav-menu::-webkit-scrollbar {
        width: 4px;
    }

    .sb-sidenav-menu::-webkit-scrollbar-track {
        background: #2d3748;
    }

    .sb-sidenav-menu::-webkit-scrollbar-thumb {
        background: #4a5568;
        border-radius: 2px;
    }

    .sb-sidenav-menu::-webkit-scrollbar-thumb:hover {
        background: #718096;
    }
</style>

<script>
    // Smooth sidebar interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Add click animation to nav links
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.classList.contains('collapsed') && this.getAttribute('data-bs-toggle') === 'collapse') {
                    return; // Don't add animation for collapse toggles
                }

                // Add ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.3);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
            `;

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    });

    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
    document.head.appendChild(style);
</script>