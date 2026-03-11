<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelJabatan extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    //Codeigniter : Write Less Do More
  }
  public function get_jabatan_with_pekerjaan()
  {
    $this->db->select('jabatan.*, pekerjaan.*');
    $this->db->from('jabatan');
    $this->db->join('pekerjaan', 'pekerjaan.jabatan_idjabatan = jabatan.idjabatan', 'left');
    return $this->db->get()->result_array();
  }

  function get_data()
  {
    return $this->db->get('jabatan');
  }

  function get_jabatan_aktif()
  {
    $this->db->where("tampil", "1");
    return $this->db->get('jabatan');
  }

  function get_data_edit($id)
  {
    $this->db->where('idjabatan', $id);
    return $this->db->get('jabatan');
  }

  function get_nama_jabatan($id)
  {
    $this->db->where('idjabatan', $id);
    return $this->db->get('jabatan')->row_array()['namajabatan'];
  }


  
}
