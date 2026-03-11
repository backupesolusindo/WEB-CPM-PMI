<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DinasLuar extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("ModelDinasLuar");
    $this->load->model("ModelPegawai");
  }

  function index()
  {
    $data = array(
      'title'         => 'JADWAL DINAS LUAR',
      'body'          => 'DinasLuar/list' ,
      'DinasLuar'     => $this->ModelDinasLuar->getAll()->result()
    );
    $this->load->view('index', $data);
  }

  function input()
  {
    $data = array(
      'title'     => 'FORM INPUT DINAS LUAR',
      'body'      => 'DinasLuar/input',
      'form'      => 'DinasLuar/form',
    );
    $this->load->view('index', $data);
  }

  function edit($id)
  {
    $data = array(
      'title'     => 'FORM EDIT PRESENSI DINAS LUAR',
      'body'      => 'DinasLuar/edit',
      'form'      => 'DinasLuar/form',
      'dinasluar' => $this->ModelDinasLuar->get_data($id)->row_array(),
    );
    $this->load->view('index', $data);
  }

  function peserta($id)
  {
    $data = array(
      'title'     => 'FORM UNDANGAN PESERTA PRESENSI DINAS LUAR',
      'body'      => 'DinasLuar/peserta',
      'dinasluar'  => $this->ModelDinasLuar->get_data($id)->row_array(),
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
      'peserta'   => $this->ModelDinasLuar->getUndanganPeserta($id)->result()
    );
    $this->load->view('index', $data);
  }

  function IzinPresensi($iddinas, $idpres, $status)
  {
    $this->db->where("idpegawai_dinasluar", $idpres);
    if ($this->db->update('pegawai_dinasluar', array('status_pres_wfo' => $status, ))) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Merubah Data Berhasil","type" => "success" ));
      redirect(base_url().'DinasLuar/peserta/'.$iddinas);
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Merubah Ulang","type" => "danger" ));
      redirect(base_url().'DinasLuar/peserta/'.$iddinas);
    }
  }

  function insert_peserta()
  {
    $iddinas_luar = $this->input->post("iddinas_luar");
    $pegawai    = $this->input->post("uuid");
    $data = array();
    $this->db->where("dinas_luar_iddinas_luar", $iddinas_luar);
    if ($this->db->delete("pegawai_dinasluar")) {
      for ($i=0; $i < sizeof($pegawai); $i++) {
        $ar = array(
          'dinas_luar_iddinas_luar' => $iddinas_luar,
          'pegawai_uuid'        => $pegawai[$i]
        );
        array_push($data, $ar);
      }
      if (sizeof($pegawai) > 0) {
        if ($this->db->insert_batch("pegawai_dinasluar", $data)) {
          $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
        }else {
          $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
        }
      }
    }else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
    }
    redirect(base_url().'DinasLuar');
  }

  function insert()
  {
    $data = array(
      'no_surat'         => $this->input->post("no_surat"),
      'nama_surat'       => $this->input->post("nama_surat"),
      'keterangan'       => $this->input->post("keterangan"),
      'tanggal_mulai'    => date("Y-m-d", strtotime($this->input->post("tanggal_mulai"))),
      'tanggal_selesai'  => date("Y-m-d", strtotime($this->input->post("tanggal_selesai"))),
    );
    if ($this->db->insert('dinas_luar', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
      redirect(base_url().'DinasLuar');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
      redirect(base_url().'DinasLuar');
    }
  }

  function update()
  {
    $data = array(
      'no_surat'         => $this->input->post("no_surat"),
      'nama_surat'       => $this->input->post("nama_surat"),
      'keterangan'       => $this->input->post("keterangan"),
      'tanggal_mulai'    => date("Y-m-d", strtotime($this->input->post("tanggal_mulai"))),
      'tanggal_selesai'  => date("Y-m-d", strtotime($this->input->post("tanggal_selesai"))),
    );
    $this->db->where("iddinas_luar", $this->input->post("id"));
    if ($this->db->update('dinas_luar', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Ubah Data Berhasil"));
      redirect(base_url().'DinasLuar');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Ubah Ulang"));
      redirect(base_url().'DinasLuar');
    }
  }

  function hapus($id)
  {
    $this->db->where("dinas_luar_iddinas_luar", $id);
    $this->db->delete("pegawai_dinasluar");
      $this->db->where("iddinas_luar", $id);
      if ($this->db->delete('dinas_luar')) {
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Hapus Data Berhasil"));
        redirect(base_url().'DinasLuar');
      } else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Hapus Ulang"));
        redirect(base_url().'DinasLuar');
      }
  }

}
