<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Report</title>
   
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }

            .container {
                width: 100%;
                padding: 0;
            }

            h2,
            h3 {
                color: #000;
            }

            .card,
            .table {
                border: none;
                box-shadow: none;
                margin-bottom: 20px;
            }

            .table {
                width: 100%;
                border-collapse: collapse;
            }

            .table th,
            .table td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }

            .table th {
                background-color: #f2f2f2;
            }

            /* Hide non-printable elements */
            button,
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div id="layoutSidenav_content">
        <div class="container mt-4">

            <h2 class="text-primary">Diagnosis</h2>

            <!-- Patient Information -->
            <h3 class="mt-4">Patient Information</h3>
            <?php if (isset($patient) && !empty($patient)): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>Patient ID:</strong> <?= htmlspecialchars(str_pad($patient->id, 4, '0', STR_PAD_LEFT)) ?></p>
                        <p><strong>Full Name:</strong> <?= htmlspecialchars($patient->name . ' ' . $patient->mname . ' ' . $patient->lname) ?></p>
                        <p><strong>Address:</strong> <?= htmlspecialchars($patient->address) ?></p>
                        <p><strong>Birthday:</strong> <?= htmlspecialchars($patient->birthday) ?></p>
                        <p><strong>Marital Status:</strong> <?= htmlspecialchars($patient->marital_status) ?></p>
                    </div>
                </div>
            <?php else: ?>
                <p>No patient found.</p>
            <?php endif; ?>

            <!-- Vital Signs -->
            <h3 class="mt-4">Vital Signs</h3>
            <?php if (!empty($vital_signs)): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Blood Pressure (Systolic)</th>
                            <th>Blood Pressure (Diastolic)</th>
                            <th>Pulse Rate</th>
                            <th>Respiration Rate</th>
                            <th>Temperature</th>
                            <th>Oxygen Saturation</th>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>BMI</th>
                            <th>Checkup Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vital_signs as $vital_sign): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vital_sign->blood_pressure_systolic); ?></td>
                                <td><?php echo htmlspecialchars($vital_sign->blood_pressure_diastolic); ?></td>
                                <td><?php echo htmlspecialchars($vital_sign->pulse_rate); ?></td>
                                <td><?php echo htmlspecialchars($vital_sign->respiration_rate); ?></td>
                                <td><?php echo htmlspecialchars($vital_sign->temperature); ?></td>
                                <td><?php echo htmlspecialchars($vital_sign->oxygen_saturation); ?></td>
                                <td><?php echo htmlspecialchars($vital_sign->height); ?></td>
                                <td><?php echo htmlspecialchars($vital_sign->weight); ?></td>
                                <td><?php echo htmlspecialchars($vital_sign->bmi); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($vital_sign->checkup_date))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No vital signs found for this registration ID.</p>
            <?php endif; ?>

           <!-- Laboratory Tests Section -->
<h3 class="mt-4">Ultrasound Tests</h3>
<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-light">
            <tr>
                <th>Ultrasound Findings</th>
                <th>Baby's Height (cm)</th>
                <th>Baby's Weight (kg)</th>
                <th>Doctor's Notes</th>
                <th>Test Date</th>
                <th>Diagnosis Type</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($laboratory_tests)): ?>
                <?php foreach ($laboratory_tests as $test): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($test->ultrasound); ?></td>
                        <td><?php echo htmlspecialchars($test->urinalysis); ?> cm</td> <!-- Baby's Height -->
                        <td><?php echo htmlspecialchars($test->pregnancy_test); ?> kg</td> <!-- Baby's Weight -->
                        <td><?php echo htmlspecialchars($test->results); ?></td>
                        <td><?php echo htmlspecialchars(date('F j, Y', strtotime($test->created_at))); ?></td>
                        <td>
                            <?php
                                // You can map the diagnosis_type_id to the diagnosis name if required
                                // Example: Assuming you have a function or array mapping diagnosis types
                                $diagnosis_type = ''; // You need a way to fetch the diagnosis type name from the ID
                                if ($test->diagnosis_type_id == 1) {
                                    $diagnosis_type = 'Pre-mature';
                                } elseif ($test->diagnosis_type_id == 2) {
                                    $diagnosis_type = 'Placenta Previa';
                                } elseif ($test->diagnosis_type_id == 3) {
                                    $diagnosis_type = 'Abruptio Placenta';
                                } elseif ($test->diagnosis_type_id == 4) {
                                    $diagnosis_type = 'Cesarian Section';
                                } elseif ($test->diagnosis_type_id == 6) {
                                    $diagnosis_type = 'Check Up';
                                }
                                echo htmlspecialchars($diagnosis_type); // Display diagnosis type
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No Ultrasound tests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


            <!-- Display Previous Findings -->
            
            <?php if (isset($findings) && !empty($findings)): ?>
                <h3 class="mt-4">Recommendations</h3>
                <ul class="list-group mb-4">
                    <?php foreach ($findings as $finding): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($finding->recommendations) ?> -
                            <?= htmlspecialchars($finding->created_at) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <h3 class="mt-4">Diagnosis</h3>
                <ul class="list-group mb-4">
                    <?php foreach ($findings as $finding): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($finding->findings) ?> -
                            <?= htmlspecialchars($finding->created_at) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <h3 class="mt-4">Previous Diagnosis</h3>
                <p>No Diagnosis recorded yet.</p>
            <?php endif; ?>
            
            <!-- Print Button -->
<button onclick="window.print()" class="btn btn-primary no-print" style="margin-bottom: 20px;">Print Report</button>

<!-- Signature Section -->
<div class="signature-section" style="text-align: left; position: relative; margin-top: 40px; display: none;">
<div style="position: relative; display: inline-block; width: 200px;">
    <!-- Signature Image (Overlayed over the name) -->
    <img src="<?php echo base_url('assets/images/signature.png'); ?>"
        alt="Signature"
        style="position: absolute; top: 10px; left: 0; width: 300px; height: auto; opacity: 0.8;">
</div>
<!-- Printed Name and Line -->
<p style="margin-top: 100px;">____________________________</p>
<p><strong>Dr.</strong> Dra. Chona Mendoza</p>
<p><strong>Signature</strong></p>

</div>

<!-- CSS to control visibility -->
<style>
    /* Hide signature by default */
    .signature-section {
        display: none;
    }

    /* Show signature when printing */
    @media print {
        .signature-section {
            display: block !important;
        }
    }
</style>




<!-- Print Styling (For Hiding Elements) -->
<style>
    @media print {
        /* Hide print button during printing */
        .no-print {
            display: none;
        }

        /* Ensure the report content fills the page */
        #reportSection {
            margin: 0;
            padding: 20px;
        }

        /* Ensure tables are properly formatted for printing */
        table {
            border: 1px solid #000;
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border: 1px solid #000;
        }
    }
</style>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
