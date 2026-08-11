<!-- Main Layout -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment</title>
    <!-- Include CSS libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
</head>
<body>
 </body>
 </html>
<div id="layoutSidenav_content">
    <main class="container mt-4">
        <div class="container h-10">
            <div class="row justify-content-center">
                <div class="col-lg-10 h-10">
                    <div>
                        <h1 class="mt-4">Set Appointment</h1>
                        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                        <?php echo form_open('appointments/create', ['class' => 'needs-validation', 'novalidate' => '']); ?>
                        <!-- Hidden input for patient_id -->
                        <input type="hidden" name="patient_id" value="<?php echo isset($patient_id) ? htmlspecialchars($patient_id) : ''; ?>">

                        <?php if ($this->session->flashdata('error_message')): ?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('error_message'); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Hidden input for patient_id -->
                        <input type="hidden" name="patient_id" value="<?php echo isset($patient) && $patient ? $patient['id'] : ''; ?>">

                        <div>
                            <p style="font-weight: bold; font-size: 40px;"><?php echo htmlspecialchars(ucwords($patient['name'] . ' ' . $patient['mname'] . ' ' . $patient['lname'])); ?></p>
                        </div>

                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Appointment Date</label>
                            <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
                            <div class="invalid-feedback">
                                Please select a date.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="appointment_time" class="form-label">Appointment Time</label>
                            <select name="appointment_time" id="appointment_time" class="form-control" required>
                                <option value="">Select a time slot</option>
                                <!-- Time slot options will be added dynamically via JavaScript -->
                            </select>
                            <div class="invalid-feedback">
                                Please select a time.
                            </div>
                        </div>

                        <?php
                        $user_level = $this->session->userdata('user_level'); // Get user_level from session
                        ?>

                        <?php if ($user_level === "doctor"): ?>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Doctor's Notes (Date)</label>
                                <input type="date" name="notes" id="notes" class="form-control" value="<?= isset($appointment['notes']) ? date('Y-m-d', strtotime($appointment['notes'])) : date('Y-m-d'); ?>" />
                            </div>
                        <?php endif; ?>

                        <?php 
$user_level = $this->session->userdata('user_level'); 
$allowed_roles = ['doctor', 'admin', 'secretary'];
?>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>

    <?php if (in_array($user_level, $allowed_roles)): ?>
        <!-- If user is doctor, admin, or secretary, show full dropdown -->
        <select name="status" id="status" class="form-control" required>
            <option value="">Select Status</option>
            <option value="pending">Pending</option>
            <option value="booked">Booked</option>
            <option value="cancelled">Cancelled</option>
        </select>
    <?php else: ?>
        <!-- If user is NOT doctor, admin, or secretary, set status to Pending automatically -->
        <input type="hidden" name="status" value="pending">
        <input type="text" class="form-control" value="Pending" disabled>
    <?php endif; ?>

    <div class="invalid-feedback">
        Please select a status.
    </div>
</div>

<div class="text-center mt-4">
    <button type="submit" class="btn btn-primary">Create Appointment</button>
</div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('#appointment_time').timepicker({
                timeFormat: 'hh:mm p',
                interval: 30, // Time intervals (30 mins)
                minTime: '12:00am',
                maxTime: '11:59pm',
                defaultTime: '09:00am',
                startTime: '12:00am',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });
    </script>

    <!-- Include JS libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('disc/js/scripts.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('disc/js/datatables-simple-demo.js') ?>"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const appointmentDateInput = document.getElementById("appointment_date");
        const appointmentTimeSelect = document.getElementById("appointment_time");

        // Set min date to today and set today's date as default
        const today = new Date();
        const philippinesTimeOffset = 8 * 60; // Offset in minutes for UTC+8
        today.setMinutes(today.getMinutes() + today.getTimezoneOffset() + philippinesTimeOffset);

        const todayString = today.toISOString().split("T")[0];
        appointmentDateInput.setAttribute("min", todayString);
        appointmentDateInput.value = todayString; // Set current date as default

        // List of excluded times in 'HH:MM' format
        const excludedTimes = ['11:30', '17:30'];

        function isExcludedTime(time) {
            return excludedTimes.includes(time);
        }

        appointmentDateInput.addEventListener("change", function() {
            const selectedDate = new Date(appointmentDateInput.value);
            const now = new Date();
            now.setMinutes(now.getMinutes() + now.getTimezoneOffset() + philippinesTimeOffset); // Convert current time to Philippine time

            // Clear previous options
            appointmentTimeSelect.innerHTML = "";

            // Generate time options in 30-minute intervals from the current time to 5:00 PM Philippine time
            if (selectedDate.toDateString() === now.toDateString()) {
                let startHour = now.getHours();
                let startMinute = now.getMinutes() >= 30 ? 30 : 0;
                const endHour = 17;

                for (let hour = startHour; hour <= endHour; hour++) {
                    for (let minute = startMinute; minute < 60; minute += 30) {
                        const timeString = `${hour.toString().padStart(2, '0')}:${minute === 0 ? '00' : minute}`;

                        // Skip excluded times
                        if (isExcludedTime(timeString)) continue;

                        const option = document.createElement("option");
                        const formattedHour = hour > 12 ? hour - 12 : hour; // Convert to 12-hour format
                        const formattedMinute = minute === 0 ? '00' : minute;
                        const amPm = hour >= 12 ? 'PM' : 'AM';

                        option.value = timeString; // Store as 'HH:MM'
                        option.textContent = `${formattedHour}:${formattedMinute} ${amPm}`; // Display in 12-hour format
                        appointmentTimeSelect.appendChild(option);
                    }
                    startMinute = 0; // Reset startMinute after the first hour
                }
            } else {
                // For other future dates, show all slots from 9:00 AM to 5:00 PM
                for (let hour = 9; hour <= 17; hour++) {
                    for (let minute = 0; minute < 60; minute += 30) {
                        const timeString = `${hour.toString().padStart(2, '0')}:${minute === 0 ? '00' : minute}`;

                        // Skip excluded times
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

        // Trigger change event to populate initial time slots
        appointmentDateInput.dispatchEvent(new Event("change"));
    });
    </script>
</div>
