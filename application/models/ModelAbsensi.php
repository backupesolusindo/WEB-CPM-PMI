<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelAbsensi extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_Absensi($idabsen, $tanggal = null)
  {
    $this->db->join("pegawai","pegawai.uuid = absensi.pegawai_uuid");
    if ($tanggal != null || $tanggal != "") {
      $this->db->where("LEFT(waktu,10) = ", date("Y-m-d", strtotime($tanggal)));
    }
    $this->db->where("idabsensi", $idabsen);
    return $this->db->get("absensi");
  }

  function cek_Absensi($uuid, $tanggal)
  {
    $this->db->join("pegawai","pegawai.uuid = absensi.pegawai_uuid");
    $this->db->where("LEFT(waktu,10) = ", date("Y-m-d", strtotime($tanggal)));
    $this->db->where("pegawai_uuid", $uuid);
    $this->db->order_by("idabsensi","DESC");
    return $this->db->get("absensi");
  }

  public function cek_presensi_kegiatan($idkegiatan, $uuid, $tanggal)
  {
    $this->db->where("kegiatan_idkegiatan", $idkegiatan);
    $this->db->where("LEFT(jam_presensi,10) = ", date("Y-m-d", strtotime($tanggal)));
    $this->db->where("pegawai_uuid", $uuid);
    return $this->db->get("absen_kegiatan");
  }

  function get_AbsensiPulang($idabsen)
  {
    $this->db->where("absensi_idabsensi", $idabsen);
    return $this->db->get("absensi_pulang");
  }

  function get_AbsensiCabang($idabsen)
  {
    $this->db->where("absensi_idabsensi", $idabsen);
    return $this->db->get("absen_cabang");
  }

  function get_Absensi_Istirahat($idabsen, $tanggal = null)
  {
    if ($tanggal != null || $tanggal != "") {
      $this->db->where("LEFT(waktu,10) = ", date("Y-m-d", strtotime($tanggal)));
    }
    $this->db->where("idabsensi", $idabsen);
    return $this->db->get("absensi_istirahat");
  }

  function get_Selesai_Istirahat($idabsen)
  {
    $this->db->where("absensi_istirahat_idabsensi", $idabsen);
    return $this->db->get("absensi_selesai_istirahat");
  }

  function get_kegiatan($uuid)
  {
    $this->db->join("kegiatan","kegiatan.idkegiatan = absen_kegiatan.kegiatan_idkegiatan");
    $this->db->where("tanggal_selesai", "2021-09-02");
    $this->db->where("pegawai_uuid", $uuid);
    return $this->db->get("absen_kegiatan");
  }

  public function get_listabsensi()
  {
    $this->db->join("pegawai","pegawai.uuid = absensi.pegawai_uuid");
    $this->db->order_by("waktu","DESC");
    return $this->db->get('absensi');
  }

  function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2) {
  $theta = $lon1 - $lon2;
  $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
  $miles = acos($miles);
  $miles = rad2deg($miles);
  $miles = $miles * 60 * 1.1515;
  $feet  = $miles * 5280;
  $yards = $feet / 3;
  $kilometers = $miles * 1.609344;
  $meters = $kilometers * 1000;
  return $meters;
}

}
