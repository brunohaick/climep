<?php

session_start();
require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

//conectar();

class PDF extends FPDF {

    function atestadoConsulta() {
        //$this->SetY("-1");
        $this->SetAutoPageBreak(70, 32);
        $atestado = utf8_decode($_SESSION['atestadoConsulta']['atestadoConsultaText']);

        $this->SetFont('Arial', '', 12);
        $this->AddPage();
        $this->MultiCell(0, 5, $atestado, 0, "L", false, 12);  
    }        

}

$pdf = new PDF();
$pdf->SetMargins(40, 10, 10);
$pdf->atestadoConsulta();
$pdf->Output("atestado.pdf", "I");
