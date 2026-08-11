<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Medication</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <main class="container mt-4">
        <div class="container">
            <div class="card shadow-sm p-4">
                <h3 class="text-primary">Health Conditions</h3>
                <h4>Name: <span class="text-decoration-underline fw-bold"><?php echo strtoupper($patient_name); ?></span></h4>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo site_url('medication/store_medication'); ?>" method="post">
                    <input type="hidden" name="registration_id" value="<?php echo $registration_id; ?>">
                    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Ear, Nose, Throat Disorders:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ear_nose_throat_disorders" value="1" required>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ear_nose_throat_disorders" value="2" required>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Heart Conditions / High Blood Pressure:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="heart_conditions_high_blood_pressure" value="1" required>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="heart_conditions_high_blood_pressure" value="2" required>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Respiratory Issues (Tuberculosis, Asthma):</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="respiratory_tuberculosis_asthma" value="1" required>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="respiratory_tuberculosis_asthma" value="2" required>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Neurologic Issues (Migraines, Frequent Headaches):</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="neurologic_migraines_frequent_headaches" value="1" required>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="neurologic_migraines_frequent_headaches" value="2" required>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Gonorrhea / Chlamydia / Syphilis:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gonorrhea_chlamydia_syphilis" value="1" required>
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gonorrhea_chlamydia_syphilis" value="2" required>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                    </div>

                    <!-- Obstetric History -->
                    <div class="card shadow-sm p-3 mt-4">
                        <h4 class="text-primary">Obstetric History</h4>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Number of Pregnancies:</label>
                                <input type="number" class="form-control" name="no_of_pregnancy" min="0" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Menstrual Period:</label>
                                <input type="date" class="form-control" name="last_menstrual" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Age of Gestation (in weeks):</label>
                                <input type="number" class="form-control" name="age_gestation" min="0" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Expected Date of Confinement:</label>
                                <input type="date" class="form-control" name="expected_date_confinement" required>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary px-4">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- DataTables Integration -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
