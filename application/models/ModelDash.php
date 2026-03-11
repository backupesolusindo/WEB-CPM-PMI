<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelDash extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function getBulanKegiatan($id,$bulan)
  {
    $this->db->where("LEFT(jam_presensi,7)",$bulan);
    $this->db->where("pegawai_uuid",$id);
    return $this->db->get("absen_kegiatan");
  }
  function getBulanPresensi($id,$bulan)
  {
    $this->db->where("LEFT(waktu,7)",$bulan);
    $this->db->where("pegawai_uuid",$id);
    return $this->db->get("absensi");
  }
  function getBulanCuti($id,$bulan)
  {
    $this->db->where("LEFT(tanggal_mulai,7)",$bulan);
    $this->db->where("pegawai_uuid",$id);
    return $this->db->get("izin");
  }

}
