<div id="layoutSidenav_content">
	<main class="container mt-4">
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
			<h1 class="h2">Update Status</h1>
		</div>

		<div class="container">
			<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

			<?php echo form_open('appointments/edit/' . $appointment['id'], ['class' => 'needs-validation', 'novalidate' => '']); ?>

			<div class="mb-3">
    <label for="patient_name" class="form-label">Patient</label>
    <input type="text" name="patient_name" id="patient_name" class="form-control" 
           value="<?php echo htmlspecialchars($appointment['patient_name']); ?>" 
           readonly aria-label="Patient Name" style="text-transform: capitalize;">
</div>


			<div class="form-group mb-3">
				<label for="appointment_date">Date</label>
				<input type="date" name="appointment_date" class="form-control" value="<?php echo set_value('appointment_date', $appointment['appointment_date']); ?>" required aria-label="Appointment Date">
				<div class="invalid-feedback">Please select a date.</div>
			</div>

			<div class="form-group mb-3">
				<label for="appointment_time">Time</label>
				<input type="time" name="appointment_time" id="appointment_time" class="form-control" value="<?php echo set_value('appointment_time', $appointment['appointment_time']); ?>" required aria-label="Appointment Time">
				<div class="invalid-feedback">Please select a time.</div>
			</div>

			<!-- <div class="form-group mb-3">
				<label for="doctor">Doctor</label>
				<input type="text" name="doctor" id="doctor" class="form-control" value="<?php echo htmlspecialchars($appointment['doctor']); ?>" readonly aria-label="Doctor Name">
			</div> -->

			<?php $user_level = $this->session->userdata('user_level'); ?>

			<div class="form-group mb-3">
				<label for="status">Status</label>
				<select name="status" id="status" class="form-control" required>
					<?php if ($user_level !== 'doctor'): ?>
						<option value="pending" <?php echo set_select('status', 'pending', isset($appointment['status']) && $appointment['status'] == 'pending'); ?>>Pending</option>
						<option value="cancelled" <?php echo set_select('status', 'cancelled', isset($appointment['status']) && $appointment['status'] == 'cancelled'); ?>>Cancelled</option>
						<option value="follow_up" <?php echo set_select('status', 'follow_up', isset($appointment['status']) && $appointment['status'] == 'follow_up'); ?>>Follow Up</option>
						<option value="reschedule" <?php echo set_select('status', 'reschedule', isset($appointment['status']) && $appointment['status'] == 'reschedule'); ?>>Reschedule</option>
					<?php endif; ?>

					<option value="booked" <?php echo set_select('status', 'booked', isset($appointment['status']) && $appointment['status'] == 'booked'); ?>>Booked</option>

					<?php if ($user_level === 'doctor' || $user_level === 'admin'): ?>
						<option value="completed" <?php echo set_select('status', 'completed', isset($appointment['status']) && $appointment['status'] == 'completed'); ?>>Completed</option>
						<option value="follow_up" <?php echo set_select('status', 'follow_up', isset($appointment['status']) && $appointment['status'] == 'follow_up'); ?>>Follow Up</option>
						<option value="reschedule" <?php echo set_select('status', 'reschedule', isset($appointment['status']) && $appointment['status'] == 'reschedule'); ?>>Reschedule</option>
					<?php endif; ?>
				</select>
				<div class="invalid-feedback">Please select a status.</div>
			</div>

			<?php if ($user_level === 'doctor' || $user_level === 'admin'): ?>
				<div class="form-group mb-3">
					<label for="notes">Next Appointment</label>
					<input type="date" name="notes" id="notes" class="form-control" value="<?php echo set_value('notes', $appointment['notes']); ?>" />

				</div>
			<?php endif; ?>

			<?php if ($this->session->flashdata('success')): ?>
				<div class="alert alert-success">
					<?php echo $this->session->flashdata('success'); ?>
				</div>
			<?php endif; ?>

			<button type="submit" class="btn btn-primary">Update Appointment</button>
			<?php echo form_close(); ?>
		</div>
	</main>
</div>

<script>
	(function() {
		'use strict';
		var forms = document.querySelectorAll('.needs-validation');

		Array.prototype.slice.call(forms).forEach(function(form) {
			form.addEventListener('submit', function(event) {
				if (!form.checkValidity()) {
					event.preventDefault();
					event.stopPropagation();
				}

				form.classList.add('was-validated');
			}, false);
		});
	})();
</script>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const appointmentDateInput = document.getElementById("appointment_date");
		const appointmentTimeSelect = document.getElementById("appointment_time");

		const today = new Date();
		const philippinesTimeOffset = 8 * 60;
		today.setMinutes(today.getMinutes() + today.getTimezoneOffset() + philippinesTimeOffset);

		const todayString = today.toISOString().split("T")[0];
		appointmentDateInput.setAttribute("min", todayString);
		appointmentDateInput.value = todayString;

		const excludedTimes = ['11:30', '17:30'];

		function isExcludedTime(time) {
			return excludedTimes.includes(time);
		}

		appointmentDateInput.addEventListener("change", function() {
			const selectedDate = new Date(appointmentDateInput.value);
			const now = new Date();
			now.setMinutes(now.getMinutes() + now.getTimezoneOffset() + philippinesTimeOffset);

			appointmentTimeSelect.innerHTML = "";

			if (selectedDate.toDateString() === now.toDateString()) {
				let startHour = now.getHours();
				let startMinute = now.getMinutes() >= 30 ? 30 : 0;
				const endHour = 17;

				for (let hour = startHour; hour <= endHour; hour++) {
					for (let minute = startMinute; minute < 60; minute += 30) {
						const timeString = `${hour.toString().padStart(2, '0')}:${minute === 0 ? '00' : minute}`;

						if (isExcludedTime(timeString)) continue;

						const option = document.createElement("option");
						const formattedHour = hour > 12 ? hour - 12 : hour;
						const formattedMinute = minute === 0 ? '00' : minute;
						const amPm = hour >= 12 ? 'PM' : 'AM';

						option.value = timeString;
						option.textContent = `${formattedHour}:${formattedMinute} ${amPm}`;
						appointmentTimeSelect.appendChild(option);
					}
					startMinute = 0;
				}
			} else {
				for (let hour = 9; hour <= 17; hour++) {
					for (let minute = 0; minute < 60; minute += 30) {
						const timeString = `${hour.toString().padStart(2, '0')}:${minute === 0 ? '00' : minute}`;

						if (isExcludedTime(timeString)) continue;

						const option = document.createElement("option");
						const formattedHour = hour > 12 ? hour - 12 : hour;
						const formattedMinute = minute === 0 ? '00' : minute;
						const amPm = hour >= 12 ? 'PM' : 'AM';

						option.value = timeString;
						option.textContent = `${formattedHour}:${formattedMinute} ${amPm}`;
						appointmentTimeSelect.appendChild(option);
					}
				}
			}
		});

		appointmentDateInput.dispatchEvent(new Event("change"));
	});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?= base_url('disc/js/scripts.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="<?= base_url('disc/js/datatables-simple-demo.js') ?>"></script>
