<?php

session_start();
require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

//conectar();

class PDF extends FPDF {
    
    function prestadorServico() {
        $this->SetY("-1");        
        $this->SetAutoPageBreak(true, 30);
        $this->SetFont('Arial', '', 12);
        $pserv = $_SESSION['prestadorServico']['prestadorServicoText'];          
        $this->Cell(0, 5, "Solicito", 0, 0, "L");
        $this->Ln(6);
        $this->Cell(0, 5, $pserv['nome'], 0, 0, "L");
        $this->Ln(20);
        $this->Cell(0, 5, "NESTA", 0, 0, "L");
        $this->Ln(20);
        $this->Cell(0, 5, "Solicito para", 0, 0, "L");
        $this->Ln(10);
        $this->Cell(0, 5, $pserv['nomeCliente'], 0, 0, "L");
        $this->Ln(10);        
        $this->MultiCell(0, 5, $pserv['encaminhamento'], 0, "L", false, 12);  
    }        

}

$pdf = new PDF();
$pdf->SetMargins(40, 10, 10);
$pdf->prestadorServico();
$pdf->Output("prestserv.pdf", "I");
