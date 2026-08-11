<div id="layoutSidenav_content">
    <main class="container mt-4">
        <h2 class="mb-4 text-center">Initial Record</h2>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Initial Record Information</h5>
            </div>
            <div class="card-body">
                <?php 
                // Get registration_id from the query string
                $registration_id = $this->input->get('registration_id');
                
                // Fetch patient details by registration ID
                $patient = $this->vital_sign_model->get_patient_by_registration_id($registration_id);
                
                // Check if patient data is found
                if (!$patient) {
                    echo '<div class="alert alert-danger">Patient not found.</div>';
                    // Optionally, redirect back to the previous page or another relevant page
                    // redirect('VitalSign/search_form'); 
                }
                ?>
                <form action="<?= site_url('VitalSign/store'); ?>" method="post">
                    <input type="hidden" name="registration_id" value="<?= $registration_id; ?>" />

                    <div class="mb-3">
                        <label for="patient_name">Patient Name:</label>
                        <input type="text" class="form-control" id="patient_name" value="<?= isset($patient) ? htmlspecialchars($patient->full_name) : ''; ?>" disabled />
                    </div>

                    <!-- Vital Signs -->
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="blood_pressure_systolic">Systolic BP:</label>
                            <input type="text" class="form-control" name="blood_pressure_systolic" placeholder="Systolic BP" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="blood_pressure_diastolic">Diastolic BP:</label>
                            <input type="text" class="form-control" name="blood_pressure_diastolic" placeholder="Diastolic BP" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="pulse_rate">Pulse Rate:</label>
                            <input type="text" class="form-control" name="pulse_rate" placeholder="Pulse Rate" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="respiration_rate">Respiration Rate:</label>
                            <input type="text" class="form-control" name="respiration_rate" placeholder="Respiration Rate" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="temperature">Temperature:</label>
                            <input type="number" class="form-control" name="temperature" step="0.1" placeholder="Temperature" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="oxygen_saturation">Oxygen Saturation:</label>
                            <input type="text" class="form-control" name="oxygen_saturation" placeholder="Oxygen Saturation" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="height">Height (cm):</label>
                            <input type="number" class="form-control" name="height" step="0.01" placeholder="Height in cm" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="weight">Weight (kg):</label>
                            <input type="number" class="form-control" name="weight" step="0.01" placeholder="Weight in kg" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="bmi">BMI:</label>
                            <input type="text" class="form-control" name="bmi" placeholder="BMI" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="created_at">Date Recorded:</label>
                            <input type="date" class="form-control" name="created_at" value="<?= date('Y-m-d'); ?>" readonly>
                        </div>
                    </div>

                   <!-- Form Submit Buttons -->
<div class="mt-3">
    <button type="submit" class="btn btn-primary" name="submit_action" value="next">Next</button>
    <button type="submit" class="btn btn-secondary" name="submit_action" value="save_only">Save Only</button>
</div>

                    
                </form>
            </div>
        </div>
    </main>
</div>
