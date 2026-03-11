<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelPegawai");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function getPegawai()
  {
    $kepala_unit = $this->ModelPegawai->get_kepalaunit($this->input->post("uuid"))->row_array();
    $unit = $kepala_unit['nama_unit'];
    $pegawai = $this->ModelPegawai->get_UnitPegawai($unit);
    $data = array();
    if ($pegawai->num_rows() > 0) {
      $data = $pegawai->result();
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }

  function update_profil()
  {
    $patch = "document/profil/";
    // echo $patch;
    $config['upload_path']          = "./" . $patch;
    $config['allowed_types']        = '*';
    $config['max_size']             = 16240;
    $data = array();
    $this->load->library('upload', $config);
    if ($this->upload->do_upload('image')) {
      $uuid = $this->input->post("uuid");
      $data = array(
        'foto_profil'          => $patch . $this->upload->data()['file_name']
      );
      $this->db->where("uuid", $uuid);
      if ($this->db->update("pegawai", $data)) {
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
        'message' => "Gagal Upload File Silakan Coba lagi",
        // 'message' => "Gagal Upload File Silakan Coba lagi".$this->upload->display_errors(),
        'status' => 500
      );
    }
    echo json_encode(array('response' => $data, 'message' => $res));
  }

  function set_lokasi()
  {
    $data = array(
      'kampus_idkampus' => $this->input->post("idkampus"),
    );
    $this->db->where("uuid", $this->input->post("uuid"));
    if ($this->db->update("pegawai", $data)) {
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
}
