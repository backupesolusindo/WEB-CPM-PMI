<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupRole extends CI_Controller{

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
      'body'      => "User/Group/list",
      'title'     => "List Group Roles",
      'group'     => $this->ModelUsers->get_group()->result()
    );
    $this->load->view('index', $data);
  }

  function input()
  {
    $data = array(
      'title'         => "Tambah Group Roles",
      'body'      => "User/Group/input",
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(

      'idgroup_roles' => $this->input->post('id'),
      'nama_group'    => $this->input->post('group'),
    );
    $this->db->insert('group_roles', $data);
    redirect('GroupRole');
  }

  function delete()
  {
    // code...
      $id = $this->input->post("id");
      // for ($i=0; $i < sizeof($id); $i++) {
      //   echo $id[$i];
      // }
      $this->db->where_in("idgroup_roles", $id);
      if ($this->db->delete("group_roles")) {

      }else {

      }
      redirect(base_url()."GroupRole");
  }


}
