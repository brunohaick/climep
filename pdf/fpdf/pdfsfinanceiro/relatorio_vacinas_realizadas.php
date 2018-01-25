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
		if ($ncol > 3)
			$this->Cell(0, 3, '-------------------------------------------------------------------------------------------------------------------------------------------------------------------------', 0, 0, false);
		else
			$this->Cell(0, 3, '----------------------------------------------------------------------------------------------------------------------', 0, 0, false);
	}

	function contarOcorrencias() {
		global $ncol, $dados;                
                
		$contador = 0;                    
		$ncolunas = sizeof($dados);                                
                for ($i = 0; $i < 3; $i++) {
                    $nlinhas = count($dados[$i]['dados'][0]);                    
                    for ($j = 0; $j < $nlinhas; $j++) {                        
			if (floatval($dados[$i]['dados'][$ncol-1][$j]['media']) != 0.0 && floatval($dados[$i]['dados'][$ncol-1][$j]['media']) != NULL) {
				$contador++;
			}
                    }
                }		
		return $contador;
	}

	function Header() {

		global $ncol, $dados;
		$date = date('d/m/Y H:i:s');

		$this->SetFont('Courier', 'B', 8);
		$this->Cell(0, 3, utf8_decode('CLIMEP - RELATÓRIO CONTABIL ( 6 - Vacinas Realizadas )'), 0, 0, 'L', false);
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
		$this->Cell(45, 3, utf8_decode('DESCRIÇAO'), 0, 0, 'L', false);
		$this->AfastaCell(1);
		for ($i = 2; $i < count($dados[0]['cabecalho']); $i+=3) {
			$mes = explode('/', $dados[0]['cabecalho'][$i]);
			$this->Cell(20, 3, utf8_decode($dados[0]['cabecalho'][$i]), 0, 0, 'R', false);
			$this->Cell(12, 3, $dados[0]['cabecalho'][$i + 1], 0, 0, 'C', false);
			$this->Cell(12, 3, $dados[0]['cabecalho'][$i + 2], 0, 0, 'R', false);
		}
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

	function relatorioVacinasRealizadas() {
		$this->SetFont('Courier', 'B', 8);

		global $ncol, $dados;

		if ($ncol > 3) {
			$this->AddPage('L');
		} else {
			$this->AddPage();
		}
		$this->SetFont('Courier', '', 8);

		$tmpdados = $dados;
		unset($totalGeral);
		for ($k = 0; $k < 3; $k++) {
			$tmpdados = $dados[$k];

			$ncolunas = count($tmpdados['dados']);
			$nlinhas = count($tmpdados['dados'][0]);


			if ($k == 0) {
				$this->Cell(20, 3, "", 0, 0, 'R', false);
				$this->AfastaCell(1);
				$this->Cell(45, 3, utf8_decode("VACINAÇOES"), 0, 0, 'L', false);
			} else if ($k == 1) {
				$this->Cell(20, 3, "", 0, 0, 'R', false);
				$this->AfastaCell(1);
				$this->Cell(45, 3, utf8_decode("IMUNOTERAPIA"), 0, 0, 'L', false);
			} else if ($k == 2) {
				$this->Cell(20, 3, "", 0, 0, 'R', false);
				$this->AfastaCell(1);
				$this->Cell(45, 3, utf8_decode("TESTES DE TRIAGEM"), 0, 0, 'L', false);
			}
			$this->AfastaCell(1);
			foreach ($tmpdados['totalgeral'] as $key => $valor) {
				$totalGeral[$k][$key] = bcadd($valor['valor'], $totalGeral[$k][$key], 2);
				$this->Cell(20, 3, number_format($valor['valor'], 2, ',', '.'), 0, 0, 'R', false);
				$this->Cell(12, 3, number_format($valor['porc'], 2, ',', '.'), 0, 0, 'R', false);
				$this->Cell(12, 3, intval($valor['qtd']), 0, 0, 'R', false);
			}
			$this->Ln(3);
			for ($i = 0; $i < $nlinhas; $i++) {
				if (intval($tmpdados['dados'][$ncolunas - 1][$i]['media']) != 0 && intval($tmpdados['dados'][$ncolunas - 1][$i]['media']) != NULL) {
					$this->Cell(20, 3, utf8_decode($tmpdados['dados'][0][$i]['codigo']), 0, 0, 'R', false);
					$this->AfastaCell(1);
					$this->Cell(45, 3, utf8_decode($tmpdados['dados'][0][$i]['nome']), 0, 0, 'L', false);
					$this->AfastaCell(1);
					for ($j = 0; $j < $ncolunas - 1; $j++) {
						$this->Cell(20, 3, number_format($tmpdados['dados'][$j][$i]['valor'], 2, ',', '.'), 0, 0, 'R', false);
						$this->Cell(12, 3, number_format($tmpdados['dados'][$j][$i]['porc'], 2, ',', '.'), 0, 0, 'R', false);
						$this->Cell(12, 3, intval($tmpdados['dados'][$j][$i]['qtd']), 0, 0, 'R', false);
					}
					$this->Cell(20, 3, number_format($tmpdados['dados'][$ncolunas - 1][$i]['media'], 2, ',', '.'), 0, 0, 'R', false);
					$this->Cell(12, 3, number_format($tmpdados['dados'][$ncolunas - 1][$i]['porc'], 2, ',', '.'), 0, 0, 'R', false);
					$this->Cell(12, 3, intval($tmpdados['dados'][$ncolunas - 1][$i]['qtd']), 0, 0, 'R', false);
					$this->Ln();
				}
			}
			$this->Ln(3);
		}

		$this->Ln();
		$this->Cell(20, 3, '', 0, 0, 'R', false);
		$this->AfastaCell(1);
		$this->Cell(45, 3, 'TOTAL GERAL:', 0, 0, 'L', false);
		$this->AfastaCell(1);
                
		foreach($dados['totalgeral2'] as $valor){                        
			$this->Cell(20, 3, number_format($valor, 2, ',', '.'), 0, 0, 'R', false);
			$this->Cell(12, 3, '', 0, 0, 'R', false);
			$this->Cell(12, 3, '', 0, 0, 'R', false);			
		}                
	}

}

$dados = $_SESSION['Mapavacinasrealizadas'];
$ncol = 0;
$ncol = ((count($dados[0]['cabecalho'])) - 2) / 3;
$pdf = new PDF();
$pdf->SetMargins(4, 2, 4);
$pdf->relatorioVacinasRealizadas();
$pdf->Output("relatorio_vacinasrealizadas.pdf", "I");

//unset($_SESSION['Mapavacinasrealizadas']);
