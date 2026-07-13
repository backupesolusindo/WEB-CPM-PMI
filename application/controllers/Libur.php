<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Libur extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("ModelLibur");
    $this->load->model("ModelPegawai");
    $this->load->model("ModelUnit");
  }

  // ===================== LIBUR NASIONAL =====================

  function index()
  {
    $data = array(
      'title' => 'Libur Nasional',
      'body'  => 'Libur/list',
    );
    $this->load->view('index', $data);
  }

  function tabel()
  {
    $tahun = $this->input->post("tahun");
    $data = array(
      'libur' => $this->ModelLibur->getLibur($tahun)->result()
    );
    $this->load->view('Libur/tabel', $data);
  }

  function input()
  {
    $data = array(
      'title' => 'Libur Nasional',
      'body'  => 'Libur/input',
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'tanggal'    => date("Y-m-d", strtotime($this->input->post('tanggal'))),
      'keterangan' => $this->input->post('keterangan'),
    );
    if ($this->db->insert('tanggal_libur', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Tambah Data Berhasil"));
      redirect(base_url() . 'Libur');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Mohon Untuk Melakukan Tambah Ulang"));
      redirect(base_url() . 'Libur');
    }
  }

  function delete($id)
  {
    $this->db->where("idtanggal_libur", $id);
    if ($this->db->delete('tanggal_libur')) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Hapus Data Berhasil"));
      redirect(base_url() . 'Libur');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Mohon Untuk Melakukan Hapus Ulang"));
      redirect(base_url() . 'Libur');
    }
  }

  // ===================== LIBUR PEGAWAI (KALENDER) =====================

  /**
   * Halaman utama kalender libur pegawai
   */
  function kalender()
  {
    $data = array(
      'title' => 'Libur Pegawai',
      'body'  => 'Libur/kalender',
      'unit'  => $this->ModelUnit->get_parent_unit()->result(),
    );
    $this->load->view('index', $data);
  }

  /**
   * Ambil data events untuk FullCalendar (AJAX)
   */
  function data_kalender_libur()
  {
    $bulan = $this->input->post("bulan");
    $tahun = $this->input->post("tahun");

    if (!$bulan) $bulan = date("m");
    if (!$tahun) $tahun = date("Y");

    $tgl_mulai = $tahun . "-" . $bulan . "-01";
    $tgl_akhir = date("Y-m-t", strtotime($tgl_mulai));

    $events = array();

    // Data libur nasional (background merah)
    $libur_nasional = $this->ModelLibur->getLibur($tahun);
    foreach ($libur_nasional->result() as $value) {
      $tgl = date("Y-m-d", strtotime($value->tanggal));
      if ($tgl >= $tgl_mulai && $tgl <= $tgl_akhir) {
        $events[] = array(
          'title'   => '🔴 ' . $value->keterangan,
          'start'   => $tgl,
          'color'   => '#e74c3c',
          'type'    => 'libur_nasional',
          'display' => 'background',
          'allDay'  => true
        );
      }
    }

    // Data libur pegawai (count per tanggal)
    $libur_pegawai = $this->ModelLibur->countLiburPegawaiPerTanggal($bulan, $tahun);
    foreach ($libur_pegawai->result() as $value) {
      $events[] = array(
        'title'      => $value->jumlah . ' Pegawai Libur',
        'start'      => $value->tanggal,
        'color'      => '#fd7e14',
        'type'       => 'libur_pegawai',
        'keterangan' => $value->keterangan,
        'allDay'     => true
      );
    }

    echo json_encode($events);
  }

  /**
   * Ambil detail libur pegawai per tanggal (AJAX)
   */
  function detail_libur_pegawai()
  {
    $tanggal = $this->input->post("tanggal");
    if (!$tanggal) {
      echo json_encode(array('libur_nasional' => null, 'pegawai' => array()));
      return;
    }

    $result = array(
      'libur_nasional' => null,
      'pegawai'        => array()
    );

    // Cek libur nasional
    $cek = $this->ModelLibur->getDataLibur($tanggal);
    if ($cek->num_rows() > 0) {
      $lib = $cek->row();
      $result['libur_nasional'] = array(
        'keterangan' => $lib->keterangan,
        'tanggal'    => date("d-m-Y", strtotime($lib->tanggal))
      );
    }

    // Daftar pegawai yang libur hari itu
    $libur_pg = $this->ModelLibur->getLiburPegawaiByTanggal($tanggal);
    foreach ($libur_pg->result() as $row) {
      $result['pegawai'][] = array(
        'id'           => $row->idlibur_pegawai,
        'nama_pegawai' => $row->nama_pegawai,
        'nip'          => $row->NIP,
        'unit'         => $row->unit,
        'keterangan'   => $row->keterangan,
      );
    }

    echo json_encode($result);
  }

  /**
   * Insert libur pegawai (AJAX, bisa banyak sekaligus)
   */
  function insert_libur_pegawai()
  {
    $tanggal     = $this->input->post("tanggal");
    $keterangan  = $this->input->post("keterangan");
    $pegawai_ids = $this->input->post("pegawai_uuid"); // array

    if (!$tanggal || empty($pegawai_ids)) {
      echo json_encode(array('status' => 'error', 'message' => 'Tanggal dan pegawai wajib diisi'));
      return;
    }

    $berhasil = 0;
    foreach ($pegawai_ids as $uuid) {
      if ($this->ModelLibur->insertLiburPegawai($tanggal, $keterangan, $uuid)) {
        $berhasil++;
      }
    }

    if ($berhasil > 0) {
      echo json_encode(array('status' => 'success', 'message' => $berhasil . ' data libur pegawai berhasil disimpan'));
    } else {
      echo json_encode(array('status' => 'info', 'message' => 'Data sudah ada atau gagal disimpan'));
    }
  }

  /**
   * Delete libur pegawai by ID (AJAX)
   */
  function delete_libur_pegawai($id)
  {
    if ($this->ModelLibur->deleteLiburPegawai($id)) {
      echo json_encode(array('status' => 'success', 'message' => 'Data berhasil dihapus'));
    } else {
      echo json_encode(array('status' => 'error', 'message' => 'Gagal menghapus data'));
    }
  }

  /**
   * Ambil daftar pegawai untuk select (AJAX, dengan filter unit)
   */
  function get_pegawai_list()
  {
    $unit     = $this->input->post("unit");
    $sub_unit = $this->input->post("sub_unit");
    $tanggal  = $this->input->post("tanggal");

    $pegawai_list = $this->ModelPegawai->get_TotalPegawai($unit, $sub_unit)->result();

    // Tandai pegawai yang sudah libur di tanggal tersebut
    $sudah_libur = array();
    if ($tanggal) {
      $libur_data = $this->ModelLibur->getLiburPegawaiByTanggal($tanggal);
      foreach ($libur_data->result() as $row) {
        $sudah_libur[] = $row->pegawai_uuid;
      }
    }

    $result = array();
    foreach ($pegawai_list as $p) {
      $result[] = array(
        'uuid'         => $p->uuid,
        'nama_pegawai' => $p->nama_pegawai,
        'nip'          => $p->NIP ?? '-',
        'unit'         => $p->unit ?? '-',
        'sudah_libur'  => in_array($p->uuid, $sudah_libur),
      );
    }

    echo json_encode($result);
  }
}
