<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelLaporan extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function rekapPresensi($uuid, $tgl_mulai=null, $tgl_akhir=null)
  {
    $this->db->where("pegawai_uuid",$uuid);
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absensi.waktu,10) BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
    }
    return $this->db->get("absensi");
  }
  function rekapPresensiDouble($uuid, $tgl_mulai=null, $tgl_akhir=null)
  {
    $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
    $this->db->where("pegawai_uuid",$uuid);
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absensi.waktu,10) BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
    }
    $this->db->group_by("LEFT(absensi.waktu,10)");
    $this->db->order_by("absensi.waktu");
    return $this->db->get("absensi");
  }

  function rekapPresensiLuarJam($uuid, $tgl_mulai=null, $tgl_akhir=null)
  {
    $this->db->where("pegawai_uuid",$uuid);
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(presensi_lokasi.waktu,10) BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
    }
    return $this->db->get("presensi_lokasi");
  }

  function rekapKegiatan($uuid, $tgl_mulai = null, $tgl_akhir = null)
  {
    $this->db->join("pegawai","absen_kegiatan.pegawai_uuid = pegawai.uuid");
    $this->db->join("kegiatan","kegiatan.idkegiatan = absen_kegiatan.kegiatan_idkegiatan");
    $this->db->where("pegawai_uuid",$uuid);
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('absen_kegiatan.jam_presensi BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
    }
    return $this->db->get("absen_kegiatan");
  }

  public function PresensiAktif($uuid)
  {
    $this->db->join("absensi","absensi.pegawai_uuid = pegawai.uuid");
    $this->db->where("LEFT(absensi.waktu,7) =", date("Y-m"));
    $this->db->where("pegawai.uuid", $uuid);
    $this->db->order_by("absensi.idabsensi", "DESC");
    return $this->db->get("pegawai");
  }
  public function rekap_kerjadiluarjam($uuid, $tgl_mulai=null, $tgl_akhir=null)
  {
    $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
    $this->db->where("pegawai_uuid",$uuid);
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(presensi_lokasi.waktu,10) BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
    }
    $this->db->group_by("LEFT(presensi_lokasi.waktu,10)");
    return $this->db->get("presensi_lokasi");
  }

  public function rekap_lembur($uuid, $tgl_mulai=null, $tgl_akhir=null)
  {
    $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
    $this->db->where("pegawai_uuid",$uuid);
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absen_lembur.jam_presensi,10) BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
    }
    $this->db->group_by("LEFT(absen_lembur.jam_presensi,10)");
    return $this->db->get("absen_lembur");
  }

}
