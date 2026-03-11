<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelRiwayat");
    $this->load->model("ModelKegiatan");
    $this->load->model("ModelPerizinan");
    $this->load->model("ModelAbsensi");
    $this->load->model('ModelPegawai');
    $this->load->model('ModelAproval');
    $this->load->model('ModelLembur');
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function cek()
  {
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    echo json_encode($kepala_unit);
  }

  function riwayat_harian_old()
  {
    $data = array();
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $absenharian = $this->ModelRiwayat->RiwayatHarianMonitoring($unit,"0");
      if ($absenharian->num_rows() > 0) {
        foreach ($absenharian->result() as $value) {
          $absenpulang = $this->ModelRiwayat->Pulang($value->idabsensi)->row_array();
          $istirahat        = $this->ModelRiwayat->get_Absensi_Istirahat(date("Y-m-d", strtotime($value->waktu)));
          $jam_istirahat = "Belum Presensi Istirahat";
          if ($istirahat->num_rows() > 0) {
            $istirahat = $istirahat->row_array();
            $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) ." - Selesai Istirahat Belum Presensi";
            $selesaiIstirahat = $this->ModelRiwayat->get_Selesai_Istirahat($istirahat["idabsensi"]);
            if ($selesaiIstirahat->num_rows() > 0) {
              $selesaiIstirahat = $selesaiIstirahat->row_array();
              $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) ." - ". date("H:i:s", strtotime($selesaiIstirahat['waktu']));
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
          }else {
            $toleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
            if ($diff <= $toleransi) {
              $status_tepat = "2";
              $k_tepat = "Toleransi";
            }else {
              $status_tepat = "3";
              $k_tepat = "Terlambat";
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
          'jenis'           => $value->jenis,
          'jenis_pusat'     => $kep->jenis,
          'waktu_pulang'    => @$absenpulang['waktu'],
          'foto_pulang'     => @$absenpulang['foto'],
          'waktu_istirahat' => $jam_istirahat,
          'status_tepat'    => $status_tepat,
          'k_tepat'         => $k_tepat,
          );
          if ($value->jenis == "LABORATORIUM" && $kep->jenis == "JURUSAN") {
          }else {
            array_push($data, $ar);
          }
        }
        $res = array(
        'message' => "Success",
        'status' => 200
        );
      }else {
        $res = array(
        'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
        'status' => 500
        );
      }

    }
    echo json_encode(array('data'=>$data,'message'=>$res));
  }

  function riwayat_harian()
  {
    $data = array();
    $dataAbsensi = array();
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $dataAbsensi = $this->ModelAproval->ApPresensiHarian($unit, $this->input->post("uuid"), $kep->monitor)->result();
      // $dataKep = $this->ModelAproval->ApKepPresensiHarian($unit, $this->input->post("uuid"), $kep->monitor)->result();
      // foreach ($dataKep as $value) {
      //   array_push($dataAbsensi, $value);
      // }
      // if ($kep->monitor == 1) {
      //   $dataPeg = $this->ModelAproval->ApPresensiHarian($unit, $this->input->post("uuid"))->result();
      //   foreach ($dataPeg as $value) {
      //     array_push($dataAbsensi, $value);
      //   }
      // }
      foreach ($dataAbsensi as $value) {
        $absenpulang    = $this->ModelRiwayat->Pulang($value->idabsensi)->row_array();
        $istirahat      = $this->ModelRiwayat->get_Absensi_Istirahat(date("Y-m-d", strtotime($value->waktu)));
        $jam_istirahat  = "Belum Presensi Istirahat";
        if ($istirahat->num_rows() > 0) {
          $istirahat = $istirahat->row_array();
          $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) ." - Selesai Istirahat Belum Presensi";
          $selesaiIstirahat = $this->ModelRiwayat->get_Selesai_Istirahat($istirahat["idabsensi"]);
          if ($selesaiIstirahat->num_rows() > 0) {
            $selesaiIstirahat = $selesaiIstirahat->row_array();
            $jam_istirahat = date("H:i:s", strtotime($istirahat['waktu'])) ." - ". date("H:i:s", strtotime($selesaiIstirahat['waktu']));
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
        }else {
          $toleransi = strtotime(date("H:i:s", strtotime($value->jam_toleransi))) - strtotime(date("H:i:s", strtotime("00:00:00")));
          if ($diff <= $toleransi) {
            $status_tepat = "2";
            $k_tepat = "Toleransi";
          }else {
            $status_tepat = "3";
            $k_tepat = "Terlambat";
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
        'jenis'           => $value->jenis,
        'jenis_pusat'     => $kep->jenis,
        'waktu_pulang'    => @$absenpulang['waktu'],
        'foto_pulang'     => @$absenpulang['foto'],
        'waktu_istirahat' => $jam_istirahat,
        'status_tepat'    => $status_tepat,
        'k_tepat'         => $k_tepat,
        );
        if ($value->jenis == "LABORATORIUM" && $kep->jenis == "JURUSAN") {
        }else {
          array_push($data, $ar);
        }
      }
    }
    if (sizeof($data) > 0) {
      $res = array(
      'message' => "Success",
      'status' => 200
      );
    }else {
      $res = array(
      'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
      'status' => 500
      );
    }
    echo json_encode(array('data'=>$data,'message'=>$res));
  }

  public function coba()
  {
    $data = array();
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $dataKep = $this->ModelAproval->ApKepPresensiHarian($unit, $this->input->post("uuid"))->result();
      foreach ($dataKep as $value) {
        array_push($data, $value);
      }
      // $dataPeg = $this->ModelAproval->ApPresensiHarian($unit, $this->input->post("uuid"))->result();
      // foreach ($dataPeg as $value) {
      //   array_push($data, $value);
      // }
    }
    if (sizeof($data) > 0) {
      $res = array(
      'message' => "Success",
      'status' => 200
      );
    }else {
      $res = array(
      'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
      'status' => 500
      );
    }
    echo json_encode(array('data'=>$data,'message'=>sizeof($data)));
  }

  function approval_presensi()
  {
    $idabsensi = $this->input->post("idabsensi");
    $approval = $this->input->post("approval");
    $this->db->where("idabsensi", $idabsensi);
    $res = array();
    if ($this->db->update("absensi", array('status_absensi' => $approval))) {
      $da = $this->ModelAbsensi->get_Absensi($idabsensi)->row_array();
      $this->core->curlNotif($da["token"],
      "Persetujuan Presensi", "Cek Riwayat Presensi ");
      $res = array(
      'message' => "Success",
      'status' => 200
      );
    }else {
      $res = array(
      'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
      'status' => 500
      );
    }
    echo json_encode(array('data'=>"",'message'=>$res));
  }

  function approval_perizinan()
  {
    $idizin = $this->input->post("idizin");
    $approval = $this->input->post("approval");
    $this->db->where("idizin", $idizin);
    if ($this->db->update("izin", array('status' => $approval))) {
      $da = $this->ModelPerizinan->get_perizinan($idizin)->row_array();
      $this->core->curlNotif($da["token"],
      "Persetujuan Cuti", "Cek Riwayat Cuti ");
      $res = array(
      'message' => "Success",
      'status' => 200
      );
    }else {
      $res = array(
      'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
      'status' => 500
      );
    }
    echo json_encode(array('message'=>$res));
  }

  function riwayat_perizinan()
  {
    $data = array();
    $res = array(
    'message' => "Maaf Tidak Bisa Ambil Data",
    'status' => 500
    );
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $izin = $this->ModelPerizinan->get_riwayatMonitoring($unit,"0");
      if ($izin->num_rows() > 0) {
        $data = $izin->result();
        $res = array(
        'message' => "Success",
        'status' => 200
        );
      }else {
        $res = array(
        'message' => "Maaf Tidak Bisa Ambil Data",
        'status' => 500
        );
      }
    }
    echo json_encode(array('data'=>$data,'message'=>$res));
  }

  function riwayat_kegiatan()
  {
    $data = array();
    $res = array(
    'message' => "Maaf Tidak Bisa Ambil Data",
    'status' => 500
    );
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $riwayat = $this->ModelKegiatan->getKegiatanAproval($unit,"0");
      if ($riwayat->num_rows() > 0) {
        foreach ($riwayat->result() as $value) {
          $kegiatan = $this->ModelKegiatan->get_data($value->kegiatan_idkegiatan)->row_array();
          $ar = array(
          'idabsen_kegiatan'=> $value->idabsen_kegiatan,
          'nama_pegawai'    => $value->nama_pegawai,
          'NIP'             => $value->NIP,
          'absen_latitude'  => $value->absen_latitude,
          'absen_longtitude'=> $value->absen_longtitude,
          'foto'            => $value->foto,
          'jam_presensi'    => date("H:i:s", strtotime($value->jam_presensi)),
          'tgl_presensi'    => date("D, d M Y", strtotime($value->jam_presensi)),
          'status_aproval'  => $value->status_aproval,
          'kegiatan'        => $kegiatan
          );
          array_push($data, $ar);
        }
        $res = array(
        'message' => "Success",
        'status' => 200
        );
      }else {
        $res = array(
        'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
        'status' => 500
        );
      }
    }
    echo json_encode(array('data'=>$data,'message'=>$res));
  }

  function riwayat_lembur()
  {
    $data = array();
    $res = array(
    'message' => "Maaf Tidak Bisa Ambil Data",
    'status' => 500
    );
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->result();
    foreach ($kepala_unit as $kep) {
      $unit = $kep->nama_unit;
      $riwayat = $this->ModelLembur->getKegiatanAproval($unit,"0");
      if ($riwayat->num_rows() > 0) {
        foreach ($riwayat->result() as $value) {
          $lembur = $this->ModelLembur->get_data($value->lembur_idlembur)->row_array();
          $ar = array(
          'idabsen_lembur'  => $value->idabsen_lembur,
          'nama_pegawai'    => $value->nama_pegawai,
          'NIP'             => $value->NIP,
          'absen_latitude'  => $value->absen_latitude,
          'absen_longtitude'=> $value->absen_longtitude,
          'foto'            => $value->foto,
          'jam_presensi'    => date("H:i:s", strtotime($value->jam_presensi)),
          'jam_presensi_selesai'    => date("H:i:s", strtotime($value->jam_presensi_selesai)),
          'tgl_presensi'    => date("D, d M Y", strtotime($value->jam_presensi)),
          'status_aproval'  => $value->status_aproval,
          'lembur'          => $lembur
          );
          array_push($data, $ar);
        }
        $res = array(
        'message' => "Success",
        'status' => 200
        );
      }else {
        $res = array(
        'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
        'status' => 500
        );
      }
    }
    echo json_encode(array('data'=>$data,'message'=>$res));
  }

}
