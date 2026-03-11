<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelJadwalMasuk extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function get_all_jadwalmasuk()
  {
    $this->db->join("jabatan","jabatan.idjabatan = jadwal_masuk.jabatan_idjabatan");
    return $this->db->get('jadwal_masuk');
  }

  public function get_jadwalmasuk($idjabatan = null, $jenis = 1)
  {
    $this->db->join("jabatan","jabatan.idjabatan = jadwal_masuk.jabatan_idjabatan");
    if ($idjabatan != null || $idjabatan != "") {
      $this->db->where("jabatan_idjabatan", $idjabatan);
    }
    // $this->db->where("jenis", $jenis);
    return $this->db->get('jadwal_masuk');
  }

  public function get_edit($idjadwal_masuk)
  {
    $this->db->where("idjadwal_masuk", $idjadwal_masuk);
    $this->db->join("jabatan","jabatan.idjabatan = jadwal_masuk.jabatan_idjabatan");
    return $this->db->get("jadwal_masuk");
  }

  public function get_jadwal_jabatan($jabatan, $wf)
  {
    $this->db->where("jabatan_idjabatan", $jabatan);
    $this->db->where("jenis", $wf);
    return $this->db->get("jadwal_masuk");
  }


}
