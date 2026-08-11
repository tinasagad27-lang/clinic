<div id="layoutSidenav_content">
    <div class="container mt-4">
        <h2 class="text-primary">Patient List</h2>
        <a href="<?php echo site_url('diagnosis/search_form'); ?>" class="btn btn-primary mb-4">Add Prescription</a>

        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="datatablesSimple">
                <thead class="thead-dark">
                    <tr>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Date Released</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($diagnoses as $diagnosis): ?>
                        <tr id="row-<?php echo $diagnosis['id']; ?>">
                            <td>
                                <?php echo isset($diagnosis['registration_id']) 
                                    ? htmlspecialchars(str_pad($diagnosis['registration_id'], 4, '0', STR_PAD_LEFT)) 
                                    : 'No ID'; ?>
                            </td>
                            <td><?php echo htmlspecialchars($diagnosis['name'] . ' ' . $diagnosis['mname'] . ' ' . $diagnosis['lname']); ?></td>
                            <td><?php echo htmlspecialchars($diagnosis['date_released']); ?></td>
                            <td>
                                <button onclick="printSummary(<?php echo $diagnosis['id']; ?>)" class="btn btn-success btn-sm" title="Print Prescription">
                                    <i class="fas fa-prescription-bottle-alt"></i> Prescription
                                </button>
                            </td>
                        </tr>

                        <!-- Hidden prescription summary section for printing -->
                        <div id="summary-<?php echo $diagnosis['id']; ?>" style="display:none;">
                            <h1 style="text-align: center; color: #007bff; font-weight: bold;">Mendoza Clinic</h1>
                            <h4 style="text-align: center; margin-bottom: 30px;">Prescription Summary</h4>
                            <hr style="border: 1px solid #007bff; margin-bottom: 30px;">

                            <div style="margin-bottom: 20px;">
                                <p><strong>Patient ID:</strong> <?php echo htmlspecialchars(str_pad($diagnosis['registration_id'], 4, '0', STR_PAD_LEFT)); ?></p>
                                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($diagnosis['name'] . ' ' . $diagnosis['mname'] . ' ' . $diagnosis['lname']); ?></p>
                                <p><strong>Prescriptions:</strong> <?php echo htmlspecialchars($diagnosis['prescriptions']); ?></p>
                                <p><strong>Date Released:</strong> <?php echo htmlspecialchars($diagnosis['date_released']); ?></p>
                                <p><strong>Issued Date:</strong> <?php echo date('Y-m-d'); ?></p>
                            </div>

                            <!-- Signature Section -->
                            <div style="text-align: center; margin-top: 40px;">
                                <div style="position: relative; display: inline-block; width: 220px;">
                                    <img src="<?php echo base_url('assets/images/signature.png'); ?>"
                                        alt="Signature"
                                        style="position: absolute; top: -10px; left: 50%; transform: translateX(-50%); width: 180px; height: auto; opacity: 0.8;">
                                    <p style="margin-top: 60px;">____________________________</p>
                                </div>
                                <p style="margin-top: 10px;"><strong>Dr.</strong> Dra. Chona Mendoza</p>
                                <p><strong>Signature</strong></p>
                            </div>

                            <div style="text-align: center; margin-top: 20px;">
                                <p>Thank you for visiting!</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        $(document).ready(function() {
            $('#datatablesSimple').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "order": [[2, "desc"], [1, "asc"]],
                "language": {
                    "zeroRecords": "No records found",
                    "info": "Showing page _PAGE_ of _PAGES_",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
        });

        function printSummary(rowId) {
            var summaryContent = document.getElementById('summary-' + rowId);
            if (!summaryContent) {
                alert("Summary content not found!");
                return;
            }

            var originalContent = document.body.innerHTML;
            document.body.innerHTML = summaryContent.innerHTML;

            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
</div>
