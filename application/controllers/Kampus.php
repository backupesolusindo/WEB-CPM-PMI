<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kampus extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelKampus");
  }

  function index()
  {
    $data = array(
    'title'  => 'KAMPUS',
    'body'   => 'Kampus/list' ,
    'kampus'   => $this->ModelKampus->get_kampus()->result(),
  );
    $this->load->view('index', $data);
  }

  function get_lokasi($id)
  {
    $kampus = $this->ModelKampus->get_edit($id)->row_array();
    echo json_encode($kampus);
  }

  function input()
  {
    $data = array(
      'title'=> 'FORM INPUT KAMPUS',
      'form' => 'Kampus/form',
      'body' => 'Kampus/input',
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'nama_kampus'   => $this->input->post("nama_kampus"),
      'latitude'      => $this->input->post("latitude"),
      'longtitude'    => $this->input->post("longtitude"),
    );
    if ($this->db->insert('kampus', $data)) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Berhasil","type" => "success" ));
      redirect(base_url().'Kampus');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Tambah Ulang","type" => "danger" ));
      redirect(base_url().'Kampus');
    }
  }

  function edit($idkampus)
  {
    $data = array(
      'title' => 'FORM EDIT KAMPUS',
      'form'  => 'Kampus/form',
      'body'  => 'Kampus/edit' ,
      'kampus'  => $this->ModelKampus->get_edit($idkampus)->row_array(),
    );
    $this->load->view('index', $data);
  }

  function update()
  {
    $data = array(
      'nama_kampus'   => $this->input->post("nama_kampus"),
      'latitude'      => $this->input->post("latitude"),
      'longtitude'    => $this->input->post("longtitude"),
    );
    $this->db->where("idkampus", $this->input->post("idkampus"));
    if ($this->db->update('kampus', $data)) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Edit Data Berhasil","type" => "success" ));
      redirect(base_url().'Kampus');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Edit Ulang","type" => "danger" ));
      redirect(base_url().'Kampus');
    }
  }

  function hapus($idkampus)
  {
      $this->db->where("idkampus", $idkampus);
      if ($this->db->delete('kampus')) {
        $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Hapus Data Berhasil","type" => "success" ));
        redirect(base_url().'Kampus');
      } else {
        $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Hapus Ulang","type" => "danger" ));
        redirect(base_url().'Kampus');
      }
  }

}
