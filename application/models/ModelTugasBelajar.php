<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelTugasBelajar extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_all($tahun=null, $status=null)
  {
    if ($tahun != null || $tahun != "") {
      $this->db->where("tahun", $tahun);
    }
    if ($status != null || $status != "") {
      $this->db->where("tugas_belajar.status",$status);
    }
    return $this->db->get("tugas_belajar");
  }

  function get_tugasbelajar($id)
  {
    $this->db->where("idtugas_belajar",$id);
    return $this->db->get("tugas_belajar");
  }

  function get_cekPegawai($uuid)
  {
    $this->db->where("pegawai_uuid",$uuid);
    $this->db->where("tugas_belajar.status","1");
    return $this->db->get("tugas_belajar");
  }

}
