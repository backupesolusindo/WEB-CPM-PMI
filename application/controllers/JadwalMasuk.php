<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JadwalMasuk extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelJadwalMasuk");
    $this->load->model("ModelJabatan");
  }

  function index()
  {
    $data = array(
    'title'         => 'JADWAL MASUK',
    'body'          => 'JadwalMasuk/list' ,
    'jadwalmasuk'   => $this->ModelJadwalMasuk->get_all_jadwalmasuk()->result(),
  );
    $this->load->view('index', $data);
  }

  function input()
  {
    $data = array(
      'title'     => 'FORM INPUT JADWAL MASUK',
      'form'      => 'JadwalMasuk/form',
      'body'      => 'JadwalMasuk/input',
      'jabatan'   => $this->ModelJabatan->get_data()->result()
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'jam_masuk'           => $this->input->post("jam_masuk"),
      'jabatan_idjabatan'   => $this->input->post("jabatan_idjabatan"),
      'jam_pulang'          => $this->input->post("jam_pulang"),
      'isti_keluar'         => $this->input->post("isti_keluar"),
      'isti_masuk'          => $this->input->post("isti_masuk"),
      'jenis'               => $this->input->post("jenis"),
      'total_jamkerja'      => $this->input->post("total_jamkerja"),
      'hari'                => $this->input->post("hari"),
      'nama'                => $this->input->post("nama"),
      'jml_wfh'             => $this->input->post("jml_wfh"),
      'jml_wfo'             => $this->input->post("jml_wfo"),
      'toleransi_kedatangan'=> $this->input->post("toleransi_kedatangan"),
      'toleransi_kepulangan'=> $this->input->post("toleransi_kepulangan"),
    );
    if ($this->db->insert('jadwal_masuk', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
    }
    redirect(base_url().'JadwalMasuk');
  }

  function perhitungan_jam()
  {
    $jam_masuk = date("Y-m-d H:i", strtotime(date("Y-m-d").$this->input->post("masuk")));
    $jam_pulang= date("Y-m-d H:i", strtotime(date("Y-m-d").$this->input->post("pulang")));
    if (strtotime($jam_pulang) < strtotime($jam_masuk)) {
      $tgl_pulang = date("Y-m-d").$this->input->post("pulang");
      $jam_pulang= date("Y-m-d H:i", strtotime('+1 days', strtotime($tgl_pulang)));
    }
    $awal  = date_create($jam_masuk);
    $akhir = date_create($jam_pulang); // waktu sekarang
    $diff  = date_diff($awal, $akhir);

    echo $diff->h . ' jam ';
    echo $diff->i . ' menit ';
  }

  function edit($idjadwal_masuk)
  {
    $data = array(
      'title'        => 'FORM EDIT JADWAL MASUK',
      'form'         => 'JadwalMasuk/form',
      'body'         => 'JadwalMasuk/edit' ,
      'jadwalmasuk'  => $this->ModelJadwalMasuk->get_edit($idjadwal_masuk)->row_array(),
      'jabatan'      => $this->ModelJabatan->get_data()->result()
    );
    $this->load->view('index', $data);
  }

  function update()
  {
    $data = array(
      'nama'                => $this->input->post("nama"),
      'hari'                => $this->input->post("hari"),
      'jam_masuk'           => $this->input->post("jam_masuk"),
      'jabatan_idjabatan'   => $this->input->post("jabatan_idjabatan"),
      'jam_pulang'          => $this->input->post("jam_pulang"),
      'isti_keluar'         => $this->input->post("isti_keluar"),
      'isti_masuk'          => $this->input->post("isti_masuk"),
      'jenis'               => $this->input->post("jenis"),
      'total_jamkerja'      => $this->input->post("total_jamkerja"),
      'jml_wfh'             => $this->input->post("jml_wfh"),
      'jml_wfo'             => $this->input->post("jml_wfo"),
      'toleransi_kedatangan'=> $this->input->post("toleransi_kedatangan"),
      'toleransi_kepulangan'=> $this->input->post("toleransi_kepulangan"),
    );
    $this->db->where("idjadwal_masuk", $this->input->post("idjadwal_masuk"));
    if ($this->db->update('jadwal_masuk', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Merubah Data Berhasil"));
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Merubah Data Ulang"));
    }
    redirect(base_url().'JadwalMasuk');
  }

  function hapus($idjadwal_masuk)
  {
      $this->db->where("idjadwal_masuk", $idjadwal_masuk);
      if ($this->db->delete('jadwal_masuk')) {
        $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Hapus Data Berhasil","type" => "success" ));
        redirect(base_url().'JadwalMasuk');
      } else {
        $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Hapus Ulang","type" => "danger" ));
        redirect(base_url().'JadwalMasuk');
      }
  }

}
