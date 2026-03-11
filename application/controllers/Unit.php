<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelUnit");
  }

  function index()
  {
    $data = array(
    'title'  => 'UNIT',
    'body'   => 'Unit/list' ,
    'unit'   => $this->ModelUnit->get_unit()->result(),
  );
    $this->load->view('index', $data);
  }

  function cek_unit()
  {
    $kantor = $this->db->get("kampus")->result();
    foreach ($kantor as $value) {
      $cek_unit = $this->ModelUnit->get_edit($value->idkampus);
      if ($cek_unit->num_rows() > 0) {
        $data = array(
          "nama_unit"   => $value->nama_kampus
        );
        $this->db->where("idunit", $value->idkampus);
        $this->db->update("unit", $data);
      }else {
        $data = array(
          "idunit"      => $value->idkampus,
          "nama_unit"   => $value->nama_kampus,
          "jenis"       => "CABANG",
          "level"       => "1",
          "parent_unit" => "LKM BKD PUSAT",
          "status"      => "1",
        );
        $this->db->insert("unit", $data);
      }
    }
  }

  function input()
  {
    $data = array(
      'title'=> 'FORM INPUT UNIT',
      'form' => 'Unit/form',
      'body' => 'Unit/input',
    );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $data = array(
      'nama_unit'        => $this->input->post("nama_unit"),
    );
    if ($this->db->insert('unit', $data)) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Berhasil","type" => "success" ));
      redirect(base_url().'Unit');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Tambah Ulang","type" => "danger" ));
      redirect(base_url().'Unit');
    }
  }

  function edit($idunit)
  {
    $data = array(
      'title' => 'FORM EDIT UNIT',
      'form'  => 'Unit/form',
      'body'  => 'Unit/edit' ,
      'unit'  => $this->ModelUnit->get_edit($idunit)->row_array(),
    );
    $this->load->view('index', $data);
  }

  function update()
  {
    $data = array(
      'nama_unit'        => $this->input->post("nama_unit"),
    );
    $this->db->where("idunit", $this->input->post("idunit"));
    if ($this->db->update('unit', $data)) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Edit Data Berhasil","type" => "success" ));
      redirect(base_url().'Unit');
    } else {
      $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Edit Ulang","type" => "danger" ));
      redirect(base_url().'Unit');
    }
  }

  function sembunyikan()
  {
    $data = array(
      'status'        => "0",
    );
    $this->db->where("idunit", $this->input->post("idunit"));
    if ($this->db->update('unit', $data)) {
      echo "berhasil";
    } else {
      echo "gagal";
    }
  }

  function tampilkan()
  {
    $data = array(
      'status'        => "1",
    );
    $this->db->where("idunit", $this->input->post("idunit"));
    if ($this->db->update('unit', $data)) {
      echo "berhasil";
    } else {
      echo "gagal";
    }
  }

  function hapus($idunit)
  {
      $this->db->where("idunit", $idunit);
      if ($this->db->delete('unit')) {
        $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Hapus Data Berhasil","type" => "success" ));
        redirect(base_url().'Unit');
      } else {
        $this->session->set_flashdata('notifJS', array('heading' => "Gagal",'text'=>"Mohon Untuk Melakukan Hapus Ulang","type" => "danger" ));
        redirect(base_url().'Unit');
      }
  }

  function sinkron()
  {
    $credentials = $this->core->getAccessApi();

      $param = array(
        "informations"      => false,
        "with_informations" => false,
      );
      $curl = curl_init("http://api.polije.ac.id/resources/global/unit"."?".http_build_query($param));
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer ".$credentials['accessToken'], "User-Agent: ".strtolower($_SERVER['HTTP_USER_AGENT'])));

      $json_response = curl_exec($curl);
      curl_close($curl);

    $data = array(
      'size' => sizeof(json_decode($json_response)),
      'res'  => json_decode($json_response)
    );
    // echo json_encode($data);
    foreach (json_decode($json_response) as $api) {
      $status = 0;
      if ($api->status_unit == "Aktif") {
        $status = 1;
      }
      $arUpdate["status"] = 0;
      $arUpdate = array(
        'nama_unit'     => $api->unit,
        'jenis'         => $api->jenis,
        'level'         => $api->level,
        'parent_unit'   => $api->parent_unit,
        'status'        => $status
      );

      if ($this->db->get_where("unit",array('nama_unit' => $api->unit))->num_rows() < 1) {
        $this->db->insert("unit", $arUpdate);
      }else {
        $this->db->where("nama_unit",$api->unit);
        $this->db->update("unit", $arUpdate);
      }
    }
    $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Sinkronisasi Data Pegawai "));
    redirect('Unit');
  }

}
