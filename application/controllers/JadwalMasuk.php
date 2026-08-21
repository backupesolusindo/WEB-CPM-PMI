<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JadwalMasuk extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("ModelJadwalMasuk");
    $this->load->model("ModelJabatan");
    $this->load->model("ModelKampus");
  }

  function index()
  {
    $jadwalmasuk = $this->ModelJadwalMasuk->get_all_jadwalmasuk()->result();

    // Ambil daftar pegawai per jadwal untuk ditampilkan di tabel
    foreach ($jadwalmasuk as &$jdw) {
      $jdw->pegawai_assigned = $this->ModelJadwalMasuk->get_pegawai_detail_by_jadwal($jdw->idjadwal_masuk)->result();
    }
    unset($jdw);

    $data = array(
      'title'       => 'JADWAL MASUK',
      'body'        => 'JadwalMasuk/list',
      'jadwalmasuk' => $jadwalmasuk,
      'jabatan'     => $this->ModelJabatan->get_jabatan_aktif()->result(),
    );
    $this->load->view('index', $data);
  }

  function input()
  {
    $data = array(
      'title'            => 'FORM INPUT JADWAL MASUK',
      'form'             => 'JadwalMasuk/form',
      'body'             => 'JadwalMasuk/input',
      'jabatan'          => $this->ModelJabatan->get_jabatan_aktif()->result(),
      'pegawai_list'     => array(),
      'selected_pegawai' => array(),
      'kampus_list'      => $this->ModelKampus->get_kampus_aktif()->result(),
      'selected_kampus'  => array(),
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'jam_masuk'            => $this->input->post("jam_masuk"),
      'jabatan_idjabatan'    => $this->input->post("jabatan_idjabatan"),
      'jam_pulang'           => $this->input->post("jam_pulang"),
      'isti_keluar'          => $this->input->post("isti_keluar"),
      'isti_masuk'           => $this->input->post("isti_masuk"),
      'jenis'                => $this->input->post("jenis"),
      'total_jamkerja'       => $this->input->post("total_jamkerja"),
      'hari'                 => $this->input->post("hari"),
      'nama'                 => $this->input->post("nama"),
      'jml_wfh'              => $this->input->post("jml_wfh"),
      'jml_wfo'              => $this->input->post("jml_wfo"),
      'toleransi_kedatangan' => $this->input->post("toleransi_kedatangan"),
      'toleransi_kepulangan' => $this->input->post("toleransi_kepulangan"),
      'batas_absen'          => $this->input->post("batas_absen"),
    );

    if ($this->db->insert('jadwal_masuk', $data)) {
      $idjadwal_masuk = $this->db->insert_id();

      // Simpan relasi pegawai (bisa lebih dari satu)
      $pegawai_uuids = $this->input->post("pegawai_uuid") ?: array();
      if (!is_array($pegawai_uuids)) {
        $pegawai_uuids = array($pegawai_uuids);
      }
      $this->ModelJadwalMasuk->save_jadwal_pegawai($idjadwal_masuk, $pegawai_uuids);

      // Simpan relasi kampus (bisa lebih dari satu, kosong = semua kampus)
      $kampus_ids = $this->input->post("kampus_id") ?: array();
      if (!is_array($kampus_ids)) {
        $kampus_ids = array($kampus_ids);
      }
      $this->ModelJadwalMasuk->save_jadwal_kampus($idjadwal_masuk, $kampus_ids);

      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Berhasil Tambah Data Jadwal Masuk"));
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
    }
    redirect(base_url() . 'JadwalMasuk');
  }

  function perhitungan_jam()
  {
    $jam_masuk = date("Y-m-d H:i", strtotime(date("Y-m-d") . $this->input->post("masuk")));
    $jam_pulang = date("Y-m-d H:i", strtotime(date("Y-m-d") . $this->input->post("pulang")));
    if (strtotime($jam_pulang) < strtotime($jam_masuk)) {
      $tgl_pulang = date("Y-m-d") . $this->input->post("pulang");
      $jam_pulang = date("Y-m-d H:i", strtotime('+1 days', strtotime($tgl_pulang)));
    }
    $awal  = date_create($jam_masuk);
    $akhir = date_create($jam_pulang);
    $diff  = date_diff($awal, $akhir);

    echo $diff->h . ' jam ';
    echo $diff->i . ' menit ';
  }

  function edit($idjadwal_masuk)
  {
    $jadwalmasuk = $this->ModelJadwalMasuk->get_edit($idjadwal_masuk)->row_array();

    $pegawai_list = array();
    if (!empty($jadwalmasuk['jabatan_idjabatan'])) {
      $pegawai_list = $this->ModelJadwalMasuk->get_pegawai_by_jabatan($jadwalmasuk['jabatan_idjabatan'])->result();
    }

    // UUID pegawai yang sudah di-assign ke jadwal ini
    $selected_pegawai = $this->ModelJadwalMasuk->get_pegawai_by_jadwal($idjadwal_masuk);

    // ID kampus yang sudah di-assign ke jadwal ini
    $selected_kampus = $this->ModelJadwalMasuk->get_kampus_by_jadwal($idjadwal_masuk);

    $data = array(
      'title'            => 'FORM EDIT JADWAL MASUK',
      'form'             => 'JadwalMasuk/form',
      'body'             => 'JadwalMasuk/edit',
      'jadwalmasuk'      => $jadwalmasuk,
      'jabatan'          => $this->ModelJabatan->get_jabatan_aktif()->result(),
      'pegawai_list'     => $pegawai_list,
      'selected_pegawai' => $selected_pegawai,
      'kampus_list'      => $this->ModelKampus->get_kampus_aktif()->result(),
      'selected_kampus'  => $selected_kampus,
    );
    $this->load->view('index', $data);
  }

  function update()
  {
    $data = array(
      'nama'                 => $this->input->post("nama"),
      'hari'                 => $this->input->post("hari"),
      'jam_masuk'            => $this->input->post("jam_masuk"),
      'jabatan_idjabatan'    => $this->input->post("jabatan_idjabatan"),
      'jam_pulang'           => $this->input->post("jam_pulang"),
      'isti_keluar'          => $this->input->post("isti_keluar"),
      'isti_masuk'           => $this->input->post("isti_masuk"),
      'jenis'                => $this->input->post("jenis"),
      'total_jamkerja'       => $this->input->post("total_jamkerja"),
      'jml_wfh'              => $this->input->post("jml_wfh"),
      'jml_wfo'              => $this->input->post("jml_wfo"),
      'toleransi_kedatangan' => $this->input->post("toleransi_kedatangan"),
      'toleransi_kepulangan' => $this->input->post("toleransi_kepulangan"),
      'batas_absen'          => $this->input->post("batas_absen"),
    );

    $idjadwal_masuk = $this->input->post("idjadwal_masuk");
    $this->db->where("idjadwal_masuk", $idjadwal_masuk);

    if ($this->db->update('jadwal_masuk', $data)) {
      // Update relasi pegawai
      $pegawai_uuids = $this->input->post("pegawai_uuid") ?: array();
      if (!is_array($pegawai_uuids)) {
        $pegawai_uuids = array($pegawai_uuids);
      }
      $this->ModelJadwalMasuk->save_jadwal_pegawai($idjadwal_masuk, $pegawai_uuids);

      // Update relasi kampus
      $kampus_ids = $this->input->post("kampus_id") ?: array();
      if (!is_array($kampus_ids)) {
        $kampus_ids = array($kampus_ids);
      }
      $this->ModelJadwalMasuk->save_jadwal_kampus($idjadwal_masuk, $kampus_ids);

      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Berhasil Merubah Data Jadwal Masuk"));
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Merubah Data Ulang"));
    }
    redirect(base_url() . 'JadwalMasuk');
  }

  function hapus($idjadwal_masuk)
  {
    // Hapus relasi pegawai dan kampus dulu (FK)
    $this->ModelJadwalMasuk->delete_jadwal_pegawai($idjadwal_masuk);
    $this->ModelJadwalMasuk->delete_jadwal_kampus($idjadwal_masuk);

    $this->db->where("idjadwal_masuk", $idjadwal_masuk);
    if ($this->db->delete('jadwal_masuk')) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil", 'text' => "Hapus Data Berhasil", "type" => "success"));
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal", 'text' => "Mohon Untuk Melakukan Hapus Ulang", "type" => "danger"));
    }
    redirect(base_url() . 'JadwalMasuk');
  }

  function get_pegawai_by_jabatan()
  {
    $idjabatan = $this->input->post('idjabatan');
    $pegawai   = $this->ModelJadwalMasuk->get_pegawai_by_jabatan($idjabatan)->result();
    echo json_encode($pegawai);
  }
}
