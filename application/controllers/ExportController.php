<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExportController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Appointment_model');
        $this->load->model('OnlineAppointments_model');
        $this->load->model('Registration_model');
        $this->load->model('Diagnosis_model');
    }
    private function set_headers($filename, $type)
    {
        if ($type === 'csv') {
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=$filename");
        } elseif ($type === 'excel') {
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=$filename");
        }
        header("Pragma: no-cache");
        header("Expires: 0");
    }

    private function output_csv($data, $headers)
    {
        $output = fopen("php://output", "w");
        fputcsv($output, $headers);
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    }

    private function output_excel($data, $headers)
    {
        echo "<table border='1'><tr>";
        foreach ($headers as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr>";
        foreach ($data as $row) {
            echo "<tr>";
            foreach ($row as $cell) {
                echo "<td>" . htmlspecialchars($cell) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        exit();
    }

    // Export Walk-In Appointments
    public function walkin($type = 'csv')
    {
        $appointments = $this->Appointment_model->get_appointments();
        $filename = "walkin_appointments_" . date('Ymd') . ".$type";

        $headers = ['Patient Name', 'Appointment Date', 'Appointment Time', 'Doctor', 'Status'];
        $data = array_map(function($appointment) {
            return [
                $appointment['patient_name'],
                $appointment['appointment_date'],
                $appointment['appointment_time'],
                $appointment['doctor'],
                $appointment['status']
            ];
        }, $appointments);

        $this->set_headers($filename, $type);
        if ($type === 'csv') {
            $this->output_csv($data, $headers);
        } elseif ($type === 'excel') {
            $this->output_excel($data, $headers);
        }
    }

    // Export Online Appointments
    public function online($type = 'csv')
    {
        $onlineappointments = $this->OnlineAppointments_model->get_all_onlineappointments();
        $filename = "online_appointments_" . date('Ymd') . ".$type";

        $headers = ['First Name', 'Last Name', 'Email', 'Contact Number', 'Appointment Date', 'Appointment Time', 'Status'];
        $data = array_map(function($onlineappointment) {
            return [
                $onlineappointment['firstname'],
                $onlineappointment['lastname'],
                $onlineappointment['email'],
                $onlineappointment['contact_number'],
                $onlineappointment['appointment_date'],
                $onlineappointment['appointment_time'],
                $onlineappointment['status']
            ];
        }, $onlineappointments);

        $this->set_headers($filename, $type);
        if ($type === 'csv') {
            $this->output_csv($data, $headers);
        } elseif ($type === 'excel') {
            $this->output_excel($data, $headers);
        }
    }


    // Export Walk-In Appointments CSV
    public function walkin_csv()
    {
        $appointments = $this->Appointment_model->get_appointments(); // Fetch walk-in appointment data

        $filename = 'walkin_appointments_' . date('Ymd') . '.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        // Header row
        fputcsv($output, array('Patient Name', 'Appointment Date', 'Appointment Time', 'Doctor', 'Status'));

        // Data rows
        foreach ($appointments as $appointment) {
            fputcsv($output, array(
                $appointment['patient_name'],
                $appointment['appointment_date'],
                $appointment['appointment_time'],
                $appointment['doctor'],
                $appointment['status']
            ));
        }

        fclose($output);
        exit();
    }

    // Export Walk-In Appointments Excel
    public function walkin_excel()
    {
        $appointments = $this->Appointment_model->get_appointments(); // Fetch walk-in appointment data

        $filename = 'walkin_appointments_' . date('Ymd') . '.xls';
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<tr><th>Patient Name</th><th>Appointment Date</th><th>Appointment Time</th><th>Doctor</th><th>Status</th></tr>";

        foreach ($appointments as $appointment) {
            echo "<tr>";
            echo "<td>{$appointment['patient_name']}</td>";
            echo "<td>{$appointment['appointment_date']}</td>";
            echo "<td>{$appointment['appointment_time']}</td>";
            echo "<td>{$appointment['doctor']}</td>";
            echo "<td>{$appointment['status']}</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit();
    }
    // Export CSV
    public function export_csv()
    {
        $appointments = $this->Appointment_model->get_appointments(); // Fetch data

        $filename = 'appointments_' . date('Ymd') . '.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        // Header row
        fputcsv($output, array('Patient Name', 'Appointment Date', 'Appointment Time', 'Doctor', 'Status'));

        // Data rows
        foreach ($appointments as $appointment) {
            fputcsv($output, array(
                // $appointment['id'],
                $appointment['patient_name'],
                // Uncomment this line if 'custom_id' exists in the appointment data
                // $appointment['custom_id'], 
                $appointment['appointment_date'],
                $appointment['appointment_time'],
                $appointment['doctor'],
                $appointment['status']
            ));
        }

        fclose($output);
        exit();
    }


    public function export_excel()
    {
        $appointments = $this->Appointment_model->get_appointments();

        $filename = 'appointments_' . date('Ymd') . '.xls';
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<tr><th>Patient Name</th><th>Appointment Date</th><th>Appointment Time</th><th>Doctor</th><th>Status</th></tr>";

        foreach ($appointments as $appointment) {
            echo "<tr>";
            // echo "<td>{$appointment['id']}</td>";
            echo "<td>{$appointment['patient_name']}</td>";
            // Uncomment this line if 'custom_id' exists in the appointment data
            // echo "<td>{$appointment['custom_id']}</td>";
            echo "<td>{$appointment['appointment_date']}</td>";
            echo "<td>{$appointment['appointment_time']}</td>";
            echo "<td>{$appointment['doctor']}</td>";
            echo "<td>{$appointment['status']}</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit();
    }
    public function online_csv()
    {
        $onlineappointments = $this->OnlineAppointments_model->get_all_onlineappointments();

        $filename = 'online_appointments_' . date('Ymd') . '.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");


        fputcsv($output, array('First Name', 'Last Name', 'Email', 'Contact Number', 'Appointment Date', 'Appointment Time', 'Status'));


        foreach ($onlineappointments as $onlineappointment) {
            fputcsv($output, array(
                // $onlineappointment['id'],
                $onlineappointment['firstname'],
                $onlineappointment['lastname'],
                $onlineappointment['email'],
                $onlineappointment['contact_number'],
                $onlineappointment['appointment_date'],
                $onlineappointment['appointment_time'],
                $onlineappointment['status']
            ));
        }

        fclose($output);
        exit();
    }

    public function online_excel()
    {
        $onlineappointments = $this->OnlineAppointments_model->get_all_onlineappointments();

        $filename = 'online_appointments_' . date('Ymd') . '.xls';
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<tr><th>First Name</th><th>Last Name</th><th>Email</th><th>Birthday</th><th>Appointment Date</th><th>Appointment Time</th><th>Status</th></tr>";

        foreach ($onlineappointments as $onlineappointment) {
            echo "<tr>";
            // echo "<td>{$onlineappointment['id']}</td>";
            echo "<td>{$onlineappointment['firstname']}</td>";
            echo "<td>{$onlineappointment['lastname']}</td>";
            echo "<td>{$onlineappointment['email']}</td>";
            echo "<td>{$onlineappointment['contact_number']}</td>";
            echo "<td>{$onlineappointment['appointment_date']}</td>";
            echo "<td>{$onlineappointment['appointment_time']}</td>";
            echo "<td>{$onlineappointment['status']}</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit();
    }
    public function export_registration_csv()
    {
        $this->load->model('Registration_model');
        $registrations = $this->Registration_model->get_all_registrations(); // Fetch data

        $filename = 'registrations_' . date('Ymd') . '.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        // Header row
        fputcsv($output, array(
            'Name',
            'Middle Name',
            'Last Name',
            'Marital Status',
            'Patient Contact No',
            'Age',
            'Philhealth ID',
            'Birthday',
            'Address',
            'Guardian Name',
            'Relation to Patient',
            'Guardian Contact Number',
            'Number of Pregnancies',
            'Last Menstrual Date',
            'Age of Gestation',
            'Expected Date of Confinement',
            'Patient Record Date',
            'Patient Record Update'
        ));


        // Data rows
        foreach ($registrations as $registration) {
            fputcsv($output, array(
                $registration['name'],
                $registration['mname'],
                $registration['lname'],
                $registration['marital_status'],
                $registration['patient_contact_no'],
                $registration['age'],
                $registration['philhealth_id'],
                $registration['birthday'],
                $registration['address'],
                $registration['husband'],
                $registration['occupation'],
                $registration['husband_phone'],
                // $registration['no_of_pregnancy'],
                // $registration['last_menstrual'],
                // $registration['age_gestation'],
                // $registration['expected_date_confinement'],
                $registration['created_at'],
                $registration['last_update']
            ));
        }

        fclose($output);
        exit();
    }

    public function export_registration_excel()
{
    $this->load->model('Registration_model');
    $registrations = $this->Registration_model->get_all_registrations(); // Fetch data

    $filename = 'registrations_' . date('Ymd') . '.xls';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<table border='1'>";
    echo "<tr><th>Name</th>" .
        "<th>Middle Name</th>" .
        "<th>Last Name</th>" .
        "<th>Marital Status</th>" .
        "<th>Age</th>" .
        "<th>Patient Contact No</th>" .
        "<th>Philhealth ID</th>" .
        "<th>Birthday</th>" .
        "<th>Address</th>" .
        "<th>Guardian Name</th>" .
        "<th>Relation to Patient</th>" .
        "<th>Guardian Contact Number</th>" .
        "<th>Patient Record Date</th>" .
        "<th>Patient Record Update</th></tr>";

    foreach ($registrations as $registration) {
        echo "<tr>";
        echo "<td>{$registration['name']}</td>";
        echo "<td>{$registration['mname']}</td>";
        echo "<td>{$registration['lname']}</td>";
        echo "<td>{$registration['marital_status']}</td>";
        echo "<td>{$registration['age']}</td>";
        echo "<td>{$registration['patient_contact_no']}</td>";
        echo "<td>{$registration['philhealth_id']}</td>";
        echo "<td>{$registration['birthday']}</td>";
        echo "<td>{$registration['address']}</td>";
        echo "<td>{$registration['husband']}</td>";
        echo "<td>{$registration['occupation']}</td>";
        echo "<td>{$registration['husband_phone']}</td>";
        echo "<td>{$registration['created_at']}</td>";
        // Check if 'last_update' exists, if not, set a default value
        echo "<td>" . (isset($registration['last_update']) ? $registration['last_update'] : 'N/A') . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    exit();
}

public function report_view()
{
    // Load necessary models
    $this->load->model('Registration_model');
    $this->load->model('OnlineAppointments_model');
    $this->load->model('Appointment_model');
    $this->load->model('Checkup_model');
    $this->load->model('Diagnosis_model');
    $this->load->model('LaboratoryTest_model'); // ✅ Corrected model name

    // Get date range from the request (form submission)
    $start_date = $this->input->get('startDate');
    $end_date = $this->input->get('endDate');

    // Set default date range if not provided
    if (empty($start_date) || empty($end_date)) {
        $today = date('Y-m-d');
        $start_date = $today; // Default to today if no date provided
        $end_date = $today;
    }

    // Set weekly and monthly ranges
    $start_of_week = date('Y-m-d', strtotime('monday this week', strtotime($start_date)));
    $end_of_week = date('Y-m-d', strtotime('sunday this week', strtotime($end_date)));

    $start_of_month = date('Y-m-01', strtotime($start_date)); // 1st day of the month
    $end_of_month = date('Y-m-t', strtotime($end_date));      // Last day of the month

    // Daily report
    $data['dailyRegistrations'] = $this->Registration_model->get_registrations_with_service_type($start_date, $end_date);
    $data['dailyOnlineAppointments'] = $this->OnlineAppointments_model->get_appointments_by_date($start_date, $end_date);
    $data['dailyWalkInAppointments'] = $this->Appointment_model->get_appointments_by_date($start_date, $end_date);
    $data['dailyCheckups'] = $this->Checkup_model->get_checkups_by_date($start_date, $end_date);
    $data['dailyDiagnoses'] = $this->Diagnosis_model->count_diagnoses_by_date($start_date, $end_date);
    $data['dailyLabTests'] = $this->LaboratoryTest_model->get_tests_by_date($start_date, $end_date); // ✅ Fixed the model name

    // Weekly report
    $data['weeklyRegistrations'] = $this->Registration_model->get_registrations_with_service_type($start_of_week, $end_of_week);
    $data['weeklyOnlineAppointments'] = $this->OnlineAppointments_model->get_appointments_by_date($start_of_week, $end_of_week);
    $data['weeklyWalkInAppointments'] = $this->Appointment_model->get_appointments_by_date($start_of_week, $end_of_week);
    $data['weeklyCheckups'] = $this->Checkup_model->get_checkups_by_date($start_of_week, $end_of_week);
    $data['weeklyDiagnoses'] = $this->Diagnosis_model->count_diagnoses_by_date($start_of_week, $end_of_week);
    $data['weeklyLabTests'] = $this->LaboratoryTest_model->get_tests_by_date($start_of_week, $end_of_week); // ✅ Fixed the model name

    // Monthly report
    $data['monthlyRegistrations'] = $this->Registration_model->get_registrations_with_service_type($start_of_month, $end_of_month);
    $data['monthlyOnlineAppointments'] = $this->OnlineAppointments_model->get_appointments_by_date($start_of_month, $end_of_month);
    $data['monthlyWalkInAppointments'] = $this->Appointment_model->get_appointments_by_date($start_of_month, $end_of_month);
    $data['monthlyCheckups'] = $this->Checkup_model->get_checkups_by_date($start_of_month, $end_of_month);
    $data['monthlyDiagnoses'] = $this->Diagnosis_model->count_diagnoses_by_date($start_of_month, $end_of_month);
    $data['monthlyLabTests'] = $this->LaboratoryTest_model->get_tests_by_date($start_of_month, $end_of_month); // ✅ Fixed the model name

    // Load the view with the collected data
    $this->load->view('report_view', $data);
}



    public function export_diagnosis_csv()
    {
        $diagnoses = $this->Diagnosis_model->get_all_diagnoses(); // Fetch diagnosis data

        $filename = 'diagnosis_' . date('Ymd') . '.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        // Header row
        fputcsv($output, array('Patient Name', 'Diagnosis Type', 'Recommendation', 'Prescriptions', 'Date Released'));

        // Data rows
        foreach ($diagnoses as $diagnosis) {
            fputcsv($output, array(
                htmlspecialchars($diagnosis['name'] . ' ' . $diagnosis['mname'] . ' ' . $diagnosis['lname']),
                htmlspecialchars($diagnosis['type']),
                htmlspecialchars($diagnosis['recommendation']),
                htmlspecialchars($diagnosis['prescriptions']),
                htmlspecialchars($diagnosis['date_released']),
            ));
        }

        fclose($output);
        exit();
    }

    // Export Diagnosis to Excel
    public function export_diagnosis_excel()
    {
        $diagnoses = $this->Diagnosis_model->get_all_diagnoses(); // Fetch diagnosis data

        $filename = 'diagnosis_' . date('Ymd') . '.xls';
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<tr><th>Patient Name</th><th>Diagnosis Type</th><th>Recommendation</th><th>Prescriptions</th><th>Date Released</th></tr>";

        foreach ($diagnoses as $diagnosis) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($diagnosis['name'] . ' ' . $diagnosis['mname'] . ' ' . $diagnosis['lname']) . "</td>";
            echo "<td>" . htmlspecialchars($diagnosis['type']) . "</td>";
            echo "<td>" . htmlspecialchars($diagnosis['recommendation']) . "</td>";
            echo "<td>" . htmlspecialchars($diagnosis['prescriptions']) . "</td>";
            echo "<td>" . htmlspecialchars($diagnosis['date_released']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit();
    }
    // Add these methods to Checkup controller

    public function export_checkup_csv()
    {
        $this->load->model('checkup_model');
        $checkups = $this->checkup_model->get_all_with_patients();

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="checkups.csv"');

        $output = fopen('php://output', 'w');

        // Write header row
        fputcsv($output, ['Patient Full Name', 'Birthday', 'Age', 'Address', 'Blood Pressure', 'Pulse Rate', 'Respiration Rate', 'Temperature', 'Oxygen Saturation', 'Height', 'Weight', 'Checkup Date', 'Next Checkup Date', 'Prescription','Recommendation','Doctor Comment']);

        // Write data rows
        foreach ($checkups as $checkup) {
            fputcsv($output, [
                htmlspecialchars($checkup->name . ' ' . ($checkup->mname ? htmlspecialchars($checkup->mname) . ' ' : '') . htmlspecialchars($checkup->lname)),
                htmlspecialchars(date('Y-m-d', strtotime($checkup->birthday))),
                htmlspecialchars($checkup->age),
                htmlspecialchars($checkup->address),
                htmlspecialchars($checkup->blood_pressure),
                htmlspecialchars($checkup->pulse_rate),
                htmlspecialchars($checkup->respiration_rate),
                htmlspecialchars($checkup->temperature),
                htmlspecialchars($checkup->oxygen_saturation),
                htmlspecialchars($checkup->height),
                htmlspecialchars($checkup->weight),
                date('Y-m-d H:i', strtotime($checkup->checkup_date)),
                htmlspecialchars($checkup->next_checkup_date),
                htmlspecialchars($checkup->prescription),
                htmlspecialchars($checkup->recommendation),
                htmlspecialchars($checkup->doctor_comment),
                
                
            ]);
        }

        fclose($output);
        exit();
    }

    public function export_checkup_excel()
{
    $this->load->model('checkup_model');
    $checkups = $this->checkup_model->get_all_with_patients(); // Fetch checkup data

    $filename = 'checkups_' . date('Ymd') . '.xls'; // Filename for the Excel file
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Start the output of the Excel table
    echo "<table border='1'>";
    echo "<tr>
            <th>Patient Full Name</th>
            <th>Birthday</th>
            <th>Age</th>
            <th>Address</th>
            <th>Blood Pressure</th>
            <th>Pulse Rate</th>
            <th>Respiration Rate</th>
            <th>Temperature</th>
            <th>Oxygen Saturation</th>
            <th>Height</th>
            <th>Weight</th>
            <th>Checkup Date</th>
            <th>Next Checkup Date</th>
            <th>Prescription</th>
            <th>Recommendation</th>
            <th>Doctor Comment</th>
          </tr>";

    // Output each checkup as a row in the table
    foreach ($checkups as $checkup) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($checkup->name . ' ' . ($checkup->mname ? htmlspecialchars($checkup->mname) . ' ' : '') . htmlspecialchars($checkup->lname)) . "</td>";
        echo "<td>" . htmlspecialchars(date('Y-m-d', strtotime($checkup->birthday))) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->age) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->address) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->blood_pressure) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->pulse_rate) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->respiration_rate) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->temperature) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->oxygen_saturation) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->height) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->weight) . "</td>";
        echo "<td>" . htmlspecialchars(date('Y-m-d H:i', strtotime($checkup->checkup_date))) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->next_checkup_date) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->prescription) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->recommendation) . "</td>";
        echo "<td>" . htmlspecialchars($checkup->doctor_comment) . "</td>";
        
        echo "</tr>";
    }

    echo "</table>";
    exit();
}
}
