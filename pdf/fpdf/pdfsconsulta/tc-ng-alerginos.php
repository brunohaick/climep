<?php

session_start();
require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

	function tc_ng_alerginos() {

		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);
                $dadostc = $_SESSION['testecutaneo']['testecutaneoNovo'];

		$this->SetFont('Arial', '', 10);
		$this->AddPage();
		$this->Image("pdf/fpdf/pdfsconsulta/tc-ng-alerginos.png",0,0,210,298);

		$this->SetFont('Arial', 'U', 12);
		$this->Ln(35);
		$this->Cell(40,5,'', 0, 0, 'L');
		$this->Cell(156,5,utf8_decode(strtoupper($dadostc['nome'])), 0, 0, 'C');
		$this->SetFont('Arial', '', 10);
		$this->Ln(9);
		$this->Cell(40,5,'', 0, 0, 'L');
		$this->Cell(20,5,'', 0, 0, 'L');
		$this->Cell(30,5,date('d/m/Y'), 0, 0, 'L');
		$this->Cell(28,5,'', 0, 0, 'L');
		$this->Cell(30,5,date('d/m/Y'), 0, 0, 'L');// dia +2
		$this->Cell(24,5,'', 0, 0, 'L');
		$this->Cell(30,5,date('d/m/Y'), 0, 0, 'L'); //dia +4

		$this->Ln(33);
		// 1 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['ant'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['an'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['mer'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['me'], 0, 1, 'C'); //dados

		// 2 linha
		$this->Cell(43,5,'', 0, 0, 'L');
		$this->Cell(57,5,'', 0, 0, 'L');
		$this->Cell(9,5,$dadostc['bal'], 0, 0, 'C'); //dados
		$this->Cell(9,5,$dadostc['ba'], 0, 0, 'C');
		$this->Cell(53,5,'', 0, 0, 'L');
		$this->Cell(10,5,$dadostc['ben'], 0, 0, 'C'); //dados
		$this->Cell(10,5,$dadostc['be'], 0, 1, 'C'); //dados

		// 3 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['ppd'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['pp'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['qar'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['qa'], 0, 1, 'C'); //dados


		// 4 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['hid'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['hi'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['qui'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['qu'], 0, 1, 'C'); //dados

		// 5 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['bik'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['bi'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['nit'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['ni'], 0, 1, 'C'); //dados

		// 6 linha
		$this->Cell(43,5,'', 0, 0, 'L');
		$this->Cell(57,5,'', 0, 0, 'L');
		$this->Cell(9,5,$dadostc['plg'], 0, 0, 'C'); //dados
		$this->Cell(9,5,$dadostc['pl'], 0, 0, 'C');
		$this->Cell(53,5,'', 0, 0, 'L');
		$this->Cell(10,5,$dadostc['prb'], 0, 0, 'C'); //dados
		$this->Cell(10,5,$dadostc['pr'], 0, 1, 'C'); //dados

		// 7 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['but'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['bu'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['res'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['re'], 0, 1, 'C'); //dados


		// 8 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['neo'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['ne'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['thi'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['th'], 0, 1, 'C'); //dados

		// 9 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['irg'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['ir'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['tbt'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['tb'], 0, 1, 'C'); //dados

		// 10linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['kat'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['ka'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['cab'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['ca'], 0, 1, 'C'); //dados

		// 11linha
		$this->Cell(43,5,'', 0, 0, 'L');
		$this->Cell(57,5,'', 0, 0, 'L');
		$this->Cell(9,5,$dadostc['cbz'], 0, 0, 'C'); //dados
		$this->Cell(9,5,$dadostc['cb'], 0, 0, 'C');
		$this->Cell(53,5,'', 0, 0, 'L');
		$this->Cell(10,5,$dadostc['pmz'], 0, 0, 'C'); //dados
		$this->Cell(10,5,$dadostc['pm'], 0, 1, 'C'); //dados

		// 12 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['lan'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['la'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['sfn'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['sf'], 0, 1, 'C'); //dados

		// 13 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['tiu'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['ti'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['clf'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['cl'], 0, 1, 'C'); //dados

		// 14 linha
		$this->Cell(43,4,'', 0, 0, 'L');
		$this->Cell(57,4,'', 0, 0, 'L');
		$this->Cell(9,4,$dadostc['eti'], 0, 0, 'C'); //dados
		$this->Cell(9,4,$dadostc['et'], 0, 0, 'C');
		$this->Cell(53,4,'', 0, 0, 'L');
		$this->Cell(10,4,$dadostc['pfd'], 0, 0, 'C'); //dados
		$this->Cell(10,4,$dadostc['pf'], 0, 1, 'C'); //dados

		// 15 linha
		$this->Cell(43,6,'', 0, 0, 'L');
		$this->Cell(57,6,'', 0, 0, 'L');
		$this->Cell(9,6,$dadostc['per'], 0, 0, 'C'); //dados
		$this->Cell(9,6,$dadostc['pe'], 0, 0, 'C');
		$this->Cell(53,6,'', 0, 0, 'L');
		$this->Cell(10,6,$dadostc['fmd'], 0, 0, 'C'); //dados
		$this->Cell(10,6,$dadostc['fm'], 0, 1, 'C'); //dados
	}

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->tc_ng_alerginos();
$pdf->Output("testecutaneo.pdf", "I");
?>
