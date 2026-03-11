<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelRiwayat extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function RiwayatHarian($uuid, $aproval = null, $tgl_mulai = null, $tgl_akhir = null)
  {
    $this->db->join("pegawai", "pegawai.uuid = absensi.pegawai_uuid");
    $this->db->where("uuid",$uuid);
    if ($aproval != null || $aproval != "") {
      $this->db->where("status_absensi", $aproval);
    }
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absensi.waktu,10) BETWEEN "'.$tgl_mulai.'" AND "'.$tgl_akhir.'"');
    }
    return $this->db->get("absensi");
  }

  function RiwayatHarianMonitoring($unit, $aproval = null, $tgl_mulai = null, $tgl_akhir = null, $sub_unit = null)
  {
    $this->db->join("pegawai", "pegawai.uuid = absensi.pegawai_uuid");
    $this->db->where("pegawai.status_aktif", "1");
    // $this->db->join("unit", "unit.nama_unit LIKE pegawai.unit");
    if ($aproval != null || $aproval != "") {
      $this->db->where("status_absensi", $aproval);
    }
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absensi.waktu,10) BETWEEN "'.$tgl_mulai.'" AND "'.$tgl_akhir.'"');
    }
    if ($sub_unit != null || $sub_unit != "") {
      $this->db->where("unit.nama_unit",$sub_unit);
    }elseif ($unit != null || $unit != "") {
      $this->db->group_start();
      $this->db->where("unit.nama_unit",$unit);
      $this->db->or_where("unit.parent_unit",$unit);
      $this->db->group_end();
    }
    return $this->db->get("absensi");
  }

  function RiwayatHarianLokasiMonitoring($unit, $lokasi = null, $tgl_mulai = null, $tgl_akhir = null, $sub_unit = null)
  {
    $this->db->join("pegawai", "pegawai.uuid = absensi.pegawai_uuid");
    $this->db->join("unit", "unit.nama_unit LIKE CONCAT_WS(' ', pegawai.jenis_unit, pegawai.unit)");
    if ($lokasi != null || $lokasi != "") {
      $this->db->where("jenis_tempat", $lokasi);
    }
    if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
      $this->db->where('LEFT(absensi.waktu,10) BETWEEN "'.$tgl_mulai.'" AND "'.$tgl_akhir.'"');
    }
    if ($sub_unit != null || $sub_unit != "") {
      $this->db->where("unit.nama_unit",$sub_unit);
    }elseif ($unit != null || $unit != "") {
      $this->db->group_start();
      $this->db->where("unit.nama_unit",$unit);
      $this->db->or_where("unit.parent_unit",$unit);
      $this->db->group_end();
    }
    return $this->db->get("absensi");
  }

  function RiwayatHarianMonitoringGrafik($unit, $aproval = null, $tgl_mulai = null, $tgl_akhir = null, $sub_unit = null)
  {
    $this->db->query('SET SESSION sql_mode = ""');

    // ONLY_FULL_GROUP_BY
    $this->db->query('SET SESSION sql_mode =
    REPLACE(REPLACE(REPLACE(
      @@sql_mode,
      "ONLY_FULL_GROUP_BY,", ""),
      ",ONLY_FULL_GROUP_BY", ""),
      "ONLY_FULL_GROUP_BY", "")');
      $this->db->join("pegawai", "pegawai.uuid = absensi.pegawai_uuid");
      $this->db->join("unit", "unit.nama_unit LIKE CONCAT_WS(' ', pegawai.jenis_unit, pegawai.unit)");
      if ($aproval != null || $aproval != "") {
        $this->db->where("status_absensi", $aproval);
      }
      if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
        $this->db->where('LEFT(absensi.waktu,10) BETWEEN "'.$tgl_mulai.'" AND "'.$tgl_akhir.'"');
      }
      if ($sub_unit != null || $sub_unit != "") {
        $this->db->like("unit.nama_unit",$sub_unit);
      }elseif ($unit != null || $unit != "") {
        $this->db->group_start();
        $this->db->like("unit.nama_unit",$unit);
        $this->db->or_like("unit.parent_unit",$unit);
        $this->db->group_end();
      }
      $this->db->group_by("LEFT(absensi.waktu,10)");
      return $this->db->get("absensi");
    }

    function RiwayatHarianMonitoringPegawai($uuid, $aproval = null, $tgl_mulai = null, $tgl_akhir = null)
    {
      $this->db->join("pegawai", "pegawai.uuid = absensi.pegawai_uuid");
      if ($aproval != null || $aproval != "") {
        $this->db->where("status_absensi", $aproval);
      }
      if ($tgl_mulai != null || $tgl_akhir != null || $tgl_mulai != "" || $tgl_akhir != "" ) {
        $this->db->where('LEFT(absensi.waktu,10) BETWEEN "'.$tgl_mulai.'" AND "'.$tgl_akhir.'"');
      }
      if ($uuid != null || $uuid != "") {
        $this->db->like("pegawai.uuid",$uuid);
      }
      return $this->db->get("absensi");
    }

    function Pulang($idabsensi)
    {
      $this->db->where("absensi_idabsensi", $idabsensi);
      return $this->db->get("absensi_pulang");
    }

    function AbsenCabang($idabsensi)
    {
      $this->db->where("absensi_idabsensi", $idabsensi);
      return $this->db->get("absen_cabang");
    }

    function get_Absensi_Istirahat($pegawai_uuid = null, $tanggal = null)
    {
      $this->db->where("LEFT(waktu,10) = ", date("Y-m-d", strtotime($tanggal)));
      $this->db->where("pegawai_uuid", $pegawai_uuid);
      $this->db->order_by("idabsensi","DESC");
      return $this->db->get("absensi_istirahat");
    }

    function get_Selesai_Istirahat($idabsen)
    {
      $this->db->where("absensi_istirahat_idabsensi", $idabsen);
      return $this->db->get("absensi_selesai_istirahat");
    }

  }
