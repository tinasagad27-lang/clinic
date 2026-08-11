<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Simple Email Helper using PHP's mail() function
 * Works without OpenSSL/SMTP
 */

if (!function_exists('send_email_simple')) {
    /**
     * Send email using CodeIgniter's Email library with SMTP, falling back to PHP mail().
     *
     * @param string $to Email address to send to
     * @param string $subject Email subject
     * @param string $message Email message (HTML)
     * @param string $from From email address
     * @param string $from_name From name
     * @return boolean
     */
    function send_email_simple($to, $subject, $message, $from = null, $from_name = null)
    {
        $ci = &get_instance();
        $ci->config->load('email', true);

        if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'Email send skipped because recipient address is invalid: ' . $to);
            return false;
        }

        if (empty($from)) {
            $from = $ci->config->item('mail_from', 'email') ?: $ci->config->item('smtp_user', 'email') ?: 'no-reply@mendozaclinic.local';
        }

        if (empty($from_name)) {
            $from_name = $ci->config->item('mail_from_name', 'email') ?: 'Mendoza Clinic';
        }

        $email_config = array(
            'protocol'    => $ci->config->item('protocol', 'email') ?: 'smtp',
            'smtp_host'   => $ci->config->item('smtp_host', 'email'),
            'smtp_user'   => $ci->config->item('smtp_user', 'email'),
            'smtp_pass'   => $ci->config->item('smtp_pass', 'email'),
            'smtp_port'   => $ci->config->item('smtp_port', 'email') ?: 587,
            'smtp_crypto' => $ci->config->item('smtp_crypto', 'email') ?: 'tls',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'wordwrap'    => TRUE,
            'newline'     => "\r\n",
        );

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . $from_name . " <" . $from . ">\r\n";
        $headers .= "Reply-To: " . $from . "\r\n";

        $smtp_enabled = !empty($email_config['smtp_host']) && !empty($email_config['smtp_user']) && !empty($email_config['smtp_pass']);
        $transports = stream_get_transports();
        $transport_available = !empty($email_config['smtp_crypto']) && in_array(strtolower($email_config['smtp_crypto']), $transports, true);

        if ($smtp_enabled && $transport_available) {
            try {
                $ci->load->library('email');
                $ci->email->initialize($email_config);
                $ci->email->set_mailtype('html');
                $ci->email->set_newline("\r\n");
                $ci->email->set_crlf("\r\n");
                $ci->email->from($from, $from_name);
                $ci->email->to($to);
                $ci->email->subject($subject);
                $ci->email->message($message);

                if ($ci->email->send()) {
                    return true;
                }

                log_message('error', 'SMTP email failed: ' . $ci->email->print_debugger(['headers']));
            } catch (Exception $e) {
                log_message('error', 'SMTP email exception: ' . $e->getMessage());
            }
        } else {
            log_message('info', 'SMTP is not available for email delivery; using PHP mail() fallback.');
        }

        return mail($to, $subject, $message, $headers);
    }
}
