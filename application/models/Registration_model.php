<?php
class Registration_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
        $this->load->helper('string');
        $this->table = 'registration';
    }
    //pagination
    public function update_registration($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->where('is_deleted', 0); // Add the condition if needed
        $this->db->update('registration', $data);
    }
    public function get_tomorrows_appointments()
    {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $this->db->where('appointment_date', $tomorrow);
        $this->db->where('appointment_status', 'booked');
        return $this->db->get('registration')->result_array();
    }
    public function get_patient_by_id_medication($registration_id)
    {
        $this->db->where('id', $registration_id);
        $query = $this->db->get('registration');
        return $query->row_array(); // Returns patient details
    }

    public function save_registration_and_update_status($data, $appointmentId = null, $newStatus = null)
    {
        $this->db->trans_start(); // Start a transaction

        // Insert registration data
        $inserted = $this->db->insert($this->table, $data);
        $registrationId = $this->db->insert_id(); // Get inserted ID

        // If an appointment ID is provided, update the status
        if ($appointmentId !== null && $newStatus !== null) {
            $this->db->where('id', $appointmentId);
            $this->db->update('appointments', ['status' => $newStatus]);
        }

        $this->db->trans_complete(); // Complete the transaction

        if ($this->db->trans_status() === FALSE) {
            return false; // If any query fails, rollback
        }

        return $registrationId; // Return the new registration ID
    }

    public function insert_registration($data)
    {
        return $this->db->insert($this->table, $data);
    }
    public function update_status($appointmentId, $newStatus)
    {
        $this->db->where('id', $appointmentId);
        return $this->db->update('appointments', ['status' => $newStatus]);
    }


    public function rows()
    {
        $query = $this->db->get_where($this->table, array('is_deleted' => 0));
        return $query->result();
    }

    public function row($id)
    {
        $query = $this->db->get_where($this->table, array('is_deleted' => 0, 'id' => $id));
        return $query->row();
    }

    public function add($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // public function update_registration($id, $data)
    // {
    //     return $this->db->update($this->table, $data, array('id' => $id, 'is_deleted' => 0));
    // }
    public function get_available_slots()
    {
        $this->load->model('OnlineAppointments_model');

        $date = $this->input->get('date');

        if ($date) {
            $existingAppointments = $this->OnlineAppointments_model->get_booked_slots($date);

            // Define your time slots
            $timeSlots = [
                '09:00',
                '09:30',
                '10:00',
                '10:30',
                '13:00',
                '13:30',
                '14:00',
                '14:30',
                '15:00',
                '15:30',
                '16:00',
                '16:30',
                '17:00'
            ];

            // Define lunch break slots
            $lunchBreakSlots =  ['11:30', '17:30', '17:00', '15:30d'];

            // Filter out booked time slots and lunch break slots
            $availableSlots = array_diff($timeSlots, $existingAppointments, $lunchBreakSlots);

            // Return available slots as JSON
            echo json_encode(['availableSlots' => array_values($availableSlots)]);
        }
    }
    public function delete($id)
    {
        return $this->db->delete($this->table, array('id' => $id));
    }


    public function rows_with_files()
    {
        $this->db->select('registration.*, GROUP_CONCAT(files.file_name) as files');
        $this->db->from('registration');
        $this->db->join('files', 'files.registration_id = registration.id', 'left');
        $this->db->where('registration.is_deleted', 0);
        $this->db->group_by('registration.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_tests()
    {
        $this->db->select('laboratory_tests.*, registration.name, registration.birthday, registration.address');
        $this->db->from('laboratory_tests');
        $this->db->join('registration', 'registration.id = laboratory_tests.registration_id');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function search_by_name($name)
    {

        $this->db->select('id, name, mname, lname, address, birthday, marital_status');
        $this->db->from('registration');
        $this->db->like('name', $name);
        $this->db->or_like('mname', $name);
        $this->db->or_like('lname', $name);
        $query = $this->db->get();

        return $query->result();
    }



    public function get_registrations()
    {
        $this->db->select('*');
        $this->db->from('registration');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function rows_with_files_ordered()
    {

        $this->db->order_by('created_at', 'DESC');
        $this->db->order_by('last_update', 'DESC');
        $query = $this->db->get('registration');
        return $query->result();
    }

    public function get_registration_by_id($id)
    {
        return $this->db->get_where('registration', ['id' => $id])->row_array();
    }

    public function get_patient_by_name($name = '', $lname = '')
    {
        $this->db->select('*');
        $this->db->from('registration');

        if ($name) {
            $this->db->like('name', $name); // Use LIKE for partial matching
        }
        if ($lname) {
            $this->db->like('lname', $lname); // Use LIKE for partial matching
        }

        $query = $this->db->get();
        return $query->result_array(); // Return multiple rows as an array
    }
    public function get_patient_by_id_findings($registration_id)
    {
        // Select only the required fields
        $this->db->select('id, name, mname, lname, address, birthday, marital_status'); // Ensure 'id' is included
        $this->db->where('id', $registration_id); // Make sure you are using the correct ID
        $query = $this->db->get('registration');
        return $query->row(); // Return an object
    }

    public function is_valid_registration($registration_id)
    {
        $this->db->where('id', $registration_id);
        $query = $this->db->get('registration');
        return $query->num_rows() > 0; // Return true if at least one row is found
    }

    public function get_patient_by_id($patient_id)
    {
        $this->db->where('id', $patient_id);
        $query = $this->db->get('registration');
        return $query->row_array();
    }
    // Method to get patient by registration ID
    public function get_patient_by_registration_id($registration_id)
    {
        $this->db->where('registration_id', $registration_id);
        $query = $this->db->get('registration');
        return $query->row_array(); // Return a single row
    }
    public function export_registration_csv()
    {
        $registrations = $this->get_all_registrations(); // Fetch data

        $filename = 'registrations_' . date('Ymd') . '.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        // Header row
        // Header row (plain text, no HTML tags)
        fputcsv($output, array(
            'Name',
            'Middle Name',
            'Last Name',
            'Marital Status',
            'Patient Contact No',
            'Philhealth ID',
            'Birthday',
            'Address',
            'Age',
            'Guardian Name',
            'Relation to Patient',
            'Guardian Contact Number',
            'Number of Pregnancies',
            'Last Menstrual Date',
            'Age of Gestation',
            'Expected Date of Confinement'
        ));


        // Data rows
        foreach ($registrations as $registration) {
            fputcsv($output, array(
                // $registration['custom_id'],
                $registration['name'],
                $registration['mname'],
                $registration['lname'],
                $registration['marital_status'],
                $registration['age'],
                $registration['patient_contact_no'],
                $registration['philhealth_id'],
                $registration['birthday'],
                $registration['address'],
                $registration['husband'],
                $registration['occupation'],
                $registration['husband_phone'],
                $registration['no_of_pregnancy'],
                $registration['last_menstrual'],
                $registration['age_gestation'],
                $registration['expected_date_confinement']
            ));
        }

        fclose($output);
        exit();
    }
    public function get_all_registrations()
    {
        $this->db->select('id, name, mname, lname, marital_status, age, patient_contact_no, philhealth_id, birthday, address, husband, occupation, husband_phone, created_at, last_update');
        $query = $this->db->get('registration');
        return $query->result_array(); // Return data as an array
    }



    public function export_registration_excel()
    {
        $registrations = $this->get_all_registrations(); // Fetch data

        $filename = 'registrations_' . date('Ymd') . '.xls';
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<th>Name</th><th>Middle Name</th><th>Last Name</th><th>Marital Status</th><th>age</th><th>Patient Contact No</th><th>Philhealth ID</th><th>Birthday</th><th>Address</th><th>Age</th><th>Guardian Name</th><th>Relation to Patient</th><th>Guardian Contact Number</th><th>Number of Pregnancies</th><th>Last Menstrual Date</th><th>Age of Gestation</th><th>Expected Date of Confinement</th></tr>";

        foreach ($registrations as $registration) {
            echo "<tr>";
            // echo "<td>{$registration['custom_id']}</td>";
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
            echo "<td>{$registration['no_of_pregnancy']}</td>";
            echo "<td>{$registration['last_menstrual']}</td>";
            echo "<td>{$registration['age_gestation']}</td>";
            echo "<td>{$registration['expected_date_confinement']}</td>";
            echo "</tr>";
        }

        echo "</table>";
        exit();
    }
    public function get_total_registrations()
    {
        return $this->db->count_all('registration');
    }

    public function get_registrations_with_service_type($start_date, $end_date)
    {
        $this->db->select('registration.name, registration.mname, registration.lname, registration.created_at, registration.doctor, diagnosis_types.type as service_type');
        $this->db->from('registration');
        $this->db->join('laboratory_tests', 'laboratory_tests.registration_id = registration.id', 'left');
        $this->db->join('diagnosis_types', 'laboratory_tests.diagnosis_type_id = diagnosis_types.id', 'left');
        $this->db->where('registration.created_at >=', $start_date . ' 00:00:00');
        $this->db->where('registration.created_at <=', $end_date . ' 23:59:59');
        $query = $this->db->get();
        return $query->result();
    }


    public function get_patient_by_custom_id($custom_id)
    {
        $this->db->where('custom_id', $custom_id);
        $query = $this->db->get('registration');

        return $query->row_array();
    }
    //para na ito sa bagong database na may appointment date na
    public function insert_appointment($data)
    {
        // Insert data into `registration` table
        if ($this->db->insert('registration', $data)) {
            return $this->db->insert_id(); // Return the inserted ID
        } else {
            return false; // Return false if insertion fails
        }
    }

    // Function to check for conflicts in appointment schedules
    public function check_appointment_conflict($appointment_date, $appointment_time)
    {
        $this->db->where('appointment_date', $appointment_date);
        $this->db->where('appointment_time', $appointment_time);
        $this->db->where('is_deleted', 0); // Ensure the record is active
        $query = $this->db->get('registration');

        return $query->num_rows() > 0; // Return true if conflict exists
    }
    public function get_all_online_appointments()
    {
        // Query to get all data from the 'registration' table
        $query = $this->db->get('registration');
        if ($query->num_rows() > 0) {
            return $query->result_array(); // Return the result as an associative array
        } else {
            return []; // Return an empty array if no data is found
        }
    }

    public function process_appointment($data)
    {
        // Calculate age based on birthday
        $data['age'] = date_diff(date_create($data['birthday']), date_create('today'))->y;
        $data['appointment_status'] = 'pending';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['last_update'] = date('Y-m-d H:i:s');

        // Attempt to insert the appointment data
        return $this->db->insert('appointments', $data);
    }

    // Model method to get an appointment by ID
    public function get_appointment_by_id($id)
    {
        return $this->db->get_where('registration', ['id' => $id])->row_array();
    }

    // Model method to update an appointment
    public function update_appointment($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('registration', $data);
    }

    public function onlineedit($id)
    {
        $query = $this->db->get_where('registration', ['id' => $id]);
        return $query->row_array(); // Fetch the result as an associative array
    }

    public function onlineupdate($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('registration', $data); // Ensure 'registration' is the correct table name
    }


    public function getRegistrationById($id)
    {
        $query = $this->db->get_where('registration', ['id' => $id]);
        return $query->row_array();
    }

    public function getAllRegistrations()
    {
        $this->db->from('registration'); // Specify the correct table name
        $query = $this->db->get();
        return $query->result_array(); // or $query->result() for objects
    }

    // public function check_appointment_exists($appointment_date, $appointment_time, $email = null)
    // {
    //     $this->db->where('appointment_date', $appointment_date);
    //     $this->db->where('appointment_time', $appointment_time);
    //     $this->db->where('appointment_status !=', 'cancelled');

    //     if ($email !== null) {
    //         $this->db->where('email', $email);
    //     }

    //     $query = $this->db->get('registration');
    //     return $query->row_array(); // Returns an associative array or null
    // }
    public function check_appointment_exists($appointment_date, $appointment_time)
    {
        $this->db->where('appointment_date', $appointment_date);
        $this->db->where('appointment_time', $appointment_time);
        $query = $this->db->get('registration');

        return $query->row_array(); // Return the first matching record, or false if none exists
    }



    public function check_recent_appointment($email, $appointment_date)
    {
        // Check for any appointment that was created within the last 5 minutes for the same email
        $this->db->where('email', $email);
        $this->db->where('appointment_date', $appointment_date);
        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-5 minutes')));
        $query = $this->db->get('registration');

        return $query->num_rows() > 0; // Returns true if there's a recent appointment, false otherwise
    }

    public function update_appointment_status($email, $status)
    {
        $this->db->set('appointment_status', $status);
        $this->db->set('last_update', date('Y-m-d H:i:s'));
        $this->db->where('email', $email);
        $this->db->update('registration'); // Ensure this matches your table name

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false; // Optionally, log the query here for debugging
        }
    }

    public function get_doctor_by_id($doctor_id)
    {
        $this->db->where('id', $doctor_id);
        $query = $this->db->get('doctor');
        return $query->row_array(); // Return doctor details
    }
}
