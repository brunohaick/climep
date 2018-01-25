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
        global $ncol;
        if ($ncol > 4)
            $this->Cell(0, 3, '-------------------------------------------------------------------------------------------------------------------------------------------------------------------------', 0, 0, false);
        else
            $this->Cell(0, 3, '----------------------------------------------------------------------------------------------------------------------', 0, 0, false);
    }

    function contarOcorrencias() {
        global $ncol, $dados;
        $contador = 0;
        foreach ($dados['dados'] as $dado) {
            if (intval($dado['valor'][$ncol - 1]['media']) != 0) {
                $contador++;
            }
        }
        foreach ($dados['despesas'] as $dado) {
            if (intval($dado['valor'][$ncol - 1]['media']) != 0) {
                $contador++;
            }
        }
        return $contador;
    }

    function Header() {
        global $ncol, $arrayMeses, $dados, $font;        
        $date = date('d/m/Y H:i:s');        

        $this->SetFont($font, 'B', 8);
        $this->Cell(0, 3, utf8_decode('CLIMEP - RELATÓRIO CONTABIL ( 4 - Padrão )'), 0, 0, 'L', false);
        $this->SetFont($font, '', 8);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('PERIODO : ' . converteData($dados['datainicio']) . ' A ' . converteData($dados['datafim']) . '    -OCORRÊNCIAS ( ' . $this->contarOcorrencias() . ' )'), 0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('DATA IMPRESSÃO : ' . $date . ' USUÁRIO : ' . $_SESSION['usuario']['nome']), 0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, 3, utf8_decode('SISTEMAS - MÓDULO : INFO - FINANCEIRO'), 0, 0, 'L', false);
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
        $this->Cell(20, 3, utf8_decode('PLANO'), 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(45, 3, utf8_decode('DESCRIÇÃO'), 0, 0, 'L', false);
        $this->AfastaCell(1);

        for ($i = 2; $i < $ncol * 2; $i+=2) {
            $mes = explode('/', $dados['cabecalho'][$i]);
            $this->Cell(20, 3, $mes[0], 0, 0, 'R', false);
            $this->Cell(12, 3, $dados['cabecalho'][$i + 1], 0, 0, 'C', false);
        }
        $this->Cell(20, 3, utf8_decode('MÉDIA'), 0, 0, 'R', false);
        $this->Cell(12, 3, '%', 0, 0, 'C', false);
        $this->Ln();
        $this->inserirLinhaPontilhada();
        $this->Ln();
    }

    function Footer() {
        
        global $font;
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont($font, 'B', 8);
        // Print centered page number
        $this->Cell(0, 5, utf8_decode("[Página ") . $this->PageNo() . "]", 0, 0, 'L');
    }

    function relatorioPadrao() {
        global $ncol, $arrayMeses, $dados, $font;
        
        $this->SetFont($font, 'B', 8);

//        die(print_r($dados['despesas']));

        if ($ncol > 4) {
            $this->AddPage('L');
        } else {
            $this->AddPage();
        }
        $codigo = $dados['dados'][0]['codigo'];
        $codigo = explode('.', $codigo);
        $ant = $codigo[1];
        $this->SetFont($font, '', 8);

        foreach ($dados['dados'] as $dado) {

            if (intval($dado['valor'][$ncol - 1]['media']) != 0) {
                $codigo = $dado['codigo'];
                $codigo = explode('.', $codigo);
                $codigo = $codigo[1];

                if ($ant != $codigo) {
                    $ant = $codigo[1];
                    $this->Ln(5);
                }

                $this->Cell(20, 3, utf8_decode($dado['codigo']), 0, 0, 'R', false);
                $this->AfastaCell(1);
                $this->Cell(45, 3, encurtar2(utf8_decode($dado['nome']),26), 0, 0, 'L', false);
                $this->AfastaCell(1);
                foreach ($dado['valor'] as $indice => $valor) {
                    if ($indice >= $ncol - 1) {
                        $this->Cell(20, 3, number_format($valor['media'], 2, ',', '.'), 0, 0, 'R', false);
                        $this->Cell(12, 3, number_format($valor['taxa'], 2, ',', '.'), 0, 0, 'R', false);
                    } else {
                        $this->Cell(20, 3, number_format($valor['valor'], 2, ',', '.'), 0, 0, 'R', false);
                        $this->Cell(12, 3, number_format($valor['taxa'], 2, ',', '.'), 0, 0, 'R', false);
                    }
                }
                $this->Ln();
            }
        }
        foreach ($dados['despesas'] as $dado) {            
            if (intval($dado['valor'][$ncol - 1]['media']) != 0) {

                $codigo = $dado['codigo'];
                $codigo = explode('.', $codigo);
                $codigo = $codigo[1];

                if ($ant != $codigo) {
                    $ant = $codigo[1];
                    $this->Ln();
                }

                $this->Cell(20, 3, utf8_decode($dado['codigo']), 0, 0, 'R', false);
                $this->AfastaCell(1);
                $this->Cell(45, 3, encurtar2(utf8_decode($dado['nome']),26), 0, 0, 'L', false);
                $this->AfastaCell(1);
                foreach ($dado['valor'] as $indice => $valor) {
                    if ($indice >= $ncol - 1) {
                        $this->Cell(20, 3, number_format($valor['media'], 2, ',', '.'), 0, 0, 'R', false);
                        $this->Cell(12, 3, number_format($valor['taxa'], 2, ',', '.'), 0, 0, 'R', false);
                    } else {
                        $this->Cell(20, 3, number_format($valor['valor'], 2, ',', '.'), 0, 0, 'R', false);
                        $this->Cell(12, 3, number_format($valor['taxa'], 2, ',', '.'), 0, 0, 'R', false);
                    }
                }
                $this->Ln();
            }
        }
        $this->Ln();
        $this->Cell(20, 3, '', 0, 0, 'R', false);
        $this->AfastaCell(1);
        $this->Cell(45, 3, 'TOTAL GERAL:', 0, 0, 'L', false);
        $this->AfastaCell(1);
        foreach ($dados['total'] as $valor) {
            $this->Cell(20, 3, number_format($valor['valor'], 2, ',', '.'), 0, 0, 'R', false);
            $this->Cell(12, 3, number_format($valor['taxa'], 2, ',', '.'), 0, 0, 'R', false);
        }
    }

}

$dados = $_SESSION['Mapapadrao'];
$ncol = 0;
$ncol = sizeof($dados['dados'][0]['valor']);
$font = 'Courier';
$pdf = new PDF();
$pdf->SetMargins(4, 4, 4);
$pdf->relatorioPadrao();
$pdf->Output("relatorio_padrao.pdf", "I");
