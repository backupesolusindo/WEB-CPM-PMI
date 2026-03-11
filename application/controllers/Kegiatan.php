<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kegiatan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelKegiatan");
    $this->load->model("ModelKampus");
    $this->load->model("ModelUnit");
    $this->load->model("ModelPegawai");
  }

  function index()
  {
    $unit = $this->ModelUnit->get_parent_unit()->result();
    $data = array(
      'title'         => 'JADWAL PRESENSI KEGIATAN',
      'body'          => 'Kegiatan/list' ,
      'unit'          => $unit
    );
    $this->load->view('index', $data);
  }

  function data_kegiatan()
  {
    $unit = $this->input->post("unit");
    $subunit = $this->input->post("sub_unit");
    if ($subunit == "" || $subunit == null) {
        $unit = $this->input->post("unit");
    }else {
        $unit = $subunit;
    }
    // echo $unit;
    $data = array(
      'kegiatan'          => $this->ModelKegiatan->get_kegiatan_unit($unit)->result()
    );
    $this->load->view('Kegiatan/data_kegiatan', $data);
  }

  function input()
  {
    $data = array(
      'title'     => 'FORM INPUT PRESENSI KEGIATAN',
      'body'      => 'Kegiatan/input',
      'kampus'    => $this->ModelKampus->get_kampus()->result(),
      'unit'      => $this->ModelUnit->get_unit()->result(),
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $data);
  }

  function cekKodeKegiatan()
  {
    $id = $this->input->post("id");
    $status = 0;
    if ($this->ModelKegiatan->cekKode($id)->num_rows() > 0 || $id == "") {
      $status = 0;
    }else {
      $status = 1;
    }
    echo $status;
  }

  function edit($kode)
  {
    $id = $this->core->decrypt_url($kode);
    $data = array(
      'title'     => 'FORM EDIT PRESENSI KEGIATAN',
      'body'      => 'Kegiatan/edit',
      'kegiatan'  => $this->ModelKegiatan->get_data($id)->row_array(),
      'kampus'    => $this->ModelKampus->get_kampus()->result(),
      'unit'      => $this->ModelUnit->get_unit()->result(),
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $data);
  }

  function peserta($kode)
  {
    $id = $this->core->decrypt_url($kode);
    $data = array(
      'title'     => 'FORM UNDANGAN PESERTA PRESENSI KEGIATAN',
      'body'      => 'Kegiatan/peserta',
      'kegiatan'  => $this->ModelKegiatan->get_data($id)->row_array(),
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
      'peserta'   => $this->ModelKegiatan->getUndanganPeserta($id)->result()
    );
    $this->load->view('index', $data);
  }

  function insert_peserta()
  {
    $idkegiatan = $this->input->post("idkegiatan");
    $pegawai    = $this->input->post("uuid");
    $data = array();
    $this->db->where("kegiatan_idkegiatan", $idkegiatan);
    if ($this->db->delete("kegiatan_peserta")) {
      for ($i=0; $i < sizeof($pegawai); $i++) {
        $ar = array(
          'kegiatan_idkegiatan' => $idkegiatan,
          'pegawai_uuid'        => $pegawai[$i]
        );
        array_push($data, $ar);
      }
      // echo json_encode($data);
      if ($this->db->insert_batch("kegiatan_peserta", $data)) {
        for ($i=0; $i < sizeof($pegawai); $i++) {
          $kegiatan = $this->ModelKegiatan->get_data($idkegiatan)->row_array();
          $da = $this->ModelPegawai->edit($pegawai[$i])->row_array();
          @$this->core->curlNotif(
            $da["token"],
            "Kegiatan : ".$kegiatan['nama_kegiatan'],
            "Dilaksanakan Tanggal".date('d-m-Y', strtotime($kegiatan['tanggal']))." Pukul :".$kegiatan['jam_mulai']
          );
        }
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
      }else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
      }
    }else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
    }
    redirect(base_url().'Kegiatan');
  }

  function insert()
  {
    $data = array(
      'idkegiatan'          => $this->input->post("idkegiatan"),
      'nama_kegiatan'       => $this->input->post("nama_kegiatan"),
      'uuid_pic'            => $this->input->post("pic"),
      'jam_mulai'           => $this->input->post("jam_mulai"),
      'jam_selesai'         => $this->input->post("jam_selesai"),
      'tanggal'             => date("Y-m-d", strtotime($this->input->post("tanggal"))),
      'tanggal_selesai'     => date("Y-m-d", strtotime($this->input->post("tanggal_selesai"))),
      'latitude'            => $this->input->post("latitude"),
      'longtitude'          => $this->input->post("longtitude"),
      'unit_idunit'         => $this->input->post("unit"),
      'radius'              => $this->input->post("radius"),
      'kampus_idkampus'     => $this->input->post("kampus"),
      'gedung_idgedung'     => $this->input->post("gedung"),
    );
    if ($this->db->insert('kegiatan', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
      redirect(base_url().'Kegiatan');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
      redirect(base_url().'Kegiatan');
    }
  }

  function update()
  {
    $data = array(
      'nama_kegiatan'       => $this->input->post("nama_kegiatan"),
      'uuid_pic'            => $this->input->post("pic"),
      'jam_mulai'           => $this->input->post("jam_mulai"),
      'jam_selesai'         => $this->input->post("jam_selesai"),
      'tanggal'             => date("Y-m-d", strtotime($this->input->post("tanggal"))),
      'tanggal_selesai'     => date("Y-m-d", strtotime($this->input->post("tanggal_selesai"))),
      'latitude'            => $this->input->post("latitude"),
      'longtitude'          => $this->input->post("longtitude"),
      'unit_idunit'         => $this->input->post("unit"),
      'radius'              => $this->input->post("radius"),
      'kampus_idkampus'     => $this->input->post("kampus"),
      'gedung_idgedung'     => $this->input->post("gedung"),
    );
    $this->db->where("idkegiatan", $this->input->post("idkegiatan"));
    if ($this->db->update('kegiatan', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
      redirect(base_url().'Kegiatan');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
      redirect(base_url().'Kegiatan');
    }
  }

  function hapus($kode)
  {
    $idkegiatan = $this->core->decrypt_url($kode);
    $this->db->where("kegiatan_idkegiatan", $idkegiatan);
    $this->db->delete("kegiatan_peserta");
      $this->db->where("idkegiatan", $idkegiatan);
      if ($this->db->delete('kegiatan')) {
        $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Hapus Data Berhasil","type" => "success" ));
        redirect(base_url().'Kegiatan');
      } else {
        $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Hapus Ulang","type" => "danger" ));
        redirect(base_url().'Kegiatan');
      }
  }

}
