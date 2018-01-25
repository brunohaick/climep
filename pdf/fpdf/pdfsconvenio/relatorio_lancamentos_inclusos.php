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

        return count($dados['lista']);
    }

    function Header() {
        global $dados;

        $cabecalho = $dados['cabecalho'];

        $this->SetFont('Courier', 'B', 8);
        $this->Cell(0, 3, utf8_decode('CLIMEP - RELATÓRIO DE LANÇAMENTOS INCLUSOS'), 0, 0, 'L', false);
        $this->SetFont('Courier', '', 8);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('LOJA=' . $cabecalho['nomeConvenio'] . ', EMPRESA=' . $cabecalho['nomeEmpresa'] . ', DE ' . converteData($cabecalho['data_inicio']) . ' A ' . converteData($cabecalho['data_fim']) . ' (' . $this->contarOcorrencias() . ') REGISTROS'), 0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('SISTEMAS - MÓDULO : INFO - CONVÊNIO'), 0, 0, 'L', false);
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
        $this->AfastaCell(1);
        $this->Cell(13, 3, utf8_decode('Nº GUIA'), 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(13, 3, utf8_decode('MATRIC'), 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(9, 3, utf8_decode('NOME'), 0, 0, 'L', false);
        $this->AfastaCell(47);
        $this->Cell(23, 3, utf8_decode('SERVICO'), 0, 0, 'L', false);
        $this->AfastaCell(35);
        $this->Cell(9, 3, utf8_decode('DATA'), 0, 0, 'L', false);
        $this->AfastaCell(8);
        $this->Cell(10, 3, utf8_decode('VALOR'), 0, 0, 'L', false);
        $this->AfastaCell(1.5);
        $this->Cell(12, 3, utf8_decode('MÉDICO'), 0, 0, 'L', false);
        $this->AfastaCell(4);
        $this->Cell(15, 3, utf8_decode('USUÁRIO'), 0, 0, 'L', false);
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

    function relatorioLancamentosInclusos() {
        global $dados;

        $this->SetFont('Courier', 'B', 8);
        $this->AddPage();
        $this->SetFont('Courier', '', 8);

        $subtotal = $dados['subtotal'];
        $ordenado = $dados['cabecalho']['ordenado'];
        $order = 'codigo';
        if ($ordenado == 1) {
            $order = 'codigo';
        } else if ($ordenado == 2) {
            $order = 'convenio';
        } else if ($ordenado == 3) {
            $order = 'medico';
        } else if ($ordenado == 4) {
            $order = 'data';
        } else if ($ordenado == 5) {
            $order = 'depnome';
        } else if ($ordenado == 6) {
            $order = 'convenio';
        } else if ($ordenado == 7) {
            $order = 'medico';
        } else if ($ordenado == 8) {
            $order = 'valor';
        }

        $k = 0;
        $total = 0;
        $totalgeral = 0;
        foreach ($subtotal as $subt) {
            $totalgeral = bcadd($subt['valor'], $totalgeral);
        }
        foreach ($dados['lista'] as $i => $d) {

            if ($i === 0) {
                $aux = $d[$order];
                $total = $subtotal[$k]['valor'];
            }
            if ($aux != $d[$order]) {
                $this->SetFont('Courier', 'B', 8);
                $this->AfastaCell(1);
                $this->Cell(13, 3, '', 0, 0, 'C', false);
                $this->AfastaCell(1);
                $this->Cell(13, 3, '', 0, 0, 'C', false);
                $this->AfastaCell(1);
                $this->Cell(55, 3, '', 0, 0, 'L', false);
                $this->AfastaCell(1);
                $this->Cell(47, 3, '', 0, 0, 'L', false);
                $this->AfastaCell(1);
                $this->Cell(18, 3, '', 0, 0, 'R', false);
                $this->AfastaCell(1);
                $this->Cell(18, 3, number_format($subtotal[$k]['valor'], 2, ',', '.'), 0, 0, 'R', false);
                $this->AfastaCell(1);
                $this->Cell(16, 3, '', 0, 0, 'L', false);
                $this->AfastaCell(1);
                $this->Cell(16, 3, '', 0, 0, 'L', false);
                $this->Ln();
                $this->SetFont('Courier', '', 8);
                $k++;
                $total = $subtotal[$k]['valor'];
                
                $this->inserirLinhaPontilhada();
                $this->Ln();
                $this->Cell(0, 3, '', 0, 0, 'R', false);
                $this->Ln();
            }


            $this->AfastaCell(1);
            $this->Cell(13, 3, utf8_decode($d['numero_da_guia']), 0, 0, 'C', false);
            $this->AfastaCell(1);
            $this->Cell(13, 3, utf8_decode($d['matricula']), 0, 0, 'C', false);
            $this->AfastaCell(1);
            $this->Cell(55, 3, encurtar2(utf8_decode($d['depnome']), 31), 0, 0, 'L', false);
            $this->AfastaCell(1);
            $this->Cell(47, 3, encurtar2(utf8_decode($d['servico']), 26), 0, 0, 'L', false);
            $this->AfastaCell(1);
            $this->Cell(18, 3, utf8_decode($d['data']), 0, 0, 'R', false);
            $this->AfastaCell(1);
            $this->Cell(18, 3, number_format($d['valor'], 2, ',', '.'), 0, 0, 'R', false);
            $this->AfastaCell(1);
            $this->Cell(16, 3, encurtar2(utf8_decode($d['medico']), 10), 0, 0, 'L', false);
            $this->AfastaCell(1);
            $this->Cell(16, 3, utf8_decode($d['usuario']), 0, 0, 'L', false);
            $this->Ln();
        }

        $this->SetFont('Courier', 'B', 8);
        $this->AfastaCell(1);
        $this->Cell(13, 3, '', 0, 0, 'C', false);
        $this->AfastaCell(1);
        $this->Cell(13, 3, '', 0, 0, 'C', false);
        $this->AfastaCell(1);
        $this->Cell(55, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(47, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(18, 3, '', 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(18, 3, number_format($subtotal[$k]['valor'], 2, ',', '.'), 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(16, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(16, 3, '', 0, 0, 'L', false);
        $this->Ln();
        $this->SetFont('Courier', '', 8);
        
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
        $this->Ln();
        
        $this->SetFont('Courier', 'B', 8);
        $this->AfastaCell(1);
        $this->Cell(13, 3, '', 0, 0, 'C', false);
        $this->AfastaCell(1);
        $this->Cell(13, 3, '', 0, 0, 'C', false);
        $this->AfastaCell(1);
        $this->Cell(55, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(47, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(18, 3, 'Total', 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(18, 3, number_format($totalgeral, 2, ',', '.'), 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(16, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(16, 3, '', 0, 0, 'L', false);
        $this->Ln();
        $this->SetFont('Courier', '', 8);
        
    }

}

$dados = $_SESSION['listaConvenio'];
$pdf = new PDF();
$pdf->SetMargins(4, 2, 4);
$pdf->relatorioLancamentosInclusos();
$pdf->Output("relatorio_lacamentos_inclusos.pdf", "I");
