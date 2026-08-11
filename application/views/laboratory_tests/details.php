<div id="layoutSidenav_content">
    <div class="container mt-4">
        <h2 class="text-primary">Ultrasound Results</h2>

        <div class="card mb-4" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">Patient Information</h5>
                        <ul class="list-unstyled">
                            <li><strong>Patient ID:</strong> <?= htmlspecialchars(str_pad($test['registration_id'], 4, '0', STR_PAD_LEFT)); ?></li>

                            <li><strong>Name:</strong> <?= htmlspecialchars($this->LaboratoryTest_model->get_patient_name($test['registration_id'])); ?></li>
                            <li><strong>Birthday:</strong> <?= htmlspecialchars($this->LaboratoryTest_model->get_birthday($test['registration_id'])); ?></li>
                            <li><strong>Address:</strong> <?= htmlspecialchars($this->LaboratoryTest_model->get_address($test['registration_id'])); ?></li>
                            <!-- <td><?php echo htmlspecialchars($diagnosis['type']); ?></td> -->
                            <li><strong>Diagnosis Type:</strong> <?= htmlspecialchars($this->LaboratoryTest_model->get_diagnosis_type($test['registration_id'])); ?></li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <h5 class="card-title">Ultrasound Details</h5>
                        <ul class="list-unstyled">
                            <li><strong>Ultrasound Findings:</strong> <?= htmlspecialchars($test['ultrasound']); ?></li>
                            <li><strong>Baby's Height:</strong> <?= htmlspecialchars($test['pregnancy_test']); ?> cm</li>
                            <li><strong>Baby's Weight:</strong> <?= htmlspecialchars($test['urinalysis']); ?> kg</li>
                            <li><strong>Date of Test:</strong> <?= htmlspecialchars(date('F j, Y', strtotime($test['test_date']))); ?></li>
                            <li><strong>Additional Comments:</strong> <?= htmlspecialchars($test['results']); ?></li>
                            <li><strong>Record Created On:</strong> <?= htmlspecialchars(date('F j, Y, g:i a', strtotime($test['created_at']))); ?></li>
                            <li><strong>Last Updated On:</strong> <?= htmlspecialchars(date('F j, Y, g:i a', strtotime($test['last_update']))); ?></li>
                        </ul>
                    </div>

                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= site_url('laboratorytests/index'); ?>" class="btn btn-secondary">Back to List</a>
                    <!-- <a href="<?= site_url('laboratorytests/create'); ?>" class="btn btn-success">Add New Test</a> -->
                </div>
            </div>
        </div>
    </div>
</div>
