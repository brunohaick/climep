<?php
//session_start();
require('fpdf.php');
require('Bootstrap.php');

class PDF extends FPDF {
	function Certificado_Header($language,$dados) {
		$cliente = $_SESSION['certificado']['cliente'];
		$titular = $_SESSION['certificado']['titular'];
		$matricula = $titular['matricula']."-".$cliente['membro'];

		$data['pt']['titulo'] = "CERTIFICADO DE VACINAÇÃO";
		$data['pt']['texto'] = "Certifico a quem interessar que os informes abaixo transcritos constituem-se um resumo das anotações arquivadas no Serviço de Imunização desta Clínica, correspondendo a procedimentos administrativos à pessoa abaixo identificada :";
		$data['pt']['nome'] = "Nome:";
		$data['pt']['sexo'] = "Sexo:";
		$data['pt']['nascimento'] = "Nascimento:";
		$data['pt']['matricula'] = "Matrícula:";
		$data['pt']['matricula_tam'] = "20";
		$data['pt']['endereco'] = "Endereço:";
		$data['pt']['data_nascimento'] = $cliente['data_nascimento'];

		$data['en']['titulo'] = "CERTIFICATE OF VACCINATION";
		$data['en']['texto'] = "I Certify to whom it may concern that the information present in this certificate is a summary of the immunization services perfomed in this Clinic. The immunization records are listed below:";
		$data['en']['nome'] = "Name:";
		$data['en']['sexo'] = "Sex:";
		$data['en']['nascimento'] = "Birth Date:";
		$data['en']['matricula'] = "Registration Number:";
		$data['en']['matricula_tam'] = "38";
		$data['en']['endereco'] = "Address:";
		$data['en']['data_nascimento'] = $cliente['data_nascimento_en'];

		$dados['nome'] = $cliente['nome']." ".$cliente['sobrenome'];
		$dados['sexo'] = $cliente['sexo'];
		$dados['nascimento'] = $cliente['data_nascimento'];
		$dados['matricula'] = $matricula;
		$dados['endereco'] = $titular['endereco'].", ".$titular['numero'].", ".$titular['cidade'].", ".$titular['estado'];

		$this->SetFont('Arial','B',12);
		$this->MultiCell(0,5,utf8_decode($data[$language]['titulo']),0,'C');
		$this->Ln(5);
		$this->SetFont('Arial','',10);
		$this->MultiCell(0,5,utf8_decode($data[$language]['texto']),0,'J');
		$this->Ln(3);

		$this->Cell(15,5,utf8_decode($data[$language]['nome']),0,0,'J');
		$this->SetFont('Arial','B',10);
		$this->Cell(0,5,strtoupper(utf8_decode($dados['nome'])),0,0,'J');
		$this->Ln(7);

		$this->SetFont('Arial','',10);
		$this->Cell(13,5,utf8_decode($data[$language]['sexo']),0,0,'J');
		$this->SetFont('Arial','B',10);
		$this->Cell(40,5,strtoupper(utf8_decode($dados['sexo'])),0,0,'J');

		$this->SetFont('Arial','',10);
		$this->Cell(24,5,utf8_decode($data[$language]['nascimento']),0,0,'J');
		$this->SetFont('Arial','B',10);
		$this->Cell(35,5,strtoupper(utf8_decode($data[$language]['data_nascimento'])),0,0,'J');

		$this->SetFont('Arial','',10);
		$this->Cell($data[$language]['matricula_tam'],5,utf8_decode($data[$language]['matricula']),0,0,'J');
		$this->SetFont('Arial','B',10);
		$this->Cell(40,5,strtoupper(utf8_decode($dados['matricula'])),0,0,'J');
		$this->Ln(7);

		$this->SetFont('Arial','',10);
		$this->Cell(20,5,utf8_decode($data[$language]['endereco']),0,0,'J');
		$this->SetFont('Arial','B',10);
		$this->Cell(0,5,strtoupper(utf8_decode($dados['endereco'])),0,0,'J');
		$this->Ln(7);

	}

