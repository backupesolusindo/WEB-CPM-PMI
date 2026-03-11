<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelGedung extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function get_gedung($idkampus =null)
  {
    $this->db->select("kampus.nama_kampus, gedung.*");
    if ($idkampus != null || $idkampus != "") {
      $this->db->where("kampus_idkampus", $idkampus);
    }
    $this->db->join("kampus","kampus.idkampus = gedung.kampus_idkampus");
    return $this->db->get('gedung');
  }

  public function get_edit($idgedung)
  {
    $this->db->where("idgedung", $idgedung);
    return $this->db->get("gedung");
  }

}
