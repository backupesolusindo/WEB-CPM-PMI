<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JwtMiddleware
{
    public function checkAuth()
    {
        $CI = & get_instance();
        $CI->load->library('JwtAuth');

        $headers = $CI->input->get_request_header('Authorization');
        $token = str_replace('Bearer ', '', $headers);

        if (!$token || !$CI->jwtauth->verifyToken($token)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'message' => 'Unauthorized']);
            exit;
        }
    }
}
