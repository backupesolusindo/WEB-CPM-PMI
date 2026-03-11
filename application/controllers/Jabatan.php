<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jabatan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('ModelJabatan');
    $this->load->model('ModelUsers');
  }

  function index()
  {
    $data = array(
      'title'     => "LIST JABATAN",
      'body'      => "User/Jabatan/list",
      'jabatan'   => $this->ModelJabatan->get_data()->result()
    );
    $this->load->view('index', $data);
  }

  function input()
  {
    $data = array(
      'title'     => "TAMBAH JABATAN",
      'body'      => "User/Jabatan/input",
      'group'     => $this->ModelUsers->get_group()->result()
    );
    $this->load->view('index', $data);
  }

  function edit()
  {
    $id = $this->uri->segment(3);
    $jabatan = $this->ModelJabatan->get_data_edit($id)->row_array();
    $data = array(
      'title'     => "EDIT JABATAN",
      'body'        => "User/Jabatan/edit",
      'group'       => $this->ModelUsers->get_group()->result(),
      'idjabatan'   => $jabatan['idjabatan'],
      'namajabatan' => $jabatan['namajabatan'],
      'j_roles'     => explode(', ', $jabatan['j_roles']),
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'idjabatan'     => $this->input->post('kode'),
      'namajabatan'   => $this->input->post('namajabatan'),
      'j_roles'       => implode(', ', $this->input->post('roles'))
    );
    $this->db->insert('jabatan', $data);
    redirect('Jabatan');
  }

  function update()
  {
    $id = $this->input->post('kode');
    $data = array(
      'namajabatan'   => $this->input->post('namajabatan'),
      'j_roles'       => implode(', ', $this->input->post('roles'))
    );
    $this->db->where('idjabatan', $id);
    $this->db->update('jabatan', $data);
    $this->db->where_in('Jabatan',$id);
    $this->db->update('user',array('roles'=>implode(', ', $this->input->post('roles'))));
    redirect('Jabatan');
  }

  function hapus()
  {
    $id = $this->uri->segment(3);
    $this->db->where('idjabatan', $id);
    $this->db->delete('jabatan');
    redirect('Jabatan');
  }

  function filter_roles()
  {
    $id       = $this->uri->segment(3);
    $jabatan  = $this->ModelJabatan->get_data_edit($id)->row_array();
    $data = array(
      'body'        => "User/Jabatan/edit",
      'group'       => $this->ModelUsers->get_group()->result(),
      'j_roles'     => explode(', ', $jabatan['j_roles']),
    );
    $this->load->view('User/Jabatan/filter_roles', $data);
  }

}
