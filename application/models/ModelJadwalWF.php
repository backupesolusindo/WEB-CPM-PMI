<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelJadwalWF extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function getCheck($uuid, $tanggal)
  {
    $this->db->where("tanggal", date("Y-m-d", strtotime($tanggal)));
    $this->db->where("pegawai_uuid", $uuid);
    return $this->db->get("jadwal_wf");
  }
  function getJadwal($uuid, $tanggal = null)
  {
    // $this->db->where("tanggal", date("Y-m-d", strtotime($tanggal)));
    $this->db->where("pegawai_uuid", $uuid);
    return $this->db->get("jadwal_wf");
  }

}
