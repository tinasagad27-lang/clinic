<div id="layoutSidenav_content">
    <div class="container">
        <h1 class="mb-4 text-center">Update Appointment</h1>
        <form action="<?= base_url('onlineappointments/online_update/' . $registration['id']); ?>" method="POST">
            <div class="row">
                <!-- First column -->
                <div class="col-md-6">
                    <div class="mb-4 p-3 border border-light rounded shadow-sm">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email', $registration['email']); ?>" placeholder="Enter email" required>
                    </div>
                    <div class="mb-4 p-3 border border-light rounded shadow-sm">
                        <label for="name" class="form-label">First Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= set_value('name', $registration['name']); ?>" placeholder="Enter first name" readonly>
                    </div>
                    <div class="mb-4 p-3 border border-light rounded shadow-sm">
                        <label for="mname" class="form-label">Middle Name:</label>
                        <input type="text" class="form-control" id="mname" name="mname" value="<?= set_value('mname', $registration['mname']); ?>" placeholder="Enter middle name" readonly>
                    </div>
                    <div class="mb-4 p-3 border border-light rounded shadow-sm">
                        <label for="lname" class="form-label">Last Name:</label>
                        <input type="text" class="form-control" id="lname" name="lname" value="<?= set_value('lname', $registration['lname']); ?>" placeholder="Enter last name" readonly>
                    </div>
                </div>

                <!-- Second column -->
                <div class="col-md-6">
                    <div class="mb-4 p-3 border border-light rounded shadow-sm">
                        <label for="doctor" class="form-label">Doctor's Name:</label>
                        <input type="text" class="form-control" id="doctor" name="doctor" value="<?= set_value('doctor', 'Dr. Chona Mendoza'); ?>" placeholder="Doctor" required>
                    </div>

                    <div class="mb-4 p-3 border border-light rounded shadow-sm">
                        <label for="marital_status" class="form-label">Marital Status:</label>
                        <select class="form-select" id="marital_status" name="marital_status" required>
                            <option value="single" <?= set_select('marital_status', 'single', $registration['marital_status'] == 'single'); ?>>Single</option>
                            <option value="married" <?= set_select('marital_status', 'married', $registration['marital_status'] == 'married'); ?>>Married</option>
                            <option value="divorced" <?= set_select('marital_status', 'divorced', $registration['marital_status'] == 'divorced'); ?>>Divorced</option>
                            <option value="widowed" <?= set_select('marital_status', 'widowed', $registration['marital_status'] == 'widowed'); ?>>Widowed</option>
                        </select>
                    </div>

                    <div class="mb-4 p-3 border border-light rounded shadow-sm">
                        <label for="appointment_date" class="form-label">Appointment Date:</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" value="<?= set_value('appointment_date', $registration['appointment_date']); ?>" required>
                    </div>

                    <div class="form-group">
    <label for="appointment_time">Appointment Time</label>
    <input type="time" class="form-control" id="appointment_time" name="appointment_time" 
           value="<?= !empty($registration['appointment_time']) ? date('H:i', strtotime($registration['appointment_time'])) : '' ?>" required>
