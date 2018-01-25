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
        global $ncol, $dados, $totalgeralmes;
        $contador = 0;        
        foreach ($dados['dados'] as $dado) {
            if (intval($dado['valor'][$ncol - 1]['media']) != 0) {
                $contador++;
            }                      
            
            for($i = 0; $i < count($dado['valor']); $i++){
                if($i < count($dado['valor'])-1){
                    $totalgeralmes['valor'][$i] += $dado['valor'][$i]['valor'];
                } else {
                    $totalgeralmes['valor'][$i] += $dado['valor'][$i]['media'];
                }
            }
        }
        return $contador;
    }

    function Header() {
        global $ncol, $arrayMeses, $dados;
        //die(print_r($dados['cabecalho']));
        $date = date('d/m/Y H:i:s');
        $ocorrencias = sizeof($dados['dados']) + sizeof($dados['despesas']);

        $this->SetFont('Courier', 'B', 8);
        $this->Cell(0, 3, utf8_decode('CLIMEP - RELATÓRIO CONTABIL ( 4 - Forma de Pagamento )'), 0, 0, 'L', false);
        $this->SetFont('Courier', '', 8);
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
        // Go to 1.5 cm from bottom
        $this->SetY(-15);
        // Select Arial italic 8
        $this->SetFont('Arial', 'B', 8);
        // Print centered page number
        $this->Cell(0, 5, utf8_decode("[Página ") . $this->PageNo() . "]", 0, 0, 'L');
    }

    function relatorioPadrao() {
        $this->SetFont('Courier', 'B', 8);
        global $ncol, $arrayMeses, $dados, $totalgeralmes;
        $totalgeralmes = $_SESSION['Mapapagamento']['total'];
//        die(print_r($dados['despesas']));
        if ($ncol > 3) {
            $this->AddPage('L');
        } else {
            $this->AddPage();
        }
        $codigo = $dados['dados'][0]['codigo'];
        $codigo = explode('.', $codigo);
        $ant = $codigo[1];
        $this->SetFont('Courier', '', 8);
        foreach ($dados['dados'] as $dado) {
            
            if (intval($dado['valor'][$ncol - 1]['media']) != 0 && $dado['codigo'] != '1') {
                $codigo = $dado['codigo'];
                $codigo = explode('.', $codigo);
                $codigo = $codigo[1];

                if ($ant != $codigo) {
                    $ant = $codigo[1];
                    $this->Ln(5);
                }

                $this->Cell(20, 3, utf8_decode($dado['codigo']), 0, 0, 'R', false);
                $this->AfastaCell(1);
                $this->Cell(45, 3, utf8_decode($dado['nome']), 0, 0, 'L', false);
                $this->AfastaCell(1);
                foreach ($dado['valor'] as $key=>$valor) {                    
                    if($key < count($valor)-1){
                        $this->Cell(20, 3, number_format($valor['valor'], 2, ',', '.'), 0, 0, 'R', false);
                        $this->Cell(12, 3, number_format(($valor['valor']*100)/$totalgeralmes['valor'][$key], 1, ',', '.') . '%', 0, 0, 'R', false);
                        $totalgeralmes['taxa'][$key] += ($valor['valor']*100)/$totalgeralmes['valor'][$key];
                    } else {
                        $this->Cell(20, 3, number_format($valor['media'], 2, ',', '.'), 0, 0, 'R', false);
                        $this->Cell(12, 3, number_format(($valor['media']*100)/$totalgeralmes['valor'][$key], 1, ',', '.') . '%', 0, 0, 'R', false);
                        $totalgeralmes['taxa'][$key] += ($valor['media']*100)/$totalgeralmes['valor'][$key];
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
        
        for($i=0; $i<count($totalgeralmes['valor']); $i++){
            $this->Cell(20, 3, number_format($totalgeralmes[$i]['valor'], 2, ',', '.'), 0, 0, 'R', false);
            $this->Cell(12, 3, number_format($totalgeralmes[$i]['taxa'], 1, ',', '.') . '%', 0, 0, 'C', false);
        }
    }

}

$totalgeralmes = '';
$dados = $_SESSION['Mapapagamento'];
$totalgeralmes = $dados['total'];
$ncol = 0;
$ncol = sizeof($dados['dados'][0]['valor']);
$pdf = new PDF();
$pdf->SetMargins(4, 2, 4);
$pdf->relatorioPadrao();
$pdf->Output("relatorio_formapagamento.pdf", "I");
