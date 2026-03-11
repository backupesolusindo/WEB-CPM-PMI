<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('ModelPegawai');
    $this->load->model('ModelUnit');
  }

  function cobaDOM()
  {
    $array = array(
      'title'       => "Monitoring Akses",
      'body'        => "Monitoring/cobaDOM",
    );

    $this->load->view('index', $array);
  }

  function index()
  {
    $array = array(
      'title'       => "Monitoring Akses",
      'body'        => "Monitoring/list",
      // 'btn_title'   => "<a href=\"".base_url()."Pegawai/input\"><button type=\"button\" class=\"btn btn-info d-none d-lg-block m-l-15\"><i class=\"fa fa-plus-circle\"></i> Tambah Karyawan</button></a>",
      'Pegawai'     => $this->ModelPegawai->get_allmonitoring()->result(),
      // 'Atasan'      => $this->ModelPegawai->get_atasan()->result(),
    );

    $this->load->view('index', $array);
  }

  function list_pegawai($uuid)
  {
    $array = array(
      'title'       => "Monitoring Akses",
      'body'        => "Monitoring/list_monitoring",
      'uuid'        => $uuid,
      'pimpinan'    => $this->ModelPegawai->edit($uuid)->row_array(),
      'Kepala'      => $this->ModelPegawai->get_kepalamonitoring($uuid)->result(),
    );
    $this->load->view('index', $array);
  }

  function input()
  {
    $data = array(
      'title'     => 'FORM INPUT KEPALA UNIT',
      'body'      => 'Monitoring/input',
      'unit'      => $this->ModelUnit->get_unit()->result(),
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $data);
  }

  function input_atasan()
  {
    $data = array(
      'title'     => 'FORM INPUT ATASAN',
      'body'      => 'Monitoring/input_atasan',
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'unit_idunit'        => $this->input->post("idunit"),
      'pegawai_uuid'       => $this->input->post("uuid"),
      'monitor'            => $this->input->post("monitor"),
    );
    if ($this->db->insert('kepala_unit', $data)) {
      $this->db->where("uuid",$this->input->post("uuid"));
      $this->db->update("pegawai", array('status_monitoring' => "1"));
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
      redirect(base_url().'Monitoring');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
      redirect(base_url().'Monitoring');
    }
  }

  function insert_atasan()
  {
    $data = array(
      'jab_atasan'            => $this->input->post("jab_atasan"),
      'status_monitoring'     => "1",
    );
    $this->db->where("uuid",$this->input->post("uuid"));
    if ($this->db->update('pegawai', $data)) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
      redirect(base_url().'Monitoring');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
      redirect(base_url().'Monitoring');
    }
  }

  function hapus($id)
  {
    $this->db->where("idkepala_unit", $id);
    if ($this->db->delete('kepala_unit')) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Hapus Data Berhasil"));
      redirect(base_url().'Monitoring');
    } else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Hapus Ulang"));
      redirect(base_url().'Monitoring');
    }
  }

  function hirarki()
  {
    $array = array(
      'title'       => "Monitoring Akses",
      'body'        => "Monitoring/hirarki",
      'unit'        => $this->ModelUnit->get_parent_unit()->result()
    );
    $this->load->view('index', $array);
  }

  function tabel_hirarki()
  {
    $array = array(
      'unit_filter' => $this->input->post("unit"),
      'atasan'      => $this->ModelPegawai->get_atasan()->result(),
    );
    $this->load->view('Monitoring/tabel_hirarki', $array);
  }

  function excel()
  {
    $array = array(
      'title'       => "Monitoring Akses",
      'body'        => "Monitoring/excel_hirarki",
      'unit'        => $this->ModelUnit->get_parent_unit()->result()
    );
    $this->load->view('index', $array);
  }

  function tabel_excel()
  {
    $array = array(
      'unit'       => $this->input->post("unit"),
      'atasan'      => $this->ModelPegawai->get_atasan()->result(),
    );
    $this->load->view('Monitoring/data_excel', $array);
  }

}
