<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Use PHP's mail() function instead of SMTP
// This requires a mail server configured on the system (sendmail, postfix, etc.)
// For Windows: configure SMTP in php.ini

$config['use_smtp'] = FALSE;  // Use mail() function instead of SMTP
$config['mail_from'] = 'chrstnsgd10@gmail.com';
$config['mail_from_name'] = 'Mendoza Clinic';
