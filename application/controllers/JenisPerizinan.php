<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisPerizinan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelJenisPerizinan");
  }

  function index()
  {
    $data = array(
    'title'            => 'JENIS PERIZINAN',
    'body'             => 'JenisPerizinan/list' ,
    'jenisperizinan'   => $this->ModelJenisPerizinan->get_jenisperizinan()->result(),
  );
    $this->load->view('index', $data);
  }

  function coba()
  {
    $param = array(
      // "limit" => 0,
      // "offset" => 100,
      // "jenis" => "PROGRAM STUDI",
      // "level" => 2,
      // "uuid" => "c9eb9bef-00f3-11eb-ab7b-fefcfe8d8c7c",
      "parent" => "KANTOR POLIJE",
      "informations" => false,
    );
    $response = json_decode(bridge("global/unit",$param));
  }

  function input()
  {
    $data = array(
      'title' =>  'FORM INPUT JENIS PERIZINAN',
      'form' => 'JenisPerizinan/form',
      'body' => 'JenisPerizinan/input',
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'jenis_izin'        => $this->input->post("jenis_izin"),
    );
    if ($this->db->insert('jenis_perizinan', $data)) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Berhasil","type" => "success" ));
      redirect(base_url().'JenisPerizinan');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Tambah Ulang","type" => "danger" ));
      redirect(base_url().'JenisPerizinan');
    }
  }

  function edit($idjenisperizinan)
  {
    $data = array(
      'title'           => 'FORM EDIT JENIS PERIZINAN',
      'form'            => 'JenisPerizinan/form',
      'body'            => 'JenisPerizinan/edit' ,
      'jenisperizinan'  => $this->ModelJenisPerizinan->get_edit($idjenisperizinan)->row_array(),
    );
    $this->load->view('index', $data);
  }

  function update()
  {
    $data = array(
      'jenis_izin'        => $this->input->post("jenis_izin"),
    );
    $this->db->where("idjenis_perizinan", $this->input->post("idjenis_perizinan"));
    if ($this->db->update('jenis_perizinan', $data)) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Edit Data Berhasil","type" => "success" ));
      redirect(base_url().'JenisPerizinan');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Edit Ulang","type" => "danger" ));
      redirect(base_url().'JenisPerizinan');
    }
  }

  function hapus($idjenis_perizinan)
  {
      $this->db->where("idjenis_perizinan", $idjenis_perizinan);
      if ($this->db->delete('jenis_perizinan')) {
        $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Hapus Data Berhasil","type" => "success" ));
        redirect(base_url().'JenisPerizinan');
      } else {
        $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Hapus Ulang","type" => "danger" ));
        redirect(base_url().'JenisPerizinan');
      }
  }

}
