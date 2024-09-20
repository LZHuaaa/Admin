<?php 
  
require('fpdf/fpdf.php'); 
  
// portrait(orientation), milimeter, A4 size 
$pdf = new FPDF('P','mm','A4'); 
  
// Add new pages. By default no pages available. 
$pdf->AddPage(); 
  
// Set font format and font-size 
$pdf->SetFont('Times', 'B', 20); 
  

// Framed rectangular area 
//width, height, text, border, line......
$pdf->Cell(71, 10, '', 0, 0); 
$pdf->Cell(59, 5, 'Invoice', 0, 0); 
$pdf->Cell(59, 10, '', 0, 1); 
  
// Set it new line 
$pdf->Ln(); 

$pdf->SetFont('Times', 'B', 15); 
$pdf->Cell(71,5,'WET',0,0);
$pdf->Cell(59,5,'',0,0);
$pdf->Cell(59,5,'Details',0,1);

$pdf->SetFont('Times', '', 10); 

$pdf->Cell(130,5,'Near DAV',0,0);
$pdf->Cell(25,5,'Customer Name',0,0);
$pdf->Cell(59,5,'Lee Zhi Hua',0,1);

$pdf->Cell(130,5,'City. 75001',0,0);
$pdf->Cell(25,5,'Payment Date:',0,0);
$pdf->Cell(59,5,'12th Jan 2019',0,1);

$pdf->Cell(130,5,'',0,0);
$pdf->Cell(25,5,'Receipt No:',0,0);
$pdf->Cell(59,5,'ORD0001',0,1);
  
// Framed rectangular area 
$pdf->Cell(176, 10, 'A Computer Science Portal for geek!', 0, 0, 'C'); 
  
// Close document and sent to the browser 
$pdf->Output(); 
  
?> 