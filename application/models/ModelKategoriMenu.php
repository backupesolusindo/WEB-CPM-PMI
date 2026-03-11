<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKategoriMenu extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function list()
  {
    return $this->db->get('kategori_menu');
  }

  function edit($id)
  {
    $this->db->where('idkategori_menu', $id);
    return $this->db->get('kategori_menu');
  }
}
