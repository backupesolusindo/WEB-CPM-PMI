<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_middleware
{
    public function verify_token()
    {
        $ci = &get_instance(); // Panggil instance CI di dalam metode
        $ci->load->helper('url');

        // Ambil token dari header Authorization
        $headers = $this->get_authorization_header();
        $token = null;

        if (!empty($headers) && preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            $token = $matches[1];
        }

        // Debugging token


        if (!$token) {
            // Jika tidak ada token, kirim respons error
            $this->send_error_response(401, 'Token tidak ditemukan');
        }

        // Verifikasi token dengan API OAuth2
        if (!$this->verify_token_with_oauth2_api($token)) {
            // Jika token tidak valid, kirim respons error
            $this->send_error_response(401, 'Token tidak valid atau kadaluarsa');
        }

        return true;
    }

    private function get_authorization_header()
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            return trim($_SERVER['HTTP_AUTHORIZATION']);
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            foreach ($headers as $key => $value) {
                if (strcasecmp($key, 'Authorization') === 0) {
                    return trim($value);
                }
            }
        }

        return null;
    }

    private function verify_token_with_oauth2_api($token)
    {
        $ci = &get_instance();
        $ci->load->helper('url');
        $url = base_url('Api/Oauth2/verify_token');

        log_message('debug', 'Verifying token: ' . $token);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        log_message('debug', 'Response from OAuth2 API: ' . $result);
        log_message('debug', 'HTTP Code: ' . $http_code);

        if ($http_code !== 200 || !$result) {
            log_message('error', 'OAuth2 API error: No valid response');
            return false;
        }

        $response = json_decode($result, true) ?? [];

        if (isset($response['is_valid']) && $response['is_valid'] === true) {
            log_message('debug', 'Token is valid');
            return true;
        } else {
            log_message('error', 'Token verification failed. Response: ' . json_encode($response));
            return false;
        }
    }

    private function send_error_response($status_code, $message)
    {
        $ci = &get_instance();
        $ci->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output(json_encode([
                'status' => false,
                'message' => $message
            ]))
            ->_display();
        exit;
    }
}
