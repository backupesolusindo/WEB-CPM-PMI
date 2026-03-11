<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('ModelLaporan');
    $this->load->model('ModelRiwayat');
    $this->load->model('ModelUnit');
    $this->load->model('ModelPerizinan');
    $this->load->model('ModelKegiatan');
  }

  function index()
  {
    if ($_SESSION['jabatan'] == "admin_unit") {
      $array = array(
        'title' => "Dashboard",
        'body'  => "Dashboard/AdminUnit",
        'unit'  => $this->ModelUnit->get_parent_unit()->result(),
      );
    }else {
      $array = array(
        'title' => "Dashboard",
        'body'  => "Dashboard/Dash",
        'unit'  => $this->ModelUnit->get_parent_unit()->result(),
      );
    }
    $start = date('Y-m-d');
    $end = date('Y-m-d');
    $this->load->view('index', $array);

    // --------- Otomatis Aproval
    $tgl1 = date("Y-m-d");// pendefinisian tanggal awal
    $tgl_mulai = date('Y-m-d', strtotime('-7 days', strtotime($tgl1)));
    // echo $tgl_mulai;
    $this->db->where('LEFT(absensi.waktu,10) < "'.$tgl_mulai.'"');
    $this->db->where("status_absensi","0");
    $presensi = $this->db->get("absensi")->num_rows();
    if ($presensi > 0) {
      $this->db->where('LEFT(absensi.waktu,10) < "'.$tgl_mulai.'"');
      $this->db->where("status_absensi","0");
      $this->db->update("absensi",array('status_absensi' => "1"));
    }
  }

  function data_kegiatan()
  {
    $unit = $this->input->post("unit");
    $subunit = $this->input->post("sub_unit");
    if ($subunit == "" || $subunit == null) {
        $unit = $this->input->post("unit");
    }else {
        $unit = $subunit;
    }
    $data = array(
      'kegiatan'          => $this->ModelKegiatan->get_kegiatan_unit_terlaksana($unit)->result()
    );
    $this->load->view('Dashboard/data_kegiatan', $data);
  }

  function text()
  {
    $arrayName = array('User','GroupRole','Roles');
    echo json_encode($arrayName);
  }

  function infoBulanan()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit = $this->input->post("unit");
    $sub_unit = $this->input->post("sub_unit");
    $wfh=0; $wfo=0; $kegiatan=0; $cuti=0;
    $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoring($unit, null, $tgl_mulai, $tgl_akhir, $sub_unit);
    foreach ($absenharian->result() as $value) {
      if ($value->jenis_tempat == 1) {
        $wfo += 1;
      }elseif ($value->jenis_tempat == 2) {
        $wfh += 1;
      }
    }
    $cuti = $this->ModelPerizinan->get_riwayatMonitoring($unit, "1", $tgl_mulai, $tgl_akhir, $sub_unit)->num_rows();
    $kegiatan = $this->ModelKegiatan->getKegiatanAproval($unit, "1", $tgl_mulai, $tgl_akhir, $sub_unit)->num_rows();
    echo json_encode(array(
      'wfo' => $wfo,
      'wfh' => $wfh,
      'kegiatan' => $kegiatan,
      'cuti' => $cuti,
     ));
  }

  function chartbulanan()
  {
    $toleransi = 0;
    $terlambat = 0;
    $tepat = 0;
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit = $this->input->post("unit");
    $sub_unit = $this->input->post("sub_unit");

    $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoring($unit, null, $tgl_mulai, $tgl_akhir, $sub_unit);
    foreach ($absenharian->result() as $value) {
      $jam_jadwal  = strtotime($value->jam_jadwal);
      $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
      $diff  = $masuk - $jam_jadwal;
      if ($diff <= 0) {
        $tepat += 1;
      }else {
        $wtoleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
        if ($diff <= $wtoleransi) {
          $toleransi += 1;
        }else {
          $terlambat += 1;
        }
      }
    }
    $data = array(
      'tepat' => $tepat,
      'toleransi' => $toleransi,
      'terlambat' => $terlambat,
    );
    echo json_encode($data);
  }

  function grafik()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit = $this->input->post("unit");
    $sub_unit = $this->input->post("sub_unit");

    $data = array();
    $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoringGrafik($unit, null, $tgl_mulai, $tgl_akhir, $sub_unit);
    foreach ($absenharian->result() as $value) {
      $toleransi = 0;
      $terlambat = 0;
      $tepat = 0;
      $tgl = date("Y-m-d", strtotime($value->waktu));
      $absenharianS = $this->ModelRiwayat->RiwayatHarianMonitoring(null, null, $tgl, $tgl);
      foreach ($absenharianS->result() as $value) {
        $jam_jadwal  = strtotime($value->jam_jadwal);
        $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
        $diff  = $masuk - $jam_jadwal;
        if ($diff <= 0) {
          $tepat += 1;
        }else {
          $wtoleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
          if ($diff <= $wtoleransi) {
            $toleransi += 1;
          }else {
            $terlambat += 1;
          }
        }
      }
      $ar = array(
        'tanggal' => date("d-m-Y", strtotime($value->waktu)),
        'tepat' => $tepat,
        'toleransi' => $toleransi,
        'terlambat' => $terlambat,
      );
      array_push($data,$ar);
    }
    echo json_encode($data);
  }

  public function coba()
  {
    $this->load->view('index3');
  }

}
