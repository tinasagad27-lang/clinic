<!-- vital_sign/index.php -->
<div id="layoutSidenav_content">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Record List</h2>
            <div>
                <!-- <button class="btn btn-outline-secondary me-2" onclick="location.reload();">Refresh</button> -->
                <a href="<?= site_url('VitalSign/create'); ?>" class="btn btn-primary">Add New Record</a>
            </div>
        </div>

        <!-- Vital Signs Table -->
<div class="card">
    <div class="card-body">
        <h2 class="text-primary text-center">Vital Signs Table</h2>
        <table id="vitalSignsTable" class="table table-hover table-bordered" style="width:100%; margin-top: 20px;">
            <thead class="table-light">
                <tr>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Address</th>
                    <th>Vital Sign Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($vital_signs)) : ?>
                    <?php foreach ($vital_signs as $vital_sign) : ?>
                        <tr>
                            <td>
                                <?= isset($vital_sign->registration_id) ? sprintf('%04d', $vital_sign->registration_id) : 'N/A'; ?>
                            </td>
                            <td><?= htmlspecialchars($vital_sign->patient_name); ?></td>
                            <td><?= htmlspecialchars($vital_sign->address); ?></td>
                            <td>
                                <a href="<?= site_url('VitalSign/view/' . $vital_sign->id); ?>" class="btn btn-info btn-sm">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center">No vital sign records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Script to capitalize first letter of each word for all input fields -->
<script>
    document.querySelectorAll("input[type='text'], input[type='tel'], input[type='date']").forEach(function(input) {
        input.addEventListener("input", function() {
            this.value = this.value
                .toLowerCase()
                .replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                });
        });
    });
</script>

    </div>
</div>

<!-- DataTables Integration -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        $('#vitalSignsTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [5, 10, 25, 50, 100],
            "pageLength": 10
        });
    });
</script>
