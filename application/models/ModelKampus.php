<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelKampus extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function get_kampus($cari = null)
  {
    if ($cari != null && $cari != "") {
      $this->db->like("nama_kampus", $cari);
    }
    $this->db->order_by("nama_kampus");
    return $this->db->get("kampus");
  }

  public function get_kampus_aktif($cari = null)
  {
    $this->db->where("status", "aktif");
    if ($cari != null && $cari != "") {
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

  public function toggle_status($idkampus)
  {
    $current = $this->get_edit($idkampus)->row_array();
    if (!$current) return false;
    $new_status = ($current['status'] === 'aktif') ? 'nonaktif' : 'aktif';
    $this->db->where("idkampus", $idkampus);
    return $this->db->update("kampus", ['status' => $new_status]);
  }
}
