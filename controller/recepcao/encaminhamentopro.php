<?php

$tipoForm = _INPUT('tipoForm','string','post');

if($tipoForm == "busca_membros") {

	$matricula = _INPUT('matricula','string','post'); //essa é a matricula mesmo. Não é o id do Cliente
	$idcliente = clienteId($matricula);
//	$tipo = buscaTipoCliente($matricula);
//	if($tipo == "titular") {

		$dependentes = buscaDependentesPorTitular($idcliente);

		foreach($dependentes as $dependente ) {
			$dif = diferenca_data($dependente['data_nascimento'],date('Y-m-d'));

			if($dif['ano'] == 0) {
				$dependente['idade'] = $dif['mes']."m";
			} else if($dif['mes'] == 0) {
				$dependente['idade'] = $dif['ano']."a";
			} else {
				$dependente['idade'] = $dif['ano']."a ".$dif['mes']."m";
			}

			$arr[] = $dependente;
		}
/*
	} else if($tipo == "dependente") {

		$titular = buscaTitularPorDependente($matricula);
		$dif = diferenca_data($dependente['data_nascimento'],date('Y-m-d'));

		$arr[0] = $titular;
		$arr[0]['idade'] = $dif['ano']."a ".$dif['mes']."m ".$dif['dia']."d";

		$dependentes = buscaDependentesPorTitular($titular['id']);

		foreach($dependentes as $dependente) {
			$dif = diferenca_data($dependente['data_nascimento'],date('Y-m-d'));
			$dependente['idade'] = $dif['ano']."a ".$dif['mes']."m ".$dif['dia']."d";
			$arr[] = $dependente;
		}

	}
 */
	echo saidaJson($arr);

} else {
	if(isset($_POST['flag'])) {
		$dependentes = buscaDependentesPorTitular($_POST['idTitular']);
		
		foreach($dependentes as $dependente ) {
			$dif = diferenca_data($dependente['data_nascimento'],date('Y-m-d'));
			//print_r($dif);
			echo "
			<tr name='table-color' class='dayhead '>
				<th align='center'> ".$dependente['cliente_id']." </th>
				<th align='center'> ".$dependente['nome']." </th>
				<th align='center'> ".$dif['ano']."a ".$dif['mes']."m ".$dif['dia']."d </th>
			</tr>
			";
			
		}
	} else {
		include('view/recepcao/encaminhamentopro.phtml');
	}
}
