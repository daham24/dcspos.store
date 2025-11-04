<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mt-4">
    <div class="d-flex align-items-center">
      <i class="fas fa-edit fa-2x text-primary me-3"></i>
      <div>
        <h1 class="h3 mb-0">Edit Repair Item</h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="repairs.php" class="text-decoration-none">Repairs</a></li>
            <li class="breadcrumb-item active">Edit Repair</li>
          </ol>
        </nav>
      </div>
    </div>
    <a href="repairs.php" class="btn btn-outline-primary">
      <i class="fas fa-arrow-left me-1"></i>Back to Repairs
    </a>
  </div>

  <?php
  $paramValue = checkParamId('id');
  if (!is_numeric($paramValue)) {
    echo '<div class="alert alert-danger mt-4">Invalid Repair ID: ' . htmlspecialchars($paramValue) . '</div>';
  } else {
    $repair = getById('repairs', $paramValue);
    if ($repair['status'] != 200) {
      echo '<div class="alert alert-danger mt-4">' . htmlspecialchars($repair['message']) . '</div>';
    } else {
      $repairData = $repair['data'];

      // Pre-fill arrays for checkboxes
      $physicalConditions = explode(', ', $repairData['physical_condition'] ?? '');
      $receivedItems = explode(', ', $repairData['received_items'] ?? '');
  ?>

      <!-- Edit Repair Card -->
      <div class="card mt-4 shadow-sm border-0">
        <div class="card-header bg-white py-3">
          <h5 class="card-title mb-0">
            <i class="fas fa-tools text-primary me-2"></i>Edit Repair Information
          </h5>
        </div>
        <div class="card-body">
          <?php alertMessage(); ?>

          <form action="code.php" method="POST" id="editRepairForm">
            <input type="hidden" name="repairId" value="<?= htmlspecialchars($repairData['id']); ?>">

            <div class="row">
              <!-- Item Name -->
              <div class="col-md-6 mb-4">
                <label class="form-label fw-semibold">
                  Item Name <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-mobile-alt text-muted"></i>
                  </span>
                  <input type="text" id="item_name" name="item_name" required
                    value="<?= htmlspecialchars($repairData['item_name']); ?>"
                    class="form-control"
                    placeholder="Enter device name"
                    maxlength="100">
                </div>
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
                      $selected = ($repairData['customer_id'] == $customer['id']) ? 'selected' : '';
                      echo "<option value='{$customer['id']}' $selected>
                                            {$customer['name']} - {$customer['phone']}" .
                        (!empty($customer['email']) ? " - {$customer['email']}" : "") . "
                                          </option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>

            <!-- Physical Condition -->
            <div class="row">
              <div class="col-md-6 mb-4">
                <label class="form-label fw-semibold">Physical Condition</label>
                <div class="card border-0 bg-light">
                  <div class="card-body">
                    <h6 class="card-title mb-3 text-muted">
                      <i class="fas fa-clipboard-check me-2"></i>Select Conditions
                    </h6>
                    <div class="row">
                      <?php
                      $physicalConditionOptions = [
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
                        'Camera issues' => 'fas fa-camera',
                        'Other' => 'fas fa-ellipsis-h'
                      ];
                      $i = 0;
                      foreach ($physicalConditionOptions as $condition => $icon):
                        if ($i % 2 == 0) echo '<div class="col-md-6">';
                        $checked = in_array($condition, $physicalConditions) ? 'checked' : '';
                      ?>
                        <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox"
                            name="physical_condition[]"
                            value="<?= $condition ?>"
                            id="condition_<?= $i ?>" <?= $checked ?>>
                          <label class="form-check-label d-flex align-items-center" for="condition_<?= $i ?>">
                            <i class="<?= $icon ?> text-primary me-2" style="width: 16px;"></i>
                            <?= $condition ?>
                          </label>
                        </div>
                      <?php
                        if ($i % 2 == 1 || $i == count($physicalConditionOptions) - 1) echo '</div>';
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
                    <h6 class="card-title mb-3 text-muted">
                      <i class="fas fa-box-open me-2"></i>Items with Device
                    </h6>
                    <div class="row">
                      <?php
                      $receivedItemOptions = [
                        'Battery' => 'fas fa-battery-full',
                        'Charger' => 'fas fa-plug',
                        'Hands free' => 'fas fa-headphones',
                        'Data cable' => 'fas fa-cable',
                        'Memory card' => 'fas fa-sd-card',
                        'Handset' => 'fas fa-mobile-alt',
                        'Sim' => 'fas fa-sim-card',
                        'Other' => 'fas fa-ellipsis-h'
                      ];
                      $j = 0;
                      foreach ($receivedItemOptions as $item => $icon):
                        if ($j % 2 == 0) echo '<div class="col-md-6">';
                        $checked = in_array($item, $receivedItems) ? 'checked' : '';
                      ?>
                        <div class="form-check mb-2">
                          <input class="form-check-input" type="checkbox"
                            name="received_items[]"
                            value="<?= $item ?>"
                            id="item_<?= $j ?>" <?= $checked ?>>
                          <label class="form-check-label d-flex align-items-center" for="item_<?= $j ?>">
                            <i class="<?= $icon ?> text-primary me-2" style="width: 16px;"></i>
                            <?= $item ?>
                          </label>
                        </div>
                      <?php
                        if ($j % 2 == 1 || $j == count($receivedItemOptions) - 1) echo '</div>';
                        $j++;
                      endforeach;
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
              <label class="form-label fw-semibold">
                Description (Issue) <span class="text-danger">*</span>
              </label>
              <div class="card border-0 bg-light">
                <div class="card-body">
                  <div class="input-group">
                    <span class="input-group-text bg-light align-items-start pt-2">
                      <i class="fas fa-clipboard-list text-muted"></i>
                    </span>
                    <textarea id="description" name="description" required
                      class="form-control" rows="4"
                      placeholder="Describe the issue in detail..."
                      maxlength="500"><?= htmlspecialchars($repairData['description']); ?></textarea>
                  </div>
                  <div class="form-text">Provide detailed information about the repair issue</div>
                </div>
              </div>
            </div>

            <!-- Status Toggle -->
            <div class="mb-4">
              <div class="card border-0 bg-light">
                <div class="card-body py-3">
                  <div class="row align-items-center">
                    <div class="col-md-8">
                      <label class="form-label fw-semibold mb-0">Repair Status</label>
                      <p class="text-muted mb-0 small">
                        Update the current status of this repair
                      </p>
                    </div>
                    <div class="col-md-4 text-end">
                      <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox"
                          name="status" id="statusSwitch"
                          <?= $repairData['status'] ? 'checked' : ''; ?>
                          style="width: 3.5rem; height: 1.5rem;">
                        <label class="form-check-label fw-semibold" for="statusSwitch">
                          <span id="statusText" class="<?= $repairData['status'] ? 'text-success' : 'text-warning'; ?>">
                            <?= $repairData['status'] ? 'Completed' : 'Pending'; ?>
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center border-top pt-4">
              <div>
                <small class="text-muted">
                  <i class="fas fa-info-circle me-1"></i>
                  Last updated: <?= date('M j, Y g:i A', strtotime($repairData['updated_at'])); ?>
                </small>
              </div>
              <div class="d-flex gap-2">
                <a href="repairs.php" class="btn btn-outline-secondary">
                  <i class="fas fa-times me-1"></i>Cancel
                </a>
                <button type="submit" name="updateRepair" class="btn btn-primary">
                  <i class="fas fa-save me-1"></i>Update Repair
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

  <?php
    }
  }
  ?>
</div>

<?php include('includes/footer.php'); ?>

<style>
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

  .card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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
    // Status toggle functionality
    const statusSwitch = document.getElementById('statusSwitch');
    const statusText = document.getElementById('statusText');

    if (statusSwitch) {
      statusSwitch.addEventListener('change', function() {
        statusText.textContent = this.checked ? 'Completed' : 'Pending';
        statusText.className = this.checked ? 'text-success' : 'text-warning';
      });
    }

    // Character counter for description
    const descriptionTextarea = document.getElementById('description');
    if (descriptionTextarea) {
      // Create character counter
      const counter = document.createElement('div');
      counter.className = 'form-text text-end';
      counter.innerHTML = '<span id="charCount">' + descriptionTextarea.value.length + '</span>/500 characters';
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

    // Form validation
    document.getElementById('editRepairForm').addEventListener('submit', function(e) {
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

    // Helper function for alerts
    function showAlert(message, type = 'success') {
      // You can integrate with your existing alert system
      console.log(`${type}: ${message}`);
    }
  });
</script>