<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model('ModelLaporan');
    $this->load->model('ModelRiwayat');
    $this->load->model('ModelUnit');
    $this->load->model('ModelPerizinan');
    $this->load->model('ModelKegiatan');
    $this->load->model('ModelLibur');
  }

  function index()
  {
    if ($_SESSION['jabatan'] == "admin_unit") {
      $array = array(
        'title' => "Dashboard",
        'body'  => "Dashboard/AdminUnit",
        'unit'  => $this->ModelUnit->get_parent_unit()->result(),
      );
    } else {
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
    $tgl1 = date("Y-m-d"); // pendefinisian tanggal awal
    $tgl_mulai = date('Y-m-d', strtotime('-7 days', strtotime($tgl1)));
    // echo $tgl_mulai;
    $this->db->where('LEFT(absensi.waktu,10) < "' . $tgl_mulai . '"');
    $this->db->where("status_absensi", "0");
    $presensi = $this->db->get("absensi")->num_rows();
    if ($presensi > 0) {
      $this->db->where('LEFT(absensi.waktu,10) < "' . $tgl_mulai . '"');
      $this->db->where("status_absensi", "0");
      $this->db->update("absensi", array('status_absensi' => "1"));
    }
  }

  function data_kegiatan()
  {
    $unit = $this->input->post("unit");
    $subunit = $this->input->post("sub_unit");
    if ($subunit == "" || $subunit == null) {
      $unit = $this->input->post("unit");
    } else {
      $unit = $subunit;
    }
    $data = array(
      'kegiatan'          => $this->ModelKegiatan->get_kegiatan_unit_terlaksana($unit)->result()
    );
    $this->load->view('Dashboard/data_kegiatan', $data);
  }

  function text()
  {
    $arrayName = array('User', 'GroupRole', 'Roles');
    echo json_encode($arrayName);
  }

  function infoBulanan()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("start")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("end")));
    $unit = $this->input->post("unit");
    $sub_unit = $this->input->post("sub_unit");
    $wfh = 0;
    $wfo = 0;
    $kegiatan = 0;
    $cuti = 0;
    $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoring($unit, null, $tgl_mulai, $tgl_akhir, $sub_unit);
    foreach ($absenharian->result() as $value) {
      if ($value->jenis_tempat == 1) {
        $wfo += 1;
      } elseif ($value->jenis_tempat == 2) {
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
      } else {
        $wtoleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
        if ($diff <= $wtoleransi) {
          $toleransi += 1;
        } else {
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
        } else {
          $wtoleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
          if ($diff <= $wtoleransi) {
            $toleransi += 1;
          } else {
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
      array_push($data, $ar);
    }
    echo json_encode($data);
  }

  public function coba()
  {
    $this->load->view('index3');
  }

  function kalender()
  {
    $array = array(
      'title' => "Dashboard Kalender",
      'body'  => "Dashboard/Kalender",
      'unit'  => $this->ModelUnit->get_parent_unit()->result(),
    );
    $this->load->view('index', $array);
  }

  function data_kalender()
  {
    $bulan = $this->input->post("bulan");
    $tahun = $this->input->post("tahun");
    $unit = $this->input->post("unit");
    $sub_unit = $this->input->post("sub_unit");

    if ($bulan == "" || $bulan == null) {
      $bulan = date("m");
    }
    if ($tahun == "" || $tahun == null) {
      $tahun = date("Y");
    }

    $tgl_mulai = $tahun . "-" . $bulan . "-01";
    $tgl_akhir = date("Y-m-t", strtotime($tgl_mulai));

    $events = array();

    // Data Libur (ditampilkan pertama dengan warna merah)
    $libur = $this->ModelLibur->getLibur($tahun);
    foreach ($libur->result() as $value) {
      $tgl = date("Y-m-d", strtotime($value->tanggal));
      // Cek apakah tanggal libur dalam range bulan yang dipilih
      if ($tgl >= $tgl_mulai && $tgl <= $tgl_akhir) {
        $events[] = array(
          'title' => $value->keterangan,
          'start' => $tgl,
          'color' => '#e74c3c',
          'type' => 'libur',
          'display' => 'background',
          'allDay' => true
        );
      }
    }

    // Data Presensi
    $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoring($unit, null, $tgl_mulai, $tgl_akhir, $sub_unit);
    $presensi_count = array();
    foreach ($absenharian->result() as $value) {
      $tgl = date("Y-m-d", strtotime($value->waktu));
      if (!isset($presensi_count[$tgl])) {
        $presensi_count[$tgl] = 0;
      }
      $presensi_count[$tgl]++;
    }

    foreach ($presensi_count as $tgl => $count) {
      $events[] = array(
        'title' => $count . ' Presensi',
        'start' => $tgl,
        'color' => '#02c292',
        'type' => 'presensi'
      );
    }

    // Data Cuti
    $cuti = $this->ModelPerizinan->get_riwayatMonitoring($unit, "1", $tgl_mulai, $tgl_akhir, $sub_unit);
    $cuti_count = array();
    foreach ($cuti->result() as $value) {
      $tgl = date("Y-m-d", strtotime($value->tanggal_mulai));
      if (!isset($cuti_count[$tgl])) {
        $cuti_count[$tgl] = 0;
      }
      $cuti_count[$tgl]++;
    }

    foreach ($cuti_count as $tgl => $count) {
      $events[] = array(
        'title' => $count . ' Cuti',
        'start' => $tgl,
        'color' => '#fec107',
        'type' => 'cuti'
      );
    }

    // Data Kegiatan
    $kegiatan = $this->ModelKegiatan->getKegiatanAproval($unit, "1", $tgl_mulai, $tgl_akhir, $sub_unit);
    $kegiatan_count = array();
    foreach ($kegiatan->result() as $value) {
      $tgl = date("Y-m-d", strtotime($value->tanggal_mulai));
      if (!isset($kegiatan_count[$tgl])) {
        $kegiatan_count[$tgl] = 0;
      }
      $kegiatan_count[$tgl]++;
    }

    foreach ($kegiatan_count as $tgl => $count) {
      $events[] = array(
        'title' => $count . ' Kegiatan',
        'start' => $tgl,
        'color' => '#4099ff',
        'type' => 'kegiatan'
      );
    }

    echo json_encode($events);
  }

  function detail_kalender()
  {
    $tanggal = $this->input->post("tanggal");
    $unit = $this->input->post("unit");
    $sub_unit = $this->input->post("sub_unit");

    $data = array(
      'presensi' => array(),
      'cuti' => array(),
      'kegiatan' => array(),
      'libur' => null
    );

    // Cek apakah tanggal adalah hari libur
    $cek_libur = $this->ModelLibur->getDataLibur($tanggal);
    if ($cek_libur->num_rows() > 0) {
      $libur_data = $cek_libur->row();
      $data['libur'] = array(
        'keterangan' => $libur_data->keterangan,
        'tanggal' => date("d-m-Y", strtotime($libur_data->tanggal))
      );
    }

    // Data Presensi
    $presensi = $this->ModelRiwayat->RiwayatHarianMonitoring($unit, null, $tanggal, $tanggal, $sub_unit);
    foreach ($presensi->result() as $value) {
      $jam_jadwal  = strtotime($value->jam_jadwal);
      $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
      $diff  = $masuk - $jam_jadwal;

      $status = '';
      if ($diff <= 0) {
        $status = 'Tepat Waktu';
        $badge = 'success';
      } else {
        $wtoleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
        if ($diff <= $wtoleransi) {
          $status = 'Toleransi';
          $badge = 'warning';
        } else {
          $status = 'Terlambat';
          $badge = 'danger';
        }
      }

      $data['presensi'][] = array(
        'nama' => $value->nama_pegawai,
        'nip' => $value->NIP ?? $value->NIK ?? "-",
        'unit' => $value->unit,
        'waktu' => date("H:i:s", strtotime($value->waktu)),
        'jam_jadwal' => $value->jam_jadwal,
        'status' => $status,
        'badge' => $badge,
        'lokasi' => $value->jenis_tempat == 1 ? 'WFO' : 'WFH'
      );
    }

    // Data Cuti
    $cuti = $this->ModelPerizinan->get_riwayatMonitoring($unit, "1", $tanggal, $tanggal, $sub_unit);
    foreach ($cuti->result() as $value) {
      $data['cuti'][] = array(
        'nama' => $value->nama,
        'nip' => $value->nip,
        'unit' => $value->unit,
        'jenis' => $value->jenis_perizinan,
        'tanggal_mulai' => date("d-m-Y", strtotime($value->tanggal_mulai)),
        'tanggal_selesai' => date("d-m-Y", strtotime($value->tanggal_selesai)),
        'keterangan' => $value->keterangan
      );
    }

    // Data Kegiatan
    $kegiatan = $this->ModelKegiatan->getKegiatanAproval($unit, "1", $tanggal, $tanggal, $sub_unit);
    foreach ($kegiatan->result() as $value) {
      $data['kegiatan'][] = array(
        'nama' => $value->nama,
        'nip' => $value->nip,
        'unit' => $value->unit,
        'nama_kegiatan' => $value->nama_kegiatan,
        'tanggal_mulai' => date("d-m-Y", strtotime($value->tanggal_mulai)),
        'tanggal_selesai' => date("d-m-Y", strtotime($value->tanggal_selesai)),
        'lokasi' => $value->lokasi
      );
    }

    echo json_encode($data);
  }
}
