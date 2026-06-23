<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('ModelPegawai');
    $this->load->model('ModelJabatan');
    $this->load->model('ModelUnit');

    // $this->load->library('Core');
  }

  function index()
  {
    // $this->load->library('Core');
    $array = array(
      'title'       => "Pegawai",
      'body'        => "Pegawai/list",
      // 'btn_title'   => "<a href=\"".base_url()."Pegawai/input\"><button type=\"button\" class=\"btn btn-info d-none d-lg-block m-l-15\"><i class=\"fa fa-plus-circle\"></i> Tambah Karyawan</button></a>",
      'Pegawai'     => $this->ModelPegawai->get_list()->result()
    );

    $this->load->view('index', $array);
  }

  function input()
  {
    $array = array(
      'title'       => "Pegawai",
      'body'        => "Pegawai/input",
      'jabatan'     => $this->ModelJabatan->get_data()->result(),
      'unit'        => $this->ModelUnit->get_unit()->result()
    );
    $this->load->view('index', $array);
  }

  function insert()
  {
    $data = array(
      'NIP'               => $this->input->post('nip'),
      'NIK'               => $this->input->post('nik'),
      'nama_pegawai'      => $this->input->post('nama_pegawai'),
      'email'             => $this->input->post('email'),
      'jab_struktur'      => $this->input->post('jabatan'),
      'unit'              => $this->input->post('unit'),
      'jenis_unit'        => $this->input->post('jenis_unit'),
    );
    if ($this->db->insert('pegawai', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Menambahkan Pegawai Baru."));
      redirect('Pegawai');
    } else {
      echo "gagal";
    }
  }

  function edit($id)
  {
    $array = array(
      'title'       => "Pegawai",
      'body'        => "Pegawai/update",
      'pegawai'     => $this->ModelPegawai->edit($id)->row_array(),
      'jabatan'     => $this->ModelJabatan->get_data()->result(),
      'unit'        => $this->ModelUnit->get_unit()->result()
    );
    $this->load->view('index', $array);
  }

  function hapus($id)
  {
    $this->db->where("uuid", $id);
    if ($this->db->update('pegawai', array('status_aktif' => 0))) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Reset Login "));
      redirect('Pegawai');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gaga Reset Login"));
      redirect('Pegawai');
    }
  }

  function update()
  {
    $id = $this->input->post('id');
    $data = array(
      'NIP'               => $this->input->post('nip'),
      'NIK'               => $this->input->post('nik'),
      'nama_pegawai'      => $this->input->post('nama_pegawai'),
      'email'             => $this->input->post('email'),
      'jab_struktur'      => $this->input->post('jabatan'),
      'unit'              => $this->input->post('unit'),
      'jenis_unit'        => $this->input->post('jenis_unit'),
    );
    $this->db->where('uuid', $id);
    if ($this->db->update('pegawai', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Merubah Data Pegawai " . $this->input->post('nama')));
      redirect('Pegawai');
    } else {
      echo "gagal";
    }
  }

  function reset_login($id)
  {
    $this->db->where('uuid', $id);
    if ($this->db->update('pegawai', array('status_login' => 0))) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Reset Login "));
      redirect('Pegawai');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gaga Reset Login"));
      redirect('Pegawai');
    }
  }

  function reset_password($id)
  {
    $this->db->where('uuid', $id);
    if ($this->db->update('pegawai', array('password' => password_hash("pmijember", PASSWORD_DEFAULT, array("cost" => 10))))) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Reset Password "));
      redirect('Pegawai');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gaga Reset Password"));
      redirect('Pegawai');
    }
  }
}
