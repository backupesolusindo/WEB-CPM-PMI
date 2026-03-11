<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Izin extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelJenisPerizinan");
    $this->load->model("ModelPerizinan");
    $this->load->model('ModelAuth');
    $this->ModelAuth->verify_token();
  }

  function insert_izin()
  {
    $data = array(
      'pegawai_uuid'  => $this->input->post("id"),
      'tanggal_mulai' => date("Y-m-d", strtotime($this->input->post("tanggal_akhir"))),
      'tanggal_akhir' => date("Y-m-d", strtotime($this->input->post("tanggal_mulai"))),
      'alasan'        => $this->input->post("alasan"),
      'jenis_perizinan_idjenis_perizinan' => $this->input->post("jenis_perizinan"),
    );

    $patch = "document/izin/";
    // echo $patch;
    $config['upload_path']          = "./" . $patch;
    $config['allowed_types']        = '*';
    $config['max_size']             = 91240;
    $this->load->library('upload', $config);
    if ($this->upload->do_upload('image')) {
      $data['file'] = $patch . $this->upload->data()['file_name'];
    }
    if ($this->db->insert("izin", $data)) {
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

  function get_jenis()
  {
    $jenis = $this->ModelJenisPerizinan->get_jenisperizinan();
    $data_jenis = array();
    if ($jenis->num_rows() > 0) {
      $data_jenis = $jenis->result();
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
    echo json_encode(array('response' => $data_jenis, 'message' => $res));
    // echo json_encode($data_jenis);
  }

  function riwayat_perizinan()
  {
    $uuid = $this->input->post("uuid");
    $status = $this->input->post("status");
    $tgl_mulai = date("Y-m-d", strtotime($this->input->post("mulai")));
    $tgl_akhir = date("Y-m-d", strtotime($this->input->post("akhir")));
    $izin = $this->ModelPerizinan->get_riwayat($uuid, $status, $tgl_mulai, $tgl_akhir);
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
    echo json_encode(array('data' => $data, 'message' => $res));
  }
}
