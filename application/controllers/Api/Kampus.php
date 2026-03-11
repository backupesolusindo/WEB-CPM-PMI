<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kampus extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelKampus");
    $this->load->model("ModelPegawai");
    $this->load->model("ModelJadwalLokasi");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function get_list($pegawai_uuid = "", $cari = "")
  {
    $cari = urldecode($cari);
    $kantor = $this->ModelKampus->get_kampus($cari)->result();
    // $kantor = array();
    // $pegawai = $this->ModelPegawai->edit($pegawai_uuid)->row_array();
    // $hari_lokasi = $this->ModelJadwalLokasi->get_jadwalpegawai($pegawai_uuid, date("D"));
    // if ($hari_lokasi->num_rows() > 0) {
    //   $kantor = $hari_lokasi->result();
    // } else {
    //   $kepala_unit = $this->ModelPegawai->get_kepalaunit($pegawai_uuid);
    //   if ($kepala_unit->row_array() > 0) {
    //     foreach ($kepala_unit->result() as $value) {
    //       $datakantor = $this->ModelKampus->get_kampus($value->nama_unit)->row_array();
    //       array_push($kantor, $datakantor);
    //     }
    //   } else {
    //     if ($pegawai['unit'] != null) {
    //       $datakantor = $this->ModelKampus->get_kampus($pegawai['unit'])->row_array();
    //       array_push($kantor, $datakantor);
    //     }
    //   }
    // }
    if (sizeof($kantor) > 0) {
      $res = array(
        'message' => "Success",
        'status' => 200
      );
    } else {
      $res = array(
        'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
        'status' => 500
      );
    }
    echo json_encode(array('data' => $kantor, 'message' => $res));
  }
}
