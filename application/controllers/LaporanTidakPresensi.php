<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LaporanTidakPresensi extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("ModelPegawai");
    $this->load->model("ModelAbsensi");
  }

  function index()
  {
    $data = array(
      'title'         => "Laporan Presensi Pegawai",
      'body'          => 'LaporanTidakPresensi/list',
    );
    $this->load->view('index', $data);
  }

  function tabel()
  {
    $this->load->model("ModelLibur");

    $tanggal       = $this->input->post("tanggal");
    $tgl_format    = date("Y-m-d", strtotime($tanggal));

    // Kumpulkan UUID pegawai yang sedang libur di tanggal ini
    $libur_data    = $this->ModelLibur->getLiburPegawaiByTanggal($tgl_format);
    $uuid_libur    = array();
    foreach ($libur_data->result() as $row) {
      $uuid_libur[] = $row->pegawai_uuid;
    }

    $data = array(
      'pegawai'       => $this->ModelPegawai->get_list()->result(),
      'tanggal'       => $tanggal,
      'uuid_libur'    => $uuid_libur,
    );
    $this->load->view('LaporanTidakPresensi/tabel', $data);
  }
}
