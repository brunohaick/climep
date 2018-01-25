<?php

session_start();
require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

//conectar();

class PDF extends FPDF {

    function AfastaCell($espacamento) {
        $this->Cell($espacamento, 0, '', '');
    }

    function reciboConvenio() {

        global $dados;
        $date = date('w d m Y');
        $date = explode(' ', $date);
        $this->SetFont('Arial', 'BU', 18);


        $this->AddPage();
        $this->Cell(0, 5, utf8_decode('RECIBO DE CONVÊNIO'), 0, 0, 'C', false);
        $this->Ln();
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 5, utf8_decode('FAT.Nº' . $dados['id'] . '/'), 0, 0, 'R', false);
        $this->Ln();
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 5, 'R$ ' . number_format($dados['valorPago'], 2, ',', '.'), 0, 0, 'R', false);
        $this->Ln(10);
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(0, 5, utf8_decode("    Recebemos da " . $dados['nomeConvenio'] . ", a supra importância de R$ " . number_format($dados['valorPago'], 2, ',', '.') . " (" . valorPorExtenso(number_format($dados['valorPago'], 2, ',', '.')) . "), referente a serviços médicos prestados aos seus funcionários."), 0, "L", false, 12);
        $this->Ln();
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, utf8_decode('Belém(Pa), ' . mostraSemana($date[0]) . ', ' . $date[1] . ' de ' . mostraMes($date[2]) . ' de ' . $date[3]), 0, 0, 'R', false);
        $this->Ln(15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, utf8_decode('__________________________________________________________________'), 0, 0, 'C', false);
        $this->Ln();
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, utf8_decode('Clínica de Medicina Preventiva do Pará S/C Ltda, CNPJ nº'), 0, 0, 'C', false);
        $this->Ln();
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, utf8_decode('05.083.142/0001-83'), 0, 0, 'C', false);
    }

}

$dados = $_SESSION['FaturaReciboConvenio'];
$pdf = new PDF();
$pdf->SetMargins(20, 40, 20);
$pdf->reciboConvenio();
$pdf->Output("reciboConvenio.pdf", "I");
