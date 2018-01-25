<?php

session_start();
require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

//conectar();

class PDF extends FPDF {

    function historicoConsulta_header() {
        $this->SetY("-1");
        $this->SetAutoPageBreak(true, 30);
        $historicoConsultaArray = $_SESSION['historicoConsulta']['historicoConsultaArray'];

        $this->SetFont('Arial', 'B', 10);
        //$this->AddPage();
        $this->Cell(0, 5, utf8_decode(strtoupper("CLIMEP")), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, utf8_decode($historicoConsultaArray['data']), 0, 0, 'R');
        $this->Ln();
        $this->Cell(40, 5, utf8_decode(strtoupper("histÓria clÍnica de ")), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(0, 5, utf8_decode(strtoupper($historicoConsultaArray['nomeCliente'])), 0, 0, 'L');
        $this->Cell(0, 5, utf8_decode($historicoConsultaArray['matricula']), 0, 0, 'R');
        $this->Ln();
        $this->Cell(0, 5, '', 'B', 1, 'R');
        $this->Rect(10, 25, 190, 55);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 6, utf8_decode("Antecedentes familiares"), 0, 0, 'L');
        $this->Ln();
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(0, 5, utf8_decode(html_entity_decode($historicoConsultaArray['antecedentesFamiliares'])), 0, "L", false, 12);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 6, utf8_decode("Antecedentes pessoais"), 0, 0, 'L');
        $this->Ln();
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(0, 5, utf8_decode($historicoConsultaArray['antecedentesPessoal']), 0, "L", false, 12);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 6, utf8_decode("Alergias"), 0, 0, 'L');
        $this->Ln();
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(0, 5, utf8_decode($historicoConsultaArray['alergias']), 0, "L", false, 12);
        $this->SetFont('Arial', 'B', 9);
        $this->Ln(20);
        $this->Cell(0, 5, utf8_decode("História"), 0, 0, 'L');
        $this->Ln();
        $this->Cell(0, 0, '', 'B', 1, 'L');
        $this->Ln(2);
    }    

    function historicoConsulta() {

        $this->historicoConsulta_header();
        $historicoConsultaArray = $_SESSION['historicoConsulta']['historicoConsultaArray'];

        for ($i = sizeof($historicoConsultaArray['consultasData']) - 1; $i >= 0; $i--) {
            $this->SetFont('Arial', 'B', 9);
            $this->Cell(20, 5, utf8_decode($historicoConsultaArray['consultasData'][$i]), 0, 0, 'L');
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 5, utf8_decode($historicoConsultaArray['medico'][$i] . "  CRM " . $historicoConsultaArray['crm'][$i]), 0, 0, 'R');
            $this->SetFont('Arial', '', 9);
            $this->Ln(6);
            $this->MultiCell(0, 5, html_entity_decode(utf8_decode($historicoConsultaArray['consultas'][$i])), 0, "L", false, 12);
            $this->Ln(7);
            $this->Cell(0, 5, utf8_decode("Hipótese Diagnóstica"), 0, 0, 'L');
            $this->Ln();
            $this->MultiCell(0, 5, utf8_decode($historicoConsultaArray['hipotese'][$i]), 0, "L", false, 12);
            $this->Ln(1);
            $this->Cell(0, 5, '', 'B', 1, 'R');
            $this->Ln(2);
        }        
        $this->Ln(2);
        $this->SetFont('Arial', '', 9);        
    }
    
    function Footer() {
        $historicoConsultaArray = $_SESSION['historicoConsulta']['historicoConsultaArray'];        

        $this->Ln();
        $this->SetFont('Arial', 'B', 8);

        $this->SetY(275);

        $this->Cell(0, 5, utf8_decode(strtoupper($historicoConsultaArray['nomeCliente'])), 0, 0, 'R');        
    }

}

$pdf = new PDF();
$pdf->SetMargins(10, 10, 10);
$pdf->historicoConsulta();
$pdf->Output("historico.pdf", "I");
