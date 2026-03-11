<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanTidakPresensi extends CI_Controller{

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
    $data = array(
      'pegawai'          => $this->ModelPegawai->get_list()->result(),
      'tanggal'          => $this->input->post("tanggal")
     );
    $this->load->view('LaporanTidakPresensi/tabel', $data);
  }

}
