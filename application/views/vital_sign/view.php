<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div id="layoutSidenav_content">
    <main class="container mt-4">
        <h2 class="mb-4 text-center">Initial Check Up Record</h2>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
				<div class="col-md-12 form-group mb-3">
    <label for="patient_name">Patient Name:</label>
    <p id="patient_name" style="font-weight: bold;"><?= htmlspecialchars($vital_sign->patient_name); ?></p>
</div>

<!-- JavaScript to capitalize the first letter of each word -->
<script>
    // Get the patient name element
    var patientNameElement = document.getElementById("patient_name");

    // Capitalize each word in the patient name
    if (patientNameElement) {
        var name = patientNameElement.textContent
            .toLowerCase()
            .replace(/\b\w/g, function (char) {
                return char.toUpperCase();
            });

        // Apply the formatted name
        patientNameElement.textContent = name;
    }
</script>

                    <div class="col-md-6 form-group mb-3">
                        <label for="blood_pressure_systolic">Systolic BP:</label>
                        <p id="blood_pressure_systolic"><?= $vital_sign->blood_pressure_systolic; ?> mmHg</p>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="blood_pressure_diastolic">Diastolic BP:</label>
                        <p id="blood_pressure_diastolic"><?= $vital_sign->blood_pressure_diastolic; ?> mmHg</p>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="pulse_rate">Pulse Rate:</label>
                        <p id="pulse_rate"><?= $vital_sign->pulse_rate; ?> bpm</p>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="respiration_rate">Respiration Rate:</label>
                        <p id="respiration_rate"><?= $vital_sign->respiration_rate; ?> breaths/min</p>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="temperature">Temperature:</label>
                        <p id="temperature"><?= $vital_sign->temperature; ?> °C</p>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="oxygen_saturation">Oxygen Saturation:</label>
                        <p id="oxygen_saturation"><?= $vital_sign->oxygen_saturation; ?> %</p>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="height">Height:</label>
                        <p id="height"><?= $vital_sign->height; ?> cm</p>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="weight">Weight:</label>
                        <p id="weight"><?= $vital_sign->weight; ?> kg</p>
                    </div>
                    
                    <div class="col-md-6 form-group mb-3">
                        <label for="bmi">BMI:</label>
                        <p id="bmi"><?= $vital_sign->bmi; ?></p>
                    </div>
                    
                </div>
                <label for="bmi">Date Recorded:</label>
                <p id="bmi"><?= $vital_sign->created_at; ?></p>
                <div class="text-center" style="display: flex; justify-content: center; gap: 10px;">
                    <a href="<?= site_url('VitalSign/index'); ?>" class="btn btn-secondary">Back to List</a>
                    <!-- <a href="<?= site_url('VitalSign/update/' . $vital_sign->id); ?>" class="btn btn-primary">Edit</a> -->
                    <a href="<?= site_url('Medication/view_all_details/' . $vital_sign->registration_id); ?>" class="btn btn-primary">Next</a>
                </div>
            </div>
        </div>
    </main>
</div>
