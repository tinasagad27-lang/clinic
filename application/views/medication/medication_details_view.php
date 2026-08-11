<div id="layoutSidenav_content">
    <main class="container mt-4">
        <h2 class="mt-4 text-center">Medication Details</h2>
        <hr> <!-- Horizontal line for better separation -->

        <!-- Medication Details Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Condition</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Array to map field names to display labels
                    $fields = [
                        'ear_nose_throat_disorders' => 'Ear, Nose, Throat Disorders',
                        'heart_conditions_high_blood_pressure' => 'Heart Conditions / High Blood Pressure',
                        'respiratory_tuberculosis_asthma' => 'Respiratory Issues (Tuberculosis, Asthma)',
                        'neurologic_migraines_frequent_headaches' => 'Neurologic Issues (Migraines, Frequent Headaches)',
                        'gonorrhea_chlamydia_syphilis' => 'Gonorrhea / Chlamydia / Syphilis',
                        'no_of_pregnancy' => 'Number of Pregnancies',
                        'last_menstrual' => 'Last Menstrual Period',
                        'age_gestation' => 'Age of Gestation (in weeks)',
                        'expected_date_confinement' => 'Expected Date of Confinement',
                    ];

                    // Loop through each field
                    foreach ($fields as $field => $label) {
                        $value = isset($medication_details[$field]) ? $medication_details[$field] : '';

                        // Change display logic for Yes (1) and No (2) or handle other fields
                        if ($field === 'last_menstrual' || $field === 'expected_date_confinement') {
                            // Format date fields
                            $display_value = $value ? date('Y-m-d', strtotime($value)) : 'N/A';
                        } else if (in_array($field, ['no_of_pregnancy', 'age_gestation'])) {
                            $display_value = $value !== '' ? $value : 'N/A';
                        } else if ($value == 1) {
                            $display_value = 'Yes';
                        } elseif ($value == 2) {
                            $display_value = 'No';
                        } else {
                            $display_value = $value !== '' ? $value : 'N/A'; // Handle other cases
                        }
                        echo "<tr><th>{$label}</th><td>{$display_value}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="text-center">
    <a href="<?php echo site_url('medication/index'); ?>" class="btn btn-secondary">&larr; Back</a>

    <!-- Create Laboratory Button (Admin and Doctor only) -->
    <?php if (in_array($this->session->userdata('user_level'), ['admin', 'doctor'])) { ?>
        <a href="<?= site_url('laboratorytests/create/' . (isset($medication_details['registration_id']) ? $medication_details['registration_id'] : '')); ?>"
            class="btn btn-primary">
            Create Laboratory
        </a>
    <?php } ?>
</div>
    </main>
</div>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?= base_url('disc/js/scripts.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="<?= base_url('disc/js/datatables-simple-demo.js') ?>"></script>
