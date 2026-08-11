<?php
defined('BASEPATH') or exit('No direct script access allowed');

class OnlineAppointments extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('OnlineAppointments_model');
		$this->load->model('Registration_model');
		$this->load->model('Dashboard_model');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->view('r_assets/navbar');
		$this->load->view('r_assets/sidebar');
		$this->load->config('email');
	}
	public function create()
	{
		$this->load->view('onlineappointments/create');
	}

	public function onlinestore()
	{
		// Load necessary libraries and models
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('Registration_model');

		// Set validation rules for the form fields
		$this->form_validation->set_rules('email', 'Email', 'required|is_unique[registration.email]', [
			'is_unique' => 'This email is already registered. Please select "Existing Member" to proceed with the booking..'
		]);
		$this->form_validation->set_rules('name', 'First Name', 'required');
		$this->form_validation->set_rules('mname', 'Middle Name', 'required');
		$this->form_validation->set_rules('lname', 'Last Name', 'required');
		$this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
		$this->form_validation->set_rules('patient_contact_no', 'Contact Number', 'required');
		$this->form_validation->set_rules('birthday', 'Birthday', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('occupation', 'Occupation', 'required');
		$this->form_validation->set_rules('appointment_date', 'Appointment Date', 'required');
		$this->form_validation->set_rules('appointment_time', 'Appointment Time', 'required');
		$this->form_validation->set_rules('philhealth_id', 'PhilHealth ID', 'max_length[50]');

		// Run validation
		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			redirect('clinic/index');
		}

		$email = $this->input->post('email');
		$appointment_date = $this->input->post('appointment_date');
		$current_time = time();

		// Check if the same email has booked in the last 5 minutes
		if ($this->session->userdata('last_booking') && $this->session->userdata('last_email')) {
			$last_booking = $this->session->userdata('last_booking');
			$last_email = $this->session->userdata('last_email');

			if ($email === $last_email && ($current_time - $last_booking) < 300) {
				$this->session->set_flashdata('error', 'You can only create one appointment every 5 minutes.');
				redirect('clinic/index');
			}
		}

		// Check for a conflicting booked slot in the database
		$existingAppointment = $this->Registration_model->check_appointment_exists($appointment_date, $this->input->post('appointment_time'));
		if ($existingAppointment && $existingAppointment['appointment_status'] === 'booked') {
			$this->session->set_flashdata('error', 'This time slot is already booked.');
			redirect('clinic/index');
		}

		// Save booking data if no conflict
		$data = [
			'email' => $email,
			'name' => $this->input->post('name'),
			'mname' => $this->input->post('mname'),
			'lname' => $this->input->post('lname'),
			'marital_status' => $this->input->post('marital_status'),
			'patient_contact_no' => $this->input->post('patient_contact_no'),
			'birthday' => $this->input->post('birthday'),
			'address' => $this->input->post('address'),
			'occupation' => $this->input->post('occupation'),
			'philhealth_id' => $this->input->post('philhealth_id'),
			'husband' => $this->input->post('husband'),
			'husband_phone' => $this->input->post('husband_phone'),
			'appointment_date' => $appointment_date,
			'appointment_time' => $this->input->post('appointment_time'),
			'created_at' => date('Y-m-d H:i:s'),
			'appointment_status' => 'pending'
		];

		// Save age
		$birthday = new DateTime($this->input->post('birthday'));
		$age = $birthday->diff(new DateTime())->y;
		$data['age'] = $age;

		// Insert into database
		$registration_id = $this->Registration_model->insert_registration($data);

		// Check if insertion was successful
		if (!$registration_id) {
			$this->session->set_flashdata('error', 'There was an issue booking your appointment. Please try again.');
			redirect('clinic/index');
		}

		// Debug: Ensure registration_id is being saved
		log_message('debug', 'Registration ID: ' . $registration_id);

		// Update session for 5-minute booking limitation
		$this->session->set_userdata([
			'last_booking' => $current_time,
			'last_email' => $email
		]);

		// Send email confirmation using PHP mail() function
		$this->load->helper('email_helper');

		$email_subject = 'Appointment Booking Confirmation';
		$email_message = '<html><body>';
		$email_message .= '<h2>Appointment Booking Confirmation</h2>';
		$email_message .= '<p>We received your appointment request.</p>';
		$email_message .= '<p>Please wait for our email confirmation within 3-4 business days.</p>';
		$email_message .= '<p>Thank you!</p>';
		$email_message .= '</body></html>';

		if (send_email_simple($email, $email_subject, $email_message)) {
			$this->session->set_flashdata('success', 'Your appointment has been successfully booked! An email confirmation has been sent to you.');
			log_message('info', "Confirmation email sent to {$email}");
		} else {
			$this->session->set_flashdata('error', 'There was an issue sending the email confirmation. Please contact us for assistance.');
			log_message('error', "Email failed for {$email}");
		}

		// After successful registration
		$registration_id = $this->db->insert_id(); // Get the last inserted registration ID
		redirect('medication/online_medication/' . $registration_id);
	}

	public function store()
	{
		// Define lunch break slots
		$lunchBreakSlots = ['11:00', '12:30', '17:00', '15:30'];

		// Get form input data
		$data = array(
			'email' => $this->input->post('email'),
			'name' => $this->input->post('firstname'),
			'mname' => $this->input->post('mname'),
			'lname' => $this->input->post('lastname'),
			'marital_status' => $this->input->post('marital_status'),
			'husband' => $this->input->post('husband'),
			'husband_phone' => $this->input->post('husband_phone'),
			'patient_contact_no' => $this->input->post('contact_number'),
			'philhealth_id' => $this->input->post('philhealth_id'),
			'birthday' => $this->input->post('birthday'),
			'age' => date_diff(date_create($this->input->post('birthday')), date_create('today'))->y,
			'address' => $this->input->post('address'),
			'occupation' => $this->input->post('occupation'),
			'appointment_date' => $this->input->post('appointment_date'),
			'appointment_time' => $this->input->post('appointment_time'),
			'appointment_status' => 'pending',
			'created_at' => date('Y-m-d H:i:s'),
			'last_update' => date('Y-m-d H:i:s')
		);

		// Check if the selected time is a lunch break slot
		if (in_array($data['appointment_time'], $lunchBreakSlots)) {
			$this->session->set_flashdata('warning', 'The selected time slot is during lunch break. Please choose a different time.');
			redirect('onlineappointments/create');
			return;
		}

		// Check if the time slot is already booked
		if ($this->OnlineAppointments_model->is_time_booked($data['appointment_date'], $data['appointment_time'])) {
			$this->session->set_flashdata('warning', 'The selected time slot is already booked. Please choose a different time.');
			redirect('onlineappointments/create');
		} else {
			if ($this->Registration_model->insert_appointment($data)) {
				$this->session->set_flashdata('success', 'Your appointment has been successfully booked!');
			} else {
				$this->session->set_flashdata('error', 'There was an issue booking your appointment. Please try again.');
			}
			redirect('onlineappointments');
		}
	}

	public function get_available_slots()
	{
		$this->load->model('OnlineAppointments_model');
		$this->load->model('Registration_model');
		$date = $this->input->get('date');

		if ($date) {
			$existingAppointments = $this->OnlineAppointments_model->get_booked_slots($date);
			$timeSlots = [
				'09:00',
				'09:30',
				'10:00',
				'10:30',
				'11:00',
				'11:30',
				'12:00',
				'12:30',
				'13:00',
				'13:30',
				'14:00',
				'14:30',
				'15:00',
				'15:30',
				'16:00',
				'16:30',
				'17:00',
				'17:30',
			];

			$availableSlots = array_diff($timeSlots, $existingAppointments);
			echo json_encode(['availableSlots' => array_values($availableSlots)]);
		}
	}



	public function online_edit($id)
	{
		$this->load->model('Registration_model');
		$data['registration'] = $this->Registration_model->onlineedit($id);

		if (empty($data['registration'])) {
			$this->session->set_flashdata('error', 'Registration not found.');
			redirect('onlineappointments');
		}


		$this->load->view('onlineappointments/edit', $data);
	}

	public function online_update($id)
	{
		$this->load->model('Registration_model');
		$this->load->library('email');

		// Load existing data
		$existingData = $this->Registration_model->get_registration_by_id($id);
		if (!$existingData) {
			$this->session->set_flashdata('error', 'Registration not found.');
			redirect('dashboard/admin/index');
			return;
		}

		// Collect form data
		$data = [
			'email' => $this->input->post('email') ?: $existingData['email'],
			'name' => $this->input->post('name') ?: $existingData['name'],
			'mname' => $this->input->post('mname') ?: $existingData['mname'],
			'lname' => $this->input->post('lname') ?: $existingData['lname'],
			'philhealth_id' => $this->input->post('philhealth_id') ?: $existingData['philhealth_id'],
			'birthday' => $this->input->post('birthday') ?: $existingData['birthday'],
			'age' => $this->input->post('age') ?: $existingData['age'],
			'address' => $this->input->post('address') ?: $existingData['address'],
			'patient_contact_no' => $this->input->post('patient_contact_no') ?: $existingData['patient_contact_no'],
			'marital_status' => $this->input->post('marital_status') ?: $existingData['marital_status'],
			'husband' => $this->input->post('husband') ?: $existingData['husband'],
			'husband_phone' => $this->input->post('husband_phone') ?: $existingData['husband_phone'],
			'occupation' => $this->input->post('occupation') ?: $existingData['occupation'],
			'appointment_date' => $this->input->post('appointment_date') ?: $existingData['appointment_date'],
			'appointment_status' => $this->input->post('appointment_status'),
			'appointment_time' => $this->input->post('appointment_time') ?: $existingData['appointment_time'],
			'doctor' => $this->input->post('doctor') ?: $existingData['doctor'],
			'next_checkup_date' => $this->input->post('next_checkup_date') ?: $existingData['next_checkup_date']
		];

		// Call the model's update method
		$updateStatus = $this->Registration_model->onlineupdate($id, $data);

		if ($updateStatus) {
			$this->session->set_flashdata('success', 'Registration updated successfully.');

			// Send email notification based on status
			$this->send_email_confirmation(
				$data['email'],
				$data['name'],
				$data['lname'],
				$data['appointment_status'],
				$data['appointment_date'],
				$data['appointment_time']
			);
		} else {
			$this->session->set_flashdata('error', 'Failed to update the registration.');
		}

		redirect('dashboard/admin/index');
	}


	// Send Email using PHP mail() function
	private function send_email_confirmation($recipient_email, $name, $lname, $status, $appointment_date = null, $appointment_time = null)
	{
		$this->load->helper('email_helper');

		$subject = '';
		$message = '';

		if ($status === 'booked') {
			$subject = 'Appointment Confirmation';
			$message = "
			<html>
			<head>
				<title>Appointment Confirmation</title>
			</head>
			<body>
				<p>Dear $name $lname,</p>
				<p>Your appointment at Mendoza Clinic has been successfully booked. Below are the details of your appointment:</p>
				<ul>
					<li><strong>Date:</strong> $appointment_date</li>
					<li><strong>Time:</strong> $appointment_time</li>
					<li><strong>Location:</strong> A Morales St, Santa Maria, Bulacan</li>
				</ul>
				<p>Please ensure that you arrive at least 15 minutes before your scheduled appointment time.</p>
				<p>Thank you for choosing Mendoza Clinic. We look forward to seeing you!</p>
				<br>
				<p>Best regards,</p>
				<p>The Mendoza Clinic Team</p>
			</body>
			</html>";
		} elseif ($status === 'cancelled') {
			$subject = 'Appointment Cancellation';
			$message = "
			<html>
			<head>
				<title>Appointment Cancellation</title>
			</head>
			<body>
				<p>Dear $name $lname,</p>
				<p>We regret to inform you that your appointment has been cancelled. Below are the details of the cancelled appointment:</p>
				<ul>
					<li><strong>Date:</strong> $appointment_date</li>
					<li><strong>Time:</strong> $appointment_time</li>
					<li><strong>Location:</strong> A Morales St, Santa Maria, Bulacan</li>
				</ul>
				<p>If you wish to reschedule, please visit our appointment portal or contact us directly.</p>
				<p>We apologize for any inconvenience caused.</p>
				<br>
				<p>Best regards,</p>
				<p>The Mendoza Clinic Team</p>
			</body>
			</html>";
		} elseif ($status === 'reminder_sent') {
			$subject = 'Appointment Reminder';
			$message = "
			<html>
			<head>
				<title>Appointment Reminder</title>
			</head>
			<body>
				<p>Dear $name $lname,</p>
				<p>This is a reminder for your upcoming appointment at Mendoza Clinic. Below are the details of your appointment:</p>
				<ul>
					<li><strong>Date:</strong> $appointment_date</li>
					<li><strong>Time:</strong> $appointment_time</li>
					<li><strong>Location:</strong> A Morales St, Santa Maria, Bulacan</li>
				</ul>
				<p>Please ensure that you arrive at least 15 minutes before your scheduled appointment time.</p>
				<p>Thank you for choosing Mendoza Clinic. We look forward to seeing you!</p>
				<br>
				<p>Best regards,</p>
				<p>The Mendoza Clinic Team</p>
			</body>
			</html>";
		} elseif ($status === 'reschedule') {
			$subject = 'Appointment Rescheduled';
			$message = "
			<html>
			<head>
				<title>Appointment Rescheduled</title>
			</head>
			<body>
				<p>Dear $name $lname,</p>
				<p>Your appointment at Mendoza Clinic has been successfully rescheduled. Below are the updated details of your appointment:</p>
				<ul>
					<li><strong>New Date:</strong> $appointment_date</li>
					<li><strong>New Time:</strong> $appointment_time</li>
					<li><strong>Location:</strong> A Morales St, Santa Maria, Bulacan</li>
				</ul>
				<p>Please ensure that you arrive at least 15 minutes before your scheduled appointment time.</p>
				<p>Thank you for choosing Mendoza Clinic. We look forward to seeing you!</p>
				<br>
				<p>Best regards,</p>
				<p>The Mendoza Clinic Team</p>
			</body>
			</html>";
		} elseif ($status === 'follow_up') {
			$subject = 'Follow-Up Appointment Scheduled';
			$message = "
			<html>
			<head>
				<title>Follow-Up Appointment</title>
			</head>
			<body>
				<p>Dear $name $lname,</p>
				<p>This is a notification for your follow-up appointment at Mendoza Clinic. Below are the details:</p>
				<ul>
					<li><strong>Date:</strong> $appointment_date</li>
					<li><strong>Time:</strong> $appointment_time</li>
					<li><strong>Location:</strong> A Morales St, Santa Maria, Bulacan</li>
				</ul>
				<p>Please confirm your availability and ensure that you arrive at least 15 minutes before your scheduled time.</p>
				<p>Thank you for trusting Mendoza Clinic with your health. We look forward to seeing you!</p>
				<br>
				<p>Best regards,</p>
				<p>The Mendoza Clinic Team</p>
			</body>
			</html>";
		}

		if (send_email_simple($recipient_email, $subject, $message)) {
			$this->session->set_flashdata('success', ucfirst($status) . ' email sent successfully.');
		} else {
			$this->session->set_flashdata('error', 'Failed to send ' . $status . ' email.');
			log_message('error', "Online appointment email failed to {$recipient_email} for status: {$status}");
		}
	}




	public function edit($id)
	{
		$this->load->model('Registration_model');

		// Get registration data by ID
		$data['registration'] = $this->Registration_model->getRegistrationById($id);

		// Load the edit view and pass the data
		$this->load->view('edit_view', $data);
	}

	public function delete($id)
	{
		$this->OnlineAppointments_model->delete_appointment($id);
		redirect('onlineappointments');
	}

	public function approve($id)
	{
		$data = array('appointment_status' => 'booked');
		$this->OnlineAppointments_model->update_appointment($id, $data);
		$this->session->set_flashdata('success', 'Appointment approved successfully.');
		// Send confirmation email to the registrant
		$registration = $this->Registration_model->get_registration_by_id($id);
		if (!empty($registration)) {
			$this->send_email_confirmation(
				$registration['email'],
				$registration['name'],
				$registration['lname'],
				'booked',
				$registration['appointment_date'] ?? null,
				$registration['appointment_time'] ?? null
			);
		}

		redirect('onlineappointments');
	}

	public function reject($id)
	{
		$data = array('appointment_status' => 'cancelled');
		$this->OnlineAppointments_model->update_appointment($id, $data);
		$this->session->set_flashdata('success', 'Appointment rejected successfully.');
		// Send cancellation email to the registrant
		$registration = $this->Registration_model->get_registration_by_id($id);
		if (!empty($registration)) {
			$this->send_email_confirmation(
				$registration['email'],
				$registration['name'],
				$registration['lname'],
				'cancelled',
				$registration['appointment_date'] ?? null,
				$registration['appointment_time'] ?? null
			);
		}

		redirect('onlineappointments');
	}

	public function index()
	{
		$this->load->model('Registration_model');
		$data['registrations'] = $this->Registration_model->get_all_online_appointments();
		$this->load->view('r_assets/navbar');
		$this->load->view('r_assets/sidebar');
		// Load the view and pass the data
		$this->load->view('onlineappointments/index', $data);
	}




	public function update($id)
	{
		// Define lunch break slots
		$lunchBreakSlots = ['11:00', '12:30', '17:00'];

		// Get existing appointment data based on the ID
		$existingAppointment = $this->Registration_model->get_appointment_by_id($id);

		// Check if appointment exists
		if (!$existingAppointment) {
			$this->session->set_flashdata('error', 'Appointment not found.');
			redirect('onlineappointments');
		}

		// Get form input data
		$data = array(
			'email' => $this->input->post('email'),
			'name' => $this->input->post('firstname'),
			'mname' => $this->input->post('mname'),
			'lname' => $this->input->post('lastname'),
			'marital_status' => $this->input->post('marital_status'),
			'husband' => $this->input->post('husband'),
			'husband_phone' => $this->input->post('husband_phone'),
			'patient_contact_no' => $this->input->post('contact_number'),
			'philhealth_id' => $this->input->post('philhealth_id'),
			'birthday' => $this->input->post('birthday'),
			'age' => date_diff(date_create($this->input->post('birthday')), date_create('today'))->y,
			'address' => $this->input->post('address'),
			'occupation' => $this->input->post('occupation'),
			'appointment_date' => $this->input->post('appointment_date'),
			'appointment_time' => $this->input->post('appointment_time'),
			'last_update' => date('Y-m-d H:i:s')
		);

		// Check if the selected time is a lunch break slot
		if (in_array($data['appointment_time'], $lunchBreakSlots)) {
			$this->session->set_flashdata('warning', 'The selected time slot is during lunch break. Please choose a different time.');
			redirect('onlineappointments/edit/' . $id);
			return;
		}

		// Check if the time slot is already booked (excluding the current appointment)
		if ($this->OnlineAppointments_model->is_time_booked($data['appointment_date'], $data['appointment_time'], $id)) {
			$this->session->set_flashdata('warning', 'The selected time slot is already booked. Please choose a different time.');
			redirect('onlineappointments/edit/' . $id);
		} else {
			if ($this->Registration_model->update_appointment($id, $data)) {
				$this->session->set_flashdata('success', 'Your appointment has been successfully updated!');
				// If the appointment status changed, send an email notification
				$newStatus = isset($data['appointment_status']) ? $data['appointment_status'] : null;
				$oldStatus = isset($existingAppointment['appointment_status']) ? $existingAppointment['appointment_status'] : null;
				if (!empty($newStatus) && $newStatus !== $oldStatus) {
					$this->send_email_confirmation(
						$data['email'],
						$data['name'],
						$data['lname'],
						$newStatus,
						$data['appointment_date'] ?? null,
						$data['appointment_time'] ?? null
					);
				}
			} else {
				$this->session->set_flashdata('error', 'There was an issue updating your appointment. Please try again.');
			}
			redirect('onlineappointments');
		}
	}
}
