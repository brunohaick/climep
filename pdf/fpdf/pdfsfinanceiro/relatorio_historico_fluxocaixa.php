<?php

session_start();
require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

//conectar();

class PDF extends FPDF {

    function AfastaCell($espacamento) {
        $this->Cell($espacamento, 0, '', '');
    }

    function inserirLinhaPontilhada() {
        $this->Cell(0, 3, '----------------------------------------------------------------------------------------------------------------------', 0, 0, false);
    }

    function contarOcorrencias() {
        global $dados;

        return count($dados['fatura']);
    }

    function Header() {
        global $dados;

        $cabecalho = $dados['cabecalho'];
        $date = date('d/m/Y H:i:s');

        $this->SetFont('Courier', 'B', 8);
        $this->Cell(0, 3, utf8_decode('CLIMEP - RELATÓRIO DE HISTÓRICO - FLUXO DE CAIXA'), 0, 0, 'L', false);
        $this->SetFont('Courier', '', 8);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('CONTA CORRENTE : ' . $cabecalho['ccid'] . ' OCORRÊNCIAS ( ' . $this->contarOcorrencias() . ' )'), 0, 0, 'L', false);
        $this->Cell(0, 3, utf8_decode('PERIODO : ' . $cabecalho['data_inicio'] . ' A ' . $cabecalho['data_fim'] . ''), 0, 0, 'R', false);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('DATA IMPRESSÃO : ' . $date . ' USUÁRIO : ' . $_SESSION['usuario']['nome']), 0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('SISTEMAS - MÓDULO : INFO - FINANCEIRO'), 0, 0, 'L', false);
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
        $this->Cell(18, 3, utf8_decode('DATA'), 0, 0, 'L', false);
        $this->Cell(17, 3, utf8_decode('DOCUMENTO'), 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(18, 3, utf8_decode('OPERAÇÃO'), 0, 0, 'L', false);
        $this->AfastaCell(85);
        $this->Cell(12, 3, utf8_decode('DEBITO'), 0, 0, 'L', false);
        $this->AfastaCell(9);
        $this->Cell(14, 3, utf8_decode('CREDITO'), 0, 0, 'L', false);
        $this->AfastaCell(17);
        $this->Cell(10, 3, utf8_decode('SALDO'), 0, 0, 'L', false);
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
    }

    function Footer() {
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'B', 8);
        // Print centered page number
        $this->Cell(0, 5, utf8_decode("[Página ") . $this->PageNo() . "]", 0, 0, 'L');
    }

    function relatorioHistoricoFluxoCaixa() {
        global $dados;
        $this->SetFont('Courier', 'B', 8);
        $this->AddPage();
        $this->SetFont('Courier', '', 8);
        $saldo = $dados['saldo'];

        $aux = $dados['fatura'][0]['data'];
        $debito = 0;
        $credito = 0;
        $totalDebito = 0;
        $totalCredito = 0;

        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->Cell(92, 3, 'Saldo Anterior (' . $aux . ')', 0, 0, 'L', false);
        $this->Cell(23, 3, number_format('0', 2, ',', '.'), 0, 0, 'R', false);
        $this->Cell(23, 3, number_format('0', 2, ',', '.'), 0, 0, 'R', false);
        $this->Cell(27, 3, number_format($saldo, 2, ',', '.'), 0, 0, 'R', false);
        $this->Ln(5);

        foreach ($dados['fatura'] as $d) {

            if ($aux != $d['data']) {
                $this->SetFont('Courier', 'B', 8);
                $this->Cell(18, 3, '', 0, 0, 'L', false);
                $this->Cell(18, 3, '', 0, 0, 'L', false);
                $this->Cell(92, 3, 'Sub Total (' . $aux . ')', 0, 0, 'L', false);
                $this->Cell(23, 3, number_format($debito, 2, ',', '.'), 0, 0, 'R', false);
                $this->Cell(23, 3, number_format($credito, 2, ',', '.'), 0, 0, 'R', false);
                $this->Cell(27, 3, number_format($saldo, 2, ',', '.'), 0, 0, 'R', false);
                $this->SetFont('Courier', '', 8);
                $this->Ln();

                $this->inserirLinhaPontilhada();
                $this->Ln(5);

                $totalCredito = bcadd($credito, $totalCredito);
                $totalDebito = bcadd($debito, $totalDebito);

                $debito = 0;
                $credito = 0;
                $aux = $d['data'];
            }

            $val = 0;

            $this->Cell(18, 3, utf8_decode($d['data']), 0, 0, 'L', false);
            $this->Cell(18, 3, utf8_decode($d['documento']), 0, 0, 'L', false);
            ;
            $this->Cell(92, 3, utf8_decode($d['fornecedor']), 0, 0, 'L', false);
            if ($d['I/O'] == 'S') {
                $this->Cell(23, 3, number_format($d['valor'], 2, ',', '.'), 0, 0, 'R', false);
                $this->Cell(23, 3, number_format('0', 2, ',', '.'), 0, 0, 'R', false);
                $val = $d['valor'] * (-1);
                $debito = bcadd($d['valor'], $debito);
            } else {
                $this->Cell(23, 3, number_format('0', 2, ',', '.'), 0, 0, 'R', false);
                $this->Cell(23, 3, number_format($d['valor'], 2, ',', '.'), 0, 0, 'R', false);
                $val = $d['valor'];
                $credito = bcadd($d['valor'], $credito);
            }
            $saldo = bcadd($val, $saldo);
            $this->Cell(27, 3, number_format($saldo, 2, ',', '.'), 0, 0, 'R', false);
            $this->Ln();
        }

        $this->SetFont('Courier', 'B', 8);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->Cell(92, 3, 'Sub Total (' . $aux . ')', 0, 0, 'L', false);
        $this->Cell(23, 3, number_format($debito, 2, ',', '.'), 0, 0, 'R', false);
        $this->Cell(23, 3, number_format($credito, 2, ',', '.'), 0, 0, 'R', false);
        $this->Cell(27, 3, number_format($saldo, 2, ',', '.'), 0, 0, 'R', false);
        $this->SetFont('Courier', '', 8);
        
        $totalCredito = bcadd($credito, $totalCredito);
        $totalDebito = bcadd($debito, $totalDebito);
        
        $this->Ln(5);
        $this->inserirLinhaPontilhada();
        $this->Ln();
        $this->SetFont('Courier', 'B', 8);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->Cell(92, 3, 'Total', 0, 0, 'L', false);
        $this->Cell(23, 3, number_format($totalDebito, 2, ',', '.'), 0, 0, 'R', false);
        $this->Cell(23, 3, number_format($totalCredito, 2, ',', '.'), 0, 0, 'R', false);
        $this->Cell(27, 3, number_format($saldo, 2, ',', '.'), 0, 0, 'R', false);
        $this->SetFont('Courier', '', 8);
        
    }

}

$dados = $_SESSION['relatoriofatura'];
//die(print_r($dados));
$pdf = new PDF();
$pdf->SetMargins(4, 2, 4);
$pdf->relatorioHistoricoFluxoCaixa();
$pdf->Output("relatorio_historico_fluxocaixa.pdf", "I");
