<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAproval extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function ApPresensiHarian($unit, $uuid, $hidden_diri = true)
  {
    $this->db->join("pegawai", "pegawai.uuid = absensi.pegawai_uuid");
    $this->db->join("unit", "unit.nama_unit LIKE CONCAT_WS(' ', pegawai.jenis_unit, pegawai.unit)");
    $this->db->where("unit.nama_unit",$unit);
    $this->db->where("status_absensi", "0");
    if ($hidden_diri) {
      $this->db->where("pegawai.uuid != ",$uuid);
    }
    return $this->db->get("absensi");
  }

  function ApKepPresensiHarian($unit, $uuid, $monitoring = 1, $hidden_diri = true)
  {
    $this->db->join("pegawai", "pegawai.uuid = absensi.pegawai_uuid");
    $this->db->join("kepala_unit","kepala_unit.pegawai_uuid = pegawai.uuid");
    $this->db->join("unit","unit.idunit = kepala_unit.unit_idunit");
    $this->db->where("status_absensi", "0");
    if ($hidden_diri) {
      $this->db->where("pegawai.uuid != ",$uuid);
    }
    $this->db->group_start();
    $this->db->where("unit.nama_unit",$unit);
    if ($monitoring == 1) {
      $this->db->or_where("unit.parent_unit",$unit);
    }
    $this->db->group_end();
    return $this->db->get("absensi");
  }

}
