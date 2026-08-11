<link rel="icon" href="<?php echo base_url('assets/logo/favicon.ico'); ?>" type="image/gif">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
	.capitalize {
		text-transform: capitalize;
	}
</style>

<div id="layoutSidenav_content">
	<main>
		<div class="container-fluid px-4">
			<br>
			<div class="row mb-4">
				<div class="col-xl-4 col-md-6">
					<div class="card shadow bg-primary text-white mb-4">
						<div class="card-body d-flex align-items-center">
							<i class="fas fa-calendar-alt me-2 fa-2x"></i>
							<div>
								<h5 class="card-title">Appointments</h5>
								<p class="card-text"><?= $appointments_count + $onlineappointments_count; ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6">
					<div class="card shadow bg-warning text-white mb-4">
						<div class="card-body d-flex align-items-center">
							<i class="fas fa-medkit me-2 fa-2x"></i>
							<div>
								<h5 class="card-title">Initial Check up</h5>
								<p class="card-text"><?= $vitalsign_count; ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6">
					<div class="card shadow bg-success text-white mb-4">
						<div class="card-body d-flex align-items-center">
							<i class="fas fa-user-check me-2 fa-2x"></i>
							<div>
								<h5 class="card-title">Registration</h5>
								<p class="card-text"><?= $registration_count; ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-md-6">
					<div class="card shadow bg-success text-white mb-4">
						<div class="card-body d-flex align-items-center">
							<i class="fas fa-user-check me-2 fa-2x"></i>
							<div>
								<h5 class="card-title">Diagnosis</h5>
								<p class="card-text"><?= $findings_count; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container mt-4">
				<div class="row mb-4">
					<!-- Patients Statistics Chart -->
					<div class="col-md-6">
						<div class="card shadow mb-4">
							<div class="card-body">
								<h5 class="card-title">Patients Statistic</h5>
								<canvas id="linearChart"></canvas>
							</div>
						</div>
					</div>
					<div class="col-md-6">
    <!-- Doctor's Schedule Card -->
<div class="card shadow mb-4">
    <div class="card-header">
        <i class="fas fa-user-md me-1"></i> Appointments Schedule
    </div>
    <div class="card-body">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selectedDate = $_POST['appointment_date'];
            $bookedTimes = [];

            // Set timezone
            date_default_timezone_set('Asia/Manila');
            $currentTime = date('H:i');

            // Merge all booked appointments
            $allAppointments = array_merge($appointments, $onlineappointments, $registrations);

            // Collect Walk-In Appointments
            foreach ($appointments as $appointment) {
                $appointmentDateTime = new DateTime($appointment['appointment_date'] . ' ' . $appointment['appointment_time'], new DateTimeZone('Asia/Manila'));
                $status = $appointment['status'] ?? 'pending';

                // Skip past appointments and hidden statuses
                if ($appointmentDateTime->format('Y-m-d') == $selectedDate && !in_array($status, ['cancelled', 'completed', 'follow_up', 'reschedule'])) {
                    $bookedTimes[] = $appointmentDateTime->format('H:i');
                }
            }

            foreach ($allAppointments as $appointment) {
                if (is_object($appointment) && isset($appointment->appointment_date)) {
                    if (date('Y-m-d', strtotime($appointment->appointment_date)) == $selectedDate) {
                        $bookedTimes[] = date('H:i', strtotime($appointment->appointment_time));
                    }
                }
            }

            // Define clinic hours (9:00 AM - 5:00 PM, skipping lunch break)
            $clinicHours = [
                '09:00', '09:30', '10:00', '10:30', '11:00',
                '13:00', '13:30', '14:00', '14:30', '15:00',
                '15:30', '16:00', '16:30'
            ];

            // Remove past slots for today's availability
            if ($selectedDate == date('Y-m-d')) {
                $clinicHours = array_filter($clinicHours, function ($time) use ($currentTime) {
                    return $time >= $currentTime;
                });
            }

            echo "<div class='mb-3'><strong>Schedule for " . date('F d, Y', strtotime($selectedDate)) . "</strong></div>";
            echo "<div class='row'>";
            
            foreach ($clinicHours as $time) {
                $status = in_array($time, $bookedTimes) ? "Not Available" : "Available";
                $colorClass = in_array($time, $bookedTimes) ? "bg-danger text-white" : "bg-success text-white";

                echo "<div class='col-md-4 mb-2'>";
                echo "<div class='card $colorClass text-center p-2'>";
                echo "<strong>" . date('h:i A', strtotime($time)) . "</strong>";
                echo "<div>$status</div>";
                echo "</div>";
                echo "</div>";
            }

            echo "</div>";
        }
        ?>
    </div>
