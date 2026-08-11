<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration Form</title>

    <style>
        body {
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-section {
            margin-bottom: 30px;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            font-weight: bold;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .grid-item {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            text-transform: capitalize;
        }
    </style>
</head>

<body>
    <div id="layoutSidenav_content">
        <main>
            <?php echo form_open('registration/submit', ['class' => 'form-horizontal']); ?>
            <div class="container mt-4">
                <h1 class="text-center mb-4 text-success">Patient Registration</h1>

                <?php if ($this->session->flashdata('upld_err')): ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('upld_err') ?></div>
                <?php endif; ?>

                <!-- Patient Information Section -->
                <div class="card p-4 mb-4 shadow-sm">
                    <h5 class="card-title bg-success text-white p-2 rounded">Patient Information</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="name">First Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="mname">Middle Name:</label>
                            <input type="text" class="form-control" id="mname" name="mname">
                        </div>
                        <div class="col-md-4">
                            <label for="lname">Last Name:</label>
                            <input type="text" class="form-control" id="lname" name="lname" required>
                        </div>
                        <div class="col-md-4">
                            <label for="marital_status">Marital Status:</label>
                            <select class="form-control" id="marital_status" name="marital_status" required>
                                <option value="">Select</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="birthday">Birthday:</label>
                            <input type="date" class="form-control" id="birthday" name="birthday" required>
                        </div>
                        <div class="col-md-4">
                            <label for="age">Age:</label>
                            <input type="text" class="form-control" id="age" name="age" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="address">Address:</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="col-md-6">
                            <label for="patient_contact_no">Patient's Contact No:</label>
                            <input type="text" class="form-control" id="patient_contact_no" name="patient_contact_no">
                        </div>
                        <div class="col-md-6">
                            <label for="philhealth_id">PhilHealth ID (if any):</label>
                            <input type="number" class="form-control" id="philhealth_id" name="philhealth_id">
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" style="text-transform: lowercase;">
                        </div>

                        <script>
                            document.getElementById("email").addEventListener("input", function() {
                                this.value = this.value.toLowerCase();
                            });
                        </script>

                    </div>
                </div>

                <!-- Guardian Information Section -->
                <div class="card p-4 mb-4 shadow-sm">
                    <h5 class="card-title bg-success text-white p-2 rounded">Guardian Information</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="husband">Name of Guardian:</label>
                            <input type="text" class="form-control" id="husband" name="husband">
                        </div>
                        <div class="col-md-4">
                            <label for="husband_phone">Contact Number:</label>
                            <input type="text" class="form-control" id="husband_phone" name="husband_phone">
                        </div>
                        <div class="col-md-4">
                            <label for="occupation">Relation to the Patient:</label>
                            <input type="text" class="form-control" id="occupation" name="occupation">
                        </div>
                    </div>
                </div>

                <!-- Doctor Name Section -->
                <div class="card p-4 mb-4 shadow-sm">
                    <h5 class="card-title bg-success text-white p-2 rounded">Doctor's Name</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="doctor">Doctor's Name:</label>
                            <input type="text" class="form-control" id="doctor" name="doctor" value="Dr. Chona Mendoza">

                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success px-4">Next</button>
                    <button type="button" class="btn btn-secondary px-4" onclick="window.location.href='<?php echo site_url('dashboard/admin'); ?>'">Back</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </main>
    </div>
</body>


</body>
<script>
    document.getElementById('birthday').addEventListener('change', function() {
        const birthdayInput = this.value;
        const birthday = new Date(birthdayInput);
        const today = new Date();

        let age = today.getFullYear() - birthday.getFullYear();

        const monthDiff = today.getMonth() - birthday.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
            age--;
        }


        const ageInput = document.getElementById('age');
        ageInput.value = age;


        if (age < 0) {
            ageInput.style.color = 'red';
        } else {
            ageInput.style.color = '';
        }
    });
</script>

</html>