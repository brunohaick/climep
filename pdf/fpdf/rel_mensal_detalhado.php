<?php
session_start();
$_SESSION['gambit'] = 1 ;
require('fpdf.php');
require("../../bootstrap.php");
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
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
	
	}
	function StyleRed($param1,$param2,$param3,$param4,$param5,$param6,$param7) {
		$this->SetTextColor(255,0,0);// texto do titulo header * 255 = branco 0 = preto*
		$this->Cell($param1,$param2,$param3,$param4,$param5,$param6,$param7);
		$this->SetTextColor(0);// texto do titulo header * 255 = branco 0 = preto*
	}
	function LegendColors(){
		$z = 10;
		$this->SetFillColor(126,192,238);
		$this->AfastaCell(64);
		$this->Cell(50,5,utf8_decode('Dia Trabalhado'),'0',0,'L',false);
		$this->Cell($z,5,'','0',0,'C',true);
		$this->Ln();
		$this->SetFillColor(139,131,120);
		$this->AfastaCell(64);
		$this->Cell(50,5,utf8_decode('Dia Opcional'),'0',0,'L',false);
		$this->Cell($z,5,'','0',0,'C',true);
		$this->Ln();
		$this->SetFillColor(255,193,193);
		$this->AfastaCell(64);
		$this->Cell(50,5,utf8_decode('Dia Não Trabalhado'),'0',0,'L',false);
		$this->Cell($z,5,'','0',0,'C',true);
		$this->Ln();
		$this->SetFillColor(255,215,0);
		$this->AfastaCell(64);
		$this->Cell(50,5,utf8_decode('Feriado ou Excessão'),'0',0,'L',false);
		$this->Cell($z,5,'','0',0,'C',true);
		$this->Ln();
	}
	function AfastaCell($espacamento){
		$this->Cell($espacamento,0,'','');
	}

	function FancyTable($header, $data1)
	{

		$w = array(30,43,43,40,40);
		$count = 0;
		foreach($data1 as $data) {
			if($count != 0) {
				$this->AddPage();
			}
			$count = 1;
			$mes_impresso = utf8_decode($data['calendario_final']['mes_impresso']);
			$this->MultiCell(0,5,utf8_decode($data['dados']['nome']),0,'C');
			$this->MultiCell(0,5,'',0,'L');
			$header_semana = array('dom','seg','ter','qua','qui','sex','sab');
### Calendario   ###
			$z = 10;
			$this->SetFillColor(238,233,233);// Cor de Fundo do titulo, 255,0,0 = vermelho, 0,48,108 = azul
			$this->AfastaCell(60);
			$this->Cell(70,7,utf8_decode($data['calendario_final']['mes_impresso']),1,0,'C',true);
			$this->Ln();
			$this->AfastaCell(60);
			for($i=0;$i<count($header_semana);$i++){
				$this->Cell($z,7,$header_semana[$i],1,0,'C',true);
			}
			$this->Ln();


			$var = 0;
			if (($data['calendario'][1]['dia_semana']) != 0 ){
					$this->AfastaCell(60);
					$dia_semana = ($data['calendario'][1]['dia_semana']);
					while($var != $dia_semana) {
						$this->Cell($z,5,'','LR',0,'C',true);
						$var++;
					}
			}
			foreach($data['calendario'] as $linha){
				$estilo = explode(",",$linha['estilo']);

				$this->SetFillColor($estilo[0],$estilo[1],$estilo[2]);
				if($linha['dia_semana'] == 6) {
					$this->Cell($z,5,$linha['dia'],'R',0,'C',true);
					$this->Ln();
				} else if ($linha['dia_semana'] == 0 ) {
					$this->AfastaCell(60);
					$this->Cell($z,5,$linha['dia'],'L',0,'C',true);
				} else {
					$this->Cell($z,5,$linha['dia'],'0',0,'C',true);
				}
				$dia_semana = $linha['dia_semana'];
			}
			while($dia_semana != 6){
				$this->SetFillColor(238,233,233);// Cor de Fundo do titulo, 255,0,0 = vermelho, 0,48,108 = azul
				$this->Cell($z,5,'','1',0,'C',true);
				$dia_semana++;
			}
			$this->Ln();
			$this->AfastaCell(60);
			$this->Cell(70,0,'','T');// linha final
			$this->Ln();
			$this->MultiCell(0,5,'',0,'L');
			$this->MultiCell(0,5,"Faltas : ".utf8_decode($data['calendario_final']['n_faltas']),0,'C');
			$this->MultiCell(0,5,"Justificativas : ".utf8_decode($data['calendario_final']['n_just']),0,'C');
			$this->MultiCell(0,5,'',0,'L');
			$this->LegendColors();


### Horarios     ###
			$this->MultiCell(0,5,'',0,'L');
			$this->CreateHeader($w,$header);
			$fill = false;
				$countLine = 0;
			foreach($data['horario'] as $row) {

				if(!empty($row['data_ponto']) || !empty($row['HoraEntrada']) || !empty($row['HoraSaida']) || !empty($row['TotalHoras']) || !empty($row['expedienteTotalHoras'])) {
	//			(!empty($row['data_ponto'])) ? $this->Cell($w[0],6,$row['data_ponto'],'L',0,'C',$fill) :'' ;
	//			(!empty($row['HoraEntrada'])) ? $this->Cell($w[1],6,$row['HoraEntrada'],'0',0,'C',$fill) :'' ;
	//			(!empty($row['HoraSaida'])) ? $this->Cell($w[2],6,$row['HoraSaida'],'0',0,'C',$fill) :'' ;
	//			(!empty($row['TotalHoras'])) ? $this->Cell($w[3],6,$row['TotalHoras'],'0',0,'C',$fill) :'' ;
	//			(!empty($row['expedienteTotalHoras'])) ? $this->Cell($w[4],6,$row['expedienteTotalHoras'],'R',0,'C',$fill) :'' ;
	//			(!empty($row['expedienteTotalHoras'])) ? $this->Ln() :'' ;
				$this->Cell($w[0],6,$row['data_ponto'],'L',0,'C',$fill);
				$this->Cell($w[1],6,$row['HoraEntrada'],'0',0,'C',$fill);
				$this->Cell($w[2],6,$row['HoraSaida'],'0',0,'C',$fill);
				$this->Cell($w[3],6,$row['TotalHoras'],'0',0,'C',$fill);
				$this->Cell($w[4],6,$row['expedienteTotalHoras'],'R',0,'C',$fill);
				$this->Ln();
				$fill = !$fill;
				}
				if($countLine == 26) {
				$this->Cell(array_sum($w),0,'','T');// linha final
				$this->Ln();
				$this->CreateHeader($w,$header);
				
				}
				$countLine++;
			}
			$this->Cell(array_sum($w),0,'','T');// linha final
			$this->Ln();

			$this->SetFillColor(0,48,108);// Cor de Fundo do titulo, 255,0,0 = vermelho, 0,48,108 = azul
			$this->SetTextColor(255);// texto do titulo header * 255 = branco 0 = preto*
			$this->SetLineWidth(.3);// largura da linha
			$this->SetFont('','B');// negrito
				$this->Cell(116,7,'Total : ',1,0,'C',true);
//				$this->Cell(93,7,'Total : ',1,0,'C',true);
			$this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('');

//			$this->Cell(13,7,'','T',0,'C',false);
			$this->Cell(40,7,$data['dados']['HorasTrabalhadas'],'TB',0,'C',false);
			$this->Cell(40,7,$data['dados']['HorasTrabalhadasExpediente'],'TBR',0,'C',false);
			$this->Ln();

			$this->SetFillColor(0,48,108);// Cor de Fundo do titulo, 255,0,0 = vermelho, 0,48,108 = azul
			$this->SetTextColor(255);// texto do titulo header * 255 = branco 0 = preto*
			$this->SetLineWidth(.3);// largura da linha
			$this->SetFont('','B');// negrito
			$header_max_min = array('Qnt Max hrs trabalhadas no periodo','Qnt Min hrs trabalhadas no periodo');
			for($i=0;$i<count($header_max_min);$i++){
				$this->Cell(98,7,$header_max_min[$i],1,0,'C',true);
			}
			$this->Ln();
			$this->SetFillColor(224,235,255);
			$this->SetTextColor(0);
			$this->SetFont('');
			$this->Cell(98,7,$data['dados']['maxHorasTrabalhadas'],1,0,'C',false);
			$this->Cell(98,7,$data['dados']['minHorasTrabalhadas'],1,0,'C',false);
			$this->Ln();
			
		}
	}
}
$data_atual = date('Y_m_d');
$mes = date('m');
$ano = date('Y');
$dados = Array_Rel_Mensal_detalhado();
$pdf = new PDF();
$mesAnt = ($mes - 1 > 0) ? $meses[$mes-1] : $meses[12+($mes-1)];
$mesAnt2 = ($mes - 2 > 0) ? $meses[$mes-2] : $meses[12+($mes-2)];
$header = array('Data', 'Hora E/S Manha', 'Hora E/S Tarde','Trabalhado','Expediente');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->FancyTable($header,$dados);
($_SESSION['previous_date']['mes'] != 'Todos') ? $pdf->Output("RelDetalhado-".$data_atual."-".$meses[$_SESSION['previous_date']['mes'] - 0].".pdf","I") : $pdf->Output("RelDetalhado-".$data_atual."-Ano-".$_SESSION['previous_date']['ano'].".pdf","I");
