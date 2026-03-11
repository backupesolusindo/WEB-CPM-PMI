<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . "third_party/OAuth2/Autoloader.php";
OAuth2\Autoloader::register();

use OAuth2\Server;
use OAuth2\Request;
use OAuth2\Response;
use OAuth2\GrantType\ClientCredentials;

require_once APPPATH . "libraries/PegawaiPdoStorage.php";
require_once APPPATH . "libraries/UserCredentialsNoRefresh.php";

class Oauth2 extends CI_Controller
{
    private $server;

    public function __construct()
    {
        parent::__construct();

        // Gunakan konfigurasi database dari CodeIgniter
        $dsn = 'mysql:dbname=' . $this->db->database . ';host=' . $this->db->hostname;
        $username = $this->db->username;
        $password = $this->db->password;

        // Gunakan custom storage yang kita buat
        $storage = new PegawaiPdoStorage([
            'dsn'      => $dsn,
            'username' => $username,
            'password' => $password
        ]);

        // Buat instance Server dengan access_lifetime 30 menit (1800 detik)
        $this->server = new Server($storage, [
            'access_lifetime' => 1800, // 30 menit
            'always_issue_refresh_token' => false
        ]);

        // Tambahkan grant type password
        $this->server->addGrantType(new ClientCredentials($storage));
        $this->server->addGrantType(new \OAuth2\GrantType\UserCredentialsNoRefresh($storage));
    }

    // Endpoint untuk mendapatkan token
    public function token()
    {
        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        $this->server->handleTokenRequest($request, $response)->send();
    }


    public function verify_token()
    {
        $request = Request::createFromGlobals();
        $response = new Response();

        if (!$this->server->verifyResourceRequest($request, $response)) {
            $response_data = [
                'status' => 401,
                'is_valid' => false,
                'error' => 'Token tidak valid atau kadaluarsa'
            ];
            return $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode($response_data));
        }

        $token_data = $this->server->getAccessTokenData($request);
        $response_data = [
            'status' => 200,
            'is_valid' => true,
            'token_data' => $token_data
        ];

        return $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($response_data));
    }


    // Fungsi untuk mendapatkan informasi dari token
    public function get_token_data($token)
    {
        $request = new Request();
        $request->headers['Authorization'] = 'Bearer ' . $token;

        if (!$this->server->verifyResourceRequest($request)) {
            return false;
        }

        return $this->server->getAccessTokenData($request);
    }
}
