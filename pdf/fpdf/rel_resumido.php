<?php
require('fpdf.php');
class PDF extends FPDF
{
	function LoadData($file)
	{
		$lines = file($file);
		$data = array();
		foreach($lines as $line)
			$data[] = explode(';',trim($line));
		return $data;
	}

	function CreateHeader($w,$header){

		$this->SetFillColor(0,48,108);// Cor de Fundo do titulo, 255,0,0 = vermelho, 0,48,108 = azul
		$this->SetTextColor(255);// texto do titulo header * 255 = branco 0 = preto*
		$this->SetDrawColor(0);// cor dar borda. 128.0.0 => vermelho mais escuro.
		$this->SetLineWidth(.3);// largura da linha
		$this->SetFont('','B');// negrito
		for($i=0;$i<count($header);$i++){
			$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
		}
		$this->Ln();
 ######  Essas Linhas Abaixo Servem Para o Estilo de Escrita na Tabela #######
		$this->SetDrawColor(0,48,108);// cor dar borda. 128.0.0 => vermelho mais escuro.
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
	
	}
	function StyleRed($param1,$param2,$param3,$param4,$param5,$param6,$param7) {
		$this->SetTextColor(255,0,0);// texto do titulo header * 255 = branco 0 = preto*
		$this->Cell($param1,$param2,$param3,$param4,$param5,$param6,$param7);
		$this->SetTextColor(0);// texto do titulo header * 255 = branco 0 = preto*
	}

	function FancyTable($header, $data)
	{

		$data = array_reverse($data);
		$w = array(15,15,15,15,15,15);
//		$w = array(35,10,80,28,28,28,20);
		$this->CreateHeader($w,$header);

//		$fill = false;
//			$j = 1;
//		foreach($data as $row)
//		{
//			$this->Cell($w[0],6,$row['matricula'],'L',0,'C',$fill);// LR borda na esquerda e direita
//			$this->Cell($w[1],6,$row['tipo'],'0',0,'C',$fill);
//			$this->Cell($w[2],6,utf8_decode($row['nome']),'0',0,'C',$fill);//$this->Cell($w[2],6,number_format($row[2]),'0',0,'R',$fill);
//			($row['MesPerc'] <= 50) ? $this->StyleRed($w[3],6,number_format($row['MesPerc'],2,',',' ')." %",'0',0,'C',$fill) : $this->Cell($w[3],6,number_format($row['MesPerc'],2,',',' ')." %",'0',0,'C',$fill);
//			$this->Cell($w[4],6,$row['MesAtrasoM'],'0',0,'C',$fill);
//			$this->Cell($w[5],6,$row['MesAtrasoT'],'0',0,'C',$fill);
//			($row['MesAntPerc'] <= 50) ? $this->StyleRed($w[6],6,number_format($row['MesAntPerc'],2,',',' ')." %",'0',0,'C',$fill) : $this->Cell($w[6],6,number_format($row['MesAntPerc'],2,',',' ')." %",'0',0,'C',$fill);
//			$this->Cell($w[7],6,$row['MesAntAtrasoM'],'0',0,'C',$fill);
//			$this->Cell($w[8],6,$row['MesAntAtrasoT'],'0',0,'C',$fill);
//			($row['2MesAntPerc'] <= 50) ? $this->StyleRed($w[9],6,number_format($row['2MesAntPerc'],2,',',' ')." %",'0',0,'C',$fill) : $this->Cell($w[9],6,number_format($row['2MesAntPerc'],2,',',' ')." %",'0',0,'C',$fill);
//			$this->Cell($w[10],6,$row['2MesAntAtrasoM'],'0',0,'C',$fill);
//			$this->Cell($w[11],6,$row['2MesAntAtrasoT'],'0',0,'C',$fill);
//			$this->Cell($w[12],6,$row['nota_final'],'R',0,'C',$fill);
//			$this->Ln();
//			$fill = !$fill;
//			if($j == 28 || $j == 57 && count($data) > 56){
//				$this->Cell(array_sum($w),0,'','T');// linha final
//				$this->Ln();
//				$this->CreateHeader($w,$header);
//				$j++;
//			}
//			$j++;
//		}
//		$this->Cell(array_sum($w),0,'','T');// linha final
	}
}
$pdf = new PDF("L");
$header = array('Controle','Matricula','Nome', 'Valor','Parcelas','Total');
//$data = $pdf->LoadData('PDF/fpdf/countries.txt');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->FancyTable($header,$tdsUsuarios);
$pdf->Output("teste.pdf","I");
