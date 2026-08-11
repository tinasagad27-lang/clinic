<div id="layoutSidenav_content">
    <main class="container mt-4">
        <h1 class="text-center mb-4">Patient Details</h1>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="patient-info">
                                    <h2>Patient Information</h2>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                    <p><strong>Patient ID:</strong> <?= isset($registration->id) ? htmlspecialchars(str_pad($registration->id, 4, '0', STR_PAD_LEFT)) : 'No ID'; ?></p>
                                            <p><strong>Name:</strong> <?= $registration->name ?> <?= $registration->mname ?> <?= $registration->lname ?></p>
                                            <p><strong>Birthday:</strong> <?= $registration->birthday ?></p>
                                            <p><strong>Age:</strong> <?= $registration->age ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Address:</strong> <?= $registration->address ?></p>
                                            <p><strong>Contact:</strong> <?= $registration->patient_contact_no ?></p>
                                            <p><strong>Philhealth ID:</strong> <?= $registration->philhealth_id;?>  </p>
											<!-- <p><strong>Doctor's Name:</strong> <?= $registration->doctor;?>  </p> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="patient-additional-info">
                                    <h2>Additional Information</h2>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Guardian's Name:</strong> <?= $registration->husband ?></p>
                                            <p><strong>Relation to Patient:</strong> <?= $registration->occupation ?></p>
                                        </div>
                                        <!-- <div class="col-md-6">
                                            <p><strong>Number of Pregnancies:</strong> <?= $registration->no_of_pregnancy ?></p>
                                            <p><strong>Last Menstrual Date:</strong> <?= $registration->last_menstrual ?></p>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="patient-record-info">
                                    <h2>Patient Record</h2>
                                    <hr>
                                    <p><strong>Patient Record Created:</strong> <?= $registration->created_at ?></p>
                                    <p><strong>Patient Record Updated:</strong> <?= $registration->last_update ?></p>
                                </div>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-md-12">
                                <div class="patient-record-info">
                                    <h2>Assigned Doctor</h2>
                                    <hr>
                                    <p><strong>Doctor's Name:</strong> <?= $registration->doctor ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a href="<?php echo site_url('registration/patients'); ?>" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Patients
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
