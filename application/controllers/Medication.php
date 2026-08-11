<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Medication extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Medication_model');
        $this->load->model('LaboratoryTest_model');
        $this->load->model('Registration_model'); // Load the registration model to fetch patient details
        $this->load->helper('url');
        $this->load->library('session');
        // Check if the current method is NOT 'online_medication' before loading navbar & sidebar
        if ($this->router->fetch_method() !== 'online_medication') {
            $this->load->view('r_assets/navbar');
            $this->load->view('r_assets/sidebar');
        }
    }

    public function index()
    {
        // Fetch all medications ordered by last_update in descending order
        $data['medications'] = $this->Medication_model->get_all_medications_ordered();

        // Fetch patient details for all medications
        foreach ($data['medications'] as &$medication) {
            $patient = $this->Registration_model->get_patient_by_id($medication['registration_id']);
            // Ensure patient array has name, mname, lname, and address
            $medication['patient'] = $patient ? $patient : ['name' => 'Unknown', 'mname' => '', 'lname' => '', 'address' => 'No Address']; // Provide a fallback
        }

        // Load views without checking user level

        $this->load->view('medication/medication_view', $data);
    }



    private function check_role($role)
    {
        return $this->session->userdata('role') === $role;
    }


    public function edit($id)
    {
        $data['medication'] = $this->Medication_model->get_medication_by_id($id);

        $this->load->view('medication/medication_edit', $data);
    }

    public function search()
    {
        $name = $this->input->post('name');
        $data['patients'] = $this->Medication_model->search_by_name($name);

        $this->load->view('medication/patient_search_results', $data);
    }


    public function search_form()
    {

        $this->load->view('medication/patient_search');
    }

    // public function view_all_details($medication_id)
    // {
    //     $medication_details = $this->Medication_model->get_medication_details($medication_id);
    //     $data['medication_details'] = $medication_details;


    //     $this->load->view('medication/medication_details_view', $data);
    // }
    public function view_all_details($medication_id)
    {
        $medication_details = $this->Medication_model->get_medication_details($medication_id);
        $data['medication_details'] = $medication_details;

        if (!empty($medication_details)) {
            $data['registration_id'] = $medication_details['registration_id']; // Ensure registration_id is available
        }

        $this->load->view('medication/medication_details_view', $data);
    }


    public function add($registration_id)
    {

        $patient = $this->Registration_model->get_patient_by_id($registration_id);

        if ($patient) {
            $data['patient_name'] = $patient['name'] . ' ' . $patient['mname'] . ' ' . $patient['lname'];
            $data['registration_id'] = $registration_id;
        } else {
            $data['patient_name'] = 'Unknown Patient';
            $data['registration_id'] = $registration_id;
        }

        $data['patient'] = $patient;
        $this->load->view('medication/medication_add', $data);
    }

    public function online_medication($registration_id = NULL)
{
    if ($registration_id === NULL) {
        show_error('Invalid Registration ID');
    }

    // Load model
    $this->load->model('Registration_model');
    
    // Get patient details
    $patient = $this->Registration_model->get_patient_by_id_medication($registration_id);

    if ($patient) {
        $data['patient_name'] = strtoupper($patient['name'] . ' ' . $patient['mname'] . ' ' . $patient['lname']);
    } else {
        $data['patient_name'] = 'Unknown Patient';
    }

    $data['registration_id'] = $registration_id;

    // Load view with patient name
    $this->load->view('medication/online_medication', $data);
}



