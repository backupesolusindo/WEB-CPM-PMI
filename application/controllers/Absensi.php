<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model("ModelAbsensi");
    $this->load->model("ModelPegawai");
    $this->load->model("ModelJadwalMasuk");
    $this->load->model("ModelKegiatan");
  }

  function input()
  {
    $data = array(
      'title'     => 'Presensi',
      'body'      => 'Absensi/input_presensi' ,
      'pegawai'   => $this->ModelPegawai->get_list()->result(),
      'jadwal'    => $this->ModelJadwalMasuk->get_all_jadwalmasuk()->result(),
    );
    $this->load->view('index', $data);
  }

  function approval($idabsensi, $uuid)
  {
    $this->db->where("pegawai_uuid", $uuid);
    if ($this->db->update("absensi",array("status_absensi"=>1))) {
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Berhasil"));
    }else {
      $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal"));
    }
    redirect(base_url().'Laporan/DetailRekap/'.$uuid);
  }

  function insert_presensi()
  {
    $patch = "document/foto_absen_web/";
    // echo $patch;
    $config['upload_path']          = "./".$patch;
    $config['allowed_types']        = 'gif|jpg|png|jpeg';
    $config['max_size']             = 11240;

    $this->load->library('upload', $config);
    $this->upload->initialize($config);
    $foto = "desain/Login/logo.png";
    if ($this->upload->do_upload('foto')) {
      $foto = $patch.$this->upload->data()['file_name'];
    }

    $jadwal = $this->ModelJadwalMasuk->get_edit($this->input->post("idjadwal"))->row_array();
    $jam_jadwal   = date("H:i:s", strtotime($jadwal["jam_masuk"]));
    $jenis_absen = "1";
    if ($this->input->post("jenis_tempat") == "2") {
      $jenis_absen = "4";
    }
    $data = array(
    'waktu'         => date("Y-m-d H:i:s", strtotime($this->input->post("tanggal")." ".$this->input->post("jam_masuk"))),
    'jam_jadwal'    => $jam_jadwal,
    'idjadwal'      => $this->input->post("idjadwal"),
    'pegawai_uuid'  => $this->input->post("uuid"),
    'latitude'      => "-8.159477",
    'longitude'     => "113.722460",
    'jenis_absen'   => $jenis_absen,
    'jenis_tempat'  => $this->input->post("jenis_tempat"),
    'foto'          => $foto
    );
    $get_datang = $this->ModelAbsensi->cek_Absensi($this->input->post("uuid"), date("Y-m-d", strtotime($this->input->post("tanggal"))));
    if ($get_datang->num_rows() > 0) {
      $get_datang = $get_datang->row_array();
      $this->db->where("idabsensi",$get_datang['idabsensi']);
      $this->db->update("absensi",$data);
      $get_pulang = $this->ModelAbsensi->get_AbsensiPulang($get_datang['idabsensi']);
      $data_pulang = array(
      'waktu'         => date("Y-m-d H:i:s", strtotime($this->input->post("tanggal")." ".$this->input->post("jam_pulang"))),
      'pegawai_uuid'  => $this->input->post("uuid"),
      'absensi_idabsensi'  => $get_datang['idabsensi'],
      'latitude'      => "-8.159477",
      'longitude'     => "113.722460",
      'foto'          => $foto
      );
      if ($get_pulang->num_rows() > 0) {
        $this->db->where("absensi_idabsensi",$get_datang['idabsensi']);
        $this->db->update("absensi_pulang",$data_pulang);
      }else {
        $this->db->insert("absensi_pulang", $data_pulang);
      }
      $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Update Data Berhasil"));
      redirect(base_url().'Absensi/input');
    }else {
      if ($this->db->insert("absensi", $data)) {
        $id_insert = $this->db->insert_id();
        $data_pulang = array(
        'waktu'         => date("Y-m-d H:i:s", strtotime($this->input->post("tanggal")." ".$this->input->post("jam_pulang"))),
        'pegawai_uuid'  => $this->input->post("uuid"),
        'absensi_idabsensi'  => $id_insert,
        'latitude'      => "-8.159477",
        'longitude'     => "113.722460",
        'foto'          => $foto
        );
        if ($this->db->insert("absensi_pulang", $data_pulang)) {
          $this->db->where("uuid", $this->input->post("uuid"));
          $this->db->update("pegawai", array("status_absen"=>"2"));
        }
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
        redirect(base_url().'Absensi/input');
      }else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
        redirect(base_url().'Absensi/input');
      }
    }
  }

  function input_lembur()
  {
    $data = array(
    'title'     => 'Presensi',
    'body'      => 'Absensi/input_lembur' ,
    'pegawai'   => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $data);
  }

  function insert_lembur()
  {
    $idlembur = $this->input->post("idlembur");
    $data = array(
    'jam_presensi'          => date("Y-m-d H:i:s", strtotime($this->input->post("tanggal")." ".$this->input->post("jam_mulai"))),
    'jam_presensi_selesai'  => date("Y-m-d H:i:s", strtotime($this->input->post("tanggal")." ".$this->input->post("jam_selesai"))),
    'lembur_idlembur'       => $idlembur,
    'pegawai_uuid'          => $this->input->post("uuid"),
    'absen_latitude'        => "-8.159477",
    'absen_longtitude'      => "113.722460",
    'status_lokasi'         => "1",
    'foto'                  => "desain/Login/logo.png"
    );
    $get_presensi = $this->ModelAbsensi->cek_presensi_lembur($idlembur, $this->input->post("uuid"), date("Y-m-d", strtotime($this->input->post("tanggal"))));
    if ($get_presensi->num_rows() > 0) {
      $get_presensi = $get_presensi->row_array();
      $this->db->where("idabsen_lembur", $get_presensi['idabsen_lembur']);
      if ($this->db->update("absen_lembur", $data)) {
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Merubah Data Berhasil"));
        redirect(base_url().'Laporan/detailKegiatanLembur/'.$idlembur);
      }else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
        redirect(base_url().'Absensi/input_lembur'.$idlembur);
      }
    }else {
      if ($this->db->insert("absen_lembur", $data)) {
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
        redirect(base_url().'Laporan/detailKegiatanLembur/'.$idlembur);
      }else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
        redirect(base_url().'Absensi/input_lembur'.$idlembur);
      }
    }
  }

  function input_kegiatan($idkegiatan)
  {
    $idkegiatan = $this->core->decrypt_url($idkegiatan);
    $data = array(
    'title'     => 'Presensi',
    'body'      => 'Absensi/input_kegiatan' ,
    'idkegiatan'=> $idkegiatan,
    'kegiatan'  => $this->ModelKegiatan->get_data($idkegiatan)->row_array(),
    'pegawai'   => $this->ModelPegawai->get_list()->result(),
    );
    $this->load->view('index', $data);
  }

  function insert_kegiatan()
  {
    $idkegiatan = $this->input->post("idkegiatan");
    $foto = "desain/Login/logo.png";
    $patch = "document/foto_absen_web/";
    // echo $patch;
    $config['upload_path']          = "./".$patch;
    $config['allowed_types']        = 'gif|jpg|png|jpeg';
    $config['max_size']             = 11240;

    $this->load->library('upload', $config);
    $this->upload->initialize($config);
    if ($this->upload->do_upload('foto')) {
      $foto = $patch.$this->upload->data()['file_name'];
    }
    $data = array(
    'jam_presensi'         => date("Y-m-d H:i:s", strtotime($this->input->post("tanggal")." ".$this->input->post("jam_mulai"))),
    'kegiatan_idkegiatan'  => $idkegiatan,
    'pegawai_uuid'         => $this->input->post("uuid"),
    'absen_latitude'       => $this->input->post("lat"),
    'absen_longtitude'     => $this->input->post("long"),
    'status_lokasi'        => "1",
    'foto'                 => $foto
    );
    $get_presensi = $this->ModelAbsensi->cek_presensi_kegiatan($idkegiatan, $this->input->post("uuid"), date("Y-m-d", strtotime($this->input->post("tanggal"))));
    if ($get_presensi->num_rows() > 0) {
      $get_presensi = $get_presensi->row_array();
      $this->db->where("idabsen_kegiatan", $get_presensi['idabsen_kegiatan']);
      if ($this->db->update("absen_kegiatan", $data)) {
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Merubah Data Berhasil"));
      }else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
      }
    }else {
      if ($this->db->insert("absen_kegiatan", $data)) {
        $this->session->set_flashdata('notifJS', $this->core->NotifSuccess("Selamat Berhasil Tambah Data Berhasil"));
      }else {
        $this->session->set_flashdata('notifJS', $this->core->NotifError("Gagal Mohon Untuk Melakukan Tambah Ulang"));
      }
    }
    redirect(base_url().'Absensi/input_kegiatan/'.$this->core->encrypt_url($idkegiatan));
  }

  // function approval($idabsensi, $uuid)
  // {
  //   $this->db->where("idabsensi", $idabsensi);
  //   if ($this->db->update("absensi", array('status_absensi' => '1'))) {
  //     $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Berhasil","type" => "success" ));
  //   }else {
  //     $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Gagal","type" => "success" ));
  //   }
  //   redirect(base_url().'Laporan/DetailRekap/'.$uuid);
  // }

  function ditolak($idabsensi, $uuid)
  {
    $this->db->where("idabsensi", $idabsensi);
    if ($this->db->update("absensi", array('status_absensi' => '2'))) {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Berhasil","type" => "success" ));
    }else {
      $this->session->set_flashdata('notifJS', array('heading' => "Berhasil",'text'=>"Tambah Data Gagal","type" => "success" ));
    }
    redirect(base_url().'Laporan/DetailRekap/'.$uuid);
  }


}
