<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PrintData extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    require_once APPPATH.'third_party/fpdf/fpdf.php';
    // $this->load->library('Pdf');
    $this->load->library('Pdf');
  }

  function index(){

      // $pdf = new FPDF ('P', 'mm', 'A5');
      //
      //
      // $pdf->AddPage();
      //
      //
      // $pdf->SetFont('Arial', 'B',16);
      // $pdf->Cell(0,7, 'AYAM GEPHOK',0,1,'C');
      // $pdf->SetFont('Arial', 'B',12);
      // $pdf->Cell(0,7, 'Jl. Mastrip I No. 19A',0,1,'C');
      // $pdf->Cell(0,7, '----JEMBER----',0,1,'C');
      // $pdf->Cell(0,7, '----------------------------------------------------------------------------------------------------',0,1,'C');
      //
      //
      // // $pdf->Cell(0,7,'',0,1);
      // //
      // $pdf->SetFont('Arial', 'B',10);
      // $pdf->Cell(50,6, 'Barang',0,0,'L');
      // $pdf->Cell(50,6, 'Harga',0,0,'R');
      //
      // $pdf->Cell(50,7,'',0,1);
      // $pdf->Cell(50,7,'',0,1);
      // $pdf->SetFont('Times', '',10);
      // $pdf->Cell(50,6, 'Beras',0,0,'L');
      // $pdf->Cell(50,6, 'Rp. 15.000',0,1,'R');
      // $pdf->Cell(50,6, 'Gula',0,0,'L');
      // $pdf->Cell(50,6, 'Rp. 17.000',0,1,'R');
      // $pdf->Cell(50,6, 'Tisu',0,0,'L');
      // $pdf->Cell(50,6, 'Rp. 2.500',0,0,'R');
      //
      //
      // $pdf->Cell(50,7,'',0,1);
      // $pdf->Cell(50,7,'',0,1);
      // $pdf->Cell(50,7,'',0,1);
      // $pdf->SetFont('Arial', 'B',12);
      // $pdf->Cell(0,7, '----------------------------------------',0,1,'R');
      // $pdf->SetFont('Arial', 'B',10);
      // $pdf->Cell(0,7, 'Total :',0,0,'C');
      // $pdf->SetFont('Times', '',10);
      // $pdf->Cell(0,7, 'Rp. 34.500 :',0,1,'R');
      // $pdf->SetFont('Arial', 'B',10);
      // $pdf->Cell(0,7, 'Tunai :',0,0,'C');
      // $pdf->SetFont('Times', '',10);
      // $pdf->Cell(0,7, 'Rp. 50.0000 :',0,1,'R');
      // $pdf->Cell(50,7,'',0,1);
      // $pdf->Cell(50,7,'',0,1);
      // $pdf->Cell(50,7,'',0,1);
      // $pdf->Cell(50,7,'',0,1);
      // $pdf->SetFont('Arial', 'B',12);
      // $pdf->Cell(0,7, 'Terima Kasih',0,1,'C');
      // $pdf->Cell(0,7, '======== Selamat Datang Kembali ========',0,1,'C');
    }
    $Pdf->Output();
  }
