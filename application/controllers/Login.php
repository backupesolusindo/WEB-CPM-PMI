<?php
class Login extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('ModelUsers');
		$this->load->model('ModelPegawai');
		date_default_timezone_set('Asia/Jakarta');
	}

	function index(){
		$this->session->set_flashdata('item', 'value');
		$this->load->view('Login/Login');
	}

	function aksi_login(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		// $username = "admin";
		// $password = "asd";
		$cek = $this->ModelUsers->cek_username($username)->num_rows();
		if($cek > 0){
    		$data_login = $this->ModelUsers->cek_username($username)->row_array();
            if ($data_login['id_user']==null) {
                $this->session->set_flashdata("message", "Username Tidak Terdaftar");
								echo "u";
            }else{
                $pw_valid = $data_login['password'];
								$nik  = $data_login["pegawai_NIK"];
                if (password_verify($password, $pw_valid)) {
                    $id_login = $data_login['id_user'];
										$pegawai = $this->ModelPegawai->edit($data_login['pegawai_NIK'])->row_array();
                    $data_session = array(
                        'id_login' => $id_login,
                        'nik'      => $nik,
                        'username' => $data_login['username'],
                        'nama' 		 => $data_login['nama'],
                        'jabatan'  => $data_login['jabatan'],
                        'unit'  	 => $data_login['unit_user'],
                    );
                    $this->session->set_userdata($data_session);
										$data_riwayat = array(
											'NIK' 					=> $nik,
											'user_id_user'	=> $id_login,
										 	'last_login'		=> date('Y-m-d H:i:s'),
											'ip'						=> $this->input->ip_address()
										);
										$this->db->insert('riwayat_login', $data_riwayat);
										$this->db->reset_query();
										$this->db->where($data_riwayat);
										@$data_riwayat_login = @$this->db->get('riwayat_login')->row_array();
										$this->session->set_userdata(array('no_login'=>$data_riwayat_login['no_login']));
                    // redirect(base_url());
										echo base_url();
										// redirect(base_url().);
                    // echo "error";
                    // die("berhasil login");
                    }else{
                     $this->session->set_flashdata("message", "Password salah");
                    // redirect('Login');
										echo "p";
                  // die("password salah");
                }
            }
        }else{
            $this->session->set_flashdata("message","Username tidak terdaftar");
            // redirect('Login');
						echo "up";
            // die("username tidak ditemukan");
        }
			//echo "Username dan password salah !";
		//}
	}

	function logout(){
		$no_login = $_SESSION['no_login'];
		$this->db->where('no_login',$no_login);
		$this->db->update('riwayat_login', array('logout' => date('Y-m-d H:i:s')));
		$this->session->sess_destroy();
		redirect("login");
	}

	function pindah_sesi(){
		$data['shift'] = $this->db->get_where("shift")->result();
    $this->load->view("Login/akses",$data);
  }

  public function cek_sesi(){
    $sesi = $this->input->post("sesi");
    // $sesi = "PAGI";
    $cek_sesi = $this->db->get_where("absensi",
    array("DATE(tanggal)"=>date("Y-m-d"),'shift'=>$sesi,'user_id_user'=>$this->session->userdata("id_login")));
    if ($cek_sesi->num_rows() == 0) {
      $data = array(
				'tanggal' => date("Y-m-d H:i:s"),
				'shift' => $sesi,
				'user_id_user' => $this->session->userdata("id_login"),
			);
			$this->db->insert("absensi",$data);
    }
		$this->session->set_userdata(array("sesi"=>$sesi));
		echo 1;
    // echo $cek_sesi->num_rows();
  }

	public function simpan_kas(){
    $sesi = $this->session->userdata("sesi");
    $jumlah = $this->combine_harga($this->input->post("jumlah"));
    $data = array(
      'tanggal' => date("Y-m-d H:i:s"),
      'kas' => $jumlah,
      'sesi' => $sesi,
      'user_id_user' => $this->session->userdata("id_login")
    );
    if ($this->db->insert("sesi",$data)) {
      // $this->session->set_userdata(array("idsesi"=>$this->db->insert_id()));
      echo 1;
    }else{
      echo 0;
    }
  }

	public function coba()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		echo $username."  -  ".$password;
	}

	function combine_harga($harga)
  {
    $jml_bayar = "";
    $ex_jml_bayar = explode(",", $harga);
    for ($i=0; $i < count($ex_jml_bayar); $i++) {
      $jml_bayar = $jml_bayar."".$ex_jml_bayar[$i];
    }
    return $jml_bayar;
  }

}
