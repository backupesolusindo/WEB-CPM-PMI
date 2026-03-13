<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lembur extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelLembur");
    $this->load->model("ModelUnit");
    $this->load->model("ModelPegawai");
  }

  function index()
  {
    $unit = $this->ModelUnit->get_parent_unit()->result();
    $data = array(
      'title'         => 'JADWAL PRESENSI KEGIATAN',
      'body'          => 'Lembur/list',
      'unit'          => $unit
    );
    $this->load->view('index', $data);
  }

  function data_lembur()
  {
    $unit = $this->input->post("unit");
    $subunit = $this->input->post("sub_unit");
    if ($subunit == "" || $subunit == null) {
      $unit = $this->input->post("unit");
    } else {
      $unit = $subunit;
    }
    $data = array(
      'data'          => $this->ModelLembur->get_lembur_unit($unit)->result()
    );
    $this->load->view('Lembur/tabel', $data);
  }

  function input()
  {
    $data = array(
      'title'     => 'FORM INPUT PRESENSI LEMBUR',
      'body'      => 'Lembur/input',
      'unit'      => $this->ModelUnit->get_unit()->result(),
    );
    $this->load->view('index', $data);
  }

  function edit($id)
  {
    $data = array(
      'title'     => 'FORM EDIT PRESENSI KEGIATAN',
      'body'      => 'Lembur/edit',
      'data'      => $this->ModelLembur->get_data($id)->row_array(),
      'unit'      => $this->ModelUnit->get_unit()->result(),
    );
    $this->load->view('index', $data);
  }

  function peserta($id)
  {
    $data = array(
      'title'     => 'FORM UNDANGAN PESERTA PRESENSI KEGIATAN',
      'body'      => 'Lembur/peserta',
      'lembur'    => $this->ModelLembur->get_data($id)->row_array(),
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
      'peserta'   => $this->ModelLembur->getUndanganPeserta($id)->result()
    );
    $this->load->view('index', $data);
  }

  function Presensi($id)
  {
    $data = array(
      'title'     => 'DAFTAR PRESENSI LEMBUR',
      'body'      => 'Lembur/presensi',
      'lembur'    => $this->ModelLembur->get_data($id)->row_array(),
      'presensi'  => $this->ModelLembur->get_cek($id, null)->result()
    );
    $this->load->view('index', $data);
  }

  function approve_presensi()
  {
    $id = $this->input->post("id");
    $data = array(
      'status_aproval' => '1'
    );
    $this->db->where("idabsen_lembur", $id);
    if ($this->db->update('absen_lembur', $data)) {
      echo json_encode(array('status' => 'success', 'message' => 'Presensi berhasil disetujui'));
    } else {
      echo json_encode(array('status' => 'error', 'message' => 'Gagal menyetujui presensi'));
    }
  }

  function reject_presensi()
  {
    $id = $this->input->post("id");
    $data = array(
      'status_aproval' => '2'
    );
    $this->db->where("idabsen_lembur", $id);
    if ($this->db->update('absen_lembur', $data)) {
      echo json_encode(array('status' => 'success', 'message' => 'Presensi berhasil ditolak'));
    } else {
      echo json_encode(array('status' => 'error', 'message' => 'Gagal menolak presensi'));
    }
  }


  function insert_peserta()
  {
    $idlembur = $this->input->post("idlembur");
    $pegawai    = $this->input->post("uuid");
    $data = array();
    $this->db->where("lembur_idlembur", $idlembur);
    if ($this->db->delete("peserta_lembur")) {
      for ($i = 0; $i < sizeof($pegawai); $i++) {
        $ar = array(
          'lembur_idlembur' => $idlembur,
          'pegawai_uuid'        => $pegawai[$i]
        );
        array_push($data, $ar);
      }
      // echo json_encode($data);
      if ($this->db->insert_batch("peserta_lembur", $data)) {
        for ($i = 0; $i < sizeof($pegawai); $i++) {
          $lembur = $this->ModelLembur->get_data($idlembur)->row_array();
          $da = $this->ModelPegawai->edit($pegawai[$i])->row_array();
          @$this->core->curlNotif(
            $da["token"],
            "Lembur : " . $lembur['keterangan_lembur'],
            "Dilaksanakan Tanggal" . date('d-m-Y', strtotime($lembur['tgl_mulai']))
          );
        }
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
      } else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
      }
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
    }
    redirect(base_url() . 'Lembur');
  }

  function insert()
  {
    $data = array(
      'keterangan_lembur'   => $this->input->post("keterangan_lembur"),
      'unit_idunit'         => $this->input->post("unit"),
      'tgl_mulai'           => date("Y-m-d", strtotime($this->input->post("tgl_mulai"))),
      'tgl_selesai'         => date("Y-m-d", strtotime($this->input->post("tgl_selesai"))),
    );
    if ($this->db->insert('lembur', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
    }
    redirect(base_url() . 'Lembur');
  }

  function update()
  {
    $data = array(
      'keterangan_lembur'   => $this->input->post("keterangan_lembur"),
      'unit_idunit'         => $this->input->post("unit"),
      'tgl_mulai'           => date("Y-m-d", strtotime($this->input->post("tgl_mulai"))),
      'tgl_selesai'         => date("Y-m-d", strtotime($this->input->post("tgl_selesai"))),
    );
    $this->db->where("idlembur", $this->input->post("idlembur"));
    if ($this->db->update('lembur', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
    }
    redirect(base_url() . 'Lembur');
  }

  function hapus($kode)
  {
    $idkegiatan = $this->core->decrypt_url($kode);
    $this->db->where("kegiatan_idkegiatan", $idkegiatan);
    $this->db->delete("kegiatan_peserta");
    $this->db->where("idkegiatan", $idkegiatan);
    if ($this->db->delete('kegiatan')) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil", 'text' => "Hapus Data Berhasil", "type" => "success"));
      redirect(base_url() . 'Kegiatan');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal", 'text' => "Mohon Untuk Melakukan Hapus Ulang", "type" => "danger"));
      redirect(base_url() . 'Kegiatan');
    }
  }
}
