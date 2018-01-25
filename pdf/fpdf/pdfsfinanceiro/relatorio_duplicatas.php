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
        $date = date('d/m/Y H:i:s');

        $cabecalho = $dados['cabecalho'];

        if ($cabecalho['status'] == 0)
            $cabecalho['status'] = "TODOS";
        else if ($cabecalho['status'] == 1)
            $cabecalho['status'] = "EM ABERTO";
        else if ($cabecalho['status'] == 2)
            $cabecalho['status'] = "BAIXA PARCIAL";
        else if ($cabecalho['status'] == 3)
            $cabecalho['status'] = "BAIXADO";

        $this->SetFont('Courier', 'B', 8);
        $this->Cell(0, 3, utf8_decode('CLIMEP - RELATÓRIO DE DUPLICATAS'), 0, 0, 'L', false);
        $this->SetFont('Courier', '', 8);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('FORNECEDOR = ' . $cabecalho['fornecedor'] . ', LOJA = ' . $cabecalho['empresa'] . ', POR 01 - ' . $cabecalho['status'] . ' DE ' . converteData($cabecalho['data_inicio']) . ' À ' . converteData($cabecalho['data_fim']) . ' (' . $this->contarOcorrencias() . ') REGISTROS'), 0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('DATA IMPRESSÃO : ' . $date . ' USUÁRIO : ' . $_SESSION['usuario']['nome']), 0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('SISTEMAS - MÓDULO : INFO - FINANCEIRO'), 0, 0, 'L', false);
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
        $this->Cell(7, 3, utf8_decode('STA'), 0, 0, 'L', false);
        $this->Cell(18, 3, utf8_decode('DUPLICATA'), 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(18, 3, utf8_decode('FORNECEDOR'), 0, 0, 'L', false);
        $this->AfastaCell(20);
        $this->Cell(18, 3, utf8_decode('EMPRESA'), 0, 0, 'L', false);
        $this->AfastaCell(20);
        $this->Cell(18, 3, utf8_decode('LANÇAMENTO'), 0, 0, 'L', false);
        $this->AfastaCell(2);
        $this->Cell(18, 3, utf8_decode('EMISSÃO'), 0, 0, 'L', false);
        $this->AfastaCell(12);
        $this->Cell(10, 3, utf8_decode('VALOR'), 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(10, 3, utf8_decode('%'), 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(20, 3, utf8_decode('BANCO'), 0, 0, 'L', false);
        $this->AfastaCell(2);
        $this->Ln(3);
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

    function relatorioDuplicatas() {
        global $dados;
        $lista = $dados['lista'];
        $subtotal = $dados['subtotal'];
        $this->SetFont('Courier', 'B', 8);
        $this->AddPage();
        $this->SetFont('Courier', '', 8);

        $ordenado = $dados['cabecalho']['ordenado'];
        
        if ($ordenado == 1) {
            $order = "nome_fornecedor";
        } else if ($ordenado == 2) {
            $order = "nome_empresa";
        } else if ($ordenado == 3) {
            $order = "data_lancamento";
        } else if ($ordenado == 4) {
            $order = "data_emissao";
        } else if ($ordenado == 5) {
            $order = "nome_moeda";
        } else if ($ordenado == 6) {
            $order = "nome_banco";
        } else if ($ordenado == 7) {
            $order = "data_baixa";
        } else if ($ordenado == 8) {
            $order = "nome_status";
        }

        $k = 0;
        $total = 0;
        $totalgeral = 0;
        foreach ($subtotal as $subt) {
            $totalgeral = bcadd($subt['total'], $totalgeral);
        }
        $dataemissao = '';
        foreach ($lista as $i => $l) {
            if ($i === 0) {
                $aux = $l[$order];
                $total = $subtotal[$k]['total'];
            }
            if ($aux != $l[$order]) {

                $this->SetFont('Courier', 'B', 8);
                $this->Cell(7, 3, '', 0, 0, 'L', false);
                $this->Cell(18, 3, '', 0, 0, 'L', false);
                $this->AfastaCell(1);
                $this->Cell(37, 3, utf8_decode($subtotal[$k]['nome']), 0, 0, 'L', false);
                $this->Cell(37, 3, '', 0, 0, 'L', false);
                $this->AfastaCell(2);
                $this->Cell(18, 3, '', 0, 0, 'L', false);
                $this->AfastaCell(2);
                $this->Cell(18, 3, $dataemissao, 0, 0, 'L', false);
                $this->AfastaCell(1);
                $this->Cell(21, 3, 'R$ ' . number_format($subtotal[$k]['total'], 2, ',', ''), 0, 0, 'R', false);
                $this->AfastaCell(1);
                $this->Cell(10, 3, number_format(($subtotal[$k]['total'] * 100) / $totalgeral, 2, ',', ''), 0, 0, 'R', false);

                $this->Ln();
                $aux = $l[$order];
                $this->SetFont('Courier', '', 8);
                $this->inserirLinhaPontilhada();
                $this->Ln();
                $this->Cell(0, 3, '', 0, 0, 'R', false);
                $this->Ln();
                $k++;
                $total = $subtotal[$k]['total'];
            }
            $this->Cell(7, 3, utf8_decode($l['nome_status']), 0, 0, 'L', false);
            $this->Cell(18, 3, utf8_decode($l['numero']), 0, 0, 'L', false);
            $this->AfastaCell(1);
            $this->Cell(37, 3, encurtar2(utf8_decode($l['nome_fornecedor']), 21), 0, 0, 'L', false);
            $this->AfastaCell(1);
            $this->Cell(37, 3, encurtar2(utf8_decode($l['nome_empresa']),21), 0, 0, 'L', false);
            $this->AfastaCell(1);
            $this->Cell(18, 3, utf8_decode($l['data_lancamento']), 0, 0, 'L', false);
            $this->AfastaCell(2);
            $this->Cell(18, 3, utf8_decode($l['data_emissao']), 0, 0, 'L', false);
            $this->AfastaCell(1);
            $this->Cell(21, 3, 'R$ ' . number_format($l['total'], 2, ',', ''), 0, 0, 'R', false);
            $this->AfastaCell(1);
            $this->Cell(10, 3, number_format(bcdiv(($l['total'] * 100), $total), 2, ',', ''), 0, 0, 'R', false);
            $this->AfastaCell(1);
            $this->Cell(27, 3, encurtar2(utf8_decode($l['nome_banco']), 18), 0, 0, 'L', false);
            $dataemissao = $l['data_emissao'];
            $this->Ln();
        }

        $this->SetFont('Courier', 'B', 8);
        $this->Cell(7, 3, '', 0, 0, 'L', false);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(37, 3, utf8_decode($subtotal[$k]['nome']), 0, 0, 'L', false);
//        $this->AfastaCell(2);
        $this->Cell(37, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(2);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(2);
        $this->Cell(18, 3, $dataemissao, 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(21, 3, 'R$ ' . number_format($subtotal[$k]['total'], 2, ',', ''), 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(10, 3, number_format(($subtotal[$k]['total'] * 100) / $totalgeral, 2, ',', ''), 0, 0, 'R', false);
        $this->SetFont('Courier', '', 8);
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
        $this->SetFont('Courier', 'B', 8);
        $this->Cell(7, 3, '', 0, 0, 'L', false);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(37, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(2);
        $this->Cell(37, 3, '', 0, 0, 'L', false);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(2);
        $this->Cell(18, 3, $dataemissao, 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(21, 3, 'R$ ' . number_format($totalgeral, 2, ',', ''), 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(10, 3, '', 0, 0, 'R', false);
        $this->SetFont('Courier', '', 8);
    }

}

$dados = $_SESSION['listaDuplicatas'];
//die(print_r($dados));
$pdf = new PDF();
$pdf->SetMargins(4, 2, 4);
$pdf->relatorioDuplicatas();
$pdf->Output("relatorio_duplicatas.pdf", "I");
