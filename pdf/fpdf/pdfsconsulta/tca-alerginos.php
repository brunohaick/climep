<?php

session_start();
require('pdf/fpdf/fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {

	function tca_alerginos() {

		$this->SetY("-1");
		$this->SetAutoPageBreak(1, 1);
                $dadostc = $_SESSION['testecutaneo']['testecutaneoAntigo'];
                
		$dados['nome'] = 'Marcus Vinicius de Oliveira Dias';

		$this->SetFont('Arial', '', 12);
		$this->AddPage();                
		$this->Image("pdf/fpdf/pdfsconsulta/tca-alerginos.png",0,0,210,298);

		$this->SetFont('Arial', 'U', 12);
		$this->Ln(31);
		$this->Cell(43,5,'', 0, 0, 'L');
		$this->Cell(156,5,utf8_decode(strtoupper($dadostc['nome'])), 0, 0, 'C');
		$this->SetFont('Arial', '', 10);
		$this->Ln(8);
		$this->Cell(42,5,'', 0, 0, 'L');
		$this->Cell(156,5,date('d/m/Y'), 0, 0, 'C');

		$this->Ln(30);
		
		// 1 linha gato/leite
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['gat'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['lte'], 0, 1, 'C'); //dados
		
		// 2 linha cao/trigo
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['cão'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['tgo'], 0, 1, 'C'); //dados
		
		// 3 linha poeira/aves
		$this->Cell(99,5,' ', 0, 0, 'L');
		$this->Cell(19,5,$dadostc['pó'], 0, 0, 'C'); //dados
		$this->Cell(53,5,' ', 0, 0, 'L');
		$this->Cell(21,5,$dadostc['ave'], 0, 1, 'C'); //dados
		
		// 4 linha fungo/bovino
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['fgs'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['can'], 0, 1, 'C'); //dados
		
		// 5 linha tabaco/suino
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['tab'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['cas'], 0, 1, 'C'); //dados
		
		// 6 linha batara/camarao
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['bar'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['cam'], 0, 1, 'C'); //dados
		
		// 7 linha penas/caranguejo
		$this->Cell(99,5,' ', 0, 0, 'L');
		$this->Cell(19,5,$dadostc['pen'], 0, 0, 'C'); //dados
		$this->Cell(53,5,' ', 0, 0, 'L');
		$this->Cell(21,5,$dadostc['car'], 0, 1, 'C'); //dados
		
		// 8 linha blomia/chocolate
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['btr'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['cho'], 0, 1, 'C'); //dados

		// 9 linha pteronissinus/ovo
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['dpt'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['ovo'], 0, 1, 'C'); //dados

		// 10 linha farinae/peixe
		$this->Cell(99,6,' ', 0, 0, 'L');
		$this->Cell(19,6,$dadostc['dfa'], 0, 0, 'C'); //dados
		$this->Cell(53,6,' ', 0, 0, 'L');
		$this->Cell(21,6,$dadostc['pxe'], 0, 1, 'C'); //dados

// tabela 2 

		$this->Ln(11.5);

		// 1 linha aspergillus fumigatus / pulga
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['afu'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['pga'], 0, 1, 'C'); //dados

		// 2 linha alternaria/alternata/mosquito
		$this->Cell(99,5,' ', 0, 0, 'L');
		$this->Cell(19,5,$dadostc['aal'], 0, 0, 'C'); //dados
		$this->Cell(53,5,' ', 0, 0, 'L');
		$this->Cell(21,5,$dadostc['mos'], 0, 1, 'C'); //dados

		// 3 linha candida albicans/culex
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['cal'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['cul'], 0, 1, 'C'); //dados

		// 4 linha ciadosporidum herbarum/formiga
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['che'], 0, 0, 'C'); //dados
		$this->Cell(53,4,' ', 0, 0, 'L');
		$this->Cell(21,4,$dadostc['for'], 0, 1, 'C'); //dados

		// 5 linha chaetomium globosum
		$this->Cell(99,5,' ', 0, 0, 'L');
		$this->Cell(19,5,$dadostc['cgl'], 0, 1, 'C'); //dados


		// 6 linha mucor mucedo
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['mmu'], 0, 1, 'C'); //dados


		// 7 linha penicillium notatum
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['pno'], 0, 1, 'C'); //dados


		// 8 linha pullularia puluilans
		$this->Cell(99,6,' ', 0, 0, 'L');
		$this->Cell(19,6,$dadostc['ppu'], 0, 1, 'C'); //dados


		$this->Ln(6.5);
                                
		// 1 linha cavalo
		$this->Cell(99,5,' ', 0, 0, 'L');
		$this->Cell(19,5,$dadostc['que'], 0, 1, 'C'); //dados


		// 2 linha porco
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['suí'], 0, 1, 'C'); //dados


		// 3 linha cabra
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['cap'], 0, 1, 'C'); //dados


		// 4 linha boi
		$this->Cell(99,5,' ', 0, 0, 'L');
		$this->Cell(19,5,$dadostc['bov'], 0, 1, 'C'); //dados

		// 5 linha ovelha
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['ovi'], 0, 1, 'C'); //dados

		// 6 linha coelho
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['coe'], 0, 1, 'C'); //dados

		// 7 linha gato
		$this->Cell(99,4,' ', 0, 0, 'L');
		$this->Cell(19,4,$dadostc['gat2'], 0, 1, 'C'); //dados

		// 8 linha cao
		$this->Cell(99,6,' ', 0, 0, 'L');
		$this->Cell(19,6,$dadostc['cão2'], 0, 1, 'C'); //dados
	}

}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(0, 0, 0);
$pdf->tca_alerginos();
$pdf->Output("testecutaneo.pdf", "I");
?>
