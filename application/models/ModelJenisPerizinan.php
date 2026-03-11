<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelJenisPerizinan extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function get_jenisperizinan()
  {
    return $this->db->get("jenis_perizinan");
  }

  public function get_edit($idjenis_perizinan)
  {
    $this->db->where("idjenis_perizinan", $idjenis_perizinan);
    return $this->db->get("jenis_perizinan");
  }

}