</div>

                    <div class="mb-4 p-3 border border-light rounded shadow-sm">
                        <label for="status" class="form-label">Status:</label>
                        <select name="appointment_status" id="appointment_status" class="form-control" required>
                            <option value="">Select Status</option>
                            <?php
                            $user_level = $this->session->userdata('user_level');
                            if ($user_level == 'secretary' || $user_level == 'admin') {
                                // echo '<option value="pending" ' . set_select('appointment_status', 'pending', $registration['appointment_status'] == 'pending') . '>Pending</option>';
                                echo '<option value="booked" ' . set_select('appointment_status', 'booked', $registration['appointment_status'] == 'booked') . '>Booked</option>';
                                echo '<option value="follow_up" ' . set_select('appointment_status', 'follow_up', $registration['appointment_status'] == 'follow_up') . '>Follow Up</option>';
                                echo '<option value="reschedule" ' . set_select('appointment_status', 'reschedule', $registration['appointment_status'] == 'reschedule') . '>Reschedule</option>';
                                echo '<option value="reminder_sent" ' . set_select('appointment_status', 'reminder_sent', $registration['appointment_status'] == 'reminder_sent') . '>Reminder Sent</option>';
                                echo '<option value="cancelled" ' . set_select('appointment_status', 'cancelled', $registration['appointment_status'] == 'cancelled') . '>Cancelled</option>';
                            }
                            if ($user_level == 'admin') {
                                echo '<option value="follow_up" ' . set_select('appointment_status', 'follow_up', $registration['appointment_status'] == 'follow_up') . '>Follow Up</option>';
                                echo '<option value="reschedule" ' . set_select('appointment_status', 'reschedule', $registration['appointment_status'] == 'reschedule') . '>Reschedule</option>';
                                echo '<option value="completed" ' . set_select('appointment_status', 'completed', $registration['appointment_status'] == 'completed') . '>Completed</option>';
                            }
                            if ($user_level == 'doctor') {

                                echo '<option value="follow_up" ' . set_select('appointment_status', 'follow_up', $registration['appointment_status'] == 'follow_up') . '>Follow Up</option>';
                                echo '<option value="reschedule" ' . set_select('appointment_status', 'reschedule', $registration['appointment_status'] == 'reschedule') . '>Reschedule</option>';
                                echo '<option value="completed" ' . set_select('appointment_status', 'completed', $registration['appointment_status'] == 'completed') . '>Completed</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Next Check-Up Date (Visible to Admin & Doctor only) -->
                    <?php if ($user_level == 'admin' || $user_level == 'doctor') : ?>
                        <div class="mb-4 p-3 border border-light rounded shadow-sm" id="next_checkup_container" style="display: none;">
                            <label for="next_checkup_date" class="form-label">Next Check Up:</label>
                            <input type="datetime-local" class="form-control" id="next_checkup_date" name="next_checkup_date">
                        </div>
                    <?php endif; ?>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            var statusSelect = document.getElementById("appointment_status");
                            var nextCheckupContainer = document.getElementById("next_checkup_container");

                            statusSelect.addEventListener("change", function() {
                                if (this.value === "reschedule") {
                                    nextCheckupContainer.style.display = "block"; // Show if "Reschedule" is selected
                                } else {
                                    nextCheckupContainer.style.display = "none"; // Hide otherwise
                                }
                            });
                        });
                        document.addEventListener("DOMContentLoaded", function() {
                            var statusSelect = document.getElementById("appointment_status");
                            var nextCheckupContainer = document.getElementById("next_checkup_container");

                            statusSelect.addEventListener("change", function() {
                                if (this.value === "reschedule" || this.value === "follow_up") {
                                    nextCheckupContainer.style.display = "block"; // Show if "Reschedule" or "Follow Up" is selected
                                } else {
                                    nextCheckupContainer.style.display = "none"; // Hide otherwise
                                }
                            });
                        });
                    </script>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg" name="submit_action" value="save_and_email">Save and Email</button>
                    <!-- <button type="submit" class="btn btn-info btn-lg ms-3" name="submit_action" value="send_reminder">Send Reminder</button> -->
                    <a href="<?= base_url('dashboard/admin'); ?>" class="btn btn-danger btn-lg ms-3">Back</a>
                </div>
        </form>
    </div>
</div>



<!-- Script to populate time slots -->
<!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        const appointmentDateInput = document.getElementById('appointment_date');
        const appointmentTimeSelect = document.getElementById('appointment_time');

        const today = new Date();
        const todayString = today.toISOString().split('T')[0];
        appointmentDateInput.setAttribute('min', todayString);

        const lunchBreakSlots = [{
                hour: 11,
                minute: 30
            },
            {
                hour: 12,
                minute: 0
            },
            {
                hour: 17,
                minute: 0
            },
            {
                hour: 17,
                minute: 30
            },
            {
                hour: 15,
                minute: 30
            }
        ];

        function isLunchBreak(hour, minute) {
            return lunchBreakSlots.some(slot => slot.hour === hour && slot.minute === minute);
        }

        function populateTimeSlots() {
            const selectedDate = new Date(appointmentDateInput.value);
            const now = new Date();

            appointmentTimeSelect.innerHTML = "";

            if (selectedDate.toDateString() === now.toDateString()) {
                let startHour = now.getHours();
                let startMinute = now.getMinutes() >= 30 ? 30 : 0;
                const endHour = 17;

                for (let hour = startHour; hour <= endHour; hour++) {
                    for (let minute = startMinute; minute < 60; minute += 30) {
                        if (isLunchBreak(hour, minute)) continue;

                        const option = document.createElement("option");
                        const formattedHour = hour > 12 ? hour - 12 : hour;
                        const formattedMinute = minute === 0 ? '00' : minute;
                        const amPm = hour >= 12 ? 'PM' : 'AM';

                        option.value = `${hour.toString().padStart(2, '0')}:${formattedMinute}`;
                        option.textContent = `${formattedHour}:${formattedMinute} ${amPm}`;
                        appointmentTimeSelect.appendChild(option);
                    }
                    startMinute = 0;
                }
            } else {
                for (let hour = 9; hour <= 17; hour++) {
                    for (let minute = 0; minute < 60; minute += 30) {
                        if (isLunchBreak(hour, minute)) continue;

                        const option = document.createElement("option");
                        const formattedHour = hour > 12 ? hour - 12 : hour;
                        const formattedMinute = minute === 0 ? '00' : minute;
                        const amPm = hour >= 12 ? 'PM' : 'AM';

                        option.value = `${hour.toString().padStart(2, '0')}:${formattedMinute}`;
                        option.textContent = `${formattedHour}:${formattedMinute} ${amPm}`;
                        appointmentTimeSelect.appendChild(option);
                    }
                }
            }
        }

        appointmentDateInput.addEventListener("change", populateTimeSlots);
        populateTimeSlots();
    });
</script> -->