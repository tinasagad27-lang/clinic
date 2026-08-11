<div id="layoutSidenav_content">
	<main class="container mt-4">
		<div class="container">

			<h3>Health Conditions</h3>
			<h4>Name: <span style="text-decoration: underline;"><?php echo strtoupper($patient_name); ?></span></h4>

			<?php if ($this->session->flashdata('success')): ?>
				<div class="alert alert-success">
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php endif; ?>



			<form action="<?php echo site_url('medication/store'); ?>" method="post">
				<?php echo validation_errors(); // Display validation errors 
				?>
				<input type="hidden" name="registration_id" value="<?php echo $registration_id; ?>">
				<div class="row mb-4">
					<div class="col-md-6">
						<div class="form-outline">
							<label class="form-label" for="ent_yes">Ear, Nose, Throat Disorders:</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="ear_nose_throat_disorders" id="ent_yes" value="1" required>
								<label class="form-check-label" for="ent_yes">Yes</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="ear_nose_throat_disorders" id="ent_no" value="2" required>
								<label class="form-check-label" for="ent_no">No</label>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-outline">
							<label class="form-label" for="heart_yes">Heart Conditions / High Blood Pressure:</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="heart_conditions_high_blood_pressure" id="heart_yes" value="1" required>
								<label class="form-check-label" for="heart_yes">Yes</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="heart_conditions_high_blood_pressure" id="heart_no" value="2" required>
								<label class="form-check-label" for="heart_no">No</label>
							</div>
						</div>
					</div>
				</div>

				<div class="row mb-4">
					<div class="col-md-6">
						<div class="form-outline">
							<label class="form-label" for="respiratory_yes">Respiratory Issues (Tuberculosis, Asthma):</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="respiratory_tuberculosis_asthma" id="respiratory_yes" value="1" required>
								<label class="form-check-label" for="respiratory_yes">Yes</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="respiratory_tuberculosis_asthma" id="respiratory_no" value="2" required>
								<label class="form-check-label" for="respiratory_no">No</label>
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-outline">
							<label class="form-label" for="neurologic_yes">Neurologic Issues (Migraines, Frequent Headaches):</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="neurologic_migraines_frequent_headaches" id="neurologic_yes" value="1" required>
								<label class="form-check-label" for="neurologic_yes">Yes</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="neurologic_migraines_frequent_headaches" id="neurologic_no" value="2" required>
								<label class="form-check-label" for="neurologic_no">No</label>
							</div>
						</div>
					</div>
				</div>

				<div class="row mb-4">
					<div class="col-md-6">
						<div class="form-outline">
							<label class="form-label" for="std_yes">Gonorrhea / Chlamydia / Syphilis:</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="gonorrhea_chlamydia_syphilis" id="std_yes" value="1" required>
								<label class="form-check-label" for="std_yes">Yes</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="gonorrhea_chlamydia_syphilis" id="std_no" value="2" required>
								<label class="form-check-label" for="std_no">No</label>
							</div>
						</div>
					</div>
				</div>

				<!-- New Fields for Obstetric History -->
				<h4>Obstetric History</h4>

				<div class="row mb-4">
					<div class="col-md-6">
						<div class="form-outline">
							<label class="form-label" for="no_of_pregnancy">Number of Pregnancies:</label>
							<input type="number" class="form-control" name="no_of_pregnancy" id="no_of_pregnancy" min="0" required>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-outline">
							<label class="form-label" for="last_menstrual">Last Menstrual Period:</label>
							<input type="date" class="form-control" name="last_menstrual" id="last_menstrual" required>
						</div>
					</div>
				</div>

				<div class="row mb-4">
					<div class="col-md-6">
						<div class="form-outline">
							<label class="form-label" for="age_gestation">Age of Gestation (in weeks):</label>
							<input type="number" class="form-control" name="age_gestation" id="age_gestation" min="0" required>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-outline">
							<label class="form-label" for="expected_date_confinement">Expected Date of Confinement:</label>
							<input type="date" class="form-control" name="expected_date_confinement" id="expected_date_confinement" required>
						</div>
					</div>
				</div>

				<!-- <a href="<?php echo site_url('appointments/create/' . $registration_id); ?>" class="btn" style="background-color: green; color: white;">
    Save and Create Schedule
</a> -->



				<button type="submit" class="btn btn-primary">Save</button>
				<a href="<?php echo site_url('dashboard/admin/index'); ?>" class="btn btn-secondary" style="margin-left: 10px;">Back to Dashboard</a>
			</form>
		</div>
	</main>
</div>
<!-- DataTables Integration -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
