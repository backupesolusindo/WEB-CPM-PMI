<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelAuth extends CI_Model
{
    public function verify_token()
    {
        // $ci = &get_instance();  
        // $ci->load->library('Auth_middleware');

        // if (!$ci->auth_middleware->verify_token()) {
        //     $ci->output
        //         ->set_status_header(401)
        //         ->set_content_type('application/json', 'utf-8')
        //         ->set_output(json_encode([
        //             'status' => false,
        //             'message' => 'Unauthorized: Token tidak valid atau kadaluarsa'
        //         ]))
        //         ->_display();
        //     exit;
        // }
    }
}
