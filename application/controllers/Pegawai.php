<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('ModelPegawai');
    $this->load->model('ModelJabatan');
    $this->load->model('ModelUnit');

    // $this->load->library('Core');
  }

  function index()
  {
    // $this->load->library('Core');
    $array = array(
      'title'       => "Pegawai",
      'body'        => "Pegawai/list",
      // 'btn_title'   => "<a href=\"".base_url()."Pegawai/input\"><button type=\"button\" class=\"btn btn-info d-none d-lg-block m-l-15\"><i class=\"fa fa-plus-circle\"></i> Tambah Karyawan</button></a>",
      'Pegawai'     => $this->ModelPegawai->get_list()->result()
    );

    $this->load->view('index', $array);
  }

  function input()
  {
    $array = array(
      'title'       => "Pegawai",
      'body'        => "Pegawai/input",
      'jabatan'     => $this->ModelJabatan->get_data()->result(),
      'unit'        => $this->ModelUnit->get_unit()->result()
    );
    $this->load->view('index', $array);
  }

  function insert()
  {
    $data = array(
      'uuid'              => $this->input->post('nip'),
      'NIP'               => $this->input->post('nip'),
      'NIK'               => $this->input->post('nip'),
      'nama_pegawai'      => $this->input->post('nama_pegawai'),
      'email'             => $this->input->post('email'),
      'jab_struktur'      => $this->input->post('jabatan'),
      'unit'              => $this->input->post('unit'),
      'jenis_unit'        => $this->input->post('jenis_unit'),
    );
    if ($this->db->insert('pegawai', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Menambahkan Pegawai Baru."));
      redirect('Pegawai');
    } else {
      echo "gagal";
    }
  }

  function edit($id)
  {
    $array = array(
      'title'       => "Pegawai",
      'body'        => "Pegawai/update",
      'pegawai'     => $this->ModelPegawai->edit($id)->row_array(),
      'jabatan'     => $this->ModelJabatan->get_data()->result(),
      'unit'        => $this->ModelUnit->get_unit()->result()
    );
    $this->load->view('index', $array);
  }

  function hapus($id)
  {
    $this->db->where("uuid", $id);
    if ($this->db->update('pegawai', array('status_aktif' => 0))) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Reset Login "));
      redirect('Pegawai');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gaga Reset Login"));
      redirect('Pegawai');
    }
  }

  function update()
  {
    $id = $this->input->post('id');
    $data = array(
      'NIP'               => $this->input->post('nip'),
      'NIK'               => $this->input->post('nip'),
      'nama_pegawai'      => $this->input->post('nama_pegawai'),
      'email'             => $this->input->post('email'),
      'jab_struktur'      => $this->input->post('jabatan'),
      'unit'              => $this->input->post('unit'),
      'jenis_unit'        => $this->input->post('jenis_unit'),
    );
    $this->db->where('uuid', $id);
    if ($this->db->update('pegawai', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Merubah Data Pegawai " . $this->input->post('nama')));
      redirect('Pegawai');
    } else {
      echo "gagal";
    }
  }

  function reset_login($id)
  {
    $this->db->where('uuid', $id);
    if ($this->db->update('pegawai', array('status_login' => 0))) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Reset Login "));
      redirect('Pegawai');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gaga Reset Login"));
      redirect('Pegawai');
    }
  }

  function reset_password($id)
  {
    $this->db->where('uuid', $id);
    if ($this->db->update('pegawai', array('password' => password_hash("pmijember", PASSWORD_DEFAULT, array("cost" => 10))))) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Reset Password "));
      redirect('Pegawai');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gaga Reset Password"));
      redirect('Pegawai');
    }
  }

  function sinkron()
  {
    $credentials = $this->core->getAccessApi();

    $param = array(
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

    $data = array(
      'size' => sizeof(json_decode($json_response)),
      'res'  => json_decode($json_response)
    );
    // echo json_encode($data);
    foreach (json_decode($json_response) as $apiPegawai) {
      $arUpdate = array(
        'NIP'              => $apiPegawai->nip,
        'NIK'              => $apiPegawai->no_ktp,
        'nama_asli'        => $apiPegawai->nama_lengkap,
        'nama_pegawai'     => $apiPegawai->gelar_depan . "" . $apiPegawai->nama_lengkap . ", " . $apiPegawai->gelar_belakang,
        'tipe_pegawai'     => $apiPegawai->tipe_pegawai,
        'email'            => $apiPegawai->email,
        'unit'             => $apiPegawai->unit,
        'jenis_unit'       => $apiPegawai->jenis_unit,
        'jab_struktur'     => $apiPegawai->jab_struktur,
      );
      $jml_uuid = $this->db->get_where("pegawai", array('uuid' => $apiPegawai->uuid))->num_rows();
      if ($jml_uuid > 0) {
        $this->db->where("uuid", $apiPegawai->uuid);
        $this->db->update("pegawai", $arUpdate);
      } else {
        if ($apiPegawai->nip != "-") {
          $this->db->where("NIP", $apiPegawai->nip);
          $this->db->update("pegawai", $arUpdate);
        }
      }
      // echo $value->uuid;
      $this->db->where("namajabatan", $apiPegawai->jab_struktur);
      if ($this->db->get("jabatan")->num_rows() < 1) {
        $this->db->insert(
          "jabatan",
          array(
            'idjabatan' => trim($apiPegawai->jab_struktur),
            'namajabatan' => $apiPegawai->jab_struktur,
            'tampil'    => "1"
          )
        );
      }
    }
    $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Sinkronisasi Data Pegawai "));
    redirect('Pegawai');
  }

  function belum()
  {
    $credentials = $this->core->getAccessApi();

    $param = array(
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

    $array = array(
      'title'       => "Pegawai",
      'body'        => "Pegawai/list_belum",
      'Pegawai'     => json_decode($json_response)
    );

    $this->load->view('index', $array);
  }
}
