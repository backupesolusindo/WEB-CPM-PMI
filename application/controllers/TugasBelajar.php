<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TugasBelajar extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model("ModelTugasBelajar");
    $this->load->model("ModelPegawai");
  }

  function index()
  {
    $data = array(
      'title'         => 'TUGAS BELAJAR',
      'body'          => 'TugasBelajar/index' ,
    );
    $this->load->view('index', $data);
  }

  function get_tabel()
  {
    $tahun = $this->input->post("tahun");
    $status= $this->input->post("status");
    $data = array(
      'data' => $this->ModelTugasBelajar->get_all($tahun, $status)->result()
    );
    $this->load->view('TugasBelajar/tabel', $data);
  }

  function input()
  {
    $data = array(
      'title'     => 'FORM TUGAS BELAJAR',
      'body'      => 'TugasBelajar/input',
      'form'      => 'TugasBelajar/form',
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $data);
  }

  function edit($id)
  {
    $data = array(
      'title'     => 'FORM TUGAS BELAJAR',
      'body'      => 'TugasBelajar/edit',
      'form'      => 'TugasBelajar/form',
      'data'      => $this->ModelTugasBelajar->get_tugasbelajar($id)->row_array(),
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'nama_kampus'         => $this->input->post("nama_kampus"),
      'keterangan'          => $this->input->post("keterangan"),
      'status'              => "1",
      'tahun'               => $this->input->post("tahun"),
      'tahun_selesai'       => $this->input->post("tahun_selesai"),
      'pegawai_uuid'        => $this->input->post("pegawai_uuid"),
    );
    if ($this->db->insert('tugas_belajar', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
    }
    redirect(base_url().'TugasBelajar');
  }

  function update()
  {
    $id = $this->input->post("id");
    $data = array(
      'nama_kampus'         => $this->input->post("nama_kampus"),
      'keterangan'          => $this->input->post("keterangan"),
      'status'              => "1",
      'tahun'               => $this->input->post("tahun"),
      'tahun_selesai'       => $this->input->post("tahun_selesai"),
      'pegawai_uuid'        => $this->input->post("pegawai_uuid"),
    );
    $this->db->where("idtugas_belajar",$id);
    if ($this->db->update('tugas_belajar', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Merubah Data Berhasil"));
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Merubah Ulang"));
    }
    redirect(base_url().'TugasBelajar');
  }

  function delete($id)
  {
    $this->db->where("idtugas_belajar",$id);
    if ($this->db->delete('tugas_belajar')) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Menghapus Data Berhasil"));
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Menghapus Ulang"));
    }
    redirect(base_url().'TugasBelajar');
  }

}
