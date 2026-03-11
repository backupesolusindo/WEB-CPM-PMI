<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelOauth2 extends CI_Model
{
    // Simpan access token
    public function store_tokens($user_id, $accessToken, $expiresAt)
    {
        // Hapus token lama jika ada (berdasarkan user_id)
        $this->db->where('user_id', $user_id)->delete('oauth_access_tokens');

        // Simpan access token baru
        $this->db->insert('oauth_access_tokens', [
            'access_token' => $accessToken,
            'user_id'      => $user_id, // Simpan ID user yang memiliki token
            'expires'      => $expiresAt
        ]);
    }

    // Ambil token terbaru untuk user tertentu
    public function get_latest_token($user_id)
    {
        $query = $this->db->where('user_id', $user_id)
            ->where('expires >', date('Y-m-d H:i:s'))
            ->order_by('expires', 'DESC')
            ->limit(1)
            ->get('oauth_access_tokens');

        return $query->row();
    }

    // Cek apakah token valid dan ambil data user
    public function get_token($accessToken)
    {
        $query = $this->db->where('access_token', $accessToken)
            ->where('expires >', date('Y-m-d H:i:s'))
            ->get('oauth_access_tokens');

        return $query->row();
    }

    // Ambil access token baru jika expired
    public function get_new_access_token()
    {
        $client_id = 'client123';
        $client_secret = 'secretXYZ';

        // Buat request token langsung dalam aplikasi tanpa cURL
        $_POST['grant_type'] = 'client_credentials';
        $_POST['client_id'] = $client_id;
        $_POST['client_secret'] = $client_secret;

        ob_start();
        $this->load->controller('Oauth2')->token();
        $tokenResponse = ob_get_clean();

        $tokenData = json_decode($tokenResponse, true);

        if (isset($tokenData['access_token'])) {
            $this->store_tokens($client_id, $tokenData['access_token'], date('Y-m-d H:i:s', time() + 1800));
            return $tokenData['access_token'];
        }

        return null;
    }
}
