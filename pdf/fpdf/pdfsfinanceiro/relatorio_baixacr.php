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
        $this->Cell(0, 3, utf8_decode('CLIMEP - RELATÓRIO DE DUPLICATAS - CONTAS A PAGAR'), 0, 0, 'L', false);
        $this->SetFont('Courier', '', 8);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('CLIENTE = ' . $cabecalho['cliente'] . ', LOJA = ' . $cabecalho['empresa'] . ', POR 01 - ' . $cabecalho['status'] . ' DE ' . converteData($cabecalho['data_inicio']) . ' À ' . converteData($cabecalho['data_fim']) . ' (' . $this->contarOcorrencias() . ') REGISTROS'), 0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('DATA IMPRESSÃO : ' . $date . ' USUÁRIO : ' . $_SESSION['usuario']['nome']), 0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('SISTEMAS - MÓDULO : INFO - FINANCEIRO'), 0, 0, 'L', false);
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
        $this->Cell(12, 3, utf8_decode('STATUS'), 0, 0, 'L', false);
        $this->Cell(14, 3, utf8_decode('PARCELA'), 0, 0, 'L', false);
        $this->Cell(29, 3, utf8_decode('NOME DO CLIENTE'), 0, 0, 'L', false);
        $this->AfastaCell(50);
        $this->Cell(18, 3, utf8_decode('EMISSÃO'), 0, 0, 'L', false);
        $this->AfastaCell(12);
        $this->Cell(10, 3, utf8_decode('VALOR'), 0, 0, 'L', false);
        $this->AfastaCell(7);
        $this->Cell(15, 3, utf8_decode('DESCONTO'), 0, 0, 'L', false);
        $this->AfastaCell(7);
        $this->Cell(23, 3, utf8_decode('TOT. RECEBER'), 0, 0, 'L', false);
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

    function relatorioBaixacr() {
        global $dados;
        $lista = $dados['lista'];
        $subtotal = $dados['subtotal'];
        $this->SetFont('Courier', 'B', 8);
        $this->AddPage();
        $this->SetFont('Courier', '', 8);

        $ordenado = $dados['cabecalho']['ordenado'];

        if ($ordenado == 1) {
            $order = "nome_status";
        } else if ($ordenado == 2) {
            $order = "nome_cliente";
        } else if ($ordenado == 3) {
            $order = "data_emissao";
        } else if ($ordenado == 4) {
            $order = "data_vencimento";
        } else if ($ordenado == 5) {
            $order = "nome_moeda";
        } else if ($ordenado == 6) {
            $order = "nome_banco";
        } else if ($ordenado == 7) {
            $order = "nome_tipo_doc";
        }

        $k = 0;
        $total = 0;
        $totalparcela = 0;
        $totaldesconto = 0;
        $totalreceber = 0;
        foreach ($lista as $i => $d) {
            if ($i === 0) {
                $aux = $d[$order];
                $total = $subtotal[$k]['total_parcela'];
            }
            if ($aux != $d[$order]) {

                $this->SetFont('Courier', 'B', 8);


                $this->Cell(12, 3, '', 0, 0, 'L', false);
                $this->Cell(14, 3, '', 0, 0, 'L', false);
                $this->Cell(78, 3, '', 0, 0, 'L', false);
                $this->AfastaCell(1);
                $this->Cell(18, 3, '', 0, 0, 'L', false);
                $this->AfastaCell(2);
                $this->Cell(21, 3, number_format($subtotal[$k]['total_parcela'], 2, ',', '.'), 0, 0, 'R', false);
                $this->AfastaCell(1);
                $this->Cell(21, 3, number_format($subtotal[$k]['total_desconto'], 2, ',', '.'), 0, 0, 'R', false);
                $this->AfastaCell(8);
                $this->Cell(21, 3, number_format($subtotal[$k]['total_parcela'] - $subtotal[$k]['total_desconto'], 2, ',', '.'), 0, 0, 'R', false);
                $this->Ln();
                
                $totalparcela += $subtotal[$k]['total_parcela'];
                $totaldesconto += $subtotal[$k]['total_desconto'];
                
                $this->Ln();
                $aux = $d[$order];
                $this->SetFont('Courier', '', 8);
                $this->inserirLinhaPontilhada();
                $this->Ln();
                $this->Cell(0, 3, '', 0, 0, 'R', false);
                $this->Ln();
                $k++;
                $total = $subtotal[$k]['total_parcela'];
            }

            $this->Cell(12, 3, utf8_decode($d['nome_status']), 0, 0, 'L', false);
            $this->Cell(14, 3, utf8_decode($d['numero_parcela']), 0, 0, 'L', false);
            $this->Cell(78, 3, utf8_decode($d['nome_cliente']), 0, 0, 'L', false);
            $this->AfastaCell(1);
            $this->Cell(18, 3, utf8_decode($d['data_vencimento']), 0, 0, 'L', false);
            $this->AfastaCell(2);
            $this->Cell(21, 3, number_format($d['valor_parcela'], 2, ',', '.'), 0, 0, 'R', false);
            $this->AfastaCell(1);
            $this->Cell(21, 3, number_format($d['desconto'], 2, ',', '.'), 0, 0, 'R', false);
            $this->AfastaCell(8);
            $this->Cell(21, 3, number_format($d['valor_parcela'] - $d['desconto'], 2, ',', '.'), 0, 0, 'R', false);            
            $this->Ln();
        }

        $this->SetFont('Courier', 'B', 8);


        $this->Cell(12, 3, '', 0, 0, 'L', false);
        $this->Cell(14, 3, '', 0, 0, 'L', false);
        $this->Cell(78, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(2);
        $this->Cell(21, 3, number_format($subtotal[$k]['total_parcela'], 2, ',', '.'), 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(21, 3, number_format($subtotal[$k]['total_desconto'], 2, ',', '.'), 0, 0, 'R', false);
        $this->AfastaCell(8);
        $this->Cell(21, 3, number_format($subtotal[$k]['total_parcela'] - $subtotal[$k]['total_desconto'], 2, ',', '.'), 0, 0, 'R', false);
        $this->Ln();

        $totalparcela += $subtotal[$k]['total_parcela'];
        $totaldesconto += $subtotal[$k]['total_desconto'];
        
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
        $this->Ln();

        $this->Cell(12, 3, '', 0, 0, 'L', false);
        $this->Cell(14, 3, '', 0, 0, 'L', false);
        $this->Cell(78, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(1);
        $this->Cell(18, 3, '', 0, 0, 'L', false);
        $this->AfastaCell(2);
        $this->Cell(21, 3, 'R$ ' . number_format($totalparcela, 2, ',', ''), 0, 0, 'R', false);     
        $this->AfastaCell(1);
        $this->Cell(21, 3, 'R$ ' . number_format($totaldesconto, 2, ',', ''), 0, 0, 'R', false);
        $this->AfastaCell(7);
        $this->Cell(21, 3, 'R$ ' .($totalparcela - $totaldesconto), 0, 0, 'R', false);
        $this->Ln();        
    }

}

$dados = $_SESSION['lista_faturas_parcelas'];
//die(print_r($dados));
$pdf = new PDF();
$pdf->SetMargins(4, 2, 4);
$pdf->relatorioBaixacr();
$pdf->Output("relatorio_baixacr.pdf", "I");
