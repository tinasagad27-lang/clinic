<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Manila');

class Appointments extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Appointment_model');
		$this->load->model('Registration_model');
		$this->load->model('Diagnosis_model');
		$this->load->library('session');
		$this->load->helper(['url', 'form']);
		$this->load->library(['form_validation', 'session']);
	}

	public function search()
	{
		$name = $this->input->post('name');

		// Search patients using the Appointment_model
		$data['patients'] = $this->Appointment_model->search_patient_by_name($name);
		$data['search_name'] = $name; // Store the search term

		// Load the view with search results

		$this->load->view('appointments/patient_search_results', $data);
	}

	public function search_form()
	{
		// Prepare data for the view
		$data['search_name'] = $this->session->flashdata('search_name'); // Use flashdata for temporary storage

		$this->load->view('appointments/patient_search'); // Ensure this is the correct view file
	}

	public function index()
	{
		$data['appointments'] = $this->Appointment_model->get_appointments();

		$this->load->view('r_assets/navbar');
		$this->load->view('r_assets/sidebar');
		$this->load->view('appointments/index', $data);
	}
	public function followup()
	{
		$data['appointments'] = $this->Appointment_model->get_appointments();

		$this->load->view('r_assets/navbar');
		$this->load->view('r_assets/sidebar');
		$this->load->view('appointments/followup_index', $data);
	}

	public function view($id)
	{
		$data['appointment'] = $this->Appointment_model->get_appointment_by_id($id);

		if (empty($data['appointment'])) {
			show_404();
		}

		// Debugging line
		log_message('debug', 'Appointment data: ' . print_r($data['appointment'], true));

		// Extract patient name if available
		$data['patient_name'] = $data['appointment']['patient_name']; // Default if not set

		$this->load->view('r_assets/navbar');
		$this->load->view('r_assets/sidebar');
		$this->load->view('appointments/view', $data);
	}


	public function create($patient_id = null)
	{
		$this->load->library('form_validation');
		$this->load->model('Registration_model');
		$this->load->model('Appointment_model'); // Ensure the Appointment model is loaded

		// Get user role from session
		$user_level = $this->session->userdata('user_level');
		$allowed_roles = ['doctor', 'admin', 'secretary'];

		// Set validation rules
		$this->form_validation->set_rules('patient_id', 'Patient', 'required');
		$this->form_validation->set_rules('appointment_date', 'Date', 'required');
		$this->form_validation->set_rules('appointment_time', 'Time', 'required');

		// If user is doctor, admin, or secretary, validate status input
		if (in_array($user_level, $allowed_roles)) {
			$this->form_validation->set_rules('status', 'Status', 'required');
		}

		$data = []; // Create an empty data array

		// Check if a patient is provided
		if ($patient_id) {
			$patient = $this->Registration_model->get_patient_by_id($patient_id);
			if ($patient) {
				$data['patient'] = $patient;
				$data['patient_id'] = $patient_id;
			} else {
				$data['patient'] = null;
			}
		} else {
			$data['patient'] = [
				'name' => '',
				'mname' => '',
				'lname' => '',
			];
			$data['patient_id'] = null;
		}

		if ($this->form_validation->run() === FALSE) {
			$data['patients'] = $this->Appointment_model->get_patients();
			$this->load->view('appointments/create', $data);
		} else {
			// Process the form data
			$appointment_date = $this->input->post('appointment_date');
			$appointment_time = $this->input->post('appointment_time');

			// Check if the time slot is already booked
			if ($this->Appointment_model->is_time_slot_booked($appointment_date, $appointment_time)) {
				$this->session->set_flashdata('error_message', 'This time slot is already booked. Please choose another time.');
				redirect('appointments/create');
			}

			// If user is NOT doctor, admin, or secretary, force status to "Pending"
			if (!in_array($user_level, $allowed_roles)) {
				$status = 'pending';
			} else {
				$status = $this->input->post('status'); // Allow admin roles to set status
			}

			$appointment_data = array(
				'registration_id' => $this->input->post('patient_id'),
				'appointment_date' => $appointment_date,
				'appointment_time' => $appointment_time,
				'notes' => $this->input->post('notes'),
				'status' => $status, // Controlled status logic applied here
			);


			if ($this->Appointment_model->create_appointment($appointment_data)) {
				$this->session->set_flashdata('message', 'Appointment created successfully!');

				if (!in_array($user_level, $allowed_roles)) {
					redirect('clinic/index');
				}

				redirect('dashboard/admin/index');
			} else {

				$this->session->set_flashdata('error_message', 'Failed to create appointment. Please try again.');
				redirect('appointments/create');
			}
		}
	}


	public function edit($id)
	{
		// Load the models
		$this->load->model('Appointment_model');
		$this->load->model('Registration_model'); // Load the registration model to get patient email
		$this->load->library('email'); // Load email library

		// Load appointment details
		$data['appointment'] = $this->Appointment_model->get_appointment_by_id($id);

		// If the appointment does not exist, show 404
		if (empty($data['appointment'])) {
			show_404();
		}

		// Get patient details
		$patient_id = $data['appointment']['registration_id'];
		$patient = $this->Registration_model->get_patient_by_id($patient_id);
		$patient_email = $patient['email'] ?? '';

		$data['patient_name'] = $data['appointment']['patient_name'];

		// Set validation rules
		$this->form_validation->set_rules('appointment_date', 'Date', 'required');
		$this->form_validation->set_rules('appointment_time', 'Time', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');

		// Load views
		$this->load->view('r_assets/navbar');
		$this->load->view('r_assets/sidebar');

		if ($this->form_validation->run() === FALSE) {
			// Reload the form with errors
			$this->load->view('appointments/edit', $data);
		} else {
			// Prepare data for update
			$update_data = array(
				'appointment_date' => $this->input->post('appointment_date'),
				// 'doctor' => $this->input->post('doctor'),
				'appointment_time' => $this->input->post('appointment_time'),
				'status' => $this->input->post('status'),
				'notes' => $this->input->post('notes')
			);

			// Track previous status
			$old_status = $data['appointment']['status'];
			$new_status = $update_data['status'];

			// Update existing appointment
			if ($this->Appointment_model->update_appointment($id, $update_data)) {
				if ($old_status !== $new_status) {
					log_message('info', "Appointment status changed from {$old_status} to {$new_status} for appointment {$id}");
					$this->send_status_email($patient_email, $data['patient_name'], $new_status, $update_data);
				} else {
					log_message('info', "Appointment updated without status change for appointment {$id}");
				}

				// Success message
				$this->session->set_flashdata('success', 'Appointment updated successfully.');
				redirect('dashboard/admin/index');
			} else {
				// Failure message
				$this->session->set_flashdata('error', 'Update failed!');
				redirect('appointments/edit/' . $id);
			}
		}
	}

	/**
	 * Sends an email notification based on appointment status using PHP mail()
	 */
	private function send_status_email($to_email, $patient_name, $status, $appointment_data)
	{
		if (empty($to_email)) {
			log_message('error', "No recipient email for appointment status notification. status={$status}");
			return; // No email provided, exit function
		}

		$this->load->helper('email_helper');
		log_message('info', "Sending appointment status email to {$to_email} for status {$status}");

		// Email Subject & Message
		$subject = "Appointment Status Update";
		$message = "<h2>Dear {$patient_name},</h2>";

		switch ($status) {
			case 'booked':
				$message .= "<p>Your appointment has been successfully booked.</p>";
				break;
			case 'reschedule':
				$message .= "<p>Your appointment has been rescheduled.</p>";
				break;
			case 'cancelled':
				$message .= "<p>We regret to inform you that your appointment has been cancelled.</p>";
				break;
			case 'completed':
				$message .= "<p>Your appointment has been completed. Thank you for visiting.</p>";
				break;
			case 'approved':
				$message .= "<p>Your appointment has been approved. Please check the details and arrive on time.</p>";
				break;
			case 'rejected':
				$message .= "<p>Your appointment has been rejected. Please contact the clinic if you have questions.</p>";
				break;
			default:
				$message .= "<p>Your appointment status has been updated to: <strong>{$status}</strong>.</p>";
				break;
		}

		// Add appointment details
		$message .= "
       <p><strong>Appointment Date:</strong> {$appointment_data['appointment_date']}</p>
<p><strong>Time:</strong> {$appointment_data['appointment_time']}</p>
<p><strong>Important:</strong> Please arrive at least <strong>15 minutes before your scheduled time</strong> to complete any necessary paperwork and ensure a smooth consultation.</p>
<p>If you have any questions or need to reschedule, feel free to contact us.</p>
<p>We look forward to seeing you!</p>
<p>Best Regards,<br><strong>Mendoza Clinic</strong></p>
    ";

		if (send_email_simple($to_email, $subject, $message)) {
			log_message('info', "Status email sent to {$to_email}");
		} else {
			$this->session->set_flashdata('error', 'Email send failed');
			log_message('error', "Email failed for {$to_email}");
		}
	}


	public function delete($id)
	{
		$this->Appointment_model->delete_appointment($id);
		redirect('appointments');
	}

	public function approve($id)
	{
		if ($this->Appointment_model->approve_appointment($id)) {
			$appointment = $this->Appointment_model->get_appointment_by_id($id);
			$patient_email = $appointment['patient_email'] ?? '';
			$patient_name = $appointment['patient_name'] ?? '';
			$this->send_status_email($patient_email, $patient_name, 'approved', $appointment);
			$this->session->set_flashdata('message', 'Appointment approved successfully!');
		} else {
			$this->session->set_flashdata('message', 'Failed to approve appointment.');
		}

		redirect('appointments');
	}

	public function reject($id)
	{
		if ($this->Appointment_model->reject_appointment($id)) {
			$appointment = $this->Appointment_model->get_appointment_by_id($id);
			$patient_email = $appointment['patient_email'] ?? '';
			$patient_name = $appointment['patient_name'] ?? '';
			$this->send_status_email($patient_email, $patient_name, 'rejected', $appointment);
			$this->session->set_flashdata('message', 'Appointment rejected successfully!');
		} else {
			$this->session->set_flashdata('message', 'Failed to reject appointment.');
		}

		redirect('appointments');
	}

	public function settings()
	{
		$this->form_validation->set_rules('open_days', 'Open Days', 'required');
		$this->form_validation->set_rules('open_hours', 'Open Hours', 'required');

		if ($this->form_validation->run() === FALSE) {
			$data['settings'] = $this->Appointment_model->get_settings();
			$this->load->view('r_assets/navbar');
			$this->load->view('r_assets/sidebar');
			$this->load->view('appointments/settings', $data);
		} else {
			$this->Appointment_model->update_settings();
			$this->session->set_flashdata('message', 'Settings updated successfully!');
			redirect('appointments/settings');
		}
	}

	public function get_total_appointments()
	{
		return $this->db->count_all('appointments');
	}

	public function get_appointments_by_status($status)
	{
		$this->db->where('status', $status);
		return $this->db->count_all_results('appointments');
	}
}
