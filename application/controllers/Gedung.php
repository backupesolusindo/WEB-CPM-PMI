<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gedung extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelGedung");
    $this->load->model("ModelKampus");
  }

  function index()
  {
    $data = array(
    'title'     => 'GEDUNG',
    'body'      => 'Gedung/list' ,
    'gedung'   => $this->ModelGedung->get_gedung()->result(),
    // 'kampus'   => $this->ModelKampus->get_kampus()->result(),
  );
    $this->load->view('index', $data);
  }

  function get_listgedung($idkampus)
  {
    $kampus = $this->ModelGedung->get_gedung($idkampus)->result();
    echo json_encode($kampus);
  }

  function get_gedung($idgedung)
  {
    $kampus = $this->ModelGedung->get_edit($idgedung)->row_array();
    echo json_encode($kampus);
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
      'title'=> 'FORM INPUT GEDUNG',
      'form' => 'Gedung/form',
      'body' => 'Gedung/input',
      'kampus'   => $this->ModelKampus->get_kampus()->result(),
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'nama_gedung'   => $this->input->post("nama_gedung"),
      'latitude'      => $this->input->post("latitude"),
      'longtitude'    => $this->input->post("longtitude"),
      'kampus_idkampus' =>  $this->input->post("kampus_idkampus"),
    );
    if ($this->db->insert('gedung', $data)) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Berhasil","type" => "success" ));
      redirect(base_url().'Gedung');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Tambah Ulang","type" => "danger" ));
      redirect(base_url().'Gedung');
    }
  }

  function edit($idgedung)
  {
    $data = array(
      'title' => 'FORM EDIT GEDUNG',
      'form'  => 'Gedung/form',
      'body'  => 'Gedung/edit' ,
      'gedung'  => $this->ModelGedung->get_edit($idgedung)->row_array(),
      'kampus'   => $this->ModelKampus->get_kampus()->result(),
    );
    $this->load->view('index', $data);
  }

  function update()
  {
    $data = array(
      'nama_gedung'   => $this->input->post("nama_gedung"),
      'latitude'      => $this->input->post("latitude"),
      'longtitude'    => $this->input->post("longtitude"),
      'kampus_idkampus' =>  $this->input->post("kampus_idkampus"),
    );
    $this->db->where("idgedung", $this->input->post("idgedung"));
    if ($this->db->update('gedung', $data)) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Edit Data Berhasil","type" => "success" ));
      redirect(base_url().'Gedung');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Edit Ulang","type" => "danger" ));
      redirect(base_url().'Gedung');
    }
  }

  function hapus($idgedung)
  {
      $this->db->where("idgedung", $idgedung);
      if ($this->db->delete('gedung')) {
        $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Hapus Data Berhasil","type" => "success" ));
        redirect(base_url().'Gedung');
      } else {
        $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Hapus Ulang","type" => "danger" ));
        redirect(base_url().'Gedung');
      }
  }

}
