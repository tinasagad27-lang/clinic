<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Appointments</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="<?php echo site_url('appointments/search_form'); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-plus"></i> Create New Appointment
                </a>
            </div>
        </div>

        <!-- Displaying session flash messages -->
        <?php if ($this->session->flashdata('message')): ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('message'); ?>
            </div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-calendar-alt me-1"></i>
                Appointments
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Time</th>
                                <!-- <th>Doctor</th> -->
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($appointments)): ?>
                                <!-- Sorting appointments to show new ones first -->
                                <?php usort($appointments, function ($a, $b) {
                                    return strtotime($b['appointment_date'] . ' ' . $b['appointment_time']) - strtotime($a['appointment_date'] . ' ' . $a['appointment_time']);
                                }); ?>

                                <?php foreach ($appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo isset($appointment['patient_name']) ? $appointment['patient_name'] : 'Unknown'; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></td>
                                        <td>
                                            <?php
                                            // Set the timezone to Philippine Time
                                            date_default_timezone_set('Asia/Manila');

                                            // Combine appointment date and time into a single DateTime object
                                            $appointmentDateTime = strtotime($appointment['appointment_date'] . ' ' . $appointment['appointment_time']);

                                            // Display appointment time in 12-hour format with AM/PM
                                            echo date('h:i A', $appointmentDateTime); // 'h:i A' for 12-hour format with AM/PM
                                            ?>
                                        </td>


                                        <!-- <td><?php echo $appointment['doctor']; ?></td> -->
                                        <td><?php echo ucfirst($appointment['status']); ?></td>
                                        <td>
                                            <a href="<?php echo site_url('Appointments/view/' . $appointment['id']); ?>" class="btn btn-outline-primary btn-sm me-1">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <!-- <a href="<?php echo site_url('Appointments/edit/' . $appointment['id']); ?>" class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a> -->
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No appointments found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="<?php echo site_url('onlineappointments/index'); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-plus"></i> List of Online Appointments
                </a>
    </main>
</div>
<script>
    $(document).ready(function() {
        $('#datatablesSimple').DataTable({
            "order": [
                [1, "desc"], // Sort by Date
                [2, "desc"] // Then by Time
            ],
            "columnDefs": [{
                "orderable": false,
                "targets": 5 // Make the Actions column not sortable
            }],
            paging: true,
            ordering: true,
            info: true
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?= base_url('disc/js/scripts.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script> -->
<script src="<?= base_url('disc/js/datatables-simple-demo.js') ?>"></script>