public function store_medication()
{
    $registration_id = $this->input->post('registration_id');

    if (!$registration_id) {
        log_message('error', 'Registration ID missing in store_medication');
        show_error('Missing registration ID');
    }

    log_message('debug', 'Saving medication for Registration ID: ' . $registration_id);

    // Ensure medical entry exists
    $existing_medical = $this->Medication_model->get_medical_by_registration_id($registration_id);

    if (!$existing_medical) {
        log_message('debug', 'No existing medical entry. Creating default medical record for Registration ID: ' . $registration_id);

        $default_medical_data = [
            'registration_id' => $registration_id,
            'ear_nose_throat_disorders' => NULL,
            'heart_conditions_high_blood_pressure' => NULL,
            'respiratory_tuberculosis_asthma' => NULL,
            'neurologic_migraines_frequent_headaches' => NULL,
            'gonorrhea_chlamydia_syphilis' => NULL,
            'no_of_pregnancy' => NULL,
            'last_menstrual' => NULL,
            'age_gestation' => NULL,
            'expected_date_confinement' => NULL,
            'last_update' => date('Y-m-d H:i:s')
        ];

        $this->Medication_model->insert_medication($default_medical_data);
        log_message('debug', 'Default medical entry created.');
    }

    // Now proceed with updating the medical record
    $data = array(
        'ear_nose_throat_disorders' => $this->input->post('ear_nose_throat_disorders'),
        'heart_conditions_high_blood_pressure' => $this->input->post('heart_conditions_high_blood_pressure'),
        'respiratory_tuberculosis_asthma' => $this->input->post('respiratory_tuberculosis_asthma'),
        'neurologic_migraines_frequent_headaches' => $this->input->post('neurologic_migraines_frequent_headaches'),
        'gonorrhea_chlamydia_syphilis' => $this->input->post('gonorrhea_chlamydia_syphilis'),
        'no_of_pregnancy' => $this->input->post('no_of_pregnancy'),
        'last_menstrual' => $this->input->post('last_menstrual'),
        'age_gestation' => $this->input->post('age_gestation'),
        'expected_date_confinement' => $this->input->post('expected_date_confinement'),
        'last_update' => date('Y-m-d H:i:s')
    );

    // Update the record instead of inserting a new one
    $this->db->where('registration_id', $registration_id);
    $this->db->update('medical', $data);

    $this->session->set_flashdata('success', 'Medication updated successfully.');

    // Redirect to final step
    redirect('clinic/index');
}

    public function store()
    {

        // $this->form_validation->set_rules('registration', 'Registration ID', 'required');
        $this->form_validation->set_rules('ear_nose_throat_disorders', 'Ear, Nose, Throat Disorders', 'trim');
        $this->form_validation->set_rules('heart_conditions_high_blood_pressure', 'Heart Conditions / High Blood Pressure', 'trim');
        $this->form_validation->set_rules('respiratory_tuberculosis_asthma', 'Respiratory Tuberculosis / Asthma', 'trim');
        $this->form_validation->set_rules('neurologic_migraines_frequent_headaches', 'Neurologic Migraines / Frequent Headaches', 'trim');
        $this->form_validation->set_rules('gonorrhea_chlamydia_syphilis', 'Gonorrhea / Chlamydia / Syphilis', 'trim');
        $this->form_validation->set_rules('no_of_pregnancy', 'Number of Pregnancies', 'trim|integer');
        $this->form_validation->set_rules('last_menstrual', 'Last Menstrual Period', 'trim|date');
        $this->form_validation->set_rules('age_gestation', 'Age of Gestation', 'trim|integer');
        $this->form_validation->set_rules('expected_date_confinement', 'Expected Date of Confinement', 'trim|date');

        if ($this->form_validation->run() === FALSE) {

            $this->add($this->input->post('registration_id'));
        } else {

            $data = array(
                'registration_id' => $this->input->post('registration_id'),
                'ear_nose_throat_disorders' => $this->input->post('ear_nose_throat_disorders'),
                'heart_conditions_high_blood_pressure' => $this->input->post('heart_conditions_high_blood_pressure'),
                'respiratory_tuberculosis_asthma' => $this->input->post('respiratory_tuberculosis_asthma'),
                'neurologic_migraines_frequent_headaches' => $this->input->post('neurologic_migraines_frequent_headaches'),
                'gonorrhea_chlamydia_syphilis' => $this->input->post('gonorrhea_chlamydia_syphilis'),
                'no_of_pregnancy' => $this->input->post('no_of_pregnancy'),
                'last_menstrual' => $this->input->post('last_menstrual'),
                'age_gestation' => $this->input->post('age_gestation'),
                'expected_date_confinement' => $this->input->post('expected_date_confinement')
            );

            $this->Medication_model->insert_medication($data);
            $this->session->set_flashdata('success', 'Medication added successfully.');

            redirect('medication');
        }
    }
    public function update()
    {

        $this->form_validation->set_rules('ear_nose_throat_disorders', 'Ear, Nose, Throat Disorders', 'required');
        $this->form_validation->set_rules('heart_conditions_high_blood_pressure', 'Heart Conditions / High Blood Pressure', 'required');
        $this->form_validation->set_rules('respiratory_tuberculosis_asthma', 'Respiratory Tuberculosis / Asthma', 'required');
        $this->form_validation->set_rules('neurologic_migraines_frequent_headaches', 'Neurologic Migraines / Frequent Headaches', 'required');
        $this->form_validation->set_rules('gonorrhea_chlamydia_syphilis', 'Gonorrhea / Chlamydia / Syphilis', 'required');
        // $this->form_validation->set_rules('registration_id', 'Registration ID', 'required|integer');
        $this->form_validation->set_rules('no_of_pregnancy', 'Number of Pregnancies', 'trim|integer');
        $this->form_validation->set_rules('last_menstrual', 'Last Menstrual Period', 'trim|date');
        $this->form_validation->set_rules('age_gestation', 'Age of Gestation', 'trim|integer');
        $this->form_validation->set_rules('expected_date_confinement', 'Expected Date of Confinement', 'trim|date');

        if ($this->form_validation->run() === FALSE) {

            $id = $this->input->post('id');
            $this->edit($id);
        } else {
            try {

                $data = array(
                    'id' => $this->input->post('id'),
                    // 'registration_id' => $this->input->post('registration_id'),
                    'ear_nose_throat_disorders' => $this->input->post('ear_nose_throat_disorders'),
                    'heart_conditions_high_blood_pressure' => $this->input->post('heart_conditions_high_blood_pressure'),
                    'respiratory_tuberculosis_asthma' => $this->input->post('respiratory_tuberculosis_asthma'),
                    'neurologic_migraines_frequent_headaches' => $this->input->post('neurologic_migraines_frequent_headaches'),
                    'gonorrhea_chlamydia_syphilis' => $this->input->post('gonorrhea_chlamydia_syphilis'),
                    'no_of_pregnancy' => $this->input->post('no_of_pregnancy'),
                    'last_menstrual' => $this->input->post('last_menstrual'),
                    'age_gestation' => $this->input->post('age_gestation'),
                    'expected_date_confinement' => $this->input->post('expected_date_confinement')
                );


                if ($this->Medication_model->update_medication($data)) {
                    $this->session->set_flashdata('success', 'Medication updated successfully.');
                    redirect('medication');
                } else {
                    throw new Exception('Failed to update medication.');
                }
            } catch (Exception $e) {

                if (strpos($e->getMessage(), '1452') !== false) {
                    $this->session->set_flashdata('error', 'Failed to update. Registration ID is invalid or does not exist.');
                } else {

                    $this->session->set_flashdata('error', 'An error occurred while updating. Please try again.');
                }

                $this->edit($this->input->post('id'));
            }
        }
    }



    public function delete($id = NULL)
    {
        $this->Medication_model->delete_medication($id);
        $this->session->set_flashdata('success', 'Medication deleted successfully.');
        redirect('medication');
    }
}
