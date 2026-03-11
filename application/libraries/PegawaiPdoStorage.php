<?php
defined('BASEPATH') or exit('No direct script access allowed');

use OAuth2\Storage\Pdo;
use OAuth2\Storage\UserCredentialsInterface;

class PegawaiPdoStorage extends Pdo implements UserCredentialsInterface
{

    /**
     * Override metode checkUserCredentials agar menggunakan tabel pegawai.
     *
     * @param string $username (NIP)
     * @param string $password (plain text)
     * @return bool
     */
    public function checkClientCredentials($client_id, $client_secret = null)
    {
        $CI = &get_instance();
        $CI->load->database();

        $query = $CI->db->where('client_id', $client_id)
            ->get('oauth_clients');

        $client = $query->row_array();

        if (!$client) {
            return false;
        }

        // Jika client_secret di database dalam bentuk hash, gunakan password_verify()
        if (password_verify($client_secret, $client['client_secret'])) {
            return true;
        }

        // Jika client_secret masih dalam plain text (sementara), bandingkan langsung
        return $client['client_secret'] === $client_secret;
    }


    public function getClientDetails($client_id)
    {
        $CI = &get_instance();
        $CI->load->database();

        $query = $CI->db->where('client_id', $client_id)
            ->get('oauth_clients');

        return $query->row_array();
    }
}

/**
 * Override metode getUserDetails agar mengembalikan data dari tabel pegawai.
 *
 * @param string $username (NIP)
 * @return array|false
 */

    // public function getUserDetails($username)
    // {
    //     $CI = &get_instance();
    //     $CI->load->database();

    //     $query = $CI->db->where('NIP', $username)->get('pegawai');
    //     $user = $query->row();

    //     if (!$user) {
    //         return false;
    //     }

    //     // Kembalikan data minimal: gunakan uuid sebagai user_id dan NIP sebagai username
    //     return array(
    //         'user_id'  => $user->uuid,
    //         'username' => $user->NIP
    //     );
    // }

    // public function checkUserCredentials($username, $password)
    // {
    //     log_message('debug', "Fungsi checkUserCredentials dipanggil dengan NIP: $username");

    //     $CI = &get_instance();
    //     $CI->load->database();

    //     $query = $CI->db->where('NIP', $username)->get('pegawai');
    //     $user = $query->row();

    //     if (!$user) {
    //         log_message('error', "Login gagal: NIP $username tidak ditemukan.");
    //         return false;
    //     }

    //     log_message('debug', "Hash dari database: " . $user->password);
    //     log_message('debug', "Password yang diuji: " . $password);

    //     if (!password_verify($password, $user->password)) {
    //         log_message('error', "Login gagal: Password salah untuk NIP $username.");
    //         return false;
    //     }

    //     log_message('info', "Login berhasil untuk NIP $username.");
    //     return true;
    // }
