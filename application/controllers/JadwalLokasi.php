<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JadwalLokasi extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("ModelJadwalLokasi");
    $this->load->model("ModelKampus");
    $this->load->model("ModelPegawai");
  }

  function index()
  {
    $array = array(
      'title'       => "Jadwal Lokasi Kantor",
      'body'        => "JadwalLokasi/list",
      'Pegawai'     => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $array);
  }

  function input()
  {
    $data = array(
      'title'   => 'Form Input Jadwal Lokasi Kantor',
      'body'    => 'JadwalLokasi/input',
      'pegawai' => $this->ModelPegawai->get_list()->result(),
      'unit'    => $this->ModelKampus->get_kampus()->result(),
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $idkampus = $this->input->post("idkampus");
    $uuid     = $this->input->post("uuid");
    $hari     = $this->input->post("hari");
    // echo json_encode($this->input->post());
    // die();
    $data = array();
    foreach ($hari as $val_hari) {
      // echo $val_hari;

      $cek = $this->ModelJadwalLokasi->get_jadwalpegawai($uuid, $idkampus, $val_hari);
      if ($cek->num_rows() < 1) {
        $ar = array(
          'hari'            => $val_hari,
          'pegawai_uuid'    => $uuid,
          'kampus_idkampus' => $idkampus,
        );
        array_push($data, $ar);
      }
    }
    if (sizeof($data) > 0) {
      if ($this->db->insert_batch('hari_lokasi', $data)) {
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
        redirect(base_url() . 'JadwalLokasi');
      } else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
        redirect(base_url() . 'JadwalLokasi');
      }
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Mohon Untuk Memilih data yang belum Ada"));
      redirect(base_url() . 'JadwalLokasi');
    }
  }

  function hapus($id)
  {
    $this->db->where("idhari_lokasi", $id);
    if ($this->db->delete('hari_lokasi')) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Hapus Data Berhasil"));
      redirect(base_url() . 'JadwalLokasi');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Hapus Ulang"));
      redirect(base_url() . 'JadwalLokasi');
    }
  }
}
