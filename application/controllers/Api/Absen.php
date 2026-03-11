<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Absen extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelAbsensi");
    $this->load->model("ModelJadwalMasuk");
    $this->load->model("ModelPegawai");
    $this->load->model("ModelKampus");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function cobaNotif()
  {
    echo $this->core->curlNotif(
      "fOU5OcWqRJupoGT9ZbQGWC:APA91bGIEhz9NQOAuKAiFVSYxrJ4mQGdl4hI17lBI5WCerw1tNhUmaO-EmHxOYIJ-VlAiHilLYJUTSxCrKAiYrmAyD4AJ9a9Ho9MTZyLbvH-VxwzLQ9tekDCK6yQDJy2-zikmjuPaU1k",
      "Coba PHP API",
      "INI DENGAN API"
    );
  }

  function insert_absen()
  {
    $data = array();
    $boleh_presensis = 1;
    $pesan_presensi = "";
    $lat = $this->input->post("lat");
    $long = $this->input->post("long");
    $idkampus = @$this->input->post("idkampus");

    $cek_presensi = $this->ModelAbsensi->cek_Absensi(
      $this->input->post("id"),
      date("Y-m-d")
    );
    if ($cek_presensi->num_rows() > 0) {
      $boleh_presensis = 0;
      $pesan_presensi = "Anda Sudah Melakukan Presensi Hari Ini. Mohon Cek Di Riwayat Presensi Anda";
    } else {
      $cek_pegawai_aktif = $this->ModelPegawai->cek_pegawai_aktif($this->input->post("id"));
      if ($cek_pegawai_aktif->num_rows() <= 0) {
        $boleh_presensis = 0;
        $pesan_presensi = "Maaf Akun Anda Sudah Tidak Aktif";
      } elseif ($lat == 0.0 || $long == 0.0) {
        $boleh_presensis = 0;
        $pesan_presensi = "Maaf Lokasi Anda Tidak Valid, Silakan Presensi Ulang";
      } else {
        $boleh_presensis = 1;
      }
    }

    $jam_jadwal = date("H:i:s", strtotime($this->input->post("jam_masuk")));
    $masuk = date("H:i:s");
    $diff = strtotime($masuk) - strtotime($jam_jadwal);
    // echo $diff;
    $data = array();
    // if ($diff < 7200) {
    if ($boleh_presensis == 1) {
      $patch = "document/foto_absen/";
      // echo $patch;
      $config['upload_path'] = "./" . $patch;
      $config['allowed_types'] = '*';
      $config['max_size'] = 11240;
      $this->load->library('upload', $config);
      if ($this->upload->do_upload('image')) {
        $data = array(
          'waktu' => date("Y-m-d H:i:s"),
          'jam_jadwal' => date("H:i:s", strtotime($this->input->post("jam_masuk"))),
          'idjadwal' => $this->input->post("idjadwal"),
          'pegawai_uuid' => $this->input->post("id"),
          'latitude' => $this->input->post("lat"),
          'longitude' => $this->input->post("long"),
          'jenis_absen' => $this->input->post("jenis_absen"),
          'jenis_tempat' => $this->input->post("jenis_tempat"),
          'kampus_idkampus' => @$this->input->post("idkampus"),
          'foto' => $patch . $this->upload->data()['file_name']
        );
        if ($this->db->insert("absensi", $data)) {
          $id_insert = $this->db->insert_id();
          $this->db->where("uuid", $this->input->post("id"));
          $this->db->update("pegawai", array("idabsen" => $id_insert, "status_absen" => "1"));
          $res = array(
            'message' => "Berhasil",
            'status' => 200
          );
        } else {
          $res = array(
            'message' => "Gagal Menyimpan",
            'status' => 501
          );
        }
      } else {
        $res = array(
          'message' => "Gagal Upload Foto" . $this->upload->display_errors(),
          'status' => 500
        );
      }
    } else {
      $res = array(
        'message' => $pesan_presensi,
        'status' => 502
      );
    }
    echo json_encode(array('response' => $data, 'message' => $res));
  }

  function insert_absen_lokasi()
  {
    $patch = "document/foto_absen/";
    // echo $patch;
    $config['upload_path'] = "./" . $patch;
    $config['allowed_types'] = '*';
    $config['max_size'] = 11240;
    $data = array();
    $this->load->library('upload', $config);
    if ($this->upload->do_upload('image')) {
      $data = array(
        'waktu' => date("Y-m-d H:i:s"),
        'pegawai_uuid' => $this->input->post("id"),
        'latitude' => $this->input->post("lat"),
        'longtitude' => $this->input->post("long"),
        'foto' => $patch . $this->upload->data()['file_name']
      );
      if ($this->db->insert("presensi_lokasi", $data)) {
        $res = array(
          'message' => "Berhasil",
          'status' => 200
        );
      } else {
        $res = array(
          'message' => "Gagal Menyimpan",
          'status' => 501
        );
      }
    } else {
      $res = array(
        'message' => "Gagal",
        'status' => 500
      );
    }
    echo json_encode(array('response' => $data, 'message' => $res));
  }

  function insert_istirahat()
  {
    $patch = "document/foto_istirahat/";
    // echo $patch;
    $config['upload_path'] = "./" . $patch;
    $config['allowed_types'] = '*';
    $config['max_size'] = 11240;
    $data = array();
    $this->load->library('upload', $config);
    if ($this->upload->do_upload('image')) {
      $data = array(
        'waktu' => date("Y-m-d H:i:s"),
        'pegawai_uuid' => $this->input->post("id"),
        'latitude' => $this->input->post("lat"),
        'longitude' => $this->input->post("long"),
        'jenis_tempat' => $this->input->post("jenis_tempat"),
        'foto' => $patch . $this->upload->data()['file_name']
      );
      if ($this->db->insert("absensi_istirahat", $data)) {
        $id_insert = $this->db->insert_id();
        $this->db->where("uuid", $this->input->post("id"));
        $this->db->update("pegawai", array("idistirahat" => $id_insert, "status_istirahat" => "1"));
        $res = array(
          'message' => "Berhasil",
          'status' => 200
        );
      } else {
        $res = array(
          'message' => "Gagal Menyimpan",
          'status' => 501
        );
      }
    } else {
      $res = array(
        'message' => "Gagal",
        'status' => 500
      );
    }
    echo json_encode(array('response' => $data, 'message' => $res));
  }

  function insert_absen_selesai()
  {
    $idabsensi = $this->input->post("idabsensi");
    $data_absen = $this->ModelAbsensi->get_Absensi($idabsensi)->row_array();
    $data_jadwal = $this->ModelJadwalMasuk->get_edit($data_absen['idjadwal'])->row_array();
    $lat = $this->input->post("lat");
    $long = $this->input->post("long");
    $jam_jadwal = date("H:i:s", strtotime($data_jadwal["jam_pulang"]));
    $masuk = date("H:i:s");
    $diff = strtotime($masuk) - strtotime($jam_jadwal);
    $data = array();
    $message = "Waktu Presensi Anda Melewati Batas";
    $boleh_absen = true;

    if (@$data_absen['jab_struktur'] == "MBC") { #Status 4 untuk WFH
      $kampus = $this->ModelKampus->get_edit("1")->row_array();
      $jarak = $this->ModelAbsensi->getDistanceBetweenPoints($lat, $long, $kampus['latitude'], $kampus['longtitude']);
      if ($jarak < $kampus['radius']) {
        $boleh_absen = false;
        $message = "Maaf Tidak boleh Presensi Pulang di Pusat";
      }
    }
    // if ($diff < 7200) {
    if ($boleh_absen) {
      $patch = "document/foto_absen/";
      // echo $patch;
      $config['upload_path'] = "./" . $patch;
      $config['allowed_types'] = '*';
      $config['max_size'] = 11240;
      $absenPulang = $this->ModelAbsensi->get_AbsensiPulang($idabsensi);
      if ($absenPulang->num_rows() > 0) {
        $absenPulang = $absenPulang->row_array();
        unlink('./' . $absenPulang['foto']);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('image')) {
          $data = array(
            'waktu' => date("Y-m-d H:i:s"),
            'pegawai_uuid' => $this->input->post("id"),
            'latitude' => $this->input->post("lat"),
            'longitude' => $this->input->post("long"),
            'foto' => $patch . $this->upload->data()['file_name']
          );
          $this->db->where("absensi_idabsensi", $idabsensi);
          if ($this->db->update("absensi_pulang", $data)) {
            $this->db->where("uuid", $this->input->post("id"));
            $this->db->update("pegawai", array("status_absen" => "2"));
            $res = array(
              'message' => "Berhasil",
              'status' => 200
            );
          } else {
            $res = array(
              'message' => "Gagal Menyimpan",
              'status' => 501
            );
          }
        } else {
          $res = array(
            'message' => "Gagal",
            'status' => 500
          );
        }
      } else {
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('image')) {
          $data = array(
            'waktu' => date("Y-m-d H:i:s"),
            'pegawai_uuid' => $this->input->post("id"),
            'absensi_idabsensi' => $idabsensi,
            'latitude' => $this->input->post("lat"),
            'longitude' => $this->input->post("long"),
            'foto' => $patch . $this->upload->data()['file_name']
          );
          if ($this->db->insert("absensi_pulang", $data)) {
            $this->db->where("uuid", $this->input->post("id"));
            $this->db->update("pegawai", array("status_absen" => "2"));
            $res = array(
              'message' => "Berhasil",
              'status' => 200
            );
          } else {
            $res = array(
              'message' => "Gagal Menyimpan",
              'status' => 501
            );
          }
        } else {
          $res = array(
            'message' => "Gagal",
            'status' => 500
          );
        }
      }
    } else {
      $res = array(
        'message' => $message,
        'status' => 502
      );
    }
    echo json_encode(array('response' => $data, 'message' => $res));
  }

  function insert_absen_cabang()
  {
    $idabsensi = $this->input->post("idabsensi");
    $data_absen = $this->ModelAbsensi->get_Absensi($idabsensi)->row_array();
    $data_jadwal = $this->ModelJadwalMasuk->get_edit($data_absen['idjadwal'])->row_array();
    $lat = $this->input->post("lat");
    $long = $this->input->post("long");
    $jam_jadwal = date("H:i:s", strtotime($data_jadwal["jam_pulang"]));
    $masuk = date("H:i:s");
    $diff = strtotime($masuk) - strtotime($jam_jadwal);
    $data = array();
    $message = "Waktu Presensi Anda Melewati Batas";
    $boleh_absen = true;

    if (@$data_absen['jab_struktur'] == "MBC") { #Status 4 untuk WFH
      $kampus = $this->ModelKampus->get_edit("1")->row_array();
      $jarak = $this->ModelAbsensi->getDistanceBetweenPoints($lat, $long, $kampus['latitude'], $kampus['longtitude']);
      if ($jarak < $kampus['radius']) {
        $boleh_absen = false;
        $message = "Maaf Tidak boleh Presensi Pulang di Pusat";
      }
    }
    // if ($diff < 7200) {
    if ($boleh_absen) {
      $patch = "document/foto_absen/";
      // echo $patch;
      $config['upload_path'] = "./" . $patch;
      $config['allowed_types'] = '*';
      $config['max_size'] = 11240;
      $absenPulang = $this->ModelAbsensi->get_AbsensiCabang($idabsensi);
      if ($absenPulang->num_rows() > 0) {
        $absenPulang = $absenPulang->row_array();
        @unlink('./' . $absenPulang['foto']);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('image')) {
          $data = array(
            'waktu' => date("Y-m-d H:i:s"),
            'pegawai_uuid' => $this->input->post("id"),
            'latitude' => $this->input->post("lat"),
            'longitude' => $this->input->post("long"),
            'foto' => $patch . $this->upload->data()['file_name']
          );
          $this->db->where("absensi_idabsensi", $idabsensi);
          if ($this->db->update("absen_cabang", $data)) {
            $res = array(
              'message' => "Berhasil",
              'status' => 200
            );
          } else {
            $res = array(
              'message' => "Gagal Menyimpan",
              'status' => 501
            );
          }
        } else {
          $res = array(
            'message' => "Gagal",
            'status' => 500
          );
        }
      } else {
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('image')) {
          $data = array(
            'waktu' => date("Y-m-d H:i:s"),
            'pegawai_uuid' => $this->input->post("id"),
            'absensi_idabsensi' => $idabsensi,
            'latitude' => $this->input->post("lat"),
            'longitude' => $this->input->post("long"),
            'foto' => $patch . $this->upload->data()['file_name']
          );
          if ($this->db->insert("absen_cabang", $data)) {
            $res = array(
              'message' => "Berhasil",
              'status' => 200
            );
          } else {
            $res = array(
              'message' => "Gagal Menyimpan",
              'status' => 501
            );
          }
        } else {
          $res = array(
            'message' => "Gagal",
            'status' => 500
          );
        }
      }
    } else {
      $res = array(
        'message' => $message,
        'status' => 502
      );
    }
    echo json_encode(array('post' => $this->input->post(), 'response' => $data, 'message' => $res));
  }

  function insert_istirahat_selesai()
  {
    $patch = "document/foto_istirahat/";
    // echo $patch;
    $config['upload_path'] = "./" . $patch;
    $config['allowed_types'] = '*';
    $config['max_size'] = 11240;
    $data = array();
    $this->load->library('upload', $config);
    if ($this->upload->do_upload('image')) {
      $data = array(
        'waktu' => date("Y-m-d H:i:s"),
        'pegawai_uuid' => $this->input->post("id"),
        'absensi_istirahat_idabsensi' => $this->input->post("idabsensi"),
        'latitude' => $this->input->post("lat"),
        'longitude' => $this->input->post("long"),
        'foto' => $patch . $this->upload->data()['file_name']
      );
      if ($this->db->insert("absensi_selesai_istirahat", $data)) {
        $this->db->where("uuid", $this->input->post("id"));
        $this->db->update("pegawai", array("status_istirahat" => "2"));
        $res = array(
          'message' => "Berhasil",
          'status' => 200
        );
      } else {
        $res = array(
          'message' => "Gagal Menyimpan",
          'status' => 501
        );
      }
    } else {
      $res = array(
        'message' => "Gagal",
        'status' => 500
      );
    }
    echo json_encode(array('response' => $data, 'message' => $res));
  }


  function update_location()
  {
    $idpegawai = $this->input->post("id");
    $data = array(
      'user_id'       => $idpegawai,
      'latitude'      => $this->input->post("latitude"),
      'longitude'     => $this->input->post("longitude"),
      'accuracy'      => $this->input->post("accuracy"),
      'timestamp'     => $this->input->post("timestamp"),
      'speed'         => $this->input->post("speed"),
      'heading'       => $this->input->post("heading"),
    );
    $cek_data = $this->db->get_where("realtime_location", array("user_id" => $idpegawai));
    if ($cek_data->num_rows() <= 0) {
      $this->db->insert("realtime_location", $data);
    } else {
      $this->db->where("user_id", $idpegawai);
      $this->db->update("realtime_location", $data);
    }
    if ($this->db->affected_rows() > 0) {
      $res = array(
        'message' => "Berhasil",
        'status' => 200
      );
    } else {
      $res = array(
        'message' => "Gagal Menyimpan",
        'status' => 501
      );
    }
    echo json_encode(array('response' => $data, 'message' => $res));
  }

  private function activate_realtime_location($user_id, $lat, $long)
  {
    $data = array(
      'user_id' => $user_id,
      'latitude' => $lat,
      'longitude' => $long,
      'accuracy' => 0,
      'timestamp' => date('Y-m-d H:i:s'),
      'speed' => 0.00,
      'heading' => 0.00
    );

    $cek_data = $this->db->get_where("realtime_location", array("user_id" => $user_id));
    if ($cek_data->num_rows() <= 0) {
      $this->db->insert("realtime_location", $data);
    } else {
      $this->db->where("user_id", $user_id);
      $this->db->update("realtime_location", $data);
    }
  }

  // FUNGSI BARU: Nonaktifkan realtime location
  private function deactivate_realtime_location($user_id)
  {
    $this->db->where("user_id", $user_id);
    $this->db->delete("realtime_location");
  }

  // FUNGSI BARU: Cek status realtime location
  function check_realtime_status()
  {
    $user_id = $this->input->post("id");
    $cek_data = $this->db->get_where("realtime_location", array("user_id" => $user_id));

    if ($cek_data->num_rows() > 0) {
      $data = $cek_data->row_array();
      $res = array(
        'message' => "Realtime location aktif",
        'status' => 200,
        'is_active' => true,
        'data' => $data
      );
    } else {
      $res = array(
        'message' => "Realtime location tidak aktif",
        'status' => 200,
        'is_active' => false
      );
    }
    echo json_encode(array('response' => $res));
  }
}
