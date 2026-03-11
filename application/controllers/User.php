<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller{

  public $data_user = array();

  public function __construct()
  {
    parent::__construct();
    $this->data_user = array(
      'Nama'    => $this->input->post('username'),
      'Jabatan' => $this->input->post('jabatan'),
      'Password'=> password_hash($this->input->post('password'),PASSWORD_DEFAULT,array("cost"=>10)),
      'pegawai_NIK'=> $this->input->post('nik'));

      $this->load->model('ModelUsers');
      $this->load->model('ModelPegawai');
      $this->load->model('ModelJabatan');
      $this->load->model('ModelUnit');
      // $this->load->model('ModelLogin');
  }

  function index()
  {
    $data = array(
      'title'         => "List User",
      'body'          => 'User/list',
      'user'          => $this->ModelUsers->get_data()
     );
    $this->load->view('index', $data);
  }

  function input()
  {
    $data = array(
      'form'    => 'User/form',
      'body'    => 'User/input',
      'title'   => "Tambah User",
      'pegawai' => $this->ModelPegawai->get_list()->result(),
      'group'   => $this->ModelUsers->get_group()->result(),
      'jabatan' => $this->ModelJabatan->get_data()->result(),
      'unit'    => $this->ModelUnit->get_parent_unit()->result(),
     );
    $this->load->view('index', $data);
  }

  function insert()
  {
    $pass = $this->input->post('password');
    $repass = $this->input->post('repassword');
    $hitung = $this->ModelUsers->total_rows($this->input->post('username'));
    if ($hitung >= 1) {
      $this->session->set_flashdata('error', $this->core->alert_danger('Username Telah digunakan'));
      redirect('User/input');
    } else {
      if ($pass == $repass) {
        $nama = $this->input->post('username');
        $peg = $this->ModelPegawai->edit($this->input->post('nik'));
        if ($peg->num_rows() > 0) {
          $nama = $peg->row_array()['nama_pegawai'];
        }
          if ($this->db->insert('user', array(
                'username'   => $this->input->post('username'),
                'nama'    => $nama,
                'Jabatan' => $this->input->post('jabatan'),
                'Password'=> password_hash($this->input->post('password'),PASSWORD_DEFAULT,array("cost"=>10)),
                'pegawai_NIK'=> $this->input->post('nik'),
                'create_on' => date('Y-m-d H:i:s'),
                'unit_user' => $this->input->post('unit'),
                'roles'   => implode(", ", $this->input->post('roles')))
              ))
          {
            $this->session->set_flashdata('alert', $this->core->alert_succcess('Berhasil Tersimpan'));
            redirect('User');
          } else {
            $this->session->set_flashdata('alert', $this->core->alert_danger('Gagal Tersimpan'));
            redirect('User/input');
          }
      }else {
        $this->session->set_flashdata('error', $this->core->alert_danger('password tidak cocok'));
        // $this->session->set_flashdata($data_user);
        redirect('User/input');
      }
    }
  }

  function edit()
  {
    $id = $this->uri->segment(3);
    $user = $this->ModelUsers->get_data_user($id);
    $data = array(
      'form'    => 'User/form',
      'body'    => 'User/edit',
      'title'   => 'Edit User',
      'pegawai' => $this->ModelPegawai->get_list()->result(),
      'user'    => $user,
      'group'   => $this->ModelUsers->get_group()->result(),
      'jabatan' => $this->ModelJabatan->get_data()->result(),
      'unit'    => $this->ModelUnit->get_parent_unit()->result(),
      'j_roles' => explode(', ', $user['roles']),
     );
    $this->load->view('index', $data);
  }

  function update()
  {
    $id = $this->input->post('id');
    $user_name = $this->ModelUsers->get_data_user($id);
    $pass = $this->input->post('password');
    $repass = $this->input->post('repassword');
    $hitung = $this->ModelUsers->total_rows($this->input->post('username'));
      if ($pass == $repass) {
              $this->db->where('id_user',$id);
          if ($this->db->update('user', array(
                'nama'    => $this->input->post('nama'),
                'username'=> $this->input->post('username'),
                'jabatan' => $this->input->post('jabatan'),
                'password'=> password_hash($this->input->post('password'),PASSWORD_DEFAULT,array("cost"=>10)),
                'update_on' => date('Y-m-d H:i:s'),
                'unit_user' => $this->input->post('unit'),
                'roles'   => implode(", ", $this->input->post('roles')))
              ))
          {
            $this->session->set_flashdata('alert', $this->core->alert_succcess('Berhasil Tersimpan'));
            redirect('User');
          } else {
            $this->session->set_flashdata('alert', $this->core->alert_danger('Gagal Tersimpan'));
            redirect('User/edit/'.$id);
          }
      }else {
        $this->session->set_flashdata('error', $this->core->alert_danger('password tidak cocok'));
        redirect('User/edit/'.$id);
      }
  }


  function hapus($id)
	{
		// $id = $this->input->post('id');
		$this->db->where('id_user', $id);
		$delete = $this->db->delete('user');
		if ($delete == true) {
				$this->session->set_flashdata('alert', $this->core->alert_succcess('Berhasil Hapus Data Pegawai'));
		}else{
				$this->session->set_flashdata('alert', $this->core->alert_succcess('Gagal Hapus Data Pegawai, Data masih Terrelasi!!!'));
		};
		redirect('User');
	}

}
