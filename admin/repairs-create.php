<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-tools fa-2x text-primary me-3"></i>
            <div>
                <h1 class="h3 mb-0">Add Repair Item</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="repairs.php" class="text-decoration-none">Repairs</a></li>
                        <li class="breadcrumb-item active">Add Repair</li>
                    </ol>
                </nav>
            </div>
        </div>
        <a href="repairs.php" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Back to Repairs
        </a>
    </div>

    <!-- Add Repair Card -->
    <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-plus-circle text-primary me-2"></i>Repair Information
            </h5>
        </div>
        <div class="card-body">
            <form action="repairs-process.php" method="POST" id="repairForm">
                <!-- Item Name -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">
                            Item Name <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-mobile-alt text-muted"></i>
                            </span>
                            <input type="text" id="item_name" name="item_name" class="form-control"
                                placeholder="Enter device name (e.g., iPhone 12, Samsung Galaxy S21)" required
                                maxlength="100">
                        </div>
                        <div class="form-text">Enter the specific device model and brand</div>
                    </div>

                    <!-- Customer Selection -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">
                            Select Customer <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-user text-muted"></i>
                            </span>
                            <select id="customer_id" name="customer_id" class="form-select" required>
                                <option value="">-- Select Customer --</option>
                                <?php
                                $customers = getAll('customers');
                                foreach ($customers as $customer) {
                                    echo "<option value='{$customer['id']}'>{$customer['name']} - {$customer['phone']}" .
                                        (!empty($customer['email']) ? " - {$customer['email']}" : "") . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-text">Choose the customer who owns this device</div>
                    </div>
                </div>

                <!-- Physical Condition -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Physical Condition</label>
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    $physicalConditions = [
                                        'Water logged' => 'fas fa-tint',
                                        'No power' => 'fas fa-bolt',
                                        'Signal issues' => 'fas fa-signal',
                                        'Charging issues' => 'fas fa-plug',
                                        'Display Damage' => 'fas fa-mobile',
                                        'Mic issues' => 'fas fa-microphone',
                                        'Speaker issues' => 'fas fa-volume-up',
                                        'Battery issues' => 'fas fa-battery-quarter',
                                        'Volume key issues' => 'fas fa-sliders-h',
                                        'Software issues' => 'fas fa-code',
                                        'Camera issues' => 'fas fa-camera'
                                    ];
                                    $i = 0;
                                    foreach ($physicalConditions as $condition => $icon):
                                        if ($i % 2 == 0) echo '<div class="col-md-6">';
                                    ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="physical_condition[]"
                                                value="<?= $condition ?>" id="condition_<?= $i ?>">
                                            <label class="form-check-label d-flex align-items-center" for="condition_<?= $i ?>">
                                                <i class="<?= $icon ?> text-primary me-2" style="width: 16px;"></i>
                                                <?= $condition ?>
                                            </label>
                                        </div>
                                    <?php
                                        if ($i % 2 == 1 || $i == count($physicalConditions) - 1) echo '</div>';
                                        $i++;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Received Items -->
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold">Received Items</label>
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    $receivedItems = [
                                        'Battery' => 'fas fa-battery-full',
                                        'Charger' => 'fas fa-plug',
                                        'Hands free' => 'fas fa-headphones',
                                        'Data cable' => 'fas fa-cable',
                                        'Memory card' => 'fas fa-sd-card',
                                        'Handset' => 'fas fa-mobile-alt',
                                        'Sim' => 'fas fa-sim-card',
                                        'Sim Tray' => 'fas fa-sim-card'
                                    ];
                                    $j = 0;
                                    foreach ($receivedItems as $item => $icon):
                                        if ($j % 2 == 0) echo '<div class="col-md-6">';
                                    ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="received_items[]"
                                                value="<?= $item ?>" id="item_<?= $j ?>">
                                            <label class="form-check-label d-flex align-items-center" for="item_<?= $j ?>">
                                                <i class="<?= $icon ?> text-primary me-2" style="width: 16px;"></i>
                                                <?= $item ?>
                                            </label>
                                        </div>
                                    <?php
                                        if ($j % 2 == 1 || $j == count($receivedItems) - 1) echo '</div>';
                                        $j++;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Additional Information <span class="text-danger">*</span>
                    </label>
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="input-group">
                                <span class="input-group-text bg-light align-items-start pt-2">
                                    <i class="fas fa-clipboard-list text-muted"></i>
                                </span>
                                <textarea id="description" name="description" class="form-control" rows="4"
                                    placeholder="Describe the issue in detail, any specific symptoms, customer complaints, etc."
                                    required maxlength="500"></textarea>
                            </div>
                            <div class="form-text">Provide detailed information about the repair issue</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center border-top pt-4">
                    <div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Fields marked with <span class="text-danger">*</span> are required
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-1"></i>Reset Form
                        </button>
                        <button type="submit" name="saveRepair" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Repair
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Tips Section -->
    <div class="row mt-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-light h-100">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-check text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Condition Assessment</h6>
                            <p class="card-text small text-muted mb-0">
                                Accurately document all physical conditions to provide proper diagnosis and estimate.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-light h-100">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-box-open text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Item Verification</h6>
                            <p class="card-text small text-muted mb-0">
                                Verify all received items with the customer to avoid disputes and ensure nothing is missing.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-light h-100">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-sticky-note text-primary fa-lg"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Detailed Description</h6>
                            <p class="card-text small text-muted mb-0">
                                Provide clear, detailed descriptions to help technicians understand the exact issues.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<style>
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .form-check-label {
        cursor: pointer;
        transition: color 0.2s ease-in-out;
    }

    .form-check-label:hover {
        color: #0d6efd;
    }

    .input-group-text {
        border-right: none;
    }

    .form-control:focus+.input-group-text {
        border-color: #86b7fe;
        border-right: 1px solid #86b7fe;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus on item name field
        document.getElementById('item_name').focus();

        // Form validation
        document.getElementById('repairForm').addEventListener('submit', function(e) {
            const itemName = document.getElementById('item_name').value.trim();
            const customerId = document.getElementById('customer_id').value;
            const description = document.getElementById('description').value.trim();

            if (itemName.length < 2) {
                e.preventDefault();
                showAlert('Please enter a valid item name (at least 2 characters)', 'error');
                document.getElementById('item_name').focus();
                return;
            }

            if (!customerId) {
                e.preventDefault();
                showAlert('Please select a customer', 'error');
                document.getElementById('customer_id').focus();
                return;
            }

            if (description.length < 10) {
                e.preventDefault();
                showAlert('Please provide a more detailed description (at least 10 characters)', 'error');
                document.getElementById('description').focus();
                return;
            }

            // Check if at least one physical condition is selected
            const physicalConditions = document.querySelectorAll('input[name="physical_condition[]"]:checked');
            if (physicalConditions.length === 0) {
                e.preventDefault();
                showAlert('Please select at least one physical condition', 'error');
                return;
            }
        });

        // Character counter for description
        const descriptionTextarea = document.getElementById('description');
        if (descriptionTextarea) {
            // Create character counter
            const counter = document.createElement('div');
            counter.className = 'form-text text-end';
            counter.innerHTML = '<span id="charCount">0</span>/500 characters';
            descriptionTextarea.parentNode.appendChild(counter);

            descriptionTextarea.addEventListener('input', function() {
                const charCount = this.value.length;
                document.getElementById('charCount').textContent = charCount;

                if (charCount > 500) {
                    counter.className = 'form-text text-end text-danger';
                } else if (charCount > 400) {
                    counter.className = 'form-text text-end text-warning';
                } else {
                    counter.className = 'form-text text-end text-muted';
                }
            });
        }

        // Helper function for alerts
        function showAlert(message, type = 'success') {
            // You can integrate with your existing alert system
            // For now, using a simple alert
            const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
            // This would typically integrate with your existing alert system
            console.log(`${type}: ${message}`);
        }
    });
</script>