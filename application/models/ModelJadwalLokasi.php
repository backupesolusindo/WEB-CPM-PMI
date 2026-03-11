<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelJadwalLokasi extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_jadwalpegawai($uuid, $hari)
  {
    $this->db->join("kampus", "kampus.idkampus = hari_lokasi.kampus_idkampus", "left");
    $this->db->where("pegawai_uuid", $uuid);
    $this->db->where("hari", $hari);
    return $this->db->get("hari_lokasi");
  }

  function cek_jadwalpegawai($uuid, $idkampus, $hari)
  {
    $this->db->join("kampus", "kampus.idkampus = hari_lokasi.kampus_idkampus");
    $this->db->where("pegawai_uuid", $uuid);
    $this->db->where("kampus_idkampus", $idkampus);
    $this->db->where("hari", $hari);
    return $this->db->get("hari_lokasi");
  }
}
