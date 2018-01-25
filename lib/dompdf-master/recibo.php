<?php
//$programadas = $_SESSION['fichavacina']['vacinasprogramadas'];
//$cliente = $_SESSION['fichavacina']['cliente'];
//
//$nome = $cliente['nome'];
//$matricula = $cliente['matricula'];
$hoje = date('d/m/Y');

$html = "";

//foreach($programadas as $prog) {
//
//	$nomeVac = $prog['vacinaNome'];
//	$data = $prog['data_prevista'];
//	$html .=" <tr>
//		<td class='texto5'>
//			<h6>$nomeVac</h6>
//		</td>
//		<td class='texto5'>
//			<h6>$data</h6>
//		</td>
//	</tr>";
//}

//#header { position: fixed; top: 5px; right: 0px; height: 150px; color: black;}
$strhtml = "
<html>
	<head>
		<meta charset='utf-8'>
		<link href='css/bootstrap.css' rel='stylesheet'>
		<link href='css/pdfs.css' rel='stylesheet'>
	</head>
	<style>
		table.vaci-vacinas { margin-top: 10px; margin-left: 20px; margin-right:20px; border-collapse: collapse; }
		table.vaci-vacinas thead tr th,table.vaci-vacinas tbody tr td { border: 1px solid #000000; font-size: 6px; text-align: center; color: black; }
		table.vaci-vacinas th { background-color: #0088CC; width: 52.3px; height: 15px;}
		table.vaci-vacinas td { background-color: white; height: 25px;}

		table.tabela { border-collapse: collapse; width:710px}
		table.tabela td.texto { height: 20px; width: 410px;}
		table.tabela td.imagem { height: 45px; width: 135px;}

		imagem4 { height: 45px; width: 155px;}

		table.tabela2 { border:0px; border-collapse: collapse; width:400px}
		table.tabela2 td.datatexto { height: 10px; width: 320px;}
		table.tabela2 td.datadata { height: 10px; width: 60px;}
		table.tabela2 td.texto { height: 35px; width: 320px;}
		table.tabela2 td.matricula { height: 35px; width: 60px; text-align: right}

		table.tabela3 { border-collapse: collapse; width:800px}
		table.tabela3 td.texto3 { height: 30px; width: 425px;}
		table.tabela3 td.imagem3 { height: 45px; width: 120px;}

		table.program { border-collapse: collapse; width:200px; height:130px}
		table.program td.texto5 { width: 120px;}

		table.imagemtopo { border-collapse: collapse; width:510px; height:55px}
		table.imagemtopo td.texto5 { width: 20px;}

		.teste { margin-left: 20px; margin-right: 20px; margin-top: 10px; }
		.teste2 { margin-left: -10px; margin-right: -10px; margin-top: -10px; }

		.paragrafo {font-size: 8px; line-height:8px}

		.page-header {
			margin-left: 30px;
			border-top:2px solid #000000;
			border-bottom:2px solid #000000;
			border-left:  2px solid #000000;
			border-right: 2px solid #000000;
		}

		.page-carteirinha {
			margin-left: 10px;
			border:solid 2px #000000;
			-moz-border-radius-topleft: 39px;
			-moz-border-radius-topright:0px;
			-moz-border-radius-bottomleft:0px;
			-moz-border-radius-bottomright:39px;
			-webkit-border-top-left-radius:39px;
			-webkit-border-top-right-radius:0px;
			-webkit-border-bottom-left-radius:0px;
			-webkit-border-bottom-right-radius:39px;
			border-top-left-radius:39px;
			border-top-right-radius:0px;
			border-bottom-left-radius:0px;
			border-bottom-right-radius:39px;
		}
	</style>
<body>
";

$strhtml .= utf8_decode('
        <div id="header">
            <div class="row-fluid">
                <div class="span6" align="center">
                    <div class="page-carteirinha">
                        <div>
								<h3 align="center"><span> &nbsp; </span></h3>
								<div class="row-fluid"> 
									<img src="climep1.png" height="70px" width="150px">
								</div>
						</div>
                        <div>
                            <div class="row-fluid">
                                <div class="span2" >
                                </div>
                                <div class="span12" align="center">
                                    <h4 align="center"><span> &nbsp; </span></h4>
                                    <h3 align="center"><span>Anotações de Vacinação e outras</span></h3>
                                    <h3 align="center"><span>Medidas Preventivas</span></h3>
                                    <h5 align="center"><span>
');

$strhtml .= utf8_decode($nome);
$strhtml .= utf8_decode('
					</span></h5>
                                    <h6 align="center"><span>
');
$strhtml .= utf8_decode($matricula);
$strhtml .= utf8_decode('
</span></h6>
                                    <h4 align="center"><span> &nbsp; </span></h4>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span11">
                                    <div align="center" class="page-header">
                                        <div align="center">
                                                <h6 align="center" style="margin-top:5px">PROCEDIMENTOS AGENDADOS</h6>
						<table align="center" class="program">
');
$strhtml .= utf8_decode($html);
$strhtml .= utf8_decode('
 
						</table>
					</div>
                                    </div> 
                                </div>
                            </div>
                            <div class="row-fluid">
                                <h6 align="center"><span>Braz de Aguiar  410, Belém, PA  (91) 3181-1644 </span></h6>
                                <h6 align="center"><span>www.climep.com.br</span></h6>
                                <h6 align="center"><span> &nbsp; </span></h6>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div> 
		<div style="page-break-before: always;"></div>
');

$strhtml .= '
</body>
</html>
';
//echo $strhtml;
require_once("dompdf_config.inc.php"); 
$dompdf = new DOMPDF();
$dompdf->
$dompdf->load_html($strhtml);
$dompdf->set_paper('a4', 'Portrait');
$dompdf->render();
$dompdf->stream("exemplo-01.pdf", array("Attachment" => 0));

//$dompdf->stream("arquivo.pdf");
//$pdf = $dompdf->output();
//file_put_contents("arquivo.pdf", $pdf)