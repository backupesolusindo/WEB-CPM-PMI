<?php
// defined('BASEPATH') or exit('No direct script access allowed');

// use \Firebase\JWT\JWT;

// if (!function_exists('generate_jwt')) {
//     function generate_jwt($payload)
//     {
//         $CI = &get_instance();
//         $CI->config->load('jwt', TRUE);
//         $key = $CI->config->item('jwt_key', 'jwt');
//         $algorithm = $CI->config->item('jwt_algorithm', 'jwt');
//         return JWT::encode($payload, $key, $algorithm);
//     }
// }

// if (!function_exists('validate_jwt')) {
//     function validate_jwt($token)
//     {
//         $CI = &get_instance();
//         $CI->config->load('jwt', TRUE);
//         $key = $CI->config->item('jwt_key', 'jwt');
//         $algorithm = $CI->config->item('jwt_algorithm', 'jwt');
//         try {
//             return JWT::decode($token, $key, array($algorithm));
//         } catch (Exception $e) {
//             return FALSE;
//         }
//     }
// }
