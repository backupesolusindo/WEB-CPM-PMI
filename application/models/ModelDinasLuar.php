<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelDinasLuar extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function getAll($tgl_mulai = null, $tgl_akhir = null)
  {
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('tanggal_mulai BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
      $this->db->or_where('tanggal_selesai BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
    }
    return $this->db->get("dinas_luar");
  }

  function get_data($iddinasluar)
  {
    $this->db->where("iddinas_luar", $iddinasluar);
    return $this->db->get("dinas_luar");
  }

  function getUndanganPeserta($iddinasluar)
  {
    $this->db->join("pegawai", "pegawai.uuid = pegawai_dinasluar.pegawai_uuid");
    $this->db->where("dinas_luar_iddinas_luar", $iddinasluar);
    return $this->db->get("pegawai_dinasluar");
  }

  function cekDinasLuar($uuid, $tanggal)
  {
    $this->db->join("dinas_luar","dinas_luar.iddinas_luar = pegawai_dinasluar.dinas_luar_iddinas_luar");
    $this->db->where("pegawai_uuid", $uuid);
    $this->db->where("dinas_luar.tanggal_mulai <=","$tanggal");
    $this->db->where("dinas_luar.tanggal_selesai >=","$tanggal");
    return $this->db->get("pegawai_dinasluar");
  }

}