	function Certificado_Dados($language,$dados) {

		$vacinas = $_SESSION['certificado']['vacinas'];

		$data['pt']['thead_1'] = "Vacinação Realizada - Lote"; 
		$data['en']['thead_1'] = "Vaccination - Lot";

		$data['pt']['thead_2'] = "dd/mm/aa"; 
		$data['en']['thead_2'] = "mm/dd/yy";

		$this->SetFont('Arial','B',11);
		$this->Cell(53,4,utf8_decode($data[$language]['thead_1']),1,0,'J');
		$this->Cell(30,4,utf8_decode($data[$language]['thead_2']),1,0,'C');
		$this->Cell(4,4,'',0,0,'J');
		$this->Cell(53,4,utf8_decode($data[$language]['thead_1']),1,0,'J');
		$this->Cell(30,4,utf8_decode($data[$language]['thead_2']),1,0,'C');
		$this->Ln(4);

		for($i = 0; $i < count($vacinas); $i++) {

			if($language == 'pt') {
				$nome = $vacinas[$i]['vacinaNome'];
				$nomeVac = $vacinas[$i]['data_prevista'];
				if($vacinas[$i]['status_nome'] == 'Externo')
					$nome .= " (*)";
			} else if($language == 'en'){
				$nomeVac = $vacinas[$i]['data_prevista_en'];
				if(!empty($vacinas[$i]['descricao_ingles']))
					$nome = $vacinas[$i]['descricao_ingles'];
				else
					$nome = $vacinas[$i]['vacinaNome'];

				if($vacinas[$i]['status_nome'] == 'Externo')
					$nome .= " (*)";
			}

			$dados[$i]['vacina'] = $nome;
			$dados[$i]['data'] = $nomeVac;

		}

		$this->SetFont('Arial','',8.5);
		$i = 0;
		foreach($dados as $vacina) {
			$i++;
			if($i%2 != 0) {

				$this->Cell(53,3.4,utf8_decode($vacina['vacina']),1,0,'J');
				$this->Cell(30,3.4,utf8_decode($vacina['data']),1,0,'C');
				$this->Cell(4,3.4,'',0,0,'J');
			} else {

				$this->Cell(53,3.4,utf8_decode($vacina['vacina']),1,0,'J');
				$this->Cell(30,3.4,utf8_decode($vacina['data']),1,0,'C');
				$this->Ln(3.4);
			}

		}
		if($i%2 != 0) {
			$this->Cell(53,3.4,'',1,0,'J');
			$this->Cell(30,3.4,'',1,0,'C');
		}
		$this->Ln(8);

	}

