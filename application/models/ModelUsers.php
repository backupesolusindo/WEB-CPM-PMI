<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelUsers extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function get_data()
  {
    return $this->db->get('user')->result();
  }

  function get_account($nik)
  {
    $this->db->where('pegawai_NIK',$nik);
    return $this->db->get('user');
  }

  function get_data_user($id)
  {
    return $this->db->get_where('user', array('id_user' => $id, ))->row_array();
  }

  function get_data_edit($id){
      return $this->db->get_where('user', array('karyawan_id'=>$id));
  }

  function cek_username($username){
      // $this->db->join("pegawai","pegawai.uuid = user.pegawai_NIK");
      return $this->db->get_where("user",array('username'=>$username));
  }

   function total_rows($username) {
       return $this->db->get_where('user', array('username'=>$username))->num_rows();
  }

  function get_data_login($id){
      $this->db->select("pegawai.*, user.*, outlet.nama as nama_outlet");
      $this->db->join('pegawai',"pegawai.NIK = user.pegawai_NIK");
      $this->db->join('outlet',"outlet.idoutlet = user.outlet_idoutlet");
      return $this->db->get_where("user",array("id_user"=>$id));
  }

  function get_group()
  {
      return $this->db->get('group_roles');
  }

  function get_group_edit($id)
  {
      $this->db->where('idgroup_roles', $id);
      return $this->db->get('group_roles');
  }

  function get_roles($group)
  {
      return $this->db->get_where('roles', array('group_roles_idgroup_roles' => $group));
  }

  function roles()
  {
      $this->db->join('group_roles', 'group_roles.idgroup_roles = roles.group_roles_idgroup_roles');
      return $this->db->get('roles');
  }

  public function save_riwayat($id_login, $tgl, $menu)
  {
    $data = array(
      'user_id_user '     => $id_login,
      'tgl_jam'           => $tgl,
      'menu_yang_diakses' => $menu
     );
     $this->db->insert('riwayat_akses', $data);
  }


}
