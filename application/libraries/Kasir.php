<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kasir {

  public function method()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    // include_once APPPATH . '/third_party/fpdf/fpdf.php';
    include APPPATH . '/third_party/Fpdf/fpdf.php';
  }

  public function MataUangK($value)
  {
    return substr($value, 0, -3)."K";
  }

  public function MataUangRp($value)
  {
    return "Rp. ".number_format($value,0,",",".");
  }

  public function Curency($value)
  {
    return number_format($value,0,",",".");
  }

}
