<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dash extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelAbsensi");
    $this->load->model("ModelPegawai");
    $this->load->model("ModelRiwayat");
    $this->load->model("ModelKegiatan");
    $this->load->model("ModelPerizinan");
    $this->load->model("ModelDash");
    $this->load->model("ModelDinasLuar");
    $this->load->model("ModelJabatan");
    $this->load->model("ModelJadwalMasuk");
    $this->load->model("ModelKampus");
    $this->load->model("ModelTugasBelajar");
    $this->load->model("ModelLembur");
    $this->load->model("ModelLibur");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function get_dash($uuid)
  {
    $peg = $this->ModelPegawai->edit($uuid);
    $tanggal = date("Y-m-d");
    $durasi_absensi = [
      "limit_durasi" => 0,
      "time_durasi" => 0,
      "ket_durasi" => 'Belum melakukan presensi'
    ];
    $data = array();
    if ($peg->num_rows() > 0) {
      $pegawai          = $peg->row_array();
      // if ($pegawai['foto_profil'] != null || $pegawai['foto_profil'] != "" ) {
      $pegawai['foto_profil'] = "desain/Login/logopmi.png";
      // }
      $jabatan          = $this->ModelJabatan->get_data_edit($pegawai["jab_struktur"])->row_array();
      $absen            = $this->ModelAbsensi->get_Absensi($pegawai["idabsen"], $tanggal)->row_array();
      if (@$jabatan['lintas_hari'] == 1) {
        if ($this->ModelAbsensi->get_Absensi($pegawai["idabsen"], date("Y-m-d"))->num_rows() < 1) {
          $tgl_kemarin      = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
          $absen            = $this->ModelAbsensi->get_Absensi($pegawai["idabsen"], $tgl_kemarin)->row_array();
        }
      }
      $absenpulang      = @$this->ModelAbsensi->get_AbsensiPulang($absen["idabsensi"])->row_array();
      $absencabang      = @$this->ModelAbsensi->get_AbsensiCabang($absen["idabsensi"])->row_array();
      $istirahat        = @$this->ModelAbsensi->get_Absensi_Istirahat($pegawai["idistirahat"], date("Y-m-d"))->row_array();
      $selesaiIstirahat = @$this->ModelAbsensi->get_Selesai_Istirahat($istirahat["idabsensi"])->row_array();
      $kegiatan         = @$this->ModelAbsensi->get_kegiatan($uuid)->result();
      $dinasluar        = @$this->ModelDinasLuar->cekDinasLuar($uuid, date("Y-m-d"));
      $tugas_belajar    = @$this->ModelTugasBelajar->get_cekPegawai($uuid);
      $jadwal_masukdata = @$this->ModelJadwalMasuk->get_jadwalmasuk($jabatan['idjabatan']);
      if ($jadwal_masukdata->num_rows() < 1) {
        $jadwal_masukdata = $this->ModelJadwalMasuk->get_jadwalmasuk();
      }
      $jadwal_masukdata = $jadwal_masukdata->row_array();
      $jadwal_masuk["masuk_notif1"] = date('H:i', strtotime('-30 minutes', strtotime($jadwal_masukdata['jam_masuk'])));
      $jadwal_masuk["masuk_notif2"] = date('H:i', strtotime('-10 minutes', strtotime($jadwal_masukdata['jam_masuk'])));
      $jadwal_masuk["masuk_notif3"] = date('H:i', strtotime('0 minutes', strtotime($jadwal_masukdata['jam_masuk'])));
      $jadwal_masuk["pulang_notif"] = date('H:i', strtotime('0 minutes', strtotime($jadwal_masukdata['jam_pulang'])));
      if ($absen) {
        $waktu_absen = strtotime($absen['waktu']);
        $waktu_pulang = strtotime($absenpulang['waktu'] ?? date('H:i:s')); // gunakan waktu sekarang jika null
        $durasi_detik = $waktu_pulang - $waktu_absen;
        $durasi_absensi["limit_durasi"] = strtotime($jadwal_masukdata['jam_pulang']) - strtotime($jadwal_masukdata['jam_masuk']);
        $durasi_absensi["time_durasi"] = $durasi_detik;
        $durasi_absensi["ket_durasi"] = $this->core->formatDurasiLengkap($durasi_detik);
      }
      if ($dinasluar->num_rows() > 0) {
        $dinasluar = $dinasluar->row_array();
        $datadinasluar["status"] = $dinasluar["status_pres_wfo"];
        $datadinasluar["ada_surat"] = "1";
        $datadinasluar["no_surat"] = $dinasluar["no_surat"];
        $datadinasluar["nama_surat"] = $dinasluar["nama_surat"];
        $datadinasluar["tanggal_mulai"] = date("d-m-Y", strtotime($dinasluar["tanggal_mulai"]));
        $datadinasluar["tanggal_selesai"] = date("d-m-Y", strtotime($dinasluar["tanggal_selesai"]));
      } else {
        $datadinasluar["status"] = "1";
        $datadinasluar["ada_surat"] = "0";
      }
      $datatugas_belajar["ada_tugas_belajar"] = "0";
      if ($tugas_belajar->num_rows() > 0) {
        $datadinasluar["status"] = "0";
        $datatugas_belajar["ada_tugas_belajar"] = "1";
        $datatugas_belajar["tugas_belajar"] = $tugas_belajar->row_array();
      }
      $lokasi = $this->ModelKampus->get_edit($pegawai['kampus_idkampus']);
      if ($lokasi->num_rows() < 1) {
        $lokasi = $this->ModelKampus->get_edit("1");
        $cek_kampus = $this->ModelKampus->get_kampus($pegawai['unit']);
        if ($cek_kampus->num_rows() > 0) {
          $lokasi = $cek_kampus;
        }
      }
      $data = array(
        'pegawai'           => $pegawai,
        'lokasi'            => $lokasi->row_array(),
        'dinasluar'         => $datadinasluar,
        'tugas_belajar'     => $datatugas_belajar,
        'absen'             => $absen,
        'absensi_pulang'    => $absenpulang,
        'durasi_absensi'    => $durasi_absensi,
        'absensi_cabang'    => $absencabang,
        'istirahat'         => $istirahat,
        'jabatan'           => $jabatan,
        'selesai_istirahat' => $selesaiIstirahat,
        'jmlPresensiBln'    => $this->ModelDash->getBulanPresensi($uuid, date("Y-m"))->num_rows(),
        'jmlKegiatanBln'    => $this->ModelDash->getBulanKegiatan($uuid, date("Y-m"))->num_rows(),
        'jmlCutiBln'        => $this->ModelDash->getBulanCuti($uuid, date("Y-m"))->num_rows(),
        'kegiatan'          => $kegiatan,
        'jadwal_masuk'      => $jadwal_masuk,
        'version'           => $this->core->VersionAndroidAPP()
      );
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function getKegiatanTerkini()
  {
    $uuid = $this->input->post("uuid");
    $kampus = $this->ModelKegiatan->get_kegiatan_terkini($uuid);
    if ($kampus->num_rows() > 0) {
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
    echo json_encode(array('data' => $kampus->result(), 'message' => $res));
  }

  function infoStats()
  {
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $unit = $this->input->post("unit");
    $wfh = 0;
    $wfo = 0;
    $kegiatan = 0;
    $cuti = 0;
    $toleransi = 0;
    $terlambat = 0;
    $tepat = 0;
    $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoring($unit, null, $tgl_mulai, $tgl_akhir);
    foreach ($absenharian->result() as $value) {
      if ($value->jenis_tempat == 1) {
        $wfo += 1;
      } elseif ($value->jenis_tempat == 2) {
        $wfh += 1;
      }
      $jam_jadwal   = strtotime($value->jam_jadwal);
      $masuk        = strtotime(date("H:i:s", strtotime($value->waktu)));
      $diff         = $masuk - $jam_jadwal;
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
    $cuti = $this->ModelPerizinan->get_riwayatMonitoring($unit, "1", $tgl_mulai, $tgl_akhir)->num_rows();
    $kegiatan = $this->ModelKegiatan->getKegiatanAproval($unit, "1", $tgl_mulai, $tgl_akhir)->num_rows();
    echo json_encode(array(
      'wfo' => $wfo,
      'wfh' => $wfh,
      'tepat' => $tepat,
      'toleransi' => $toleransi,
      'terlambat' => $terlambat,
      'kegiatan' => $kegiatan,
      'cuti' => $cuti,
    ));
  }

  function get_monitoring($uuid)
  {
    $peg = $this->ModelPegawai->get_kepalaunit($uuid);
    $data = array();
    if ($peg->num_rows() > 0) {
      $peg         = $peg->row_array();
      $pegawai          = $peg;
      $jmlApPresensi    = $this->ModelRiwayat->RiwayatHarianMonitoring($peg["nama_unit"], "0")->num_rows();
      $jmlApLembur      = $this->ModelLembur->getKegiatanAproval($peg["nama_unit"], "0")->num_rows();
      $jmlApKegiatan    = $this->ModelKegiatan->getKegiatanAproval($peg["nama_unit"], "0")->num_rows();
      $jmlApCuti        = $this->ModelPerizinan->get_riwayatMonitoring($peg["nama_unit"], "0")->num_rows();
      $data = array(
        'pegawai' => $pegawai,
        'jmlApPresensi'   => $jmlApPresensi,
        'jmlApLembur'   => $jmlApLembur,
        'jmlApKegiatan' => $jmlApKegiatan,
        'jmlApCuti' => $jmlApCuti,
        'UnitMonitoring' => $peg["nama_unit"],
        'version'   => $this->core->VersionMonitoringAPP()
      );
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function getStatus($uuid)
  {
    $toleransi = 0;
    $terlambat = 0;
    $tepat = 0;
    $tgl_mulai = date("Y-m-") . "01";
    $tgl_akhir = date("Y-m-d");
    $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoringPegawai($uuid, null, $tgl_mulai, $tgl_akhir);
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

  function set_jadwal($uuid)
  {
    $peg = $this->ModelPegawai->edit($uuid);
    $tanggal = date("Y-m-d");
    $data = array();
    if ($peg->num_rows() > 0) {
      $pegawai          = $peg->row_array();
      $absen            = $this->ModelAbsensi->get_Absensi($pegawai["idabsen"], $tanggal)->row_array();
      $jadwalmasuk      = $this->ModelJadwalMasuk->get_jadwalmasuk($pegawai["jab_struktur"]);
      if ($jadwalmasuk->num_rows() > 0) {
        $jabatan          = $this->ModelJabatan->get_data_edit($pegawai["jab_struktur"])->row_array();
        $jadwalmasuk      = $this->ModelJadwalMasuk->get_jadwalmasuk($pegawai["jab_struktur"])->result();
        if ($jabatan['lintas_hari'] == 1) {
          if ($this->ModelAbsensi->get_Absensi($pegawai["idabsen"], date("Y-m-d"))->num_rows() < 1) {
            $tgl_kemarin      = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
            $absen            = $this->ModelAbsensi->get_Absensi($pegawai["idabsen"], $tgl_kemarin)->row_array();
          }
        }
      } else {
        $jabatan          = $this->ModelJabatan->get_data_edit("pegawai")->row_array();
        $jadwalmasuk      = $this->ModelJadwalMasuk->get_jadwalmasuk("pegawai")->result();
      }
      $absenpulang      = @$this->ModelAbsensi->get_AbsensiPulang($absen["idabsensi"])->row_array();
      $istirahat        = @$this->ModelAbsensi->get_Absensi_Istirahat($pegawai["idistirahat"], date("Y-m-d"))->row_array();
      $selesaiIstirahat = @$this->ModelAbsensi->get_Selesai_Istirahat($istirahat["idabsensi"])->row_array();

      $data = array(
        'pegawai'   => $pegawai,
        'jadwal'    => $jadwalmasuk,
        'absen'     => $absen,
        'absensi_pulang' => $absenpulang,
        'istirahat' => $istirahat,
        // 'jabatan'   => $jabatan,
        'selesai_istirahat' => $selesaiIstirahat,
      );
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function set_jadwal_WFH($uuid)
  {
    $peg = $this->ModelPegawai->edit($uuid);
    $tanggal = date("Y-m-d");
    $data = array();
    if ($peg->num_rows() > 0) {
      $pegawai          = $peg->row_array();
      $absen            = $this->ModelAbsensi->get_Absensi($pegawai["idabsen"], $tanggal)->row_array();
      $jadwalmasuk      = $this->ModelJadwalMasuk->get_jadwalmasuk($pegawai["jab_struktur"], "2");
      if ($jadwalmasuk->num_rows() > 0) {
        $jabatan          = $this->ModelJabatan->get_data_edit($pegawai["jab_struktur"])->row_array();
        $jadwalmasuk      = $this->ModelJadwalMasuk->get_jadwalmasuk($pegawai["jab_struktur"], "2")->result();
        if ($jabatan['lintas_hari'] == 1) {
          if ($this->ModelAbsensi->get_Absensi($pegawai["idabsen"], date("Y-m-d"))->num_rows() < 1) {
            $tgl_kemarin      = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
            $absen            = $this->ModelAbsensi->get_Absensi($pegawai["idabsen"], $tgl_kemarin)->row_array();
          }
        }
      } else {
        $jabatan          = $this->ModelJabatan->get_data_edit("pegawai")->row_array();
        $jadwalmasuk      = $this->ModelJadwalMasuk->get_jadwalmasuk("pegawai", "2")->result();
      }
      $absenpulang      = @$this->ModelAbsensi->get_AbsensiPulang($absen["idabsensi"])->row_array();
      $istirahat        = @$this->ModelAbsensi->get_Absensi_Istirahat($pegawai["idistirahat"], date("Y-m-d"))->row_array();
      $selesaiIstirahat = @$this->ModelAbsensi->get_Selesai_Istirahat($istirahat["idabsensi"])->row_array();

      $data = array(
        'pegawai'   => $pegawai,
        'jadwal'    => $jadwalmasuk,
        'absen'     => $absen,
        'absensi_pulang' => $absenpulang,
        'istirahat' => $istirahat,
        // 'jabatan'   => $jabatan,
        'selesai_istirahat' => $selesaiIstirahat,
      );
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function getKalender()
  {
    $uuid = $this->input->post("uuid");
    $bulan = $this->input->post("bulan"); // format: Y-m (contoh: 2025-03)
    $tahun = $this->input->post("tahun"); // format: Y (contoh: 2025)

    // Validasi input
    if (empty($uuid)) {
      echo json_encode(array(
        'data' => array(),
        'message' => array(
          'message' => "UUID pegawai tidak boleh kosong",
          'status' => 400
        )
      ));
      return;
    }

    // Set default bulan dan tahun jika tidak ada
    if (empty($bulan)) {
      $bulan = date("Y-m");
    }
    if (empty($tahun)) {
      $tahun = date("Y");
    }

    // Validasi pegawai
    $peg = $this->ModelPegawai->edit($uuid);
    if ($peg->num_rows() < 1) {
      echo json_encode(array(
        'data' => array(),
        'message' => array(
          'message' => "Pegawai tidak ditemukan",
          'status' => 404
        )
      ));
      return;
    }

    $pegawai = $peg->row_array();

    // Hitung tanggal awal dan akhir bulan
    $tgl_mulai = $tahun . "-" . $bulan . "-01";
    $tgl_akhir = date("Y-m-t", strtotime($tgl_mulai));

    // Ambil data presensi
    $presensi = $this->ModelRiwayat->RiwayatHarian($uuid, null, $tgl_mulai, $tgl_akhir);
    $data_presensi = array();
    foreach ($presensi->result() as $row) {
      $tanggal = date("Y-m-d", strtotime($row->waktu));
      $pulang = $this->ModelRiwayat->Pulang($row->idabsensi)->row();

      $data_presensi[$tanggal] = array(
        'idabsensi' => $row->idabsensi,
        'tanggal' => $tanggal,
        'waktu_masuk' => date("H:i:s", strtotime($row->waktu)),
        'waktu_pulang' => $pulang ? date("H:i:s", strtotime($pulang->waktu)) : null,
        'jenis_tempat' => $row->jenis_tempat, // 1=WFO, 2=WFH
        'status_absensi' => $row->status_absensi,
        'keterangan' => $row->keterangan ?? null
      );
    }

    // Ambil data cuti/izin
    $izin = $this->ModelPerizinan->get_riwayat($uuid, null, $tgl_mulai, $tgl_akhir);
    $data_izin = array();
    foreach ($izin->result() as $row) {
      // Hitung semua tanggal dalam range izin
      $start = strtotime($row->tanggal_mulai);
      $end = strtotime($row->tanggal_akhir);

      for ($i = $start; $i <= $end; $i += 86400) {
        $tanggal = date("Y-m-d", $i);
        // Hanya masukkan jika dalam bulan yang diminta
        if (substr($tanggal, 0, 7) == $bulan) {
          if (!isset($data_izin[$tanggal])) {
            $data_izin[$tanggal] = array();
          }
          $data_izin[$tanggal][] = array(
            'idizin' => $row->idizin,
            'jenis_izin' => $row->jenis_izin,
            'tanggal_mulai' => $row->tanggal_mulai,
            'tanggal_akhir' => $row->tanggal_akhir,
            'keterangan' => $row->keterangan,
            'status' => $row->status
          );
        }
      }
    }

    // Ambil data hari libur
    $libur = $this->ModelLibur->getLibur($tahun);
    $data_libur = array();
    foreach ($libur->result() as $row) {
      $tanggal = $row->tanggal;
      // Hanya masukkan jika dalam bulan yang diminta
      if (substr($tanggal, 0, 7) == $bulan) {
        $data_libur[$tanggal] = array(
          'idlibur' => $row->idlibur,
          'tanggal' => $tanggal,
          'keterangan' => $row->keterangan
        );
      }
    }

    // Gabungkan semua data per tanggal
    $kalender = array();
    $start_date = strtotime($tgl_mulai);
    $end_date = strtotime($tgl_akhir);

    for ($i = $start_date; $i <= $end_date; $i += 86400) {
      $tanggal = date("Y-m-d", $i);
      $hari = date("N", $i); // 1=Senin, 7=Minggu

      $kalender[] = array(
        'tanggal' => $tanggal,
        'hari' => $this->getNamaHari($hari),
        'hari_numeric' => $hari,
        'is_weekend' => ($hari == 6 || $hari == 7), // Sabtu atau Minggu
        'presensi' => isset($data_presensi[$tanggal]) ? $data_presensi[$tanggal] : null,
        'izin' => isset($data_izin[$tanggal]) ? $data_izin[$tanggal] : array(),
        'libur' => isset($data_libur[$tanggal]) ? $data_libur[$tanggal] : null
      );
    }

    $data = array(
      'pegawai' => array(
        'uuid' => $pegawai['uuid'],
        'nama' => $pegawai['nama_pegawai'],
        'nip' => $pegawai['NIP']
      ),
      'periode' => array(
        'bulan' => $bulan,
        'tahun' => $tahun,
        'tanggal_mulai' => $tgl_mulai,
        'tanggal_akhir' => $tgl_akhir
      ),
      'kalender' => $kalender,
      'summary' => array(
        'total_hari' => count($kalender),
        'total_presensi' => count($data_presensi),
        'total_izin' => $izin->num_rows(),
        'total_libur' => count($data_libur)
      )
    );

    $res = array(
      'message' => "Success",
      'status' => 200
    );

    echo json_encode(array('data' => $data, 'message' => $res));
  }

  private function getNamaHari($hari_numeric)
  {
    $nama_hari = array(
      1 => 'Senin',
      2 => 'Selasa',
      3 => 'Rabu',
      4 => 'Kamis',
      5 => 'Jumat',
      6 => 'Sabtu',
      7 => 'Minggu'
    );
    return $nama_hari[$hari_numeric];
  }
}
