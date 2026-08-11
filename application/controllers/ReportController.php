<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportController extends CI_Controller {

    // Constructor
    public function __construct()
    {
        parent::__construct();
        // Load models
        $this->load->model('appointment_model'); // Appointment model
        $this->load->model('registration_model'); // Registration model
    }

	public function export_to_csv($start_date, $end_date)
	{
		date_default_timezone_set('Asia/Manila'); // Set timezone to Philippine time
	
		$this->load->model('appointment_model');
		$this->load->model('registration_model');
	
		// Get appointments and registrations within the date range
		$appointments = $this->appointment_model->get_appointments_by_date($start_date, $end_date);
		$registrations = $this->registration_model->get_registrations_with_service_type($start_date, $end_date); // Updated to include service type
	
		// Output CSV headers
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="report_' . $start_date . '_to_' . $end_date . '.csv"');
	
		$output = fopen('php://output', 'w');
		fputcsv($output, ['First Name', 'Middle Name', 'Last Name', 'Scheduled', 'Assigned Doctor', 'Service Type']);
	
		// Write registration data to CSV
		foreach ($registrations as $registration) {
			fputcsv($output, [
				$registration->name,
				$registration->mname,
				$registration->lname,
				date('F j, Y, g:i A', strtotime($registration->created_at)), // Full date and time
				$registration->doctor ?? 'N/A',
				$registration->service_type ?? 'N/A' // Add service type
			]);
		}
	
		fclose($output);
	}
	

}
?>