</div>


    <!-- Available Appointment Slots Form -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <i class="fas fa-calendar-check me-1"></i> Available Appointment Slots
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Select Appointment Date</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Check Slots</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



			<table class="table table-striped table-bordered table-hover" id="datatablesSimple">
				<h2>Appointments</h2>

				<!-- <button id="downloadCsv" class="btn btn-primary" onclick="downloadCsv()">Download CSV</button> -->

				<thead>
					<tr>
						<!-- <th>Patient ID</th> -->
						<th>Patient Name</th>
						<th>Appointment Date</th>
						<th>Appointment Time</th>
						<th>Type</th>
						<th>Status</th>
						<th>Actions</th> <!-- Added Actions column -->
					</tr>
				</thead>
				<tbody>

					<?php
					date_default_timezone_set('Asia/Manila');

					// Define status classes
					$statusClasses = [
						'booked' => 'bg-success',
						'pending' => 'bg-warning text-dark',
						'approved' => 'bg-info',
						'completed' => 'bg-secondary',
						'cancelled' => 'bg-danger',
						'declined' => 'bg-danger',
						'reschedule' => 'bg-secondary',
					];

					// Initialize an array to hold all appointments and registrations
					$allAppointments = [];
					$currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
					$cutoffTime = new DateTime('17:00', new DateTimeZone('Asia/Manila')); // Define cutoff time as 5 PM


					// Collect Walk-In Appointments
					foreach ($appointments as $appointment) {
						$appointmentDateTime = new DateTime($appointment['appointment_date'] . ' ' . $appointment['appointment_time'], new DateTimeZone('Asia/Manila'));
						$status = $appointment['status'] ?? 'pending';

						// Skip past appointments and hidden statuses
						if ($appointmentDateTime >= $currentDateTime && !in_array($status, ['cancelled', 'completed', 'follow_up', 'reschedule'])) {
							$formatted = formatDateTime($appointmentDateTime);
							$allAppointments[] = [
								'patient_id' => '', // No ID for walk-in appointments
								'patient_name' => htmlspecialchars($appointment['patient_name']),
								'date' => $formatted['date'],
								'time' => $formatted['time'],
								'type' => 'Walk-In',
								'status' => $status,
								'status_class' => $statusClasses[$status] ?? 'bg-secondary',
								'edit_link' => base_url('appointments/edit/' . $appointment['id']),
							];
						}
					}

					// Collect Online Appointments
					foreach ($onlineappointments as $onlineappointment) {
						$appointmentDateTime = new DateTime($onlineappointment['appointment_date'] . ' ' . $onlineappointment['appointment_time'], new DateTimeZone('Asia/Manila'));
						$status = $onlineappointment['STATUS'] ?? 'pending';

						// Skip past appointments and hidden statuses
						if ($appointmentDateTime >= $currentDateTime && !in_array($status, ['cancelled', 'completed', 'follow_up', 'reschedule'])) {
							$formatted = formatDateTime($appointmentDateTime);
							$allAppointments[] = [
								'patient_name' => htmlspecialchars($onlineappointment['firstname']) . ' ' . htmlspecialchars($onlineappointment['lastname']),
								'date' => $formatted['date'],
								'time' => $formatted['time'],
								'type' => 'Online',
								'status' => $status,
								'status_class' => $statusClasses[$status] ?? 'bg-secondary',
								'edit_link' => base_url('onlineappointments/edit/' . $onlineappointment['id']),
							];
						}
					}

					// Collect Registrations
					if (isset($registrations) && is_array($registrations) && !empty($registrations)) {
						foreach ($registrations as $registration) {
							if (!empty($registration->appointment_date) && !empty($registration->appointment_time)) {
								$appointmentDateTime = new DateTime($registration->appointment_date . ' ' . $registration->appointment_time, new DateTimeZone('Asia/Manila'));
								$isPast = $appointmentDateTime < $currentDateTime;

								// Display past appointments only if they have an edit link or if the status is completed or cancelled
								if (!$isPast || in_array($registration->appointment_status, ['booked', 'pending']) || isset($registration->edit_link)) {
									$formatted = formatDateTime($appointmentDateTime);
									$allAppointments[] = [
										'patient_name' => htmlspecialchars($registration->name . ' ' . $registration->mname . ' ' . $registration->lname),
										'date' => $formatted['date'],
										'time' => $formatted['time'],
										'type' => 'Online',
										'status' => htmlspecialchars($registration->appointment_status),
										'status_class' => $statusClasses[$registration->appointment_status] ?? 'bg-secondary',
										'edit_link' => base_url('onlineappointments/online_edit/' . $registration->id),
										'is_past' => $isPast
									];
								}
							}
						}
					}

					// Sort all appointments by date and time in ascending order
					usort($allAppointments, function ($a, $b) {
						return ($a['date'] . ' ' . $a['time']) <=> ($b['date'] . ' ' . $b['time']);
					});
					$user_level = $this->session->userdata('user_level'); // Get the user level from the session

					// Display all appointments
					if (!empty($allAppointments)) {
						foreach ($allAppointments as $appointment):
							$appointmentTime = new DateTime($appointment['date'] . ' ' . $appointment['time'], new DateTimeZone('Asia/Manila'));

							// Check if the appointment date is in the past
							if ($appointmentTime < $currentDateTime) {
								continue; // Hide past appointments
							}

							// Check if user is doctor or secretary and hide specific statuses
if (($user_level === 'doctor' || $user_level === 'secretary') && in_array($appointment['status'], ['follow_up', 'reschedule'])) {
    continue; // Hide appointments with 'follow_up' or 'reschedule' statuses for doctors and secretaries
}

// Apply role-specific filtering for doctor and secretary on other statuses
if ($user_level === 'doctor' && in_array($appointment['status'], ['completed', 'cancelled', 'pending'])) {
    continue; // Hide "completed", "cancelled", and "pending" for doctors
}

if ($user_level === 'secretary' && in_array($appointment['status'], ['completed', 'cancelled', 'follow_up', 'reschedule'])) {
    continue; // Hide "completed", "cancelled", "follow_up", and "reschedule" for secretaries
}


							// Admin sees everything
					?>
							<tr class="<?= $appointment['status_class']; ?>">
								<td class="capitalize"><?= $appointment['patient_name']; ?></td>
								<td class="capitalize"><?= $appointment['date']; ?></td>
								<td class="capitalize"><?= $appointment['time']; ?></td>
								<td class="capitalize"><?= $appointment['type']; ?></td>

								<td>
									<span class="badge <?= $appointment['status_class']; ?>">
										<?= ucfirst($appointment['status']); ?>
									</span>
								</td>
								<td>
									<?php if ($appointment['edit_link']): ?>
										<a href="<?= $appointment['edit_link']; ?>" class="btn btn-warning btn-sm">Update Status</a>
									<?php else: ?>
										<button class="btn btn-secondary btn-sm" disabled>No Action</button>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach;
					} else { ?>
						<tr>
							<td colspan="7">No appointments or registrations found.</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			
			<table class="table table-striped table-bordered table-hover" id="datatablesSimple">
				
				<h2>
					<?php echo (isset($allAppointments) && !empty($allAppointments) && in_array('reschedule', array_column($allAppointments, 'status')) || in_array('follow_up', array_column($allAppointments, 'status'))) ? 'Reschedule and Follow-Up Appointments' : 'Re-Appointments'; ?>
				</h2>

				<!-- <button id="downloadCsv" class="btn btn-primary" onclick="downloadCsv()">Download CSV</button> -->

				<thead>
					<tr>
						<th style="width: 20%;">Patient Name</th>
						<th style="width: 20%;">Appointment Date</th>
						<th style="width: 15%;">Appointment Time</th>
						<th style="width: 15%;">Type</th>
						<th style="width: 15%;">Status</th>
						<th style="width: 15%;">Actions</th> <!-- Added Actions column -->
					</tr>
				</thead>
				<tbody>

					<?php
					date_default_timezone_set('Asia/Manila');

					// Define status classes
					$statusClasses = [
						'booked' => 'bg-success',
						'pending' => 'bg-warning text-dark',
						'approved' => 'bg-info',
						'completed' => 'bg-secondary',
						'cancelled' => 'bg-danger',
						'declined' => 'bg-danger',
						'reschedule' => 'bg-secondary',
					];

					// Initialize an array to hold all appointments and registrations
					$allAppointments = [];
					$currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
					$cutoffTime = new DateTime('17:00', new DateTimeZone('Asia/Manila')); // Define cutoff time as 5 PM

					// Helper function to format date and time
					function formatDateTime($dateTime)
					{
						return [
							'date' => $dateTime->format('F d, Y'), // Full month name, day, year
							'time' => $dateTime->format('g:i A'), // 12-hour format with AM/PM
						];
					}

					// Collect Walk-In Appointments
					foreach ($appointments as $appointment) {
						$appointmentDateTime = new DateTime($appointment['appointment_date'] . ' ' . $appointment['appointment_time'], new DateTimeZone('Asia/Manila'));
						$status = $appointment['status'] ?? 'pending';

						// Only include reschedule and follow_up appointments
						if (in_array($status, ['reschedule', 'follow_up'])) {
							$formatted = formatDateTime($appointmentDateTime);
							$allAppointments[] = [
								'patient_id' => '', // No ID for walk-in appointments
								'patient_name' => htmlspecialchars($appointment['patient_name']),
								'date' => $formatted['date'],
								'time' => $formatted['time'],
								'type' => 'Walk-In',
								'status' => $status,
								'status_class' => $statusClasses[$status] ?? 'bg-secondary',
								'edit_link' => base_url('appointments/edit/' . $appointment['id']),
							];
						}
					}

					// Collect Online Appointments
					foreach ($onlineappointments as $onlineappointment) {
						$appointmentDateTime = new DateTime($onlineappointment['appointment_date'] . ' ' . $onlineappointment['appointment_time'], new DateTimeZone('Asia/Manila'));
						$status = $onlineappointment['STATUS'] ?? 'pending';

						// Only include reschedule and follow_up appointments
						if (in_array($status, ['reschedule', 'follow_up'])) {
							$formatted = formatDateTime($appointmentDateTime);
							$allAppointments[] = [
								'patient_name' => htmlspecialchars($onlineappointment['firstname']) . ' ' . htmlspecialchars($onlineappointment['lastname']),
								'date' => $formatted['date'],
								'time' => $formatted['time'],
								'type' => 'Online',
								'status' => $status,
								'status_class' => $statusClasses[$status] ?? 'bg-secondary',
								'edit_link' => base_url('onlineappointments/edit/' . $onlineappointment['id']),
							];
						}
					}

					// Collect Registrations
					if (isset($registrations) && is_array($registrations) && !empty($registrations)) {
						foreach ($registrations as $registration) {
							if (!empty($registration->appointment_date) && !empty($registration->appointment_time)) {
								$appointmentDateTime = new DateTime($registration->appointment_date . ' ' . $registration->appointment_time, new DateTimeZone('Asia/Manila'));
								$isPast = $appointmentDateTime < $currentDateTime;

								// Only include reschedule and follow_up appointments
								if (in_array($registration->appointment_status, ['reschedule', 'follow_up'])) {
									$formatted = formatDateTime($appointmentDateTime);
									$allAppointments[] = [
										'patient_name' => htmlspecialchars($registration->name . ' ' . $registration->mname . ' ' . $registration->lname),
										'date' => $formatted['date'],
										'time' => $formatted['time'],
										'type' => 'Online',
										'status' => htmlspecialchars($registration->appointment_status),
										'status_class' => $statusClasses[$registration->appointment_status] ?? 'bg-secondary',
										'edit_link' => base_url('onlineappointments/online_edit/' . $registration->id),
										'is_past' => $isPast
									];
								}
							}
						}
					}

					// Sort all appointments by date and time in ascending order
					usort($allAppointments, function ($a, $b) {
						return ($a['date'] . ' ' . $a['time']) <=> ($b['date'] . ' ' . $b['time']);
					});
					$user_level = $this->session->userdata('user_level'); // Get the user level from the session

					// Display all appointments
					if (!empty($allAppointments)) {
						foreach ($allAppointments as $appointment):
							$appointmentTime = new DateTime($appointment['date'] . ' ' . $appointment['time'], new DateTimeZone('Asia/Manila'));

							// // Check if current time is past 5 PM
							// if ($currentDateTime >= $cutoffTime) {
							// 	continue; // Hide all entries after 5 PM
							// }

							// Apply role-specific filtering
							if ($user_level === 'doctor' && in_array($appointment['status'], ['completed', 'cancelled', 'pending'])) {
								continue; // Hide "completed" and "cancelled" for doctors
							}

							if ($user_level === 'secretary' && in_array($appointment['status'], ['completed', 'cancelled'])) {
								continue; // Hide "completed" and "cancelled" for secretaries
							}

							if ($user_level === 'secretary' && !in_array($appointment['status'], ['reschedule', 'follow_up'])) {
								continue; // Only show "reschedule" and "follow_up" for nurses
							}

							// Admin sees everything
					?>
							<tr class="<?= $appointment['status_class']; ?>">
								<td class="capitalize"><?= $appointment['patient_name']; ?></td>
								<td class="capitalize"><?= $appointment['date']; ?></td>
								<td class="capitalize"><?= $appointment['time']; ?></td>
								<td class="capitalize"><?= $appointment['type']; ?></td>

								<td>
									<span class="badge <?= $appointment['status_class']; ?>">
										<?= ucfirst($appointment['status']); ?>
									</span>
								</td>
								<td>
									<?php if ($appointment['edit_link']): ?>
										<a href="<?= $appointment['edit_link']; ?>" class="btn btn-warning btn-sm">Update Status</a>
									<?php else: ?>
										<button class="btn btn-secondary btn-sm" disabled>No Action</button>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach;
					} else { ?>
						<tr>
							<td colspan="6">No appointments or registrations found.</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>




		</div>
	</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
	$(document).ready(function() {
		// Fetch the chart data via AJAX
		$.ajax({
			url: '<?= site_url("dashboard/admin/get_chart_data"); ?>', // URL for the get_chart_data method
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				// Ensure that the data returned is valid
				if (data) {
					const currentMonth = data.current_month || "Unknown"; // Default to "Unknown" if no current month is available
					const appointmentsCount = data.appointments_count || 0; // Default to 0 if no data
					// const onlineAppointmentsCount = data.onlineappointments_count || 0; // Default to 0 if no data
					const registrationCount = data.registration_count || 0; // Default to 0 if no data

					// Create the chart
					const linearCtx = document.getElementById('linearChart').getContext('2d');

					const chartData = {
						labels: [currentMonth], // Only show the current month
						datasets: [{
								label: 'Appointments',
								data: [appointmentsCount],
								backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue fill
								borderColor: 'rgba(54, 162, 235, 1)', // Blue line
								borderWidth: 2,
								fill: false, // No fill for bar chart
							},
							{
								label: 'Online Appointments',
								data: [registrationCount],
								backgroundColor: 'rgba(255, 99, 132, 0.2)', // Light red fill
								borderColor: 'rgba(255, 99, 132, 1)', // Red line
								borderWidth: 2,
								fill: false, // No fill for bar chart
							},
							{
								label: 'Registration',
								data: [registrationCount],
								backgroundColor: 'rgba(255, 206, 86, 0.2)', // Light yellow fill
								borderColor: 'rgba(255, 206, 86, 1)', // Yellow line
								borderWidth: 2,
								fill: false, // No fill for bar chart
							}
						]
					};

					const linearChart = new Chart(linearCtx, {
						type: 'bar', // Change the chart type to 'bar'
						data: chartData,
						options: {
							scales: {
								y: {
									beginAtZero: true,
									title: {
										display: true,
										text: 'Count'
									}
								},
								x: {
									title: {
										display: true,
										text: 'Month'
									}
								}
							},
							responsive: true,
							plugins: {
								legend: {
									display: true,
									position: 'top',
								},
								title: {
									display: true,
									text: 'Overview for ' + currentMonth
								}
							}
						}
					});
				} else {
					console.log("No data received.");
				}
			},
			error: function(err) {
				console.log('Error fetching chart data:', err);
			}
		});
	});
</script>


<script>
	$(document).ready(function() {
		$('#datatablesSimple').DataTable({
			"rowCallback": function(row, data, index) {
				var status = data[4]; // Adjust based on your actual data
				// Check your condition to apply class
				if (status === 'booked') { // Example condition
					$(row).addClass('table-danger');
				}
			}
		});
	});
</script>
<!-- <div id="layoutSidenav_content">
<table class="table table-striped table-bordered table-hover" id="datatablesSimple">
    <thead>
        <tr>
            <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Appointment Date</th>
            <th>Appointment Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (isset($registrations) && is_array($registrations) && !empty($registrations)) : ?>
            <?php foreach ($registrations as $registration): ?>
                <tr>
                    <td><?= isset($registration->id) ? htmlspecialchars(str_pad($registration->id, 4, '0', STR_PAD_LEFT)) : 'No ID'; ?></td>
                    <td><?= htmlspecialchars($registration->name . ' ' . $registration->mname . ' ' . $registration->lname); ?></td> 
                    <td><?= htmlspecialchars($registration->appointment_date); ?></td>
                    <td><?= htmlspecialchars($registration->appointment_time); ?></td>
                    <td><?= htmlspecialchars($registration->appointment_status); ?></td> 
                   
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6">No registrations found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div> -->
<!-- Removed duplicate formatDateTime function -->
<script>
	function downloadCsv() {
		// Get the table element
		var table = document.getElementById("datatablesSimple");

		// Initialize an array to hold CSV data
		var csvData = [];

		// Get the header row and add it to the CSV data
		var headers = [];
		var headerCells = table.getElementsByTagName('thead')[0].getElementsByTagName('th');
		for (var i = 0; i < headerCells.length; i++) {
			headers.push(headerCells[i].textContent);
		}
		csvData.push(headers.join(',')); // Join headers with commas

		// Get all the rows and add them to the CSV data
		var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
		for (var i = 0; i < rows.length; i++) {
			var rowData = [];
			var cells = rows[i].getElementsByTagName('td');
			for (var j = 0; j < cells.length; j++) {
				rowData.push(cells[j].textContent.trim()); // Collect the text of each cell
			}
			csvData.push(rowData.join(',')); // Join row data with commas
		}

		// Create a CSV file and trigger the download
		var csvFile = new Blob([csvData.join('\n')], {
			type: 'text/csv'
		});
		var downloadLink = document.createElement('a');
		downloadLink.href = URL.createObjectURL(csvFile);
		downloadLink.download = 'appointments_report.csv';
		downloadLink.click();

	}
</script>

<!-- Include your script files here -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?= base_url('disc/js/scripts.js') ?>"></script>
<script src="https://cdnjs.cloudflare .com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/chart-area-demo.js"></script>
<script src="assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<!-- <script src="<?= base_url('disc/js/datatables-simple-demo.js') ?>"></script> -->