	function Certificado_Footer($language) {

		$data['pt']['anotacao'] = "Anotação:"; 
		$data['en']['anotacao'] = "Notes:";

		$data['pt']['anotacao_texto'] = " (*) Vacinações realizadas em outras instituição,conforme documentação apresentada pelo cliente"; 
		$data['en']['anotacao_texto'] = " (*) Vaccines received in external institutions, according to the client.";

		$data['pt']['texto_1'] = "Este certificado tem validade em todo o Território Nacional, de acordo com a Lei nº 6.259 de 30 de outubro de 1975, regulamentada pelo Decreto nº 78.231 de 12 de agosto de 1976. Portaria Conjunta ANVISA/FUNASA nº 01 em 02 de agosto de 2000 e Portaria nº 597 do Ministério da Saúde de 08 de abril de 2004. Tendo sua validade em todo o território nacional.";
		$data['pt']['texto_2'] = "A CLIMEP está designada como centro de vacinação de febre amarela conforme o Anexo 7 do regulamento Sanitário Internacional de 2005. Resolução RDC21 de 28mar08, anexo II, art 1 , § único. http://anvisa.gov.br/paf/vacinacao/servico_vacina_privado.pdf.";
		$data['pt']['texto_3'] = "CLIMEP - Clínica de Medicina Preventiva do Pará S/C Ltda. Licença Sanitária 0633/2012 (código 8630-5/06 Serviços de Vacinação e Sanitária, Imunização Humanas) processo 2458/2013-DVSCEP, Prefeitura Municipal de Belém, SESMA, Departamento de Vigilância SIVISA, Sistema de Informação em Vigilância Sanitária, emitido em 25abr2013. Certificado de Inscrição 2.1-PA-102-03 de 22jan1986 no Conselho Regional de Medicina do Estado do Pará emitido em 21jan2013.";

		$data['en']['texto_1'] = "This certificate has validity in the whole Brazilian Territory, in accordance with de Law Lei nº 6.259 of 30 of October of 1975, regulated by the Decreeo nº 78.231 of 12 de August of 1976. Joint Regulation by ANVISA/FUNASA nº 01 of 02 of August of 2000, and Regulation nº 597 of the Brazilian Health Department, of 08 of April of 2004.";
		$data['en']['texto_2'] = "The CLIMEP is assigned as a yellow fever center of internacional vaccination of yellow fever as Annex 7 of 2005 International Sanitary Regulation and ANVISA Brazil Resolution RDC 21 of 28mar08, Annex II, first art, single §. http://anvisa.gov.br/paf/vacinacao/servico_vacina_privado.pdf.";
		$data['en']['texto_3'] = "CLIMEP - Clínica de Medicina Preventiva do Pará S/C Ltda. Sanitary Permit 0633/2012 (code 8630-5/06 - Human Immunization and Vaccination Services) process 2458/2013-DVSCEP, Belém City Hall, SESMA, Sanitary Watch Department, SIVISA - Sanitary Watch Information System Brazil, issued in 25apr2013. Registration Certificate 2.1-PA-102-03 de 22jan1986 in the State of Pará Regional Medical Council issued in 21jan2013.";

		$dia = date('d');
		$mes = mostraMes(date('m'));
		$ano = date('Y');
		$diadasemana = mostraSemana(date('w'));

		$data['pt']['data'] = "Belém, $diadasemana, $dia de $mes de $ano";
		$data['en']['data'] = date('m/d/Y'); 

		$data['pt']['carimbo'] = "Carimbo e Assinatura"; 
		$data['en']['carimbo'] = "Physician's Stamp and Signature";

		$this->SetFont('Arial','',10);
		$this->Cell(0,4,utf8_decode($data[$language]['anotacao']),1,1,'J');
		$this->SetFont('Arial','',8);
		$this->Cell(0,4,utf8_decode($data[$language]['anotacao_texto']),'LR',1,'J');
		$this->Cell(0,4,'','LRB',1,'J');

		$this->SetFont('Arial','',8);
		$this->Ln(3);
		$this->MultiCell(0,3,utf8_decode($data[$language]['texto_1']),0,'J');
		$this->Ln(3);
		$this->MultiCell(0,3,utf8_decode($data[$language]['texto_2']),0,'J');
		$this->Ln(3);
		$this->MultiCell(0,3,utf8_decode($data[$language]['texto_3']),0,'J');
		$this->Ln(3);

		$this->SetFont('Arial','',10);
		$this->MultiCell(0,5,utf8_decode($data[$language]['data']),0,'C');
		$this->Ln(4);

		$this->Cell(48,15,'',0,0);
		$this->Cell(75,5,utf8_decode($data[$language]['carimbo']),'TLR',1,'C');
		$this->Cell(48,15,'',0,0);
		$this->Cell(75,15,'','LRB',1,'J');

	}

}

$pdf = new PDF();
// Column headings
$header = array('Controle','Matricula','Nome','FP', 'Valor','Parcelas','Data','Total');
// Data loading
//$dados = $_SESSION['PDF']['resumoCaixa'];
$dados = ResumoCaixaPDF();
$pdf->SetFont('Arial','',10);
$pdf->SetMargins(20,20,20);
$pdf->AddPage();
$language = $_SESSION['certificado']['language'];
$dados = Array();
$pdf->Certificado_Header($language,$dados);
$pdf->Certificado_Dados($language,$dados);
$pdf->Certificado_Footer($language);
$pdf->Output("CertificadoVacinacao.pdf","I");
unset($_SESSION['certificado']);
?>
