<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Libur extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelLibur");
  }

  function index()
  {
    $data = array(
    'title'            => 'Libur Nasional',
    'body'             => 'Libur/list' ,
    );
    $this->load->view('index', $data);
  }

  function tabel()
  {
    $tahun = $this->input->post("tahun");
    $data = array(
      'libur'   => $this->ModelLibur->getLibur($tahun)->result()
    );
    $this->load->view('Libur/tabel', $data);
  }

  function input()
  {
    $data = array(
    'title'            => 'Libur Nasional',
    'body'             => 'Libur/input' ,
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'tanggal'       => date("Y-m-d", strtotime($this->input->post('tanggal'))),
      'keterangan'    => $this->input->post('keterangan'),
    );
    if ($this->db->insert('tanggal_libur', $data)) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Berhasil","type" => "success" ));
      redirect(base_url().'Libur');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Tambah Ulang","type" => "danger" ));
      redirect(base_url().'Libur');
    }
  }

  function delete($id)
  {
    $this->db->where("idtanggal_libur",$id);
    if ($this->db->insert('tanggal_libur')) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Berhasil","type" => "success" ));
      redirect(base_url().'Libur');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Tambah Ulang","type" => "danger" ));
      redirect(base_url().'Libur');
    }
  }

}
