<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelUnit extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  public function get_unit()
  {
    $this->db->order_by("level");
    return $this->db->get("unit");
  }

  public function get_parent_unit()
  {
    $this->db->order_by("level");
    $this->db->where("status","1");
    // $this->db->or_where("level","1");
    return $this->db->get("unit");
  }

  public function get_parent_unit_sub()
  {
    $this->db->order_by("level");
    $this->db->where("level","1");
    return $this->db->get("unit");
  }

  public function get_sub_unit($parent = null)
  {
    if ($parent != null || $parent != "") {
      $this->db->where("parent_unit", $parent);
    }
    // $this->db->where("level","2");
    return $this->db->get("unit");
  }

  public function get_edit($idunit)
  {
    $this->db->where("idunit", $idunit);
    return $this->db->get("unit");
  }

  function hirarki_parentunit($unit)
  {
    $this->db->where("nama_unit", $unit);
    $this->db->where("status = 1");
    $this->db->order_by("level");
    return $this->db->get("unit");
  }
  function hirarki_unit($unit)
  {
    $this->db->where("parent_unit", $unit);
    $this->db->where("status = 1");
    $this->db->where("nama_unit != 'KANTOR POLIJE'");
    $this->db->order_by("level");
    return $this->db->get("unit");
  }

}
