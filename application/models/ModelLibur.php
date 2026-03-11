<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelLibur extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function getLibur($tahun)
  {
    $this->db->where("LEFT(tanggal, 4) ",$tahun);
    $this->db->order_by("tanggal");
    return $this->db->get("tanggal_libur");
  }
  function getDataLibur($tanggal)
  {
    $this->db->where("tanggal",date("Y-m-d", strtotime($tanggal)));
    return $this->db->get("tanggal_libur");
  }

}
