<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKampus extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function get_kampus($cari = null)
  {
    if ($cari != null || $cari != "") {
      $this->db->like("nama_kampus", $cari);
    }
    $this->db->order_by("nama_kampus");
    return $this->db->get("kampus");
  }

  public function get_edit($idkampus)
  {
    $this->db->where("idkampus", $idkampus);
    return $this->db->get("kampus");
  }

}
