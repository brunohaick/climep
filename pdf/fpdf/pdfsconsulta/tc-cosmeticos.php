<?php

session_start();
require('../fpdf.php');
//require('./Bootstrap.php');
//conectar();

class PDF extends FPDF {

	function tc_cosmeticos($dados) {

		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);

		$this->AddPage();
		$this->Image("tc-cosmeticos.png",0,0,210,298);

		$this->SetFont('Arial', 'BU', 12);
		$this->Ln(38);
		$this->Cell(40,5,'', 0, 0, 'L');
		$this->Cell(156,5,'Marcus Vinicius de Oliveira Dias', 0, 0, 'C');
		$this->SetFont('Arial', '', 8);
		$this->Ln(13.5);
		$this->Cell(40,5,'', 0, 0, 'L');
		$this->Cell(25,5,'', 0, 0, 'L');
		$this->Cell(30,5,date('d/m/Y'), 0, 0, 'L');
		$this->Cell(26,5,'', 0, 0, 'L');
		$this->Cell(30,5,date('d/m/Y'), 0, 0, 'L');// dia +2
		$this->Cell(24,5,'', 0, 0, 'L');
		$this->Cell(30,5,date('d/m/Y'), 0, 0, 'L'); //dia +4


		$this->Ln(12.5);
		$this->Cell(98,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 0, 'C'); //dados
		$this->Cell(57,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 1, 'C'); //dados

		$this->Cell(98,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 0, 'C'); //dados
		$this->Cell(57,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 1, 'C'); //dados

		$this->Cell(98,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 0, 'C'); //dados
		$this->Cell(57,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 1, 'C'); //dados

		$this->Cell(98,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 0, 'C'); //dados
		$this->Cell(57,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 1, 'C'); //dados

		$this->Cell(98,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 0, 'C'); //dados
		$this->Cell(57,5,'', 0, 0, 'L');
		$this->Cell(10,4,'-', 0, 0, 'C'); //dados
		$this->Cell(10,4,'++', 0, 1, 'C'); //dados

		$this->Ln(39.5);
		// Aerosois
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// bebidas
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// capsulas
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// ceras
		$this->Cell(110,5,'', 0, 0, 'L');
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 1, 'C'); //dados

		// colutorios
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// cosmeticos
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// cremes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// cremes cond
		$this->Cell(110,5,'', 0, 0, 'L');
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 1, 'C'); //dados

		// desodorantes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// emulsoes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// esmalte
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// fortificantes
		$this->Cell(110,5,'', 0, 0, 'L');
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 1, 'C'); //dados

		// geis
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// gomas
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// lentes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// loções
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// loções cremo
		$this->Cell(110,5,'', 0, 0, 'L');
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 1, 'C'); //dados

		// lentes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// material dentario
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// medicamentos
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// mucilagens
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// oleos secantes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// pastas de dente
		$this->Cell(110,5,'', 0, 0, 'L');
		$this->Cell(8,5,'*',0 , 0, 'C'); //dados
		$this->Cell(8,5,'*',0 , 0, 'C'); //dados
		$this->Cell(9,5,'*',0 , 0, 'C'); //dados
		$this->Cell(8,5,'*',0 , 0, 'C'); //dados
		$this->Cell(9,5,'*',0 , 0, 'C'); //dados
		$this->Cell(9,5,'*',0 , 0, 'C'); //dados
		$this->Cell(9,5,'*',0 , 0, 'C'); //dados
		$this->Cell(8,5,'*',0 , 0, 'C'); //dados
		$this->Cell(8,5,'*',0 , 0, 'C'); //dados
		$this->Cell(8,5,'*',0 , 1, 'C'); //dados

		// lentes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// lentes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// lentes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// lentes
		$this->Cell(110,5,'', 0, 0, 'L');
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(9,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 0, 'C'); //dados
		$this->Cell(8,5,'*', 0, 1, 'C'); //dados

		// lentes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// lentes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// lentes
		$this->Cell(110,4,'', 0, 0, 'L');
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(9,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 0, 'C'); //dados
		$this->Cell(8,4,'*', 0, 1, 'C'); //dados

		// lentes
		$this->Cell(110,8,'', 0, 0, 'L');
		$this->Cell(8,8,'*', 0, 0, 'C'); //dados
		$this->Cell(8,8,'*', 0, 0, 'C'); //dados
		$this->Cell(9,8,'*', 0, 0, 'C'); //dados
		$this->Cell(8,8,'*', 0, 0, 'C'); //dados
		$this->Cell(9,8,'*', 0, 0, 'C'); //dados
		$this->Cell(9,8,'*', 0, 0, 'C'); //dados
		$this->Cell(9,8,'*', 0, 0, 'C'); //dados
		$this->Cell(8,8,'*', 0, 0, 'C'); //dados
		$this->Cell(8,8,'*', 0, 0, 'C'); //dados
		$this->Cell(8,8,'*', 0, 1, 'C'); //dados
	}

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->tc_cosmeticos($_SESSION['editaPessoaClienteDados']);
$pdf->Output("tc-cosmeticos.pdf", "I");
?>
