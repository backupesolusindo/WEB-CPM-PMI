<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jadwal extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelJadwalMasuk");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function getNotif()
  {
    $idjabatan    = $this->input->post("idjabatan");
    $pegawai_uuid = $this->input->post("pegawai_uuid");

    // Gunakan logika prioritas: pegawai spesifik → jabatan → semua
    $jadwal = $this->ModelJadwalMasuk->get_jadwal_for_pegawai($pegawai_uuid, $idjabatan);

    $data = array();
    if ($jadwal->num_rows() > 0) {
      $data = $jadwal->row_array();
      $res = array(
        'message' => "Success",
        'status'  => 200
      );
    } else {
      $res = array(
        'message' => "Maaf Tidak Bisa Ambil Data",
        'status'  => 500
      );
    }
    echo json_encode(array('data' => $data, 'message' => $res));
  }
}
