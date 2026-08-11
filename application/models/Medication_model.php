<?php
class Medication_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database('medical');
    }
    public function save_email_account($data)
    {
        return $this->db->insert('email_account', $data);
    }
    public function get_patient_by_id_medication($registration_id)
{
    $this->db->where('id', $registration_id);
    $query = $this->db->get('registration');
    return $query->row_array(); // Returns patient details
}

public function get_medical_by_registration_id($registration_id)
{
    $this->db->where('registration_id', $registration_id);
    $query = $this->db->get('medical');
    return $query->row_array(); // Returns the row if found, NULL if not
}

    public function get_email_by_appointment_id($appointment_id)
    {
        $this->db->select('email');
        $this->db->from('email_account');
        $this->db->where('appointment_id', $appointment_id);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function get_patient_by_registration_id($registration_id)
    {
        $this->db->where('registration_id', $registration_id);
        $query = $this->db->get('patients');
        return $query->row_array(); // or another suitable return format
    }
    
    public function insert_medication($data)
    {
        return $this->db->insert('medical', $data);
    }

    public function update_medication($data)
    {
        $this->db->where('id', $data['id']);
        return $this->db->update('medical', $data);
    }

    public function delete_medication($id)
    {
        return $this->db->delete('medical', array('id' => $id));
    }


    public function get_all_medications()
    {
        $this->db->select('medical.*, registration.name, registration.mname, registration.lname');
        $this->db->from('medical');
        $this->db->join('registration', 'registration.id = medical.registration_id'); // Using registration_id
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_medication_by_id($id)
    {
        $this->db->select('medical.*, registration.name, registration.mname, registration.lname');
        $this->db->from('medical');
        $this->db->join('registration', 'registration.id = medical.registration_id');
        $this->db->where('medical.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    public function get_all_medications_ordered()
    {
       
        $this->db->order_by('last_update', 'DESC');
        $query = $this->db->get('medical'); 
    
        return $query->result_array();
    }
    public function get_patient_by_id($registration_id)
    {
        $this->db->select('name, mname, lname');
        $this->db->from('registration');
        $this->db->where('id', $registration_id);
        $query = $this->db->get();
        return $query->row_array(); // Return patient details
    }
    public function search_by_name($name)
    {
        $this->db->select('registration.*');
        $this->db->from('registration');
        $this->db->like('name', $name);
        $this->db->or_like('mname', $name);
        $this->db->or_like('lname', $name);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_medication_details($medication_id)
    {
        $query = $this->db->get_where('medical', array('id' => $medication_id));
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    public function selectedname($registration_id)
    {
        $this->db->select('name, mname, lname');
        $this->db->from('registration');
        $this->db->where('id', $registration_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false; // Return false if the patient is not found
        }
    }

    public function get_medication_by_patient($registration_id)
    {
        $this->db->where('registration_id', $registration_id); // Using registration_id
        $query = $this->db->get('medical'); // assuming your table is called 'medical'
        return $query->result_array(); // Return all medications for the patient
    }
}
