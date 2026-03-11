<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatAbsen extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelRiwayat");
    $this->load->model("ModelKegiatan");
    $this->load->model("ModelLaporan");
    $this->load->model("ModelLembur");
    $this->load->model('ModelAuth');
    $this->load->model('ModelJadwalMasuk');
    $this->ModelAuth->verify_token();
  }

  function riwayat_harian(){
    $uuid = $this->input->post("uuid");
    $status = $this->input->post("status");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $absenharian = $this->ModelRiwayat->RiwayatHarian($uuid, $status, $tgl_mulai, $tgl_akhir);
    $data = array();
    $limit_durasi = 0;
    $time_durasi = 0;
    $ket_durasi = 'Belum melakukan presensi';
    
    if ($absenharian->num_rows() > 0) {
        foreach ($absenharian->result() as $value) {
            $foto_pulang = "";
            $waktu_pulang = "Belum Presensi Pulang";
            $waktu_cabang = null;
            
            // FIX: Tambahkan null check untuk idjadwal
            if (empty($value->idjadwal)) {
                continue; // Skip data ini jika tidak ada jadwal
            }
            
            $jadwal_result = $this->ModelJadwalMasuk->get_edit($value->idjadwal);
            if (!$jadwal_result || $jadwal_result->num_rows() == 0) {
                continue; // Skip jika jadwal tidak ditemukan
            }
            
            $jadwal_masuk = $jadwal_result->row_array();
            $absenpulang = $this->ModelRiwayat->Pulang($value->idabsensi);
            $absenCabang = $this->ModelRiwayat->AbsenCabang($value->idabsensi);
            
            $limit_durasi = strtotime($jadwal_masuk['jam_pulang']) - strtotime($jadwal_masuk['jam_masuk']);
            $time_durasi = strtotime($jadwal_masuk['jam_pulang']) - strtotime(date("H:i:s", strtotime($value->waktu)));
            
            if ($absenpulang->num_rows() > 0) {
                $absenpulang = $absenpulang->row_array();
                $foto_pulang = $absenpulang['foto'];
                $waktu_pulang = date("H:i:s d-m-Y", strtotime($absenpulang['waktu']));
                $time_durasi = strtotime($absenpulang['waktu']) - strtotime($value->waktu);
            }
            
            $ket_durasi = $this->core->formatDurasiLengkap($time_durasi);
            
            if ($absenCabang->num_rows() > 0) {
                $waktu_cabang = date("H:i:s d-m-Y", strtotime($absenCabang->row_array()['waktu']));
            }
            
            $istirahat = $this->ModelRiwayat->get_Absensi_Istirahat($uuid, date("Y-m-d", strtotime($value->waktu)));
            $jam_istirahat = "Belum Melakukan Presensi Istirahat";
            
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
            
            $ar = array(
                'waktu'           => date("H:i:s d-m-Y", strtotime($value->waktu)),
                'status_absensi'  => $value->status_absensi,
                'latitude'        => $value->latitude,
                'longitude'       => $value->longitude,
                'jenis_tempat'    => $value->jenis_tempat,
                'foto'            => $value->foto,
                'waktu_pulang'    => $waktu_pulang,
                'waktu_cabang'    => $waktu_cabang,
                'foto_pulang'     => $foto_pulang,
                'waktu_istirahat' => $jam_istirahat,
                'limit_durasi'    => $limit_durasi,
                'time_durasi'     => $time_durasi,
                'ket_durasi'      => $ket_durasi
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
    echo json_encode(array('data' => $data, 'message' => $res));
}


  function laporan_kegiatan()
  {
    $uuid = $this->input->post("uuid");
    $status = $this->input->post("status");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $data = array();
    $riwayat = $this->ModelKegiatan->riwayat_kegiatan($uuid, $status, $tgl_mulai, $tgl_akhir);
    if ($riwayat->num_rows() > 0) {
      foreach ($riwayat->result() as $value) {
        $kegiatan = $this->ModelKegiatan->get_data($value->kegiatan_idkegiatan)->row_array();
        $ar = array(
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function laporan_luarjam()
  {
    $uuid = $this->input->post("uuid");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $data = array();
    $riwayat = $this->ModelLaporan->rekapPresensiLuarJam($uuid, $tgl_mulai, $tgl_akhir);
    if ($riwayat->num_rows() > 0) {
      foreach ($riwayat->result() as $value) {
        $ar = array(
          'absen_latitude' => $value->latitude,
          'absen_longtitude' => $value->longtitude,
          'foto' => $value->foto,
          'jam_presensi' => date("H:i:s", strtotime($value->waktu)),
          'tgl_presensi' => date("D, d M Y", strtotime($value->waktu)),
          'status_absensi' => $value->status_aproval,
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function laporan_lembur()
  {
    $uuid = $this->input->post("uuid");
    $status = $this->input->post("status");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $data = array();
    $riwayat = $this->ModelLembur->riwayat_lembur($uuid, $status, $tgl_mulai, $tgl_akhir);
    if ($riwayat->num_rows() > 0) {
      foreach ($riwayat->result() as $value) {
        $lembur = $this->ModelLembur->get_data($value->lembur_idlembur)->row_array();
        $jam_presensi_selesai = null;
        $tgl_presensi_selesai = null;
        if ($value->jam_presensi_selesai != null || $value->jam_presensi_selesai != "") {
          $jam_presensi_selesai = date("H:i:s", strtotime($value->jam_presensi_selesai));
          $tgl_presensi_selesai = date("D, d M Y", strtotime($value->jam_presensi_selesai));
        }
        $ar = array(
          'absen_latitude' => $value->absen_latitude,
          'absen_longtitude' => $value->absen_longtitude,
          'foto' => $value->foto,
          'jam_presensi' => date("H:i:s", strtotime($value->jam_presensi)),
          'tgl_presensi' => date("D, d M Y", strtotime($value->jam_presensi)),
          'jam_presensi_selesai' => $jam_presensi_selesai,
          'tgl_presensi_selesai' => $tgl_presensi_selesai,
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }
}
