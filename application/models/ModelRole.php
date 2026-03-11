<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelRole extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $link_Roles = $this->uri->segment(1);
    $user_Roles = $this->db->get_where('user',array('id_user' => $_SESSION['id_login'], ))->row_array();
    $jabatan_Roles = $_SESSION['jabatan'] ;

    $level_Roles[$jabatan_Roles] = array();
    $roles_Roles = explode(', ', $user_Roles['roles']);
    foreach ($roles_Roles as $value_Roles) {
      array_push($level_Roles[$jabatan_Roles], $value_Roles);
    }
    $level_Roles[0] = array(
      "all",
    );
    $Menu_Roles['Roles'] = "true";
    $access = false;
    for ($i=0; $i < sizeof($level_Roles[$jabatan_Roles]); $i++) {
      if ($level_Roles[$jabatan_Roles][$i] == $link_Roles) {
        $access = true;
      } elseif ($level_Roles[$jabatan_Roles][$i] == "all") {
        $access = true; break;
      } elseif ($link_Roles == "") {
        $access = true; break;
      }
    };
    if (!$access) {
      // redirect('Permission');
    }
  }

}
