<?php
require ('plugin/pdf/fpdf.php');
$pdf = new FPDF ('P', 'mm', 'Letter');

$pdf->AddPage();

$pdf->SetFont('Arial', 'B', '14');
$pdf->Cell(25,15, 'LAPORAN PENJUALAN', 0,1,'C');

$pdf->Cell(10,7, '',0,1);
$pdf->SetFont('Times', 'B', '12');

$pdf->Cell(8,6, 'No',1,0,'C');
$pdf->Cell(15,6, 'ID Penjualan',1,0,'C');
$pdf->Cell(50,6, 'Barang',1,0,'C');
$pdf->Cell(30,6, 'Harga',1,0,'C');
$pdf->Cell(100,6, 'Nama Kasir',1,0,'C');
$pdf->Cell(25,6, 'Tanggal',1,0,'C');

// $pdf-SetFont('Times', '', '12');



 ?>
