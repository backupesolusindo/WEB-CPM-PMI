<?php
// defined('BASEPATH') or exit('No direct script access allowed');



// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;   

// class JwtAuth
// {
//     private $key;
//     private $algorithm;

//     public function __construct()
//     {
//         $this->CI = &get_instance();
//         $this->key = $this->CI->config->item('jwt_key');
//         $this->algorithm = $this->CI->config->item('jwt_algorithm');

//     }



//     public function generateToken($data, $expiry = 3600)
//     {
//         $issuedAt = time();
//         $payload = [
//             'iat' => $issuedAt,
//             'exp' => $issuedAt + $expiry,
//             'data' => $data
//         ];
//         return JWT::encode($payload, $this->key, $this->algorithm);
//     }

//     public function verifyToken($token)
//     {
//         try {
//             return JWT::decode($token, new Key($this->key, $this->algorithm));
//         } catch (Exception $e) {
//             log_message('error', 'JWT Verification Failed: ' . $e->getMessage());
//             return false;
//         }
//     }
// }
