<?php

//session_start();
//die(print_r($_SESSION['caixa']));
require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

    function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        if (strpos($corners, '2') === false)
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $y) * $k));
        else
            $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        if (strpos($corners, '3') === false)
            $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
        else
            $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        if (strpos($corners, '4') === false)
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - ($y + $h)) * $k));
        else
            $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);

        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
        if (strpos($corners, '1') === false) {
            $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $y) * $k));
            $this->_out(sprintf('%.2F %.2F l', ($x + $r) * $k, ($hp - $y) * $k));
        } else
            $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    function recibo() {

        $titular = $_SESSION['caixa']['recibo']['clienteTitular'];
        $servicos = $_SESSION['caixa']['recibo']['servicos'];
        $totalFinal = $_SESSION['caixa']['recibo']['total'];
        $desconto = $_SESSION['caixa']['recibo']['desconto'];
//		unset($_SESSION['caixa']['recibo']);
        $array = null;
        $total = 0.0;
        foreach ($servicos as $s) {
            $array[$s['cliente_id']][] = $s;
            if ($s['forma_pagamento'] != 'modulos') {
                if ($s['forma_pagamento'] == 'cartaocred') {
                    if ($s['materialPrecoCartao'] < $s['materialPrecoVista']) {
                        $total+=$s['materialPrecoVistaOriginal'];
                    } else {
                        $total+=$s['materialPrecoCartaoOriginal'];
                    }
                } else {
                    $total+=$s['materialPrecoVistaOriginal'];
                }
            }
        }
		$novoDesconto =0;
		foreach ($servicos as $s) {
            //$array[$s['cliente_id']][] = $s;
            if ($s['forma_pagamento'] != 'modulos') {
                if ($s['forma_pagamento'] == 'cartaocred') {
                    if ($s['materialPrecoCartao'] < $s['materialPrecoVista']) {
                        $novoDesconto+=$s['materialPrecoVista'];
                    } else {
                        $novoDesconto+=$s['materialPrecoCartao'];
                    }
                } else {
                    $novoDesconto+=$s['materialPrecoVista'];
                }
            }
        }

        $this->SetY("-1");
        $this->SetAutoPageBreak(1, 1);
        $this->AddPage();
        $this->Image("pdf/fpdf/pdfscaixa/climep.png", 172, 5, 30, 15);

        $this->Ln(10);
        $this->SetFont('Arial', 'B', 25);
        $this->Cell(0, 10, "R E C I B O", 0, 0, 'C');

        $this->Ln();
        $this->SetFont('Arial', '', 10);
        $this->Cell(17, 5, utf8_decode("Matrícula: "), 0, 0, 'L');
        $this->Cell(10, 5, $titular['matricula'], 0, 0, 'L');
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(120, 5, date('Y'), 0, 0, 'R');

        $this->Ln(10);
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(152, 5, "R$", 0, 0, 'R');
        $this->Cell(0, 5, number_format($novoDesconto, 2, ',', '.'), 0, 0, 'R');

        $this->Ln(15);
        $this->SetFont('Arial', '', 10);
        $this->Cell(10, 5, "", 0, 0, 'R');
        $this->MultiCell(0, 5, utf8_decode('Recebemos de ' . $titular['nome'] . ', CPF: ' . $titular['cpf'] . ', a importância de R$ ' . number_format($totalFinal, 2, ',', '.') . ' referente aos serviços prestados abaixo.'), 0, 'J');


        $this->SetY(70);
        $this->SetFont('Arial', 'U', 10);
        $this->Cell(10, 5, "", 0, 0, 'L');
        $this->Cell(30, 5, "Membro", 0, 0, 'L');
        $this->Cell(57, 5, utf8_decode("Serviço"), 0, 0, 'L');
        $this->Cell(43, 5, "Valor", 0, 0, 'R');
        $this->Cell(2, 5, "", 0, 0, 'R');
        $this->Cell(30, 5, "OBS", 0, 0, 'L');

        $this->Ln(10);
        $this->SetFont('Arial', '', 10);

        $nlinhas = 0;

        foreach ($array as $tmparray) {
            $nlinhas++;
            $this->Cell(10, 5, '', 0, 0, 'L');
            $this->Cell(25, 5, $tmparray[0]['cliente_membro'] . '-' . $tmparray[0]['cliente_nome'], 0, 0, 'L');
            $this->Ln();
            foreach ($tmparray as $servico) {
                if ($servico['forma_pagamento'] != 'modulos') {
                    $nlinhas++;
                    $this->Cell(10, 5, '', 0, 0, 'L');
                    $this->Cell(30, 5, '', 0, 0, 'L');
                    if ($servico['modulo'] == 1) {
                        $this->Cell(80, 5, $servico['materialNome'] . ' (modulo)', 0, 0, 'L');
                    } else {
                        $this->Cell(80, 5, $servico['materialNome'], 0, 0, 'L');
                    }
                    if ($servico['forma_pagamento'] == 'cartaocred') {
                        if ($servico['materialPrecoCartaoOriginal'] < $servico['materialPrecoVistaOriginal']) {
                            $this->Cell(20, 5, number_format($servico['materialPrecoVistaOriginal'], 2, ',', '.'), 0, 0, 'R');
                        } else {
                            $this->Cell(20, 5, number_format($servico['materialPrecoCartaoOriginal'], 2, ',', '.'), 0, 0, 'R');
                        }
                    } else {
                        $this->Cell(20, 5, number_format($servico['materialPrecoVistaOriginal'], 2, ',', '.'), 0, 0, 'R');
                    }
                    $this->Cell(2, 5, "", 0, 0, 'R');
                    $this->Cell(47, 5, $servico['status'], 0, 0, 'L');
                    $this->Ln();
                }
            }
            $nlinhas++;
            if ($desconto > 0) {
                $this->Cell(10, 5, '', 0, 0, 'L');
                $this->Cell(30, 5, '', 0, 0, 'L');
                $this->Cell(80, 5, 'Desconto Climep', 0, 0, 'L');
                $this->Cell(20, 5, '- ' . number_format(($desconto), 2, ',', '.'), 0, 0, 'R');
                $this->Cell(2, 5, "", 0, 0, 'R');
                $this->Cell(47, 5, 'REALIZADO', 0, 0, 'L');
                $this->Ln();
            }
        }
//        
//        
//        foreach ($servicos as $s){            
//            $this->Cell(12, 5, '', 0, 0, 'L');            
//            $this->Cell(25, 5, $i.'0', 0, 0, 'L');
//            $this->Cell(85, 5, $i.'0', 0, 0, 'L');
//            $this->Cell(20, 5, '10.000,00', 0, 0, 'L');
//            $this->Cell(47, 5, "PAGAMENTO ANTECIPADO", 0, 0, 'L');
//            $this->Ln();
//        }           

        $this->RoundedRect(15, 78, 185, $nlinhas * 5 + 10, 2, "1234");

        $dia = date('d');
        $mes = mostraMes(date('m'));
        $ano = date('Y');
        $diadasemana = mostraSemana(date('w'));

        $data = $diadasemana . ', ' . $dia . ' de ' . $mes . ' de ' . $ano;

        $this->Ln(15);
        $this->Cell(0, 5, $data, 0, 0, 'R');

        $this->SetFont('Arial', '', 8);
        $this->Ln(5);
        $this->Cell(0, 10, "__________________________________________________________________", 0, 0, 'C');
        $this->Ln(5);
        $this->Cell(0, 10, utf8_decode("Clínica de Medicina Preventiva do Pará - CLIMEP"), 0, 0, 'C');
        $this->SetFont('Arial', '', 7);
        $this->Ln(3);
        $this->Cell(0, 10, utf8_decode("Av. Braz de Aguiar, 410 - 66035-405 - Nazaré"), 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 10, "Tel.:(91) 3181-1644  Fax.:(91) 3181-1610", 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 10, "CNPJ: 05.083.142/0001-83", 0, 0, 'C');
        $this->Ln(3);
        $this->Cell(0, 10, utf8_decode("Belém - Pará - Brasil"), 0, 0, 'C');
        $this->Ln(3);
    }

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(10, 10, 10);
$pdf->recibo();
$pdf->Output("recibo.pdf", "I");
