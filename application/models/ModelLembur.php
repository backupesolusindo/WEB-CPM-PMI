<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelLembur extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_all($tgl_mulai = null, $tgl_akhir = null, $unit=null, $sub_unit=null)
  {
    $this->db->join("unit","kegiatan.unit_idunit = unit.idunit");
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->or_where('kegiatan.tgl_mulai BETWEEN "'.date("Y-m-d", strtotime($tgl_mulai)).'" AND "'.date("Y-m-d", strtotime($tgl_akhir)).'"');
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
    $this->db->order_by("lembur.tgl_mulai","DESC");
    return $this->db->get("lembur");
  }

  function get_lembur_unit($unit=null)
  {
    $this->db->join("unit","lembur.unit_idunit = unit.idunit");
    if ($unit != null || $unit != "") {
      $this->db->where("unit.nama_unit",$unit);
    }
    $this->db->order_by("lembur.tgl_mulai","DESC");
    return $this->db->get("lembur");
  }

  function get_data($id)
  {
    $this->db->join("unit","lembur.unit_idunit = unit.idunit");
    $this->db->where("idlembur", $id);
    return $this->db->get("lembur");
  }

  function getUndanganPeserta($idlembur)
  {
    // $this->db->join("pegawai","pegawai.uuid = peserta_lembur.pegawai_uuid");
    $this->db->where("peserta_lembur.lembur_idlembur", $idlembur);
    return $this->db->get("peserta_lembur");
  }

  function get_terkini($uuid=null)
  {
    $this->db->join("unit","lembur.unit_idunit = unit.idunit");
    $this->db->join("peserta_lembur","peserta_lembur.lembur_idlembur = lembur.idlembur");
    // if ($uuid != null || $uuid != "") {
    //   $this->db->where("peserta_lembur.pegawai_uuid",$uuid);
    // }
    $this->db->where('lembur.tgl_selesai >= "'.date("Y-m-d").'"');
    return $this->db->get("lembur");
  }

  function get_cek($idlembur, $uuid, $tanggal = null)
  {
    if ($tanggal != null || $tanggal != "") {
      $this->db->where("LEFT(jam_presensi,10)", date("Y-m-d", strtotime($tanggal)));
    }
    $this->db->where("lembur_idlembur",$idlembur);
    $this->db->where("pegawai_uuid",$uuid);
    return $this->db->get("absen_lembur");
  }

  function getKegiatanAproval($unit = null, $aproval = null, $tgl_mulai = null, $tgl_akhir = null, $sub_unit = null)
  {
    $this->db->join("pegawai", "pegawai.uuid = absen_lembur.pegawai_uuid");
    $this->db->join("unit", "unit.nama_unit LIKE CONCAT_WS(' ', pegawai.jenis_unit, pegawai.unit)");
    if ($aproval != null || $aproval != "") {
      $this->db->where("status_aproval", $aproval);
    }
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absen_lembur.jam_presensi,10) BETWEEN "'.$tgl_mulai.'" AND "'.$tgl_akhir.'"');
    }
    if ($sub_unit != null || $sub_unit != "") {
      $this->db->like("unit.nama_unit",$sub_unit);
    }elseif ($unit != null || $unit != "") {
      $this->db->group_start();
      $this->db->like("unit.nama_unit",$unit);
      $this->db->or_like("unit.parent_unit",$unit);
      $this->db->group_end();
    }
    return $this->db->get("absen_lembur");
  }

  function riwayat_lembur($uuid, $aproval = null, $tgl_mulai = null, $tgl_akhir = null)
  {
    $this->db->join("pegawai", "pegawai.uuid = absen_lembur.pegawai_uuid");
    $this->db->where("uuid",$uuid);
    if ($aproval != null || $aproval != "") {
      $this->db->where("status_aproval", $aproval);
    }
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absen_lembur.jam_presensi,10) BETWEEN "'.$tgl_mulai.'" AND "'.$tgl_akhir.'"');
    }
    return $this->db->get("absen_lembur");
  }

}
