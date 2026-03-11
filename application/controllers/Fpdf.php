<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Fpdf extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->libraries('Pdf');
    }
    function index()
	{
        $pdf = new FPDF('P', 'mm','Letter');

        $pdf->AddPage();

        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,7,'DAFTAR PEMBELIAN',0,1,'C');
        $pdf->Cell(10,7,'',0,1);

        $pdf->SetFont('Arial','B',10);

        $pdf->Cell(8,6,'No',1,0,'C');
        $pdf->Cell(25,6,'ID Transaksi',1,0,'C');
        $pdf->Cell(100,6,'Nama Pembeli',1,0,'C');
        $pdf->Cell(12,6,'Tanggal',1,0,'C');
        $pdf->Cell(100,6,'Nama Kasir',1,0,'C');
        $pdf->Cell(35,6,'Pembelian',1,0,'C');

        $pdf->SetFont('Arial','',10);
        $coba_fpdf = $this->db->get('coba_fpdf')->result();
        $no=0;
        foreach ($coba_fpdf as $data){
            $pdf->Cell(8,6,$no,1,0);
            $pdf->cell(25,6,$data->id_transaksi,1,0);
            $pdf->Cell(100,6,$data->nama_pembeli,1,0);
            $pdf->Cell(12,6,date("Y-m-d", strtotime($this->input->post("tanggal"))),1,0);
            $pdf->Cell(100,6,$data->nama_kasir,1,0);
            $pdf->Cell(35,6,$data->pembelian,1,0);
            $no++;
        }
        $pdf->Output();
	}

  }
