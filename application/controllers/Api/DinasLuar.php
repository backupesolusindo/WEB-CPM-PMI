<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DinasLuar extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //Codeigniter : Write Less Do More
        $this->load->model("ModelDinasLuar");
        $this->load->model('ModelAuth');
        $this->ModelAuth->verify_token();
    }

    function riwayat_dinasluar()
    {
        $uuid = $this->input->post("uuid");
        $tgl_mulai = date("Y-m-d", strtotime($this->input->post("tgl_mulai")));
        $tgl_akhir = date("Y-m-d", strtotime($this->input->post("tgl_akhir")));
        $data = array();
        try {
            $izin = $this->ModelDinasLuar->getDinasLuarbyUUID($uuid, $tgl_mulai, $tgl_akhir);
            $data = $izin->result();
            $res = array(
                'message' => "Success",
                'status' => 200
            );
        } catch (Exception $e) {
            $res = array(
                'message' => "Maaf Tidak Bisa Ambil Data : " . $e->getMessage(),
                'status' => 500
            );
        }
        echo json_encode(array('data' => $data, 'message' => $res));
    }
}
