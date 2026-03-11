<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JadwalWF extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelJadwalWF");
    $this->load->model("ModelLibur");
    $this->load->model("ModelPegawai");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function getTanggal()
  {
    $arTahun = array();
    $arBulan = array();
    for ($i = date("Y"); $i >= 2021; $i--) {
      array_push($arTahun, array("tahun" => $i));
    }
    array_push($arBulan, array("kode" => "01", "bulan" => "Januari"));
    array_push($arBulan, array("kode" => "02", "bulan" => "Februari"));
    array_push($arBulan, array("kode" => "03", "bulan" => "Maret"));
    array_push($arBulan, array("kode" => "04", "bulan" => "April"));
    array_push($arBulan, array("kode" => "05", "bulan" => "Mei"));
    array_push($arBulan, array("kode" => "06", "bulan" => "Juni"));
    array_push($arBulan, array("kode" => "07", "bulan" => "Juli"));
    array_push($arBulan, array("kode" => "08", "bulan" => "Agustus"));
    array_push($arBulan, array("kode" => "09", "bulan" => "September"));
    array_push($arBulan, array("kode" => "10", "bulan" => "Oktober"));
    array_push($arBulan, array("kode" => "11", "bulan" => "November"));
    array_push($arBulan, array("kode" => "12", "bulan" => "Desember"));

    echo json_encode(array(
      "tahun" => $arTahun,
      "bulan" => $arBulan,
    ));
  }

  function getJadwal()
  {
    $uuid = $this->input->post("uuid");
    $tahun = $this->input->post("tahun");
    $bulan = $this->input->post("bulan");
    $status_approval = 0;
    $kalendar = CAL_GREGORIAN;
    $data     = array();
    $jhari  = cal_days_in_month($kalendar, $bulan, $tahun);
    $sisa = 0;
    $status_minggu = 1;
    for ($i = 1; $i <= $jhari; $i++) {
      $tanggal = $i . "-" . $bulan . "-" . $tahun;
      $hari = date("D", strtotime($tanggal));
      if ($hari == "Sun" && $status_minggu == 1) {
        $sisa = 7 - $i;
        $status_minggu = 0;
      }
    }
    for ($i = 0; $i <= $sisa; $i++) {
      $ar = array(
        'jenis_kerja'   => "",
        'kode_jenis'    => 99,
        'tanggal' => "",
        'hari'    => "",
        'bulan'   => "",
        'tahun'   => "",
        'tglF'    => "",
      );
      array_push($data, $ar);
    }
    $status_approval = 0;
    for ($i = 1; $i <= $jhari; $i++) {
      $tanggal = $i . "-" . $bulan . "-" . $tahun;
      $hari = date("D", strtotime($tanggal));
      $jk = "-";
      $kj = 0;
      $cek = $this->ModelJadwalWF->getCheck($uuid, $tanggal);
      if ($cek->num_rows() > 0) {
        if ($cek->row_array()["wf"] == 1) {
          $jk = "WFO";
          $status_approval = $cek->row_array()["stats_approval"];
        } else {
          $jk = "WFH";
          $status_approval = $cek->row_array()["stats_approval"];
        }
        $kj = $cek->row_array()["wf"];
      }
      $libur = $this->ModelLibur->getDataLibur($tanggal);
      if ($libur->num_rows() > 0 || $hari == "Sun") {
        // $jk = $libur->row_array()["keterangan"];
        $kj = "99";
      }
      $ar = array(
        'jenis_kerja'   => $jk,
        'kode_jenis'    => $kj,
        'tanggal' => $i,
        'hari'    => $hari,
        'bulan'   => $bulan,
        'tahun'   => $tahun,
        'tglF'    => $tanggal,
        'status_aproval'    => $status_approval,
      );
      array_push($data, $ar);
    }
    if ($status_approval == null || $status_approval == "") {
      $status_approval = 0;
    }
    echo json_encode(array("status_approval" => "" . $status_approval . "", "data" => $data));
  }

  function coba()
  {
    $cek = $this->ModelJadwalWF->getCheck("1f8e79b5-00eb-11eb-ab7b-fefcfe8d8c7c", "4-10-2021")->row_array();
    echo json_encode($cek);
  }

  function insert_JadwalWF()
  {
    $tanggal = date("Y-m-d", strtotime($this->input->post("tanggal")));
    $blt = date("Y-m", strtotime($this->input->post("tanggal")));
    $uuid = $this->input->post("uuid");
    $kode_jenis = $this->input->post("kode_jenis");

    $res = array();
    $data = array(
      'pegawai_uuid'  => $uuid,
      'tanggal'       => $tanggal,
      'wf'            => $kode_jenis,
    );
    $cek = $this->ModelJadwalWF->getCheck($uuid, $tanggal);
    if ($cek->num_rows() > 0) {
      $this->db->where("idjadwal_wf", $cek->row_array()["idjadwal_wf"]);
      if ($this->db->update("jadwal_wf", $data)) {
        $this->db->where("pegawai_uuid", $uuid);
        $this->db->where("LEFT(tanggal, 7) =", $blt);
        $this->db->update("jadwal_wf", array('stats_approval' => 0));
        $res = array(
          'message' => "Success",
          'status' => 200
        );
      } else {
        $res = array(
          'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
          'status' => 500
        );
      }
    } else {
      if ($this->db->insert("jadwal_wf", $data)) {
        $res = array(
          'message' => "Success",
          'status' => 200
        );
      } else {
        $res = array(
          'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
          'status' => 500
        );
      }
    }

    echo json_encode(array('message' => $res));
  }

  function approval_jadwal()
  {
    $uuid = $this->input->post("uuid");
    $status = $this->input->post("status");
    $tahun = $this->input->post("tahun");
    $bulan = $this->input->post("bulan");

    $this->db->where("pegawai_uuid", $uuid);
    $this->db->where("LEFT(tanggal, 7) =", date("Y-m", strtotime($tahun . "-" . $bulan)));
    if ($this->db->update("jadwal_wf", array('stats_approval' => $status))) {
      $da = $this->ModelPegawai->edit($uuid)->row_array();
      if ($status == 1) {
        $this->core->curlNotif($da["token"], "Persetujuan Jadwal Kerja", "Jadwal Kerja Anda Approved");
      } else {
        $this->core->curlNotif($da["token"], "Persetujuan Jadwal Kerja", "Jadwal Kerja Anda Ditolak Harap Dicek Kembali");
      }
      $res = array(
        'message' => "Success",
        'status' => 200
      );
    } else {
      $res = array(
        'message' => "Submit Gagal, Mohon Cek Koneksi Anda",
        'status' => 500
      );
    }
    echo json_encode(array('message' => $res));
  }
}
