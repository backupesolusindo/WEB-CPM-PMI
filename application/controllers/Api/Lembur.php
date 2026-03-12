<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lembur extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelLembur");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function getLembur()
  {
    $uuid = $this->input->post("uuid");
    $kampus = $this->ModelLembur->get_terkini($uuid);
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

  function absen_lembur()
  {
    $patch = "document/foto_lembur/";
    // echo $patch;
    $config['upload_path']          = "./" . $patch;
    $config['allowed_types']        = '*';
    $config['max_size']             = 11240;
    $data = array();
    $lembur = $this->ModelLembur->get_data($this->input->post("idlembur"))->row_array();
    if (strtotime('now') >= strtotime($lembur['tgl_mulai'])) {
      $this->db->where("lembur_idlembur", $this->input->post("idlembur"));
      $this->db->where("pegawai_uuid", $this->input->post("id"));
      $this->db->where("LEFT(jam_presensi, 10) = ", date("Y-m-d"));
      $absen_lembur = $this->db->get("absen_lembur");
      if ($absen_lembur->num_rows() < 1) {
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('image')) {
          $data = array(
            'jam_presensi'         => date("Y-m-d H:i:s"),
            'lembur_idlembur'      => $this->input->post("idlembur"),
            'pegawai_uuid'         => $this->input->post("id"),
            'absen_latitude'       => $this->input->post("lat"),
            'absen_longtitude'     => $this->input->post("long"),
            'status_lokasi'        => $this->input->post("status_lokasi"),
            'foto'                 => $patch . $this->upload->data()['file_name']
          );
          if ($this->db->insert("absen_lembur", $data)) {
            // $id_insert = $this->db->insert_id();
            // $this->db->where("uuid", $this->input->post("id"));
            // $this->db->update("pegawai", array("idabsen"=>$id_insert,"status_absen"=>"1"));
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
        $absen_lembur = $absen_lembur->row_array();
        $data = array(
          'jam_presensi_selesai' => date("Y-m-d H:i:s"),
        );
        $this->db->where("idabsen_lembur", $absen_lembur['idabsen_lembur']);
        if ($this->db->update('absen_lembur', $data)) {
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
      }
    } else {
      $tgl_ket = date("d-m-Y", strtotime($kegiatan['tanggal']));
      if ($kegiatan['tanggal'] != $kegiatan['tanggal_selesai']) {
        date("d-m-Y", strtotime($kegiatan['tanggal'])) . " s/d " . date("d-m-Y", strtotime($kegiatan['tanggal_selesai']));
      }
      $res = array(
        'message' => "Belum Waktunya Presensi, Lembur Tanggal " . $tgl_ket,
        'status' => 500
      );
    }
    echo json_encode(array('response' => $data, 'message' => $res));
  }

  function cek_absen_lembur($idlembur, $uuid)
  {
    $kampus = $this->ModelLembur->get_cek($idlembur, $uuid, date("Y-m-d"));
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

  function list_aproval()
  {
    $unit = $this->input->post("unit");
    $data = array();
    $riwayat = $this->ModelLembur->getKegiatanAproval($unit, "0");
    if ($riwayat->num_rows() > 0) {
      foreach ($riwayat->result() as $value) {
        $kegiatan = $this->ModelKegiatan->get_data($value->kegiatan_idkegiatan)->row_array();
        $ar = array(
          'idabsen_lembur' => $value->idabsen_lembur,
          'nama_pegawai'    => $value->nama_pegawai,
          'NIP'             => $value->NIP,
          'absen_latitude'  => $value->absen_latitude,
          'absen_longtitude' => $value->absen_longtitude,
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
    } else {
      $res = array(
        'message' => "Maaf Tidak Bisa Ambil Data, Mohon Cek Koneksi Anda",
        'status' => 500
      );
    }
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function approval_lembur()
  {
    $idabsensi = $this->input->post("idabsensi");
    $approval = $this->input->post("approval");
    $this->db->where("idabsen_lembur", $idabsensi);
    if ($this->db->update("absen_lembur", array('status_aproval' => $approval))) {
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
    echo json_encode(array('message' => $res));
  }
}
