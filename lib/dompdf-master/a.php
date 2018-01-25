<?php

$html = "";

$strhtml = '
<html>
	<head>
		<meta charset="utf-8">
	</head>
<body>
<table border="2">
	<tr>
		<td >
			<h4><b>Anotações de Vacinações e outras Medidas Preventivas</b></h4>
		</td>
		<td> 
			<div height="70px" width="150px">
				<img src="climep1.png" height="90px" width="150px">
			</div>
		</td>
	</tr>
</table>
';







$strhtml .= '
</body>
</html>
';
require_once("./dompdf_config.inc.php");

$dompdf = new DOMPDF();
$dompdf->load_html($strhtml);
$dompdf->set_paper('a4', 'Portrait');
$dompdf->render();
$dompdf->stream("exemplo-01.pdf", array("Attachment" => 0));

