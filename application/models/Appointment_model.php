<?php
class Appointment_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function get_all_appointments()
    {
        $query = $this->db->get('appointments'); // Fetch all appointments
        return $query->result_array(); // Return as an array
    }



    public function get_appointments()
    {
        // Select appointment details and patient full name
        $this->db->select('appointments.*, CONCAT(registration.name, " ", registration.mname, " ", registration.lname) AS patient_name, registration.custom_id');
        $this->db->from('appointments');
        $this->db->join('registration', 'appointments.registration_id = registration.id', 'left');
        $query = $this->db->get();
        return $query->result_array(); // Return as an array
    }

    public function get_appointment_by_id($id)
    {
        $this->db->select('appointments.*, registration.email AS patient_email, CONCAT(registration.name, " ", registration.mname, " ", registration.lname) AS patient_name');
        $this->db->from('appointments');
        $this->db->join('registration', 'appointments.registration_id = registration.id', 'left');
        $this->db->where('appointments.id', $id); // Use the correct ID here
        $query = $this->db->get();
        return $query->row_array(); // Return a single row
    }

    public function get_patients()
    {
        $this->db->select('r.id, CONCAT(r.name, " ", r.mname, " ", r.lname) AS full_name');
        $this->db->from('registration r');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function create_appointment($data)
    {
        return $this->db->insert('appointments', $data);
    }

    // public function update_appointment($id, $data)

    // {
    //     $data = array(
    //         'appointment_date' => $this->input->post('appointment_date'),
    //         'appointment_time' => $this->input->post('appointment_time'),
    //         'doctor' => $this->input->post('doctor'),
    //         'notes' => $this->input->post('notes'),
    //         'status' => $this->input->post('status')
    //     );
    //     $this->db->where('id', $id);
    //     return $this->db->update('appointments', $data);
    // }
    public function update_appointment($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('appointments', $data);  // Make sure 'appointments' is the correct table name
    }

    public function delete_appointment($id)
    {
        return $this->db->delete('appointments', array('id' => $id));
    }

    public function approve_appointment($id)
    {
        // Define the data to update the status
        $data = array('status' => 'approved');

        // Update the appointment record with the specified ID
        $this->db->where('id', $id);
        return $this->db->update('appointments', $data);
    }

    public function reject_appointment($id)
    {
        // Define the data to update the status
        $data = array('status' => 'rejected');

        // Update the appointment record with the specified ID
        $this->db->where('id', $id);
        return $this->db->update('appointments', $data);
    }

    // public function get_appointments() {
    //     // Select the necessary fields from appointments and registration tables
    //     $this->db->select('appointments.*, registration.name as patient_name, registration.mname, registration.lname');

    //     // From the appointments table
    //     $this->db->from('appointments');

    //     // Join the registration table on the patient_id column
    //     $this->db->join('registration', 'registration.patient_id = registration.patient_id', 'left');

    //     // Execute the query and return the results as an array
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }


    public function get_settings()
    {
        // Implement the logic to get settings if needed
        return array(); // Placeholder
    }

    public function update_settings()
    {
        // Implement the logic to update settings if needed
    }
    public function get_total_appointments()
    {
        return $this->db->count_all('appointments');
    }
    public function get_doctors()
    {
        $query = $this->db->get('doctor'); // Assuming the table name is 'doctors'
        return $query->result();
    }

    public function get_appointments_by_status($status)
    {
        $this->db->where('status', $status);
        return $this->db->count_all_results('appointments');
    }
    public function get_appointments_by_date($start_date, $end_date)
    {
        $this->db->select('appointments.*, registration.name, registration.mname, registration.lname');
        $this->db->from('appointments');
        // Join the registration table on registration_id (not patient_id)
        $this->db->join('registration', 'appointments.registration_id = registration.id', 'left');
        $this->db->where('appointments.created_at >=', $start_date . ' 00:00:00');
        $this->db->where('appointments.created_at <=', $end_date . ' 23:59:59');
        $query = $this->db->get();
        return $query->result();
    }


    public function search_patient_by_name($name)
    {
        // Sanitize the input and search by name or email
        $this->db->like('name', $name);
        $this->db->or_like('mname', $name);
        $this->db->or_like('lname', $name);
        $this->db->or_like('email', $name); // Added search by email
        $this->db->where('is_deleted', 0); // Exclude deleted patients

        $query = $this->db->get('registration');
        return $query->result_array(); // Return results as an array
    }

    //     public function is_time_slot_booked($appointment_date, $appointment_time)
    // {
    //     $this->db->where('appointment_date', $appointment_date);
    //     $this->db->where('appointment_time', $appointment_time);
    //     $this->db->where('status !=', 'cancelled'); // Exclude cancelled appointments
    //     $query = $this->db->get('appointments');

    //     return $query->num_rows() > 0; // Return true if there are any bookings
    // }
    private function is_excluded_time($appointment_time)
    {
        // Exclude 11:30 AM, 12:00 PM, 12:30 PM, and 5:30 PM
        $excluded_times = ['11:30', '17:30'];

        return in_array($appointment_time, $excluded_times);
    }

    public function is_time_slot_booked($appointment_date, $appointment_time)
    {
        // Check if the time slot is in the excluded list
        if ($this->is_excluded_time($appointment_time)) {
            return true; // Indicate that the time slot is not available
        }

        // Proceed with booking check
        $this->db->where('appointment_date', $appointment_date);
        $this->db->where('appointment_time', $appointment_time);
        $this->db->where('status !=', 'cancelled'); // Exclude cancelled appointments
        $query = $this->db->get('appointments');

        return $query->num_rows() > 0; // Return true if there are any bookings
    }
}
