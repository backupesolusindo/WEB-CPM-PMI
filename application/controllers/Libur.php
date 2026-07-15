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
    $this->load->model("ModelJabatan");
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
      'title'   => 'Libur Pegawai',
      'body'    => 'Libur/kalender',
      'jabatan' => $this->ModelJabatan->get_jabatan_aktif()->result(),
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
   * Copy libur pegawai dari satu tanggal ke tanggal lain (AJAX)
   */
  function copy_libur_pegawai()
  {
    $tanggal_sumber     = $this->input->post("tanggal_sumber");
    $tanggal_tujuan_arr = $this->input->post("tanggal_tujuan"); // array
    $keterangan_baru    = $this->input->post("keterangan");

    if (!$tanggal_sumber || empty($tanggal_tujuan_arr)) {
      echo json_encode(array('status' => 'error', 'message' => 'Tanggal sumber dan tanggal tujuan wajib diisi'));
      return;
    }

    // Ambil semua pegawai libur di tanggal sumber
    $libur_sumber = $this->ModelLibur->getLiburPegawaiByTanggal($tanggal_sumber);
    $data_sumber  = $libur_sumber->result();

    if (empty($data_sumber)) {
      echo json_encode(array('status' => 'error', 'message' => 'Tidak ada data libur pegawai di tanggal sumber'));
      return;
    }

    $total_berhasil = 0;
    $total_skip     = 0;
    $tgl_berhasil   = array();

    foreach ($tanggal_tujuan_arr as $tgl_tujuan) {
      $tgl_tujuan = date("Y-m-d", strtotime($tgl_tujuan));

      // Jangan copy ke tanggal yang sama dengan sumber
      if ($tgl_tujuan === date("Y-m-d", strtotime($tanggal_sumber))) {
        continue;
      }

      $berhasil_per_tgl = 0;
      foreach ($data_sumber as $row) {
        // Gunakan keterangan baru jika diisi, atau keterangan asli
        $ket = !empty($keterangan_baru) ? $keterangan_baru : $row->keterangan;
        if ($this->ModelLibur->insertLiburPegawai($tgl_tujuan, $ket, $row->pegawai_uuid)) {
          $berhasil_per_tgl++;
        } else {
          $total_skip++;
        }
      }

      if ($berhasil_per_tgl > 0) {
        $total_berhasil += $berhasil_per_tgl;
        $tgl_berhasil[]  = date("d/m/Y", strtotime($tgl_tujuan));
      }
    }

    if ($total_berhasil > 0) {
      $msg  = '<b>' . $total_berhasil . ' data</b> berhasil disalin ke: ';
      $msg .= implode(', ', $tgl_berhasil);
      if ($total_skip > 0) {
        $msg .= '<br><small class="text-muted">(' . $total_skip . ' data dilewati karena sudah ada)</small>';
      }
      echo json_encode(array('status' => 'success', 'message' => $msg));
    } else {
      echo json_encode(array('status' => 'info', 'message' => 'Semua data sudah ada di tanggal tujuan, tidak ada yang disalin'));
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
    $jabatan = $this->input->post("jabatan");
    $tanggal = $this->input->post("tanggal");

    $pegawai_list = $this->ModelPegawai->get_TotalPegawai(null, null, null, $jabatan ?: null)->result();

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
