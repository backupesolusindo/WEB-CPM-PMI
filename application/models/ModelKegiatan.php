<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelKegiatan extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_all($tgl_mulai = null, $tgl_akhir = null, $unit=null, $sub_unit=null)
  {
    $this->db->join("pegawai","kegiatan.uuid_pic = pegawai.uuid");
    $this->db->join("unit","kegiatan.unit_idunit = unit.idunit");
    $this->db->join("kampus","kegiatan.kampus_idkampus = kampus.idkampus","LEFT");
    $this->db->join("gedung","kegiatan.gedung_idgedung = gedung.idgedung","LEFT");
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->or_where('kegiatan.tanggal BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
      // $this->db->where('kegiatan.tanggal_selesai BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
    }
    if ($sub_unit != null || $sub_unit != "") {
      $this->db->like("unit.nama_unit",$sub_unit);
    }elseif ($unit != null || $unit != "") {
      $this->db->group_start();
      $this->db->like("unit.nama_unit",$unit);
      $this->db->or_like("unit.parent_unit",$unit);
      $this->db->group_end();
    }
    $this->db->order_by("kegiatan.tanggal","DESC");
    return $this->db->get("kegiatan");
  }

  function get_kegiatan_peserta($tgl_mulai = null, $tgl_akhir = null, $uuid=null)
  {
    $this->db->join("pegawai","kegiatan.uuid_pic = pegawai.uuid");
    $this->db->join("unit","kegiatan.unit_idunit = unit.idunit");
    $this->db->join("kegiatan_peserta","kegiatan_peserta.kegiatan_idkegiatan = kegiatan.idkegiatan");
    $this->db->join("kampus","kegiatan.kampus_idkampus = kampus.idkampus","LEFT");
    $this->db->join("gedung","kegiatan.gedung_idgedung = gedung.idgedung","LEFT");
    if ($uuid != null || $uuid != "") {
      $this->db->where("kegiatan_peserta.pegawai_uuid",$uuid);
    }
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('kegiatan.tanggal BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
      // $this->db->where('kegiatan.tanggal_selesai BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
    }
    return $this->db->get("kegiatan");
  }

  function get_kegiatan_terkini($uuid=null)
  {
    $this->db->join("pegawai","kegiatan.uuid_pic = pegawai.uuid");
    $this->db->join("unit","kegiatan.unit_idunit = unit.idunit");
    $this->db->join("kegiatan_peserta","kegiatan_peserta.kegiatan_idkegiatan = kegiatan.idkegiatan");
    $this->db->join("kampus","kegiatan.kampus_idkampus = kampus.idkampus","LEFT");
    $this->db->join("gedung","kegiatan.gedung_idgedung = gedung.idgedung","LEFT");
    if ($uuid != null || $uuid != "") {
      $this->db->where("kegiatan_peserta.pegawai_uuid",$uuid);
    }
    // $this->db->where('kegiatan.tanggal <= "'.date("Y-m-d").'"');
    $this->db->where('kegiatan.tanggal_selesai >= "'.date("Y-m-d").'"');
    return $this->db->get("kegiatan");
  }

  function get_cek($idkegiatan, $uuid, $tanggal = null)
  {
    if ($tanggal != null || $tanggal != "") {
      $this->db->where("LEFT(jam_presensi,10)", date("Y-m-d", strtotime($tanggal)));
    }
    $this->db->where("kegiatan_idkegiatan",$idkegiatan);
    $this->db->where("pegawai_uuid",$uuid);
    return $this->db->get("absen_kegiatan");
  }

  function riwayat_kegiatan($uuid, $aproval = null, $tgl_mulai = null, $tgl_akhir = null)
  {
    $this->db->join("pegawai", "pegawai.uuid = absen_kegiatan.pegawai_uuid");
    $this->db->where("uuid",$uuid);
    if ($aproval != null || $aproval != "") {
      $this->db->where("status_aproval", $aproval);
    }
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absen_kegiatan.jam_presensi,10) BETWEEN "'.$tgl_mulai.'" AND "'.$tgl_akhir.'"');
    }
    return $this->db->get("absen_kegiatan");
  }

  function get_data($id)
  {
    $this->db->join("pegawai","kegiatan.uuid_pic = pegawai.uuid");
    $this->db->join("unit","kegiatan.unit_idunit = unit.idunit");
    $this->db->join("kampus","kegiatan.kampus_idkampus = kampus.idkampus","LEFT");
    $this->db->join("gedung","kegiatan.gedung_idgedung = gedung.idgedung","LEFT");
    $this->db->where("idkegiatan", $id);
    return $this->db->get("kegiatan");
  }

  function cekKode($id)
  {
    $this->db->where("idkegiatan", $id);
    return $this->db->get("kegiatan");
  }

  function getKegiatanAproval($unit = null, $aproval = null, $tgl_mulai = null, $tgl_akhir = null, $sub_unit = null)
  {
    $this->db->join("pegawai", "pegawai.uuid = absen_kegiatan.pegawai_uuid");
    $this->db->join("unit", "unit.nama_unit LIKE CONCAT_WS(' ', pegawai.jenis_unit, pegawai.unit)");
    if ($aproval != null || $aproval != "") {
      $this->db->where("status_aproval", $aproval);
    }
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absen_kegiatan.jam_presensi,10) BETWEEN "'.$tgl_mulai.'" AND "'.$tgl_akhir.'"');
    }
    if ($sub_unit != null || $sub_unit != "") {
      $this->db->like("unit.nama_unit",$sub_unit);
    }elseif ($unit != null || $unit != "") {
      $this->db->group_start();
      $this->db->like("unit.nama_unit",$unit);
      $this->db->or_like("unit.parent_unit",$unit);
      $this->db->group_end();
    }
    return $this->db->get("absen_kegiatan");
  }

  function getPesertaKegiatan($idkegiatan)
  {
    $this->db->join("pegawai","pegawai.uuid = absen_kegiatan.pegawai_uuid");
    $this->db->where("kegiatan_idkegiatan", $idkegiatan);
    $this->db->order_by("jam_presensi");
    return $this->db->get("absen_kegiatan");
  }

  function getUndanganPeserta($idkegiatan)
  {
    $this->db->join("pegawai","pegawai.uuid = kegiatan_peserta.pegawai_uuid");
    $this->db->where("kegiatan_idkegiatan", $idkegiatan);
    return $this->db->get("kegiatan_peserta");
  }

  function get_kegiatan_unit($unit=null)
  {
    $this->db->join("pegawai","kegiatan.uuid_pic = pegawai.uuid");
    $this->db->join("unit","kegiatan.unit_idunit = unit.idunit");
    $this->db->join("kampus","kegiatan.kampus_idkampus = kampus.idkampus","LEFT");
    $this->db->join("gedung","kegiatan.gedung_idgedung = gedung.idgedung","LEFT");
    if ($unit != null || $unit != "") {
      $this->db->where("unit.nama_unit",$unit);
    }
    $this->db->order_by("kegiatan.tanggal","DESC");
    return $this->db->get("kegiatan");
  }

  function get_kegiatan_unit_terlaksana($unit=null)
  {
    $this->db->join("pegawai","kegiatan.uuid_pic = pegawai.uuid");
    $this->db->join("unit","kegiatan.unit_idunit = unit.idunit");
    $this->db->join("kampus","kegiatan.kampus_idkampus = kampus.idkampus","LEFT");
    $this->db->join("gedung","kegiatan.gedung_idgedung = gedung.idgedung","LEFT");
    if ($unit != null || $unit != "") {
      $this->db->where("unit.nama_unit",$unit);
    }
    $this->db->where('kegiatan.tanggal_selesai >= "'.date("Y-m-d").'"');
    $this->db->order_by("kegiatan.tanggal","DESC");
    return $this->db->get("kegiatan");
  }

}
