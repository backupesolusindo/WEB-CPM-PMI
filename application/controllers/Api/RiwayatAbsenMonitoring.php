<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatAbsenMonitoring extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelRiwayat");
    $this->load->model("ModelKegiatan");
    $this->load->model("ModelPerizinan");
    $this->load->model("ModelAbsensi");
    $this->load->model("ModelPegawai");
    $this->load->model("ModelLembur");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function riwayat_harian()
  {
    $status = $this->input->post("status");
    @$status_waktu = $this->input->post("status_waktu");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    $data = array();
    $data_presensi["jml_tepatwaktu"] = 0;
    $data_presensi["jml_toleransi"] = 0;
    $data_presensi["jml_terlambat"] = 0;
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoring($unit, $status, $tgl_mulai, $tgl_akhir);

      if ($absenharian->num_rows() > 0) {
        foreach ($absenharian->result() as $value) {
          $absenpulang = $this->ModelRiwayat->Pulang($value->idabsensi)->row_array();
          $istirahat        = $this->ModelRiwayat->get_Absensi_Istirahat($value->pegawai_uuid, date("Y-m-d", strtotime($value->waktu)));
          $jam_istirahat = "Belum Presensi";
          if ($istirahat->num_rows() > 0) {
            $istirahat = $istirahat->row_array();
            $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) . " - Selesai Istirahat Belum Presensi";
            $selesaiIstirahat = $this->ModelRiwayat->get_Selesai_Istirahat($istirahat["idabsensi"]);
            if ($selesaiIstirahat->num_rows() > 0) {
              $selesaiIstirahat = $selesaiIstirahat->row_array();
              $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) . " - " . date("H:i:s", strtotime($selesaiIstirahat['waktu']));
            }
          }
          $status_absensi = "Belum Di Setujui";
          if ($value->status_absensi == 1) {
            $status_absensi = "Sudah Di Setujui";
          }
          $status_tepat = "1";
          $k_tepat = "Tepat Waktu";
          $jam_jadwal  = strtotime($value->jam_jadwal);
          $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
          $diff  = $masuk - $jam_jadwal;
          if ($diff <= 0) {
            $status_tepat = "1";
            $k_tepat = "Tepat Waktu";
            $data_presensi["jml_tepatwaktu"] += 1;
          } else {
            $toleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
            if ($diff <= $toleransi) {
              $status_tepat = "2";
              $k_tepat = "Toleransi";
              $data_presensi["jml_toleransi"] += 1;
            } else {
              $status_tepat = "3";
              $k_tepat = "Terlambat";
              $data_presensi["jml_terlambat"] += 1;
            }
          }
          $ar = array(
            'idabsensi'       => $value->idabsensi,
            'nama_pegawai'    => $value->nama_pegawai,
            'NIP'             => $value->NIP,
            'waktu'           => $value->waktu,
            'status_absensi'  => $value->status_absensi,
            'latitude'        => $value->latitude,
            'longitude'       => $value->longitude,
            'jenis_tempat'    => $value->jenis_tempat,
            'foto'            => $value->foto,
            'waktu_pulang'    => @$absenpulang['waktu'],
            'foto_pulang'     => @$absenpulang['foto'],
            'waktu_istirahat' => $jam_istirahat,
            'status_tepat'    => $status_tepat,
            'k_tepat'         => $k_tepat,
          );
          if ($status_waktu != null || $status_waktu != "") {
            if ($status_waktu == $status_tepat) {
              array_push($data, $ar);
            }
          } else {
            array_push($data, $ar);
          }
        }
        $res = array(
          'message'       => "Success",
          'status'        => 200
        );
      } else {
        $res = array(
          'message'       => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
          'status'        => 500
        );
      }
    }

    echo json_encode(array('data' => $data, 'message' => $res, 'data_presensi' => $data_presensi));
  }

  function riwayat_harian_lokasi()
  {
    $data = array();
    $data_presensi["jml_tepatwaktu"] = 0;
    $data_presensi["jml_toleransi"] = 0;
    $data_presensi["jml_terlambat"] = 0;
    $lokasi = $this->input->post("lokasi");
    @$status_waktu = $this->input->post("status_waktu");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));

    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $absenharian = $this->ModelRiwayat->RiwayatHarianLokasiMonitoring($unit, $lokasi, $tgl_mulai, $tgl_akhir);

      if ($absenharian->num_rows() > 0) {
        foreach ($absenharian->result() as $value) {
          $absenpulang = $this->ModelRiwayat->Pulang($value->idabsensi)->row_array();
          $istirahat        = $this->ModelRiwayat->get_Absensi_Istirahat(date("Y-m-d", strtotime($value->waktu)));
          $jam_istirahat = "Belum Presensi";
          if ($istirahat->num_rows() > 0) {
            $istirahat = $istirahat->row_array();
            $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) . " - Selesai Istirahat Belum Presensi";
            $selesaiIstirahat = $this->ModelRiwayat->get_Selesai_Istirahat($istirahat["idabsensi"]);
            if ($selesaiIstirahat->num_rows() > 0) {
              $selesaiIstirahat = $selesaiIstirahat->row_array();
              $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) . " - " . date("H:i:s", strtotime($selesaiIstirahat['waktu']));
            }
          }
          $status_absensi = "Belum Di Setujui";
          if ($value->status_absensi == 1) {
            $status_absensi = "Sudah Di Setujui";
          }
          $status_tepat = "1";
          $k_tepat = "Tepat Waktu";
          $jam_jadwal  = strtotime($value->jam_jadwal);
          $masuk       = strtotime(date("H:i:s", strtotime($value->waktu)));
          $diff  = $masuk - $jam_jadwal;
          if ($diff <= 0) {
            $status_tepat = "1";
            $k_tepat = "Tepat Waktu";
            $data_presensi["jml_tepatwaktu"] += 1;
          } else {
            $toleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
            if ($diff <= $toleransi) {
              $status_tepat = "2";
              $k_tepat = "Toleransi";
              $data_presensi["jml_toleransi"] += 1;
            } else {
              $status_tepat = "3";
              $k_tepat = "Terlambat";
              $data_presensi["jml_terlambat"] += 1;
            }
          }
          $ar = array(
            'idabsensi'       => $value->idabsensi,
            'nama_pegawai'    => $value->nama_pegawai,
            'NIP'             => $value->NIP,
            'waktu'           => $value->waktu,
            'status_absensi'  => $value->status_absensi,
            'latitude'        => $value->latitude,
            'longitude'       => $value->longitude,
            'jenis_tempat'    => $value->jenis_tempat,
            'foto'            => $value->foto,
            'waktu_pulang'    => @$absenpulang['waktu'],
            'foto_pulang'     => @$absenpulang['foto'],
            'waktu_istirahat' => $jam_istirahat,
            'status_tepat'    => $status_tepat,
            'k_tepat'         => $k_tepat,
          );
          if ($status_waktu != null || $status_waktu != "") {
            if ($status_waktu == $status_tepat) {
              array_push($data, $ar);
            }
          } else {
            array_push($data, $ar);
          }
        }
        $res = array(
          'message'       => "Success",
          'status'        => 200
        );
      } else {
        $res = array(
          'message'       => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
          'status'        => 500
        );
      }
    }
    echo json_encode(array('data' => $data, 'message' => $res, 'data_presensi' => $data_presensi));
  }

  function laporan_kegiatan()
  {
    $data = array();
    $status = $this->input->post("status");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    // echo json_encode($kepala_unit);
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $riwayat = $this->ModelKegiatan->getKegiatanAproval($unit, $status, $tgl_mulai, $tgl_akhir);
      if ($riwayat->num_rows() > 0) {
        foreach ($riwayat->result() as $value) {
          $kegiatan = $this->ModelKegiatan->get_data($value->kegiatan_idkegiatan)->row_array();
          $ar = array(
            'idabsen_kegiatan' => $value->idabsen_kegiatan,
            'nama_pegawai'    => $value->nama_pegawai,
            'NIP'             => $value->NIP,
            'absen_latitude' => $value->absen_latitude,
            'absen_longtitude' => $value->absen_longtitude,
            'foto' => $value->foto,
            'jam_presensi' => date("H:i:s", strtotime($value->jam_presensi)),
            'tgl_presensi' => date("D, d M Y", strtotime($value->jam_presensi)),
            'status_aproval' => $value->status_aproval,
            'kegiatan' => $kegiatan
          );
          array_push($data, $ar);
        }
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function laporan_lembur()
  {
    $data = array();
    $status = $this->input->post("status");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    // echo json_encode($kepala_unit);
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $riwayat = $this->ModelLembur->getKegiatanAproval($unit, $status, $tgl_mulai, $tgl_akhir);
      if ($riwayat->num_rows() > 0) {
        foreach ($riwayat->result() as $value) {
          $lembur = $this->ModelLembur->get_data($value->lembur_idlembur)->row_array();
          $ar = array(
            'idabsen_lembur' => $value->idabsen_lembur,
            'nama_pegawai'    => $value->nama_pegawai,
            'NIP'             => $value->NIP,
            'absen_latitude' => $value->absen_latitude,
            'absen_longtitude' => $value->absen_longtitude,
            'foto' => $value->foto,
            'jam_presensi' => date("H:i:s", strtotime($value->jam_presensi)),
            'tgl_presensi' => date("D, d M Y", strtotime($value->jam_presensi)),
            'status_aproval' => $value->status_aproval,
            'lembur' => $lembur
          );
          array_push($data, $ar);
        }
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function riwayat_perizinan()
  {
    $status = $this->input->post("status");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $izin = $this->ModelPerizinan->get_riwayatMonitoring($unit, $status, $tgl_mulai, $tgl_akhir);
      $data = array();
      if ($izin->num_rows() > 0) {
        $data = $izin->result();
        $res = array(
          'message' => "Success",
          'status' => 200
        );
      } else {
        $res = array(
          'message' => "Maaf Tidak Bisa Ambil Data",
          'status' => 500
        );
      }
    }
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function detail_presensi($idabsensi)
  {
    $absen            = $this->ModelAbsensi->get_Absensi($idabsensi)->row_array();
    $pegawai          = $this->ModelPegawai->edit($absen["pegawai_uuid"])->row_array();
    $absenpulang      = @$this->ModelAbsensi->get_AbsensiPulang($absen["idabsensi"])->row_array();
    $istirahat        = @$this->ModelRiwayat->get_Absensi_Istirahat($idabsensi["pegawai_uuid"], date("Y-m-d", strtotime($absen['waktu'])))->row_array();
    $selesaiIstirahat = @$this->ModelRiwayat->get_Selesai_Istirahat($istirahat["idabsensi"])->row_array();
    $data = array(
      'pegawai'   => $pegawai,
      'absen'     => $absen,
      'absensi_pulang' => $absenpulang,
      'istirahat' => $istirahat,
      'selesai_istirahat' => $selesaiIstirahat,
    );
    $res = array(
      'message' => "Success",
      'status' => 200
    );
    echo json_encode(array('data' => $data, 'message' => $res));
  }
}
