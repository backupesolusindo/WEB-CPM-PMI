<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function NamaAPP()
{
  return "ABSENSI POOLIJE";
}
function Alamat()
{
  return "Jl. R.A. Kartini No.64, Tembaan, Kepatihan, Kec. Kaliwates";
}

function Rupiah($data)
{
  return "Rp. ".number_format($data,0,".",".");
}

function MataUang($data)
{
  return number_format($data,0,".",".");
}


 ?>
