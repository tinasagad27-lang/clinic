<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Schedule Reports</title>
    <link rel="icon" href="<?php echo base_url('assets/logo/favicon.ico'); ?>" type="image/gif">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        .date-picker-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .date-picker-container input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        .date-picker-container button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .date-picker-container button:hover {
            background-color: #0056b3;
        }

        h2 {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        #reportSection {
            display: none; /* Hide report section initially */
        }

        @media print {
            .date-picker-container, #downloadCsv {
                display: none;
            }
        }

        .report-type-container {
            margin-bottom: 20px;
        }

        .report-type-container select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
	<header style="display: flex; align-items: center; justify-content: flex-start;">
    <img src="<?php echo base_url('assets/logo/logo.png'); ?>" alt="Clinic Logo" style="max-width: 100px; margin-right: 130px;">
    <h1 style="text-align: center; margin: 0;">Clinic Schedule Reports</h1>

</header>


        <div class="date-picker-container">
            <div class="mb-3">
                <label for="startDate" class="form-label">Start Date</label>
                <input type="date" id="startDate" class="form-control" required onchange="updateDate()">
            </div>

            <div class="mb-3">
                <label for="endDate" class="form-label">End Date</label>
                <input type="date" id="endDate" class="form-control" required onchange="updateDate()">
            </div>

            <button onclick="printReport()">Print Report</button>
            <button id="downloadCsv" onclick="downloadCsv()">Download CSV</button>
        </div>

        <div class="report-type-container">
            <label for="reportType" class="form-label">Report</label>
            <select id="reportType" class="form-control" onchange="updateReportType()">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>

        <section id="reportSection">
		<?php if (
    isset($dailyRegistrations) || isset($weeklyRegistrations) || isset($monthlyRegistrations) ||
    isset($dailyLabTests) || isset($weeklyLabTests) || isset($monthlyLabTests)
): ?>

    <!-- Daily Report -->
    <div id="dailyReport">
        <h1>Daily Report</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Fullname</th>
                    <th>Date and Time</th>
                    <th>Doctor</th>
                    <th>Service Type</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $dailyOnlineCount = 0;

                    if (isset($dailyRegistrations)) {
                        foreach ($dailyRegistrations as $registration): 
                            $dailyOnlineCount++;
                ?>
                    <tr>
                        <td><?php echo ucwords($registration->name . ' ' . $registration->mname . ' ' . $registration->lname); ?></td>
                        <td><?php echo date('F j, Y, g:i A', strtotime($registration->created_at)); ?></td>
                        <td><?php echo isset($registration->doctor) ? ucwords($registration->doctor) : 'N/A'; ?></td>
                        <td><?php echo isset($registration->service_type) ? ucwords($registration->service_type) : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>
        <p><strong>Total Appointments (Daily):</strong> <?php echo $dailyOnlineCount; ?></p>
    </div>

    <!-- Weekly Report -->
    <div id="weeklyReport">
        <h1>Weekly Report</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Fullname</th>
                    <th>Date and Time</th>
                    <th>Doctor</th>
                    <th>Service Type</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $weeklyOnlineCount = 0;

                    if (isset($weeklyRegistrations)) {
                        foreach ($weeklyRegistrations as $registration): 
                            $weeklyOnlineCount++;
                ?>
                    <tr>
                        <td><?php echo ucwords($registration->name . ' ' . $registration->mname . ' ' . $registration->lname); ?></td>
                        <td><?php echo date('F j, Y, g:i A', strtotime($registration->created_at)); ?></td>
                        <td><?php echo isset($registration->doctor) ? ucwords($registration->doctor) : 'N/A'; ?></td>
                        <td><?php echo isset($registration->service_type) ? ucwords($registration->service_type) : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>
        <p><strong>Total Appointments (Weekly):</strong> <?php echo $weeklyOnlineCount; ?></p>
    </div>

    <!-- Monthly Report -->
    <div id="monthlyReport">
        <h1>Monthly Report</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>Fullname</th>
                    <th>Date and Time</th>
                    <th>Doctor</th>
                    <th>Service Type</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $monthlyOnlineCount = 0;

                    if (isset($monthlyRegistrations)) {
                        foreach ($monthlyRegistrations as $registration): 
                            $monthlyOnlineCount++;
                ?>
                    <tr>
                        <td><?php echo ucwords($registration->name . ' ' . $registration->mname . ' ' . $registration->lname); ?></td>
                        <td><?php echo date('F j, Y, g:i A', strtotime($registration->created_at)); ?></td>
                        <td><?php echo isset($registration->doctor) ? ucwords($registration->doctor) : 'N/A'; ?></td>
                        <td><?php echo isset($registration->service_type) ? ucwords($registration->service_type) : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>
        <p><strong>Total Appointments (Monthly):</strong> <?php echo $monthlyOnlineCount; ?></p>
    </div>

<?php else: ?>
    <p>No reports available. Please select a date range to filter the data.</p>
<?php endif; ?>

</section>


    </div>

    <script>
        function updateDate() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            if (startDate && endDate) {
                document.getElementById('reportSection').style.display = 'block';
                const url = new URL(window.location.href);
                url.searchParams.set('startDate', startDate);
                url.searchParams.set('endDate', endDate);
                window.history.replaceState({}, '', url);
            } else {
                document.getElementById('reportSection').style.display = 'none';
            }
        }

        function printReport() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            if (!startDate || !endDate) {
                alert('Please select a start and end date to print the report.');
                return;
            }
            document.querySelector('.date-picker-container').style.display = 'none';
            document.getElementById('downloadCsv').style.display = 'none';
            window.print();
            document.querySelector('.date-picker-container').style.display = 'flex';
            document.getElementById('downloadCsv').style.display = 'inline-block';
        }

        function downloadCsv() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            if (!startDate || !endDate) {
                alert('Please select a start and end date to download the CSV.');
                return;
            }
            window.location.href = "<?php echo site_url('ReportController/export_to_csv/'); ?>" + startDate + '/' + endDate;
        }

        function updateReportType() {
            const reportType = document.getElementById('reportType').value;
            document.getElementById('dailyReport').style.display = reportType === 'daily' ? 'block' : 'none';
            document.getElementById('weeklyReport').style.display = reportType === 'weekly' ? 'block' : 'none';
            document.getElementById('monthlyReport').style.display = reportType === 'monthly' ? 'block' : 'none';
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const startDate = urlParams.get('startDate');
            const endDate = urlParams.get('endDate');
            if (startDate) {
                document.getElementById('startDate').value = startDate;
            }
            if (endDate) {
                document.getElementById('endDate').value = endDate;
            }
            updateDate();
            updateReportType();
        };
    </script>
</body>
</html>
