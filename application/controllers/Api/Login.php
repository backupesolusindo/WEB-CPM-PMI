<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model("ModelPegawai");
    $this->load->model("ModelKampus");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  public function aksi_login()
  {

    $nip = $this->input->post('nip');
    $password = $this->input->post('password');
    $token = $this->input->post('token');
    $data_session = array();
    $mesage_respone = array();
    $res = array();
    $response = array();
    // $nip = "admin";
    // $password = "rahasiasby45";
    $cek = $this->ModelPegawai->cek_pegawai($nip);
    $res = array();
    $data_pegawai = array(
      'uuid'      => "",
      'nip'      => "",
      'pegawai'  => "",
      'unit'  => "",
      'spesial'  => 0,
    );
    if ($cek->num_rows() > 0) {
      $data_login = $cek->row_array();
      if ($data_login['NIP'] == null) {
        $res = array(
          'message' => "Maaf Anda Salah NIP/NIK/Email Dan Password",
          'status' => 3
        );
      } else {

        $pw_valid = $data_login['password'];
        if (password_verify($password, $pw_valid)) {
          $data_pegawai = array(
            'uuid'      => $data_login['uuid'],
            'nip'      => $data_login['NIP'],
            'nama'  => $data_login['nama_pegawai'],
            'unit'  => $data_login['unit'],
            'token' => $token,
            'spesial' => $data_login['spesial'],
          );

          $kampus = $this->ModelKampus->get_edit("1")->row_array();
          $cek_kampus = $this->ModelKampus->get_kampus($data_login['unit']);
          if ($cek_kampus->num_rows() > 0) {
            $kampus = $cek_kampus->row_array();
          }
          if ($data_login['status_aktif'] == 0) {
            $res = array(
              'message' => "Maaf Akun Anda Tidak Aktif",
              'kampus'  => $kampus,
              'status' => 500
            );
          } else {
            if ($data_login['status_login'] == 0) {
              $this->db->where("NIP", $nip);
              $this->db->update("pegawai", array('status_login' => "1", 'token' => $token));
              $res = array(
                'message' => "Berhasil Login",
                'kampus'  => $kampus,
                'status' => 200
              );
            } else {
              $res = array(
                'message' => "Anda Sudah Login Dengan Device lain",
                'kampus'  => $kampus,
                'status' => 500
              );
            }
          }
        } else {
          $res = array(
            'message' => "Maaf Anda Salah Password",
            'kampus'  => $this->ModelKampus->get_edit("1")->row_array(),
            'status' => 501
          );
        }
      }
    } else {
      $res = array(
        'message' => "Maaf Anda Salah NIP / Email Dan Password",
        'kampus'  => $this->ModelKampus->get_edit("1")->row_array(),
        'status' => 502
      );
    }
    // echo json_encode($data_pegawai);
    echo json_encode(array(
      'response' => $data_pegawai,
      'message' => $res,
      'post'    => $this->input->post()
    ));
  }



  public function getPegawai($username)
  {
    $credentials = $this->core->getAccessApi();

    // Filter Dengan NIP
    $param = array(
      "nip"               => $username,
      "informations"      => false,
      "with_informations" => false,
    );
    $curl = curl_init("http://api.polije.ac.id/resources/kepegawaian/pegawai" . "?" . http_build_query($param));
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $credentials['accessToken'], "User-Agent: " . strtolower($_SERVER['HTTP_USER_AGENT'])));

    $json_response = curl_exec($curl);
    curl_close($curl);

    // Filter Dengan Email
    if (sizeof(json_decode($json_response)) < 1) {
      $param = array(
        "email"               => $username,
        "informations"      => false,
        "with_informations" => false,
      );
      $curl = curl_init("http://api.polije.ac.id/resources/kepegawaian/pegawai" . "?" . http_build_query($param));
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
      curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $credentials['accessToken'], "User-Agent: " . strtolower($_SERVER['HTTP_USER_AGENT'])));

      $json_response = curl_exec($curl);
      curl_close($curl);
    }

    // Filter Dengan KTP
    if (sizeof(json_decode($json_response)) < 1) {
      $param = array(
        "no_ktp"            => $username,
        "informations"      => false,
        "with_informations" => false,
      );
      $curl = curl_init("http://api.polije.ac.id/resources/kepegawaian/pegawai" . "?" . http_build_query($param));
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
      curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $credentials['accessToken'], "User-Agent: " . strtolower($_SERVER['HTTP_USER_AGENT'])));

      $json_response = curl_exec($curl);
      curl_close($curl);
    }

    $data = array(
      'size' => sizeof(json_decode($json_response)),
      'res'  => json_decode($json_response)
    );
    return $data;
  }

  public function test()
  {
    $getPegawai = $this->getPegawai("3509204301840002");
    echo json_encode($getPegawai);
  }

  function set_token()
  {
    $uuid = $this->input->post("uuid");
    $token = $this->input->post("token");
    $this->db->where("uuid", $uuid);
    if ($this->db->update("pegawai", array('token' => $token))) {
      $res = array(
        'message' => "Set Token Berhasil",
        'status' => 200
      );
    } else {
      $res = array(
        'message' => "Anda Sudah Login Dengan Device lain",
        'status' => 500
      );
    }
    echo json_encode(array('response' => array($uuid, $token), 'message' => $res));
  }

  function resetPassword()
  {
    $uuid = $this->input->post("UUID");
    $pass = password_hash($this->input->post('password'), PASSWORD_DEFAULT, array("cost" => 10));
    $this->db->where("uuid", $uuid);
    if ($this->db->update("pegawai", array('password' => $pass))) {
      $res = array(
        'message' => "Resep Password Berhasil",
        'status' => 200
      );
    } else {
      $res = array(
        'message' => "Mohon cek koneksi Anda,",
        'status' => 500
      );
    }
    echo json_encode(array('response' => array($uuid), 'message' => $res));
  }






  public function aksi_login_monitoring()
  {

    $nip = $this->input->post('nip');
    $password = $this->input->post('password');
    $data_session = array();
    $mesage_respone = array();
    $res = array();
    $response = array();
    // $nip = "admin";
    // $password = "rahasiasby45";
    $cek = $this->ModelPegawai->cek_pegawai($nip);
    $res = array();
    $data_pegawai = array();
    $data_pegawai = array(
      'uuid'      => "",
      'nip'      => "",
      'pegawai'  => "",
      'unit'      => ""
    );
    if ($cek->num_rows() > 0) {
      $data_login = $cek->row_array();
      if ($data_login['NIP'] == null) {
        $res = array(
          'message' => "Maaf Anda Salah nip Dan Password",
          'status' => 3
        );
      } else {
        $pw_valid = $data_login['password'];
        if (password_verify($password, $pw_valid)) {
          $data_pegawai = array(
            'uuid'      => $data_login['uuid'],
            'nip'      => $data_login['NIP'],
            'nama'  => $data_login['nama_pegawai'],
            'unit'  => $data_login['unit']
          );
          if ($data_login['status_monitoring'] == "1") {
            $res = array(
              'message' => "Berhasil Login",
              'status' => 200
            );
          } else {
            $res = array(
              'message' => "Anda Tidak Punya Akses Monitoring Presensi",
              'status' => 500
            );
          }
        } else {
          $res = array(
            'message' => "Maaf Anda Salah Password",
            'status' => 501
          );
        }
      }
    } else {
      $res = array(
        'message' => "Maaf Anda Salah nip Dan Password",
        'status' => 502
      );
    }
    // echo json_encode($data_pegawai);
    echo json_encode(array('response' => $data_pegawai, 'message' => $res));
  }

  public function aksi_logout()
  {
    $uuid = $this->input->post("uuid");
    $this->db->where('uuid', $uuid);
    if ($this->db->update('pegawai', array('status_login' => 0))) {
      $res = array(
        'message' => "Berhasil Logout",
        'status' => 200
      );
    } else {
      $res = array(
        'message' => "Mohon Untuk Cek Koneksi Internet Anda",
        'status' => 500
      );
    }
    echo json_encode(array('message' => $res));
  }
}
